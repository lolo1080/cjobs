<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "functions.php";
require_once "functions_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";

doconnect();

//Check settings
get_global_settings();
$key = data_addslashes(get_get_value("key",""));
$type = data_addslashes(get_get_value("type","adv"));

$confirm_message = $text_info["c_confirm_message_not_success"];
//Confirm e-mail
if ($key != "") {
	$qr_res = mysql_query("SELECT confirm_email FROM ".$db_tables["users_confirm_email"]." WHERE confirm_id='$key'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		$_SESSION["sess_confirm_email"] = $myrow["confirm_email"];
		if ($type == "adv") { header("Location: ".$_SESSION["globsettings"]["site_url"]."management/adv_registration.php?$SLINE"); exit; }
		elseif ($type == "pub") { header("Location: ".$_SESSION["globsettings"]["site_url"]."management/pub_registration.php?$SLINE"); exit; }
		elseif ($type == "mem") { header("Location: ".$_SESSION["globsettings"]["site_url"]."management/mem_registration.php?$SLINE"); exit; }
		else { header("Location: ".$_SESSION["globsettings"]["site_url"]); exit; }
	}
}

//Visitor e-mail confirmationn page
$smarty->assign("curpage","m_email_confirm");
//$smarty->assign("Congratulations",$text_info["c_congratulations"]);
$smarty->assign("confirm_message",$confirm_message);

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array());

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());
$smarty->assign("topmenucolor","#CfCfCf");
$smarty->assign("menucopyright",$text_info["copyright"]);
$smarty->assign("Congratulations",$text_info["sign_up_confirm"]);

//Create help button
smarty_create_helpbutton("welcome.html");

$smarty->assign("AddTopMenu",false);

$smarty->display('s_content_top.tpl');
?>