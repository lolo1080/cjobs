<?php
function prepare_template_values(&$cache_params_array,$template_id)
{
 global $db_tables,$smarty,$templates_var_list,$my_error,$job_list,$pages_list;
 global $radius_array,$jobs_type_array,$jobs_from_array,$jobs_published_array,$number_results_array,$jobroll_more_link,
				$JobChannel,$JobrollPublisherID,$BrowseKeywordListNavigation,$BrowseKeywordListMostPopular;
	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get values list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = $myrow["template_vid"];
		}		
	}
	
	foreach ($templates_var_list as $k=>$v)
	{
		//Maybe present in sub-template (include in one from first level templates)
		switch ($v["name"]) {
			case '$AllowWithinSearch': $smarty->assign("AllowWithinSearch",get_allow_within_search()); break;
		}
		//Common
// 		if (!in_array($k, $data_array)) continue;
		switch ($v["name"]) {
			case '$JobCategories':
				$smarty->assign("JobCategories",get_jobcategories_selectbox()); 
				break;
			case '$JobCategoriesList': 
				$smarty->assign("JobCategoriesList",get_jobcategories_list()); 
				break;
			case '$JobCategoriesArrayList': 
				$smarty->assign("JobCategoriesArrayList",get_jobcategories_list_array()); 
				break;
			case '$SiteTitle': 
				$smarty->assign("SiteTitle",get_sitetitle()); 
				break;
			case '$SiteDescription': 
				$smarty->assign("SiteDescription",get_sitedescription()); 
				break;
			case '$SiteKeywords': 
				$smarty->assign("SiteKeywords",get_sitekeywords()); 
				break;
			case '$SearchRadiusList': 
				$smarty->assign_by_ref("SearchRadiusList",$radius_array); 
				break;
			case '$SearchJobTypesList': 
				$smarty->assign_by_ref("SearchJobTypesList",$jobs_type_array); 
				break;
			case '$SearchJobFromList': 
				$smarty->assign_by_ref("SearchJobFromList",$jobs_from_array); 
				break;
			case '$SearchJobPublishedList': 
				$smarty->assign_by_ref("SearchJobPublishedList",$jobs_published_array); 
				break;
			case '$SearchNumberResults': 
				$smarty->assign_by_ref("SearchNumberResults",$number_results_array); 
				break;
			case '$ErrorMessage': 
				$smarty->assign_by_ref("ErrorMessage",$my_error); 
				break;
			case '$SearchJobsList':
				$smarty->assign_by_ref("SearchJobsList",$job_list); 
				break;
			case '$PagesList': 
				$smarty->assign_by_ref("PagesList",$pages_list); 
				break;
			case '$ShowSearchType': 
				$smarty->assign("ShowSearchType",get_search_type_selection()); 
				break;
			case '$ShowSearchOrder': 
				$smarty->assign("ShowSearchOrder",get_search_order_selection()); 
				break;
			case '$ShowSearchResultStats': 
				$smarty->assign("ShowSearchResultStats",get_search_results_stats()); 
				break;
			case '$ShowCategoriesLinks': 
				$smarty->assign("ShowCategoriesLinks",get_categories_links_on_search_page()); 
				break;
			case '$ShowJobsPerPage': 
				$smarty->assign("ShowJobsPerPage",get_jobs_per_page_buttons()); 
				break;
			case '$JobFilterParams': 
				$smarty->assign("JobFilterParams",get_jobs_filter_params()); 
				break;
			case '$JobFilteredBy': 
				$smarty->assign("JobFilteredBy",get_jobs_jobfilteredby_params()); 
				break;
			case '$KeywordAds': 
				$smarty->assign("KeywordAds",get_jobs_keyword_ads()); 
				break;
			case '$AdvSearchJobsListTop': 
				$smarty->assign("AdvSearchJobsListTop",get_advertiser_jobs_list_ads(0)); 
				break;
			case '$AdvSearchJobsListBottom': 
				$smarty->assign("AdvSearchJobsListBottom",get_advertiser_jobs_list_ads(1));
				break;
			case '$BaseSiteURL': 
				$smarty->assign("BaseSiteURL",get_base_site_url()); 
				break;
			case '$JobrollFormat': 
				$smarty->assign("JobrollFormat",get_jobroll_format()); 
				break;
			case '$JobRollSettings': 
				$smarty->assign("JobRollSettings",get_jobroll_settings()); 
				break;
			case '$JobRollJobsList': 
				$smarty->assign_by_ref("JobRollJobsList",$job_list); 
				break;
			case '$JobRollMoreLink': 
				$smarty->assign_by_ref("JobRollMoreLink",$jobroll_more_link); 
				break;
			case '$JobChannel': 
				$smarty->assign_by_ref("JobChannel",$JobChannel); 
				break;
			case '$JobrollPublisherID': 
				$smarty->assign_by_ref("JobrollPublisherID",$JobrollPublisherID); 
				break;
			case '$BrowseKeywordList': 
				$smarty->assign("BrowseKeywordList",get_browse_keyword_list()); 
				break;
			case '$BrowseKeywordSettings': 
				$smarty->assign("BrowseKeywordSettings",get_browse_keyword_settings()); 
				break;
			case '$BrowseKeywordListNavigation': 
				$smarty->assign_by_ref("BrowseKeywordListNavigation",$BrowseKeywordListNavigation); 
				break;
			case '$BrowseKeywordListMostPopular': 
				$smarty->assign_by_ref("BrowseKeywordListMostPopular",$BrowseKeywordListMostPopular); 
				break;
			case '$JobsInCountry':
				$smarty->assign("JobsInCountry",get_jobs_in_country_info()); 
				break;
			case '$input_value_what': 
				$smarty->assign("input_value_what",get_input_value_what()); 
				break;
			case '$input_value_where': 
				$smarty->assign("input_value_where",get_input_value_where()); 
				break;
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);
}

//Return HTML selectbox (job categories)
function get_jobcategories_selectbox()
{
 global $db_tables;
	//Cache settings
	$sql = "SELECT * FROM ".$db_tables["jobcategories"]." ORDER BY cat_name";
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "homepage_template_primitives",
		"table_name"	=> "jobcategories",
		"params_list"	=> array("selectbox"), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 31*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get values list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("cat_id"=>$myrow["cat_id"], "cat_name"=>$myrow["cat_name"]);
		}
	}

	$tmp = '<select name="jobcategory" id="jobcategory" style="width:125px;">';
	$tmp .= "<option value=\"\">Job Category...</option>";
	for ($i=0; $i<count($data_array); $i++)
	{
		$tmp .= "<option value=\"{$data_array[$i]["cat_id"]}\">{$data_array[$i]["cat_name"]}</option>";
	}
	$tmp .= '</select>';

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

 return $tmp;
}

//Return Jobs categories list
function get_jobcategories_list()
{
 global $db_tables,$text_info;
	//Cache settings
	$sql = "SELECT * FROM ".$db_tables["jobcategories"]." ORDER BY cat_name";
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "homepage_template_values",
		"table_name"	=> "jobcategories",
		"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 31*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get values list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[$myrow["cat_id"]] = array("cat_name"=>$myrow["cat_name"], "cat_key"=>$myrow["cat_key"]);
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);
 return $data_array;
}

//Return Jobs categories list
function get_jobcategories_list_array()
{
 global $db_tables,$text_info;
	//Cache settings
	$sql = "SELECT * FROM ".$db_tables["jobcategories"]." ORDER BY cat_name";
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "homepage_template_values",
		"table_name"	=> "jobcategories",
		"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 31*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get values list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[$myrow["cat_id"]] = array("cat_name"=>$myrow["cat_name"], "cat_key"=>$myrow["cat_key"]);
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);
 return $data_array;
}

function get_sitetitle()
{
 global $SiteTitle,$text_info;
	if (isset($SiteTitle) && ($SiteTitle != "")) return $SiteTitle;
 return $text_info["html_site_title"];
}

function get_sitedescription()
{
 global $SiteDescription,$text_info;
	if (isset($SiteDescription) && ($SiteDescription != "")) return $SiteDescription;
 return $text_info["html_site_description"];
}

function get_sitekeywords()
{
 global $SiteKeywords,$text_info;
	if (isset($SiteKeywords) && ($SiteKeywords != "")) return $SiteKeywords;
 return $text_info["html_site_keywords"];
}

//Get IP address
function getip() {
	if (isset($_SERVER)) $realip = $_SERVER["REMOTE_ADDR"];
	else $realip = getenv('REMOTE_ADDR');
// return '68.67.211.131'; 
 return $realip; 
}

//Get country name by IP
function getcountry($realip)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT c.*,ipc.country_code3 FROM ".$db_tables["iptocountry"]." ipc INNER JOIN ".$db_tables["country"]." c ".
			"WHERE ipc.ip_from<=inet_aton('$realip') and ipc.ip_to>=inet_aton('$realip') and ipc.country_code2=c.country_code2") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return array("id"=>$myrow["cid"], "name"=>$myrow["cname"], "code2"=>$myrow["country_code2"], "code3"=>$myrow["country_code3"]);
	}
 return array("id"=>0, "name"=>"Unknown", "code2"=>"Unknown", "code3"=>"Unknown");
}

function format_sql_datetime($field_name)
{
 global $usersettings;
 return "DATE_FORMAT($field_name,'".$usersettings["datetimeformat"]."')";
}

function get_query_num_count($sql)
{
	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "data_list",
		"params_list"	=> array("row_count"), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 1*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res) > 0) {
			$myrow = mysql_fetch_array($qr_res);
			$data_array["num"] = $myrow["num"];
		}
		else $data_array["num"] = 0;
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);
 return $data_array["num"];
}

function get_page_count($curquery,$row_count)
{
	$num = get_query_num_count($curquery);
	$_SESSION["sess_job_search"]["results_count"] = $num;
 return ceil($num/$row_count); //total page count
}

//Create navigation array
function create_navigation_array()
{
 global $pages_list, $view_pages;
 global $text_info, $SLINE;
	$pages_list = array();
	$next_page_url = "";
	$page = $_SESSION["sess_job_search"]["page_start"] - $view_pages;
	$cnt = 0;
	if ( ($_SESSION["sess_job_search"]["page_start"] != 0) && ($_SESSION["sess_job_search"]["page_count"] > 0) ) $pages_list[] = array("islink"=>1, "url"=>"", "class"=>"testt", "title"=>$text_info["n_prev"]);
	while ($page < $_SESSION["sess_job_search"]["page_count"]) {
		if ($page < 0) { $page++; continue; }
		//Current page
		if ($page == $_SESSION["sess_job_search"]["page_start"]) {
			$pages_list[] = array("islink"=>0, "url"=>"", "class"=>"testt", "title"=>($page+1));
			$next_page_url = "./?".$SLINE."&nextpg=1&start=".($page+1)."&".search_params_url();	//next page url
			$pages_list[0]["url"] = "./?".$SLINE."&nextpg=1&start=".($page-1)."&".search_params_url();	//prev page url
		}
		else $pages_list[] = array("islink"=>1, "url"=>"./?".$SLINE."&nextpg=1&start=".$page."&".search_params_url(), "class"=>"testt", "title"=>($page+1));
		$page++;
		$cnt++;
		if ($cnt > (2*$view_pages)) break;
	}
	if ( ($page != ($_SESSION["sess_job_search"]["page_count"]-1) ) && ($_SESSION["sess_job_search"]["page_count"] > 0) && ($page != ($_SESSION["sess_job_search"]["page_start"]+1)) ) $pages_list[] = array("islink"=>1, "url"=>$next_page_url, "class"=>"testt", "title"=>$text_info["n_next"]);
}

function get_base_site_url()
{
	return (isset($_SESSION["globsettings"]["site_url"])) ? $_SESSION["globsettings"]["site_url"] : "";
}

function adv_check_bot_protection()
{
	if (!isset($_SESSION["sess_visitor"])) { $_SESSION["sess_bot_protection"]["bot"] = true; return; }
	if (!isset($_SESSION["sess_visitor"]["ip"]) || ($_SESSION["sess_visitor"]["ip"] == "")) { $_SESSION["sess_bot_protection"]["bot"] = true; return; }
	$realip = getip();
	$realip = substr($realip,0,14); 
	if ($realip  == "") { $_SESSION["sess_bot_protection"]["bot"] = true; return; }
	if (!isset($_SESSION["sess_bot_protection"]["was_search"]) || !$_SESSION["sess_bot_protection"]["was_search"]) { $_SESSION["sess_bot_protection"]["bot"] = true; return; }
	$_SESSION["sess_bot_protection"]["bot"] = false;
}

function check_redirect_bot_protection($rdval)
{
	if (!isset($_SESSION["sess_bot_protection"]["rdval"])) { $_SESSION["sess_bot_protection"]["bot"] = true; return; }
	if (!isset($_COOKIE["cval"])) { $_SESSION["sess_bot_protection"]["bot"] = true; return; }
	if ($_SESSION["sess_bot_protection"]["rdval"] != Decrypt($rdval)) { $_SESSION["sess_bot_protection"]["bot"] = true; return; }
}

function check_earn_ip_protection_for_this_visitor($uid_adv)
{
 global $db_tables;
	$realip = getip();
	$realip = substr($realip,0,14); 
	$earn_ip_block_hours_period = (isset($_SESSION["globsettings"]["earn_ip_protection"])) ? $_SESSION["globsettings"]["earn_ip_protection"] : 1;
	if ($realip  == "") { $_SESSION["sess_bot_protection"]["bot"] = true; return true; }
	$qr_res = mysql_query("SELECT stat_earned_ip FROM ".$db_tables["stats_earned_ips"]." WHERE IpNum=inet_aton('$realip') and uid_adv='$uid_adv' and actiontime>=DATE_SUB(NOW(),INTERVAL $earn_ip_block_hours_period HOUR) LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) { $_SESSION["sess_bot_protection"]["earnip"] = true; return true; }
 return false;
}

//Check: is it possible to lead this job for advertiser and publisher
function check_possibility_lead_job_insert($job_ads_id,$uid_adv)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT job_ads_id FROM ".$db_tables["data_list_advertiser"]." dla ".
			"INNER JOIN ".$db_tables["job_ads"]." ja ON dla.feed_id=ja.job_ads_id and ja.status=1 ".
			"WHERE dla.data_id='$job_ads_id'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) return false;
	$qr_res = mysql_query("SELECT uid_adv FROM ".$db_tables["users_advertiser"]." WHERE uid_adv='$uid_adv' and isconfirmed=1 and isenable=1 and isdeleted=0") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) return false;
 return true;
}

function reduce_advertiser_balance($uid_adv, $cost)
{
 global $db_tables;
	$cost = (float)$cost;
	$realip = getip();
	$realip = substr($realip,0,14); 
	//Check if possible (для подстраховки)
	mysql_query("UPDATE ".$db_tables["users_advertiser"]." SET balance=balance-{$cost} WHERE uid_adv='$uid_adv'") or query_die(__FILE__,__LINE__,mysql_error());
	mysql_query("INSERT INTO ".$db_tables["stats_earned_ips"]." VALUES(NULL,inet_aton('$realip'),'$uid_adv','$cost',NOW())") or query_die(__FILE__,__LINE__,mysql_error());
	set_not_actual_frontend_table_cache_data("search_result","data_list_advertiser",array(""),"as_array");
	set_not_actual_frontend_table_cache_data("search_result","keyword_ads",array("ads"),"as_array");
}

function add_publisher_balance($uid_pub,$stats_click,$cost)
{
 global $db_tables,$job_channel;
	$cost = (float)$cost;
	mysql_query("INSERT INTO ".$db_tables["stats_pub_earn_clicks"]." VALUES(NULL,'$uid_pub','$job_channel','$stats_click','$cost',NOW())") or query_die(__FILE__,__LINE__,mysql_error());
	mysql_query("UPDATE ".$db_tables["users_publisher"]." SET balance=balance+{$cost} WHERE uid_pub='$uid_pub'") or query_die(__FILE__,__LINE__,mysql_error());
}

function add_special_for_this_proc_data(&$data_array,$arrkey,$addstr)
{
	if (!is_array($data_array)) return;
	foreach($data_array as $k=>$v)
	{
		if (!isset($data_array[$k][$arrkey])) {
			foreach($v as $k1=>$v1)
			{
				$data_array[$k][$k1][$arrkey] = $data_array[$k][$k1][$arrkey].$addstr;
			}
		}
		else $data_array[$k][$arrkey] = $data_array[$k][$arrkey].$addstr;
	}
}

function check_sess_id_values($v,$vname)
{
	if ( ($v == "") && isset($_SESSION["sess_$vname"]) && ($_SESSION["sess_$vname"] != "") ) return $_SESSION["sess_$vname"];
	if ($v != "") { $_SESSION["sess_$vname"] = $v; return $v; }
	return $v;
}

//Check publisher info
function get_publisher_info()
{
 global $jobroll_publisher_id,$job_channel;
	//Get publisher info
	$publisher_id = get_get_value("publisher_id","");
	if (($publisher_id != "") && ($jobroll_publisher_id == "")) $jobroll_publisher_id = $publisher_id;
	else $jobroll_publisher_id = get_get_value("jobroll_publisher_id","");
	$jobroll_publisher_id = check_sess_id_values($jobroll_publisher_id,"jobroll_publisher_id");
	$job_channel = get_get_value("job_channel","");
	$job_channel = check_sess_id_values($job_channel,"job_channel");
}

//Check IPFW using cache
function check_visitor_ipfw_cache()
{
 global $db_tables;
	//Cache settings
	$sql = "SELECT * FROM ".$db_tables["ipfirewall"];
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "ipfirewall",
		"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 7*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("ip"=>$myrow["ip"]);
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

	$realip = getip();
	$realip = substr($realip,0,14); 
	list($ip1,$ip2,$ip3,$ip4) = preg_split("/\./",$realip);

	foreach ($data_array as $k=>$v)
	{
	 	$iplist = explode(".",$v["ip"]);

		if ( ( ($ip1 == $iplist[0]) || ($iplist[0] == '*') ) && ( ($ip2 == $iplist[1]) || ($iplist[1] == '*') ) &&
				( ($ip3 == $iplist[2]) || ($iplist[2] == '*') ) && ( ($ip4 == $iplist[3]) || ($iplist[3] == '*') ) )
 		return false;
	}
 return true;
}

//Get admin emial
function get_admin_email_free()
{
 global $db_tables;
	//Cache settings
	$sql = "SELECT admemail FROM ".$db_tables["admins"];
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "admins",
		"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 15*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("admemail"=>$myrow["admemail"]);
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

 return (isset($data_array[0]["admemail"])) ? $data_array[0]["admemail"] : '';
}

//Get mail subject for current mail
function get_mailsubject($mail)
{
 global $db_tables;
	//Cache settings
	$sql = "SELECT mailsubject FROM ".$db_tables["mailsubject"]." WHERE mailkey='$mail'";
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "mailsubject",
		"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 25*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("mailsubject"=>$myrow["mailsubject"]);
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

 return (isset($data_array[0]["mailsubject"])) ? $data_array[0]["mailsubject"] : '';
}

function get_jobs_in_country_info()
{
 global $jobs_in_country_info;
 return '<img src="'.$_SESSION["globsettings"]["site_url"].'frontend/images/coutries/'.$jobs_in_country_info[$_SESSION["globsettings"]["selected_country"]]["image"].'" style="padding-top:1px;" /><span class="jobs_in">&nbsp;<b>'.$jobs_in_country_info[$_SESSION["globsettings"]["selected_country"]]["message"].'</b> </span><a class="toplinks" href="'.$_SESSION["globsettings"]["site_url"].'change-country/">[ Change Country ]</a><span class="jobs_in"></span>';
}

function get_text_from_input_value_what($job_search_params)
{
	$result = "";
	if (isset($job_search_params["what"]) && ($job_search_params["what"] != "")) $result .= $job_search_params["what"].' ';
	if (isset($job_search_params["as_all"]) && ($job_search_params["as_all"] != "")) $result .= $job_search_params["as_all"].' ';
	if (isset($job_search_params["as_phrase"]) && ($job_search_params["as_phrase"] != "")) $result .= $job_search_params["as_phrase"].' ';
	if (isset($job_search_params["as_any"]) && ($job_search_params["as_any"] != "")) $result .= $job_search_params["as_any"].' ';
	if (isset($job_search_params["as_title"]) && ($job_search_params["as_title"] != "")) $result .= $job_search_params["as_title"].' ';
	if (isset($job_search_params["as_company"]) && ($job_search_params["as_company"] != "")) $result .= $job_search_params["as_company"].' ';
 return $result;
}

function get_input_value_what()
{
 global $job_search_params;
	$result = get_text_from_input_value_what($job_search_params);
	if (($result == "") && isset($_SESSION["sess_job_search"]["job_search_params"]) && is_array($_SESSION["sess_job_search"]["job_search_params"]))
		$result = get_text_from_input_value_what($_SESSION["sess_job_search"]["job_search_params"]);
 return $result;
}

function get_input_value_where()
{
 global $job_search_params;
	$result = (isset($job_search_params["where"])) ? $job_search_params["where"] : "";
	if (($result == "") && isset($_SESSION["sess_job_search"]["job_search_params"]["where"])) $result = $_SESSION["sess_job_search"]["job_search_params"]["where"];
	return $result;
}

function get_allow_within_search()
{
	return ($_SESSION["globsettings"]["allow_cities_in_db"]) ? true : false;
}
?>