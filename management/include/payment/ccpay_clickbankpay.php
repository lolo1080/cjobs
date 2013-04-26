<?
session_start();

require_once "../../consts.php";
require_once "../../language.php";
//user_settings();

$_SESSION["sess_PAYMENT_SYSTEM"] = $payment_systems_array["clickbank"];
$_SESSION["sess_PAYMENT_RETURN"] = true;

header("Location: ../../index.php?".$SLINE); 
?>