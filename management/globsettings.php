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
require_once "globsettings_func.php";
require_once "app_cache_functions.php";
check_access(array(0));

doconnect();

$action = get_get_post_value("action","");
$change = get_post_true_false("change","");

//Check action
if ($change) try_change();
else {
	create_values(
		$_SESSION["globsettings"]["site_title"],									$_SESSION["globsettings"]["site_url"],
		$_SESSION["globsettings"]["adv_start_balance"],						$_SESSION["globsettings"]["min_adv_cost_per_click"],
		$_SESSION["globsettings"]["max_adv_headline_length"],			$_SESSION["globsettings"]["max_adv_line1_length"],
		$_SESSION["globsettings"]["max_adv_line2_length"],				$_SESSION["globsettings"]["amount_of_listings"],
		$_SESSION["globsettings"]["amount_of_adv_sponsor_jobs_top"],$_SESSION["globsettings"]["amount_of_adv_sponsor_jobs_bottom"],
		$_SESSION["globsettings"]["amount_of_adv_keyword_ads"],
		$_SESSION["globsettings"]["member_approved"],
		$_SESSION["globsettings"]["window_target"],								$_SESSION["globsettings"]["pub_start_balance"],
		$_SESSION["globsettings"]["xml_pub_approved"],						$_SESSION["globsettings"]["pub_referal_percent"],
		$_SESSION["globsettings"]["use_stats_cache"],							$_SESSION["globsettings"]["cache_actualtime_admin"],
		$_SESSION["globsettings"]["cache_actualtime_adv"],				$_SESSION["globsettings"]["cache_actualtime_pub"],
		$_SESSION["globsettings"]["use_frontend_cache"],					$_SESSION["globsettings"]["cache_frontend_actualtime_pages"],
		$_SESSION["globsettings"]["cache_frontend_actualtime_primitives"],$_SESSION["globsettings"]["earn_ip_protection"],
		$_SESSION["globsettings"]["allow_cities_in_db"],					$_SESSION["globsettings"]["allow_cities_not_in_db"],
		$_SESSION["globsettings"]["jobs_without_city"]
	);
}

//Global settings page
$smarty->assign("curpage","globsettings");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"globsettings.php?$SLINE","text"=>$text_info["globsettings"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("globsettings.html");

//Create form
$form_capt = $text_info["c_edit"].$text_info["globsettings"];
smarty_create_cform("frm","mainform","POST","globsettings.php","","",5,$form_capt,3,300,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"change","bvalue"=>$text_info["btn_change"] ,"bscript"=>""),
));

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>