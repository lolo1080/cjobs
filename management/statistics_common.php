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
require_once "statistics_common_func.php";
require_once "app_cache_functions.php";
check_access(array(0));

//Check first entry
check_first_entry("statistics_common",array(""));

doconnect();

$start	= get_start();

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
	"actiondate"=>"actiondate"
); //"code name" => "database field"
$sortfield_array_default = "actiondate"; //default sorting field
$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder","desc"))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$smarty->assign("DataHead", array(
	array("tdw"=>"55","tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"75","tdclass"=>"tbl_td_head","data"=>sort_link("statistics_common.php","actiondate",$text_info["th_date"])),
	array("tdw"=>"100","tdclass"=>"tbl_td_head","data"=>$text_info["th_visitors_amount"]),
	array("tdw"=>"90","tdclass"=>"tbl_td_head","data"=>$text_info["th_search_amount"]),
	array("tdw"=>"90","tdclass"=>"tbl_td_head","data"=>$text_info["th_clicks_amount"]),
	array("tdw"=>"150","tdclass"=>"tbl_td_head","data"=>$text_info["th_advertisements_pageview"]),
	array("tdw"=>"130","tdclass"=>"tbl_td_head","data"=>$text_info["th_advertisements_clicks"]),
	array("tdw"=>"160","tdclass"=>"tbl_td_head","data"=>$text_info["th_pub_traffic_summary"])
));

$smarty->assign("AddFilter",false);

get_period_info($period);

//Show Keywords statistics page
$smarty->assign("curpage","statistics_common");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"statistics_common.php","text"=>$text_info["c_common_statistics"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
$period_capt = array($text_info["n_t"],$text_info["n_tw"],$text_info["n_tm"],$text_info["n_ty"],$text_info["n_all"],$text_info["n_dr"]);
smarty_create_stats_pagenavigation(true,$text_info["n_pages"],$start,$page_count,"statistics_common.php",$text_info["n_pages_from"],"",$text_info["n_go"],5,$period,$period_capt,$text_info["n_period"],$text_info["n_from"],$date_from,$text_info["n_to"],$date_to);
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("statistics_common.html");

//Create hidden values for form
$smarty->assign("fdata_action","statistics_common.php");
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Calendar
$smarty->assign("LoadCalendarScript",true);

smarty_create_session_data();

//Save session values for current page
save_session_values("statistics_common");

$smarty->display('s_content_top.tpl');
?>