<?
session_start();

require_once "../../consts.php";
require_once "../../app_errors_handler.php";
require_once "../../connect.inc";
require_once "../../app_events_handler.php";
require_once "../../functions.php";
require_once "../../functions_mini.php";
require_once "../../functions_mini2.php";
require_once "../../include/other/ctypt.php";
require_once "../../consts_mail.php";
require_once "../mail/send_mail.php";

function logfile($status, $file, $line, $message = "")
{
 global $BUSINESS,$TXN_ID,$MC_GROSS,$MC_CURRENCY,$PAYER_EMAIL,$VERIFY_SIGN,$PAYMENT_DATE,
		$PAYMENT_STATUS,$PENDING_REASON,$REASON_CODE,$uid,$uname,$upass,$REMOTE_ADDR;
	$_SESSION["sess_PAYMENT_STATUS"] = $status;
	$ftype = "";
	if ($status == "normal") $ftype = "history";
	elseif ($status == "error") $ftype = "errors";
	log_file_payment($ftype,$file,$line,$message."BUSINESS(receiver_email)=$BUSINESS\nTXN_ID(transaction id)=$TXN_ID\nMC_GROSS(amount)=$MC_GROSS\n".
		"MC_CURRENCY(currency)=$MC_CURRENCY\nPAYER_EMAIL=$PAYER_EMAIL\n".
		"VERIFY_SIGN=$VERIFY_SIGN\nPAYMENT_DATE=$PAYMENT_DATE\nPAYMENT_STATUS=$PAYMENT_STATUS\nPENDING_REASON(see payment_info.txt)=$PENDING_REASON\n".
		"REASON_CODE(see payment_info.txt)=$REASON_CODE\nREMOTE_ADDR=$REMOTE_ADDR\n".
		"  DB_UID=$uid\n  DB_LOGIN=$uname\n");
	if ($status == "error") {
		trigger_error("File: $file Line:$line. $message"); exit;
	}
}

// read the post from PayPal system and add 'cmd'
$req = "cmd=_notify-validate";
foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}

// post back to PayPal system to validate
$header = "";
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
//$fpaypal = fsockopen ("www.paypal.com", 80, $errno, $errstr, 30); - old
//$fpaypal = fsockopen ("www.sandbox.paypal.com", 80, $errno, $errstr, 30);
$fpaypal = fsockopen('ssl://www.paypal.com', 443, $errno, $errstr, 30);

// assign posted variables to local variables
$BUSINESS = html_chars(data_addslashes(get_get_post_value("business","")));
$TXN_ID = html_chars(data_addslashes(get_get_post_value("txn_id","")));
$MC_GROSS = html_chars(data_addslashes(get_get_post_value("mc_gross","")));
$MC_CURRENCY = html_chars(data_addslashes(get_get_post_value("mc_currency","")));
$PAYER_EMAIL = html_chars(data_addslashes(get_get_post_value("payer_email","")));
$VERIFY_SIGN = html_chars(data_addslashes(get_get_post_value("verify_sign","")));
$PAYMENT_DATE = html_chars(data_addslashes(get_get_post_value("payment_date","")));
$CUSTOM = html_chars(data_addslashes(get_get_post_value("custom","")));
@list($uid,$uname,$tmp_tbl_id) = preg_split("/=;=/",Decrypt($CUSTOM));
$PAYMENT_STATUS = html_chars(data_addslashes(get_get_post_value("payment_status","")));
$PENDING_REASON = html_chars(data_addslashes(get_get_post_value("pending_reason","")));
$REASON_CODE = html_chars(data_addslashes(get_get_post_value("reason_code","")));
$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

doconnect();

if (!$fpaypal) {
	logfile("error",__FILE__,__LINE__,"PayPal payment sytem error: HTTP ERROR.\n");
}
else{
	fputs ($fpaypal, $header.$req);
	while (!feof($fpaypal)) {
		$res = fgets ($fpaypal, 1024);
		if (strcmp ($res, "VERIFIED") == 0) {

			get_global_settings();

			get_payment_settings();
			$paypal_info["PayPalAccount"] = $_SESSION["paymentsettings"]["paypal_email"];
			$paypal_info["PayPalmindeposit"] = $_SESSION["paymentsettings"]["paypal_mindeposit"];

			//Check payment data
			if ($BUSINESS != $paypal_info["PayPalAccount"]) logfile("error",__FILE__,__LINE__,"BUSINESS is not equal to PayPalAccount (BUSINESS=$BUSINESS,PayPalAccount=".$paypal_info["PayPalAccount"].").\n");

			if ($MC_GROSS < $paypal_info["PayPalmindeposit"]) logfile("error",__FILE__,__LINE__,"Payment amount lesser then \"Minimum deposit by PayPal\" (Payment amount = \"$MC_GROSS\", PayPalMinDeposit=\"".$paypal_info["PayPalmindeposit"]."\").\n");

			if ($MC_CURRENCY != $paypal_info["PayPalCurrency"]) logfile("error",__FILE__,__LINE__,"Payment currency is invalid (Payment currency: \"$MC_CURRENCY\").\n");
	
			if ($PAYMENT_STATUS != "Completed") logfile("error",__FILE__,__LINE__,"Incorrect PAYMENT_STATUS (PAYMENT_STATUS=$PAYMENT_STATUS).\n");
	
			if (($uid == "") || ($uname == "") || ($tmp_tbl_id == ""))	logfile("error",__FILE__,__LINE__,"The user information is incorrect.\n");

			//Check user	
			$qr_res = mysql_query("SELECT pass,email FROM ".$db_tables["users_advertiser"]." WHERE uid_adv='$uid' and email='$uname'") or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
			if (mysql_num_rows($qr_res) == 0) logfile("error",__FILE__,__LINE__,"Can not find user with UID=$uid and Login=$uname\n");
			$myrow = mysql_fetch_array($qr_res);
			$email = $myrow["email"];

			//Get payment data from tmp table
			$qr_res = mysql_query("SELECT * FROM ".$db_tables["payments_tmp_adv"]." WHERE pid='$tmp_tbl_id'") or logfile("error",__FILE__,__LINE__,__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
			if (mysql_num_rows($qr_res) == 0) logfile("error",__FILE__,__LINE__,"Can not find payment data: {$db_tables["payments_tmp_adv"]} pid='{$_SESSION["sess_tmp_tbl_id"]}'\n");
			$myrow = mysql_fetch_array($qr_res);
			$payinfo = $myrow;
			//Check uid
			if ($myrow["uid_adv"] != $uid) logfile("error",__FILE__,__LINE__,"User id is incorrect: tbl_uid={$myrow["uid_adv"]}, returned_uid={$uid}\n");
			//Check amount
			if ($myrow["amount"] < ($MC_GROSS-0.01)) logfile("error",__FILE__,__LINE__,"Amount is incorrect: tbl_amount={$myrow["amount"]}, returned_amount={$MC_GROSS}\n");

			//Check batchnum
			$qr_res = mysql_query("SELECT batchnum FROM ".$db_tables["payments_adv"]." WHERE batchnum='$TXN_ID'") or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
			if (mysql_num_rows($qr_res) > 0) logfile("error",__FILE__,__LINE__,"Such batchnum($TXN_ID) already present in database.\n");

			$ptf = array_flip($payment_types);	
			//Add info in payment table
			mysql_query("INSERT INTO ".$db_tables["payments_adv"]." VALUES(NULL,'$uid',NOW(),'$MC_GROSS',{$ptf["PayPal"]},'".
					$payinfo["payinfo"]."<payer_email>{$PAYER_EMAIL}</payer_email><verify_sign>{$VERIFY_SIGN}</verify_sign>','$TXN_ID',1)")
				or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
  		//Update user account
			mysql_query("UPDATE ".$db_tables["users_advertiser"]." SET balance=balance+{$MC_GROSS} WHERE uid_adv='$uid'") or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
			//Send event
			$event_array = array("event"=>"update", "source"=>"advertisers", "table"=>"users_advertiser", "ad_id"=>0);
			event_handler($event_array);
  		//Delete temp data
			mysql_query("DELETE FROM ".$db_tables["payments_tmp_adv"]." WHERE pid='$uid'") or logfile("error",__FILE__,__LINE__,__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
	
			logfile("normal",__FILE__,__LINE__);
	
			//----Send mail---->>
			$parse_values["{*payment_system*}"] = $payment_systems_array["paypal"];
			$parse_values["{*payment_info_text*}"] = "Payee account: $BUSINESS\n".
				"Payment amount: $MC_GROSS\nTXN_ID: $TXN_ID\nVERIFY_SIGN: $VERIFY_SIGN";
			$parse_values["{*payment_info_html*}"] = "Payee account: $BUSINESS<br>".
				"Payment amount: $MC_GROSS<br>TXN_ID: $TXN_ID<br>VERIFY_SIGN: $VERIFY_SIGN";
			$admin_email = get_admin_email_free();
			//----Send mail to member (Account Credited Notification Email to member)
			$subj	= get_mailsubject("credited_notification");
			$htmlmessage = get_email_file("credited_notification","html");
			$textmessage = get_email_file("credited_notification","txt");
			$attach_files = get_mail_attach("credited_notification");
			$subj = str_replace("{*site_title*}",$_SESSION["globsettings"]["site_title"],$subj);
			create_and_send_email($email,$admin_email,$subj,$htmlmessage,$textmessage,$attach_files);
		//----Send mail----<<
		//--------------
		}
		else if (strcmp ($res, "INVALID") == 0) {
			logfile("error",__FILE__,__LINE__,"PayPal payment sytem error: INVALID response.\n");
		}
	}
	fclose ($fpaypal);
}
?>