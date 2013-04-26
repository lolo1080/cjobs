<?
session_start();

require_once "consts.php";
require_once "language.php";
require_once "connect.inc";
require_once "functions.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";

doconnect();

get_global_settings();

$user_type= get_get_post_value("user_type","");

//Info page
$smarty->assign("curpage","forgot_pass_success");
$smarty->assign("Congratulations",$text_info["c_congratulations"]);
$smarty->assign("add_info1",$text_info["c_password_was_send"]);
$smarty->assign("add_info2",str_replace("{*login_url_txt*}",$_SESSION["globsettings"]["site_url"].$user_type.'/',$text_info["c_please_login"]));

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