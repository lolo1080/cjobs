<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "functions.php";
require_once "include/other/filter.php";
require_once "include/other/table_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";
check_access(array(0));

doconnect();

//Check first entry
check_first_entry("statistics_clicks",array());
$start	= get_start();

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"actiondate"=>"sc.clicktime", "clicktype"=>"sc.click_type"
); //"code name" => "database field"

$sortfield_array_default = "sc.clicktime"; //default sorting field
$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder","desc"))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$DataHead = array(
	array("tdw"=>"55", "tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"110","tdclass"=>"tbl_td_head","data"=>sort_link("statistics_clicks.php","actiondate",$text_info["th_date"])),
	array("tdw"=>"85", "tdclass"=>"tbl_td_head","data"=>sort_link("statistics_clicks.php","clicktype",$text_info["th_type"])),
	array("tdw"=>"25", "tdclass"=>"tbl_td_head","data"=>$text_info["th_info"])
);
$smarty->assign("DataHead", $DataHead);

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("factiondate"=>"sc.clicktime", "fclicktype"=>"sc.click_type"); //"code name" => "database field"
$filter_errorfields = array("factiondate"=>$text_info["th_date"],
	"fclicktype"=>$text_info["th_type"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","factiondate","filter_checkbox","filter_text_110px",$select_digit_array,$select_digit_values,"filter_select_digit110px","date"),
	get_filter_td("tbl_td_bottom_light","filter","fclicktype","filter_checkbox","filter_text_120px",$select_clicktype_array,$select_clicktype_values,"filter_select_clicktype120px","clicktype",false)
);
smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"statistics_clicks.php",1,$FilterElements);
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

//Table of content
$num  = 0;
$DataBody = array();
$page_count = get_page_count("SELECT count(*) as num FROM ".$db_tables["stats_clicks"]." sc ".$limitation,$row_count);
if ($start > ($page_count-1)) $start = $page_count-1;
if ($start < 0) $start = 0;
$from_count = $start*$row_count;
$qr_res = mysql_query("SELECT sc.*,".format_sql_datetime("sc.clicktime")." as actiondate ".
	"FROM ".$db_tables["stats_clicks"]." sc $limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
	or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	$str_link_info = '<a href="statistics_clicks_work.php?stat_click='.$myrow["stat_click"].'&'.$SLINE.'">'.get_img("arrow.gif",20,20,$text_info["c_minfo"],get_js_action(10)).'</a>';
	$DataBody[$num] = array(
		array("tdw"=>"55", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
		array("tdw"=>"110","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["actiondate"]),
		array("tdw"=>"85", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$select_clicktype_array[$myrow["click_type"]]),
		array("tdw"=>"27", "tdclass"=>"tbl_td_data","tdalign"=>"align=center","data"=>$str_link_info)
	);
	$num++;
}
$smarty->assign("DataBodyCount",$num);
$smarty->assign("DataBody",$DataBody);

//Show Keywords statistics page
$smarty->assign("curpage","statistics_clicks");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"statistics_clicks.php?$SLINE","text"=>$text_info["statistics_clicks"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
smarty_create_standard_pagenavigation(true,$text_info["n_pages"],$start,$page_count,"statistics_clicks.php",$text_info["n_pages_from"],"",$text_info["n_go"],5);
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("statistics_clicks.html");

//Create hidden values for form
$smarty->assign("fdata_action","statistics_clicks.php");
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
smarty_create_session_data();

//Save session values for current page
save_session_values("statistics_clicks");
$smarty->display('s_content_top.tpl');
?>