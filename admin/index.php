<?php
session_start();

require_once "../management/consts.php";

if (isset($_SESSION["sess_user"])) unset($_SESSION["sess_user"]);
if (isset($_SESSION["sess_userid"])) unset($_SESSION["sess_userid"]);

$_SESSION["sess_curlogintype"] = "admin";

header("Location: ../management/index.php");
?>
