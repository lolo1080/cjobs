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
require_once "members_func.php";
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
$save		= get_post_true_false("save","");
$action	= get_get_post_value("action","");
$uid_mem= data_addslashes(get_get_post_value("uid_mem",""));
if (($action == "") && ($uid_mem == "")) critical_error(__FILE__,__LINE__,"Action or ID not found.");
$start = get_start();

//Calendar
$calendar_button = get_calendar("regdate",$usersettings["dateformat_c"],"calbtn","calspan");
//Create Calendar
$smarty->assign("LoadCalendarScript",true);

//Check action
if ($cancel) { 
	if (check_user_subm_script()) ;
	else { header("Location: members.php?$SLINE"); exit; }
}
if ($save) try_save($uid_mem);

//The first entry - create form -->>
if (!$save) {
	$qr_res = mysql_query("SELECT *,".format_sql_date("regdate")." as regdate FROM ".$db_tables["users_member"]." WHERE uid_mem='$uid_mem'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
	$myrow = mysql_fetch_array($qr_res);
	//Create form
	create_values($uid_mem,$myrow["email"],$myrow["pass"],$myrow["first_name"],$myrow["last_name"],$myrow["site"],
			$myrow["country_id"],$myrow["city"],$myrow["state"],$myrow["zipcode"],$myrow["regdate"],$myrow["isconfirmed"]);
	//Create buttons
	create_page_buttons("save",$text_info["btn_save"]);
}
//The first entry - create form <<--

//members work page
$smarty->assign("curpage","members_work");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"members.php?$SLINE","text"=>$text_info["members"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("members_work.html");

//Create form
smarty_create_cform("frm","mainform","POST","members_work.php","","",5,$text_info["c_edit"].$text_info["members"],3,200,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",$FormButtons);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>$action),
	array("fname"=>"uid_mem","fvalue"=>$uid_mem),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Save session values for current page
save_session_values("members_work");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>