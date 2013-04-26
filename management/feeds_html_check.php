<?
session_start();

require_once "cron/data_collection/consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "include/functions/functions_main.php";
require_once "include/functions/common_functions.php";
require_once "app_events_handler.php";
require_once "functions.php";
require_once "functions_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "include/other/table_mini.php";
require_once "app_cache_functions.php";
require_once "cron/data_collection/functions.php";
require_once "cron/data_collection/language.php";
require_once "cron/data_collection/language_additional.php";
check_access(array(0));

doconnect();

$my_error = "";
$cat_id = data_addslashes(get_get_value("cat_id",""));
$feed_id = data_addslashes(get_get_value("feed_id",""));
$config_id = data_addslashes(get_get_value("config_id",""));
$url = data_addslashes(get_get_value("url",""));

//Check values on emptiness
$vallist = array($cat_id,$feed_id,$config_id,$url);
$errlist = array($Error_messages["no_cat_id"],$Error_messages["no_feed_id"],$Error_messages["no_config_id"],$Error_messages["no_url"]);
isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)


if ($my_error != "") smarty_create_message("error","info.gif",$my_error);
else {
	$DataCollectionGlobal = array('mode'=>'check','errors'=>array(),'messages'=>array(),'break'=>0); //Global array for data collection: 'break', stop parsing in 'check' mode

	//Insert tem date for check search
	$qr_res = mysql_query("DELETE FROM ".$db_tables["html_feeds_data_temp"]." WHERE feed_id='{$feed_id}'") or query_die(__FILE__,__LINE__,mysql_error());
	$qr_res = mysql_query("INSERT INTO ".$db_tables["html_feeds_data_temp"]." VALUES(NULL,'{$feed_id}','$cat_id','".addslashes($url)."','$config_id')") or query_die(__FILE__,__LINE__,mysql_error());

	$qr_res = mysql_query("SELECT * FROM ".$db_tables["sites_feed_list"]." WHERE feed_id='$feed_id'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		$feed = array("result"=>1, "feed_id"=>$myrow["feed_id"], "feed_code"=>$myrow["feed_code"], "title"=>$myrow["title"],
									"description"=>$myrow["description"], "url"=>$myrow["url"], "registered"=>$myrow["registered"],
									"refresh_rate"=>$myrow["refresh_rate"], "max_recursion_depths"=>2/*$myrow["max_recursion_depths"]*/,
									"feed_type"=>$myrow["feed_type"], "job_ads_id"=>$myrow["job_ads_id"], "feed_format"=>$myrow["feed_format"]);
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

	//Start parsing
	start_feed_parsing($feed["feed_id"],false);

	//Critical parsing errors
	if (!isset($Configuration)) {
		$check_result = array("cat_name"=>$feed_row["cat_name"], "config_name"=>$feed_row["config_name"], "checked_url"=>$url,
												"log"=>print_r($DataCollectionGlobal["messages"],true),
												"iserror"=>true
										);
	}
	else {
	
		//Formatting data
		foreach ($Configuration["fields"] as $k=>$v)
		{
//			if ($Configuration["fields"][$k]["name"] == "description") {
				foreach($Configuration["fields"][$k]["data"] as $k1=>$v1)
				{
					$Configuration["fields"][$k]["data"][$k1] = substr(convert_html_to_text(trim( $v1 )),0,160);
					$Configuration["fields"][$k]["data"][$k1] = str_replace("\n", "", $Configuration["fields"][$k]["data"][$k1]);
				}
//				break;
//			}
/*
			if ($Configuration["fields"][$k]["name"] == "locId") {
				foreach($Configuration["fields"][$k]["data"] as $k1=>$v1)
				{
					$Configuration["fields"][$k]["data"][$k1] = substr(convert_html_to_text(trim( $v1 )),0,160);
					$Configuration["fields"][$k]["data"][$k1] = str_replace("\n", "", $Configuration["fields"][$k]["data"][$k1]);
				}
				break;
			}
*/
		}
		foreach ($DataCollection["possible_insert"] as $k=>$v)
		{
			$DataCollection["possible_insert"][$k]["description"] = str_replace("\n", "", $DataCollection["possible_insert"][$k]["description"]);
		}
		foreach ($DataCollection["real_insert"] as $k=>$v)
		{
			$DataCollection["real_insert"][$k]["description"] = str_replace("\n", "", $DataCollection["real_insert"][$k]["description"]);
  	}
	
		//Save data
		$check_result = array("cat_name"=>$feed_row["cat_name"], "config_name"=>$feed_row["config_name"], "checked_url"=>$url,
												"parsing_result"=>print_r($Configuration["fields"],true),
												"possible_insert"=>print_r($DataCollection["possible_insert"],true),
												"real_insert"=>print_r($DataCollection["real_insert"],true),
												"log"=>print_r($DataCollectionGlobal["messages"],true),
												"iserror"=>false);
	}

	$smarty->assign("check_result",$check_result);

}

smarty_create_session_data();

$smarty->display('page_feeds_html_check.tpl');
?>