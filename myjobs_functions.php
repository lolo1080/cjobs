<?
function get_myjob_number_results()
{
 global $job_search_params,$number_results_array,$number_results_default;
	$job_search_params["number_results"]= html_chars(get_get_post_value("number_results",$number_results_default));//number results per page
	if (($job_search_params["number_results"] == "") || !check_int($job_search_params["number_results"]) || !isset($number_results_array[$job_search_params["number_results"]])) $job_search_params["number_results"] = $number_results_default;
	$_SESSION["sess_job_search"]["job_search_params"]["number_results"] = $job_search_params["number_results"];
}

function check_remove_myjob()
{
	$jobkey = html_chars(get_get_post_value("jobkey",""));
	if (($jobkey != "") && isset($_COOKIE["MyJobs_save"][$jobkey])) {
		unset($_COOKIE["MyJobs_save"][$jobkey]);
		setcookie("MyJobs_save[{$jobkey}]","",time() - 3600,"/");
	}
}

function sortjobs_by_cookie(&$job_list)
{
	$job_list_temp = array();
	if (!isset($_COOKIE["MyJobs_save"])) return;
	foreach($_COOKIE["MyJobs_save"] as $k=>$v)
	{
		if ($v == "") continue;
		if (isset($job_list[$k])) $job_list_temp[$k] = $job_list[$k];
	}
	$job_list = $job_list_temp;
}
?>