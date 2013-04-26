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
require_once "adv_advertisements_func.php";
require_once "app_cache_functions.php";
check_access(array(0,1));

//Check first entry
check_first_entry("adv_advertisements",array(""));

doconnect();

$start	= get_start();

$ad_id	= get_get_value("ad_id","");
$action	= get_get_value("action","");
$type		= get_get_value("type","");

$uid_adv	= get_get_value("uid_adv","");
$uid_adv	= check_sess_id_values($uid_adv,"uid_adv");
if (($_SESSION["sess_user"] != "0") && ($uid_adv != "")) critical_error(__FILE__,__LINE__,"Incorrect params list.");
if ($_SESSION["sess_user"] == "0") $query_uid_adv = $uid_adv;
else $query_uid_adv = $_SESSION["sess_userid"];

if (($action == 'chstatus') && ($ad_id != "") && check_int($ad_id))
{
	if ($type == "") critical_error(__FILE__,__LINE__,"Cannot find Ad type.");
	$ad_id = data_addslashes(trim($ad_id));

	//Change status of Keyword Ad
	if ($type == 1) {
		if (!is_this_user_current_ad($ad_id,$_SESSION["sess_userid"]) && ($_SESSION["sess_user"] != 0)) critical_error(__FILE__,__LINE__,"Access violation. Another advertiser keyword Ad ID.");
		$qr_res = mysql_query("SELECT status FROM ".$db_tables["ads"]." WHERE ad_id='$ad_id' and uid_adv='{$query_uid_adv}'") or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res) > 0) {
			$myrow = mysql_fetch_array($qr_res);
			if (in_array($myrow["status"], array(0,1))) {
				mysql_query("UPDATE ".$db_tables["ads"]." SET status=not status WHERE ad_id='$ad_id'") or query_die(__FILE__,__LINE__,mysql_error());
				//Send event
				$event_array = array("event"=>"chstatus", "source"=>"adv_advertisements", "table"=>"ads", "ad_id"=>$ad_id);
				event_handler($event_array);
			}
		}
	}
	//Change status of Job Ad
	elseif ($type == 2)	{
		if (!is_this_user_current_job_ad($ad_id,$_SESSION["sess_userid"]) && ($_SESSION["sess_user"] != 0)) critical_error(__FILE__,__LINE__,"Access violation. Another advertiser job Ad ID.");
		$qr_res = mysql_query("SELECT status FROM ".$db_tables["job_ads"]." WHERE job_ads_id='$ad_id' and uid_adv='{$query_uid_adv}'") or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res) > 0) {
			$myrow = mysql_fetch_array($qr_res);
			if (in_array($myrow["status"], array(0,1))) {
				mysql_query("UPDATE ".$db_tables["job_ads"]." SET status=not status WHERE job_ads_id='$ad_id'") or query_die(__FILE__,__LINE__,mysql_error());
				//Send event
				$event_array = array("event"=>"chstatus", "source"=>"adv_advertisements", "table"=>"job_ads", "job_ads_id"=>$ad_id);
				event_handler($event_array);
			}
		}
	}
}
elseif (($action == 'delete') && ($ad_id != "") && check_int($ad_id))
{
	$ad_id = data_addslashes(trim($ad_id));
	//Change status of Keyword Ad
	if ($type == 1) {
		if (!is_this_user_current_ad($ad_id,$_SESSION["sess_userid"]) && ($_SESSION["sess_user"] != 0)) critical_error(__FILE__,__LINE__,"Access violation. Another advertiser Ad ID.");
		mysql_query("DELETE FROM ".$db_tables["ads"]." WHERE ad_id='$ad_id'") or query_die(__FILE__,__LINE__,mysql_error());
		//Send event
		$event_array = array("event"=>"delete", "source"=>"adv_advertisements", "table"=>"ads", "ad_id"=>$ad_id);
		event_handler($event_array);
	}
	elseif ($type == 2)	{
		if (!is_this_user_current_job_ad($ad_id,$_SESSION["sess_userid"]) && ($_SESSION["sess_user"] != 0)) critical_error(__FILE__,__LINE__,"Access violation. Another advertiser job Ad ID.");
		mysql_query("DELETE FROM ".$db_tables["job_ads"]." WHERE job_ads_id='$ad_id'") or query_die(__FILE__,__LINE__,mysql_error());
		mysql_query("DELETE FROM ".$db_tables["data_list_advertiser"]." WHERE feed_id='$ad_id'") or query_die(__FILE__,__LINE__,mysql_error());
		//Send event
		$event_array = array("event"=>"delete", "source"=>"adv_advertisements", "table"=>"job_ads", "ad_id"=>$ad_id);
		event_handler($event_array);
	}
}


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
	"ad_name"=>"ad_name", "ad_type"=>"ad_type", "clicks"=>"clicks", "adviews"=>"adviews", "ctr"=>"ctr",
	"avg_cpc"=>"avg_cpc", "cost"=>"cost", "avg_pos"=>"avg_pos", "ad_status"=>"ad_status"
); //"code name" => "database field"
$sortfield_array_default = "ad_type"; //default sorting field
$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder",""))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$smarty->assign("DataHead", array(
	array("tdw"=>"55", "tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"120","tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisements.php","ad_name",$text_info["th_advertisement"])),
	array("tdw"=>"128","tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisements.php","ad_type",$text_info["th_type"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisements.php","clicks",$text_info["th_clicks"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisements.php","adviews",$text_info["th_adviews"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisements.php","ctr",$text_info["th_ctr"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisements.php","avg_cpc",$text_info["th_avg_cpc"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisements.php","cost",$text_info["th_cost"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisements.php","avg_pos",$text_info["th_avg_pos"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("adv_advertisements.php","ad_status",$text_info["th_status"])),
	array("tdw"=>"100","tdclass"=>"tbl_td_head","data"=>$text_info["th_options"])
));

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array(
	"fad_name"=>"ad_name", "fad_type"=>"ad_type", "fclicks"=>"clicks", "fadviews"=>"adviews", "fctr"=>"ctr",
	"favg_cpc"=>"avg_cpc", "fcost"=>"cost", "favg_pos"=>"avg_pos", "fad_status"=>"ad_status"); //"code name" => "database field"
$filter_errorfields = array("fad_name"=>$text_info["th_advertisement"], "fad_type"=>$text_info["th_type"],
	"fclicks"=>$text_info["th_clicks"], "fadviews"=>$text_info["th_adviews"],	"fctr"=>$text_info["th_ctr"],
	"favg_cpc"=>$text_info["th_avg_cpc"], "fcost"=>$text_info["th_cost"], "favg_pos"=>$text_info["th_avg_pos"],
	"fad_status"=>$text_info["th_status"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","fad_name","filter_checkbox","filter_text_120px",$select_txt_array,$select_txt_values,"filter_select_text120px","text"),
	get_filter_td("tbl_td_bottom_light","filter1","fad_type","filter_checkbox","filter_text_128px",$select_adtype_array,$select_adtype_values,"filter_select_adtype128px","keyword_ad",false),
	get_filter_td("tbl_td_bottom","filter","fclicks",    "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom","filter","fadviews",   "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom","filter","fctr",       "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom","filter","favg_cpc",   "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom","filter","fcost",      "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom","filter","favg_pos",   "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom_light","filter1","fad_status","filter_checkbox","filter_text_85px",$select_active_panding_array,$select_active_panding_values,"filter_select_active85px","active",false)
);

smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"adv_advertisements.php",1,$FilterElements);
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
$limitation = create_filter_limitation($accordance,$filter_field,$text_field,$select_field,$filter_array," WHERE ",array("amount_total","amount_site",/*"amount_toolbar",*/"amount_xml"),$having_limitation);
////////////////////// Filter //////////////////////

get_period_info($period);

//Show Keywords statistics page
$smarty->assign("curpage","adv_advertisements");

//Create line with page navigation
$smarty->assign("navwidth","10");
if ($_SESSION["sess_user"] == "0")
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"advertisers_work.php?action=edit&uid_adv={$uid_adv}&{$SLINE}","text"=>$text_info["advertisers"].' ('.get_advertiser_name_by_id($uid_adv).') ',"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>1,"href"=>"adv_advertisements.php?".$SLINE,"text"=>$text_info["adv_advertisements"],"spacer"=>"")
	));
else
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"adv_advertisements.php?".$SLINE,"text"=>$text_info["adv_advertisements"],"spacer"=>"")
	));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
$period_capt = array($text_info["n_t"],$text_info["n_tw"],$text_info["n_tm"],$text_info["n_ty"],$text_info["n_all"],$text_info["n_dr"]);
smarty_create_stats_pagenavigation(false,$text_info["n_pages"],$start,$page_count,"adv_advertisements.php",$text_info["n_pages_from"],"",$text_info["n_go"],5,$period,$period_capt,$text_info["n_data_limit"].$text_info["n_period"],$text_info["n_from"],$date_from,$text_info["n_to"],$date_to);
$smarty->assign("GrayMenuItems",array());

//Create gray menu items
if ($_SESSION["sess_user"] == "0") {
	$smarty->assign("SubGrayMenuItems",array());
}
else {
	$create_keyword_ad = 'adv_advertisement_keyword_ad_work.php?action=add&'.$SLINE;
	$create_jobs_from_my_site = 'adv_advertisement_jobs_from_my_site_work.php?action=add&'.$SLINE;
	$smarty->assign("SubGrayMenuItems",array(
	array("link"=>$create_keyword_ad,"text"=>$text_info["c_create_keyword_ad"],"title"=>$text_info["c_create_keyword_ad"],
				"img_name"=>"check_on.gif","img_w"=>"20","img_h"=>"20",
				"jsaction"=>get_js_action(1),"ascript"=>""),
	array("link"=>$create_jobs_from_my_site, "text"=>$text_info["c_create_jobs_from_my_site"], "title"=>$text_info["c_create_jobs_from_my_site"],
				"img_name"=>"check_on.gif","img_w"=>"20","img_h"=>"20",
				"jsaction"=>get_js_action(1),"ascript"=>"")
	));
}
$smarty->assign("GrayMenuButtons_PageNavigation_and_PagePeriodSelect",true);

//Create help button
smarty_create_helpbutton("adv_advertisements.html");

//Create hidden values for form
$smarty->assign("fdata_action","adv_advertisements.php");
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Calendar
$smarty->assign("LoadCalendarScript",true);

smarty_create_session_data();

//Save session values for current page
save_session_values("adv_advertisements");

$smarty->display('s_content_top.tpl');
?>