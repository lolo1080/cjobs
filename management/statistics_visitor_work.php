<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "functions.php";
require_once "functions_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";
require_once "statistics_visitor_func.php";
check_access(array(0));

doconnect();

$stat_vi= get_get_post_value("stat_vi","");
$ok			= get_post_true_false("ok","");

//Check action
if ($ok) { header("Location: statistics_search.php?$SLINE"); exit; }

if ($stat_vi == "") critical_error(__FILE__,__LINE__,"No Visitor ID");

$qr_res = mysql_query("SELECT *,".format_sql_datetime("entertime")." as regdate FROM ".$db_tables["stats_visitor_info"].
		" WHERE stat_vi='$stat_vi'") or query_die(__FILE__,__LINE__,mysql_error());
$myrow = mysql_fetch_array($qr_res);
create_values($myrow["regdate"],$myrow["ip"],$myrow["ip_over_proxy"],$myrow["refer_url"],$myrow["request_url"]);

//Visitor page
$smarty->assign("curpage","statistics_visitor_work");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"statistics_search.php?$SLINE","text"=>$text_info["statistics_keywords"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
	array("islink"=>0,"href"=>"","text"=>$text_info["statistics_visitor"],"spacer"=>""),
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("statistics_visitor_work.html");

//Create form
$form_capt = $text_info["c_visitor_details"];
smarty_create_cform("frm","mainform","POST","statistics_visitor_work.php","","",5,$form_capt,3,200,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"ok","bvalue"=>$text_info["btn_ok"] ,"bscript"=>""),
));

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>