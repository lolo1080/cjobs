<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "functions.php";
require_once "adv_profile_func.php";
check_access(array(1));

$merchant2cuser = $_SESSION["paymentsettings"]["2checkout_id"];
$amount = @$_SESSION["sess_2checkout_amount"];
$uname = @$_SESSION["sess_2checkout_uname"];
$upass = @$_SESSION["sess_2checkout_upass"];
$cc_2checkout_test = ($_SESSION["paymentsettings"]["2checkout_test"]) ? "Y" : "";
unset($_SESSION["sess_2checkout_amount"]);
unset($_SESSION["sess_2checkout_uname"]);
unset($_SESSION["sess_2checkout_upass"]);

$url = $_SESSION["paymentsettings"]["2checkout_url"]."?sid=$merchant2cuser&total=$amount&demo=$cc_2checkout_test&uid=".$_SESSION["sess_userid"]."&uname=$uname&upass=$upass&$SLINE";
$url = str_replace(" ", "%20", $url);
header("Location: $url");
exit;
?>