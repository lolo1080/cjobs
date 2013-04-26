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
require_once "topmenu_func.php";
check_access(array(2));

doconnect();

$change = get_post_true_false("change","");

//Member profile page
$smarty->assign("curpage","pub_get_job_searchbox");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"pub_get_job_searchbox.php?$SLINE","text"=>$text_info["pub_get_job_searchbox"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

$FormElements = array(
	"job_get_searchbox_code"	=> $text_info["p_job_get_searchbox_code"],
	"job_option1"							=> $text_info["p_job_option1"],
	"job_option1_help"				=> $text_info["h_job_option1"],
	"job_option2"							=> $text_info["p_job_option2"],
	"job_option2_help"				=> $text_info["h_job_option2"],
	"job_option3"							=> $text_info["p_job_option3"],
	"job_option3_help"				=> $text_info["h_job_option3"],
	"job_channel"							=> $text_info["p_job_channel"],
	"job_channel_items"				=> get_job_channel_list(),
	"job_search"							=> $text_info["p_job_search"],
	"job_search_alt"					=> $text_info["p_job_search_alt"],
);

$smarty->assign("LoadColorPickerScript",true);

$smarty->assign("FormElements",$FormElements);

//Create help button
smarty_create_helpbutton("pub_get_job_searchbox.html");

//Create form
$form_capt = $text_info["c_create_a_job_searchbox"];
$smarty->assign("form_header",$form_capt);

//Main site Title
$smarty->assign("main_site_title",$_SESSION["globsettings"]["site_title"]);

//Main site URL
$smarty->assign("site_url",$_SESSION["globsettings"]["site_url"]);

//Publisher ID
$smarty->assign("publisher_id",$_SESSION["sess_userid"]);

//Path to add|delete channel script
$smarty->assign("channel_script",$_SESSION["globsettings"]["site_url"].'management/pub_get_jobroll_chaneel_work.php');

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>