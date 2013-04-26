<?
// Удаляет старые данные в таблицах
define("db_sites_feed_log_clean_days", 4); //delete all records older then db_sites_feed_log_clean_days days
define("db_data_list_data_clean_days", 65);	//delete all records older then db_data_list_data_clean_day days
define("db_data_list_deleted_data_clean_days", 3); //delete all records older then X days

$adminsite_script_dir = dirname(__FILE__)."/../";
include_once $adminsite_script_dir."consts.php"; //Include main consts.php file (from frontend area)
require_once $adminsite_script_dir."app_errors_handler.php";
//require_once $adminsite_script_dir."app_cache_functions.php";
require_once $adminsite_script_dir."language.php";
require_once $adminsite_script_dir."connect.inc";
require_once $adminsite_script_dir."include/functions/functions_main.php";

function clear_sites_feed_log_table()
{
 global $db_tables;
	mysql_query("DELETE FROM ".$db_tables["sites_feed_log"]." WHERE actiontime<=DATE_SUB(NOW(),INTERVAL ".db_sites_feed_log_clean_days." DAY)") or query_die(__FILE__,__LINE__,mysql_error());
	mysql_query("OPTIMIZE TABLE ".$db_tables["sites_feed_log"]) or query_die(__FILE__,__LINE__,mysql_error());
}

function clear_data_list_data_table()
{
 global $db_tables;
	mysql_query("DELETE FROM ".$db_tables["data_list"]." WHERE registered<=DATE_SUB(NOW(),INTERVAL ".db_data_list_data_clean_days." DAY)") or query_die(__FILE__,__LINE__,mysql_error());
	mysql_query("OPTIMIZE TABLE ".$db_tables["data_list"]) or query_die(__FILE__,__LINE__,mysql_error());
}

function clear_data_list_deleted_data_table()
{
 global $db_tables;
	mysql_query("DELETE FROM ".$db_tables["data_list_deleted"]." WHERE registered<=DATE_SUB(NOW(),INTERVAL ".db_data_list_deleted_data_clean_days." DAY)") or query_die(__FILE__,__LINE__,mysql_error());
	mysql_query("OPTIMIZE TABLE ".$db_tables["data_list_deleted"]) or query_die(__FILE__,__LINE__,mysql_error());
}

doconnect();

clear_sites_feed_log_table();
clear_data_list_data_table();
clear_data_list_deleted_data_table();
?>