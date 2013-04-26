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
require_once "include/other/filter.php";
require_once "topmenu_func.php";
require_once "statistics_clicks_func.php";
check_access(array(0));

doconnect();

$stat_click	= get_get_post_value("stat_click","");
$ok					= get_post_true_false("ok","");

//Check action
if ($ok) { header("Location: statistics_clicks.php?$SLINE"); exit; }

if ($stat_click == "") critical_error(__FILE__,__LINE__,"No Click ID");

$qr_res = mysql_query("SELECT sc.jobid,sc.click_type,".format_sql_datetime("sc.clicktime")." as regdate, sk.keyword, sk.searchtype ".
		"FROM ".$db_tables["stats_clicks"]." sc ".
		"LEFT JOIN ".$db_tables["stats_search_keywords"]." sk ON sc.stat_kid=sk.stat_kid ".
		"WHERE stat_click='$stat_click'") or query_die(__FILE__,__LINE__,mysql_error());
$myrow = mysql_fetch_array($qr_res);
//Select job title
$job_title = "";
if ($myrow["click_type"] == 0) {
	$qr_res1 = mysql_query("SELECT title FROM ".$db_tables["data_list"]." WHERE data_id='{$myrow["jobid"]}'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res1) > 0) {
		$myrow1 = mysql_fetch_array($qr_res1);
		$job_title = $myrow1["title"];
	}
}
elseif ($myrow["click_type"] == 1) {
	$qr_res1 = mysql_query("SELECT title FROM ".$db_tables["data_list_advertiser"]." WHERE data_id='{$myrow["jobid"]}'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res1) > 0) {
		$myrow1 = mysql_fetch_array($qr_res1);
		$job_title = $myrow1["title"];
	}
}
elseif ($myrow["click_type"] == 2) {
	$qr_res1 = mysql_query("SELECT headline FROM ".$db_tables["ads"]." WHERE ad_id='{$myrow["jobid"]}'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res1) > 0) {
		$myrow1 = mysql_fetch_array($qr_res1);
		$job_title = $myrow1["headline"];
	}
}

if ($myrow["searchtype"] == "") $myrow["searchtype"] = 1;
create_values($myrow["regdate"],$select_clicktype_array[$myrow["click_type"]],$myrow["keyword"],$select_searchtype_array[$myrow["searchtype"]-1],$job_title);

//Visitor page
$smarty->assign("curpage","statistics_clicks_work");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"statistics_clicks.php?$SLINE","text"=>$text_info["statistics_clicks"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
	array("islink"=>0,"href"=>"","text"=>$text_info["statistics_clicks_details"],"spacer"=>""),
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("statistics_clicks_work.html");

//Create form
$form_capt = $text_info["c_statistic_clicks_details"];
smarty_create_cform("frm","mainform","POST","statistics_clicks_work.php","","",5,$form_capt,3,200,5,300,3);

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