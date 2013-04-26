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
check_first_entry("jobs_list_common",array());

doconnect();

$start = get_start();
$action= get_get_post_value("action","");
$elist = get_get_post_value2("elist",array());

//Check action. Action == "chstatus" - change user status (Active/Disable); Action == "delete" - delete user
switch ($action) {
	case "delete":
		for ($i=0; $i<count($elist); $i++) {
			$elist[$i] = data_addslashes(trim($elist[$i]));
			if (check_int($elist[$i])) mysql_query("DELETE FROM ".$db_tables["data_list"]." WHERE data_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
		}
	break;
}

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"regdate"=>"datesort", "feed"=>"feed_title", "title"=>"d.title", "company_name"=>"d.company_name", "url"=>"d.url"
); //"code name" => "database field"
$sortfield_array_default = "datesort"; //default sorting field
$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder","desc"))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$smarty->assign("DataHead", array(
	array("tdw"=>"20", "tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"35", "tdclass"=>"tbl_td_head","data"=>get_std_checkbox_list($text_info["th_sel_group"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("jobs_list_common.php","regdate",$text_info["th_regdate"])),
	array("tdw"=>"120","tdclass"=>"tbl_td_head","data"=>sort_link("jobs_list_common.php","feed",$text_info["th_feed"])),
	array("tdw"=>"255","tdclass"=>"tbl_td_head","data"=>sort_link("jobs_list_common.php","title",$text_info["th_title"])),
	array("tdw"=>"120","tdclass"=>"tbl_td_head","data"=>sort_link("jobs_list_common.php","company_name",$text_info["th_company_name"])),
	array("tdw"=>"200","tdclass"=>"tbl_td_head","data"=>sort_link("jobs_list_common.php","url",$text_info["th_url"])),
	array("tdw"=>"60", "tdclass"=>"tbl_td_head","data"=>$text_info["th_options"])
));

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("fregdate"=>"dateinsert", "ffeed"=>"f.title", "ftitle"=>"d.title", "fcompany_name"=>"d.company_name", "furl"=>"d.url"); //"code name" => "database field"
$filter_errorfields = array("fregdate"=>$text_info["th_regdate"], "ffeed"=>$text_info["th_feed"], "ftitle"=>$text_info["th_title"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","fregdate","filter_checkbox","filter_text_75px",$select_digit_array,$select_digit_values,"filter_select_digit75px","date"),
	get_filter_td("tbl_td_bottom","filter","ffeed",	"filter_checkbox","filter_text_120px",$select_txt_array,$select_txt_values,"filter_select_text120px","text"),
	get_filter_td("tbl_td_bottom","filter","ftitle", "filter_checkbox","filter_text_255px",$select_txt_array,$select_txt_values,"filter_select_text255px","text"),
	get_filter_td("tbl_td_bottom","filter","fcompany_name",  "filter_checkbox","filter_text_120px",$select_txt_array,$select_txt_values,"filter_select_text120px","text"),
	get_filter_td("tbl_td_bottom","filter","furl",  "filter_checkbox","filter_text_200px",$select_txt_array,$select_txt_values,"filter_select_text200px","text")
);
smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"jobs_list_common.php",1,$FilterElements);

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
$dev = "&nbsp;/&nbsp;";

$page_count = get_page_count_by_rows("SELECT d.data_id, f.title as feed_title ".
		"FROM ".$db_tables["data_list"]." d ".
		"INNER JOIN ".$db_tables["sites_feed_list"]." f ON d.feed_id=f.feed_id ".
		"$limitation",$row_count);
if ($start > ($page_count-1)) $start = $page_count-1;
if ($start < 0) $start = 0;
$from_count = $start*$row_count;
$qr_res = mysql_query("SELECT d.*,d.dateinsert as datesort,".format_sql_date("d.dateinsert")." as regdate, f.title as feed_title ".
		"FROM ".$db_tables["data_list"]." d ".
		"INNER JOIN ".$db_tables["sites_feed_list"]." f ON d.feed_id=f.feed_id ".
		"$limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
		or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	$str_link_edit = '<a href="jobs_list_common_work.php?action=edit&data_id='.$myrow["data_id"].'&'.$SLINE.'">'.get_img("infedit.gif",20,20,$text_info["c_einfo"],get_js_action(6)).'</a>';
	$str_link_del = '<a href="jobs_list_common.php?action=delete&elist[]='.$myrow["data_id"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_delete_job"].'\')">'.get_img("reject.gif",20,20,$text_info["c_delete"],get_js_action(2)).'</a>';
	$DataBody[$num] = array(
		array("tdw"=>"20", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
		array("tdw"=>"35", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>get_type_checkbox("elist[]",$myrow["data_id"],"")),
		array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["regdate"]),
		array("tdw"=>"120","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["feed_title"]),
		array("tdw"=>"255","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["title"]),
		array("tdw"=>"120","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["company_name"]),
		array("tdw"=>"200","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["url"]),
		array("tdw"=>"60", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$str_link_edit.'&nbsp;'.$str_link_del)
	);
	$num++;
}
$smarty->assign("DataBodyCount",$num);
$smarty->assign("DataBody",$DataBody);

//jobs_list_common page
$smarty->assign("curpage","jobs_list_common");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"jobs_list_common.php?$SLINE","text"=>$text_info["jobs_list_common"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
smarty_create_standard_pagenavigation(true,$text_info["n_pages"],$start,$page_count,"jobs_list_common.php",$text_info["n_pages_from"],"",$text_info["n_go"],5);
  $dellnk = "javascript: if (confirm('".$text_info["i_delete_jobs"]."')) { submit_form('delete','mainform'); } void(0)";
$smarty->assign("GrayMenuItems",array(
array("link"=>$dellnk, "text"=>$text_info["n_delsel"], "title"=>$text_info["c_del_jobs"],
			"img_name"=>"reject.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(2),"ascript"=>"")
));

//Create help button
smarty_create_helpbutton("jobs_list_common.html");

//Create hidden values for form
$smarty->assign("fdata_action","jobs_list_common.php");
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>"delete"),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
smarty_create_session_data();

//Save session values for current page
save_session_values("jobs_list_common");

$smarty->display('s_content_top.tpl');
?>