<?
// Удаляет дубликацию данных о работе в таблицах
define("db_data_list_clean_period_days_default", 45);	//delete all records older then db_sites_feed_log_clean_days days
define("db_data_list_clean_period_count_default", 5000);	//recourds count in one part

if (isset($_SERVER['argc']) && ($_SERVER['argc'] == 3)) {
	$db_data_list_clean_period_days = $_SERVER['argv'][1];
	$db_data_list_clean_period_count = $_SERVER['argv'][2];
}
else {
	$db_data_list_clean_period_days = db_data_list_clean_period_days_default;
	$db_data_list_clean_period_count = db_data_list_clean_period_count_default;
}

$adminsite_script_dir = dirname(__FILE__)."/../";
include_once $adminsite_script_dir."consts.php"; //Include main consts.php file (from frontend area)
require_once $adminsite_script_dir."app_errors_handler.php";
require_once $adminsite_script_dir."language.php";
require_once $adminsite_script_dir."connect.inc";
require_once $adminsite_script_dir."include/functions/functions_main.php";
$main_frontend_script_dir = dirname(__FILE__)."/../../";
require_once $main_frontend_script_dir."consts.php";
require_once $frontend_script_dir."app_cache_functions.php";


set_time_limit(0);

doconnect();

//Get biggest data_id
$qr_res = mysql_query("SELECT data_id FROM ".$db_tables["data_list"]." ORDER BY data_id DESC LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
if (mysql_num_rows($qr_res) > 0) {
	$myrow = mysql_fetch_array($qr_res);
	$data_id = $myrow["data_id"];
}
else {
	echo "Cannot get data_id."; exit;
}

$Z = 0;
while (true) {
	//Get 5000 jobs
	$data_list = $del_data_list = array();
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["data_list"]." WHERE data_id<{$data_id} and ".
			"dateinsert>DATE_SUB(NOW(), INTERVAL ".$db_data_list_clean_period_days." DAY) ORDER BY data_id DESC LIMIT {$db_data_list_clean_period_count}") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$desc = preg_replace("/([^\w])/", "", $myrow["description"]);
		$desc = substr($desc, 0, 70);
/*
		$data_list[] = array("data_id"=>$myrow["data_id"],"title"=>$myrow["title"],"company_name"=>$myrow["company_name"],
			"locId"=>$myrow["locId"],"description"=>$desc,"job_type"=>$myrow["job_type"]);
*/
		$myrow["description_clear"] = $desc;
		$data_list[] = $myrow;
		end($data_list);
		$data_id = $myrow["data_id"];
	}
	usleep(10);

	if (count($data_list) < 10) {
		set_not_actual_frontend_table_cache_data("search_result","data_list",array(""),"as_array");
		exit;
	}
	$Z++;
	if ($Z > 10) {
		set_not_actual_frontend_table_cache_data("search_result","data_list",array(""),"as_array");
		exit;
	}

	//Delete duplicates
	$cnt = count($data_list);
	for ($i=0; $i<$cnt; $i++)
	{
		if ($i % 7 == 0) doconnect();
		for ($j=$i+1; $j<$cnt; $j++)
		{
			if (in_array($data_list[$j]["data_id"], $del_data_list)) continue;
			if (($data_list[$i]["title"] == $data_list[$j]["title"]) && ($data_list[$i]["company_name"] == $data_list[$j]["company_name"]) &&
				($data_list[$i]["locId"] == $data_list[$j]["locId"]) && ($data_list[$i]["description_clear"] == $data_list[$j]["description_clear"]) &&
				($data_list[$i]["job_type"] == $data_list[$j]["job_type"]) && ($data_list[$i]["country"] == $data_list[$j]["country"]) &&
				($data_list[$i]["city"] == $data_list[$j]["city"])) {
				$del_data_list[] = $data_list[$j]["data_id"];
				if ($use_additional_table_for_deleted_jobs) {
					mysql_query("INSERT INTO ".$db_tables["data_list_deleted"]." ".
						"(data_id,feed_id,title,company_name,locId,description,url,cat_id,job_type,site_type,isstaffing_agencies,salary,registered,source,dateinsert) ".
						"VALUES({$data_list[$j]["data_id"]},'{$data_list[$j]["feed_id"]}','".addslashes($data_list[$j]["title"])."', '".addslashes($data_list[$j]["company_name"])."',".
								"'".addslashes($data_list[$j]["locId"])."', '".addslashes($data_list[$j]["description"])."',".
								"'".addslashes($data_list[$j]["url"])."',".
								"'".addslashes($data_list[$j]["cat_id"])."',".
								"'".addslashes($data_list[$j]["job_type"])."', '".addslashes($data_list[$j]["site_type"])."',".
								"'".addslashes($data_list[$j]["isstaffing_agencies"])."','".addslashes($data_list[$j]["salary"])."','{$data_list[$j]["registered"]}',".
								"'".addslashes($data_list[$j]["source"])."','{$data_list[$j]["dateinsert"]}','{$data_list[$j]["country"]}','{$data_list[$j]["region"]}','{$data_list[$j]["city"]}')")
							or query_die(__FILE__,__LINE__,mysql_error());
				}
				mysql_query("DELETE FROM ".$db_tables["data_list"]." WHERE data_id='{$data_list[$j]["data_id"]}'") or query_die(__FILE__,__LINE__,mysql_error());
				usleep(1);
			}
		}
		set_not_actual_frontend_table_cache_data("search_result","data_list",array(""),"as_array");
	}
	usleep(100);
}
?>