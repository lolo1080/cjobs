<?
session_start();

require_once "consts.php";
require_once $admin_dir_path."app_errors_handler.php";
require_once "consts_smarty.php";
require_once "language.php";
require_once "template_vals.php";
require_once "search_functions.php";
require_once "search_functions_errpages.php";
require_once "index_functions.php";
require_once $admin_dir_path."connect.inc";
require_once $admin_dir_path."include/functions/functions_main.php";
require_once $frontend_script_dir."app_cache_functions.php";
require_once $frontend_script_dir."common_functions.php";
require_once $frontend_script_dir."common_statistic.php";

doconnect();

//Check IPFW
if (!check_visitor_ipfw_cache()) { header("Location: ipfw.php"); exit; }

//Check settings
get_global_settings();

//Collect visitor info
collect_visitor_info();

//Check publisher info
get_publisher_info();

//Create some values
$job_search_params = $job_list = $adv_job_list = array();
$result = $full_search = false;

//Check query without session
check_data_without_session($job_search_params);

/*
Plus Locations Search
*/
if (plus_locations_search($job_search_params)) {
	//Made job search
	$_SESSION["sess_job_search"]["current_search_mode"] = "plus_locations_search";
	//!remove $job_search_params = get_job_search_params();
	do_job_search($job_search_params,$job_list,$adv_job_list,$result);
}
/*
Base Search + Next Page + Change Show type + Change Order
next_page_query - visitor select new page
show_jobs_new - visitor change show mode (Show: all jobs - 44 new job)
change_jobs_sort - visitor change sore by mode (Sort by: relevance - date)
*/
elseif (!next_page_query($job_search_params,$job_list,$adv_job_list,$result) && 
		!show_jobs_new($job_search_params,$job_list,$adv_job_list,$result) && 
		!change_jobs_sort($job_search_params,$job_list,$adv_job_list,$result) ) {
	//Full search
	$full_search = true;
	//Null search session values
	null_search_sess_values();
	//Get search data
	$job_search_params = get_job_search_params();
	//Check filter (left column)
	if (filter_work()) correct_values_using_filter($job_search_params);
	//Check search data
	check_job_search_params($job_search_params);
	//Check keyword
	if ($job_search_params["error_code"] == "empty_keyword") create_start_error_page(str_replace("{*Location*}",$job_search_params["where"],$Error_messages["search_empty_keyword"]));
	//Made job search
	$_SESSION["sess_job_search"]["current_search_mode"] = "base_search";
	do_job_search($job_search_params,$job_list,$adv_job_list,$result);
	//Set statistic: search keyword
	set_stats_search_keywords($job_search_params,1);
}
//Check results count
if ( (count($job_list) == 0) && (count($adv_job_list) == 0) ) create_empty_search_result_page($job_search_params);
elseif ($full_search) {
	//Set statistic: success search keyword
	set_stats_search_success_keywords($job_search_params);
}
//Design job search
do_job_design($job_search_params,$job_list,$adv_job_list);
//Design job statistic values in job details URL
do_job_design_in_url();

//Check location
if ($job_search_params["error_code"] == "empty_location") create_start_error_page($Error_messages["search_empty_location"]);

//Bot protection
$_SESSION["sess_bot_protection"]["was_search"] = true;

//Add MyJobs from cookie
if (isset($_COOKIE["MyJobs_save"])) $smarty->assign("MyJobs_save",$_COOKIE["MyJobs_save"]);
else $smarty->assign("MyJobs_save",array());

//Site header
create_site_header_search();

// * * Check cache * * //
$template_id = 11;
$sql = "SELECT * FROM ".$db_tables["template_values"]." WHERE template_id='$template_id'";
$cache_params_array = array(
	"user"				=> 3, //$_SESSION["sess_user"]
	"cache_group"	=> "smarty_frontend",
	"userid"			=> 0, //$_SESSION["sess_userid"]
	"section"			=> "search_template_values",
	"table_name"	=> "template_values",
	"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
	"query"				=> $sql,
	"actual_time"	=> 15*60, //Время актуальности в сек.
	"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
);
prepare_template_values($cache_params_array,$template_id);
$smarty->display('searchpage.tpl');
?>