<?
session_start();

require_once "consts.php";
require_once $admin_dir_path."app_errors_handler.php";
require_once "consts_smarty.php";
require_once "language.php";
require_once "template_vals.php";
require_once "index_functions.php";
require_once "job_details_func.php";
require_once "search_functions_errpages.php";
require_once $admin_dir_path."connect.inc";
require_once $admin_dir_path."include/functions/functions_main.php";
require_once $frontend_script_dir."app_cache_functions.php";
require_once $frontend_script_dir."common_functions.php";
require_once $frontend_script_dir."common_statistic.php";
//Encrypt functions
require_once $frontend_script_dir."class.get_crypt.php";

function check_no_data_found(&$job_data)
{
 global $Error_messages;
	if (count($job_data) == 0) {
		$job_search_params["search_type"]	= "simple";
		create_start_error_page($Error_messages["search_no_data_found"]);
	}
}

function do_redirect_and_creck_result()
{
	function array_to_mystring($key,$value)
	{
		$tmp = "";
		foreach ($value as $k=>$v)
		{
			$tmp .= '&'.$key.'['.urlencode(stripslashes($k)).']='.urlencode(stripslashes($v));
		}
		return $tmp;
	}
	$rdval = html_chars(get_get_value("rdval",""));		//Get redirect value
	if ($rdval == "") {
		//Set uniqe values
		$_SESSION["sess_bot_protection"]["rdval"] = md5(uniqid(""));
		setcookie("cval",rand());
		//Add old values
		$req = "../job-details/?rdval=".Encrypt($_SESSION["sess_bot_protection"]["rdval"]);
		foreach ($_GET as $key=>$value) {
			if (is_array($value)) $req .= array_to_mystring($key,$value);
			else {
				$value = urlencode(stripslashes($value));
				$req .= "&$key=$value";
			}
		}
		//Do redirect
		header("Location: ".$req); exit;
	}
	else check_redirect_bot_protection($rdval);
}


//Get data ids (common data and advertiser data)
$data_id = html_chars(get_get_value("data_id",""));					//Main result list
$data_id_adv = html_chars(get_get_value("data_id_adv",""));	//Top adv list
$ad_id 	= html_chars(get_get_value("ad_id",""));						//Right sponsored links list

if ( (($data_id == "") && ($data_id_adv == "") && ($ad_id == "")) ||
		 (($data_id != "") && !check_int($data_id)) ||
		 (($data_id_adv != "") && !check_int($data_id_adv)) ||
		 (($ad_id != "") && !check_int($ad_id)) ) {
	doconnect();
	//Check settings
	get_global_settings();
	$job_search_params["search_type"]	= "simple";
	create_start_error_page($Error_messages["search_no_data_id"]);
}
elseif (($data_id_adv != "") || ($ad_id != "")) do_redirect_and_creck_result(); //Bot protection

doconnect();

//Check IPFW
if (!check_visitor_ipfw_cache()) { header("Location: ipfw.php"); exit; }

//Check settings
get_global_settings();

//Collect visitor info
collect_visitor_info();

//Check publisher info
get_publisher_info();


$job_data = array();

//Main result data -->
if ($data_id != "") {
	//Select this job
	$job_data = do_job_data_search($data_id);
	//Check results count
	check_no_data_found($job_data);
	//Set statistic: search keyword
	set_stats_clicks($job_data,0);
}
//Top adv list -->
elseif ($data_id_adv != "") {
	//Bot protection
	adv_check_bot_protection();
	//Select this job
	$job_data = do_job_adv_data_search($data_id_adv);
	//Check results count
	check_no_data_found($job_data);
	//Set statistic: search keyword
	set_stats_clicks($job_data,1);
	$_SESSION["sess_bot_protection"]["visitor_was_stored"] = 1;
}
//Right sponsored links list -->
elseif ($ad_id != "") {
	//Bot protection
	adv_check_bot_protection();
	//Select this job
	$job_data = do_job_sponsored_adv_data_search($ad_id);
	//Check results count
	check_no_data_found($job_data);
	//Set statistic: search keyword
	set_stats_clicks($job_data,2);
	$_SESSION["sess_bot_protection"]["visitor_was_stored"] = 1;
}

header("Location: ".$job_data[0]["url"]); exit;
?>