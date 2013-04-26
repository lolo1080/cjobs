<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "app_events_handler.php";
require_once "functions.php";
require_once "functions_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "include/other/table_mini.php";
require_once "topmenu_func.php";
require_once "jobrollsettings_func.php";
require_once "app_cache_functions.php";
check_access(array(0));

doconnect();

$action = get_get_post_value("action","");
$change = get_post_true_false("change","");

//Check action
if ($change) try_change();
else {
	$jobroll_settings = get_jobroll_settings();
	if (count($jobroll_settings) < 2) critical_error(__FILE__,__LINE__,"Cannot get jobroll settings.");
	create_values(
		$jobroll_settings["job_set_colors_bg"],						$jobroll_settings["job_set_colors_title"],
		$jobroll_settings["job_set_colors_border"],				$jobroll_settings["job_set_colors_job_title"],
		$jobroll_settings["job_set_colors_text"],					$jobroll_settings["job_set_colors_company"],
		$jobroll_settings["job_set_colors_link"],					$jobroll_settings["job_set_colors_source"],
		$jobroll_settings["job_set_colors_accent"],				$jobroll_settings["job_set_colors_location"]
	);
}

//Global settings page
$smarty->assign("curpage","jobrollsettings");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"jobrollsettings.php?$SLINE","text"=>$text_info["jobrollsettings"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

$smarty->assign("LoadColorPickerScript",true);

//Create help button
smarty_create_helpbutton("jobrollsettings.html");

//Create form
$form_capt = $text_info["c_edit"].$text_info["jobrollsettings"];
smarty_create_cform("frm","mainform","POST","jobrollsettings.php","","",5,$form_capt,3,100,5,200,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"change","bvalue"=>$text_info["btn_change"] ,"bscript"=>""),
));

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>