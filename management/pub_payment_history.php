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
check_access(array(0,2));

//Check first entry
check_first_entry("pub_payment_history",array());

//Check admin user
$action = "";
if ($_SESSION["sess_user"] == "0") {
	$uid_pub = get_get_value("uid_pub","");
	$uid_pub = check_sess_id_values($uid_pub,"uid_pub");
	if ($uid_pub == "") critical_error(__FILE__,__LINE__,"No Publisher ID");
	if ($uid_pub == "all") { $action = "all"; $sql = "WHERE 1=1"; }
	else { $action = "adm"; $sql = "WHERE uid_pub='$uid_pub'"; }
}
elseif ($_SESSION["sess_user"] == "2") {
	$uid_pub = $_SESSION["sess_userid"];
	$action = "user"; $sql = "WHERE uid_pub='$uid_pub'";
}

doconnect();

$start= get_start();
$pr		= get_get_post_value("pr","");
if ($pr == 1) { header("Location: payment_request.php?{$SLINE}"); exit; }

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"regdate"=>"regtime", "amount"=>"amount", "paydate"=>"paytime", "paytype"=>"paytype",
	"status"=>"status", "batchnum"=>"batchnum"
); //"code name" => "database field"

$sortfield_array_default = "regtime"; //default sorting field
$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder","DESC"))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$smarty->assign("DataHead", array(
	array("tdw"=>"30", "tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"110","tdclass"=>"tbl_td_head","data"=>sort_link("pub_payment_history.php","regdate",$text_info["th_registered"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("pub_payment_history.php","amount",$text_info["th_amount"])),
	array("tdw"=>"110","tdclass"=>"tbl_td_head","data"=>sort_link("pub_payment_history.php","paydate",$text_info["th_processed"])),
	array("tdw"=>"110","tdclass"=>"tbl_td_head","data"=>sort_link("pub_payment_history.php","paytype",$text_info["th_system"])),
	array("tdw"=>"100","tdclass"=>"tbl_td_head","data"=>sort_link("pub_payment_history.php","status",$text_info["th_status"])),
	array("tdw"=>"150","tdclass"=>"tbl_td_head","data"=>sort_link("pub_payment_history.php","batchnum",$text_info["th_batchnum"])),
	array("tdw"=>"60", "tdclass"=>"tbl_td_head","data"=>$text_info["th_options"])
));

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("fregdate"=>"regtime", "famount"=>"amount", "fpaydate"=>"paytime", "fpaytype"=>"paytype",
		"fstatus"=>"status"); //"code name" => "database field"
$filter_errorfields = array("fregdate"=>$text_info["th_registered"], "famount"=>$text_info["th_amount"], 
		"fpaydate"=>$text_info["th_processed"], "fpaytype"=>$text_info["th_system"],
		"fstatus"=>$text_info["th_status"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","fregdate","filter_checkbox","filter_text_110px",$select_digit_array,$select_digit_values,"filter_select_digit110px","date"),
	get_filter_td("tbl_td_bottom","filter","famount", "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom","filter","fpaydate","filter_checkbox","filter_text_110px",$select_digit_array,$select_digit_values,"filter_select_digit110px","date"),
	get_filter_td("tbl_td_bottom_light","filter","fpaytype","filter_checkbox","filter_text_110px",$select_paysystem_array,$select_paysystem_values,"filter_select_paysystem110px","paysystem",false),
	get_filter_td("tbl_td_bottom_light","filter","fstatus","filter_checkbox","filter_text_100px",$select_pendproc_array,$select_pendproc_values,"filter_select_pendproc100px","pendproc",false)
);
smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"pub_payment_history.php",2,$FilterElements);
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
$page_count = get_page_count("SELECT count(*) as num FROM ".$db_tables["payments_pub"]." $sql ".$limitation,$row_count);
if ($start > ($page_count-1)) $start = $page_count-1;
if ($start < 0) $start = 0;
$from_count = $start*$row_count;
$qr_res = mysql_query("SELECT *,".format_sql_datetime("regtime")." as regdate, ".format_sql_datetime("paytime")." as paydate FROM ".$db_tables["payments_pub"]." ".
		"$sql $limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
		or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	$paytime = (!$myrow["paydate"]) ? "" : $myrow["paydate"];
	$status = ($myrow["status"]) ? $text_info["f_Processed"]: $text_info["f_Pending"];
	$str_link_details = '<a href="pub_payment_history_work.php?pid='.$myrow["pid"].'&'.$SLINE.'">'.get_img("arrow.gif",20,20,$text_info["c_minfo"],get_js_action(10)).'</a>';
	$DataBody[$num] = array(
		array("tdw"=>"30", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
		array("tdw"=>"110","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["regdate"]),
		array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["amount"]),
		array("tdw"=>"110","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$paytime),
		array("tdw"=>"110","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$payment_types[$myrow["paytype"]]),
		array("tdw"=>"100","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$status),
		array("tdw"=>"150","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["batchnum"]),
		array("tdw"=>"60", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$str_link_details)
	);
	$num++;
}
$smarty->assign("DataBodyCount",$num);
$smarty->assign("DataBody",$DataBody);

//Payment history page
$smarty->assign("curpage","pub_payment_history");

//Create line with page navigation
$smarty->assign("navwidth","10");
if ($_SESSION["sess_user"] == "0") {
	if ($action == "adm") { //All publishers payment history
		$user_info = get_member_main_info($db_tables["users_publisher"],"uid_pub",$uid_pub);
		$c_einfo = ($user_info["result"]) ? $text_info["c_einfo"].' ('.$user_info["email"].')' : $text_info["c_einfo"];
		$smarty->assign("Pages",array(
			array("islink"=>1,"href"=>"publishers.php?$SLINE","text"=>$text_info["publishers"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
			array("islink"=>1,"href"=>"publishers_work.php?action=edit&uid_pub=".$uid_pub."&".$SLINE,"text"=>$c_einfo,"spacer"=>"&nbsp;&raquo;&nbsp;"),
			array("islink"=>0,"href"=>"","text"=>$text_info["c_payment_history"],"spacer"=>"")
		));
	}
	else { //One publisher payment history
		$smarty->assign("Pages",array(
			array("islink"=>1,"href"=>"pub_payment_history.php?uid_pub=all&".$SLINE,"text"=>$text_info["pub_payment_history"],"spacer"=>"")
		));
	}
}
else //This publisher payment history
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"pub_payment_history.php?$SLINE","text"=>$text_info["pub_payment_history"],"spacer"=>"")
	));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("pub_payment_history.html");

//Create hidden values for form
$smarty->assign("fdata_action","pub_payment_history.php");
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
smarty_create_session_data();

//Save session values for current page
save_session_values("pub_payment_history");

$smarty->display('s_content_top.tpl');
?>