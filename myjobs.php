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
require_once "myjobs_functions.php";
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
$result = false;

//Check delete cookie
check_remove_myjob();

//Null search session values
null_search_sess_values();
//Made job search
get_myjob_number_results();
$_SESSION["sess_job_search"]["current_search_mode"] = "base_search";
do_myjobs_job_search($job_search_params,$job_list,$adv_job_list,$result);

//Design job search
do_myjob_design($job_search_params,$job_list,$adv_job_list);
//Design job statistic values in job details URL
do_job_design_in_url();

//Implode all jobs
$job_list = array_merge($job_list,$adv_job_list);
sortjobs_by_cookie($job_list);
next_page_query_myjob($job_search_params,$job_list);

//Check jobs count
if (count($job_list) == 0) $my_error = $Error_messages["myjobs_empty"];
else $my_error = "";

//Create navigation array
create_navigation_array();

//Site title
$SiteTitle = $text_info["html_my_jobs"];

//Bot protection
$_SESSION["sess_bot_protection"]["was_search"] = true;

// * * Check cache * * //
$template_id = 22;
$sql = "SELECT * FROM ".$db_tables["template_values"]." WHERE template_id='$template_id'";
$cache_params_array = array(
	"user"				=> 3, //$_SESSION["sess_user"]
	"cache_group"	=> "smarty_frontend",
	"userid"			=> 0, //$_SESSION["sess_userid"]
	"section"			=> "myjobs_template_values",
	"table_name"	=> "template_values",
	"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
	"query"				=> $sql,
	"actual_time"	=> 37*60, //Время актуальности в сек.
	"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
);
prepare_template_values($cache_params_array,$template_id);
$smarty->display('myjobspage.tpl');
?>