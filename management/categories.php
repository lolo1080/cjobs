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
check_first_entry("categories",array());

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
				mysql_query("DELETE FROM ".$db_tables["jobcategories"]." WHERE cat_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
				mysql_query("DELETE FROM ".$db_tables["data_list"]." WHERE cat_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
				mysql_query("DELETE FROM ".$db_tables["data_list_advertiser"]." WHERE cat_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
				mysql_query("DELETE FROM ".$db_tables["data_list_deleted"]." WHERE cat_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
				mysql_query("DELETE FROM ".$db_tables["xml_feeds_data"]." WHERE cat_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
				mysql_query("DELETE FROM ".$db_tables["xml_feeds_data_temp"]." WHERE cat_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
				mysql_query("DELETE FROM ".$db_tables["xml2_feeds_category_keywords"]." WHERE cat_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
			}
		}
	break;
}

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"cat_id"=>"cat_id", "cat_name"=>"cat_name", "cat_key"=>"cat_key"
); //"code name" => "database field"
$sortfield_array_default = "cat_id"; //default sorting field
$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder","asc"))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$smarty->assign("DataHead", array(
	array("tdw"=>"20", "tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"35", "tdclass"=>"tbl_td_head","data"=>get_std_checkbox_list($text_info["th_sel_group"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("categories.php","cat_id",$text_info["th_id"])),
	array("tdw"=>"255","tdclass"=>"tbl_td_head","data"=>sort_link("categories.php","cat_name",$text_info["th_name"])),
	array("tdw"=>"255","tdclass"=>"tbl_td_head","data"=>sort_link("categories.php","cat_key",$text_info["th_key"])),
	array("tdw"=>"60", "tdclass"=>"tbl_td_head","data"=>$text_info["th_options"])
));

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("fcat_id"=>"cat_id", "fcat_name"=>"cat_name", "fcat_key"=>"cat_key"); //"code name" => "database field"
$filter_errorfields = array("fcat_id"=>$text_info["th_id"], "fcat_name"=>$text_info["th_name"],
		"fcat_key"=>$text_info["th_key"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","fcat_id", "filter_checkbox","filter_text_75px",$select_digit_array,$select_digit_values,"filter_select_digit75px","int"),
	get_filter_td("tbl_td_bottom","filter","fcat_name", "filter_checkbox","filter_text_255px",$select_txt_array,$select_txt_values,"filter_select_text255px","text"),
	get_filter_td("tbl_td_bottom","filter","fcat_key",  "filter_checkbox","filter_text_255px",$select_txt_array,$select_txt_values,"filter_select_text255px","text")
);
smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"categories.php",1,$FilterElements);

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
$page_count = get_page_count("SELECT count(*) as num FROM ".$db_tables["jobcategories"]." ".$limitation,$row_count);
if ($start > ($page_count-1)) $start = $page_count-1;
if ($start < 0) $start = 0;
$from_count = $start*$row_count;
$qr_res = mysql_query("SELECT * FROM ".$db_tables["jobcategories"]." ".
		"$limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
		or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	$str_link_edit = '<a href="categories_work.php?action=edit&cat_id='.$myrow["cat_id"].'&'.$SLINE.'">'.get_img("infedit.gif",20,20,$text_info["c_einfo"],get_js_action(6)).'</a>';
	$str_link_del = '<a href="categories.php?action=delete&elist[]='.$myrow["cat_id"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_delete_category"].'\')">'.get_img("reject.gif",20,20,$text_info["c_delete"],get_js_action(2)).'</a>';
	$DataBody[$num] = array(
		array("tdw"=>"20", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
		array("tdw"=>"35", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>get_type_checkbox("elist[]",$myrow["cat_id"],"")),
		array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["cat_id"]),
		array("tdw"=>"255","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["cat_name"]),
		array("tdw"=>"255","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["cat_key"]),
		array("tdw"=>"60", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$str_link_edit.'&nbsp;'.$str_link_del)
	);
	$num++;
}
$smarty->assign("DataBodyCount",$num);
$smarty->assign("DataBody",$DataBody);

//categories page
$smarty->assign("curpage","categories");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"categories.php?$SLINE","text"=>$text_info["categories"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
smarty_create_standard_pagenavigation(true,$text_info["n_pages"],$start,$page_count,"categories.php",$text_info["n_pages_from"],"",$text_info["n_go"],5);
  $addlink = 'categories_work.php?action=add&'.$SLINE;
  $dellnk = "javascript: if (confirm('".$text_info["i_start_delete_categories"]."')) { submit_form('delete','mainform'); } void(0)";
$smarty->assign("GrayMenuItems",array(
array("link"=>$addlink, "text"=>$text_info["n_add_record"], "title"=>$text_info["n_add_new_record"],
			"img_name"=>"check_on.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(1),"ascript"=>""),
array("link"=>$dellnk, "text"=>$text_info["n_delsel"], "title"=>$text_info["n_delsel_categories"],
			"img_name"=>"reject.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(2),"ascript"=>"")
));

//Create help button
smarty_create_helpbutton("categories.html");

//Create hidden values for form
$smarty->assign("fdata_action","categories.php");
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>"delete"),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
smarty_create_session_data();

//Save session values for current page
save_session_values("categories");

$smarty->display('s_content_top.tpl');
?>