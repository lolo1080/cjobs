<?
session_start();
unset($_SESSION["sess_user"]);
unset($_SESSION["sess_userid"]);
unset($_SESSION["sess_username"]);
unset($_SESSION["sess_curlogintype"]);
unset($_SESSION["globsettings"]);
unset($_SESSION["paymentsettings"]);
unset($_SESSION["sess_lang"]);
unset($_SESSION["sess_curmenu_status"]);
@session_destroy();
header("Location: ../"); exit;
?>