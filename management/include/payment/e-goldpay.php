<?
session_start();

require_once "../../consts.php";
require_once "../../language.php";
//user_settings();

$_SESSION["sess_PAYMENT_AMOUNT"] = html_chars(data_addslashes(get_get_post_value("PAYMENT_AMOUNT","")));
$_SESSION["sess_PAYMENT_BATCH_NUM"] = html_chars(data_addslashes(get_get_post_value("PAYMENT_BATCH_NUM","")));
$_SESSION["sess_PAYMENT_PAYEE_ACCOUNT"] = html_chars(data_addslashes(get_get_post_value("PAYEE_ACCOUNT","")));
$_SESSION["sess_PAYMENT_PAYER_ACCOUNT"] = html_chars(data_addslashes(get_get_post_value("PAYER_ACCOUNT","")));
$_SESSION["sess_PAYMENT_SYSTEM"] = $payment_systems_array["egold"];
$_SESSION["sess_PAYMENT_RETURN"] = true;

header("Location: ../../index.php?".$SLINE); 
?>