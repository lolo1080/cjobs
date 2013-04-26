<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "app_events_handler.php";
require_once "functions.php";
require_once "include/other/filter.php";
require_once "include/other/table_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";
require_once "app_cache_functions.php";
check_access(array(0));

//Check first entry
check_first_entry("subm_job_ads",array());

doconnect();

$start = get_start();
$action = get_get_post_value("action","");
$elist = get_get_post_value2("elist",array());

if ($action == "approve") { //"approve" - approve job Ads
	for ($i=0; $i<count($elist); $i++)
	{
		if (check_int(trim($elist[$i]))) {
			mysql_query("UPDATE ".$db_tables["job_ads"]." SET status=0 WHERE job_ads_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
			//Send event
			$event_array = array("event"=>"chstatus", "source"=>"adv_advertisements", "table"=>"job_ads", "job_ads_id"=>$elist[$i]);
			event_handler($event_array);
		}
	}
}
elseif ($action == "delete") { //"delete" - deelte job Ads
	for ($i=0; $i<count($elist); $i++)
	{
		if (check_int(trim($elist[$i]))) {
			mysql_query("DELETE FROM ".$db_tables["job_ads"]." WHERE job_ads_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
			//Send event
			$event_array = array("event"=>"delete", "source"=>"adv_advertisements", "table"=>"job_ads", "ad_id"=>$elist[$i]);
			event_handler($event_array);
		}
	}
}

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"ad_name"=>"ad_name", "destination_url"=>"destination_url", "status"=>"status"
); //"code name" => "database field"
$sortfield_array_default = "status"; //default sorting field

$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder","desc"))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$smarty->assign("DataHead", array(
	array("tdw"=>"20", "tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"35", "tdclass"=>"tbl_td_head","data"=>get_std_checkbox_list($text_info["th_sel_group"])),
	array("tdw"=>"180","tdclass"=>"tbl_td_head","data"=>sort_link("subm_job_ads.php","ad_name",$text_info["th_ad_name"])),
	array("tdw"=>"120","tdclass"=>"tbl_td_head","data"=>sort_link("subm_job_ads.php","destination_url",$text_info["th_destination_url"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("subm_job_ads.php","status",$text_info["th_status"])),
	array("tdw"=>"80", "tdclass"=>"tbl_td_head","data"=>$text_info["th_options"])
));

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("fad_name"=>"ad_name", "fdestination_url"=>"destination_url", "fstatus"=>"status"); //"code name" => "database field"
$filter_errorfields = array("fad_name"=>$text_info["th_ad_name"], "fdestination_url"=>$text_info["th_destination_url"],
	"fstatus"=>$text_info["th_status"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","fad_name",   "filter_checkbox","filter_text_180px",$select_txt_array,$select_txt_values,"filter_select_text180px","text"),
	get_filter_td("tbl_td_bottom","filter","fdestination_url",    "filter_checkbox","filter_text_120px",$select_txt_array,$select_txt_values,"filter_select_text120px","text"),
	get_filter_td("tbl_td_bottom_light","filter","fstatus","filter_checkbox","filter_text_85px",$select_smbactive_array,$select_smbactive_values,"filter_select_active85px","smbactive",false),
);
smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"subm_job_ads.php",1,$FilterElements);
//$smarty->assign("FilterColspan",2);
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

//Table of content
$num  = 0;
$DataBody = array();
$page_count = get_page_count("SELECT count(*) as num FROM ".$db_tables["job_ads"]." $limitation",$row_count);
if ($start > ($page_count-1)) $start = $page_count-1;
if ($start < 0) $start = 0;
$from_count = $start*$row_count;
$qr_res = mysql_query("SELECT * FROM ".$db_tables["job_ads"]." $limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
		or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	$approve_lnk = '<a href="subm_job_ads.php?action=approve&elist[]='.$myrow["job_ads_id"].'&'.$SLINE.'">'.get_img("check_on.gif",20,20,$text_info["c_approve"],get_js_action(1)).'</a>';
	$delete_lnk = '<a href="subm_job_ads.php?action=delete&elist[]='.$myrow["job_ads_id"].'&'.$SLINE.'">'.get_img("reject.gif",20,20,$text_info["c_reject"],get_js_action(2)).'</a>';
	$status = "";
	switch($myrow["status"]) {
		case "0": $status = $text_info["p_disable"]; $approve_lnk = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; break;
		case "1": $status = $text_info["p_active"]; $approve_lnk = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; break;
		case "2": $status = $text_info["p_panding"]; break;
	}
	$DataBody[$num] = array(
		array("tdw"=>"20", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
		array("tdw"=>"35", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>get_type_checkbox("elist[]",$myrow["job_ads_id"],"")),
		array("tdw"=>"180","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["ad_name"]),
		array("tdw"=>"120","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["destination_url"]),
		array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$status),
		array("tdw"=>"80", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$approve_lnk.'&nbsp;'.$delete_lnk)
	);
	$num++;
}
$smarty->assign("DataBodyCount",$num);
$smarty->assign("DataBody",$DataBody);

//Submissions XML Feed page
$smarty->assign("curpage","subm_job_ads");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"subm_job_ads.php?$SLINE","text"=>$text_info["subm_job_ads"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
smarty_create_standard_pagenavigation(true,$text_info["n_pages"],$start,$page_count,"subm_job_ads.php",$text_info["n_pages_from"],"",$text_info["n_go"],5);
  $approvelnk = "javascript: if (confirm('".$text_info["i_approve_submissions"]."')) { submit_form('approve','mainform'); } void(0)";
  $deletelnk = "javascript: if (confirm('".$text_info["i_delete_submissions"]."')) { submit_form('delete','mainform'); } void(0)";
$smarty->assign("GrayMenuItems",array(
array("link"=>$approvelnk,"text"=>$text_info["n_approvesel"],"title"=>$text_info["c_approve_submissions"],
			"img_name"=>"check_on.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(1),"ascript"=>""),
array("link"=>$deletelnk, "text"=>$text_info["n_delsel"], "title"=>$text_info["c_delete_submissions"],
			"img_name"=>"reject.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(2),"ascript"=>"")
));

//Create help button
smarty_create_helpbutton("subm_job_ads.html");

//Create hidden values for form
$smarty->assign("fdata_action","subm_job_ads.php");
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>$action),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
smarty_create_session_data();

//Save session values for current page
save_session_values("subm_job_ads");

$smarty->display('s_content_top.tpl');
?>