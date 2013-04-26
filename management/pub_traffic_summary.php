<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "connect.inc";
require_once "language.php";
require_once "consts_smarty.php";
require_once "topmenu_func.php";
require_once "app_events_handler.php";
require_once "functions.php";
require_once "include/other/filter.php";
require_once "include/other/table_mini.php";
require_once "include/functions/functions_smartry.php";
require_once "functions_mini.php";
require_once "pub_traffic_summary_func.php";
require_once "app_cache_functions.php";
check_access(array(0,2));

//Check first entry
check_first_entry("pub_traffic_summary",array("sess_aara_job_ads_id"));

doconnect();

$start 	= get_start();
$channel_id	= data_addslashes(trim(get_get_post_value("job_ads_id","")));

//Admin:
$uid_pub	= get_get_value("uid_pub","");
$uid_pub	= check_sess_id_values($uid_pub,"adm_uid_pub");
if (($_SESSION["sess_user"] != "0") && ($uid_pub != "")) critical_error(__FILE__,__LINE__,"Incorrect params list.");

//Statistic:
$period = get_post_value("period","");
$date_from = get_post_value("date_from","");
$date_to = get_post_value("date_to","");

$period = check_sess_id_values($period,"sk_period");
$date_from = check_sess_id_values($date_from,"sk_date_from");
$date_to = check_sess_id_values($date_to,"sk_date_to");

$_SESSION["sess_sk_period"] = $period;
$_SESSION["sess_sk_date_from"] = $date_from;
$_SESSION["sess_sk_date_to"] = $date_to;

//Calendar
$calendar_button = get_calendar("date_from",$usersettings["dateformat_c"],"calbtn_from","span_from");
$calendar_button1 = get_calendar("date_to",$usersettings["dateformat_c"],"calbtn_to","span_to");

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"actiondate"=>"datesort", "clicks"=>"clicks", "pubviews"=>"pubviews", "earnclicks"=>"earnclicks", "ctr"=>"ctr", "cpm"=>"cpm",
	"earn"=>"earn"
); //"code name" => "database field"
$sortfield_array_default = "datesort"; //default sorting field
$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder","DESC"))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$smarty->assign("DataHead", array(
	array("tdw"=>"55", "tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("pub_traffic_summary.php","actiondate",$text_info["th_date"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("pub_traffic_summary.php","clicks",$text_info["th_clicks"])),
	array("tdw"=>"85", "tdclass"=>"tbl_td_head","data"=>sort_link("pub_traffic_summary.php","pubviews",$text_info["th_pubviews"])),
	array("tdw"=>"85", "tdclass"=>"tbl_td_head","data"=>sort_link("pub_traffic_summary.php","earnclicks",$text_info["th_earnclicks"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("pub_traffic_summary.php","ctr",$text_info["th_ctr"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("pub_traffic_summary.php","cpm",$text_info["th_cpm"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("pub_traffic_summary.php","earn",$text_info["th_earn"]))
));

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("factiondate"=>"actiondate", "fclicks"=>"clicks",	"fpubviews"=>"pubviews", "fearnclicks"=>"earnclicks",
	"fctr"=>"ctr", "fcpm"=>"cpm", "fearn"=>"earn"); //"code name" => "database field"
$filter_errorfields = array("factiondate"=>$text_info["th_date"],	"fclicks"=>$text_info["th_clicks"],	
	"pubviews"=>$text_info["th_pubviews"],	"earnclicks"=>$text_info["th_earnclicks"], "fctr"=>$text_info["th_ctr"],
	"fcpm"=>$text_info["th_cpm"],	"fearn"=>$text_info["th_earn"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","factiondate","filter_checkbox","filter_text_75px",$select_digit_array,$select_digit_values,"filter_select_digit75px","date"),
	get_filter_td("tbl_td_bottom","filter","fclicks",    "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom","filter","fpubviews",  "filter_checkbox","filter_text_85px",$select_float_array,$select_float_values,"filter_select_digit85px","float"),
	get_filter_td("tbl_td_bottom","filter","fearnclicks","filter_checkbox","filter_text_85px",$select_float_array,$select_float_values,"filter_select_digit85px","float"),
	get_filter_td("tbl_td_bottom","filter","fctr",       "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom","filter","fcpm",       "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom","filter","fearn",      "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float")
);
smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"pub_traffic_summary.php",0,$FilterElements);
$smarty->assign("FilterColspan",1);
//Check error
if ($my_error != "") {
	//Load start filter
	load_old_filter($filter_field,$text_field,$select_field,$filter_array);
	//Create error message
	smarty_create_message("error","abort.gif",$my_error);
}
//Save filter in session
set_old_session($filter_field,$text_field,$select_field,$filter_array);
//SQL query (with filter limitation)
$having_limitation = "";
$limitation = create_filter_limitation($accordance,$filter_field,$text_field,$select_field,$filter_array," WHERE ",array(""),$having_limitation);
////////////////////// Filter //////////////////////

get_period_info($period);

//Show Keywords statistics page
$smarty->assign("curpage","pub_traffic_summary");

//Create line with page navigation
$smarty->assign("navwidth","10");
if ($_SESSION["sess_user"] == "0")
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"publishers_work.php?action=edit&uid_pub={$uid_pub}&{$SLINE}","text"=>$text_info["publishers"].' ('.get_publisher_name_by_id($uid_pub).') ',"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>1,"href"=>"pub_traffic_summary.php?$SLINE","text"=>$text_info["pub_traffic_summary"],"spacer"=>"")
	));
else
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"pub_traffic_summary.php?$SLINE","text"=>$text_info["pub_traffic_summary"],"spacer"=>"")
	));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
$channel_list = get_std_job_channel_list($channel_id);
$period_capt = array($text_info["n_t"],$text_info["n_tw"],$text_info["n_tm"],$text_info["n_ty"],$text_info["n_all"],$text_info["n_dr"]);
smarty_create_stats_selecet_pagenavigation(true,$text_info["n_pages"],$start,$page_count,"pub_traffic_summary.php",$text_info["n_pages_from"],"",$text_info["n_go"],5,$period,$period_capt,$text_info["n_data_limit"].$text_info["n_period"],$text_info["n_from"],$date_from,$text_info["n_to"],$date_to,$channel_list,$text_info["n_channel_limit"]);
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("pub_traffic_summary.html");

//Create hidden values for form
$smarty->assign("fdata_action","pub_traffic_summary.php");
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Calendar
$smarty->assign("LoadCalendarScript",true);

smarty_create_session_data();

//Save session values for current page
save_session_values("pub_traffic_summary");

$smarty->display('s_content_top.tpl');
?>