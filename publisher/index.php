<?
session_start();

require_once "../management/consts.php";

$_SESSION["sess_curlogintype"] = "publisher";

header("Location: ../management/index.php");
?>
