<?
/*
##################
# index.php
# Main script for
# data collections
# ---------------
# Runs by Cron
##################
# Tab size = 2
##################
*/

require_once "consts.php";
require_once $admin_dir_path."app_errors_handler.php";
require_once $admin_dir_path."connect.inc";
require_once $admin_dir_path."include/functions/functions_main.php";
require_once $admin_dir_path."include/functions/common_functions.php";
require_once $admin_dir_path."consts_mail.php"; //mail settings
require_once $admin_dir_path."mail_func.php";
require_once $admin_dir_path."include/other/table_mini.php";
require_once $admin_dir_path."include/mail/send_mail.php";
require_once $admin_dir_path."functions.php";
require_once $admin_dir_path.'xml_parser/class.xml2array.corrected.php';
require_once $admin_dir_path.'xml_parser/php45fix.php';
require_once "functions.php";
require_once "language.php";
require_once "language_additional.php";
require_once $frontend_dir."app_cache_functions.php";

set_time_limit(0);

doconnect();

debug_log_file($log_actions["run"],1,0,'Run job search script','Run cron job search script');
check_feed_list_structure();

$FID = 28;
$feed = array("result"=>0);
$qr_res = mysql_query("SELECT * FROM ".$db_tables["sites_feed_list"]." WHERE feed_id={$FID}") or query_die(__FILE__,__LINE__,mysql_error());
if (mysql_num_rows($qr_res) > 0) {
	$myrow = mysql_fetch_array($qr_res);
	$feed = array("result"=>1, "feed_id"=>$myrow["feed_id"], "feed_code"=>$myrow["feed_code"], "title"=>$myrow["title"],
								"description"=>$myrow["description"], "url"=>$myrow["url"], "registered"=>$myrow["registered"],
								"refresh_rate"=>$myrow["refresh_rate"], "max_recursion_depths"=>$myrow["max_recursion_depths"],
								"feed_type"=>$myrow["feed_type"],"job_ads_id"=>$myrow["job_ads_id"],"feed_format"=>$myrow["feed_format"]);
	//Get additional feed info for Advertiser
	if ($feed["feed_type"] == "advertiser") {
		$qr_res = mysql_query("SELECT * FROM ".$db_tables["job_ads"]." WHERE job_ads_id='{$feed["job_ads_id"]}'") or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res) > 0) {
			$myrow = mysql_fetch_array($qr_res); 
			$feed["uid_adv"] = $myrow["uid_adv"];
			$feed["status"] = $myrow["status"];
		}
		else {
			$feed["uid_adv"] = 0;
			$feed["status"] = 0;
		}
	}
}

start_feed_parsing($feed["feed_id"],true);
/*
$feeds_cnt = 0;
while ($feeds_cnt < $data_collection_config["feeds_count"])
{
	$feed = get_next_feed();
	if (!$feed["result"]) {
		debug_log_file($log_actions["select_feed"],0,0,'No available job feeds now','Exit. No available job feeds for parsing now. We will check next time.');
		break;
	}
	else {
		debug_log_file($log_actions["select_feed"],1,0,'Available feed: "'.addslashes($feed["title"]).'"','We have found Available feed "'.addslashes($feed["title"]).'" for parsing.');
		$CronEmailSendByFeed = false;
	}

	lock_this_feed($feed["feed_id"]);
	doconnect();
	//Start parsing
	start_feed_parsing($feed["feed_id"]);
	//Add stats data
	add_new_res_count();
	//Check quality
	check_new_res_quality();
	unlock_this_feed($feed["feed_id"]);

	$feeds_cnt++;
	if ($CronEmailSendByFeed)	send_cron_alert($feed["title"]);
	set_not_actual_frontend_table_cache_data("search_result","data_list",array(""),"as_array");
}
*/

debug_log_file($log_actions["finish"],1,0,'Finish job search script','Finish cron job search script');
?>