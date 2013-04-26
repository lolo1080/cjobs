<?
session_start();

require_once "../../consts.php";
require_once "../../language.php";
require_once "../../functions.php";
//user_settings();

$_SESSION["sess_PAYMENT_SYSTEM"] = $payment_systems_array["paypal"];
$_SESSION["sess_PAYMENT_STATUS"] = "normal";
$_SESSION["sess_PAYMENT_AMOUNT"] = html_chars(data_addslashes(get_get_post_value("mc_gross","")));
$_SESSION["sess_PAYMENT_BATCH_NUM"] = html_chars(data_addslashes(get_get_post_value("txn_id","")));
$_SESSION["sess_PAYMENT_PAYEE_ACCOUNT"] = html_chars(data_addslashes(get_get_post_value("business","")));
$_SESSION["sess_PAYMENT_PAYER_ACCOUNT"] = html_chars(data_addslashes(get_get_post_value("payer_email","")));
$_SESSION["sess_PAYMENT_SYSTEM"] = $payment_systems_array["paypal"];
$_SESSION["sess_PAYMENT_RETURN"] = true;

header("Location: ../../index.php?".$SLINE);
?>
