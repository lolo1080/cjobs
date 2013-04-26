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
check_first_entry("statistics_ip",array());
$start = get_start();

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"ip"=>"si.ip", "search_amount"=>"search_amount", "clicks_amount"=>"clicks_amount"
); //"code name" => "database field"

$sortfield_array_default = "si.ip"; //default sorting field
$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder","desc"))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$DataHead = array(
	array("tdw"=>"55", "tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"100","tdclass"=>"tbl_td_head","data"=>sort_link("statistics_ip.php","ip",$text_info["th_ip"])),
	array("tdw"=>"100","tdclass"=>"tbl_td_head","data"=>sort_link("statistics_ip.php","search_amount",$text_info["th_t_search_amount"])),
	array("tdw"=>"100","tdclass"=>"tbl_td_head","data"=>sort_link("statistics_ip.php","clicks_amount",$text_info["th_t_clicks_amount"]))
);
$smarty->assign("DataHead", $DataHead);

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("fip"=>"si.ip", "fsearch_amount"=>"search_amount", "fclicks_amount"=>"clicks_amount"); //"code name" => "database field"
$filter_errorfields = array("fip"=>$text_info["th_ip"],
	"fsearch_amount"=>$text_info["th_t_search_amount"], "fclicks_amount"=>$text_info["th_t_clicks_amount"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","fip",           "filter_checkbox","filter_text_100px",$select_txt_array,$select_txt_values,"filter_select_text100px","text"),
	get_filter_td("tbl_td_bottom","filter","fsearch_amount","filter_checkbox","filter_text_100px",$select_digit_array,$select_digit_values,"filter_select_digit100px","int"),
	get_filter_td("tbl_td_bottom","filter","fclicks_amount","filter_checkbox","filter_text_100px",$select_digit_array,$select_digit_values,"filter_select_digit100px","int")
);

smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"statistics_ip.php",0,$FilterElements);
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
$limitation = create_filter_limitation($accordance,$filter_field,$text_field,$select_field,$filter_array," WHERE ",array("search_amount","clicks_amount"),$having_limitation);
////////////////////// Filter //////////////////////

//Table of content
$num  = 0;
$DataBody = array();
$qr_res = mysql_query("SELECT si.ip as ip, count(distinct(sk.stat_vi)) as search_amount, ".
	"count(distinct(sc.stat_kid)) as clicks_amount ".
	"FROM ".$db_tables["stats_visitor_info"]." si ".
	"LEFT JOIN ".$db_tables["stats_search_keywords"]." sk ON si.stat_vi=sk.stat_vi ".
	"LEFT JOIN ".$db_tables["stats_clicks"]." sc ON sk.stat_kid=sc.stat_kid ".
	"$limitation GROUP BY ip $having_limitation") or query_die(__FILE__,__LINE__,mysql_error());
$page_count = ceil(mysql_num_rows($qr_res)/$row_count);
if ($start > ($page_count-1)) $start = $page_count-1;
if ($start < 0) $start = 0;
$from_count = $start*$row_count;
$qr_res = mysql_query("SELECT si.ip as ip, count(distinct(sk.stat_vi)) as search_amount, ".
	"count(distinct(sc.stat_kid)) as clicks_amount ".
	"FROM ".$db_tables["stats_visitor_info"]." si ".
	"LEFT JOIN ".$db_tables["stats_search_keywords"]." sk ON si.stat_vi=sk.stat_vi ".
	"LEFT JOIN ".$db_tables["stats_clicks"]." sc ON sk.stat_kid=sc.stat_kid ".
	"$limitation GROUP BY ip $having_limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
		or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	$DataBody[$num] = array(
		array("tdw"=>"55", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
		array("tdw"=>"100","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["ip"]),
		array("tdw"=>"100","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["search_amount"]),
		array("tdw"=>"100","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["clicks_amount"])
	);
	$num++;
}
$smarty->assign("DataBodyCount",$num);
$smarty->assign("DataBody",$DataBody);

//Show Keywords statistics page
$smarty->assign("curpage","statistics_ip");

//Create line with page navigation
$smarty->assign("navwidth","10");
//Check uid (udi == number - stats only for one user; uid == all stats for all users)
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"statistics_ip.php?action=0&$SLINE","text"=>$text_info["statistics_ip"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
smarty_create_standard_pagenavigation(true,$text_info["n_pages"],$start,$page_count,"statistics_ip.php",$text_info["n_pages_from"],"",$text_info["n_go"],5);
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("statistics_ip.html");

//Create hidden values for form
$smarty->assign("fdata_action","statistics_ip.php");
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
smarty_create_session_data();

//Save session values for current page
save_session_values("statistics_ip");
$smarty->display('s_content_top.tpl');
?>