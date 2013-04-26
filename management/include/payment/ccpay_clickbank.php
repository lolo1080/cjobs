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
 global $PAYMENT_AMOUNT,$ORDER_NUMBER,$uid,$uname,$REMOTE_ADDR,$log_info;
	$_SESSION["sess_PAYMENT_STATUS"] = $status;
	$_SESSION["sess_PAYMENT_MESSAGE"] = $message;
	$ftype = "";
	if ($status == "normal") $ftype = "history";
	elseif ($status == "error") $ftype = "errors";
	log_file_payment($ftype,$file,$line,$message."Total amount to be billed: $PAYMENT_AMOUNT\n".
		"Order number: $ORDER_NUMBER\nREMOTE_ADDR=$REMOTE_ADDR\n  DB_UID=$uid\n  DB_LOGIN=$uname\n");
	if ($status == "error") { header("Location: ccpay_clickbankpay.php?".$SLINE); exit; }
}
$uid = html_chars(data_addslashes(get_get_post_value("uid","")));
$uname = html_chars(data_addslashes(get_get_post_value("uname","")));
$upass = html_chars(data_addslashes(get_get_post_value("upass","")));
$ORDER_NUMBER = html_chars(data_addslashes(get_get_post_value("cbreceipt","")));
$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

if (!isset($_SESSION["sess_clickbank_amount"]) || !check_int($_SESSION["sess_clickbank_amount"])) logfile("error",__FILE__,__LINE__,"Your credit card could not be processed. Payment amount is incorrect.\n");

$_SESSION["sess_PAYMENT_AMOUNT"] = $PAYMENT_AMOUNT = $_SESSION["sess_clickbank_amount"];
$_SESSION["sess_PAYMENT_BATCH_NUM"] = $ORDER_NUMBER;
$_SESSION["sess_PAYMENT_PAYEE_ACCOUNT"] = "Hidden for current payment system.";
$_SESSION["sess_PAYMENT_PAYER_ACCOUNT"] = "Hidden for current payment system.";
unset($_SESSION["sess_clickbank_amount"]);

$qr_res = mysql_query("SELECT clickbank_mindeposit FROM ".$db_tables["paymentsettings"]) or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
$myrow = mysql_fetch_array($qr_res);
$cc_clickbank_mindeposit = $myrow["clickbank_mindeposit"];

if ($PAYMENT_AMOUNT < $cc_clickbank_mindeposit) logfile("error",__FILE__,__LINE__,"Payment amount lesser then \"Minimum deposit by clickbank\" (Payment amount = \"$PAYMENT_AMOUNT\", ClickBankMinDeposit=\"".$cc_clickbank_mindeposit."\").\n");

if (($uid == "") || ($uname == "") || ($upass == ""))	logfile("error",__FILE__,__LINE__,"The user information is incorrect.\n");

$qr_res = mysql_query("SELECT pass,email FROM ".$db_tables["users"]." WHERE uid='$uid' and username='$uname'") 
	or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
if (mysql_num_rows($qr_res) == 0) logfile("error",__FILE__,__LINE__,"Can not find user with UID=$uid and Login=$uname\n");

$myrow= mysql_fetch_array($qr_res);
$email= $myrow["email"];
$pass	= md5($myrow["pass"]);
if ($pass != $upass) logfile("error",__FILE__,__LINE__,"The password is incorrect for user with UID=$uid and Login=$uname.\n");

$qr_res = mysql_query("SELECT batchnum FROM ".$db_tables["payment"]." WHERE batchnum='$ORDER_NUMBER'") 
	or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
if (mysql_num_rows($qr_res) > 0) logfile("error",__FILE__,__LINE__,"Such batchnum($ORDER_NUMBER) already present in database\n");

$ptf = array_flip($payment_types);
//Add info in payment table
mysql_query("INSERT INTO ".$db_tables["payment"]." VALUES(NULL,'$uid',NOW(),$PAYMENT_AMOUNT,".$ptf["ClickBank"].",'1',NOW(),'','No data','clickbank script','$ORDER_NUMBER',1)")
	or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");

//Update user account
mysql_query("UPDATE ".$db_tables["users"]." SET amount=amount+$PAYMENT_AMOUNT WHERE uid='$uid'") 
	or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");

//Referral system
referral_system($uid,$total,"","","");

logfile("normal",__FILE__,__LINE__);

//----Send mail---->>
	$parse_values["{*site_title*}"]	= $_SESSION["globsettings"]["site_title"];
	$parse_values["{*payment_system*}"] = "E-Gold";
	$parse_values["{*payment_info_text*}"] = "Total amount to be billed: $PAYMENT_AMOUNT\n".
		"Order number: $ORDER_NUMBER";
	$parse_values["{*payment_info_html*}"] = "Total amount to be billed: $PAYMENT_AMOUNT<br>".
		"Order number: $ORDER_NUMBER";;
	$admin_email = get_admin_email_free();
	//----Send e-mail to member (Account Credited Notification Email to member)
	$subj	= get_mailsubject("credited_notification");
	$htmlmessage = get_email_file("credited_notification","html");
	$textmessage = get_email_file("credited_notification","txt");
	$attach_files = get_mail_attach("credited_notification");
	$subj = str_replace("{*site_title*}",$parse_values["{*site_title*}"],$subj);
	create_and_send_email($email,$admin_email,$subj,$htmlmessage,$textmessage,$attach_files);
//----Send mail----<<
header("Location: ccpay_clickbankpay.php?".$SLINE); exit;
?>