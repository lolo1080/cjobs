<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "consts_smarty.php"; //Main settings for Smarty
require_once "functions.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";  //Create top menu
check_access(array(0));

//Welcome page
$smarty->assign("curpage","welcome");
$smarty->assign("Greetings",$text_info["c_greetings"]);
$smarty->assign("welcome_cp",$text_info["c_welcome_cp"]);
$smarty->assign("info_cp",$text_info["c_info_cp"]);
$smarty->assign("info_help",$text_info["c_info_help"]);

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
array("islink"=>1,"href"=>"welcome.php?$SLINE","text"=>$text_info["welcome"],"spacer"=>"")
));

//Create help button
smarty_create_helpbutton("welcome.html");

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>