<?
session_start();

require_once "../management/consts.php";

$_SESSION["sess_curlogintype"] = "advertiser";

header("Location: ../management/index.php");
?>
