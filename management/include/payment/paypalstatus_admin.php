<?
//Set session name and id
if (isset($_POST["custom"]))
{
	$s = $i = "";
	$CUSTOM = htmlspecialchars($_POST["custom"]);
	@list($Pid,$s,$i) = preg_split("/=;=/",$CUSTOM);
	if (($s != "") && ($i != "")) {
		session_name($s); session_id($i);
	}
}

session_start();

require_once "../../consts.php";
require_once "../../connect.inc";
require_once "../../functions.php";
require_once "../../consts_mail.php";
require_once "../mail/send_mail.php";

doconnect();

get_global_settings();

function logfile($status, $file, $line, $message = "")
{
 global $BUSINESS,$TXN_ID,$MC_GROSS,$MC_CURRENCY,$PAYER_EMAIL,$VERIFY_SIGN,$PAYMENT_DATE,
		$PAYMENT_STATUS,$PENDING_REASON,$REASON_CODE,$REMOTE_ADDR,$SLINE;
	$_SESSION["sess_PAYMENT_STATUS"] = $status;
	$_SESSION["sess_PAYMENT_MESSAGE"] = $message;
	$ftype = "";
	if ($status == "normal") $ftype = "history";
	elseif ($status == "error") $ftype = "errors";
	log_file_payment($ftype,$file,$line,$message."BUSINESS(receiver_email)=$BUSINESS\nTXN_ID(transaction id)=$TXN_ID\nMC_GROSS(amount)=$MC_GROSS\n".
		"MC_CURRENCY(currency)=$MC_CURRENCY\nPAYER_EMAIL=$PAYER_EMAIL\n".
		"VERIFY_SIGN=$VERIFY_SIGN\nPAYMENT_DATE=$PAYMENT_DATE\nPAYMENT_STATUS=$PAYMENT_STATUS\nPENDING_REASON(see payment_info.txt)=$PENDING_REASON\n".
		"REASON_CODE(see payment_info.txt)=$REASON_CODE\nREMOTE_ADDR=$REMOTE_ADDR\n");
	header("Location: ../../index.php?".$SLINE); exit;
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
$fpaypal = fsockopen ("www.paypal.com", 80, $errno, $errstr, 30);

// assign posted variables to local variables
$BUSINESS = html_chars(data_addslashes(get_get_post_value("business","")));
$TXN_ID = html_chars(data_addslashes(get_get_post_value("txn_id","")));
$MC_GROSS = html_chars(data_addslashes(get_get_post_value("mc_gross","")));
$MC_CURRENCY = html_chars(data_addslashes(get_get_post_value("mc_currency","")));
$PAYER_EMAIL = html_chars(data_addslashes(get_get_post_value("payer_email","")));
$VERIFY_SIGN = html_chars(data_addslashes(get_get_post_value("verify_sign","")));
$PAYMENT_DATE = html_chars(data_addslashes(get_get_post_value("payment_date","")));
$PAYMENT_STATUS = html_chars(data_addslashes(get_get_post_value("payment_status","")));
$PENDING_REASON = html_chars(data_addslashes(get_get_post_value("pending_reason","")));
$REASON_CODE = html_chars(data_addslashes(get_get_post_value("reason_code","")));
$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

if (!$fpaypal) {
	logfile("error",__FILE__,__LINE__,"HTTP ERROR.\n");
}
else{
	fputs ($fpaypal, $header.$req);
	while (!feof($fpaypal)) {
		$res = fgets ($fpaypal, 1024);
		if (strcmp ($res, "VERIFIED") == 0) {
		//--------------
			$qr_res = mysql_query("SELECT paypal_email,paypal_mindeposit FROM ".$db_tables["paymentsettings"]) or logfile("error",__FILE__,__LINE__,__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
			$myrow = mysql_fetch_array($qr_res);
			$paypal_info["PayPalAccount"] = $myrow["paypal_email"];

			if ($PAYER_EMAIL != $paypal_info["PayPalAccount"]) logfile("error",__FILE__,__LINE__,"PAYER_EMAIL is not equal to PayPalAccount (PAYER_EMAIL=$PAYER_EMAIL,PayPalAccount=".$paypal_info["PayPalAccount"].").\n");

			$qr_res = mysql_query("SELECT uid,amount,payaccount FROM ".$db_tables["payment"]." WHERE pid='$Pid' and status=0 and payway=2") or logfile("error",__FILE__,__LINE__,__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
			if (mysql_num_rows($qr_res) == 0) logfile("error",__FILE__,__LINE__,"Can not find not processed payment with Pid=$Pid\n");
			$myrow = mysql_fetch_array($qr_res);
			$paypal_info["PayAccount"] = $myrow["payaccount"];
			$paypal_info["Amount"] = $myrow["amount"];
			$paypal_info["uid"] = $myrow["uid"];

			if ($BUSINESS != $paypal_info["PayAccount"]) logfile("error",__FILE__,__LINE__,"BUSINESS is not equal to PayAccount (BUSINESS=$BUSINESS,PayAccount=".$paypal_info["PayAccount"].").\n");

			if (abs($MC_GROSS-$paypal_info["Amount"]) > 0.1) logfile("error",__FILE__,__LINE__,"Payment amount not equal requested amount (Payment amount = \"$MC_GROSS\", Requsted=\"".$paypal_info["Amount"]."\").\n");

			if ($MC_CURRENCY != $paypal_info["PayPalCurrency"]) logfile("error",__FILE__,__LINE__,"Payment currency is invalid (Payment currency: \"$MC_CURRENCY\").\n");
	
			if ($PAYMENT_STATUS != "Completed") logfile("error",__FILE__,__LINE__,"Incorrect PAYMENT_STATUS (PAYMENT_STATUS=$PAYMENT_STATUS).\n");
	
			$qr_res = mysql_query("SELECT batchnum FROM ".$db_tables["payment"]." WHERE batchnum='$TXN_ID'") 
				or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
			if (mysql_num_rows($qr_res) > 0) logfile("error",__FILE__,__LINE__,"Such batchnum($TXN_ID) already present in database.\n");

			$ptf = array_flip($payment_types);	
			//Update in payment table
			mysql_query("UPDATE ".$db_tables["payment"]." SET status=1, paytime=NOW(), batchnum='$TXN_ID' WHERE pid='$Pid'") or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");

			//Get member e-mail
			$qr_res = mysql_query("SELECT email FROM ".$db_tables["users"]." WHERE uid='".$paypal_info["uid"]."'") or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
			if (mysql_num_rows($qr_res) == 0) logfile("error",__FILE__,__LINE__,"Can not find user with UID=".$paypal_info["uid"]."\n");
			$myrow = mysql_fetch_array($qr_res);
			$email = $myrow["email"];

			//----Send mail---->>
			$parse_values["{*payment_system*}"] = $payment_systems_array["paypal"];
			$parse_values["{*payment_info_text*}"] = "Payee account: $BUSINESS\n".
				"Payment_amount: $MC_GROSS\nTXN_ID: $TXN_ID\nVERIFY_SIGN: $VERIFY_SIGN";
			$parse_values["{*payment_info_html*}"] = "Payee account: $BUSINESS<br>".
				"Payment_amount: $MC_GROSS<br>TXN_ID: $TXN_ID<br>VERIFY_SIGN: $VERIFY_SIGN";
			$admin_email = get_admin_email_free();
			//----Send mail to member
			//----Send mail to member (Account Credited Notification Email to member)
			$subj	= get_mailsubject("credited_notification");
			$htmlmessage = get_email_file("credited_notification","html");
			$textmessage = get_email_file("credited_notification","txt");
			$attach_files = get_mail_attach("credited_notification");
			$subj = str_replace("{*site_title*}",$parse_values["{*site_title*}"],$subj);
			create_and_send_email($email,$admin_email,$subj,$htmlmessage,$textmessage,$attach_files);
			//----Send mail----<<

			logfile("normal",__FILE__,__LINE__);
		//--------------
		}
		else if (strcmp ($res, "INVALID") == 0) {
			logfile("error",__FILE__,__LINE__,"PayPal payment sytem error: INVALID response.\n");
		}
	}
	fclose ($fpaypal);
}
?>