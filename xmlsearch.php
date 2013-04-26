<?
session_start();

require_once "consts.php";
require_once $admin_dir_path."app_errors_handler.php";
//require_once "consts_smarty.php";
require_once "language.php";
//require_once "template_vals.php";
require_once "search_functions.php";
require_once "index_functions.php";
require_once "xmlsearch_functions.php";
require_once $admin_dir_path."connect.inc";
require_once $admin_dir_path."include/functions/functions_main.php";
require_once $frontend_script_dir."app_cache_functions.php";
require_once $frontend_script_dir."common_functions.php";
require_once $frontend_script_dir."common_statistic.php";

doconnect();

//Check IPFW
if (!check_visitor_ipfw_cache()) { create_xmsearch_error_result($Error_messages["xml_blocked_ip"]); exit; }

//Check settings
get_global_settings();

$job_search_params = $job_list = $adv_job_list = array();
$result = false;

//Null search session values
null_search_sess_values();
//Get search data
$job_search_params = get_job_xmlsearch_params();
//Collect xml visitor info
collect_xmlvisitor_info();
//Check search data
check_job_search_params($job_search_params);
//Check xml search data
check_job_xmlsearch_params($job_search_params);
//Check keyword
if ($job_search_params["error_code"] == "empty_keyword") { create_xmsearch_error_result($Error_messages["xml_empty_keyword"]); exit; }
//Check publisher info
get_publisher_info();
//Check XML feed
if (!xmlfeed_alloved_for_user()) { create_xmsearch_error_result($Error_messages["xml_xmlfeed_notallowed"]); exit; }

//Made job search
$_SESSION["sess_job_search"]["current_search_mode"] = "base_search";
do_job_search($job_search_params,$job_list,$adv_job_list,$result);

//Set statistic: search keyword
set_stats_search_keywords($job_search_params,2);

//Design job statistic values in job details URL
do_job_design_in_url();
//Design job search
do_xmljob_design($job_search_params,$job_list,$adv_job_list);

//Check location
if ($job_search_params["error_code"] == "empty_location") { create_xmsearch_error_result(str_replace("{*Location*}",$job_search_params["where"],$Error_messages["xml_empty_location"])); exit; }
else {
	//Set statistic: success search keyword
	set_stats_search_success_keywords($job_search_params);
}

//Bot protection
$_SESSION["sess_bot_protection"]["was_search"] = true;

//Design XML Output
do_xmljob_output($job_search_params,$job_list,$adv_job_list);
?>