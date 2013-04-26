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
 global $myresult,$auth,$transID,$AVRResponse,$SLINE,$REMOTE_ADDR,$payment_systems_array,$db_tables;
	$_SESSION["sess_PAYMENT_SYSTEM"] = $payment_systems_array["credit_card"];
	$_SESSION["sess_PAYMENT_STATUS"] = $status;
	$_SESSION["sess_PAYMENT_AMOUNT"] = $_SESSION["sess_cc_amount"];
	$_SESSION["sess_PAYMENT_BATCH_NUM"] = $transID;
	$_SESSION["sess_PAYMENT_RETURN"] = true;
	$ftype = "";
	if ($status == "normal") $ftype = "history";
	elseif ($status == "error") $ftype = "errors";
	log_file_payment($ftype,$file,$line,$message."Auth=$auth\nTransID=$transID\nAVRResponse=$AVRResponse\n".
		"Amount={$_SESSION["sess_cc_amount"]}\nDB_UID={$_SESSION["sess_userid"]}\nDB_LOGIN={$_SESSION["sess_username"]}\n".
		"More info={$db_tables["payments_tmp_adv"]} pid={$_SESSION["sess_tmp_tbl_id"]}\nREMOTE_ADDR=$REMOTE_ADDR\n".
		"Server Response=$myresult\n");
	if ($status == "error") {
		trigger_error("File: $file Line:$line. $message");
		header("Location: ../../adv_info.php?".$SLINE); exit;
	}
}

function add_pval($key,$value)
{
 global $req;
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}

//Prepare Values
$ch_agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (affgrabber)";

//Create POST reques
$req = "";
add_pval("gwlogin",$_SESSION["paymentsettings"]["credit_card_login"]);
add_pval("trans_method","CC");
add_pval("ccnum",$_SESSION["sess_cc_number"]);
add_pval("ccmo",$_SESSION["sess_cc_expiration_month"]);
add_pval("ccyr",$_SESSION["sess_cc_expiration_year"]);
add_pval("amount",$_SESSION["sess_cc_amount"]);
add_pval("BADDR1",$_SESSION["sess_cc_address1"]);
add_pval("BZIP1",$_SESSION["sess_cc_zipcode"]);
add_pval("BCUST_EMAIL",$_SESSION["sess_cc_email"]);
add_pval("override_email_customer","N");
add_pval("override_trans_email","N");
add_pval("BNAME",$_SESSION["sess_cc_name"]);
add_pval("CVV2",$_SESSION["sess_cc_cvv2"]);

//Send POST 
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, $credit_card_info["cc_gateway_url"]);
curl_setopt($ch, CURLOPT_USERAGENT, $ch_agent);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, 1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANYSAFE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_REFERER, $credit_card_info["cc_gateway_url"]);
$result = curl_exec($ch);
curl_close($ch);

$myresult = $result;

$passback_exp = explode("|", $myresult); #break result into pieces.

$auth = trim($passback_exp[0]); # take the first component
$auth = str_replace("\"", "", $auth); # get rid of the wrapping Quotes " "

$transID = (isset($passback_exp[1])) ? trim($passback_exp[1]) : 0; # take the second component
$transID = str_replace("\"", "", $transID); # get rid of the wrapping Quotes " "

$AVRResponse = (isset($passback_exp[2])) ? trim($passback_exp[2]) : 0; # take the third component
$AVRResponse = str_replace("\"", "", $AVRResponse); # get rid of the wrapping Quotes " "

$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

doconnect();

if(preg_match("/APPROVED/i","$auth")) {

	//Get payment data from tmp table
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["payments_tmp_adv"]." WHERE pid='{$_SESSION["sess_tmp_tbl_id"]}'") or logfile("error",__FILE__,__LINE__,__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
	if (mysql_num_rows($qr_res) == 0) logfile("error",__FILE__,__LINE__,"Can not find payment data: {$db_tables["payments_tmp_adv"]} pid='{$_SESSION["sess_tmp_tbl_id"]}'\n");
	$myrow = mysql_fetch_array($qr_res);                                

	//Check user id
	if ($myrow["uid_adv"] != $_SESSION["sess_userid"]) logfile("error",__FILE__,__LINE__,"User id is incorrect: tbl_uid={$myrow["uid_adv"]}, sess_uid={$_SESSION["sess_userid"]}\n");

	//Check amount (1)
	if ($myrow["amount"] < ($_SESSION["sess_cc_amount"]-0.01)) logfile("error",__FILE__,__LINE__,"Amount is incorrect: tbl_amount={$myrow["amount"]}, sess_amount={$_SESSION["sess_cc_amount"]}\n");
	//Check amount (2)
	if ($_SESSION["sess_cc_amount"] < $_SESSION["paymentsettings"]["credit_card_mindeposit"]) logfile("error",__FILE__,__LINE__,"Payment amount lesser then \"Minimum deposit by Credit Card\" (Payment amount = \"{$_SESSION["sess_cc_amount"]}\", CreditCard MinDeposit=\"{$_SESSION["paymentsettings"]["credit_card_mindeposit"]}\").\n");

	//Check batchnum
//	$qr_res = mysql_query("SELECT batchnum FROM ".$db_tables["payments_adv"]." WHERE batchnum='$transID'") or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
//	if (mysql_num_rows($qr_res) > 0) logfile("error",__FILE__,__LINE__,"Such batchnum($transID) already present in database.\n");

	//Add info into payment table
	$ptf = array_flip($payment_types);	
	mysql_query("INSERT INTO ".$db_tables["payments_adv"]." VALUES(NULL,'{$_SESSION["sess_userid"]}',NOW(),'{$_SESSION["sess_cc_amount"]}',".
		$ptf["Credit Card"].",'{$myrow["payinfo"]}','$transID',1)") or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
	//Update user account
	mysql_query("UPDATE ".$db_tables["users_advertiser"]." SET balance=balance+{$_SESSION["sess_cc_amount"]} WHERE uid_adv='{$_SESSION["sess_userid"]}'") or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
	//Send event
	$event_array = array("event"=>"update", "source"=>"advertisers", "table"=>"users_advertiser", "ad_id"=>0);
	event_handler($event_array);
	//Delete temp data
	mysql_query("DELETE FROM ".$db_tables["payments_tmp_adv"]." WHERE pid='{$_SESSION["sess_tmp_tbl_id"]}'") or logfile("error",__FILE__,__LINE__,__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");

	//Get member info
	$member_info = get_member_main_info($db_tables["users_advertiser"],"uid_adv",$_SESSION["sess_userid"]);
	if (!$member_info["result"]) logfile("error",__FILE__,__LINE__,__FILE__,__LINE__,"Cannot get advertiser data: uid={$_SESSION["sess_userid"]}\n");

	//----Send mail---->>
	$parse_values["{*payment_system*}"] = $payment_systems_array["credit_card"];
	$parse_values["{*payment_info_text*}"] = "Payee account: {$_SESSION["sess_username"]}\n".
		"Payment amount: {$myrow["amount"]}\nTransaction ID: $transID";
	$parse_values["{*payment_info_html*}"] = "Payee account: {$_SESSION["sess_username"]}<br>".
		"Payment amount: {$myrow["amount"]}<br>Transaction ID: $transID";
	$admin_email = get_admin_email_free();
	//----Send mail to member (Account Credited Notification Email to member)
	$subj	= get_mailsubject("credited_notification");
	$htmlmessage = get_email_file("credited_notification","html");
	$textmessage = get_email_file("credited_notification","txt");
	$attach_files = get_mail_attach("credited_notification");
	$subj = str_replace("{*site_title*}",$_SESSION["globsettings"]["site_title"],$subj);
	create_and_send_email($member_info["email"],$admin_email,$subj,$htmlmessage,$textmessage,$attach_files);
	//----Send mail----<<

	logfile("normal",__FILE__,__LINE__);

	//Add credit card info into cc_stored table
	$cc_number_enc = data_addslashes(Encrypt($_SESSION["sess_cc_number"]));
	$qr_res = mysql_query("SELECT sccid,cc_number,cc_expiration_month,cc_expiration_year FROM ".$db_tables["payments_adv_stored_cc"]." WHERE uid_adv='{$_SESSION["sess_userid"]}' and cc_number='$cc_number_enc'") or logfile("error",__FILE__,__LINE__,__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
	if (mysql_num_rows($qr_res) == 0) {
		mysql_query("INSERT INTO ".$db_tables["payments_adv_stored_cc"]." VALUES(NULL,'{$_SESSION["sess_userid"]}','$cc_number_enc',".
			"'".substr($_SESSION["sess_cc_number"], -4)."','{$_SESSION["sess_cc_expiration_month"]}','{$_SESSION["sess_cc_expiration_year"]}','{$myrow["payinfo"]}')") or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
	}

	header("Location: ../../adv_info.php?".$SLINE); exit;
}
else
{
	$ResponseError = (isset($passback_exp[6])) ? trim($passback_exp[6]) : ""; # take the sixth component
	logfile("error",__FILE__,__LINE__,"Credit Card payment sytem error: DECLINED response (Error: $ResponseError).\n");
}
?>