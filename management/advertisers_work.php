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
require_once "advertisers_func.php";
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
$uid_adv= data_addslashes(get_get_post_value("uid_adv",""));
if (($action == "") && ($uid_adv == "")) critical_error(__FILE__,__LINE__,"Action or ID not found.");
$start = get_start();

//Calendar
$calendar_button = get_calendar("regdate",$usersettings["dateformat_c"],"calbtn","calspan");
//Create Calendar
$smarty->assign("LoadCalendarScript",true);

//Check action
if ($cancel) { 
	if (check_user_subm_script()) ;
	else { header("Location: advertisers.php?$SLINE"); exit; }
}
if ($save) try_save($uid_adv);

//The first entry - create form -->>
if (!$save) {
	$qr_res = mysql_query("SELECT *,".format_sql_date("regdate")." as regdate FROM ".$db_tables["users_advertiser"]." WHERE uid_adv='$uid_adv'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
	$myrow = mysql_fetch_array($qr_res);
	//Create form
	create_values($uid_adv,$myrow["email"],$myrow["pass"],$myrow["company"],$myrow["name"],$myrow["phone"],$myrow["fax"],
			$myrow["site"],$myrow["address1"],$myrow["address2"],$myrow["country_id"],$myrow["city"],$myrow["state"],
			$myrow["zipcode"],$myrow["promotioncode"],$myrow["balance"],$myrow["regdate"],$myrow["isconfirmed"],
			$myrow["isenable"]);
	//Create buttons
	create_page_buttons("save",$text_info["btn_save"]);
}
//The first entry - create form <<--

//Advertisers work page
$smarty->assign("curpage","advertisers_work");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"advertisers.php?$SLINE","text"=>$text_info["advertisers"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("advertisers_work.html");

//Create form
smarty_create_cform("frm","mainform","POST","advertisers_work.php","","",5,$text_info["c_edit"].$text_info["advertisers"],3,200,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",$FormButtons);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>$action),
	array("fname"=>"uid_adv","fvalue"=>$uid_adv),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Save session values for current page
save_session_values("advertisers_work");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>