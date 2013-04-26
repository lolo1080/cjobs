<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "functions.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";
check_access(array(2));

doconnect();

//Member profile page
$smarty->assign("curpage","pub_get_xmlfeed");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"pub_get_xmlfeed.php?$SLINE","text"=>$text_info["pub_get_xmlfeed"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

$FormElements = array(
	"job_attribution"					=> $text_info["p_job_attribution"],
	"job_attribution_help"		=> $text_info["h_job_attribution"],
	"job_search"							=> $text_info["p_job_search"],
	"job_search_alt"					=> $text_info["p_job_search_alt"],
	"job_sample_request"			=> $text_info["p_job_sample_request"],
	"job_sample_rhelp1"				=> str_replace("{*EMail*}",get_admin_email_free(),$text_info["p_job_sample_rhelp1"]),
	"job_sample_rhelp2"				=> $text_info["p_job_sample_rhelp2"],
	"job_sample_rhelp3"				=> $text_info["p_job_sample_rhelp3"],
	"job_sample_rhelp4"				=> $text_info["p_job_sample_rhelp4"],
	"job_sample_rhelp5"				=> $text_info["p_job_sample_rhelp5"],
	"job_sample_rhelp6"				=> $text_info["p_job_sample_rhelp6"],
	"job_params_publisher_id"	=> str_replace("{*uid_pub*}",$_SESSION["sess_userid"],$text_info["p_job_params_publisher_id"]),
	"job_params_what"					=> $text_info["p_job_params_what"],
	"job_params_where"				=> $text_info["p_job_params_where"],
	"job_params_sort"					=> $text_info["p_job_params_sort"],
	"job_params_radius"				=> $text_info["p_job_params_radius"],
	"job_params_site_type"		=> $text_info["p_job_params_site_type"],
	"job_params_job_type"			=> $text_info["p_job_params_job_type"],
	"job_params_start"				=> $text_info["p_job_params_start"],
	"job_params_limit"				=> $text_info["p_job_params_limit"],
	"job_params_fromage"			=> $text_info["p_job_params_fromage"],
	"job_params_highlight"		=> $text_info["p_job_params_highlight"],
	"job_params_latlong"			=> $text_info["p_job_params_latlong"],
	"job_params_userip"				=> $text_info["p_job_params_userip"],
	"job_params_useragent"		=> $text_info["p_job_params_useragent"],
);

$smarty->assign("LoadColorPickerScript",true);

$smarty->assign("FormElements",$FormElements);

//Create help button
smarty_create_helpbutton("pub_get_xmlfeed.html");

//Create form
$form_capt = $text_info["c_create_a_job_xmlfeed"];
$smarty->assign("form_header",$form_capt);

//Main site title
//$smarty->assign("site_title",$_SESSION["globsettings"]["site_title"]);

//Main site URL
$smarty->assign("site_url",$_SESSION["globsettings"]["site_url"]);

//Publisher ID
$smarty->assign("publisher_id",$_SESSION["sess_userid"]);

//Path xml search script
$smarty->assign("xmlsearch_script",$_SESSION["globsettings"]["site_url"].'xmlsearch.php');

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>