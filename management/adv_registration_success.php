<?
session_start();

require_once "consts.php";
require_once "language.php";
require_once "connect.inc";
require_once "functions.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";
//member_settings();

doconnect();

get_global_settings();

//Info page
$smarty->assign("curpage","m_registration_success");
$smarty->assign("Congratulations",$text_info["c_congratulations"]);

if (!isset($_SESSION["sess_confirm_email"]) || ($_SESSION["sess_confirm_email"] == "")) {
	$add_info1 = ""; 
	$welcome_cp = $text_info["c_email_added"];
	$add_info2 = $text_info["c_member_confirmemail_info"];
}
else {
	$add_info1 = ""; 
	$welcome_cp = $text_info["c_welcome_member"];
	$add_info2 = ($_SESSION["globsettings"]["member_approved"]) ? $text_info["c_member_add_info"] : "";
	unset($_SESSION["sess_confirm_email"]);
}

$smarty->assign("welcome_cp",$welcome_cp);
$smarty->assign("add_info1",$add_info1);
$smarty->assign("add_info2",$add_info2);

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>$_SESSION["globsettings"]["site_url"],"text"=>$text_info["home"],"spacer"=>""),
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

$smarty->assign("AddTopMenu",false);

$smarty->display('s_content_top.tpl');
?>