<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "app_events_handler.php";
require_once "functions.php";
require_once "functions_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "include/other/table_mini.php";
require_once "topmenu_func.php";
require_once "job_search_log_func.php";
require_once "cron/data_collection/language_additional.php";
check_access(array(0));

doconnect();

function create_page_buttons($bname,$bvalue)
{
 global $FormButtons, $text_info;
	$FormButtons = array(
		array("btn_classnum"=>"1","btype"=>"submit","bname"=>$bname,"bvalue"=>$bvalue,"bscript"=>""),
	);
}

$cancel	= get_post_true_false("cancel","");
$log_id = data_addslashes(get_get_post_value("log_id",""));
if ($log_id == "") critical_error(__FILE__,__LINE__,"Log ID not found.");
$start = get_start();

//Check action
if ($cancel) { header("Location: job_search_log.php?{$SLINE}"); exit; }

//The first entry - create form -->>
$qr_res = mysql_query("SELECT *,".format_sql_datetime("actiontime")." as actiontime FROM ".$db_tables["sites_feed_log"]." WHERE log_id='$log_id'") or query_die(__FILE__,__LINE__,mysql_error());
if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
$myrow = mysql_fetch_array($qr_res);
//Create form
create_values($log_id,$myrow["actiontime"],$myrow["action"],$myrow["status"],$myrow["detail_level"],$myrow["short_message"],$myrow["long_message"]);
//Create buttons
create_page_buttons("cancel",$text_info["btn_ok"]);
//The first entry - create form <<--

//job_search_log work page
$smarty->assign("curpage","job_search_log_work");

//Create line with page navigation
$name_text = (isset($myrow["name"])) ? $myrow["name"] : html_chars(get_post_value("name",""));
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"job_search_log.php?$SLINE","text"=>$text_info["job_search_log"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("job_search_log_work.html");

//Create form
smarty_create_cform("frm","mainform","POST","job_search_log_work.php","","",5,$text_info["c_edit"].$text_info["job_search_log"],3,200,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",$FormButtons);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID),
	array("fname"=>"log_id","fvalue"=>$log_id)
));

//Save session values for current page
save_session_values("job_search_log_work");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>