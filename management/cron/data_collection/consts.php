<?
/*
#################
# consts.php
# Constant values
# for data collection
# (Main)
#################
# Tab size = 2
#################
*/

//Admin directory pah. Be aware to include the trailing slash (/).
$admin_dir_path = dirname(__FILE__)."/../../";

//Include main consts.php file (from admin area)
require_once $admin_dir_path."consts.php";

//Directory information Be aware to include the trailing slash (/).
$data_collection_script_dir	= dirname(__FILE__);

//Data collection settings
$data_collection_config["feeds_count"] = 50; //how many feeds try to update per this script run //как много ресурсав опрашивать за один вызов этого скрипта
$data_collection_config["should_sleep_after_connect"] = true; //should we run usleep after CONNECT and job INSERT
$data_collection_config["sleep_after_connect_timeout"] = 300000; //should we run usleep after CONNECT and job INSERT - 1000000 is 1sec

//Crawl log settings
$crawl_log_info["uselog"] = true; //писать log
$crawl_log_info["file"] = $admin_dir_path."logs/crawl_log.txt";
$crawl_log_info["maxsize"] = 1024; //max application log file size (in KB)
$crawl_log_info["log_rotate"]	= true; //what to do after we have file more then "maxsize" (rewrite or rotate)
?>
