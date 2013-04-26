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
check_access(array(0));

//Check first entry
check_first_entry("job_search_emails",array());

doconnect();

$start = get_start();
$action= get_get_post_value("action","");
$elist = get_get_post_value2("elist",array());
if (isset($_SESSION["sess_user_subm_type"])) unset($_SESSION["sess_user_subm_type"]);

//Check action. Action == "chstatus" - change user status (Active/Disable); Action == "delete" - delete user
switch ($action) {
	case "delete":
		for ($i=0; $i<count($elist); $i++) {
			$elist[$i] = data_addslashes(trim($elist[$i]));
			if (check_int($elist[$i])) mysql_query("DELETE FROM ".$db_tables["sites_feed_alert_emials"]." WHERE email_id='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
		}
	break;
}

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"email"=>"email"
); //"code name" => "database field"
$sortfield_array_default = "email"; //default sorting field
$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder",""))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$smarty->assign("DataHead", array(
	array("tdw"=>"20", "tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"35", "tdclass"=>"tbl_td_head","data"=>get_std_checkbox_list($text_info["th_sel_group"])),
	array("tdw"=>"200","tdclass"=>"tbl_td_head","data"=>sort_link("job_search_emails.php","email",$text_info["th_email"])),
	array("tdw"=>"80", "tdclass"=>"tbl_td_head","data"=>$text_info["th_options"])
));

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("femail"=>"email"); //"code name" => "database field"
$filter_errorfields = array("femail"=>$text_info["th_email"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","femail",	"filter_checkbox","filter_text_200px",$select_txt_array,$select_txt_values,"filter_select_text200px","text")
);
smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"job_search_emails.php",1,$FilterElements);
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
$page_count = get_page_count("SELECT count(*) as num FROM ".$db_tables["sites_feed_alert_emials"]." ".$limitation,$row_count);
if ($start > ($page_count-1)) $start = $page_count-1;
if ($start < 0) $start = 0;
$from_count = $start*$row_count;
$qr_res = mysql_query("SELECT * ".
		"FROM ".$db_tables["sites_feed_alert_emials"]." ".
		"$limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
		or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	$str_link_edit = '<a href="job_search_emails_work.php?action=edit&email_id='.$myrow["email_id"].'&'.$SLINE.'">'.get_img("infedit.gif",20,20,$text_info["c_einfo"],get_js_action(6)).'</a>';
	$str_link_del = '<a href="job_search_emails.php?action=delete&elist[]='.$myrow["email_id"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_delete_email"].'\')">'.get_img("reject.gif",20,20,$text_info["c_delete"],get_js_action(2)).'</a>';
	$DataBody[$num] = array(
		array("tdw"=>"20", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
		array("tdw"=>"35", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>get_type_checkbox("elist[]",$myrow["email_id"],"")),
		array("tdw"=>"200","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["email"]),
		array("tdw"=>"80", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$str_link_edit.'&nbsp;'.$str_link_del)
	);
	$num++;
}
$smarty->assign("DataBodyCount",$num);
$smarty->assign("DataBody",$DataBody);

//job_search_emails page
$smarty->assign("curpage","job_search_emails");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"job_search_emails.php?$SLINE","text"=>$text_info["job_search_emails"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
smarty_create_standard_pagenavigation(true,$text_info["n_pages"],$start,$page_count,"job_search_emails.php",$text_info["n_pages_from"],"",$text_info["n_go"],5);
  $addlink = 'job_search_emails_work.php?action=add&'.$SLINE;
  $dellnk = "javascript: if (confirm('".$text_info["i_delete_emails"]."')) { submit_form('delete','mainform'); } void(0)";
$smarty->assign("GrayMenuItems",array(
array("link"=>$addlink, "text"=>$text_info["n_add_record"], "title"=>$text_info["n_add_new_record"],
			"img_name"=>"check_on.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(1),"ascript"=>""),
array("link"=>$dellnk, "text"=>$text_info["n_delsel"], "title"=>$text_info["c_del_emails"],
			"img_name"=>"reject.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(2),"ascript"=>"")
));

//Create help button
smarty_create_helpbutton("job_search_emails.html");

//Create hidden values for form
$smarty->assign("fdata_action","job_search_emails.php");
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>"delete"),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
smarty_create_session_data();

//Save session values for current page
save_session_values("job_search_emails");

$smarty->display('s_content_top.tpl');
?>