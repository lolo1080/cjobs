<?
session_start();

require_once "../../consts.php";
require_once "../../language.php";
//user_settings();

header("Location: ../../payment_request.php?".$SLINE);
?>