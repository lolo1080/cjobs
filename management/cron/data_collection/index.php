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

$feeds_cnt = 0;
$DataCollectionGlobal = array('mode'=>'cron','errors'=>array(),'messages'=>array(),'break'=>0); //Global array for data collection: 'break', stop parsing in 'check' mode
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
	start_feed_parsing($feed["feed_id"],true);
	//Add stats data
	add_new_res_count();
	//Check quality
	check_new_res_quality();
	unlock_this_feed($feed["feed_id"]);

	$feeds_cnt++;
	if ($CronEmailSendByFeed)	send_cron_alert($feed["title"]);
	set_not_actual_frontend_table_cache_data("search_result","data_list",array(""),"as_array");
}

debug_log_file($log_actions["finish"],1,0,'Finish job search script','Finish cron job search script');
?>