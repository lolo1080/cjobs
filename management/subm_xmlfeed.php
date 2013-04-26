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
check_first_entry("subm_xmlfeed",array());

doconnect();

$start = get_start();
$action = get_get_post_value("action","");
$elist = get_get_post_value2("elist",array());

$_SESSION["sess_xmlfeed_subm_type"] = 1;

if ($action == "approve") { //"approve" - approve xml feed
	for ($i=0; $i<count($elist); $i++)
	{
		if (check_int(trim($elist[$i]))) {
			mysql_query("UPDATE ".$db_tables["users_publisher"]." SET isxmlfeed_enable=1 WHERE uid_pub='".$elist[$i]."' and isdeleted=0") or query_die(__FILE__,__LINE__,mysql_error());
			//Send event
			$event_array = array("event"=>"update", "source"=>"publishers", "table"=>"users_publisher", "ad_id"=>0);
			event_handler($event_array);
			//Delete from submisions
			mysql_query("DELETE FROM ".$db_tables["users_submissions"]." WHERE uid='".$elist[$i]."' and usertype='pub' and restype='xml'") or query_die(__FILE__,__LINE__,mysql_error());
		}
	}
}
elseif ($action == "reject") { //"reject" - reject xml feed
	for ($i=0; $i<count($elist); $i++)
	{
		if (check_int(trim($elist[$i]))) {
			mysql_query("DELETE FROM ".$db_tables["users_submissions"]." WHERE uid='".$elist[$i]."' and usertype='pub' and restype='xml'") or query_die(__FILE__,__LINE__,mysql_error());
		}
	}
}

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"email"=>"email", "name"=>"name", "regdate"=>"datesort"
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
	array("tdw"=>"180","tdclass"=>"tbl_td_head","data"=>sort_link("subm_xmlfeed.php","email",$text_info["th_email"])),
	array("tdw"=>"120","tdclass"=>"tbl_td_head","data"=>sort_link("subm_xmlfeed.php","name",$text_info["th_name"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("subm_xmlfeed.php","regdate",$text_info["th_regdate"])),
	array("tdw"=>"80", "tdclass"=>"tbl_td_head","data"=>$text_info["th_options"])
));

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("femail"=>"email", "fname"=>"name", "fregdate"=>"regdate"); //"code name" => "database field"
$filter_errorfields = array("femail"=>$text_info["th_email"], "fname"=>$text_info["th_name"],
	"fregdate"=>$text_info["th_regdate"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","femail",   "filter_checkbox","filter_text_180px",$select_txt_array,$select_txt_values,"filter_select_text180px","text"),
	get_filter_td("tbl_td_bottom","filter","fname",    "filter_checkbox","filter_text_120px",$select_txt_array,$select_txt_values,"filter_select_text120px","text"),
	get_filter_td("tbl_td_bottom","filter","fregdate", "filter_checkbox","filter_text_75px",$select_digit_array,$select_digit_values,"filter_select_digit75px","date"),
);
smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"subm_xmlfeed.php",1,$FilterElements);
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
$page_count = get_page_count("SELECT count(*) as num FROM ".$db_tables["users_publisher"]." up ".
		"INNER JOIN ".$db_tables["users_submissions"]." us ON up.uid_pub=us.uid and usertype='pub' and restype='xml' ".
		"WHERE isxmlfeed_enable=0 and isdeleted=0 $limitation",$row_count);
if ($start > ($page_count-1)) $start = $page_count-1;
if ($start < 0) $start = 0;
$from_count = $start*$row_count;
$qr_res = mysql_query("SELECT uid_pub,name,email,regdate as datesort,".format_sql_date("regdate")." as regdate FROM ".$db_tables["users_publisher"]." up ".
		"INNER JOIN ".$db_tables["users_submissions"]." us ON up.uid_pub=us.uid and usertype='pub' and restype='xml' ".
		"WHERE isxmlfeed_enable=0 and isdeleted=0 $limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
		or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	$approve_lnk = '<a href="subm_xmlfeed.php?action=approve&elist[]='.$myrow["uid_pub"].'&'.$SLINE.'">'.get_img("check_on.gif",20,20,$text_info["c_approve"],get_js_action(1)).'</a>';
	$reject_lnk = '<a href="subm_xmlfeed.php?action=reject&elist[]='.$myrow["uid_pub"].'&'.$SLINE.'">'.get_img("reject.gif",20,20,$text_info["c_reject"],get_js_action(2)).'</a>';
	$edit_lnk = '<a href="publishers_work.php?action=edit&uid_pub='.$myrow["uid_pub"].'&'.$SLINE.'">'.get_img("infedit.gif",20,20,$text_info["c_einfo"],get_js_action(6)).'</a>';
	$DataBody[$num] = array(
		array("tdw"=>"20", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
		array("tdw"=>"35", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>get_type_checkbox("elist[]",$myrow["uid_pub"],"")),
		array("tdw"=>"180","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["email"]),
		array("tdw"=>"120","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["name"]),
		array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["regdate"]),
		array("tdw"=>"80", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$approve_lnk.'&nbsp;'.$reject_lnk.'&nbsp;'.$edit_lnk)
	);
	$num++;
}
$smarty->assign("DataBodyCount",$num);
$smarty->assign("DataBody",$DataBody);

//Submissions XML Feed page
$smarty->assign("curpage","subm_xmlfeed");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"subm_xmlfeed.php?$SLINE","text"=>$text_info["subm_xmlfeed_all"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
smarty_create_standard_pagenavigation(true,$text_info["n_pages"],$start,$page_count,"subm_xmlfeed.php",$text_info["n_pages_from"],"",$text_info["n_go"],5);
  $approvelnk = "javascript: if (confirm('".$text_info["i_approve_submissions"]."')) { submit_form('approve','mainform'); } void(0)";
  $rejectlnk = "javascript: if (confirm('".$text_info["i_reject_submissions"]."')) { submit_form('reject','mainform'); } void(0)";
$smarty->assign("GrayMenuItems",array(
array("link"=>$approvelnk,"text"=>$text_info["n_approvesel"],"title"=>$text_info["c_approve_submissions"],
			"img_name"=>"check_on.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(1),"ascript"=>""),
array("link"=>$rejectlnk, "text"=>$text_info["n_rejectsel"], "title"=>$text_info["c_reject_submissions"],
			"img_name"=>"reject.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(2),"ascript"=>"")
));

//Create help button
smarty_create_helpbutton("subm_xmlfeed.html");

//Create hidden values for form
$smarty->assign("fdata_action","subm_xmlfeed.php");
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>$action),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
smarty_create_session_data();

//Save session values for current page
save_session_values("subm_xmlfeed");

$smarty->display('s_content_top.tpl');
?>