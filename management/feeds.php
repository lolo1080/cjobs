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
check_first_entry("feeds",array());

doconnect();

$start = get_start();
$action= get_get_post_value("action","");
$elist = get_get_post_value2("elist",array());
if (isset($_SESSION["sess_category_type"])) unset($_SESSION["sess_category_type"]);

//Check action. Action == "chstatus" - change user status (Active/Disable); Action == "delete" - delete user
switch ($action) {
	case "delete":
		for ($i=0; $i<count($elist); $i++) {
			$elist[$i] = data_addslashes(trim($elist[$i]));
			if (check_int($elist[$i])) {
				//Get feed info
				$qr_res = mysql_query("SELECT * FROM ".$db_tables["sites_feed_list"]." WHERE feed_id='{$elist[$i]}'") or query_die(__FILE__,__LINE__,mysql_error());
				if (mysql_num_rows($qr_res) > 0) {
					$myrow = mysql_fetch_array($qr_res);

					mysql_query("DELETE FROM ".$db_tables["sites_feed_list"]." WHERE feed_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
					mysql_query("DELETE FROM ".$db_tables["data_list_stats"]." WHERE feed_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
					mysql_query("DELETE FROM ".$db_tables["xml_feeds_data"]." WHERE feed_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
					mysql_query("DELETE FROM ".$db_tables["xml_feeds_data_temp"]." WHERE feed_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());

					if ($myrow["feed_type"] == "common") {
						mysql_query("DELETE FROM ".$db_tables["data_list"]." WHERE feed_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
						mysql_query("DELETE FROM ".$db_tables["data_list_deleted"]." WHERE feed_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
					}
				}
			}
		}
	break;
}

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"feed_id"=>"feed_id", "regdate"=>"datesort", "title"=>"title", "refresh_rate"=>"refresh_rate", "startparsed"=>"spdatesort"
); //"code name" => "database field"
$sortfield_array_default = "title"; //default sorting field
$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder","asc"))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$smarty->assign("DataHead", array(
	array("tdw"=>"20", "tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"35", "tdclass"=>"tbl_td_head","data"=>get_std_checkbox_list($text_info["th_sel_group"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("feeds.php","feed_id",$text_info["th_id"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("feeds.php","regdate",$text_info["th_regdate"])),
	array("tdw"=>"170","tdclass"=>"tbl_td_head","data"=>sort_link("feeds.php","title",$text_info["th_name"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("feeds.php","refresh_rate",$text_info["th_refresh_rate"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("feeds.php","startparsed",$text_info["th_startparsed"])),
	array("tdw"=>"60", "tdclass"=>"tbl_td_head","data"=>$text_info["th_format"]),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>$text_info["th_status"]),
	array("tdw"=>"80", "tdclass"=>"tbl_td_head","data"=>$text_info["th_options"])
));

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("ffeed_id"=>"feed_id", "fregdate"=>"regdate", "ftitle"=>"title", "frefresh_rate"=>"refresh_rate", "fstartparsed"=>"startparsed"); //"code name" => "database field"
$filter_errorfields = array("ffeed_id"=>$text_info["th_id"], "fregdate"=>$text_info["th_regdate"], "ftitle"=>$text_info["th_name"],
		"frefresh_rate"=>$text_info["th_refresh_rate"], "fstartparsed"=>$text_info["th_startparsed"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","ffeed_id", "filter_checkbox","filter_text_75px",$select_digit_array,$select_digit_values,"filter_select_digit75px","int"),
	get_filter_td("tbl_td_bottom","filter","fregdate","filter_checkbox","filter_text_75px",$select_digit_array,$select_digit_values,"filter_select_digit75px","date"),
	get_filter_td("tbl_td_bottom","filter","ftitle", "filter_checkbox","filter_text_170px",$select_txt_array,$select_txt_values,"filter_select_text170px","text"),
	get_filter_td("tbl_td_bottom","filter","frefresh_rated", "filter_checkbox","filter_text_75px",$select_digit_array,$select_digit_values,"filter_select_digit75px","int"),
	get_filter_td("tbl_td_bottom","filter","fstartparsed","filter_checkbox","filter_text_75px",$select_digit_array,$select_digit_values,"filter_select_digit75px","date")
);
smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"feeds.php",3,$FilterElements);

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
$page_count = get_page_count("SELECT count(*) as num FROM ".$db_tables["sites_feed_list"]." ".$limitation,$row_count);
if ($start > ($page_count-1)) $start = $page_count-1;
if ($start < 0) $start = 0;
$from_count = $start*$row_count;
$qr_res = mysql_query("SELECT *,registered as datesort,".format_sql_date("registered")." as registered, startparsed as spdatesort,".format_sql_date("startparsed")." as startparsed ".
		"FROM ".$db_tables["sites_feed_list"]." ".
		"$limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
		or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	$str_link_edit = '<a href="feeds_work.php?action=edit&feed_id='.$myrow["feed_id"].'&'.$SLINE.'">'.get_img("infedit.gif",20,20,$text_info["c_einfo"],get_js_action(6)).'</a>';
	$isactive = ($myrow["isactive"]) ? $text_info["f_Active"] : $text_info["f_Disable"];
	$str_link_del = '<a href="feeds.php?action=delete&elist[]='.$myrow["feed_id"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_delete_feed"].'\')">'.get_img("reject.gif",20,20,$text_info["c_delete"],get_js_action(2)).'</a>';

	switch ($myrow["feed_format"]) {
		case 'xml':	case 'xml1':
			$str_link_config = '<a href="feeds_xml.php?feed_id='.$myrow["feed_id"].'&'.$SLINE.'">'.get_img("campaings.gif",16,16,$text_info["c_data"],get_js_action(9)).'</a>';
			break;
		case 'xml2':
			$str_link_config = '<a href="feeds_xml2.php?feed_id='.$myrow["feed_id"].'&'.$SLINE.'">'.get_img("campaings.gif",16,16,$text_info["c_data"],get_js_action(9)).'</a>';
			break;
		case 'html':
		case 'html1':
			$str_link_config = '<a href="feeds_html.php?feed_id='.$myrow["feed_id"].'&'.$SLINE.'">'.get_img("campaings.gif",16,16,$text_info["c_data"],get_js_action(9)).'</a>';
			break;
		case 'html2':
			$str_link_config = '<a href="feeds_html2.php?feed_id='.$myrow["feed_id"].'&'.$SLINE.'">'.get_img("campaings.gif",16,16,$text_info["c_data"],get_js_action(9)).'</a>';
			break;
	}

	$DataBody[$num] = array(
		array("tdw"=>"20", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
		array("tdw"=>"35", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>get_type_checkbox("elist[]",$myrow["feed_id"],"")),
		array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["feed_id"]),
		array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["registered"]),
		array("tdw"=>"170","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["title"]),
		array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["refresh_rate"]),
		array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["startparsed"]),
		array("tdw"=>"60", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.strtoupper($myrow["feed_format"])),
		array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$isactive),
		array("tdw"=>"80", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$str_link_edit.'&nbsp;'.$str_link_config.'&nbsp;'.$str_link_del)
	);
	$num++;
}
$smarty->assign("DataBodyCount",$num);
$smarty->assign("DataBody",$DataBody);

//feeds page
$smarty->assign("curpage","feeds");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"feeds.php?$SLINE","text"=>$text_info["feeds"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
smarty_create_standard_pagenavigation(true,$text_info["n_pages"],$start,$page_count,"feeds.php",$text_info["n_pages_from"],"",$text_info["n_go"],5);
  $addlink = 'feeds_work.php?action=add&'.$SLINE;
  $dellnk = "javascript: if (confirm('".$text_info["i_start_delete_feeds"]."')) { submit_form('delete','mainform'); } void(0)";
$smarty->assign("GrayMenuItems",array(
array("link"=>$addlink, "text"=>$text_info["n_add_record"], "title"=>$text_info["n_add_new_record"],
			"img_name"=>"check_on.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(1),"ascript"=>""),
array("link"=>$dellnk, "text"=>$text_info["n_delsel"], "title"=>$text_info["n_delsel_feeds"],
			"img_name"=>"reject.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(2),"ascript"=>"")
));

//Create help button
smarty_create_helpbutton("feeds.html");

//Create hidden values for form
$smarty->assign("fdata_action","feeds.php");
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>"delete"),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
smarty_create_session_data();

//Save session values for current page
save_session_values("feeds");

$smarty->display('s_content_top.tpl');
?>