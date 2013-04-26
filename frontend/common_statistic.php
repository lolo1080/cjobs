<?php
// * * * * * //
// Statistic //
// * * * * * //

//Insert statistic info: maybe pageview keywords
/*
search_functions.php
  - get_jobs_keyword_ads()
*/
function set_stats_adv_maybe_pageview_keywords()
{
 global $db_tables,$AdsCnt;
	for ($i=0; $i<count($_SESSION["sess_job_search"]["keywordads_list"]); $i++)
	{
		for ($j=0; $j<count($_SESSION["sess_job_search"]["possible_keywords"]); $j++)
		{
			if ($_SESSION["sess_job_search"]["keywordads_list"][$i]["ad_id"] == $_SESSION["sess_job_search"]["possible_keywords"][$j]["ad_id"]) {
				mysql_query("INSERT INTO ".$db_tables["stats_adv_maybe_pageview_keywords"]." VALUES(NULL,".
					"'{$_SESSION["sess_job_search"]["possible_keywords"][$j]["kads_id"]}','{$_SESSION["sess_job_search"]["possible_keywords"][$j]["ad_id"]}',".
					"'{$_SESSION["sess_job_search"]["keywordads_list"][$i]["uid_adv"]}',".
					"'".ceil(($j+1)/$AdsCnt)."',NOW())") or query_die(__FILE__,__LINE__,mysql_error());
			}
		}
	}
}

//Insert statistic info: pageview keywords
/*
search_functions.php
  - create_shownads_list()
*/
function set_stats_adv_pageview_keywords(&$this_page_show_ads_list)
{
 global $db_tables;
	foreach ($this_page_show_ads_list as $this_page_k=>$this_page_v)
	{
		for ($j=0; $j<count($_SESSION["sess_job_search"]["possible_keywords"]); $j++)
		{
			if ($this_page_v["ad_id"] == $_SESSION["sess_job_search"]["possible_keywords"][$j]["ad_id"]) {
				mysql_query("INSERT INTO ".$db_tables["stats_adv_pageview_keywords"]." VALUES(NULL,".
					"'{$_SESSION["sess_job_search"]["possible_keywords"][$j]["kads_id"]}','{$this_page_v["ad_id"]}',".
					"'{$this_page_v["uid_adv"]}',".
					"NOW())") or query_die(__FILE__,__LINE__,mysql_error());
			}
		}
	}
}

//Collect visitor info
/*
index.php, search.php
*/
function collect_visitor_info()
{
 global $db_tables;
	//Check visitor info in session
	if (isset($_SESSION["sess_visitor"])) return;
	//Get IP
	$realip = getip();
	$realip = substr($realip,0,14); 
	//Server info
	$ip_over_proxy = $refer_url = $host = "";
	if (isset($_SERVER)) {
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) $ip_over_proxy = $_SERVER["HTTP_X_FORWARDED_FOR"];
		if (isset($_SERVER["HTTP_REFERER"])) $refer_url = $_SERVER["HTTP_REFERER"];
		if (isset($_SERVER["HTTP_HOST"])) $host = $_SERVER["HTTP_HOST"];
		if (isset($_SERVER["REQUEST_URI"])) $folder = $_SERVER["REQUEST_URI"];
	}
	else {
		if (getenv('HTTP_X_FORWARDED_FOR')) $ip_over_proxy = getenv('HTTP_X_FORWARDED_FOR');
		if (getenv('HTTP_REFERER')) $refer_url = getenv('HTTP_REFERER');
		if (getenv('HTTP_HOST')) $host = getenv('HTTP_HOST');
		if (getenv('REQUEST_URI')) $folder = getenv('REQUEST_URI');
	}
	$request_url = addslashes("http://$host$folder");
	$refer_url = addslashes($refer_url);
	//Add visitor info to session
	$_SESSION["sess_visitor"]["ip"] = $realip;
	//Insert visitor info
	mysql_query("INSERT INTO ".$db_tables["stats_visitor_info"]." VALUES(NULL,NOW(),'$realip','$ip_over_proxy','$refer_url','$request_url')")
		or query_die(__FILE__,__LINE__,mysql_error());
	$_SESSION["sess_visitor"]["stat_vi"] = mysql_insert_id();
	//Set Cookie
	if (isset($_COOKIE["NowVisitTime"]) && ($_COOKIE["NowVisitTime"] != "")) {
		setcookie("LastVisitTime",$_COOKIE["NowVisitTime"],time()+3600*24*60);
	}
	setcookie("NowVisitTime",time(),time()+3600*24*60);
}

//Collect XML visitor info
/*
xmlsearch.php
*/
function collect_xmlvisitor_info()
{
 global $db_tables,$job_search_params;
	//Check visitor info in session
//	if (isset($_SESSION["sess_visitor"])) return;
	//Get IP
	$ip_over_proxy = $refer_url = $host = "";
	$realip = getip();
	$ip_over_proxy = substr($realip,0,14); 
	//Server info
	if (isset($_SERVER)) {
		if (isset($_SERVER["HTTP_REFERER"])) $refer_url = $_SERVER["HTTP_REFERER"];
		if (isset($_SERVER["HTTP_HOST"])) $host = $_SERVER["HTTP_HOST"];
		if (isset($_SERVER["REQUEST_URI"])) $folder = $_SERVER["REQUEST_URI"];
	}
	else {
		if (getenv('HTTP_REFERER')) $refer_url = getenv('HTTP_REFERER');
		if (getenv('HTTP_HOST')) $host = getenv('HTTP_HOST');
		if (getenv('REQUEST_URI')) $folder = getenv('REQUEST_URI');
	}
	$request_url = "http://$host$folder";
	$userip		= html_chars(get_get_post_value("userip",""));		//the IP number from input
	if ($userip != "") $realip = $userip;
	//Add visitor info to session
	$_SESSION["sess_visitor"]["ip"] = $realip;
	//Insert visitor info
	mysql_query("INSERT INTO ".$db_tables["stats_visitor_info"]." VALUES(NULL,NOW(),'$realip','$ip_over_proxy','$refer_url','$request_url')")
		or query_die(__FILE__,__LINE__,mysql_error());
	$_SESSION["sess_visitor"]["stat_vi"] = mysql_insert_id();
}

//Insert statistic info: pageview keywords
/*
search.php
*/
function set_stats_search_keywords(&$job_search_params,$search_type)
{
 global $db_tables,$jobroll_publisher_id;
	$keywords = array();
	get_set_stats_search_keywords($job_search_params,$keywords);
	$_SESSION["sess_job_search"]["stats_search_keywords_ids"] = array();
	$jpi = (isset($jobroll_publisher_id) && ($jobroll_publisher_id != "") && check_int($jobroll_publisher_id)) ? $jobroll_publisher_id : 0;
	for ($i=0; $i<count($keywords); $i++)
	{
		if ($keywords[$i] == "") continue;
		//Insert search keyword info
		mysql_query("INSERT INTO ".$db_tables["stats_search_keywords"]." VALUES(NULL,'{$keywords[$i]}','{$_SESSION["sess_visitor"]["stat_vi"]}',".
			"NOW(),'$search_type','$jpi')") or query_die(__FILE__,__LINE__,mysql_error());
		$_SESSION["sess_job_search"]["stats_search_keywords_ids"][] = mysql_insert_id();
	}
}
function get_set_stats_search_keywords(&$job_search_params,&$keywords)
{
	if ($job_search_params["search_type"] == "simple") {
		$keywords[] = $job_search_params["what"];
		$keywords[] = $job_search_params["where"];
		$keywords[] = $job_search_params["title"];
		$keywords[] = $job_search_params["company_name"];
	}
	elseif ($job_search_params["search_type"] == "advanced") {
		$keywords[] = $job_search_params["what"];
		$keywords[] = $job_search_params["where"];
		$keywords[] = $job_search_params["title"];
		$keywords[] = $job_search_params["company_name"];
		$keywords[] = $job_search_params["as_all"];
		$keywords[] = $job_search_params["as_phrase"];
		$keywords[] = $job_search_params["as_title"];
		$keywords[] = $job_search_params["as_company"];
		$keywords[] = $job_search_params["as_any"];
	}
}

//Insert statistic info: success search keywords (keyworsd with search result)
/*
search.php
*/
function set_stats_search_success_keywords(&$job_search_params)
{
 global $db_tables;
	$keywords = array();
	if (!isset($_SESSION["sess_job_search"]["stats_search_success_keywords"])) $_SESSION["sess_job_search"]["stats_search_success_keywords"] = array();
	get_set_stats_search_keywords($job_search_params,$keywords);
	for ($i=0; $i<count($keywords); $i++)
	{
		if ($keywords[$i] == "") continue;
		if (in_array($keywords[$i],$_SESSION["sess_job_search"]["stats_search_success_keywords"])) continue;
		//Insert search keyword
		mysql_query("INSERT INTO ".$db_tables["stats_search_success_keywords"]." VALUES(NULL,'{$keywords[$i]}')") or query_die(__FILE__,__LINE__,mysql_error());
		$_SESSION["sess_job_search"]["stats_search_success_keywords"][] = $keywords[$i];
	}
}

//Insert statistic info: pageview jobs
/*
search_functions.php
  - get_advertiser_jobs_list_ads($p)
*/
function set_stats_adv_pageview_jobs(&$adv_pageview_jobs)
{
 global $db_tables;
	foreach ($adv_pageview_jobs as $k=>$v)
	{
		mysql_query("INSERT INTO ".$db_tables["stats_adv_pageview_jobs"]." VALUES(NULL,".
			"'{$v["feed_id"]}','{$v["uid_adv"]}',NOW())") or query_die(__FILE__,__LINE__,mysql_error());
	}
}

//Insert statistic info: maybe pageview jobs
/*
search_functions.php
  - exec_sqljob_search($sql,$order_sql,$from_count,$row_count,&$data_array,&$adv_data_array)
*/
function set_stats_adv_maybe_pageview_jobs($adv_mainsql)
{
 global $db_tables,$JobsAdsTopCnt,$JobsAdsBottomCnt;
	$adv_mainsql = preg_replace("~/\*jobs_ads_limit_start\*/(.*?)/\*jobs_ads_limit_end\*/~"," LIMIT 100",$adv_mainsql);

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "data_list_advertiser",
		"params_list"	=> array(""), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $adv_mainsql,
		"actual_time"	=> 4*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$adv_data_array = array();
	if (!read_mydata_cache($cache_params_array,$adv_data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$adv_data_array[$myrow["data_id"]] = array("feed_id"=>$myrow["feed_id"], "title"=>$myrow["title"], "company_name"=>$myrow["company_name"],
				"description"=>$myrow["description"], "clickurl"=>"../job-details/?data_id=".$myrow["data_id"], "url"=>$myrow["url"], "salary"=>$myrow["salary"],
				"registered_sec"=>$myrow["registered_sec"], "myregtime"=>$myrow["myregtime"], "region"=>$myrow["region"],
				"city"=>$myrow["city"], "postalCode"=>$myrow["postalCode"],
				"max_cpc"=>$myrow["max_cpc"], "uid_adv"=>$myrow["uid_adv"], "destination_url"=>$myrow["destination_url"],
				"job_ads_id"=>$myrow["job_ads_id"]);
		}
	}
	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$adv_data_array);

	$j = 0;
	foreach ($adv_data_array as $k=>$v)
	{
		mysql_query("INSERT INTO ".$db_tables["stats_adv_maybe_pageview_jobs"]." VALUES(NULL,".
			"'{$v["job_ads_id"]}','{$v["uid_adv"]}','".ceil( ($j+1)/($JobsAdsTopCnt+$JobsAdsBottomCnt) )."',NOW())") or query_die(__FILE__,__LINE__,mysql_error());
		$j++;
	}
}

//Insert statistic info: publisher pageview
/*
adsshowjobs.php
  - exec_sqljob_search($sql,$order_sql,$from_count,$row_count,&$data_array,&$adv_data_array)
*/
function set_stats_pub_pageview($jobroll_publisher_id,$job_channel)
{
 global $db_tables;
	if ($jobroll_publisher_id == "") return;

	$sql = "SELECT uid_pub FROM ".$db_tables["users_publisher"]." ".
				"WHERE uid_pub='$jobroll_publisher_id' and isdeleted=0 and isconfirmed=1 and isenable=1";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "users_publisher",
		"params_list"	=> array(""), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 14*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$pub_data_array = array();
	if (!read_mydata_cache($cache_params_array,$pub_data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$pub_data_array[$myrow["uid_pub"]] = array(1);
		}
	}
	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$pub_data_array);

	if (count($pub_data_array) == 0) return;

	mysql_query("INSERT INTO ".$db_tables["stats_pub_pageview"]." VALUES(NULL,".
			"'$jobroll_publisher_id','$job_channel',NOW())") or query_die(__FILE__,__LINE__,mysql_error());
}

//Create session values for keyword statistic and bot protection
function 	set_sess_for_stats_keyword(&$keywordads_array,&$positive_keywords_array)
{
	for ($i=0; $i<count($keywordads_array); $i++)
	{
		$_SESSION["sess_job_search"]["stats_keywordads_list"][$keywordads_array[$i]["ad_id"]] = get_kads_id_list($positive_keywords_array,$keywordads_array[$i]["ad_id"]);
	}
}

//Insert statistic info: visitor click on job to see it
/*
job_details.php
  - 
*/
function set_stats_clicks(&$job_data,$click_type)
{
 global $db_tables,$jobroll_publisher_id;
	//Check bot protection for this advertiser (do not allow fro one visitor do 2 clicks for one advertiser, but one visitor can do clicks to many different advertisers)
	function check_bot_protection_for_this_advertiser(&$job_data)
	{
		if ($_SESSION["sess_bot_protection"]["bot"]) return true;
		if (!isset($_SESSION["sess_bot_protection"]["uid_adv"])) $_SESSION["sess_bot_protection"]["uid_adv"] = array();
		return false;
	}

	function check_bot_protection_for_this_advertiser1(&$job_data)
	{
		if (check_bot_protection_for_this_advertiser($job_data)) return true;
		if (in_array($_SESSION["sess_job_search"]["adv_job_list"][$job_data[0]["data_id"]]["uid_adv"],$_SESSION["sess_bot_protection"]["uid_adv"])) return true;
		array_push($_SESSION["sess_bot_protection"]["uid_adv"],$_SESSION["sess_job_search"]["adv_job_list"][$job_data[0]["data_id"]]["uid_adv"]);
		return false;
	}

	function get_uid_adv_by_ad_id($job_data_0)
	{
		foreach($_SESSION["sess_job_search"]["keywordads_list"] as $k=>$v)
		{
			if ($v["ad_id"] == $job_data_0["data_id"]) return $v["uid_adv"];
		}
		return -1;
	}
	function check_bot_protection_for_this_advertiser2(&$job_data)
	{
		if (check_bot_protection_for_this_advertiser($job_data)) return true;
		$uid_adv_by_ad_id = get_uid_adv_by_ad_id($job_data[0]);
		if ($uid_adv_by_ad_id < 0) return true;
		if (in_array($uid_adv_by_ad_id,$_SESSION["sess_bot_protection"]["uid_adv"])) return true;
		if (!isset($_SESSION["sess_bot_protection"]["uid_adv"])) $_SESSION["sess_bot_protection"]["uid_adv"] = array();
		array_push($_SESSION["sess_bot_protection"]["uid_adv"],$_SESSION["sess_job_search"]["keywordads_list"][$job_data[0]["data_id"]]["uid_adv"]);
		return false;
	}

	if (!isset($_SESSION["sess_job_search"]["stats_search_keywords_ids"])) $_SESSION["sess_job_search"]["stats_search_keywords_ids"] = get_get_post_value2("ssk",array(0));
	for ($i=0; $i<count($_SESSION["sess_job_search"]["stats_search_keywords_ids"]); $i++)
	{
		$sskid = data_addslashes($_SESSION["sess_job_search"]["stats_search_keywords_ids"][$i]);
		mysql_query("INSERT INTO ".$db_tables["stats_clicks"]." VALUES(NULL,".
			"'{$job_data[0]["data_id"]}','$click_type','$sskid',NOW())") or query_die(__FILE__,__LINE__,mysql_error());
		//Set statistic for publisher: click
		$stats_click = mysql_insert_id();
		if ($jobroll_publisher_id != "") set_stats_pub_click_keywords($jobroll_publisher_id,$stats_click);
	}
	if ( ($click_type == 1) && isset($_SESSION["sess_job_search"]["adv_job_list"][$job_data[0]["data_id"]]) ) {
		//Check bot protection values
		if (check_bot_protection_for_this_advertiser1($job_data)) return;
		if (check_earn_ip_protection_for_this_visitor($_SESSION["sess_job_search"]["adv_job_list"][$job_data[0]["data_id"]]["uid_adv"])) return;
		if (!check_possibility_lead_job_insert($job_data[0]["data_id"],$_SESSION["sess_job_search"]["adv_job_list"][$job_data[0]["data_id"]]["uid_adv"])) return;
		mysql_query("INSERT INTO ".$db_tables["stats_adv_click_jobs"]." VALUES(NULL,".
			"'{$job_data[0]["data_id"]}','{$_SESSION["sess_job_search"]["adv_job_list"][$job_data[0]["data_id"]]["uid_adv"]}',".
			"'{$_SESSION["sess_job_search"]["adv_job_list"][$job_data[0]["data_id"]]["max_cpc"]}',NOW())") or query_die(__FILE__,__LINE__,mysql_error());
		//Remove front-end cache
		set_not_actual_frontend_table_cache_data("search_result","data_list_advertiser",array(""),"as_array");
		reduce_advertiser_balance($_SESSION["sess_job_search"]["adv_job_list"][$job_data[0]["data_id"]]["uid_adv"],$_SESSION["sess_job_search"]["adv_job_list"][$job_data[0]["data_id"]]["max_cpc"]);
		if ($jobroll_publisher_id != "") add_publisher_balance($jobroll_publisher_id,$stats_click,round($_SESSION["sess_job_search"]["adv_job_list"][$job_data[0]["data_id"]]["max_cpc"]*$_SESSION["globsettings"]["pub_referal_percent"]/100,2));
	}

	if ( ($click_type == 2) && isset($_SESSION["sess_job_search"]["stats_keywordads_list_page"]) && isset($_SESSION["sess_job_search"]["stats_keywordads_list"][$job_data[0]["data_id"]]) ) {
		//Check bot protection values
		if (check_bot_protection_for_this_advertiser2($job_data)) return;
		//Foreach all sponsored links
		for ($i=0; $i<count($_SESSION["sess_job_search"]["keywordads_list"]); $i++)
		{
			//Find this job
			if ($_SESSION["sess_job_search"]["stats_keywordads_list_page"][$i]["ad_id"] == $job_data[0]["data_id"]) {
				//Foreach all keywords related to this sponsored link
				$uid_adv = $max_cpc = "";
				for ($i=0; $i<count($_SESSION["sess_job_search"]["stats_keywordads_list"][$job_data[0]["data_id"]]); $i++)
				{
					if ($_SESSION["sess_job_search"]["stats_keywordads_list"][$job_data[0]["data_id"]] == "") continue;
					if (check_earn_ip_protection_for_this_visitor($_SESSION["sess_job_search"]["stats_keywordads_list_page"][$i]["uid_adv"])) continue;
					mysql_query("INSERT INTO ".$db_tables["stats_adv_click_keywords"]." VALUES(NULL,".
						"'{$_SESSION["sess_job_search"]["stats_keywordads_list"][$job_data[0]["data_id"]][$i]}','{$job_data[0]["data_id"]}',".
						"'{$_SESSION["sess_job_search"]["stats_keywordads_list_page"][$i]["uid_adv"]}',".
						"'{$_SESSION["sess_job_search"]["stats_keywordads_list_page"][$i]["max_cpc"]}',NOW())") or query_die(__FILE__,__LINE__,mysql_error());
					$uid_adv = $_SESSION["sess_job_search"]["stats_keywordads_list_page"][$i]["uid_adv"];
					$max_cpc = $_SESSION["sess_job_search"]["stats_keywordads_list_page"][$i]["max_cpc"];
				}
				//Remove front-end cache
				set_not_actual_frontend_table_cache_data("search_result","keyword_ads",array("ads"),"as_array");
				if ($uid_adv != "")	reduce_advertiser_balance($uid_adv,$max_cpc);
				if (($uid_adv != "") && ($jobroll_publisher_id != "")) add_publisher_balance($jobroll_publisher_id,$stats_click,round($max_cpc*$_SESSION["globsettings"]["pub_referal_percent"]/100,2));
				break;
			}
		}
	}
}

//Insert statistic info: visitor click on job to see it (this visitor was from webmaster site)
/*
job_details.php
  - 
*/
function set_stats_pub_click_keywords($uid_pub,$stat_click)
{
 global $db_tables,$job_channel;
	if (!check_int($uid_pub)) return;
	mysql_query("INSERT INTO ".$db_tables["stats_pub_click_keywords"]." VALUES(NULL,".
			"'$uid_pub','$job_channel','$stat_click',NOW())") or query_die(__FILE__,__LINE__,mysql_error());
}
?>