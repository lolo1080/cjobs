<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "functions.php";
require_once "functions_mini.php";
require_once "include/other/filter.php";
require_once "include/other/table_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";
check_access(array(0,1));

//Check first entry
check_first_entry("adv_fund_history",array());

//Check admin user
if ($_SESSION["sess_user"] == "0") {
	$uid_adv = get_get_value("uid_adv","");
	$uid_adv = check_sess_id_values($uid_adv,"uid_adv");
	if ($uid_adv == "") critical_error(__FILE__,__LINE__,"No Advertiser ID");
}
elseif ($_SESSION["sess_user"] == "1") {
	$uid_adv = $_SESSION["sess_userid"];
}

doconnect();

$start = get_start();

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"regdate"=>"datesort", "amount"=>"amount", "paytype"=>"paytype", "batchnum"=>"batchnum"
); //"code name" => "database field"
$sortfield_array_default = "datesort"; //default sorting field
$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder","DESC"))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$smarty->assign("DataHead", array(
	array("tdw"=>"55", "tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("adv_fund_history.php","regdate",$text_info["th_regdate"])),
	array("tdw"=>"105","tdclass"=>"tbl_td_head","data"=>sort_link("adv_fund_history.php","amount",$text_info["th_amount"])),
	array("tdw"=>"110","tdclass"=>"tbl_td_head","data"=>sort_link("adv_fund_history.php","paytype",$text_info["th_paytype"])),
	array("tdw"=>"180","tdclass"=>"tbl_td_head","data"=>sort_link("adv_fund_history.php","batchnum",$text_info["th_batchnum"])),
	array("tdw"=>"60", "tdclass"=>"tbl_td_head","data"=>$text_info["th_options"])
));

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("fregdate"=>"regdate", "famount"=>"amount", "fpaytype"=>"paytype", "fbatchnum"=>"batchnum"); //"code name" => "database field"
$filter_errorfields = array("fregdate"=>$text_info["th_regdate"], "famount"=>$text_info["th_amount"],
		"fpaytype"=>$text_info["th_paytype"],	"fbatchnum"=>$text_info["th_batchnum"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter", "fregdate","filter_checkbox","filter_text_75px",$select_digit_array,$select_digit_values,"filter_select_digit75px","date"),
	get_filter_td("tbl_td_bottom","filter", "famount", "filter_checkbox","filter_text_105px",$select_digit_array,$select_digit_values,"filter_select_digit105px","int"),
	get_filter_td("tbl_td_bottom_light","filter","fpaytype","filter_checkbox","filter_text_110px",$select_paysystem_array,$select_paysystem_values,"filter_select_paysystem110px","paytype",false),
	get_filter_td("tbl_td_bottom","filter", "fbatchnum",   "filter_checkbox","filter_text_180px",$select_txt_array,$select_txt_values,"filter_select_text180px","text")
);
smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"adv_fund_history.php",1,$FilterElements);
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
$limitation = create_filter_limitation($accordance,$filter_field,$text_field,$select_field,$filter_array," and ",array(""),$having_limitation);
////////////////////// Filter //////////////////////

//Table of content
$num  = 0;
$DataBody = array();
$dev = "&nbsp;/&nbsp;";
$page_count = get_page_count("SELECT count(*) as num FROM ".$db_tables["payments_adv"]." WHERE uid_adv='$uid_adv' ".$limitation,$row_count);
if ($start > ($page_count-1)) $start = $page_count-1;
if ($start < 0) $start = 0;
$from_count = $start*$row_count;
$qr_res = mysql_query("SELECT pid,amount,paytype,batchnum,regtime as datesort,".format_sql_date("regtime")." as regdate ".
		"FROM ".$db_tables["payments_adv"]." ".
		"WHERE uid_adv='$uid_adv' $limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
		or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	$str_link_details = '<a href="adv_fund_history_work.php?pid='.$myrow["pid"].'&'.$SLINE.'">'.get_img("arrow.gif",20,20,$text_info["c_minfo"],get_js_action(10)).'</a>';
	$DataBody[$num] = array(
		array("tdw"=>"55", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
		array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["regdate"]),
		array("tdw"=>"105","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["amount"]),
		array("tdw"=>"110","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$payment_types[$myrow["paytype"]]),
		array("tdw"=>"180","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["batchnum"]),
		array("tdw"=>"60", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$str_link_details)
	);
	$num++;
}
$smarty->assign("DataBodyCount",$num);
$smarty->assign("DataBody",$DataBody);

//adv_fund_history page
$smarty->assign("curpage","adv_fund_history");

//Create line with page navigation
$smarty->assign("navwidth","10");
if ($_SESSION["sess_user"] == "0") {
	$user_info = get_member_main_info($db_tables["users_advertiser"],"uid_adv",$uid_adv);
	$c_einfo = ($user_info["result"]) ? $text_info["c_einfo"].' ('.$user_info["email"].')' : $text_info["c_einfo"];
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"advertisers.php?$SLINE","text"=>$text_info["advertisers"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>1,"href"=>"advertisers_work.php?action=edit&uid_adv=".$uid_adv."&".$SLINE,"text"=>$c_einfo,"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>0,"href"=>"","text"=>$text_info["c_fund_history"],"spacer"=>"")
	));
}
else
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"adv_fund_history.php?$SLINE","text"=>$text_info["adv_fund_history"],"spacer"=>"")
	));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
smarty_create_standard_pagenavigation(true,$text_info["n_pages"],$start,$page_count,"adv_fund_history.php",$text_info["n_pages_from"],"",$text_info["n_go"],5);
	$statuslnk = "javascript: if (confirm('".$text_info["i_start_ch_user_status"]."')) { submit_form('chstatus','mainform'); } void(0)";
  $dellnk = "javascript: if (confirm('".$text_info["i_delete_users"]."')) { submit_form('delete','mainform'); } void(0)";
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("adv_fund_history.html");

//Create hidden values for form
$smarty->assign("fdata_action","adv_fund_history.php");
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>""),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
smarty_create_session_data();

//Save session values for current page
save_session_values("adv_fund_history");

$smarty->display('s_content_top.tpl');
?>