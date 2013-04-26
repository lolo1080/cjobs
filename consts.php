<?
/*
#################
# consts.php
# Constant values
# (Main)
#################
# Tab size = 2
#################
*/

//Admin directory pah. Be aware to include the trailing slash (/).
$admin_dir_path = dirname(__FILE__)."/management/";

//Include main consts.php file (from admin area)
require_once $admin_dir_path."consts.php";

//Directory information Be aware to include the trailing slash (/).
$visitor_script_dir	= dirname(__FILE__);
$frontend_script_dir	= $visitor_script_dir."/frontend/";
$use_additional_table_for_deleted_jobs = false;

//Additional settings
$usersettings["xml_datetimeformat"] = "D, d M Y H:i:s GMT";   //Date and time format for XML output
$usersettings["recent_job_searches_cookie_time"] = 3600 * 24 * 30 * 12;   //1 year
?>