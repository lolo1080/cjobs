<?
function event_handler($event_array)
{
 global $db_tables;

	switch ($event_array["event"]) {

		case "chstatus":
			switch ($event_array["source"]) {
				case "adv_advertisements":		// => Change record status on Advertisements page
					//Remove cache for related tables
					$a = remove_table_stats_cache("adv_advertisements",array(),"stats_adv_tmp");
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","data_list_advertiser",array(""),"as_array");
				break;
				case "adv_advertisement_keyword_ad":		// => Change record status on Advertisements page - Keyword Advertisement (___) page
					//Remove cache for related tables
					$a = remove_table_stats_cache("adv_advertisement_keyword_ad",array($event_array["ad_id"]),"stats_keyword_ads_tmp");
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","keyword_ads",array("ads"),"as_array");
					set_not_actual_frontend_table_cache_data("search_result","ads",array(""),"as_array");
				break;
			}
			break;

		case "update":
			switch ($event_array["source"]) {
				case "adv_advertisement_keyword_ad":		// => Edit record on Advertisements - Keyword Advertisement - ____ page (adv_advertisement_keyword_ad_func.php)
					//Remove cache for related tables
					$a = remove_table_stats_cache("adv_advertisements",array(),"stats_adv_tmp");
					$a = remove_table_stats_cache("adv_advertisement_keyword_ad",array($event_array["ad_id"]),"stats_keyword_ads_tmp");
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","keyword_ads",array("ads"),"as_array");
					set_not_actual_frontend_table_cache_data("search_result","ads",array(""),"as_array");
					//Remove from admin area cache
					remove_table_stats_cache_admin("adv_advertisements",array(),"stats_adv_tmp");
				break;
				case "adv_advertisement_jobs_from_my_site":		// => Edit record on Advertisements - Sponsor jobs from my site (adv_advertisement_jobs_from_my_site_func.php)
					//Remove cache for related tables
					$a = remove_table_stats_cache("adv_advertisements",array(),"stats_adv_tmp");
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","data_list_advertiser",array(""),"as_array");
					//Remove from admin area cache
					remove_table_stats_cache_admin("adv_advertisements",array(),"stats_adv_tmp");
				break;
				case "advertisers":		// => Edit record on Advertiser profile (advertisers_func.php)
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","data_list_advertiser",array(""),"as_array");
					set_not_actual_frontend_table_cache_data("search_result","keyword_ads",array("ads"),"as_array");
					set_not_actual_frontend_table_cache_data("search_result","ads",array(""),"as_array");
				break;
				case "publishers":		// => Edit record on Publisher profile ()
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","users_publisher",array(""),"as_array");
				break;
				case "ipfirewall":		// => Update IP Firewall page (ipfirewall_func.php)
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","ipfirewall",array(""),"as_array");
				break;
				case "admsettings":		// => Update Admin settings (admsettings.php)
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","admins",array(""),"as_array");
				break;
				case "mail":		// => Update Admin settings (admsettings.php)
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","mailsubject",array(""),"as_array");
				break;
			}
			break;

		case "insert":
			switch ($event_array["source"]) {
				case "adv_advertisement_keyword_ad":		// => Insert record on Advertisements - Keyword Advertisement - ____ page (adv_advertisement_keyword_ad_func.php)
					//Remove cache for related tables
					$a = remove_table_stats_cache("adv_advertisements",array(),"stats_adv_tmp");
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","keyword_ads",array("ads"),"as_array");
					set_not_actual_frontend_table_cache_data("search_result","ads",array(""),"as_array");
					//Remove from admin area cache
					remove_table_stats_cache_admin("adv_advertisements",array(),"stats_adv_tmp");
				break;
				case "adv_advertisement_jobs_from_my_site":		// => Insert record on Advertisements - Sponsor jobs from my site (adv_advertisement_jobs_from_my_site_func.php)
					//Remove cache for related tables
					$a = remove_table_stats_cache("adv_advertisements",array(),"stats_adv_tmp");
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","data_list_advertiser",array(""),"as_array");
					//Remove from admin area cache
					remove_table_stats_cache_admin("adv_advertisements",array(),"stats_adv_tmp");
				break;
				case "jobrollsettings":		// => Insert records on Jobroll settings (jobrollsettings_func.php)
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_template_values","search_template_values",array(""),"as_array");
				break;
				case "globsettings":		// => Insert records on Global settings (globsettings_func.php)
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("homepage_template_primitives","globsettings",array(""),"as_array");
				break;
				case "advertisers":		// => Edit record on Advertiser profile (advertisers_func.php)
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","data_list_advertiser",array(""),"as_array");
					set_not_actual_frontend_table_cache_data("search_result","keyword_ads",array("ads"),"as_array");
					set_not_actual_frontend_table_cache_data("search_result","ads",array(""),"as_array");
				break;
				case "publishers":		// => Register Publisher (pub_registration.php)
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","users_publisher",array(""),"as_array");
				break;
				case "ipfirewall":		// => Insert IP Firewall page (ipfirewall_func.php)
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","ipfirewall",array(""),"as_array");
				break;
				case "members":		// => Insert new Member (mem_registration_func.php)
						;
				break;
			}
			break;

		case "delete":
			switch ($event_array["source"]) {
				case "adv_advertisements":		// => Delete record from Advertisements page
					$ad_id 	= $event_array["ad_id"];
					mysql_query("DELETE FROM ".$db_tables["keyword_ads"]." WHERE ad_id='$ad_id'") or query_die(__FILE__,__LINE__,mysql_error());
					mysql_query("DELETE FROM ".$db_tables["stats_adv_pageview_keywords"]." WHERE ad_id='$ad_id' and uid_adv='{$_SESSION["sess_userid"]}'") or query_die(__FILE__,__LINE__,mysql_error());
					mysql_query("DELETE FROM ".$db_tables["stats_adv_maybe_pageview_keywords"]." WHERE ad_id='$ad_id' and uid_adv='{$_SESSION["sess_userid"]}'") or query_die(__FILE__,__LINE__,mysql_error());
					mysql_query("DELETE FROM ".$db_tables["stats_adv_click_keywords"]." WHERE ad_id='$ad_id' and uid_adv='{$_SESSION["sess_userid"]}'") or query_die(__FILE__,__LINE__,mysql_error());
					//Remove cache for related tables
					$a = remove_table_stats_cache("adv_advertisements",array(),"stats_adv_tmp");
					$a = remove_table_stats_cache("adv_advertisement_keyword_ad",array($ad_id),"stats_keyword_ads_tmp");
					//Remove front-end cache
					if ($event_array["table"] == "ads") {
						set_not_actual_frontend_table_cache_data("search_result","keyword_ads",array("ads"),"as_array");
						set_not_actual_frontend_table_cache_data("search_result","ads",array(""),"as_array");
					}
					elseif ($event_array["table"] == "job_ads") {
						set_not_actual_frontend_table_cache_data("search_result","data_list_advertiser",array(""),"as_array");
					}
					//Remove from admin area cache
					remove_table_stats_cache_admin("adv_advertisements",array(),"stats_adv_tmp");
				break;
				case "adv_advertisement_keyword_ad":		// => Delete record from Advertisements - Keyword Advertisement (___) page
					$kads_id= $event_array["kads_id"];
					$ad_id 	= $event_array["ad_id"];
					mysql_query("DELETE FROM ".$db_tables["stats_adv_pageview_keywords"]." WHERE kads_id='$kads_id'") or query_die(__FILE__,__LINE__,mysql_error());
					mysql_query("DELETE FROM ".$db_tables["stats_adv_maybe_pageview_keywords"]." WHERE kads_id='$kads_id'") or query_die(__FILE__,__LINE__,mysql_error());
					mysql_query("DELETE FROM ".$db_tables["stats_adv_click_keywords"]." WHERE kads_id='$kads_id'") or query_die(__FILE__,__LINE__,mysql_error());
					//Remove cache for related tables
					$a = remove_table_stats_cache("adv_advertisements",array(),"stats_adv_tmp");
					$a = remove_table_stats_cache("adv_advertisement_keyword_ad",array($ad_id),"stats_keyword_ads_tmp");
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","keyword_ads",array("ads"),"as_array");
					set_not_actual_frontend_table_cache_data("search_result","ads",array(""),"as_array");
					//Remove from admin area cache
					remove_table_stats_cache_admin("adv_advertisements",array(),"stats_adv_tmp");
				break;
				case "ipfirewall":		// => Delete IP Firewall page (ipfirewall.php)
					//Remove front-end cache
					set_not_actual_frontend_table_cache_data("search_result","ipfirewall",array(""),"as_array");
				break;
			}
			break;

		case "chtemplate":
			switch ($event_array["source"]) {
				case "smarty_frontend":		// => Change template content
					//Remove cache for all templates. NOTE: we use front-end app cache module!
					$cache_params_array = array(
						"user"				=> 3, //$_SESSION["sess_user"]
						"cache_group"	=> "smarty_frontend",
						"userid"			=> 0, //$_SESSION["sess_userid"]
						"section"			=> "",
						"table_name"	=> "",
						"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
						"query"				=> "",
						"actual_time"	=> 0, //Время актуальности в милисек.
						"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив	$cache_data_array
					);
					remove_mydata_cache($cache_params_array);
				break;
			}
			break;
	}
}
?>