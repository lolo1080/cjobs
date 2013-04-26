<?
session_start();

require_once "consts.php";
require_once "leftmenu_const.php";

$start_content = $start_content[$_SESSION["sess_user"]];
if (isset($_SESSION["sess_curscript"]) && $_SESSION["sess_curscript"] == "fundaccount") $start_content = "fundaccount.php";

header("Location: ".$start_content."?$SLINE"); exit;
?>