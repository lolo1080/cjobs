<?
// Оправка списка работы подписчикам
define("alerts_msg_count", 20);

$adminsite_script_dir = dirname(__FILE__)."/../";
$main_frontend_script_dir = dirname(__FILE__)."/../../";
require_once $main_frontend_script_dir."consts.php";
require_once $adminsite_script_dir."app_errors_handler.php";
require_once $main_frontend_script_dir."consts_smarty.php";
require_once $main_frontend_script_dir."language.php";
require_once $main_frontend_script_dir."search_functions.php";
require_once $main_frontend_script_dir."index_functions.php";
require_once $adminsite_script_dir."connect.inc";
require_once $adminsite_script_dir."include/functions/functions_main.php";
require_once $frontend_script_dir."app_cache_functions.php";
require_once $frontend_script_dir."common_functions.php";
require_once $adminsite_script_dir."consts_mail.php";
require_once $adminsite_script_dir."include/mail/send_mail.php";


//Get job params from XML line
function get_job_search_params_from_xml_line($job_alert)
{
	$xml_vals = array("job_alert_type","what","where","distance","deliver","as_all","as_phrase","as_any","as_not","as_title","as_company","jobs_category","jobs_type","jobs_from","norecruiters","salary");
	$js_vals = array( "search_type"   ,"what","where","radius"  ,"deliver","as_all","as_phrase","as_any","as_not","as_title","as_company","jobs_category","jobs_type","jobs_from","norecruiters","salary");
	for ($i=0; $i<count($xml_vals); $i++)
	{
		$job_search_params[$js_vals[$i]] = (preg_match("~<{$xml_vals[$i]}>(.*?)</{$xml_vals[$i]}>~i", $job_alert, $matches)) ? $matches[1] : "";
	}
	$job_search_params["error_code"] = "none";
	$job_search_params["jobs_published"] = $job_search_params["deliver"];
	$job_search_params["number_results"] = alerts_msg_count;
	$job_search_params["sort_by"] = "";	//sort jobs by (relevance, date). Empty means - relevance
	$job_search_params["job_country"] = 0;
	//Some blank values
	$job_search_params["title"] = $job_search_params["company_name"] = $job_search_params["job_country"] = 
	$job_search_params["what_where"] = $job_search_params["job_where"] = $job_search_params["job_city_state"] = "";
	//Coutry stub - get all counries jobs
	$_SESSION["globsettings"]["selected_country"] = "all";
 return $job_search_params;
}

//Sent job alert e-mail to member
function send_job_alert_email($emailto,$first_name,$period,&$jobs_items)
{
 global $parse_values,$job_search_params;
	//Parse values
	$admin_email = get_admin_email_free($first_name);
	$parse_values["{*site_title*}"]	= $_SESSION["globsettings"]["site_title"];
	$parse_values["{*site_url_txt*}"]			= $_SESSION["globsettings"]["site_url"];
	$parse_values["{*member_first_name*}"]= $first_name;
	$parse_values["{*period*}"]= $period;
	$parse_values["{*job_keywords*}"]= get_search_results_stats();
	$parse_values["{*member_login_url*}"]= $_SESSION["globsettings"]["site_url"].'myarea/';

	$_SESSION["sess_job_search"]["job_search_params"]["jobs_published"] = 1;
	$parse_values["{*view_jobs_since_yesterday*}"] = $_SESSION["globsettings"]["site_url"].'jobs/?'.search_params_url().'&job_alert=1';
	$_SESSION["sess_job_search"]["job_search_params"]["jobs_published"] = 7;
	$parse_values["{*view_jobs_since_7days*}"] = $_SESSION["globsettings"]["site_url"].'jobs/?'.search_params_url().'&job_alert=7';
	$_SESSION["sess_job_search"]["job_search_params"]["jobs_published"] = "";
	$parse_values["{*view_jobs_all*}"] = $_SESSION["globsettings"]["site_url"].'jobs/?'.search_params_url();

	$parse_values["{*jobs_list*}"]= $jobs_items;

	//Memeber cron job e-mail alert 
	$subj	= get_mailsubject("member_job_alert");
	$htmlmessage = get_email_file("member_job_alert","html");
	$textmessage = get_email_file("member_job_alert","txt");
	$attach_files= get_mail_attach("member_job_alert");
	$subj = str_replace("{*site_title*}",$parse_values["{*site_title*}"],$subj);
	create_and_send_email($emailto,$admin_email,$subj,$htmlmessage,$textmessage,$attach_files);
}


doconnect();

//Check settings
get_global_settings();

$deliver_array = array(1,7);
for ($i=0; $i<count($deliver_array); $i++)
{
	//Select job alerts
	$deliver = $deliver_array[$i];
	$qr_res = mysql_query("SELECT ja.*, m.email,m.first_name,m.last_name FROM ".$db_tables["member_job_alerts"]." ja ".
			"INNER JOIN ".$db_tables["users_member"]." m ON ja.uid_mem=m.uid_mem and m.isconfirmed=1 ".
			"WHERE ja.deliver={$deliver} and ja.senddate<=DATE_SUB(NOW(),INTERVAL 25 HOUR) and ja.status=1 ".
			"ORDER BY ja.ja_id LIMIT ".alerts_msg_count) or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		//Clear values
		$job_list = $adv_job_list = array();
		//Get search data
		$job_search_params = get_job_search_params_from_xml_line($myrow["job_alert"]);
		//Made job search
	//!!!
	$job_search_params["jobs_published"] = "";
	//!!!
		$_SESSION["sess_job_search"]["current_search_mode"] = "base_search";
		$_SESSION["sess_job_search"]["add_current_search_info"] = "cron_job_alert";
		//Check keyword
		if ($job_search_params["error_code"] == "empty_keyword") continue;
		//Made common job search
		do_job_search($job_search_params,$job_list,$adv_job_list,$result,1);
		//Check results count
		if (count($job_list) > 0) {
			//jobs list: add some data
			foreach($job_list as $job_id=>$job_data)
			{
 		 		$feed_name = get_feed_name_by_id($job_data["feed_id"]);
				$job_list[$job_id]["feed_name"] = (isset($feed_name["title"])) ? $feed_name["title"] : "";
				$registered_ago = get_registered_ago($job_data["registered_sec"]);
				$job_list[$job_id]["registered_ago"] = $registered_ago["text"];
				$job_list[$job_id]["clickurl"] = preg_replace ("/(&".$SNAME."=[a-zA-Z0-9]*)/si", "", $job_list[$job_id]["clickurl"]);
			}
			//desing jobs with smarty
			$smarty->assign_by_ref("SearchJobsList",$job_list);
			$jobs_items = $smarty->fetch($frontend_template_dir."mail_member_jobs_alert_items.tpl");
			send_job_alert_email($myrow["email"],$myrow["first_name"],$text_info["deliver_".$deliver],$jobs_items);
		}
	}
}
?>