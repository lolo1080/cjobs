<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "connect.inc";
require_once "language.php";
require_once "consts_smarty.php";
require_once "topmenu_func.php";
require_once "app_events_handler.php";
require_once "functions.php";
require_once "functions_mini.php";
require_once "include/functions/functions_smartry.php";
require_once "include/other/table_mini.php";
require_once "adv_advertisement_jobs_from_my_site_func.php";
require_once "include/other/filter.php"; //needed for checking date value
require_once "app_cache_functions.php";
check_access(array(0,1));

doconnect();

function create_page_buttons($bname,$bvalue)
{
 global $FormButtons, $text_info;
	$FormButtons = array(
		array("btn_classnum"=>"1","btype"=>"submit","bname"=>$bname,"bvalue"=>$bvalue,"bscript"=>""),
		array("btn_classnum"=>"2","btype"=>"submit","bname"=>"cancel","bvalue"=>$text_info["btn_cancel"],"bscript"=>"")
	);
}

$cancel			= get_post_true_false("cancel","");
$save				= get_post_true_false("save","");
$add				= get_post_true_false("add","");
$action			= get_get_post_value("action","");
$job_ads_id	= data_addslashes(get_get_post_value("job_ads_id",""));

$uid_adv	= get_get_value("uid_adv","");
$uid_adv	= check_sess_id_values($uid_adv,"adm_uid_adv");
if (($_SESSION["sess_user"] != "0") && ($uid_adv != "")) critical_error(__FILE__,__LINE__,"Incorrect params list.");
if ($_SESSION["sess_user"] == "0") $query_uid_adv = $uid_adv;
else $query_uid_adv = $_SESSION["sess_userid"];

if (($action == "") && ($job_ads_id == "")) critical_error(__FILE__,__LINE__,"Action or Job ID not found.");
if (($job_ads_id != "") && !is_this_user_current_job_ad($job_ads_id,$query_uid_adv)) critical_error(__FILE__,__LINE__,"Access violation. Another advertiser Job Ad ID.");
$start = get_start();

//Check action
if ($cancel) { header("Location: adv_advertisements.php?{$SLINE}"); exit; }
if ($save) try_save($job_ads_id);
elseif ($add) try_add();

//The first entry - create form -->>
if (!$save && !$add) {
	switch($action) {
		case "edit":
			$qr_res = mysql_query("SELECT * FROM ".$db_tables["job_ads"]." WHERE job_ads_id='$job_ads_id'") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
			$myrow = mysql_fetch_array($qr_res);
			//Create form
			create_values($action,$job_ads_id,$myrow["ad_name"],$myrow["destination_url"],$myrow["max_cpc"],$myrow["daily_budget"],$myrow["monthly_budget"],$myrow["status"]);
			//Create buttons
			create_page_buttons("save",$text_info["btn_save"]);
			break;
		case "add":
			//Create form
			create_values($action,"","","","","","","");
			//Create buttons
			create_page_buttons("add",$text_info["btn_add"]);
			break;
		default: critical_error(__FILE__,__LINE__,"Action is invalid.");
	}
}
//The first entry - create form <<--

//adv_advertisement_jobs_from_my_site_work work page
$smarty->assign("curpage","adv_advertisement_jobs_from_my_site_work");

//Create line with page navigation
$smarty->assign("navwidth","10");
if ($action == "edit") {
	$ad_name_text = get_job_ad_name($job_ads_id);
	if ($_SESSION["sess_user"] == "0")
		$smarty->assign("Pages",array(
			array("islink"=>1,"href"=>"advertisers_work.php?action=edit&uid_adv={$uid_adv}&{$SLINE}","text"=>$text_info["advertisers"].' ('.get_advertiser_name_by_id($uid_adv).') ',"spacer"=>"&nbsp;&raquo;&nbsp;"),
			array("islink"=>1,"href"=>"adv_advertisements.php?$SLINE","text"=>$text_info["adv_advertisements"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
			array("islink"=>0,"href"=>"","text"=>$ad_name_text,"spacer"=>""),
		));
	else
		$smarty->assign("Pages",array(
			array("islink"=>1,"href"=>"adv_advertisements.php?$SLINE","text"=>$text_info["adv_advertisements"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
			array("islink"=>0,"href"=>"","text"=>$ad_name_text,"spacer"=>""),
		));
}
else {
	if ($_SESSION["sess_user"] == "0")
		$smarty->assign("Pages",array(
			array("islink"=>1,"href"=>"advertisers_work.php?action=edit&uid_adv={$uid_adv}&{$SLINE}","text"=>$text_info["advertisers"].' ('.get_advertiser_name_by_id($uid_adv).') ',"spacer"=>"&nbsp;&raquo;&nbsp;"),
			array("islink"=>1,"href"=>"adv_advertisements.php?$SLINE","text"=>$text_info["adv_advertisements"],"spacer"=>""),
		));
	else
		$smarty->assign("Pages",array(
			array("islink"=>1,"href"=>"adv_advertisements.php?$SLINE","text"=>$text_info["adv_advertisements"],"spacer"=>"")
		));
}

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("adv_advertisement_jobs_from_my_site_work.html");

//Create form
$form_capt = ($action != "add") ? $text_info["c_edit"].$text_info["adv_advertisement_jobs_from_my_site_work"] : $text_info["c_add"].$text_info["adv_advertisement_jobs_from_my_site_work"];
smarty_create_cform("frm","mainform","POST","adv_advertisement_jobs_from_my_site_work.php","","",5,$form_capt,3,200,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",$FormButtons);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>$action),
	array("fname"=>"job_ads_id","fvalue"=>$job_ads_id),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Save session values for current page
save_session_values("adv_advertisement_jobs_from_my_site_work");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>