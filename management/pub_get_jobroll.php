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
$smarty->assign("curpage","pub_get_jobroll");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"pub_get_jobroll.php?$SLINE","text"=>$text_info["pub_get_jobroll"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

$FormElements = array(
	"job_format"					=> $text_info["p_format"],
	"job_search"					=> $text_info["p_job_search"],
	"job_search_basic"		=> $text_info["p_job_search_basic"],
	"job_search_advanced"	=> $text_info["p_job_search_advanced"],
	"job_what"						=> $text_info["p_job_what"],
	"job_with_all"				=> $text_info["p_job_with_all"],
	"job_exact_phrase"		=> $text_info["p_job_exact_phrase"],
	"job_at_least_one"		=> $text_info["p_job_at_least_one"],
	"job_none"						=> $text_info["p_job_none"],
	"job_title"						=> $text_info["p_job_title"],
	"job_company"					=> $text_info["p_job_company"],
	"job_type"						=> $text_info["p_job_type"],
	"job_type_items"	=> array(
		array("value"=>"all", "caption"=>$text_info["p_all_job_types"]),
		array("value"=>"fulltime", "caption"=>$text_info["p_full_time"]),
		array("value"=>"parttime", "caption"=>$text_info["p_part_time"]),
		array("value"=>"contract", "caption"=>$text_info["p_contract"]),
		array("value"=>"internship", "caption"=>$text_info["p_internship"]),
		array("value"=>"temporary", "caption"=>$text_info["p_temporary"])
	),
	"job_show_from"				=> $text_info["p_job_show_from"],
	"job_show_from_items"	=> array(
		array("value"=>"all", "caption"=>$text_info["p_all_web_sites"]),
		array("value"=>"jobboard", "caption"=>$text_info["p_job_boards_only"]),
		array("value"=>"employer", "caption"=>$text_info["p_employer_web_sites_only"])
	),
	"job_exclude_staffing"=>$text_info["p_job_exclude_staffing"],
	"job_where"						=>$text_info["p_job_where"],
	"job_where_viewers_location"	=> $text_info["p_job_where_viewers_location"],
	"job_where_set_location"			=> $text_info["p_job_where_set_location"],
	"job_country"					=> $text_info["p_job_country"],
	"job_country_items"		=> get_country_jobroll_list(),
	"job_city_state"			=> $text_info["p_job_city_state"],
	"job_channel"					=> $text_info["p_job_channel"],
	"job_channel_items"				=> get_job_channel_list(),
	"job_sr_colors"						=> $text_info["p_job_sr_colors"],
	"job_sr_colors_default"		=> $text_info["p_job_sr_colors_default"],
	"job_sr_colors_set_colors"=> $text_info["p_job_sr_colors_set_colors"],
	"job_set_colors"					=> $text_info["p_job_set_colors"],
	"job_set_colors_bg"				=> $text_info["p_job_colors_bg"],
	"job_set_colors_title"		=> $text_info["p_job_set_colors_title"],
	"job_set_colors_border"		=> $text_info["p_job_set_colors_border"],
	"job_set_colors_job_title"=> $text_info["p_job_set_colors_job_title"],
	"job_set_colors_text"			=> $text_info["p_job_set_colors_text"],
	"job_set_colors_company"	=> $text_info["p_job_set_colors_company"],
	"job_set_colors_link"			=> $text_info["p_job_set_colors_link"],
	"job_set_colors_source"		=> $text_info["p_job_set_colors_source"],
	"job_set_colors_accent"		=> $text_info["p_job_set_colors_accent"],
	"job_set_colors_location"	=> $text_info["p_job_set_colors_location"],
	"job_code"								=> $text_info["p_job_code"],
	"job_code_help"						=> $text_info["h_job_code"],
);


$smarty->assign("LoadColorPickerScript",true);

$smarty->assign("FormElements",$FormElements);

//Create help button
smarty_create_helpbutton("pub_get_jobroll.html");

//Create form
$form_capt = $text_info["c_create_a_jobroll"];
$smarty->assign("form_header",$form_capt);
$preview_form_capt = $text_info["c_jobroll_preview"];
$smarty->assign("form_preview_header",$preview_form_capt);

//Publisher ID
$smarty->assign("jobroll_publisher_id",$_SESSION["sess_userid"]);

//Path to add|delete channel script
$smarty->assign("channel_script",$_SESSION["globsettings"]["site_url"].'management/pub_get_jobroll_chaneel_work.php');

//Path to show jobroll PHP script
$smarty->assign("adsshowjobs_php_script",$_SESSION["globsettings"]["site_url"].'adsshowjobs.php');
//Path to show jobroll JS script
$smarty->assign("adsshowjobs_js_script",$_SESSION["globsettings"]["site_url"].'adsshowjobs.js');

//Jobroll satrt colors array
$smarty->assign("jobroll_start_colors",get_jobroll_colors(true));

//Get Jobroll Button
$GetJobrollCodeButton = array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"getjobrollcode","bvalue"=>$text_info["btn_getjobrollcode"],"bscript"=>'onClick="show_hide_jobroll_code(\'create_jobroll_table\');return false;" onSubmit="return false;"')
);
$smarty->assign("GetJobrollCodeButton",$GetJobrollCodeButton);

//Edit Jobroll Code & New Jobroll Butttons
$EtidNewJobrollCodeButtons = array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"editjobrollcode","bvalue"=>$text_info["btn_editjobrollcode"],"bscript"=>'onClick="show_hide_jobroll_code(\'editnew_jobroll_table\');return false;" onSubmit="return false;"'),
	array("btn_classnum"=>"2","btype"=>"submit","bname"=>"newjobrollcode","bvalue"=>$text_info["btn_newjobrollcode"],"bscript"=>'onClick="document.form[0].reset();" onSubmit="return false;"')
);
$smarty->assign("EtidNewJobrollCodeButtons",$EtidNewJobrollCodeButtons);
$smarty->assign("btnspace",10);

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>