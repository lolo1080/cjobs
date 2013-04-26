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
$smarty->assign("curpage","pub_get_job_textlink");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"pub_get_job_textlink.php?$SLINE","text"=>$text_info["pub_get_job_textlink"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

$FormElements = array(
	"job_get_textlink_code"		=> $text_info["p_job_get_textlink_code"],
	"job_simple_link"					=> $text_info["p_job_simple_link"],
	"job_simple_link_help"		=> str_replace("{*SiteTitle*}",$_SESSION["globsettings"]["site_title"],$text_info["h_job_simple_link"]),
	"job_textlink_title"			=> $text_info["p_job_textlink_title"],
	"job_custom_link"					=> $text_info["p_job_custom_link"],
	"job_custom_link_help"		=> $text_info["h_job_custom_link"],
	"job_textlink_preview"		=> $text_info["p_job_textlink_preview"],
	"job_textlink_what"				=> $text_info["p_job_textlink_what"],
	"job_textlink_where"			=> $text_info["p_job_textlink_where"],
	"job_textlink_title"			=> $text_info["p_job_textlink_title"],
	"job_channel"							=> $text_info["p_job_channel"],
	"job_channel_items"				=> get_job_channel_list(),
	"job_target"							=>  $text_info["p_job_target"],
	"job_target_items"	=> array(
		array("value"=>0, "caption"=>$text_info["p_disp_new_window"]),
		array("value"=>1, "caption"=>$text_info["p_disp_entire_window"]),
	),
);

$smarty->assign("LoadColorPickerScript",true);

$smarty->assign("FormElements",$FormElements);

//Create help button
smarty_create_helpbutton("pub_get_job_textlink.html");

//Create form
$form_capt = $text_info["c_create_a_job_textlink"];
$smarty->assign("form_header",$form_capt);

//Main site title
$smarty->assign("site_title",$_SESSION["globsettings"]["site_title"]);

//Main site URL
$smarty->assign("site_url",$_SESSION["globsettings"]["site_url"]);

//Publisher ID
$smarty->assign("publisher_id",$_SESSION["sess_userid"]);

//Path to add|delete channel script
$smarty->assign("channel_script",$_SESSION["globsettings"]["site_url"].'management/pub_get_jobroll_chaneel_work.php');

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>