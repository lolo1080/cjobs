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
require_once "jobs_list_common_func.php";
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
$data_id= data_addslashes(get_get_post_value("data_id",""));
if (($action == "") && ($data_id == "")) critical_error(__FILE__,__LINE__,"Action or ID not found.");
$start = get_start();

//Calendar
//$calendar_button = get_calendar("regdate",$usersettings["dateformat_c"],"calbtn","calspan");
//Create Calendar
//$smarty->assign("LoadCalendarScript",true);

//Check action
if ($cancel) header("Location: jobs_list_common.php?$SLINE");
elseif ($save) try_save($data_id);

//The first entry - create form -->>
if (!$save) {
//	$qr_res = mysql_query("SELECT d.*,".format_sql_datetime("d.dateinsert")." as regdate, f.title as feed_title, r.name, CONCAT(c.city,', ', c.region) as location, IF (r.name,r.name,c.region), cat.cat_name ".
	$qr_res = mysql_query("SELECT d.*,".format_sql_datetime("d.dateinsert")." as regdate, f.title as feed_title, r.name as rregionname, c.city as ccity, c.region as cregion, c.country as ccountry, cat.cat_name ".
		"FROM ".$db_tables["data_list"]." d ".
		"INNER JOIN ".$db_tables["sites_feed_list"]." f ON d.feed_id=f.feed_id ".
		"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".
		"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region ".
		"INNER JOIN ".$db_tables["jobcategories"]." cat ON d.cat_id=cat.cat_id ".
		"WHERE data_id='$data_id'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
	$myrow = mysql_fetch_array($qr_res);

	//Create form
	$location_db = '';
	if ($myrow["ccity"] != '') $location_db = $myrow["ccity"];
	$region = ($myrow["rregionname"] && ($myrow["rregionname"] != '')) ? $myrow["rregionname"] : $myrow["cregion"];
	if ($region != '') $location_db .= ( ($location_db != '') ? ', ' : '' ).$region;
	$location_db = $location_db.( ($location_db != '') ? ', ' : '' ).$myrow["ccountry"];
	$location_not_db = '';
	if ($myrow["city"] != '') $location_not_db = $myrow["city"];
	if ($myrow["region"] != '') $location_not_db .= ( ($location_not_db != '') ? ', ' : '' ).$myrow["region"];
	$location_not_db = $location_not_db.( ($location_not_db != '') ? ', ' : '' ).$myrow["country"];

	create_values($data_id,$myrow["regdate"],$myrow["feed_id"],$myrow["title"],$myrow["company_name"],$location_db,$location_not_db,
			$myrow["description"],$myrow["url"],$myrow["cat_name"],$myrow["salary"],$myrow["dateinsert"],$myrow["feed_title"]);
	//Create buttons
	create_page_buttons("save",$text_info["btn_save"]);
}
//The first entry - create form <<--

//members work page
$smarty->assign("curpage","jobs_list_common_work");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"jobs_list_common.php?$SLINE","text"=>$text_info["jobs_list_common"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("jobs_list_common_work.html");

//Create form
smarty_create_cform("frm","mainform","POST","jobs_list_common_work.php","","",5,$text_info["c_edit"].$text_info["members"],3,200,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",$FormButtons);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>$action),
	array("fname"=>"data_id","fvalue"=>$data_id),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Save session values for current page
save_session_values("jobs_list_common_work");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>