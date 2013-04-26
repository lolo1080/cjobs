<?
//https://www.2checkout.com/cgi-bin/buyers/cartpurchase.2c?sid=47947&total=22&demo=&cart_order_id=1109055863
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
 global $ACCOUNT_NUMBER,$TOTAL_AMOUNT,$ORDER_NUMBER,$CARD_HOLDER_NAME,$STREET_ADDRESS,$CITY,$STATE,
		$ZIP,$COUNTRY,$EMAIL,$SHIP_NAME,$SHIP_STREET_ADDRESS,$SHIP_CITY,$SHIP_STATE,$SHIP_ZIP,
		$SHIP_COUNTRY,$uid,$uname,$upass,$REMOTE_ADDR,$log_info;
	$_SESSION["sess_PAYMENT_STATUS"] = $status;
	$_SESSION["sess_PAYMENT_MESSAGE"] = $message;
	$ftype = "";
	if ($status == "normal") $ftype = "history";
	elseif ($status == "error") $ftype = "errors";
	log_file_payment($ftype,$file,$line,$message."Your 2Checkout.com account number: $ACCOUNT_NUMBER\n".
		"Total amount to be billed: $TOTAL_AMOUNT\n2Checkout.com order number: $ORDER_NUMBER\n".
		"Card holders name: $CARD_HOLDER_NAME\nCard holders street address: $STREET_ADDRESS\n".
		"Card holders city: $CITY\nCard holders state: $STATE\nCard holders zip: $ZIP\n".
		"Card holders country: $COUNTRY\nCard holders email: $EMAIL\nShipping name: $SHIP_NAME\n".
		"Shipping street address: $SHIP_STREET_ADDRESS\nShipping city: $SHIP_CITY\n".
		"Shipping state: $SHIP_STATE\nShipping zip: $SHIP_ZIP\nShipping country: $SHIP_COUNTRY\n".
		"REMOTE_ADDR=$REMOTE_ADDR\n  DB_UID=$uid\n  DB_LOGIN=$uname\n");
	if ($status == "error") { header("Location: ccpay_2checkoutpay.php?".$SLINE); exit; }
}

$ACCOUNT_NUMBER = html_chars(data_addslashes(get_get_post_value("sid","")));
$TOTAL_AMOUNT = html_chars(data_addslashes(get_get_post_value("total","")));
$ORDER_NUMBER = html_chars(data_addslashes(get_get_post_value("order_number","")));
$CARD_HOLDER_NAME = html_chars(data_addslashes(get_get_post_value("card_holder_name","")));
$STREET_ADDRESS = html_chars(data_addslashes(get_get_post_value("street_address","")));
$CITY = html_chars(data_addslashes(get_get_post_value("city","")));
$STATE = html_chars(data_addslashes(get_get_post_value("state","")));
$ZIP = html_chars(data_addslashes(get_get_post_value("zip","")));
$COUNTRY = html_chars(data_addslashes(get_get_post_value("country","")));
$EMAIL = html_chars(data_addslashes(get_get_post_value("email","")));
$SHIP_NAME = html_chars(data_addslashes(get_get_post_value("ship_name","")));
$SHIP_STREET_ADDRESS = html_chars(data_addslashes(get_get_post_value("ship_street_address","")));
$SHIP_CITY = html_chars(data_addslashes(get_get_post_value("ship_city","")));
$SHIP_STATE = html_chars(data_addslashes(get_get_post_value("ship_state","")));
$SHIP_ZIP = html_chars(data_addslashes(get_get_post_value("ship_zip","")));
$SHIP_COUNTRY = html_chars(data_addslashes(get_get_post_value("ship_country","")));
$demo = html_chars(data_addslashes(get_get_post_value("demo","")));
$uid = html_chars(data_addslashes(get_get_post_value("uid","")));
$uname = html_chars(data_addslashes(get_get_post_value("uname","")));
$upass = html_chars(data_addslashes(get_get_post_value("upass","")));
$CREDIT_CARD_PROCESSED = html_chars(data_addslashes(get_get_post_value("credit_card_processed","")));
$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

$_SESSION["sess_PAYMENT_AMOUNT"] = "22".$TOTAL_AMOUNT;
$_SESSION["sess_PAYMENT_BATCH_NUM"] = $ORDER_NUMBER;
$_SESSION["sess_PAYMENT_PAYEE_ACCOUNT"] = $ACCOUNT_NUMBER;
$_SESSION["sess_PAYMENT_PAYER_ACCOUNT"] = "Hidden for current payment system.";

if ($CREDIT_CARD_PROCESSED != "Y") logfile("error",__FILE__,__LINE__,"Credit Card Processing Error. Your credit card could not be processed.\n");

if (($demo == "Y") && (!$_SESSION["paymentsettings"]["2checkout_test"])) logfile("error",__FILE__,__LINE__,"Credit Card Processing Error. Your credit card could not be processed.\n The 2checkout payment not work in test mode.\n");

$qr_res = mysql_query("SELECT 2checkout_id,2checkout_mindeposit FROM ".$db_tables["paymentsettings"]) or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
$myrow = mysql_fetch_array($qr_res);
$cc_2checkout_id = $myrow["2checkout_id"];
$cc_2checkout_mindeposit = $myrow["2checkout_mindeposit"];

if ($ACCOUNT_NUMBER != $cc_2checkout_id) logfile("error",__FILE__,__LINE__,"ACCOUNT_NUMBER is not equal to 2checkout account(ACCOUNT_NUMBER=\"$ACCOUNT_NUMBER\",2checkout account=\"$cc_2checkout_id\").\n");

if ($TOTAL_AMOUNT < $cc_2checkout_mindeposit) logfile("error",__FILE__,__LINE__,"Payment amount lesser then \"Minimum deposit by 2checkout\" (Payment amount = \"$TOTAL_AMOUNT\", 2checkoutMinDeposit=\"".$cc_2checkout_mindeposit."\").\n");

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
mysql_query("INSERT INTO ".$db_tables["payment"]." VALUES(NULL,'$uid',NOW(),$TOTAL_AMOUNT,".$ptf["2checkout"].",'1',NOW(),'','$SHIP_NAME','2checkout script','$ORDER_NUMBER',1)") 
	or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");

//Update user account
mysql_query("UPDATE ".$db_tables["users"]." SET amount=amount+$TOTAL_AMOUNT WHERE uid='$uid'") 
	or logfile("error",__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");

//Referral system
referral_system($uid,$total,"","","");

logfile("normal",__FILE__,__LINE__);

//----Send mail---->>
	$parse_values["{*site_title*}"]	= $_SESSION["globsettings"]["site_title"];
	$parse_values["{*payment_system*}"] = "E-Gold";
	$parse_values["{*payment_info_text*}"] = "Payee 2Checkout.com account number: $ACCOUNT_NUMBER\n".
		"2Checkout.com order number: $ORDER_NUMBER\nTotal amount to be billed: $TOTAL_AMOUNT\n".
		"Card holders name: $CARD_HOLDER_NAME\nShipping name: $SHIP_NAME";
	$parse_values["{*payment_info_html*}"] = "Payee 2Checkout.com account number: $ACCOUNT_NUMBER<br>".
		"2Checkout.com order number: $ORDER_NUMBER<br>Total amount to be billed: $TOTAL_AMOUNT<br>".
		"Card holders name: $CARD_HOLDER_NAME<br>Shipping name: $SHIP_NAME";
	$admin_email = get_admin_email_free();
	//----Send e-mail to member (Account Credited Notification Email to member)
	$subj	= get_mailsubject("credited_notification");
	$htmlmessage = get_email_file("credited_notification","html");
	$textmessage = get_email_file("credited_notification","txt");
	$attach_files = get_mail_attach("credited_notification");
	$subj = str_replace("{*site_title*}",$parse_values["{*site_title*}"],$subj);
	create_and_send_email($email,$admin_email,$subj,$htmlmessage,$textmessage,$attach_files);
//----Send mail----<<
header("Location: ccpay_2checkoutpay.php?".$SLINE); exit;
?>