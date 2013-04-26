<?
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
 global $PAYEE_ACCOUNT,$PAYMENT_ID,$PAYMENT_AMOUNT,$PAYMENT_UNITS,$PAYMENT_METAL_ID,
		$PAYMENT_BATCH_NUM,$PAYER_ACCOUNT,$HANDSHAKE_HASH,$ACTUAL_PAYMENT_OUNCES,
		$TIMESTAMPGMT,$V2_HASH,$uid,$uname,$upass,$REMOTE_ADDR,$log_info;
	$_SESSION["sess_PAYMENT_STATUS"] = $status;
	$_SESSION["sess_PAYMENT_MESSAGE"] = $message;
	$ftype = "";
	if ($status == "normal") $ftype = "history";
	elseif ($status == "error") $ftype = "errors";
	log_file_payment($ftype,$file,$line,$message."PAYEE_ACCOUNT=$PAYEE_ACCOUNT\nPAYMENT_ID=$PAYMENT_ID\nPAYMENT_AMOUNT=$PAYMENT_AMOUNT\n".
		"PAYMENT_UNITS=$PAYMENT_UNITS\nPAYMENT_METAL_ID=$PAYMENT_METAL_ID\nPAYMENT_BATCH_NUM=$PAYMENT_BATCH_NUM\nPAYER_ACCOUNT=$PAYER_ACCOUNT\n".
		"HANDSHAKE_HASH=$HANDSHAKE_HASH\nACTUAL_PAYMENT_OUNCES=$ACTUAL_PAYMENT_OUNCES\nTIMESTAMPGMT=$TIMESTAMPGMT\nV2_HASH=$V2_HASH\nREMOTE_ADDR=$REMOTE_ADDR\n".
		"  DB_UID=$uid\n  DB_LOGIN=$uname\n");
	if ($status == "error") exit;
}

$ERROR = html_chars(data_addslashes(get_get_post_value("ERROR","")));
$PAYEE_ACCOUNT = html_chars(data_addslashes(get_get_post_value("PAYEE_ACCOUNT","")));
$PAYMENT_ID = html_chars(data_addslashes(get_get_post_value("PAYMENT_ID","")));
$PAYMENT_AMOUNT = html_chars(data_addslashes(get_get_post_value("PAYMENT_AMOUNT","")));
$PAYMENT_UNITS = html_chars(data_addslashes(get_get_post_value("PAYMENT_UNITS","")));
$PAYMENT_METAL_ID = html_chars(data_addslashes(get_get_post_value("PAYMENT_METAL_ID","")));
$PAYMENT_BATCH_NUM = html_chars(data_addslashes(get_get_post_value("PAYMENT_BATCH_NUM","")));
$PAYER_ACCOUNT = html_chars(data_addslashes(get_get_post_value("PAYER_ACCOUNT","")));
$HANDSHAKE_HASH = html_chars(data_addslashes(get_get_post_value("HANDSHAKE_HASH","")));
$ACTUAL_PAYMENT_OUNCES = html_chars(data_addslashes(get_get_post_value("ACTUAL_PAYMENT_OUNCES","")));
$TIMESTAMPGMT = html_chars(data_addslashes(get_get_post_value("TIMESTAMPGMT","")));
$V2_HASH = html_chars(data_addslashes(get_get_post_value("V2_HASH","")));
$uid = html_chars(data_addslashes(get_get_post_value("uid","")));
$uname = html_chars(data_addslashes(get_get_post_value("uname","")));
$upass = html_chars(data_addslashes(get_get_post_value("upass","")));
$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

if ($ERROR != "") logfile("error",__FILE__,__LINE__,"E-Gold payment sytem error: $ERROR\n");

$qr_res = mysql_query("SELECT egold_id,egold_mindeposit FROM ".$db_tables["paymentsettings"]) or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
$myrow = mysql_fetch_array($qr_res);
$egold_info["EGoldAccount"] = $myrow["egold_id"];
$egold_info["EGoldmindeposit"] = $myrow["egold_mindeposit"];

if ($PAYEE_ACCOUNT != $egold_info["EGoldAccount"]) logfile("error",__FILE__,__LINE__,"PAYEE_ACCOUNT is not equal to EGoldAccount(PAYEE_ACCOUNT=\"$PAYEE_ACCOUNT\",EGoldAccount=\"".$egold_info["EGoldAccount"]."\").\n");

if ($PAYMENT_AMOUNT < $egold_info["EGoldmindeposit"]) logfile("error",__FILE__,__LINE__,"Payment amount lesser then \"Minimum deposit by egold\" (Payment amount = \"$PAYMENT_AMOUNT\", EGoldMinDeposit=\"".$egold_info["EGoldmindeposit"]."\").\n");

if (($uid == "") || ($uname == "") || ($upass == ""))	logfile("error",__FILE__,__LINE__,"The user information is incorrect.\n");

list($ra0,$ra1,$ra2,$ra3) = preg_split("/\./",$REMOTE_ADDR);
if (($ra0 != "63") || ($ra1 != "240") || ($ra2 != "230"))	logfile("error",__FILE__,__LINE__,"Remote IP address is incorrect($REMOTE_ADDR). Correct remote address=63.240.230.*\n");

$qr_res = mysql_query("SELECT pass,email FROM ".$db_tables["users"]." WHERE uid='$uid' and username='$uname'") 
	or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
if (mysql_num_rows($qr_res) == 0) logfile("error",__FILE__,__LINE__,"Can not find user with UID=$uid and Login=$uname\n");

$myrow= mysql_fetch_array($qr_res);
$email= $myrow["email"];
$pass	= md5($myrow["pass"]);
if ($pass != $upass) logfile("error",__FILE__,__LINE__,"The password is incorrect for user with UID=$uid and Login=$uname.\n");

$qr_res = mysql_query("SELECT batchnum FROM ".$db_tables["payment"]." WHERE batchnum='$PAYMENT_BATCH_NUM'.") 
	or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
if (mysql_num_rows($qr_res) > 0) logfile("error",__FILE__,__LINE__,"Such batchnum($PAYMENT_BATCH_NUM) already present in database.\n");

$ptf = array_flip($payment_types);
//Add info in payment table
mysql_query("INSERT INTO ".$db_tables["payment"]." VALUES(NULL,'$uid',NOW(),$PAYMENT_AMOUNT,".$ptf["E-Gold"].",'1',NOW(),'','$PAYER_ACCOUNT','e-gold script','$PAYMENT_BATCH_NUM',1)") 
	or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");

//Update user account
mysql_query("UPDATE ".$db_tables["users"]." SET amount=amount+$PAYMENT_AMOUNT WHERE uid='$uid'") 
	or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");

//Referral system
referral_system($uid,$PAYMENT_AMOUNT,"","","");

logfile("normal",__FILE__,__LINE__);

//----Send mail---->>
	$parse_values["{*site_title*}"]	= $_SESSION["globsettings"]["site_title"];
	$parse_values["{*payment_system*}"] = "E-Gold";
	$parse_values["{*payment_info_text*}"] = "Payee account: $PAYEE_ACCOUNT\n".
		"Payment id: $PAYMENT_ID\nPayment_amount: $PAYMENT_AMOUNT\nPayment_batch_num: $PAYMENT_BATCH_NUM\n".
		"Timestampgmt: $TIMESTAMPGMT\nV2 hash: $V2_HASH";
	$parse_values["{*payment_info_html*}"] = "Payee account: $PAYEE_ACCOUNT<br>".
		"Payment id: $PAYMENT_ID<br>Payment_amount: $PAYMENT_AMOUNT<br>Payment_batch_num: $PAYMENT_BATCH_NUM<br>".
		"Timestampgmt: $TIMESTAMPGMT<br>V2 hash: $V2_HASH";
	$admin_email = get_admin_email_free();
	//----Send e-mail to member (Account Credited Notification Email to member)
	$subj	= get_mailsubject("credited_notification");
	$htmlmessage = get_email_file("credited_notification","html");
	$textmessage = get_email_file("credited_notification","txt");
	$attach_files = get_mail_attach("credited_notification");
	$subj = str_replace("{*site_title*}",$parse_values["{*site_title*}"],$subj);
	create_and_send_email($email,$admin_email,$subj,$htmlmessage,$textmessage,$attach_files);
//----Send mail----<<
?>