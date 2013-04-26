<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "app_events_handler.php";
require_once "functions.php";
require_once "payment_request_func.php";
require_once "functions_mini.php";
require_once "include/other/filter.php";
require_once "include/other/table_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";
require_once "consts_mail.php";
require_once "include/mail/send_mail.php";
require_once "app_cache_functions.php";
check_access(array(0));

//Check first entry
check_first_entry("payment_request",array());

doconnect();

$start	= get_start();
$action = get_get_post_value("action","");
$elist	= get_get_post_value2("elist",array());
$paytype= get_get_post_value("paytype","");

$my_error = "";

//Check Payment
if (isset($_SESSION["sess_PAYMENT_STATUS"]) && isset($_SESSION["sess_PAYMENT_MESSAGE"]) && ($_SESSION["sess_PAYMENT_STATUS"] != "")) {
	if ($_SESSION["sess_PAYMENT_STATUS"] == "normal")
		smarty_create_message("error","info.gif",$text_info["i_payment_success"]);
	else $my_error = $_SESSION["sess_PAYMENT_MESSAGE"];
	$_SESSION["sess_PAYMENT_STATUS"] = $_SESSION["sess_PAYMENT_MESSAGE"] = "";
	unset($_SESSION["sess_PAYMENT_STATUS"]);
	unset($_SESSION["sess_PAYMENT_MESSAGE"]);
}

//Check action.
//Action == "chstatus" - change payment request status (Pending->Approve);
//Action == "reject" - reject payment request (return money to user account);
//Action == "delete" - delete payment request (do not return money to user account)
switch ($action) {
	case "chstatus":
		for ($i=0; $i<count($elist); $i++) {
			if (check_int(trim($elist[$i]))) mysql_query("UPDATE ".$db_tables["payments_pub"]." SET status=1, paytime=NOW() WHERE pid='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
		}
	break;
	case "reject":
		for ($i=0; $i<count($elist); $i++) {
			if (check_int(trim($elist[$i]))) {
				$qr_res = mysql_query("SELECT amount,uid_pub FROM ".$db_tables["payments_pub"]." WHERE pid='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
				if (mysql_num_rows($qr_res) > 0) {
					$myrow = mysql_fetch_array($qr_res);
					$amount = $myrow["amount"];
					$uid_pub = $myrow["uid_pub"];
					mysql_query("UPDATE ".$db_tables["users_publisher"]." SET balance=balance+$amount WHERE uid_pub='$uid_pub'") or query_die(__FILE__,__LINE__,mysql_error());
					//Send event
					$event_array = array("event"=>"update", "source"=>"publishers", "table"=>"users_publisher", "ad_id"=>0);
					event_handler($event_array);
				}
				mysql_query("DELETE FROM ".$db_tables["payments_pub"]." WHERE pid='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
			}
		}
	break;
	case "delete":
		for ($i=0; $i<count($elist); $i++) {
			if (check_int(trim($elist[$i]))) mysql_query("DELETE FROM ".$db_tables["payments_pub"]." WHERE pid='".$elist[$i]."'") or query_die(__FILE__,__LINE__,mysql_error());
		}
	break;
	case "autopay":
		$ptf = array_flip($payment_types);
		switch ($paytype) {
			case $ptf["PayPal"]: create_payment_paypal($elist[0],$my_error); break;
			case $ptf["E-Gold"]: create_payment_egold($elist[0],$my_error); break;
			case $ptf["2checkout"]:
					echo "Sorry. Payment system under construction."; exit;
			default: critical_error(__FILE__,__LINE__,"Payment type is incorrect.");
		}
		break;
	case "paydone":
		echo "OKOKOKOKOKOKOKOKOKOKOKOKOKOKOKOKOKOKOKOKOK";
	break;
}

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"regdate"=>"p.regtime", "amount"=>"p.amount", "email"=>"u.email", "name"=>"u.name", "paytype"=>"p.paytype",
	"batchnum"=>"p.batchnum"
); //"code name" => "database field"

$sortfield_array_default = "p.regtime"; //default sorting field
$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder","DESC"))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$smarty->assign("DataHead", array(
	array("tdw"=>"30", "tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"110","tdclass"=>"tbl_td_head","data"=>sort_link("pub_payment_history.php","regdate",$text_info["th_registered"])),
	array("tdw"=>"75", "tdclass"=>"tbl_td_head","data"=>sort_link("pub_payment_history.php","amount",$text_info["th_amount"])),
	array("tdw"=>"200","tdclass"=>"tbl_td_head","data"=>sort_link("pub_payment_history.php","email",$text_info["th_email"])),
	array("tdw"=>"120","tdclass"=>"tbl_td_head","data"=>sort_link("pub_payment_history.php","name",$text_info["th_name"])),
	array("tdw"=>"110","tdclass"=>"tbl_td_head","data"=>sort_link("pub_payment_history.php","paytype",$text_info["th_system"])),
	array("tdw"=>"150","tdclass"=>"tbl_td_head","data"=>sort_link("pub_payment_history.php","batchnum",$text_info["th_batchnum"])),
	array("tdw"=>"120", "tdclass"=>"tbl_td_head","data"=>$text_info["th_options"])
));

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("fregdate"=>"p.regtime", "famount"=>"p.amount", "femail"=>"u.email", "fname"=>"u.name", "fpaytype"=>"paytype"); //"code name" => "database field"
$filter_errorfields = array("fregdate"=>$text_info["th_registered"], "famount"=>$text_info["th_amount"], 
		"femail"=>$text_info["th_email"], "fname"=>$text_info["th_name"], "fpaytype"=>$text_info["th_system"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","fregdate","filter_checkbox","filter_text_110px",$select_digit_array,$select_digit_values,"filter_select_digit110px","date"),
	get_filter_td("tbl_td_bottom","filter","famount", "filter_checkbox","filter_text_75px",$select_float_array,$select_float_values,"filter_select_digit75px","float"),
	get_filter_td("tbl_td_bottom","filter","femail",	"filter_checkbox","filter_text_200px",$select_txt_array,$select_txt_values,"filter_select_text200px","text"),
	get_filter_td("tbl_td_bottom","filter","fname",   "filter_checkbox","filter_text_120px",$select_txt_array,$select_txt_values,"filter_select_text120px","text"),
	get_filter_td("tbl_td_bottom_light","filter","fpaytype","filter_checkbox","filter_text_110px",$select_paysystem_array,$select_paysystem_values,"filter_select_paysystem110px","paysystem",false)
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
$ptf = array_flip($payment_types);
$DataBody = array();
$page_count = get_page_count("SELECT count(*) as num FROM ".$db_tables["payments_pub"]." p ".
		"LEFT JOIN ".$db_tables["users_publisher"]." u ON p.uid_pub=u.uid_pub ".
		"WHERE p.status=0 and u.isdeleted=0 ".$limitation,$row_count);
if ($start > ($page_count-1)) $start = $page_count-1;
if ($start < 0) $start = 0;
$from_count = $start*$row_count;
$qr_res = mysql_query("SELECT p.*,".format_sql_datetime("p.regtime")." as regdate, u.email, u.name ".
		"FROM ".$db_tables["payments_pub"]." p ".
		"LEFT JOIN ".$db_tables["users_publisher"]." u ON p.uid_pub=u.uid_pub ".
		"WHERE p.status=0 and u.isdeleted=0 $limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
		or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	if ($myrow["paytype"] == $ptf["PayPal"]) $str_link_autopay = create_payment_paypal_link($myrow["pid"]);
	else $str_link_autopay = '<img height="10" src="images/spacer.gif" width="20" alt="" border="0" />';
//	else $str_link_autopay = '<a href="payment_request.php?action=autopay&elist[]='.$myrow["pid"].'&paytype='.$myrow["paytype"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_autopay"].'\')">'.get_img("autopay.gif",20,20,$text_info["c_payment"],get_js_action(3)).'</a>';
	$str_link_chstatus = '<a href="payment_request.php?action=chstatus&elist[]='.$myrow["pid"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_ch_payment_status"].'\')">'.get_img("ledoff.gif",20,20,$text_info["c_chstatus"],get_js_action(8)).'</a>';
	$str_link_reject = '<a href="payment_request.php?action=reject&elist[]='.$myrow["pid"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_reject_payment"].'\')">'.get_img("reject2.gif",20,20,$text_info["c_reject"],get_js_action(15)).'</a>';
	$str_link_delete = '<a href="payment_request.php?action=delete&elist[]='.$myrow["pid"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_delete_payment"].'\')">'.get_img("reject.gif",20,20,$text_info["c_delete"],get_js_action(2)).'</a>';
	$str_link_details = '<a href="pub_payment_history_work.php?pid='.$myrow["pid"].'&'.$SLINE.'&pr=1">'.get_img("arrow.gif",20,20,$text_info["c_minfo"],get_js_action(10)).'</a>';
	$DataBody[$num] = array(
		array("tdw"=>"30", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
		array("tdw"=>"110","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["regdate"]),
		array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["amount"]),
		array("tdw"=>"200","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["email"]),
		array("tdw"=>"120","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["name"]),
		array("tdw"=>"100","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$payment_types[$myrow["paytype"]]),
		array("tdw"=>"120","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["batchnum"]),
		array("tdw"=>"120","tdclass"=>"tbl_td_data","tdalign"=>"align=center","data"=>$str_link_autopay.'&nbsp;'.$str_link_chstatus.'&nbsp;'.$str_link_reject.'&nbsp;'.$str_link_delete.'&nbsp'.$str_link_details),
	);
	$num++;
}
$smarty->assign("DataBodyCount",$num);
$smarty->assign("DataBody",$DataBody);

//Payment history page
$smarty->assign("curpage","payment_request");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"payment_request.php?$SLINE","text"=>$text_info["payment_request"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("payment_request.html");

//Create hidden values for form
$smarty->assign("fdata_action","payment_request.php");
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
smarty_create_session_data();

//Save session values for current page
save_session_values("payment_request");

$smarty->display('s_content_top.tpl');
?>