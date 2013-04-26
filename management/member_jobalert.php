<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "app_events_handler.php";
require_once "functions.php";
require_once "functions_mini.php";
require_once "include/other/filter.php";
require_once "include/other/table_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";
check_access(array(0,3));

//Check first entry
check_first_entry("member_jobalert",array());

function get_jobalert_keywords($job_alert)
{
	$js_vals = array("what","where","as_all","as_phrase","as_any","as_not","as_title","as_company");
	$keyword = "";
	for ($i=0; $i<count($js_vals); $i++)
	{
		$keyword .= (preg_match("~<{$js_vals[$i]}>(.*?)</{$js_vals[$i]}>~i", $job_alert, $matches)) ? ' '.$matches[1] : '';
	}
 return $keyword;
}

doconnect();

$start = get_start();
$action= get_get_post_value("action","");
$elist = get_get_post_value2("elist",array());

//Check admin user
if ($_SESSION["sess_user"] == "0") {
	$uid_mem = get_get_value("uid_mem","");
	$uid_mem = check_sess_id_values($uid_mem,"uid_mem");
	if ($uid_mem == "") critical_error(__FILE__,__LINE__,"No Member ID");
}
elseif ($_SESSION["sess_user"] == "3") {
	$uid_mem = $_SESSION["sess_userid"];
}


//Check action. Action == "chstatus" - change user status (Active/Disable); Action == "delete" - delete user
switch ($action) {
	case "chstatus":
		for ($i=0; $i<count($elist); $i++) {
			$elist[$i] = data_addslashes(trim($elist[$i]));
			if (check_int($elist[$i])) mysql_query("UPDATE ".$db_tables["member_job_alerts"]." SET status=not status WHERE ja_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
		}
	break;
	case "delete":
		for ($i=0; $i<count($elist); $i++) {
			$elist[$i] = data_addslashes(trim($elist[$i]));
			if (check_int($elist[$i])) mysql_query("DELETE FROM ".$db_tables["member_job_alerts"]." WHERE ja_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
		}
	break;
}

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"regdate"=>"datesort", "name"=>"name", "deliver"=>"deliver", "status"=>"status"
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
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("members.php","regdate",$text_info["th_regdate"])),
	array("tdw"=>"120","tdclass"=>"tbl_td_head","data"=>sort_link("members.php","name",$text_info["th_name"])),
	array("tdw"=>"85", "tdclass"=>"tbl_td_head","data"=>sort_link("members.php","deliver",$text_info["th_deliver"])),
	array("tdw"=>"85", "tdclass"=>"tbl_td_head","data"=>sort_link("members.php","status",$text_info["th_status"])),
	array("tdw"=>"200","tdclass"=>"tbl_td_head","data"=>$text_info["th_keywords"]),
	array("tdw"=>"80", "tdclass"=>"tbl_td_head","data"=>$text_info["th_options"])
));

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("fregdate"=>"regdate", "fname"=>"name", "fdeliver"=>"deliver", "fstatus"=>"status"); //"code name" => "database field"
$filter_errorfields = array("fregdate"=>$text_info["th_regdate"], "fname"=>$text_info["th_name"],
		"fdeliver"=>$text_info["th_deliver"], "fstatus"=>$text_info["th_status"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","fregdate","filter_checkbox","filter_text_75px",$select_digit_array,$select_digit_values,"filter_select_digit75px","date"),
	get_filter_td("tbl_td_bottom","filter","fname", 	"filter_checkbox","filter_text_120px",$select_txt_array,$select_txt_values,"filter_select_text120px","text"),
	get_filter_td("tbl_td_bottom_light","filter","fdeliver","filter_checkbox","filter_text_85px",$select_deliver_array,$select_deliver_values,"filter_select_active85px","active",false),
	get_filter_td("tbl_td_bottom_light","filter","fstatus","filter_checkbox","filter_text_85px",$select_active_array,$select_active_values,"filter_select_active85px","active",false)
);
smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"member_jobalert.php",2,$FilterElements);

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
$page_count = get_page_count("SELECT count(*) as num FROM ".$db_tables["member_job_alerts"]." WHERE uid_mem='{$uid_mem}' ".$limitation,$row_count);
if ($start > ($page_count-1)) $start = $page_count-1;
if ($start < 0) $start = 0;
$from_count = $start*$row_count;
$qr_res = mysql_query("SELECT *,regdate as datesort,".format_sql_date("regdate")." as regdate ".
		"FROM ".$db_tables["member_job_alerts"]." ".
		"WHERE uid_mem='{$uid_mem}' ".
		"$limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
		or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	$str_link_edit = '<a href="member_jobalert_work.php?action=edit&ja_id='.$myrow["ja_id"].'&'.$SLINE.'">'.get_img("infedit.gif",20,20,$text_info["c_einfo"],get_js_action(6)).'</a>';
	$str_link_del = '<a href="member_jobalert.php?action=delete&elist[]='.$myrow["ja_id"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_delete_user"].'\')">'.get_img("reject.gif",20,20,$text_info["c_delete"],get_js_action(2)).'</a>';
	if ($myrow["status"]) {
		$led_onoff = get_img("ledon.gif",20,20,$text_info["c_chstatus"],get_js_action(7));
		$isenable = $text_info["f_Active"];
	}
	else {
		$led_onoff = get_img("ledoff.gif",20,20,$text_info["c_chstatus"],get_js_action(8));
		$isenable = $text_info["f_Disable"];
	}
	$str_link_chstatus = '<a href="member_jobalert.php?action=chstatus&elist[]='.$myrow["ja_id"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_ch_ja_status"].'\')">'.$led_onoff.'</a>';
	$DataBody[$num] = array(
		array("tdw"=>"20", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
		array("tdw"=>"35", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>get_type_checkbox("elist[]",$myrow["ja_id"],"")),
		array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["regdate"]),
		array("tdw"=>"120","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["name"]),
		array("tdw"=>"85", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["deliver"]),
		array("tdw"=>"85", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$isenable),
		array("tdw"=>"200","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.get_jobalert_keywords($myrow["job_alert"])),
		array("tdw"=>"80", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$str_link_edit.'&nbsp;'.$str_link_chstatus.'&nbsp;'.$str_link_del)
	);
	$num++;
}
$smarty->assign("DataBodyCount",$num);
$smarty->assign("DataBody",$DataBody);

//members page
$smarty->assign("curpage","member_jobalert");

//Create line with page navigation
$smarty->assign("navwidth","10");
if ($_SESSION["sess_user"] == "0") {
	$user_info = get_member_main_info2($db_tables["users_member"],"uid_mem",$uid_mem);
	$c_einfo = ($user_info["result"]) ? $text_info["c_einfo"].' ('.$user_info["email"].')' : $text_info["c_einfo"];
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"members.php?$SLINE","text"=>$text_info["members"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>1,"href"=>"members_work.php?action=edit&uid_mem=".$uid_mem."&".$SLINE,"text"=>$c_einfo,"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>0,"href"=>"","text"=>$text_info["member_jobalert"],"spacer"=>""),
	));
}
else
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"member_jobalert.php?$SLINE","text"=>$text_info["member_jobalert"],"spacer"=>"")
	));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
smarty_create_standard_pagenavigation(true,$text_info["n_pages"],$start,$page_count,"member_jobalert.php",$text_info["n_pages_from"],"",$text_info["n_go"],5);
  $addlink = 'member_jobalert_work.php?action=add&'.$SLINE;
	$statuslnk = "javascript: if (confirm('".$text_info["i_start_ch_ja_status"]."')) { submit_form('chstatus','mainform'); } void(0)";
  $dellnk = "javascript: if (confirm('".$text_info["i_delete_ja"]."')) { submit_form('delete','mainform'); } void(0)";
$smarty->assign("GrayMenuItems",array(
array("link"=>$addlink, "text"=>$text_info["n_add_job_alert"], "title"=>$text_info["n_add_new_record"],
			"img_name"=>"check_on.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(1),"ascript"=>""),
array("link"=>$statuslnk,"text"=>$text_info["n_chstatus"],"title"=>$text_info["c_ch_ja_status"],
			"img_name"=>"ledon.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(7),"ascript"=>""),
array("link"=>$dellnk, "text"=>$text_info["n_delsel"], "title"=>$text_info["c_del_users"],
			"img_name"=>"reject.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(2),"ascript"=>"")
));

//Create help button
smarty_create_helpbutton("member_jobalert.html");

//Create hidden values for form
$smarty->assign("fdata_action","member_jobalert.php");
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>"delete"),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
smarty_create_session_data();

//Save session values for current page
save_session_values("member_jobalert");

$smarty->display('s_content_top.tpl');
?>