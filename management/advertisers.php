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
check_first_entry("advertisers",array());

doconnect();

$start = get_start();
$action= get_get_post_value("action","");
$elist = get_get_post_value2("elist",array());
if (isset($_SESSION["sess_user_subm_type"])) unset($_SESSION["sess_user_subm_type"]);

//Check action. Action == "chstatus" - change user status (Active/Disable); Action == "delete" - delete user
switch ($action) {
	case "chstatus":
		for ($i=0; $i<count($elist); $i++) {
			$elist[$i] = data_addslashes(trim($elist[$i]));
			if (check_int($elist[$i])) mysql_query("UPDATE ".$db_tables["users_advertiser"]." SET isenable=not isenable WHERE uid_adv='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
		}
		//Send event
		$event_array = array("event"=>"update", "source"=>"advertisers", "table"=>"users_advertiser", "ad_id"=>0);
		event_handler($event_array);
	break;
	case "delete":
		for ($i=0; $i<count($elist); $i++) {
			$elist[$i] = data_addslashes(trim($elist[$i]));
			if (check_int($elist[$i])) mysql_query("UPDATE ".$db_tables["users_advertiser"]." SET isdeleted='1',deldate=CURDATE() WHERE uid_adv='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
		}
		//Send event
		$event_array = array("event"=>"update", "source"=>"advertisers", "table"=>"users_advertiser", "ad_id"=>0);
		event_handler($event_array);
	break;
}

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"regdate"=>"datesort", "email"=>"email", "name"=>"name", "isenable"=>"isenable"
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
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("advertisers.php","regdate",$text_info["th_regdate"])),
	array("tdw"=>"200","tdclass"=>"tbl_td_head","data"=>sort_link("advertisers.php","email",$text_info["th_email"])),
	array("tdw"=>"120","tdclass"=>"tbl_td_head","data"=>sort_link("advertisers.php","name",$text_info["th_name"])),
	array("tdw"=>"85", "tdclass"=>"tbl_td_head","data"=>sort_link("advertisers.php","isenable",$text_info["th_status"])),
	array("tdw"=>"160","tdclass"=>"tbl_td_head","data"=>$text_info["th_all_status"]),
	array("tdw"=>"80", "tdclass"=>"tbl_td_head","data"=>$text_info["th_options"])
));

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("fregdate"=>"regdate", "femail"=>"email", "fname"=>"name", "fisenable"=>"isenable"); //"code name" => "database field"
$filter_errorfields = array("fregdate"=>$text_info["th_regdate"], "femail"=>$text_info["th_email"],
		"fname"=>$text_info["th_name"],	"fisenable"=>$text_info["th_status"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","fregdate","filter_checkbox","filter_text_75px",$select_digit_array,$select_digit_values,"filter_select_digit75px","date"),
	get_filter_td("tbl_td_bottom","filter","femail",	"filter_checkbox","filter_text_200px",$select_txt_array,$select_txt_values,"filter_select_text200px","text"),
	get_filter_td("tbl_td_bottom","filter","fname",   "filter_checkbox","filter_text_120px",$select_txt_array,$select_txt_values,"filter_select_text120px","text"),
	get_filter_td("tbl_td_bottom_light","filter","fisenable","filter_checkbox","filter_text_85px",$select_active_array,$select_active_values,"filter_select_active85px","active",false),
);
smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"advertisers.php",2,$FilterElements);
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
$limitation = create_filter_limitation($accordance,$filter_field,$text_field,$select_field,$filter_array," and ",array(""),$having_limitation);
////////////////////// Filter //////////////////////

//Table of content
$num  = 0;
$DataBody = array();
$dev = "&nbsp;/&nbsp;";
$page_count = get_page_count("SELECT count(*) as num FROM ".$db_tables["users_advertiser"]." WHERE isdeleted=0".$limitation,$row_count);
if ($start > ($page_count-1)) $start = $page_count-1;
if ($start < 0) $start = 0;
$from_count = $start*$row_count;
$qr_res = mysql_query("SELECT uid_adv,email,name,regdate as datesort,".format_sql_date("regdate")." as regdate,isenable,isconfirmed ".
		"FROM ".$db_tables["users_advertiser"]." ".
		"WHERE isdeleted=0 $limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
		or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	$str_link_edit = '<a href="advertisers_work.php?action=edit&uid_adv='.$myrow["uid_adv"].'&'.$SLINE.'">'.get_img("infedit.gif",20,20,$text_info["c_einfo"],get_js_action(6)).'</a>';
	if ($myrow["isenable"]) {
		$led_onoff = get_img("ledon.gif",20,20,$text_info["c_chstatus"],get_js_action(7));
		$isenable = $text_info["f_Active"];
	}
	else {
		$led_onoff = get_img("ledoff.gif",20,20,$text_info["c_chstatus"],get_js_action(8));
		$isenable = $text_info["f_Disable"];
	}
	$isconfirmed = ($myrow["isconfirmed"]) ? $text_info["f_Active"] : $text_info["f_Disable"];

//	$all_status_str .= ($myrow["xmlstatus"]) ? $text_info["f_Active"] : $text_info["f_Disable"];
	$str_link_chstatus = '<a href="advertisers.php?action=chstatus&elist[]='.$myrow["uid_adv"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_ch_user_status"].'\')">'.$led_onoff.'</a>';
	$str_link_del = '<a href="advertisers.php?action=delete&elist[]='.$myrow["uid_adv"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_delete_user"].'\')">'.get_img("reject.gif",20,20,$text_info["c_delete"],get_js_action(2)).'</a>';
	
	$DataBody[$num] = array(
		array("tdw"=>"20", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
		array("tdw"=>"35", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>get_type_checkbox("elist[]",$myrow["uid_adv"],"")),
		array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["regdate"]),
		array("tdw"=>"200","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["email"]),
		array("tdw"=>"120","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["name"]),
		array("tdw"=>"85", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$isenable),
		array("tdw"=>"160","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$isconfirmed),
		array("tdw"=>"80", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$str_link_edit.'&nbsp;'.$str_link_chstatus.'&nbsp;'.$str_link_del)
	);
	$num++;
}
$smarty->assign("DataBodyCount",$num);
$smarty->assign("DataBody",$DataBody);

//advertisers page
$smarty->assign("curpage","advertisers");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"advertisers.php?$SLINE","text"=>$text_info["advertisers"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
smarty_create_standard_pagenavigation(true,$text_info["n_pages"],$start,$page_count,"advertisers.php",$text_info["n_pages_from"],"",$text_info["n_go"],5);
	$statuslnk = "javascript: if (confirm('".$text_info["i_start_ch_user_status"]."')) { submit_form('chstatus','mainform'); } void(0)";
  $dellnk = "javascript: if (confirm('".$text_info["i_delete_users"]."')) { submit_form('delete','mainform'); } void(0)";
$smarty->assign("GrayMenuItems",array(
array("link"=>$statuslnk,"text"=>$text_info["n_chstatus"],"title"=>$text_info["c_ch_advertisers_status"],
			"img_name"=>"ledon.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(7),"ascript"=>""),
array("link"=>$dellnk, "text"=>$text_info["n_delsel"], "title"=>$text_info["c_del_users"],
			"img_name"=>"reject.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(2),"ascript"=>"")
));

//Create help button
smarty_create_helpbutton("advertisers.html");

//Create hidden values for form
$smarty->assign("fdata_action","advertisers.php");
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>"chstatus"),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
smarty_create_session_data();

//Save session values for current page
save_session_values("advertisers");

$smarty->display('s_content_top.tpl');
?>