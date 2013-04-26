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
require_once "adv_advertisement_report_job_func.php";
require_once "app_cache_functions.php";
check_access(array(0,1));

//Check first entry
check_first_entry("adv_advertisement_report_job",array("sess_aara_job_ads_id"));

doconnect();

$start 	= get_start();
$job_ads_id	= data_addslashes(trim(get_get_post_value("job_ads_id","")));

$job_ads_id	= check_sess_id_values($job_ads_id,"aara_job_ads_id");

if (!is_this_user_current_job_ad($job_ads_id,$_SESSION["sess_userid"])) critical_error(__FILE__,__LINE__,"Access violation. Another advertiser Ad ID.");

$uid_adv	= get_get_value("uid_adv","");
$uid_adv	= check_sess_id_values($uid_adv,"adm_uid_adv");
if (($_SESSION["sess_user"] != "0") && ($uid_adv != "")) critical_error(__FILE__,__LINE__,"Incorrect params list.");

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
	"actiondate"=>"datesort", "clicks"=>"clicks", "adviews"=>"adviews",	"ctr"=>"ctr",	"avg_cpc"=>"avg_cpc", "cost"=>"cost",
	"avg_pos"=>"avg_pos"
); //"code name" => "database field"
$sortfield_array_default = "datesort"; //default sorting field
$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder","DESC"))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$smarty->assign("DataHead", array(
	array("tdw"=>"55", "tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisement_report_job.php","actiondate",$text_info["th_date"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisement_report_job.php","clicks",$text_info["th_clicks"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisement_report_job.php","adviews",$text_info["th_adviews"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisement_report_job.php","ctr",$text_info["th_ctr"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisement_report_job.php","avg_cpc",$text_info["th_avg_cpc"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisement_report_job.php","cost",$text_info["th_cost"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisement_report_job.php","avg_pos",$text_info["th_avg_pos"]))
));

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("factiondate"=>"actiondate", "fclicks"=>"clicks",	"fadviews"=>"adviews", "fctr"=>"ctr",	"favg_cpc"=>"avg_cpc",
	"fcost"=>"cost", "favg_pos"=>"avg_pos"); //"code name" => "database field"
$filter_errorfields = array("factiondate"=>$text_info["th_date"],	"fclicks"=>$text_info["th_clicks"],	
	"fadviews"=>$text_info["th_adviews"],	"fctr"=>$text_info["th_ctr"], "favg_cpc"=>$text_info["th_avg_cpc"],
	"fcost"=>$text_info["th_cost"],	"favg_pos"=>$text_info["th_avg_pos"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","factiondate","filter_checkbox","filter_text_75px",$select_digit_array,$select_digit_values,"filter_select_digit75px","date"),
	get_filter_td("tbl_td_bottom","filter","fclicks",    "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom","filter","fadviews",   "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom","filter","fctr",       "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom","filter","favg_cpc",   "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom","filter","fcost",      "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom","filter","favg_pos",   "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float")
);
smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"adv_advertisement_report_job.php",0,$FilterElements);
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
$smarty->assign("curpage","adv_advertisement_report_job");

//Create line with page navigation
$ad_name_text = get_job_ad_name($job_ads_id);
$smarty->assign("navwidth","10");
if ($_SESSION["sess_user"] == "0")
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"advertisers_work.php?action=edit&uid_adv={$uid_adv}&{$SLINE}","text"=>$text_info["advertisers"].' ('.get_advertiser_name_by_id($uid_adv).') ',"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>1,"href"=>"adv_advertisements.php?$SLINE","text"=>$text_info["adv_advertisements"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>0,"href"=>"","text"=>$text_info["adv_advertisement_report_job"].' ('.$ad_name_text.')',"spacer"=>"")
	));
else
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"adv_advertisements.php?$SLINE","text"=>$text_info["adv_advertisements"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>0,"href"=>"","text"=>$text_info["adv_advertisement_report_job"].' ('.$ad_name_text.')',"spacer"=>"")
	));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
$smarty->assign("GrayMenuItems",array());

$period_capt = array($text_info["n_t"],$text_info["n_tw"],$text_info["n_tm"],$text_info["n_ty"],$text_info["n_all"],$text_info["n_dr"]);
smarty_create_stats_pagenavigation(false,$text_info["n_pages"],$start,$page_count,"adv_advertisement_report_job.php",$text_info["n_pages_from"],"",$text_info["n_go"],5,$period,$period_capt,$text_info["n_data_limit"].$text_info["n_period"],$text_info["n_from"],$date_from,$text_info["n_to"],$date_to);
$edit_keyword_ad = 'adv_advertisement_jobs_from_my_site_work.php?action=edit&job_ads_id='.$job_ads_id.'&'.$SLINE;
if ($_SESSION["sess_user"] == "0") {
	$smarty->assign("SubGrayMenuItems",array());
}
else {
	$smarty->assign("SubGrayMenuItems",array(
	array("link"=>$edit_keyword_ad,"text"=>$text_info["n_edit_job_advertisement_ad"],"title"=>$text_info["c_edit_cur_job_advertisement_ad"],
				"img_name"=>"check_on.gif","img_w"=>"20","img_h"=>"20",
				"jsaction"=>get_js_action(1),"ascript"=>"")
	));
}
$smarty->assign("GrayMenuButtons_PageNavigation_and_PagePeriodSelect",true);

//Create help button
smarty_create_helpbutton("adv_advertisement_report_job.html");

//Create hidden values for form
$smarty->assign("fdata_action","adv_advertisement_report_job.php");
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>"chstatus"),
	array("fname"=>"job_ads_id","fvalue"=>$job_ads_id),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Calendar
$smarty->assign("LoadCalendarScript",true);

smarty_create_session_data();

//Save session values for current page
save_session_values("adv_advertisement_report_job");

$smarty->display('s_content_top.tpl');
?>