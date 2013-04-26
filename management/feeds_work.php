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
require_once "feeds_func.php";
require_once "include/other/filter.php"; //needed for checking date value
require_once "app_cache_functions.php";
check_access(array(0));

doconnect();

function create_page_buttons($bname,$bvalue)
{
 global $FormButtons, $text_info;
	$FormButtons = array(
		array("btn_classnum"=>"1","btype"=>"submit","bname"=>$bname,"bvalue"=>$bvalue,"bscript"=>""),
		array("btn_classnum"=>"2","btype"=>"submit","bname"=>"cancel","bvalue"=>$text_info["btn_cancel"],"bscript"=>"")
	);
}

$cancel	= get_post_true_false("cancel","");
$add		= get_post_true_false("add","");
$save		= get_post_true_false("save","");
$action	= get_get_post_value("action","");
$feed_id= data_addslashes(get_get_post_value("feed_id",""));
if (($action == "") && ($feed_id == "")) critical_error(__FILE__,__LINE__,"Action or ID not found.");
$start = get_start();

//Calendar
$calendar_button = get_calendar("registered",$usersettings["dateformat_c"],"calbtn","calspan");
//Create Calendar
$smarty->assign("LoadCalendarScript",true);

//Check action
if ($cancel) { header("Location: feeds.php?$SLINE"); exit; }
if ($save) try_save($feed_id);
elseif ($add) try_add();

//The first entry - create form -->>
if (!$save && !$add) {
	switch($action) {
		case "edit":
			$ereadonly = $disabled = "";
			$qr_res = mysql_query("SELECT *,".format_sql_date("registered")." as registered FROM ".$db_tables["sites_feed_list"]." WHERE feed_id='$feed_id'") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
			$myrow = mysql_fetch_array($qr_res);
			//Create form
			create_values($myrow["title"],$myrow["description"],$myrow["url"],$myrow["registered"],$myrow["refresh_rate"],$myrow["max_recursion_depths"],$myrow["isactive"],$myrow["feed_type"],$myrow["job_ads_id"],$myrow["feed_format"]);
			//Create buttons
			create_page_buttons("save",$text_info["btn_save"]);
			break;
		case "add":
			$ereadonly = $disabled = "";
			//Create form
			create_values("","","","","","","1","common","0","xml");
			//Create buttons
			create_page_buttons("add",$text_info["btn_save"]);
			break;
		default: critical_error(__FILE__,__LINE__,"Action is invalid.");
	}
}
//The first entry - create form <<--

//feeds work page
$smarty->assign("curpage","feeds_work");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"feeds.php?$SLINE","text"=>$text_info["feeds"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("feeds_work.html");

//Create form
smarty_create_cform("frm","mainform","POST","feeds_work.php","","",5,$text_info["c_edit"].$text_info["feeds"],3,300,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",$FormButtons);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>$action),
	array("fname"=>"feed_id","fvalue"=>$feed_id),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Save session values for current page
save_session_values("feeds_work");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>