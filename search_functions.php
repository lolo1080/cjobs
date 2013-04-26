<?
//Set sponsore ADs count
$AdsCnt = (isset($_SESSION["globsettings"]["amount_of_adv_keyword_ads"])) ? ($_SESSION["globsettings"]["amount_of_adv_keyword_ads"] - 1) : 3;
$JobsAdsTopCnt = (isset($_SESSION["globsettings"]["amount_of_adv_sponsor_jobs_top"])) ? $_SESSION["globsettings"]["amount_of_adv_sponsor_jobs_top"] : 2;
$JobsAdsBottomCnt = (isset($_SESSION["globsettings"]["amount_of_adv_sponsor_jobs_bottom"])) ? $_SESSION["globsettings"]["amount_of_adv_sponsor_jobs_bottom"] : 2;
$donotslash_where = false;

function get_job_search_params()
{
 global $number_results_default;
	$job_search_params["error_code"] = "none";
	$job_search_params["search_type"] = "simple";
	//Get search params (Simple search values)
	$job_search_params["what"]					= html_chars(get_get_post_value("what",""));				//what - search keyword
	//Get search params (Advenced search values)
	$job_search_params["as_all"]				= html_chars(get_get_post_value("as_all",""));			//with all of these words
	$job_search_params["as_phrase"]			= html_chars(get_get_post_value("as_phrase",""));		//with the exact phrase
	$job_search_params["as_any"]				= html_chars(get_get_post_value("as_any",""));			//with at least one of these words
	$job_search_params["as_not"]				= html_chars(get_get_post_value("as_not",""));			//without the words
	$job_search_params["as_title"]			= html_chars(get_get_post_value("as_title",""));		//with these words in the title
	$job_search_params["as_company"]		= html_chars(get_get_post_value("as_company",""));	//from this company
	$job_search_params["radius"]				= html_chars(get_get_post_value("radius",0));				//within
	$job_search_params["jobs_category"]	= html_chars(get_get_post_value("jobs_category",0));//related to category
	if (!check_int($job_search_params["jobs_category"])) $job_search_params["jobs_category"]	= category_key_to_number($job_search_params["jobs_category"]); //convert category key to category number
	$job_search_params["jobs_type"]			= html_chars(get_get_post_value("jobs_type",""));		//show jobs of type
	$job_search_params["jobs_from"]			= html_chars(get_get_post_value("jobs_from",""));		//show jobs from
	$job_search_params["norecruiters"]	= html_chars(get_get_post_value("norecruiters",0));	//exclude staffing agencies
  $job_search_params["salary"]				= html_chars(get_get_post_value("salary",""));			//salary estimate
	$job_search_params["jobs_published"]= html_chars(get_get_post_value("jobs_published",""));//jobs published (anytime, within 30 days, ..., since my last visit)
	//Get search params (Common search values)
	$job_search_params["where"]					= html_chars(get_get_post_value("where",""));					//where (location): city, state, or zip
	$job_search_params["number_results"]= html_chars(get_get_post_value("number_results",$number_results_default));//number results per page
//!!!
//$job_search_params["number_results"] = 2;
//!!!
	$job_search_params["sort_by"]				= html_chars(get_get_post_value("sort_by",""));				//sort jobs by (relevance, date). Empty means - relevance

	if (($job_search_params["as_all"] != "") || ($job_search_params["as_phrase"] != "") || ($job_search_params["as_any"] != "") || 
			($job_search_params["as_not"] != "") || ($job_search_params["as_title"] != "") || ($job_search_params["as_company"] != ""))
		$job_search_params["search_type"] = "advanced";

	//+ Locations search values and jobroll (job_country)
	$job_search_params["title"] = $job_search_params["company_name"] = $job_search_params["job_country"] = "";

	//Check search type value
	$search_type = html_chars(get_get_post_value("search_type",""));
	if (in_array($search_type,array("simple","advanced"))) $job_search_params["search_type"] = $search_type;

	//Check search values from JobRoll(one text field) and  Job Search Boxs(one text field) and set correct seach params -->
	$job_search_params["what_where"]		= html_chars(get_get_value("what_where",""));	//check one search field: what_where
	if (($job_search_params["what_where"] != "") && ($job_search_params["what"] == "") && ($job_search_params["where"] == ""))
		parse_what_where_params($job_search_params);

	//Check search values from jobroll and correct (if need) -->
	$job_search_params["search_from"]		= html_chars(get_get_value("search_from",""));	//check search from jobroll
	if ($job_search_params["search_from"] == "jobroll") {
		$job_search_params["where"] = "";
		$job_search_params["job_where"]				= html_chars(get_get_value("job_where",""));		//get search params
		$job_search_params["job_city_state"]	= html_chars(get_get_value("job_city_state",""));		//get search params
		switch($job_search_params["job_where"]) {
			case "job_where_viewers_location": $job_search_params["job_where_locations"] = get_viewers_location(); break;
			case "job_where_set_location": $job_search_params["where"] = $job_search_params["job_city_state"];	break; //$job_where - radio buttons;	//where (location): city, state, or zip
		}
		$job_search_params["job_country"]	= html_chars(get_get_value("job_country",""));		//job country (from jobroll)
	}
	// <--
 return $job_search_params;
}

function check_job_search_params(&$job_search_params)
{
	switch ($job_search_params["search_type"]) {
		case "simple": check_simple_job_search($job_search_params); break;
		case "advanced": check_advanced_job_search($job_search_params); break;
		default: critical_error(__FILE__,__LINE__,"Invalid job type.");
	}
}

function check_simple_job_search(&$job_search_params)
{
	if ( ($job_search_params["what"] == "") && ($job_search_params["where"] == "") && (($job_search_params["jobs_category"] == "") ||  ($job_search_params["jobs_category"] == "0")) ) {
		$job_search_params["error_code"] = "empty_keyword";
		return;
	}
	check_common_job_values($job_search_params);
}

function check_advanced_job_search(&$job_search_params)
{
 global $radius_array,$radius_array_default,$jobs_type_array,$jobs_type_array_default,$jobs_from_array,$jobs_from_array_default,
				$jobs_published_array,$jobs_published_default;
	if (($job_search_params["as_all"] == "") && ($job_search_params["as_phrase"] == "") && ($job_search_params["as_any"] == "") && 
			($job_search_params["as_not"] == "") && ($job_search_params["as_title"] == "") && ($job_search_params["as_company"] == "") &&
			($job_search_params["where"] == "") && (($job_search_params["jobs_category"] == "") || ($job_search_params["jobs_category"] == 0))) {
		$job_search_params["error_code"] = "empty_keyword";
		return;
	}
	if (($job_search_params["radius"] == "") || !check_int($job_search_params["radius"]) || !isset($radius_array[$job_search_params["radius"]])) $job_search_params["radius"] = $radius_array_default;
	if (($job_search_params["jobs_category"] == "") || !check_int($job_search_params["jobs_category"])) $job_search_params["jobs_category"] = 0;
	if (($job_search_params["jobs_type"] == "") || !isset($jobs_type_array[$job_search_params["jobs_type"]])) $job_search_params["jobs_type"] = $jobs_type_array_default;
	if (($job_search_params["jobs_from"] == "") || !isset($jobs_from_array[$job_search_params["jobs_from"]])) $job_search_params["jobs_from"] = $jobs_from_array_default;
	if ($job_search_params["norecruiters"] != "1") $job_search_params["norecruiters"] = 0;
	if ($job_search_params["salary"] != "") {
		$job_search_params["salary"] = str_replace(",", "", $job_search_params["salary"]);
		$job_search_params["salary"] = str_replace(".", "", $job_search_params["salary"]);
		$job_search_params["salary"] = str_replace(" ", "", $job_search_params["salary"]);
		$job_search_params["salary"] = str_replace("$", "", $job_search_params["salary"]);
		if ($c = preg_match("~(\d+)([kK])*-(\d+)([kK])*~si", $job_search_params["salary"], $matches)) {
			if (isset($matches[1]) && isset($matches[3])) {
				if ( isset($matches[2]) && (strlen($matches[2]) > 0) ) $matches[1] *= 1000;
				if ( isset($matches[4]) && (strlen($matches[4]) > 0) ) $matches[3] *= 1000;
				if ($matches[1] == $matches[3]) { $job_search_params["salary_from"] = 0; $job_search_params["salary_to"] = $matches[1]; }
				elseif ($matches[3] > $matches[1]) { $job_search_params["salary_from"] = $matches[1]; $job_search_params["salary_to"] = $matches[3]; }
				elseif ($matches[1] > $matches[3]) { $job_search_params["salary_from"] = $matches[3]; $job_search_params["salary_to"] = $matches[1]; }
			}
		}
		elseif ($c = preg_match("~(\d+)([kK])*~si", $job_search_params["salary"], $matches)) {
			$job_search_params["salary_from"] = 0; $job_search_params["salary_to"] = $matches[1];
			if (isset($matches[2])) $job_search_params["salary_to"] *= 1000;
		}
	}
	else $job_search_params["salary"] = "";
	if (($job_search_params["jobs_published"] == "") || !isset($jobs_published_array[$job_search_params["jobs_published"]])) $job_search_params["jobs_published"] = $jobs_published_default;
	check_common_job_values($job_search_params);
}

function check_job_xmlsearch_params(&$job_search_params)
{
 global $radius_array,$radius_array_default,$jobs_type_array,$jobs_type_array_default,$jobs_from_array,$jobs_from_array_default,
				$jobs_published_array,$jobs_published_default;
	if (!check_int($job_search_params["start"]) || ($job_search_params["start"] < 0)) $job_search_params["start"] = 0;
	if (!in_array($job_search_params["highlight"], array(0,1))) $job_search_params["highlight"] = 0;
	if (!in_array($job_search_params["latlong"], array(0,1))) $job_search_params["latlong"] = 0;
}

function check_common_job_values(&$job_search_params)
{
 global $number_results_array,$number_results_default;
	if (($job_search_params["number_results"] == "") || !check_int($job_search_params["number_results"]) || !isset($number_results_array[$job_search_params["number_results"]])) $job_search_params["number_results"] = $number_results_default;
	if ($job_search_params["sort_by"] != "date") $job_search_params["sort_by"] = $job_search_params["sort_by_mode"] = "";
	else {
		$job_search_params["sort_by"] = "registered"; //!**
		$job_search_params["sort_by_mode"] = "DESC";
	}
}

function category_key_to_number($jobs_category)
{
 global $db_tables;
	$sql = "SELECT cat_id FROM ".$db_tables["jobcategories"]." WHERE cat_key='$jobs_category' LIMIT 1";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "jobcategories",
		"params_list"	=> array("cat_id"), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 48*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("cat_id"=>$myrow["cat_id"]);
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

 return (isset($data_array[0]["cat_id"])) ? $data_array[0]["cat_id"] : 0;
}

function slash_job_values(&$job_search_params)
{
 global $donotslash_where;
	$job_search_params["what"]					= data_addslashes($job_search_params["what"]);
	$job_search_params["as_all"]				= data_addslashes($job_search_params["as_all"]);
	$job_search_params["as_phrase"]			= data_addslashes($job_search_params["as_phrase"]);
	$job_search_params["as_any"]				= data_addslashes($job_search_params["as_any"]);
	$job_search_params["as_not"]				= data_addslashes($job_search_params["as_not"]);
	$job_search_params["as_title"]			= data_addslashes($job_search_params["as_title"]);
	$job_search_params["as_company"]		= data_addslashes($job_search_params["as_company"]);
	$job_search_params["radius"]				= data_addslashes($job_search_params["radius"]);
	$job_search_params["jobs_type"]			= data_addslashes($job_search_params["jobs_type"]);
	$job_search_params["jobs_category"]	= data_addslashes($job_search_params["jobs_category"]);
	$job_search_params["jobs_from"]			= data_addslashes($job_search_params["jobs_from"]);
	$job_search_params["norecruiters"]	= data_addslashes($job_search_params["norecruiters"]);
  $job_search_params["salary"]				= data_addslashes($job_search_params["salary"]);
	$job_search_params["jobs_published"]= data_addslashes($job_search_params["jobs_published"]);
	if (!$donotslash_where)	$job_search_params["where"] = data_addslashes($job_search_params["where"]);
	$job_search_params["number_results"]= data_addslashes($job_search_params["number_results"]);
	$job_search_params["sort_by"]				= data_addslashes($job_search_params["sort_by"]);
	$job_search_params["job_country"]		= (isset($job_search_params["job_country"])) ? data_addslashes($job_search_params["job_country"]) : "";
}

//Return keywords list as array
function search_keywords_as_array($str_line)
{
	$search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $str_line);
	$search = preg_replace("/ +/", " ", $search);
 return explode(" ",$search);
}

//Return state (2 symbols), if find
function check_get_state($name, &$st)
{
 global $usa_states, $canada_provinces;
	if (isset($usa_states[strtoupper($name)]) || isset($canada_provinces[strtoupper($name)])) { $st = $name; return 1; }
	$trans = array_flip($usa_states);
	if (isset($trans[strtoupper($name)])) { $st = $trans[strtoupper($name)]; return 1; }
	$trans = array_flip($canada_provinces);
	if (isset($trans[strtoupper($name)])) { $st = $trans[strtoupper($name)]; return 1; }
 return 0;
}

//Maybe it was long state name - convert to short state name
function maybe_state_name($long_state,$dev="or")
{
 global $usa_states,$canada_provinces;
	$usa_states_n = array_flip($usa_states);
	$canada_provinces_n = array_flip($canada_provinces);
	if (is_array($long_state)) $long_state = implode(' ',$long_state);
	$long_state = strtoupper($long_state);
	if (isset($usa_states_n[$long_state])) return " $dev c.region='".$usa_states_n[$long_state]."'";
	if (isset($canada_provinces_n[$long_state])) return " $dev c.region='".$canada_provinces_n[$long_state]."'";
}

//Parse what_where field - try to split field by "what" and "where"
function parse_what_where_params(&$job_search_params)
{
	$ww_array = search_keywords_as_array($job_search_params["what_where"]);
	$cnt = count($ww_array);
	if ($cnt == 0) return;
	while ($cnt >=0 )
	{
		$city_sql = $city_sql_part = "";
		for ($i=0; $i<$cnt; $i++)
		{
			$city_sql_part .= $ww_array[$i].' ';
		}
		if (strlen($city_sql_part) > 0) {
			$city_sql_part = substr($city_sql_part, 0, -1);
			$city_sql = "c.city='$city_sql_part' or c.region='$city_sql_part' or c.postalCode='$city_sql_part'".maybe_state_name($city_sql_part);
		}
		if (find_city($city_sql)) {
			for ($i=0; $i<$cnt; $i++)
			{
				$job_search_params["where"] .= $ww_array[$i].' ';
			}
			if (strlen($job_search_params["where"]) > 0) $job_search_params["where"] = substr($job_search_params["where"], 0, -1);
			for ($i=$cnt; $i<count($ww_array); $i++)
			{
				$job_search_params["what"] .= $ww_array[$i].' ';
			}
			if (strlen($job_search_params["what"]) > 0) $job_search_params["what"] = substr($job_search_params["what"], 0, -1);
			return;
		}
		$cnt--;
	}
	$job_search_params["what"] = $job_search_params["what_where"];
}

//Return cities ID list
function find_city($city_sql)
{
 global $db_tables;
	if ($city_sql == "") return 0;
	$sql = "SELECT locId FROM ".$db_tables["city"]." c WHERE ".$city_sql." LIMIT 1";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "city",
		"params_list"	=> array(""), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 41*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("locId"=>$myrow["locId"]);
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

 return (isset($data_array[0]["locId"])) ? 1 : 0;
}

function where_as_array($where)
{
	$where_tmp = search_keywords_as_array($where);
	$where = array();
	//Delete blank parts
	for ($i=0; $i<count($where_tmp); $i++)
	{
		if ($where_tmp[$i] == "") continue;
		$where[] = $where_tmp[$i];
	}
	return $where;
}

//Return cities query
function city_search_sql($where)
{
	$where = where_as_array($where);
	//Create SQL code
	$sql = "";
	if (count($where) == 0) return "";
	if (count($where) == 1) {
		$city_sql = array(0=>'',1=>'');
		if ($_SESSION["globsettings"]["allow_cities_in_db"]) {
			$city_sql[0] = "(c.city='{$where[0]}' or c.region='{$where[0]}' or c.postalCode='{$where[0]}'".maybe_state_name($where[0]).") ";
		}
		if ($_SESSION["globsettings"]["allow_cities_not_in_db"]) {
			$city_sql[1] = "(d.city='{$where[0]}' or d.region='{$where[0]}'".str_replace('c.region','d.region',maybe_state_name($where[0])).") ";
		}
		if (($city_sql[0] != '') && ($city_sql[1] != '')) return "({$city_sql[0]} or {$city_sql[1]}) and ";
		if ($city_sql[0] != '') return $city_sql[0]. " and ";
		if ($city_sql[1] != '') return $city_sql[1]. " and ";
	}
	else {
		if ($_SESSION["globsettings"]["allow_cities_in_db"]) {
			if (check_int($where[count($where)-1]))	{  //zip code?
				$sql .= "c.postalCode='{$where[count($where)-1]}' and "; array_pop($where);
			}
			$st = "";
			if (check_get_state($where[count($where)-1],$st)) {  //state?
				$sql .= "c.region='{$st}' and "; array_pop($where);
			}
			/*
			$sql .= "c.city='".implode(" ",$where)."'".maybe_state_name($where)." and ";
			*/
			$sql_state = "";
			$sql_state = maybe_state_name($where);
			if (strlen($sql_state) > 1) $sql .= "((c.city='".implode(" ",$where)."')".maybe_state_name($where).") and ";
			else $sql .= "c.city='".implode(" ",$where)."' and ";
		}
		if ($_SESSION["globsettings"]["allow_cities_not_in_db"]) {
			$sql1 = "";
			if (check_int($where[count($where)-1]))	{  //zip code?
				array_pop($where);
			}
			$st = "";
			if (check_get_state($where[count($where)-1],$st)) {  //state?
				$sql1 .= "d.region='{$st}' and "; array_pop($where);
			}
			/*
			$sql .= "c.city='".implode(" ",$where)."'".maybe_state_name($where)." and ";
			*/
			$sql_state = "";
			$sql_state = str_replace('c.region','d.region',maybe_state_name($where));
			if (strlen($sql_state) > 1) $sql1 .= "((d.city='".implode(" ",$where)."')".str_replace('c.region','d.region',maybe_state_name($where)).") and ";
			else $sql1 .= "d.city='".implode(" ",$where)."' and ";
		}
		if (($sql != '') && ($sql1 != '')) {
			$sql = substr($sql, 0, -4);
			$sql1 = substr($sql1, 0, -4);
			$sql = "({$sql}) or ({$sql1}) and ";
		}
		elseif ($sql1 != '') $sql = $sql1;
	}
 return $sql;
}

//Return cities query in db (like city_search_sql($where), but for allow_cities_in_db)
function city_search_sql_in_db($where)
{
	$where = where_as_array($where);
	//Create SQL code
	$sql = "";
	if (count($where) == 0) return "";
	if (count($where) == 1) return "(c.city='{$where[0]}' or c.region='{$where[0]}' or c.postalCode='{$where[0]}'".maybe_state_name($where[0]).") and ";
	else {
		if (check_int($where[count($where)-1]))	{  //zip code?
			$sql .= "c.postalCode='{$where[count($where)-1]}' and "; array_pop($where);
		}
		$st = "";
		if (check_get_state($where[count($where)-1],$st)) {  //state?
			$sql .= "c.region='{$st}' and "; array_pop($where);
		}
/*
		$sql .= "c.city='".implode(" ",$where)."'".maybe_state_name($where)." and ";
*/
		$sql_state = "";
		$sql_state = maybe_state_name($where);
		if (strlen($sql_state) > 1) $sql .= "((c.city='".implode(" ",$where)."')".maybe_state_name($where).") and ";
		else $sql .= "c.city='".implode(" ",$where)."' and ";
	}
 return $sql;
}


//Return cities query not in db (like city_search_sql($where), but for allow_cities_not_in_db)
function city_search_sql_not_in_db($where)
{
	$where = where_as_array($where);
	//Create SQL code
	$sql = "";
	if (count($where) == 0) return "";
	if (count($where) == 1) {
		return "(d.city='{$where[0]}' or d.region='{$where[0]}'".str_replace('c.region','d.region',maybe_state_name($where[0])).") and ";
	}
	else {
		if (check_int($where[count($where)-1]))	{  //zip code?
			array_pop($where);
		}
		$st = "";
		if (check_get_state($where[count($where)-1],$st)) {  //state?
			$sql .= "d.region='{$st}' and "; array_pop($where);
		}
		/*
		$sql .= "c.city='".implode(" ",$where)."'".maybe_state_name($where)." and ";
		*/
		$sql_state = "";
		$sql_state = str_replace('c.region','d.region',maybe_state_name($where));
		if (strlen($sql_state) > 1) $sql .= "((d.city='".implode(" ",$where)."')".str_replace('c.region','d.region',maybe_state_name($where)).") and ";
		else $sql .= "d.city='".implode(" ",$where)."' and ";
	}
 return $sql;
}

function sign($val)
{
 return ($val >= 0) ? 1 : -1;
}

//Return states list
function get_full_states_list()
{
 global $db_tables;
	$sql = "SELECT * FROM ".$db_tables["state"];

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "state",
		"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 48*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("state_id"=>$myrow["state_id"], "state_name"=>strtoupper($myrow["state_name"]), 
					"latitude"=>$myrow["latitude"], "longitude"=>$myrow["longitude"],
					"latitude1"=>$myrow["latitude1"], "longitude1"=>$myrow["longitude1"],
					"latitude2"=>$myrow["latitude2"], "longitude2"=>$myrow["longitude2"]
			);
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

 return $data_array;
}

//Return cities ID list
function get_locIds_list($where)
{
 global $db_tables;
	$city_sql = city_search_sql_in_db($where);
	if ($city_sql == "") return "";
	if (strlen($city_sql) > 3) $city_sql = " WHERE ".substr($city_sql, 0, -4);
	$sql = "SELECT c.locId,c.latitude,c.longitude,c.region,c.city, r.name as regionname FROM ".$db_tables["city"]." c ".
				"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region and c.country=r.country ".
				$city_sql.get_country_limit(" and c.country=")." LIMIT 5000";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "city",
		"params_list"	=> array(""), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 39*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[$myrow["locId"]] = array("latitude"=>$myrow["latitude"],"longitude"=>$myrow["longitude"],
					"region"=>strtoupper($myrow["region"]),"city"=>strtolower($myrow["city"]), "regionname"=>$myrow["regionname"]);
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

	$where = where_as_array($where);
	//To lower
	for ($i=0; $i<count($where); $i++)
	{
		$where[$i] = strtolower($where[$i]);
	}
	$region_list = array();
	$region_coords = get_full_states_list();

	//Positions	
	$positions = array();
	foreach ($data_array as $locId=>$locData)
	{
		//Check state
		if (!check_iscity_in_where($where,$locData))
		{
			if (in_array($locData["region"],$region_list)) continue;
			foreach ($region_coords as $regId=>$regData)
			{
				if ($regData['state_name'] == $locData["region"]) {
					$positions[] = array("type"=>"state",
								"latitude"=>$regData["latitude"],"longitude"=>$regData["longitude"],
								"latitude1"=>$regData["latitude1"],"longitude1"=>$regData["longitude1"],
								"latitude2"=>$regData["latitude2"],"longitude2"=>$regData["longitude2"]
					);
					$region_list[] = $regData['state_name'];
					break;
				}
			}
		}
		//Process city
		if (!isset($positions[0]["latitude"])) {
			$positions[] = array("type"=>"city","latitude"=>$locData["latitude"],"longitude"=>$locData["longitude"]);
			continue;
		}
		$pcnt = count($positions);
		$isfind = false;
		for($i=0; $i<$pcnt; $i++)
		{
			if (($locData["latitude"] >= $positions[$i]["latitude"]-0.015) && ($locData["latitude"] <= $positions[$i]["latitude"]+0.015) &&
					($locData["longitude"] >= $positions[$i]["longitude"]-0.015) && ($locData["longitude"] <= $positions[$i]["longitude"]+0.015)) {
				$isfind = true; break;
			}
		}
		if (!$isfind) $positions[] = array("type"=>"city","latitude"=>$locData["latitude"],"longitude"=>$locData["longitude"]);
	}
 return $positions;
}

function check_iscity_in_where(&$where,&$locData)
{
	//State
	for ($i=0; $i<count($where); $i++)
	{
		$pos = strpos($locData["city"], $where[$i]);
		if ($pos !== false) {
			return true;
		}
	}
	return false;
}

/*!!
function min_max_lon_lat_vals($D,$lon1,$lat1)
{
	$R = "3956"; //Earth radis (in mi)
	$asin_arg = sin($D/$R)/cos($lat1);
	if ($asin_arg > 1) $asin_arg = 1;
	elseif ($asin_arg < -1) $asin_arg = -1;
	$result["max_lon"] = $lon1 + asin($asin_arg);
	$result["min_lon"] = $lon1 - asin($asin_arg);
	$result["max_lat"] = $lat1 + (180/pi())*($D/$R);
	$result["min_lat"] = $lat1 - (180/pi())*($D/$R);
 return $result;
}
*/
function min_max_lon_lat_vals($D,$lon1,$lat1)
{
	$R = "3956"; //Earth radis (in mi)
	$d = $D/$R;
	$dr = (180/pi())*($D/$R);
	$result["max_lon"] = $lon1 + $dr;
	$result["min_lon"] = $lon1 - $dr;
	$result["max_lat"] = $lat1 + $dr;
	$result["min_lat"] = $lat1 - $dr;
 return $result;
}

/*!!
function get_lon_lat_data(&$positions,$N,$i,$radius)
{
	$longitude_sign = sign($positions[$i]["longitude{$N}"]);
	$latitude_sign = sign($positions[$i]["latitude{$N}"]);
	$lon_lat = min_max_lon_lat_vals($radius+0.000001,$positions[$i]["longitude{$N}"],$positions[$i]["latitude{$N}"]);
	if ($latitude_sign < 0) {
		$ptmp = $lon_lat["min_lat"];
		$lon_lat["min_lat"] = $lon_lat["max_lat"];
		$lon_lat["max_lat"] = $ptmp;
	}
	if ($longitude_sign < 0) {
		$ptmp = $lon_lat["min_lon"];
		$lon_lat["min_lon"] = $lon_lat["max_lon"];
		$lon_lat["max_lon"] = $ptmp;
	}
	return $lon_lat;
}
*/
function get_lon_lat_data(&$positions,$N,$i,$radius)
{
	$lon_lat = min_max_lon_lat_vals($radius+0.000001,$positions[$i]["longitude{$N}"],$positions[$i]["latitude{$N}"]);
	return $lon_lat;
}

function correct_lon_lat_list(&$lon_lat_list)
{
	$list = array();
	for ($i=0; $i<count($lon_lat_list); $i++)
	{
		$is_in = false;
		for ($j=0; $j<count($lon_lat_list); $j++)
		{
			if ($i == $j) continue;
			if (($lon_lat_list[$i]["min_lat"] > $lon_lat_list[$j]["min_lat"]) && ($lon_lat_list[$i]["max_lat"] < $lon_lat_list[$j]["max_lat"]) &&
					($lon_lat_list[$i]["min_lon"] > $lon_lat_list[$j]["min_lon"]) && ($lon_lat_list[$i]["max_lon"] > $lon_lat_list[$j]["max_lon"])) {
				$is_in = true; break;
			}
		}
		if (!$is_in) $list[] = $lon_lat_list[$i];
	}
	return $list;
}

function add_filter_sql_line()
{
	$sql = "";
	if (isset($_SESSION["sess_job_search"]["search_filter"]) && is_array($_SESSION["sess_job_search"]["search_filter"])) {
		foreach ($_SESSION["sess_job_search"]["search_filter"] as $nm=>$val)
		{
			if ($val == "") continue;
			$tbld = ($nm == "locId") ? 'c' : 'd';
			if ($nm == "locCity") {
				$tbld = 'd'; $nm = 'city';
			}
			$dg = ($nm == 'salary') ? '>=' : '=';
			$sql .= "{$tbld}.".data_addslashes($nm).$dg."'".data_addslashes($val)."' and ";
		}
	}
 return $sql;
}

function get_sql_cnt_for_do_job_search($sql)
{
	global $db_tables;
	if ($_SESSION["globsettings"]["allow_cities_in_db"]) {
		//all variants
		$sql_cnt = "SELECT count(*) as num ".
					"FROM ".$db_tables["data_list"]." d ".
					"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".
					"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region and c.country=r.country ";
	}
	else {
		//the same as all variants, but without "city" and "region" and their fields
		$sql_cnt = "SELECT count(*) as num ".
					"FROM ".$db_tables["data_list"]." d ";
	}
	$sql_cnt =	$sql_cnt.$sql.get_country_limit_by_loc_search();
	$sql_cnt = preg_replace("/ +/", " ", $sql_cnt);
	$sql_cnt = preg_replace("/and\sand/", "and", $sql_cnt);
	return $sql_cnt;
}

function do_job_search(&$job_search_params,&$data_array,&$adv_data_array,&$result,$get_only_common_data=0)
{
  global $db_tables, $jobs_published_default;
	$result = true;
	//Prepare new search
	slash_job_values($job_search_params);
	$sql = $city_sql = $city_sql1 = $show_another_sql = "";
	//Create WHERE part
	if ($job_search_params["search_type"] == "simple") {
		if ($job_search_params["what"] != "")	{
			$sql .= "/*match_against_part_start*/MATCH(d.title,d.company_name,d.description) AGAINST ('{$job_search_params["what"]}')/*match_against_part_end*/ and ";
		}
		if ($job_search_params["title"] != "") {
			$sql .= "d.title='{$job_search_params["title"]}' and "; //needs for +locations search
		}
		if ($job_search_params["company_name"] != "") {
			$sql .= "d.company_name='{$job_search_params["company_name"]}' and "; //needs for +locations search
		}
		if (($job_search_params["jobs_category"] != "") && ($job_search_params["jobs_category"] != 0)) {
			$sql .= "d.cat_id='{$job_search_params["jobs_category"]}' and ";
		}
		//Only for cron member job alert
		if ( isset($_SESSION["sess_job_search"]["add_current_search_info"]) && ($_SESSION["sess_job_search"]["add_current_search_info"] == "cron_job_alert") ) {
			if ($job_search_params["where"] != "") {
				if ($_SESSION["globsettings"]["allow_cities_in_db"]) {
					$result_c = true;
					//Get all cities regarding "where" condition
					$positions = get_locIds_list($job_search_params["where"]);
					if (!isset($positions[0]["latitude"])) {
						$result = $result_c = false;
						//$job_search_params["error_code"] = "empty_location";
						//$result = false;
						//return;
					}
					if ($result_c) {
						//Create query
						$tmp_sql = "";
						$lon_lat_list = array();
						for ($i=0; $i<count($positions); $i++)
						{
							if (!isset($positions[$i]["type"]) || ($positions[$i]["type"] == "city")) {
								$lon_lat = get_lon_lat_data($positions,"",$i,$job_search_params["radius"]);
								$lon_lat_list[] = $lon_lat;
							}
							elseif ($positions[$i]["type"] == "state") {
								$lon_lat1 = get_lon_lat_data($positions,"1",$i,$job_search_params["radius"]);
								$lon_lat2 = get_lon_lat_data($positions,"2",$i,$job_search_params["radius"]);
								$lon_lat_list[] = array("min_lat"=>$lon_lat1["min_lat"], "max_lat"=>$lon_lat2["max_lat"], "min_lon"=>$lon_lat1["min_lon"], "max_lon"=>$lon_lat2["max_lon"]);
							}
							else continue;
						}
						if (count($lon_lat_list) > 0) $lon_lat_list = correct_lon_lat_list($lon_lat_list);
						if (count($lon_lat_list) > 0) {
							for ($i=0; $i<count($lon_lat_list); $i++)
							{
								$city_sql .= "((c.latitude>=".$lon_lat_list[$i]["min_lat"]." and c.latitude<=".$lon_lat_list[$i]["max_lat"]." and c.longitude>=".$lon_lat_list[$i]["min_lon"]." and c.longitude<=".$lon_lat_list[$i]["max_lon"].")) or ";
							}
						}
						if (strlen($city_sql) > 2) $city_sql = '('.substr($city_sql, 0, -3).') and ';
						else $city_sql = "";
					}
				}
				if ($_SESSION["globsettings"]["allow_cities_not_in_db"]) {
					$city_sql1 = city_search_sql_not_in_db($job_search_params["where"]);
				}
				else {
					if (!$result_c) {
						$job_search_params["error_code"] = "empty_location";
						return;
					}
				}
				if (($city_sql != '') && ($city_sql1 != '')) {
					$city_sql = substr($city_sql, 0, -4);
					$city_sql1 = substr($city_sql1, 0, -4);
					$city_sql = "({$city_sql}) or ({$city_sql1}) and ";
				}
				elseif ($city_sql1 != '') $city_sql = $city_sql1;
			}
		}
		else {
			if ($job_search_params["where"] != "") {
				//Only for jobroll search: viewers location
				if (isset($job_search_params["where_jobroll_location"]) && ($job_search_params["where_jobroll_location"])) $city_sql = $job_search_params["where"];
				//All others search
				else $city_sql = city_search_sql($job_search_params["where"]);
			}
		}
		$sql .= add_filter_sql_line(); //Filter part
		//for member cron jobs alert
		if (($job_search_params["jobs_published"] != "") && ($job_search_params["jobs_published"] != "any") && ($job_search_params["jobs_published"] != "last")) {
			$sql .= "/*jobs_published_part_start*/d.registered>=DATE_SUB(NOW(),INTERVAL {$job_search_params["jobs_published"]} DAY)/*jobs_published_part_end*/ and ";
		}
		elseif ($job_search_params["jobs_published"] == "last") {
			if (isset($_COOKIE["LastVisitTime"]) && ($_COOKIE["LastVisitTime"] != "") && check_bigint($_COOKIE["LastVisitTime"]))
				$sql .= "/*jobs_published_part_start*/d.registered>=FROM_UNIXTIME({$_COOKIE["LastVisitTime"]})/*jobs_published_part_end*/ and ";
			else
				$sql .= "/*jobs_published_part_start*/d.registered>=DATE_SUB(NOW(),INTERVAL {$jobs_published_default} DAY)/*jobs_published_part_end*/ and ";
		}
		else {
			$sql .= ' 1=1 and /*jobs_published_part_start*/1=1/*jobs_published_part_end*/ and ';
		}
		//$sql .= ' 1=1 and /*jobs_published_part_start*/1=1/*jobs_published_part_end*/ and ';
	}
	elseif ($job_search_params["search_type"] == "advanced") {
		$sql = "";
		if ($job_search_params["title"] != "") {
			$sql .= "d.title='{$job_search_params["title"]}' and ";
		}
		if ($job_search_params["company_name"] != "") {
			$sql .= "d.company_name='{$job_search_params["company_name"]}' and ";
		}
		$ma = false; $against_content = "";
		if ($job_search_params["as_all"] != "") {
			$search = search_keywords_as_array($job_search_params["as_all"]);
			$against_content .= '+'.implode(" +",$search); $ma = true;
		}
		if ($job_search_params["as_phrase"] != "") {
			$against_content .= (($ma) ? ' ' : '').'"'.$job_search_params["as_phrase"].'"'; $ma = true;
		}
		if ($job_search_params["as_any"] != "") {
			$against_content .= (($ma) ? ' ' : '').$job_search_params["as_any"]; $ma = true;
		}
		if ($job_search_params["as_not"] != "") {
			$search = search_keywords_as_array($job_search_params["as_not"]);
			$against_content .= (($ma) ? ' ' : '').'-'.implode(" -",$search); $ma = true;
		}
		if ($ma) {
			$sql .= "/*match_against_part_start*/MATCH(d.title,d.company_name,d.description) AGAINST ('".$against_content."'".(($job_search_params["as_any"] == "")?" IN BOOLEAN MODE":"").")/*match_against_part_end*/ and ";
		}
		if ($job_search_params["as_title"] != "") {
			$sql .= "/*match_against_part_start*/MATCH(d.title) AGAINST ('{$job_search_params["as_title"]}')/*match_against_part_end*/ and ";
		}
		if ($job_search_params["as_company"] != "") {
			$sql .= "/*match_against_part_start*/MATCH(d.company_name) AGAINST ('{$job_search_params["as_company"]}')/*match_against_part_end*/ and ";
		}
		if ($job_search_params["where"] != "") {
			//Only for jobroll search: viewers location
			if (isset($job_search_params["where_jobroll_location"]) && ($job_search_params["where_jobroll_location"])) $city_sql = $job_search_params["where"];
			//All others search
			else {
				if ($_SESSION["globsettings"]["allow_cities_in_db"]) {
					$result_c = true;
					//Get all cities regarding "where" condition
					$positions = get_locIds_list($job_search_params["where"]);
					if (!isset($positions[0]["latitude"])) {
						$result = $result_c = false;
						//return;
					}
					if ($result_c) {
						//Create query
						$tmp_sql = "";
						$lon_lat_list = array();
						for ($i=0; $i<count($positions); $i++)
						{
							if (!isset($positions[$i]["type"]) || ($positions[$i]["type"] == "city")) {
								$lon_lat = get_lon_lat_data($positions,"",$i,$job_search_params["radius"]);
								$lon_lat_list[] = $lon_lat;
							}
							elseif ($positions[$i]["type"] == "state") {
								$lon_lat1 = get_lon_lat_data($positions,"1",$i,$job_search_params["radius"]);
								$lon_lat2 = get_lon_lat_data($positions,"2",$i,$job_search_params["radius"]);
								$lon_lat_list[] = array("min_lat"=>$lon_lat1["min_lat"], "max_lat"=>$lon_lat2["max_lat"], "min_lon"=>$lon_lat1["min_lon"], "max_lon"=>$lon_lat2["max_lon"]);
							}
							else continue;
						}
						if (count($lon_lat_list) > 0) $lon_lat_list = correct_lon_lat_list($lon_lat_list);
						if (count($lon_lat_list) > 0) {
							for ($i=0; $i<count($lon_lat_list); $i++)
							{
								$city_sql .= "((c.latitude>=".$lon_lat_list[$i]["min_lat"]." and c.latitude<=".$lon_lat_list[$i]["max_lat"]." and c.longitude>=".$lon_lat_list[$i]["min_lon"]." and c.longitude<=".$lon_lat_list[$i]["max_lon"].")) or ";
							}
						}
						if (strlen($city_sql) > 2) $city_sql = '('.substr($city_sql, 0, -3).') and ';
						else $city_sql = "";
					}
				}
				if ($_SESSION["globsettings"]["allow_cities_not_in_db"]) {
					$city_sql1 = city_search_sql_not_in_db($job_search_params["where"]);
				}
				else {
					if (!$result_c) {
						$job_search_params["error_code"] = "empty_location";
						return;
					}
				}
				if (($city_sql != '') && ($city_sql1 != '')) {
					$city_sql = substr($city_sql, 0, -4);
					$city_sql1 = substr($city_sql1, 0, -4);
					$city_sql = "({$city_sql}) or ({$city_sql1}) and ";
				}
				elseif ($city_sql1 != '') $city_sql = $city_sql1;
			}
		}
		if (($job_search_params["jobs_category"] != "") && ($job_search_params["jobs_category"] != 0)) {
			$sql .= "d.cat_id='{$job_search_params["jobs_category"]}' and ";
		}
		if (($job_search_params["jobs_type"] != "") && ($job_search_params["jobs_type"] != "all")) {
			$sql .= "d.job_type='{$job_search_params["jobs_type"]}' and ";
		}
		if (($job_search_params["jobs_from"] != "") && ($job_search_params["jobs_from"] != "all")) {
			$sql .= "d.site_type='{$job_search_params["jobs_from"]}' and ";
		}
		if ($job_search_params["norecruiters"]) {
			$sql .= "d.isstaffing_agencies=0 and ";
		}
		if ((isset($job_search_params["salary_from"])) && ($job_search_params["salary_from"] != "")) {
			$sql .= "d.salary>='{$job_search_params["salary_from"]}' and d.salary<='{$job_search_params["salary_to"]}' and ";
		}
		$sql .= add_filter_sql_line(); //Filter part
		if (($job_search_params["jobs_published"] != "") && ($job_search_params["jobs_published"] != "any")) {
			if ($job_search_params["jobs_published"] == "last") {
				if (isset($_COOKIE["LastVisitTime"]) && ($_COOKIE["LastVisitTime"] != "") && check_bigint($_COOKIE["LastVisitTime"]))
					$sql .= "/*jobs_published_part_start*/d.registered>=FROM_UNIXTIME({$_COOKIE["LastVisitTime"]})/*jobs_published_part_end*/ and ";
				else
					$sql .= "/*jobs_published_part_start*/d.registered>=DATE_SUB(NOW(),INTERVAL {$jobs_published_default} DAY)/*jobs_published_part_end*/ and ";
			}
			else $sql .= "/*jobs_published_part_start*/d.registered>=DATE_SUB(NOW(),INTERVAL {$job_search_params["jobs_published"]} DAY)/*jobs_published_part_end*/ and ";
		}
		else {
			$sql .= ' 1=1 and /*jobs_published_part_start*/1=1/*jobs_published_part_end*/ and ';
		}
	}
	if (isset($job_search_params["job_country"]) && ($job_search_params["job_country"] != "") && ($job_search_params["job_country"] != "--")) {
		$sql .= "c.country='{$job_search_params["job_country"]}' and "; //needs for jobroll search
	}
	if (isset($job_search_params["job_where_locations"]) && ($job_search_params["job_where_locations"] != "")) {
		$city_sql .= $job_search_params["job_where_locations"];
	}
	if (strlen($city_sql) > 3) $city_sql = " and ".substr($city_sql, 0, -4);
	if (strlen($sql) > 3) $sql = " WHERE ".substr($sql, 0, -4)." ".$city_sql;
	$job_search_params["city_sql"] = $city_sql;
	$sql = trim($sql);
	$sql_lastpart = substr($sql, -3);
	if ($sql_lastpart == 'and') $sql = substr($sql, 0, -3);
	elseif ($sql_lastpart == ' or') $sql = substr($sql, 0, -3);

	//Final SELECT part: a) count pages select
	//Try to use Normal search
	$sql_cnt = get_sql_cnt_for_do_job_search($sql);
	/*
	$sql_cnt = "SELECT count(*) as num ".
				"FROM ".$db_tables["data_list"]." d ".
				"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".$sql.get_country_limit_by_loc_search();
	$sql_cnt = preg_replace("/ +/", " ", $sql_cnt);
	$sql_cnt = preg_replace("/and\sand/", "and", $sql_cnt);
	*/
	$page_count = get_page_count($sql_cnt,$job_search_params["number_results"]);
//!!!echo "0.0) ".$sql_cnt."<br><br>";
	 //Try to use LIKE search - replace MATCH AGAINST with LIKE -->>
	if ($page_count == 0) {
		$do_like_search = false;
		if (preg_match("~/\*match_against_part_start\*/\s*?MATCH\((.+?)\)\s*?AGAINST\s*?\((.+?)\)/\*match_against_part_end\*/~",$sql,$matches)) {
			if ($matches[1] && $matches[2]) {
				$do_like_search = create_like_sql($matches[1],$matches[2],$sql,"~/\*match_against_part_start\*/(.*?)/\*match_against_part_end\*/~");
			}
		}
		if ($do_like_search) {
			$sql .= ' and d.registered>DATE_SUB(NOW(),INTERVAL 7 DAY) ';
			$sql_cnt = get_sql_cnt_for_do_job_search($sql);
			/*
			$sql_cnt = "SELECT count(*) as num ".
				"FROM ".$db_tables["data_list"]." d ".
				"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".$sql.get_country_limit_by_loc_search();
			$sql_cnt = preg_replace("/ +/", " ", $sql_cnt);
			$sql_cnt = preg_replace("/and\sand/", "and", $sql_cnt);
			*/
			$page_count = get_page_count($sql_cnt,$job_search_params["number_results"]);
		}
	}

	// <<--
	//Create ORDER BY part
	if ($job_search_params["sort_by"] != "") {
		if (!isset($job_search_params["sort_by_mode"])) $job_search_params["sort_by_mode"] = "";
		$sort_by_field = ($job_search_params["sort_by"] == "date") ? "registered" : $job_search_params["sort_by"];
		$order_sql = " ORDER BY {$sort_by_field} {$job_search_params["sort_by_mode"]}";
	}
	else $order_sql = "";
	//Save job query params
	$_SESSION["sess_job_search"]["search_started"] = true;
	$_SESSION["sess_job_search"]["order_sql"] = $order_sql;
	$_SESSION["sess_job_search"]["sql"] = $sql;
	$_SESSION["sess_job_search"]["sql_cnt"] = $sql_cnt;
	$_SESSION["sess_job_search"]["from_count"] = 0;
	$_SESSION["sess_job_search"]["row_count"] = $job_search_params["number_results"];
	$_SESSION["sess_job_search"]["page_count"] = $page_count;
	$_SESSION["sess_job_search"]["page_start"] = 0;
	$_SESSION["sess_job_search"]["job_search_params"] = $job_search_params;
	//Execute job SQL (b) data select)
	$from_count = (isset($job_search_params["start"])) ? $job_search_params["start"] : 0;
	$start = html_chars(get_get_value("start",""));
	if (($start != "") && check_int($start)) {
		if ($start > ($_SESSION["sess_job_search"]["page_count"]-1)) $start = $_SESSION["sess_job_search"]["page_count"]-1;
		if ($start < 0) $start = 0;
		$from_count = $start*$_SESSION["sess_job_search"]["row_count"];
		$_SESSION["sess_job_search"]["page_start"] = $start;
	}
	exec_sqljob_search($sql,$order_sql,0,$job_search_params["number_results"],$data_array,$adv_data_array,$get_only_common_data);
	//Execute job SQL (c) categories list select /for categories filter/)
	$sql_cat_id_list = "SELECT DISTINCT cat_id ".
				"FROM ".$db_tables["data_list"]." d ".
				"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".$sql.get_country_limit_by_loc_search();
	$sql_cat_id_list = preg_replace("/ +/", " ", $sql_cat_id_list);
	$sql_cat_id_list = preg_replace("/and\s+and/", 'and', $sql_cat_id_list);
	$cat_id_list = get_cat_id_list($sql_cat_id_list);
	$_SESSION["sess_job_search"]["sql_cat_id_list"] = $sql_cat_id_list;
	$_SESSION["sess_job_search"]["cat_id_list"] = $cat_id_list;
}

//Execute job search query
/*
$sql - main sql query
$order_sql - order sql query
$from_count,$row_count - limit
$data_array - data result
*/
function exec_sqljob_search($sql,$order_sql,$from_count,$row_count,&$data_array,&$adv_data_array,$get_only_common_data=0)
{
  global $db_tables,$JobsAdsTopCnt,$JobsAdsBottomCnt,$SLINE,$jobroll_more_link,$job_search_params;
	$JobsAdsCnt = $JobsAdsTopCnt + $JobsAdsBottomCnt;
	//Create highlight array
 	$description_terms = create_highlight_description_terms();
	$company_terms = create_highlight_company_terms();
	//Final SELECT part: b.1.) data select from common data table
	$sql = preg_replace("/ +/", " ", $sql);
	$sql = preg_replace("/&#039;/", '\\\'', $sql);
	$sql = unhtmlentities($sql);
	if ($_SESSION["globsettings"]["allow_cities_in_db"]) {
		//all variants
		$mainsql = "SELECT d.*,".format_sql_datetime("d.registered")." as myregtime, UNIX_TIMESTAMP(d.registered) as registered_sec, ".
					"r.name as rregionname, d.region as dregionname, ".
					"c.country as ccountry, c.region as cregion, c.city as ccity, c.postalCode, c.latitude, c.longitude, ".
					"d.country as dcountry, d.city as dcity ".
					"FROM ".$db_tables["data_list"]." d ".
					"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".
					"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region and c.country=r.country ";
	}
	else {
		//the same as all variants, but without "city" and "region" and their fields
		$mainsql = "SELECT d.*,".format_sql_datetime("d.registered")." as myregtime, UNIX_TIMESTAMP(d.registered) as registered_sec, ".
					"d.region as dregionname, ".
					"d.country as dcountry, d.city as dcity ".
					"FROM ".$db_tables["data_list"]." d ";
	}
	$mainsql .=	$sql.get_country_limit_by_loc_search()." ".$order_sql." LIMIT $from_count, $row_count";
	$mainsql = preg_replace("/and\s+and/", 'and', $mainsql);

//!echo "1)".$sql."<br>\n\n";
//!echo "<b>Sorry, site testing...</b><br>";
//!echo "1.1)".$mainsql."<br>\n\n";exit;

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "data_list",
		"params_list"	=> array(""), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $mainsql,
		"actual_time"	=> 10*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	$duplcates_cnt = 0;
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error()."\n".$cache_params_array["query"]);
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$city = ((isset($myrow["ccity"]) && ($myrow["ccity"] != '')) ? $myrow["ccity"] : $myrow["dcity"]);
			if (check_for_subset_duplicates($data_array,$myrow["title"],do_highlight($company_terms,$myrow["company_name"]),$myrow["locId"],do_highlight($description_terms,$myrow["description"]),$myrow["job_type"],$city,$myrow["url"])) {
				mysql_query("DELETE FROM ".$db_tables["data_list"]." WHERE data_id='{$myrow["data_id"]}'") or query_die(__FILE__,__LINE__,mysql_error());
				$duplcates_cnt = 1;
				continue;
			}
			$data_array[$myrow["data_id"]] = array("feed_id"=>$myrow["feed_id"], "title"=>$myrow["title"], "company_name"=>do_highlight($company_terms,$myrow["company_name"]),
				"description"=>do_highlight($description_terms,$myrow["description"]), "clickurl"=>$_SESSION["globsettings"]["site_url"]."job-details/?data-id=".$myrow["data_id"],
				"url"=>$myrow["url"], "salary"=>$myrow["salary"],	"registered_sec"=>$myrow["registered_sec"], "myregtime"=>$myrow["myregtime"],
				"country"=>((isset($myrow["ccountry"]) && ($myrow["ccountry"] != '')) ? $myrow["ccountry"] : $myrow["dcountry"]),
				"region"=>((isset($myrow["cregion"]) && ($myrow["cregion"] != '')) ? $myrow["cregion"] : $myrow["dregionname"]),
				"city"=>((isset($myrow["ccity"]) && ($myrow["ccity"] != '')) ? $myrow["ccity"] : $myrow["dcity"]),
				"regionname"=>((isset($myrow["rregionname"]) && ($myrow["rregionname"] != '')) ? $myrow["rregionname"] : $myrow["dregionname"]),
				"postalCode"=>((isset($myrow["postalCode"]) && ($myrow["postalCode"] != '')) ? $myrow["postalCode"] : ''),
				"latitude"=>((isset($myrow["latitude"]) && ($myrow["latitude"] != '')) ? $myrow["latitude"] : ''),
				"longitude"=>((isset($myrow["longitude"]) && ($myrow["longitude"] != '')) ? $myrow["longitude"] : ''),
				"jobkey"=>'c'.$myrow["data_id"],"source"=>$myrow["source"],"locId"=>$myrow["locId"],"job_type"=>$myrow["job_type"]);
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	if ($duplcates_cnt == 0)
		write_mydata_cache($cache_params_array,$data_array);

	//Add special for this process data
	add_special_for_this_proc_data($data_array,"clickurl",'&'.$SLINE.get_jobroll_publisher_id());

	//Check data select - only common (for jobroll) or common + advertiser ads
	if ($get_only_common_data) {
		//Member cron job alert
		if (isset($_SESSION["sess_job_search"]["add_current_search_info"]) && ($_SESSION["sess_job_search"]["add_current_search_info"] == "cron_job_alert")) {
			$jobroll_more_link = './jobs/?'.search_params_url().'&search-from=jobroll&job-where='.urlencode($job_search_params["job_where"]).'&job-country='.urlencode($job_search_params["job_country"]).'&job-city-state='.urlencode($job_search_params["job_city_state"]).get_jobroll_publisher_id();
			return;
		}
		//Jobroll more link
		if (strlen($jobroll_more_link) > 0)	return;
		else {
			$job_search_params["where"] = $_SESSION["sess_job_search"]["job_search_params"]["where"] = "";
			$jobroll_more_link = './jobs/?'.search_params_url().'&search-from=jobroll&job-where='.urlencode($job_search_params["job_where"]).'&job-country='.urlencode($job_search_params["job_country"]).'&job-city-state='.urlencode($job_search_params["job_city_state"]).get_jobroll_publisher_id();
		}
		return;
	}

	//Final SELECT part: b.2.) data select from advertiser jobs data table
//	$adv_sql = remove_adv_jobs_daily_monthly_limit($sql);
//	$adv_mainsql = "SELECT d.*,".format_sql_datetime("d.registered")." as myregtime, UNIX_TIMESTAMP(d.registered) as registered_sec, ".
//				"c.country,c.region,c.city,c.postalCode,c.latitude,c.longitude, j.max_cpc,j.job_ads_id,j.uid_adv,j.destination_url ".
//				"FROM ".$db_tables["data_list_advertiser"]." d ".
//				"INNER JOIN ".$db_tables["job_ads"]." j ON d.feed_id=j.job_ads_id and j.status=1 ".
//				"INNER JOIN ".$db_tables["users_advertiser"]." ua ON j.uid_adv=ua.uid_adv and ua.balance>0.0 and ua.isenable=1 and ua.isconfirmed=1 and ua.isdeleted=0 ".
//				"INNER JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".$adv_sql." ORDER BY j.max_cpc DESC ".
//				"/*jobs_ads_limit_start*/ LIMIT {$_SESSION["sess_job_search"]["page_start"]}, {$JobsAdsCnt} /*jobs_ads_limit_end*/";
	if ($_SESSION["globsettings"]["allow_cities_in_db"]) {
		//all variants
		$adv_mainsql = "SELECT d.*,".format_sql_datetime("d.registered")." as myregtime, UNIX_TIMESTAMP(d.registered) as registered_sec, ".
				"r.name as rregionname, d.region as dregionname, ".
				"c.country as ccountry, c.region as cregion, c.city as ccity, c.postalCode, c.latitude, c.longitude, ".
				"d.country as dcountry, d.city as dcity, ".
				"j.max_cpc,j.job_ads_id,j.uid_adv,j.destination_url, j.daily_budget, j.monthly_budget, ".
				"IFNULL((SELECT sum(dsc.cost) FROM ".$db_tables["stats_adv_click_jobs"]." dsc ".
			  "        WHERE d.data_id=dsc.job_ads_id and j.uid_adv=dsc.uid_adv and dsc.actiontime>CURDATE() ".
			  "       ),-1) as daily_cost,".
				"IFNULL((SELECT sum(msc.cost) FROM ".$db_tables["stats_adv_click_jobs"]." msc ".
			  "        WHERE d.data_id=msc.job_ads_id and j.uid_adv=msc.uid_adv and TO_DAYS(msc.actiontime)>=TO_DAYS(DATE_FORMAT(CURDATE(),\"%Y-%m-01\")) ".
			  "       ),-1) as monthly_cost ".
				"FROM ".$db_tables["data_list_advertiser"]." d ".
				"INNER JOIN ".$db_tables["job_ads"]." j ON d.feed_id=j.job_ads_id and j.status=1 ".
				"INNER JOIN ".$db_tables["users_advertiser"]." ua ON j.uid_adv=ua.uid_adv and ua.balance>0.0 and ua.isenable=1 and ua.isconfirmed=1 and ua.isdeleted=0 ".
				"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".
				"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region and c.country=r.country ";
	}
	else {
		//the same as all variants, but without "city" and "region" and their fields
		$adv_mainsql = "SELECT d.*,".format_sql_datetime("d.registered")." as myregtime, UNIX_TIMESTAMP(d.registered) as registered_sec, ".
				"d.region as dregionname, ".
				"d.country as dcountry, d.city as dcity, ".
				"j.max_cpc,j.job_ads_id,j.uid_adv,j.destination_url, j.daily_budget, j.monthly_budget, ".
				"IFNULL((SELECT sum(dsc.cost) FROM ".$db_tables["stats_adv_click_jobs"]." dsc ".
			  "        WHERE d.data_id=dsc.job_ads_id and j.uid_adv=dsc.uid_adv and dsc.actiontime>CURDATE() ".
			  "       ),-1) as daily_cost,".
				"IFNULL((SELECT sum(msc.cost) FROM ".$db_tables["stats_adv_click_jobs"]." msc ".
			  "        WHERE d.data_id=msc.job_ads_id and j.uid_adv=msc.uid_adv and TO_DAYS(msc.actiontime)>=TO_DAYS(DATE_FORMAT(CURDATE(),\"%Y-%m-01\")) ".
			  "       ),-1) as monthly_cost ".
				"FROM ".$db_tables["data_list_advertiser"]." d ".
				"INNER JOIN ".$db_tables["job_ads"]." j ON d.feed_id=j.job_ads_id and j.status=1 ".
				"INNER JOIN ".$db_tables["users_advertiser"]." ua ON j.uid_adv=ua.uid_adv and ua.balance>0.0 and ua.isenable=1 and ua.isconfirmed=1 and ua.isdeleted=0 ";
	}
	$adv_mainsql .=	$sql.get_country_limit_by_loc_search()." ORDER BY j.max_cpc DESC ".
				"/*jobs_ads_limit_start*/ LIMIT {$_SESSION["sess_job_search"]["page_start"]}, {$JobsAdsCnt} /*jobs_ads_limit_end*/";
	$adv_mainsql = preg_replace("/and\s+and/", 'and', $adv_mainsql);
//!echo "2)".$adv_mainsql."<br>\n\n";

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
			$adv_data_array[$myrow["data_id"]] = array("feed_id"=>$myrow["feed_id"], "title"=>$myrow["title"], "company_name"=>do_highlight($company_terms,$myrow["company_name"]),
				"description"=>do_highlight($description_terms,$myrow["description"]), "clickurl"=>$_SESSION["globsettings"]["site_url"]."job-details/?data-id-adv=".$myrow["data_id"],
				"url"=>$myrow["url"], "salary"=>$myrow["salary"], "registered_sec"=>$myrow["registered_sec"], "myregtime"=>$myrow["myregtime"],
				"country"=>((isset($myrow["ccountry"]) && ($myrow["ccountry"] != '')) ? $myrow["ccountry"] : $myrow["dcountry"]),
				"region"=>((isset($myrow["cregion"]) && ($myrow["cregion"] != '')) ? $myrow["cregion"] : $myrow["dregionname"]),
				"city"=>((isset($myrow["ccity"]) && ($myrow["ccity"] != '')) ? $myrow["ccity"] : $myrow["dcity"]),
				"regionname"=>((isset($myrow["rregionname"]) && ($myrow["rregionname"] != '')) ? $myrow["rregionname"] : $myrow["dregionname"]),
				"postalCode"=>((isset($myrow["postalCode"]) && ($myrow["postalCode"] != '')) ? $myrow["postalCode"] : ''),
				"latitude"=>((isset($myrow["latitude"]) && ($myrow["latitude"] != '')) ? $myrow["latitude"] : ''),
				"longitude"=>((isset($myrow["longitude"]) && ($myrow["longitude"] != '')) ? $myrow["longitude"] : ''),
				"max_cpc"=>$myrow["max_cpc"],	"uid_adv"=>$myrow["uid_adv"], "destination_url"=>$myrow["destination_url"],
				"job_ads_id"=>$myrow["job_ads_id"],	"jobkey"=>'a'.$myrow["data_id"]);
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$adv_data_array);

	//Add special for this process data
	add_special_for_this_proc_data($adv_data_array,"clickurl",'&'.$SLINE.get_jobroll_publisher_id());

	$_SESSION["sess_job_search"]["adv_job_list"] = $adv_data_array;

	//Discount job click cost
	discount_this_job_adv($adv_data_array);

	//Set statistic maybe_pageview_jobs
	set_stats_adv_maybe_pageview_jobs($adv_mainsql);

	//Create left side (job filters)
	exec_jobfilters_search($sql);

	//Create navigation array
	create_navigation_array();
}

function check_for_subset_duplicates(&$data_list,$title,$company_name,$locId,$description,$job_type,$city,$url)
{
	foreach($data_list as $k=>$v)
	{
		if (($v["title"] == $title) && ($v["company_name"] == $company_name) &&
			($v["locId"] == $locId) && ($v["description"] == $description) &&
			(strlen($v["url"]) == strlen($url)) &&
			($v["job_type"] == $job_type) && $v["city"] == $city) {
			return true;
		}
	}
	return false;
}

function create_highlight_company_terms()
{
	$jsp = $_SESSION["sess_job_search"]["job_search_params"];
	if (!isset($jsp["what_where"])) $jsp["what_where"] = "";
	$t = search_keywords_as_array($jsp["as_company"].' '.$jsp["company_name"].' '.$jsp["what"].' '.$jsp["as_all"].' '.$jsp["as_any"].' '.$jsp["where"].' '.$jsp["what_where"]);
	if (strlen($jsp["as_phrase"]) > 0) $t[] = $jsp["as_phrase"];
	return $t;
}

function create_highlight_description_terms()
{
	$jsp = $_SESSION["sess_job_search"]["job_search_params"];
	if (!isset($jsp["what_where"])) $jsp["what_where"] = "";
	$t = search_keywords_as_array($jsp["what"].' '.$jsp["as_all"].' '.$jsp["as_any"].' '.$jsp["where"].' '.$jsp["what_where"]);
	if (strlen($jsp["as_phrase"]) > 0) $t[] = $jsp["as_phrase"];
	return $t;
}

function do_highlight(&$terms,$str)
{
	if (count($terms) == 0) return $str;
	for ($i=0; $i<count($terms); $i++)
	{
		if (strlen($terms[$i]) == 0) continue;
		$str = str_replace($terms[$i], '<b>'.$terms[$i].'</b>', $str);
	}
 return $str;
}

function null_search_sess_values()
{
	$_SESSION["sess_job_search"]["search_started"] = false;
	$_SESSION["sess_job_search"]["order_sql"] = "";
	$_SESSION["sess_job_search"]["sql"] = "";
	$_SESSION["sess_job_search"]["sql_cnt"] = "";
	$_SESSION["sess_job_search"]["from_count"] = 0;
	$_SESSION["sess_job_search"]["row_count"] = 20;
	$_SESSION["sess_job_search"]["page_count"] = 1;
	$_SESSION["sess_job_search"]["page_start"] = 0;
	$_SESSION["sess_job_search"]["job_search_params"] = array();
	if (isset($_SESSION["sess_job_search"]["search_filter"]))  $_SESSION["sess_job_search"]["search_filter"] = array();
	if (isset($_SESSION["sess_job_search"]["keywordads_list"])) { $_SESSION["sess_job_search"]["keywordads_list"] = ""; unset($_SESSION["sess_job_search"]["keywordads_list"]); }
}

function check_fill_some_sess_values()
{
	if (isset($_SESSION["sess_job_search"]["search_started"]) && ($_SESSION["sess_job_search"]["search_started"] != "") &&
			isset($_SESSION["sess_job_search"]["page_count"]) && 
			isset($_SESSION["sess_job_search"]["row_count"]) && 
			($_SESSION["sess_job_search"]["sql"] != "") ) return 1;
 return 0;
}

//Get more locations... (+X locations)
function get_more_location_by_this_job(&$job_search_params,$job_data,&$job_list)
{
  global $db_tables;
	//Check jobs_category
	if (count($job_search_params) > 1)
		$jc = (isset($job_search_params["jobs_category"]) && ($job_search_params["jobs_category"] != "") && ($job_search_params["jobs_category"] != '0') && check_int($job_search_params["jobs_category"])) ? "and d.cat_id='{$job_search_params["jobs_category"]}' " : "";
	else
		$jc =	(isset($_SESSION["sess_job_search"]["job_search_params"]["jobs_category"]) && ($_SESSION["sess_job_search"]["job_search_params"]["jobs_category"] != "") && ($_SESSION["sess_job_search"]["job_search_params"]["jobs_category"] != '0') && check_int($_SESSION["sess_job_search"]["job_search_params"]["jobs_category"])) ? "and d.cat_id='{$_SESSION["sess_job_search"]["job_search_params"]["jobs_category"]}' " : "";
	//Set more locations params
	$sql = "WHERE d.title='".addslashes($job_data["title"])."' and d.company_name='".addslashes($job_data["company_name"])."' ".$jc;

	//Final SELECT part
	$sql = "SELECT d.data_id,d.title,d.company_name,d.locId,d.description,d.url,d.job_type,d.country,d.region,d.city as dcity, c.city as ccity FROM ".$db_tables["data_list"]." d ".
				"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".
				"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region and c.country=r.country ".
				$sql.get_country_limit_by_loc_search()." LIMIT 200";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "city",
		"params_list"	=> array(""), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 10*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = $data_list_array = array();
	$duplcates_cnt = 0;
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$city = ((isset($myrow["ccity"]) && ($myrow["ccity"] != '')) ? $myrow["ccity"] : $myrow["dcity"]);
			if (!isset($job_list[$myrow["data_id"]]) && check_for_subset_duplicates($data_list_array,$myrow["title"],$myrow["company_name"],$myrow["locId"],$myrow["description"],$myrow["job_type"],$city,$myrow["url"])) {
				mysql_query("DELETE FROM ".$db_tables["data_list"]." WHERE data_id='{$myrow["data_id"]}'") or query_die(__FILE__,__LINE__,mysql_error());
				$duplcates_cnt = 1;
				continue;
			}
			$data_list_array[$myrow["data_id"]] = array("title"=>$myrow["title"], "company_name"=>$myrow["company_name"],
				"description"=>$myrow["description"],"locId"=>$myrow["locId"],"job_type"=>$myrow["job_type"],
				"country"=>$myrow["country"],"region"=>$myrow["region"],"city"=>$city,"url"=>$myrow["url"]);
		}
		$cnt = count($data_list_array);
		if ($cnt > 0) $data_array["num"] = $cnt;
	}

	// * * Write cache * * //
	//if use cache - save data
	if ($duplcates_cnt == 0)
		write_mydata_cache($cache_params_array,$data_array);
 return $data_array;
}

//Get Feed name by feed ID
function get_feed_name_by_id($feed_id)
{
  global $db_tables;
	$sql = "SELECT title FROM ".$db_tables["sites_feed_list"]." WHERE feed_id='$feed_id'";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "sites_feed_list",
		"params_list"	=> array(""), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 10*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res) > 0) {
			$myrow = mysql_fetch_array($qr_res);
			$data_array["title"] = $myrow["title"];
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);
 return $data_array;
}

//Get registred ago data
function get_registered_ago($registered_sec)
{
 global $text_info;
	$divide = array(60,60,24,30,12,10); //(min,hour,day,month,year)
	$d = 0;
	$sec = time() - $registered_sec;
	do {
		$sec = round($sec/$divide[$d]);
		$d++;
	} while (($sec > $divide[$d]) && ($d < 5));
	//return time string
	$result = array("text"=>'1 '.$text_info["p_day_ago1"], "isnew"=>0);
	$sec = abs($sec);
	switch ($d) {
		case 0: $result = ($sec == 1) ? array("text"=>$sec.' '.$text_info["p_sec_ago1"], "isnew"=>1) : array("text"=>$sec.' '.$text_info["p_sec_agoN"], "isnew"=>1); break;
		case 1: $result = ($sec == 1) ? array("text"=>$sec.' '.$text_info["p_min_ago1"], "isnew"=>1) : array("text"=>$sec.' '.$text_info["p_min_agoN"], "isnew"=>1); break;
		case 2: $result = ($sec == 1) ? array("text"=>$sec.' '.$text_info["p_hour_ago1"], "isnew"=>1) : array("text"=>$sec.' '.$text_info["p_hour_agoN"], "isnew"=>1); break;
		case 3: $result = ($sec == 1) ? array("text"=>$sec.' '.$text_info["p_day_ago1"], "isnew"=>1) : array("text"=>$sec.' '.$text_info["p_day_agoN"], "isnew"=>0); break;
		case 4: $result = ($sec == 1) ? array("text"=>$sec.' '.$text_info["p_month_ago1"], "isnew"=>0) : array("text"=>$sec.' '.$text_info["p_month_agoN"], "isnew"=>0); break;
		case 5: $result = ($sec == 1) ? array("text"=>$sec.' '.$text_info["p_year_ago1"], "isnew"=>0) : array("text"=>$sec.' '.$text_info["p_year_agoN"], "isnew"=>0); break;
	}
 return $result;
}

// * Try like search * //
function create_like_sql($fields,$values,&$sql,$replv)
{
	$vals = array();
	$sqlword = ' LIKE ';
	$cond = ' AND ';
	$final_cond = ' OR ';	
	$values = str_replace(" IN BOOLEAN MODE", "", $values);
	//With all of these words
	if ( $c = preg_match_all("~(\+\w+)~si", $values, $matches) ) {
		for($i=0; $i<count($matches[1]); $i++)
		{
			$vals[] = str_replace("+", "", $matches[1][$i]);
		}
	}
	//With the exact phrase
	elseif ( $c = preg_match_all("~(\"[\w\s]+\")~si", $values, $matches) ) {
		$vals[] = str_replace("\"", "", $matches[1][0]);
	}
	//Without the words
	elseif ( $c = preg_match_all("~(\-\w+)~si", $values, $matches) ) {
		for($i=0; $i<count($matches[1]); $i++)
		{
			$vals[] = str_replace("-", "", $matches[1][$i]);
		}
		$sqlword = ' NOT LIKE ';
		$final_cond = ' AND ';
	}
	//With at least one of these words 
	elseif ( $c = preg_match_all("~(\w+)~si", $values, $matches) ) {
		for($i=0; $i<count($matches[1]); $i++)
		{
			$vals[] = $matches[1][$i];
		}
		$cond = ' OR ';
	}
	if (count($vals) == 0) return false;

	//Create SQL
	$new_qp = array();
	$mt1 = explode(',',$fields);
	for ($mi=0; $mi<count($mt1); $mi++)
	{
		$temp = str_replace("\'", "", $mt1[$mi]);
		if ($temp == '') continue;
		if ($temp == 'd.description') continue;
		$new = array();
		for ($i=0; $i<count($vals); $i++)
		{
			$new[] = "{$temp} {$sqlword} '%{$vals[$i]}%'";
		}
		if (count($new) == 0) return false;
		$new_qp[] = ' ('.implode(" {$cond} ",$new).') ';
	}
	if (count($new_qp) > 0) {
		$sql = preg_replace(
				$replv,
				' ('.implode($final_cond,$new_qp).') ',
				$sql
		);
		return true;
	}
 return false;
}

// * * * * * * * * * //
// Design job search //
function do_job_design(&$job_search_params,&$job_list,&$adv_job_list)
{
  global $db_tables, $SLINE;
	$already_more_locations = array();
	//jobs list
	foreach($job_list as $job_id=>$job_data)
	{
		//check search mode
		if ($_SESSION["sess_job_search"]["current_search_mode"] != "plus_locations_search") {
			//if no this location in our list - try to find
			if ( !isset($already_more_locations[$job_data["title"]]) || ($already_more_locations[$job_data["title"]] != $job_data["company_name"]) )
				$more_location = get_more_location_by_this_job($job_search_params,$job_data,$job_list);
			else
				$more_location["num"] = 0;
			//if we find more locations
			if (isset($more_location["num"]) && ($more_location["num"] > 1)) {
				$job_list[$job_id]["plus_locations"]["count"] = $more_location["num"];
				$job_list[$job_id]["plus_locations"]["url"] = "./?plus-locations=1&title=".urlencode($job_data["title"])."&company-name=".urlencode($job_data["company_name"])."&".$SLINE."&".search_params_url("","",false);
				$already_more_locations[$job_data["title"]] = $job_data["company_name"];
			}
			else $job_list[$job_id]["plus_locations"]["count"] = 0;
		}
		else $job_list[$job_id]["plus_locations"]["count"] = 0;
		//check other values
		$feed_name = get_feed_name_by_id($job_data["feed_id"]);
		$job_list[$job_id]["feed_name"] = (isset($feed_name["title"])) ? $feed_name["title"] : "";
		$registered_ago = get_registered_ago($job_data["registered_sec"]);
		$job_list[$job_id]["registered_ago"] = $registered_ago["text"];
		$job_list[$job_id]["isnew"] = $registered_ago["isnew"];
	}
	//adv jobs list
	foreach($adv_job_list as $job_id=>$job_data)
	{
		$adv_job_list[$job_id]["feed_name"] = $job_data["destination_url"];
		$registered_ago = get_registered_ago($job_data["registered_sec"]);
		$adv_job_list[$job_id]["registered_ago"] = $registered_ago["text"];
	}
}

function do_jobroll_design(&$job_search_params,&$job_list)
{
  global $db_tables, $SLINE;
	//jobs list
	foreach($job_list as $job_id=>$job_data)
	{
		//check other values
		$feed_name = get_feed_name_by_id($job_data["feed_id"]);
		$job_list[$job_id]["feed_name"] = (isset($feed_name["title"])) ? $feed_name["title"] : "";
		$registered_ago = get_registered_ago($job_data["registered_sec"]);
		$job_list[$job_id]["registered_ago"] = $registered_ago["text"];
	}
}

function do_xmljob_design(&$job_search_params,&$job_list,&$adv_job_list)
{
  global $db_tables, $SLINE, $usersettings;
	//jobs list
	foreach($job_list as $job_id=>$job_data)
	{
		$feed_name = get_feed_name_by_id($job_data["feed_id"]);
		$job_list[$job_id]["feed_name"] = (isset($feed_name["title"])) ? $feed_name["title"] : "";
//	if (function_exists('date_default_timezone_set')) date_default_timezone_set($usersettings["timezone_identifier"]);
		$registered_ago = get_registered_ago($job_data["registered_sec"]);
		$job_list[$job_id]["registered_ago"] = $registered_ago["text"];
		$job_list[$job_id]["registered_gmt_datetime"] = gmdate($usersettings["xml_datetimeformat"],$job_data["registered_sec"]);
	}
	//adv jobs list
	foreach($adv_job_list as $job_id=>$job_data)
	{
		$adv_job_list[$job_id]["feed_name"] = $job_data["destination_url"];
		$registered_ago = get_registered_ago($job_data["registered_sec"]);
		$adv_job_list[$job_id]["registered_ago"] = $registered_ago["text"];
		$adv_job_list[$job_id]["registered_gmt_datetime"] = gmdate($usersettings["xml_datetimeformat"],$job_data["registered_sec"]);
	}
}

function do_sendjob_design_c(&$job_data)
{
  global $db_tables, $SLINE, $usersettings;
	//jobs list
	for ($i=0; $i<count($job_data); $i++)
	{
		$feed_name = get_feed_name_by_id($job_data[$i]["feed_id"]);
		$job_data[$i]["feed_name"] = (isset($feed_name["title"])) ? $feed_name["title"] : "";
		$registered_ago = get_registered_ago($job_data[$i]["registered_sec"]);
		$job_data[$i]["registered_ago"] = $registered_ago["text"];
	}
}

function do_sendjob_design_a(&$job_data)
{
  global $db_tables, $SLINE, $usersettings;
	//adv jobs list
	for ($i=0; $i<count($job_data); $i++)
	{
		$job_data[$i]["feed_name"] = $job_data[$i]["destination_url"];
		$registered_ago = get_registered_ago($job_data[$i]["registered_sec"]);
		$job_data[$i]["registered_ago"] = $registered_ago["text"];
	}
}

function get_search_type_selection()
{
 global $jobs_published_default, $text_info, $SLINE;
	if (!isset($_COOKIE["LastVisitTime"]) || ($_COOKIE["LastVisitTime"] == "") || !check_bigint($_COOKIE["LastVisitTime"])) return "";
	$sql_cnt = $_SESSION["sess_job_search"]["sql_cnt"];
	//last jobs sql count query
	if ($_SESSION["sess_job_search"]["job_search_params"]["jobs_published"] != "last") {
		$sql_cnt = preg_replace(
						"~/\*jobs_published_part_start\*/(.*?)/\*jobs_published_part_end\*/~",
						"/*jobs_published_part_start*/d.registered>=FROM_UNIXTIME({$_COOKIE["LastVisitTime"]})/*jobs_published_part_end*/ ",
						$sql_cnt
		);
		$txtv = "p_show_new_jobs";
		$urlv = "./?jobs-new=1&mode=last&".$SLINE."&".search_params_url();
	}
	//all jobs sql count query
	else {
	//$sql_cnt = preg_replace(
	//				"~/\*jobs_published_part_start\*/(.*?)/\*jobs_published_part_end\*/~",
	//					"/*jobs_published_part_start*/d.registered>=DATE_SUB(NOW(),INTERVAL {$jobs_published_default} DAY)/*jobs_published_part_end*/ ",
	//					$sql_cnt
	//	);
		$sql_cnt = preg_replace(
					"~/\*jobs_published_part_start\*/(.*?)/\*jobs_published_part_end\*/~",
						"/*jobs_published_part_start*/ 1=1 /*jobs_published_part_end*/ ",
						$sql_cnt
		);
		$txtv = "p_show_all_jobs";
		//$urlv = "./?jobs-new=0&mode=&".$SLINE."&".search_params_url();
		$urlv = "./?mode=&".$SLINE."&".search_params_url();
		$urlv = preg_replace(
					"~jobs-published=last~",
						"jobs-published=",
						$urlv
		);
		$urlv = preg_replace(
					"~jobs_published=last~",
						"jobs_published=",
						$urlv
		);
	}
	$num = get_query_num_count($sql_cnt);
	if (!check_int($num)) return "";
	if ($num == 0) $txtv = "p_show_all_jobs_only";
	$tmp = str_replace("{*Num*}", $num, $text_info[$txtv]);
	$tmp = str_replace("{*URL*}", $urlv, $tmp);
 return $tmp;
}

function get_search_order_selection()
{
 global $text_info, $SLINE;
	if ($_SESSION["sess_job_search"]["job_search_params"]["sort_by"] == "") {
		$txtv = "p_sort_by_relevance";
		$urlv = "./?order-new=1&mode=date&".$SLINE."&".search_params_url();
	}
	else {
		$txtv = "p_sort_by_date";
		$urlv = "./?order-new=1&mode=&".$SLINE."&".search_params_url();
	}
 return str_replace("{*URL*}", $urlv, $text_info[$txtv]);
}

function correct_sort_by($asb)
{
	if ($asb == "") return "";
	if ($asb == 'registered') $asb = 'date';
 return urlencode($asb);
}

function search_params_url($jobs_category="",$number_results="",$use_search_from=true)
{
	if (!isset($_SESSION["sess_job_search"]["job_search_params"]) || !is_array($_SESSION["sess_job_search"]["job_search_params"])) return 'a=1';
	$a = $_SESSION["sess_job_search"]["job_search_params"];
	if (strlen($jobs_category) > 0) $a["jobs_category"] = $jobs_category;
	if (strlen($number_results) > 0) $a["number_results"] = $number_results;
	//Check My Jobs page
	if (!isset($a["what"]) && isset($a["number_results"])) return "number-results=".urlencode($a["number_results"]);
	$url = "search-type=".urlencode($a["search_type"])."&what=".urlencode($a["what"])."&as-all=".urlencode($a["as_all"]).
		"&as-phrase=".urlencode($a["as_phrase"])."&as-any=".urlencode($a["as_any"])."&as-not=".urlencode($a["as_not"]).
		"&as-title=".urlencode($a["as_title"])."&as-company=".urlencode($a["as_company"])."&radius=".urlencode($a["radius"]).
		"&jobs-category=".urlencode($a["jobs_category"]).
		"&jobs-type=".urlencode($a["jobs_type"])."&jobs-from=".urlencode($a["jobs_from"])."&norecruiters=".urlencode($a["norecruiters"]).
		"&salary=".urlencode($a["salary"])."&jobs-published=".urlencode($a["jobs_published"])."&where=".urlencode($a["where"]).
		"&number-results=".urlencode($a["number_results"])."&sort-by=".correct_sort_by($a["sort_by"]);
		if (isset($a["job_where"])) $url .= "&job-where=".urlencode($a["job_where"]);
		if (isset($a["job_city_state"])) $url .= "&job-city-state=".urlencode($a["job_city_state"]);
		if (isset($a["job_country"])) $url .= "&job-country=".urlencode($a["job_country"]);
		if (isset($a["search_from"]) && $use_search_from) $url .= "&search-from=".urlencode($a["search_from"]);
 return $url;
}

function search_params_url_select($radius)
{
	if (!isset($_SESSION["sess_job_search"]["job_search_params"]) || !is_array($_SESSION["sess_job_search"]["job_search_params"])) return 'a=1';
	$a = $_SESSION["sess_job_search"]["job_search_params"];
	if (isset($a["what"]) && ($a["what"] != "")) {
		if (isset($a["as_all"]) && ($a["as_all"] == "")) $a["as_all"] = $a["what"];
		elseif (isset($a["as_all"]) && ($a["as_all"] != "")) $a["as_all"] .= ' '.$a["what"];
		else $a["as_all"] = $a["what"];
	}
	$url = "search-type=advanced&what=&as-all=".urlencode($a["as_all"]).
		"&as-phrase=".urlencode($a["as_phrase"])."&as-any=".urlencode($a["as_any"])."&as-not=".urlencode($a["as_not"]).
		"&as-title=".urlencode($a["as_title"])."&as-company=".urlencode($a["as_company"])."&radius=".urlencode($radius).
		"&jobs-category=".urlencode($a["jobs_category"]).
		"&jobs-type=".urlencode($a["jobs_type"])."&jobs-from=".urlencode($a["jobs_from"])."&norecruiters=".urlencode($a["norecruiters"]).
		"&salary=".urlencode($a["salary"])."&jobs-published=any&where=".urlencode($a["where"]).
		"&number-results=".urlencode($a["number_results"])."&sort-by=".correct_sort_by($a["sort_by"]);
		if (isset($a["job_where"])) $url .= "&job-where=".urlencode($a["job_where"]);
		if (isset($a["job_city_state"])) $url .= "&job-city-state=".urlencode($a["job_city_state"]);
		if (isset($a["job_country"])) $url .= "&job-country=".urlencode($a["job_country"]);
 return $url;
}


// * * * * * * * * * *//
// Special job search //
// * * * * * * * * * *//

//If wi have not session try to get data from query string and put to session
function check_data_without_session(&$job_search_params)
{
	if (!isset($_SESSION["sess_job_search"]["job_search_params"]) || !is_array($_SESSION["sess_job_search"]["job_search_params"])) {
		try_get_data_from_query_string($job_search_params);
		$_SESSION["sess_job_search"]["job_search_params"] = $job_search_params;
	}
}

function try_get_data_from_query_string(&$job_search_params)
{
 global $Error_messages;
	//Null search session values
	null_search_sess_values();
	//Get search data
	$job_search_params = get_job_search_params();
	//Check search data
	check_job_search_params($job_search_params);
	//Check keyword
	if ($job_search_params["error_code"] == "empty_keyword") create_start_error_page($Error_messages["search_empty_keyword"]);
}

//+N locations select
function plus_locations_search(&$job_search_params)
{
	if (!isset($_SESSION["sess_job_search"]["job_search_params"]) || !is_array($_SESSION["sess_job_search"]["job_search_params"])) return 0;
	$plus_locations = get_get_true_false("plus_locations");
	$title = data_addslashes(html_chars(get_get_value("title","")));
	$company_name = data_addslashes(html_chars(get_get_value("company_name","")));
	if (!$plus_locations || ($title == "") && ($company_name == "")) return 0;

	$unset_vals = array("search_from","job_where","job_where_locations","job_country");
	for ($i=0; $i<count($unset_vals); $i++)
	{
		if (isset($_SESSION["sess_job_search"]["job_search_params"][$unset_vals[$i]])) unset($_SESSION["sess_job_search"]["job_search_params"][$unset_vals[$i]]);
	}

	$job_search_params = $_SESSION["sess_job_search"]["job_search_params"];
	$job_search_params["title"] = $title;
	$job_search_params["company_name"] = $company_name;
	$job_search_params["as_title"] = $job_search_params["as_company"] = $job_search_params["what"] = $job_search_params["where"] = 
	$job_search_params["jobs_type"] = $job_search_params["jobs_from"] = $job_search_params["salary_from"] = 
	$job_search_params["jobs_published"] = 	$job_search_params["as_all"] = $job_search_params["as_phrase"] =
	$job_search_params["as_any"] = $job_search_params["as_not"] = "";
	$job_search_params["norecruiters"] = 0;
 return 1;
}

//Next | Prev page select
function next_page_query(&$job_search_params,&$job_list,&$adv_job_list,&$result)
{
	//Check prev search
	$nextpg = get_get_true_false("nextpg");
	$start = html_chars(get_get_value("start",""));
	if ($nextpg && ($start != "") && check_int($start) && check_fill_some_sess_values() && (count($_SESSION["sess_job_search"]["job_search_params"]) > 1) ) {
		if ($start > ($_SESSION["sess_job_search"]["page_count"]-1)) $start = $_SESSION["sess_job_search"]["page_count"]-1;
		if ($start < 0) $start = 0;
		$from_count = $start*$_SESSION["sess_job_search"]["row_count"];
		$_SESSION["sess_job_search"]["page_start"] = $start;
		//Execute job SQL
		exec_sqljob_search($_SESSION["sess_job_search"]["sql"],$_SESSION["sess_job_search"]["order_sql"],$from_count,$_SESSION["sess_job_search"]["row_count"],$job_list,$adv_job_list);
		$job_search_params["error_code"] = "";
		$result = true;
		return 1;
	}
 return 0;
}

//Show: all jobs - 404 new jobs
function show_jobs_new(&$job_search_params,&$job_list,&$adv_job_list,&$result)
{
 global $db_tables,$jobs_published_default;
	//Check prev search
	$jobs_new = get_get_true_false("jobs_new");
	$mode = html_chars(get_get_post_value("mode",""));
	if ($jobs_new && (($mode == "") || ($mode == "last")) && check_fill_some_sess_values()) {
		if ($mode == "") {
			$_SESSION["sess_job_search"]["sql"] = preg_replace(
						"~/\*jobs_published_part_start\*/(.*?)/\*jobs_published_part_end\*/~",
						"/*jobs_published_part_start*/d.registered>=DATE_SUB(NOW(),INTERVAL {$jobs_published_default} DAY)/*jobs_published_part_end*/ ",
						$_SESSION["sess_job_search"]["sql"]
			);
			$_SESSION["sess_job_search"]["job_search_params"]["jobs_published"] = $jobs_published_default;
		}
		else {
			if (isset($_COOKIE["LastVisitTime"]) && ($_COOKIE["LastVisitTime"] != "") && check_bigint($_COOKIE["LastVisitTime"]))
				$_SESSION["sess_job_search"]["sql"] = preg_replace(
							"~/\*jobs_published_part_start\*/(.*?)/\*jobs_published_part_end\*/~",
							"/*jobs_published_part_start*/d.registered>=FROM_UNIXTIME({$_COOKIE["LastVisitTime"]})/*jobs_published_part_end*/ ",
							$_SESSION["sess_job_search"]["sql"]
				);
			else 
				$_SESSION["sess_job_search"]["sql"] = preg_replace(
							"~/\*jobs_published_part_start\*/(.*?)/\*jobs_published_part_end\*/~",
							"/*jobs_published_part_start*/d.registered>=DATE_SUB(NOW(),INTERVAL {$jobs_published_default} DAY)/*jobs_published_part_end*/ ",
							$_SESSION["sess_job_search"]["sql"]
				);
			$_SESSION["sess_job_search"]["job_search_params"]["jobs_published"] = "last";
		}
/*
		$sql_cnt = "SELECT count(*) as num ".
				"FROM ".$db_tables["data_list"]." d ".
				"INNER JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".$_SESSION["sess_job_search"]["sql"].get_country_limit(" and c.country=");
*/
		$sql_cnt = get_sql_cnt_for_do_job_search($_SESSION["sess_job_search"]["sql"]);
		$page_count = get_page_count($sql_cnt,$_SESSION["sess_job_search"]["job_search_params"]["number_results"]);
		$_SESSION["sess_job_search"]["sql_cnt"] = $sql_cnt;
		$_SESSION["sess_job_search"]["from_count"] = 0;
		$_SESSION["sess_job_search"]["page_count"] = $page_count;
		$_SESSION["sess_job_search"]["page_start"] = 0;
		//Execute job SQL
		exec_sqljob_search($_SESSION["sess_job_search"]["sql"],$_SESSION["sess_job_search"]["order_sql"],$_SESSION["sess_job_search"]["from_count"],$_SESSION["sess_job_search"]["row_count"],$job_list,$adv_job_list);
		$job_search_params["error_code"] = "";
		$result = true;
		return 1;
	}
 return 0;
}

//Sort by: relevance - date
function change_jobs_sort(&$job_search_params,&$job_list,&$adv_job_list,&$result)
{
 global $jobs_published_default;
	//Check prev search
	$order_new = get_get_true_false("order_new");
	$mode = html_chars(get_get_value("mode",""));
	if ($order_new && (($mode == "") || ($mode == "date")) && check_fill_some_sess_values()) {
		if ($mode == "") {
			$_SESSION["sess_job_search"]["job_search_params"]["sort_by"] = "";
			$_SESSION["sess_job_search"]["order_sql"] = "";
		}
		else {
			$_SESSION["sess_job_search"]["job_search_params"]["sort_by"] = "registered";
			$_SESSION["sess_job_search"]["order_sql"] = " ORDER BY registered DESC";
		}
		$_SESSION["sess_job_search"]["from_count"] = 0;
		$_SESSION["sess_job_search"]["page_start"] = 0;
		//Execute job SQL
		exec_sqljob_search($_SESSION["sess_job_search"]["sql"],$_SESSION["sess_job_search"]["order_sql"],$_SESSION["sess_job_search"]["from_count"],$_SESSION["sess_job_search"]["row_count"],$job_list,$adv_job_list);
		$job_search_params["error_code"] = "";
		$result = true;
		return 1;
	}
 return 0;
}

//Search result stats: Jobs 1 - 10 of 519 for hotel within New York
function get_search_results_stats()
{
 global $text_info, $radius_array;
	//From - To part
	$from = $_SESSION["sess_job_search"]["page_start"] * $_SESSION["sess_job_search"]["row_count"] + 1;
	$to = $from + $_SESSION["sess_job_search"]["row_count"];
	if ($to > $_SESSION["sess_job_search"]["results_count"]) $to = $_SESSION["sess_job_search"]["results_count"];

	//Keyword part
	$keyword = "";
	$jsp = $_SESSION["sess_job_search"]["job_search_params"];
	if ($jsp["what"] != "") $keyword .= ' '.$jsp["what"];
	if ($jsp["as_all"] != "") $keyword .= ' '.$jsp["as_all"];
	if ($jsp["as_phrase"] != "") $keyword .= ' "'.$jsp["as_phrase"].'"';
	if ($jsp["as_any"] != "") {
		$lst = explode(" ",$jsp["as_any"]);
		$keyword .= (count($lst) > 1) ? '('.implode(" or ",$lst).')' : $jsp["as_any"];
	}
	if ($jsp["as_not"] != "") {
		$lst = explode(" ",$jsp["as_not"]);
		$keyword .= (count($lst) > 1) ? '('.implode(" -",$lst).')' : '-'.$jsp["as_not"];
	}

	//Radius part
	$radius = "";
	//Cron jobs alert
	if (isset($_SESSION["sess_job_search"]["add_current_search_info"]) && ($_SESSION["sess_job_search"]["add_current_search_info"] == "cron_job_alert")) {
		if ($jsp["radius"] != "") {
			foreach ($radius_array as $k=>$v)
			{
				if ($jsp["radius"] == $k) {
					$radius = ''.$v["caption"].'';
					break;
				}
			}
		}
	}
	else {
		if ($jsp["radius"] != "") {
			if ($_SESSION["globsettings"]["allow_cities_in_db"]) {
				$radius .= '<form id="select_form" action="" method="POST" style="margin:0;padding:0;display:inline">';
				$radius .= '<select id="select_select" name="radius" onChange="var sf = get_element(\'select_form\'); sf.action=get_element(\'select_select\')[get_element(\'select_select\').selectedIndex].value; sf.submit();">';
				foreach ($radius_array as $k=>$v)
				{
					$selected = ($jsp["radius"] == $k) ? ' selected' : '';
					$radius .= '<option value="?'.search_params_url_select($k).'"'.$selected.'>'.$v["caption"].'</option>';
				}
				$radius .= '</select></form>';
			}
			else {
				$radius = $text_info["p_in"];
			}
		}
	}

	//Within part
	$within = "";
	if ($jsp["where"] != "") $within = ($radius == "") ? ' within '.$jsp["where"].'' : ' '.$jsp["where"].'';

	//Create line
	$tmp = str_replace("{*Of*}", $_SESSION["sess_job_search"]["results_count"], $text_info["p_search_results_stats"]);
	$tmp = str_replace("{*FromTo*}", $from." - ".$to, $tmp);
	$tmp = str_replace("{*Keywords*}", $keyword, $tmp);
	if ($within == "") {
		if (isset($jsp["job_where_locations"])) $within = get_viewers_location_city();
		if ($within == "") $radius = "";
		else $within = ''.$within.'';
	}
	$tmp = str_replace("{*within*}", $within, $tmp);
	$tmp = str_replace("{*radius*}", $radius, $tmp);
 return $tmp;
}

//Return Jobs categories ID list
function get_cat_id_list($sql)
{
 global $db_tables,$text_info;
	//Cache settings
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "homepage_template_values",
		"table_name"	=> "jobcategories",
		"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 46*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);
	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get values list
		$data_array[0] = 0;
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = $myrow["cat_id"];
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);
 return $data_array;
}

//Gategories links on search result page: Categories: All, Hospitality, Sales, More..
function get_categories_links_on_search_page()
{
 global $text_info, $SLINE;

	function swap_selected_category_with_first_free()
	{
	 global $categories_list;
		if (count($_SESSION["sess_job_search"]["cat_id_list"]) < 3) return;
		if ($_SESSION["sess_job_search"]["job_search_params"]["jobs_category"] == 0) return;
		for ($i=1; $i<count($_SESSION["sess_job_search"]["cat_id_list"]); $i++)
		{
			if ($_SESSION["sess_job_search"]["cat_id_list"][$i] == $_SESSION["sess_job_search"]["job_search_params"]["jobs_category"]) {
				$tmp = $_SESSION["sess_job_search"]["cat_id_list"][1];
				$_SESSION["sess_job_search"]["cat_id_list"][1] = $_SESSION["sess_job_search"]["cat_id_list"][$i];
				$_SESSION["sess_job_search"]["cat_id_list"][$i] = $tmp;
			}
		}
	}

	$tmp = '<div style="float: left;">'.$text_info["p_categories"].'&nbsp;';
	$k = 0; $add_more = $block_more = false;
	$categories_list = get_jobcategories_list();
	swap_selected_category_with_first_free();
	for ($i=0; $i<count($_SESSION["sess_job_search"]["cat_id_list"]); $i++)
	{
		$jobs_category = $_SESSION["sess_job_search"]["cat_id_list"][$i];
		if (!isset($categories_list[$jobs_category])) continue;
		if ($add_more && !$block_more) {
			$tmp .= '</div><div id="more_categories_link" style="display:inline;padding-top:2px;"><a class="simple_link" href="javascript:show_hide_categories(1);">More...</a></div><div id="more_categories_list" style="display:none;">';
			$block_more = true;
		}
		if ($k > 1) $add_more = true;
		if ($jobs_category == $_SESSION["sess_job_search"]["job_search_params"]["jobs_category"])
			$tmp .= '<span class="selected_category">'.$categories_list[$jobs_category]["cat_name"].'</span>&nbsp;&nbsp;';
		else
			$tmp .= '<a class="simple_link" href="./?'.search_params_url($jobs_category).'">'.$categories_list[$jobs_category]["cat_name"].'</a>&nbsp;&nbsp;';
		$k++;
	}
	if ($add_more && $block_more)	$tmp .= '<a class="simple_link" href="javascript:show_hide_categories(2)">Fewer...</a>';
	$tmp .= "</div>";
//	return str_replace("{*Categories*}", $tmp, $text_info["p_categories_links"]);
	return $tmp;
}

//Shows jobs per page buttons
function get_jobs_per_page_buttons()
{
 global $text_info, $SLINE, $number_results_array;
	$tmp = ''; $cnt = 0;
	foreach ($number_results_array as $k=>$v)
	{
		$cnt++;
		$separator = ($cnt != count($number_results_array)) ? '|' : '';
		if ($k == $_SESSION["sess_job_search"]["job_search_params"]["number_results"])
			$tmp .= '<span class="selected_category">'.$v["caption"].'</span>&nbsp;'.$separator.'&nbsp;';
		else
			$tmp .= '<a class="simple_link2" href="./?'.search_params_url("",$k).'">'.$v["caption"].'</a>&nbsp;'.$separator.'&nbsp;';
	}
	return str_replace("{*jobs_per_page*}", $tmp,	$text_info["p_jobs_per_page"]);
}


// * * * * * * * * * *//
// Filter job search  //
// * * * * * * * * * *//

function get_jobs_filter_params()
{
 return $_SESSION["sess_job_search"]["job_filter_params"];
}

function this_filter_present($filter_name)
{
	if (isset($_SESSION["sess_job_search"]["search_filter"][$filter_name]) && ($_SESSION["sess_job_search"]["search_filter"][$filter_name] != "")) return 1;
	else return 0;
}

function show_filter_block($filter_name, $show_val)
{
	$_SESSION["sess_job_search"]["job_filter_params"][$filter_name.'_show'] = $show_val;
}

//Create left side (job filters)
function exec_jobfilters_search($sql)
{
	//Execute job filter SQL (d) Company)
	if (this_filter_present("company_name")) show_filter_block("company_name", 0);
	else {
		exec_common_jobfilters_search($sql." and company_name<>'' and company_name<>'no' ","company_name","company_name_count","company_link","company_name","company");
		show_filter_block("company_name", 1);
	}
	//Execute job filter SQL (e) Job Title)
	if (this_filter_present("title")) show_filter_block("title", 0);
	else {
		exec_common_jobfilters_search($sql,"title","title_count","title_link","title","title");
		show_filter_block("title", 1);
	}
	//Execute job filter SQL (f) Location)
	if (this_filter_present("locId") || this_filter_present("locCity")) show_filter_block("locId", 0);
	else {
		exec_location_jobfilters_search($sql);
		show_filter_block("locId", 1);
	}
	//Execute job filter SQL (g) Job Type)
	if (this_filter_present("job_type")) show_filter_block("job_type", 0);
	else {
		exec_job_type_jobfilters_search($sql);
		show_filter_block("job_type", 1);
	}
	//Execute job filter SQL (h) Salary Estimate)
	if (this_filter_present("salary")) show_filter_block("salary", 0);
	else {
		exec_salary_jobfilters_search($sql);
		show_filter_block("salary", 1);
	}
	//Execute job filter SQL (i) Employer/Recruiter)
	if (this_filter_present("isstaffing_agencies")) show_filter_block("isstaffing_agencies", 0);
	else {
		exec_isstaffing_agencies_jobfilters_search($sql);
		show_filter_block("isstaffing_agencies", 1);
	}
	//Execute job filter SQL (j) Recent Job Searches)
	exec_recent_job_searches_jobfilters_search();
}

function corect_filter_name_length($str)
{
	if (strlen($str) > 32) return substr($str,0,32);
	else return $str;
}

//Execute job filter SQL * * *
function exec_common_jobfilters_search($sql,$fcaption,$fcount,$flink,$filter_name,$farray_name)
{
  global $db_tables,$SLINE;
	$sql = "SELECT d.{$fcaption}, count(d.{$fcaption}) as $fcount ".
				"FROM ".$db_tables["data_list"]." d ".
				"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".$sql.get_country_limit_by_loc_search()." GROUP BY {$fcaption} ".
				"ORDER BY $fcount DESC LIMIT 15";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "data_list",
		"params_list"	=> array("filter"), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 10*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array($fcaption=>corect_filter_name_length($myrow[$fcaption]), $fcount=>$myrow[$fcount],
				$flink=>"./?change-filter=set&filter-value=".urlencode($myrow[$fcaption]).'&filter-caption='.urlencode($myrow[$fcaption])
			);
		}
	}
	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

	//Add special for this process data
	add_special_for_this_proc_data($data_array,$flink,'&filter_name='.$filter_name.'&'.$SLINE."&".search_params_url());

	$_SESSION["sess_job_search"]["job_filter_params"][$farray_name] = $data_array;
}

//Execute loaction job filter SQL * * *
function exec_location_jobfilters_search($sql)
{
  global $db_tables,$SLINE;
	//Get data from DB locations (city table)
	$qsql = "SELECT c.locId, c.country, c.region, c.city, count(c.locId) as locIdcount, r.name as regionname ".
				"FROM ".$db_tables["data_list"]." d ".
				"INNER JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".
				"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region and c.country=r.country ".
				$sql.get_country_limit(" and c.country=")." GROUP BY c.locId ".
				"ORDER BY locIdcount DESC LIMIT 15";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "data_list",
		"params_list"	=> array("filter"), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $qsql,
		"actual_time"	=> 10*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array1 = array();
	if (!read_mydata_cache($cache_params_array,$data_array1)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array1[$myrow["locId"]] = array("country"=>$myrow["country"], "region"=>$myrow["region"], "regionname"=>$myrow["regionname"], "city"=>corect_filter_name_length($myrow["city"]),
					"locIdcount"=>$myrow["locIdcount"], "location_link"=>"./?change-filter=set&filter-name=locId&filter-value=".urlencode($myrow["locId"]).'&filter-caption='.urlencode($myrow["country"].', '.$myrow["region"].', '.$myrow["city"])
			);
		}
	}

	//Get data from text locations (data_list table)
	$sql = str_replace("c.city", "d.city", $sql);
	$sql = str_replace("c.region", "d.region", $sql);
	$sql = str_replace("c.postalCode", "d.region", $sql);
	$qsql = "SELECT d.country, d.region, d.city, count(d.city) as locIdcount ".
				"FROM ".$db_tables["data_list"]." d ".
				$sql.get_country_limit(" and d.country=")." and d.city<>'' GROUP BY d.city ".
				"ORDER BY locIdcount DESC LIMIT 15";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "data_list",
		"params_list"	=> array("filter"), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $qsql,
		"actual_time"	=> 10*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array2 = array();
	if (!read_mydata_cache($cache_params_array,$data_array2)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array2[$myrow["city"]] = array("country"=>$myrow["country"], "region"=>$myrow["region"], "regionname"=>$myrow["region"], "city"=>corect_filter_name_length($myrow["city"]),
					"locIdcount"=>$myrow["locIdcount"], "location_link"=>"./?change-filter=set&filter-name=locCity&filter-value=".urlencode($myrow["city"]).'&filter-caption='.urlencode($myrow["country"].', '.$myrow["region"].', '.$myrow["city"])
			);
		}
	}

	$data_array_old = array_merge($data_array1,$data_array2);

	if (count($data_array_old) > 0) {
		$data_array_new = $tmp = array();
		foreach($data_array_old as $k=>$v)
		{
			$tmp[$k] = $v["locIdcount"];
		}
		arsort($tmp);
		foreach ($tmp as $k=>$v)
		{
		  $data_array_new[$k] = $data_array_old[$k];
		}
	}
	else {
		$data_array_new = array();
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array_new);

	//Add special for this process data
	add_special_for_this_proc_data($data_array_new,"location_link",'&'.$SLINE."&".search_params_url());

	$_SESSION["sess_job_search"]["job_filter_params"]["locId"] = $data_array_new;
}

function locIdcmp($a, $b)
{
//print_r($a); print_r($b);
	if ($a["locIdcount"] == $b["locIdcount"]) return 0;
	return ($a["locIdcount"] > $b["locIdcount"]) ? -1 : 1;
}

function exec_job_type_jobfilters_search($sql)
{
  global $db_tables,$jobs_type_array,$SLINE;
	$sql = "SELECT d.job_type, count(d.job_type) as job_type_count ".
				"FROM ".$db_tables["data_list"]." d ".
				"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".
				"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region and c.country=r.country ".
				$sql.get_country_limit_by_loc_search()." GROUP BY job_type ".
				"ORDER BY job_type_count LIMIT 15";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "data_list",
		"params_list"	=> array("filter"), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 10*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("job_type"=>$myrow["job_type"], "job_caption"=>$jobs_type_array[$myrow["job_type"]]["caption"], "job_type_count"=>$myrow["job_type_count"],
				"job_type_link"=>"./?change-filter=set&filter-name=job_type&filter-value=".urlencode($myrow["job_type"]).'&filter-caption='.urlencode($myrow["job_type"])
			);
		}
	}
	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

	//Add special for this process data
	add_special_for_this_proc_data($data_array,"job_type_link",'&'.$SLINE.'&'.search_params_url());

	$_SESSION["sess_job_search"]["job_filter_params"]["job_type"] = $data_array;
}

//Execute salary job filter SQL * * *
function exec_salary_jobfilters_search($sql)
{
  global $db_tables;
	$salary_data = array();

	//$40,000+
	$salary_data["40,000+"] = exec_subX_salary_jobfilters_search($sql,"salary_count",40000,"40,000+");
	//$60,000+
	$salary_data["60,000+"] = exec_subX_salary_jobfilters_search($sql,"salary_count",60000,"60,000+");
	//$80,000+
	$salary_data["80,000+"] = exec_subX_salary_jobfilters_search($sql,"salary_count",80000,"80,000+");
	//$100,000+
	$salary_data["100,000+"] = exec_subX_salary_jobfilters_search($sql,"salary_count",100000,"100,000+");
	//$120,000+
	$salary_data["120,000+"] = exec_subX_salary_jobfilters_search($sql,"salary_count",120000,"120,000+");

	$_SESSION["sess_job_search"]["job_filter_params"]["salary"] = $salary_data;
}

//Execute SUB salary job filter SQL (X,000+)* * *
function exec_subX_salary_jobfilters_search($sql,$fcount,$salaryN,$salaryC)
{
  global $db_tables,$SLINE;

	$sql = "SELECT count(*) as $fcount ".
				"FROM ".$db_tables["data_list"]." d ".
				"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".$sql.get_country_limit_by_loc_search()." and d.salary>={$salaryN}";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "data_list",
		"params_list"	=> array("filter"), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 10*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array($fcount=>$myrow[$fcount],
				"salary_link"=>"./?change-filter=set&filter-name=salary"
			);
		}
	}
	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

	//Add special for this process data
	add_special_for_this_proc_data($data_array,"salary_link",'&filter_value='.urlencode($salaryN).'&filter_caption='.urlencode($salaryC).'&'.$SLINE.'&'.search_params_url());

 return $data_array;
}

//Execute Employer/Recruiter filter SQL * * *
function exec_isstaffing_agencies_jobfilters_search($sql)
{
  global $db_tables,$text_info;
	$staffing_data = array();

	$staffing_data[$text_info["p_employer"]] = exec_sub_isstaffing_agencies_jobfilters_search($sql,0,$text_info["p_employer"]);
	$staffing_data[$text_info["p_recruiter"]] = exec_sub_isstaffing_agencies_jobfilters_search($sql,1,$text_info["p_recruiter"]);

	$_SESSION["sess_job_search"]["job_filter_params"]["employer_recruiter"] = $staffing_data;
}

//Execute SUB Employer/Recruiter filter SQL * * *
function exec_sub_isstaffing_agencies_jobfilters_search($sql,$staff,$staffC)
{
  global $db_tables,$SLINE;

	$sql = "SELECT count(*) as employer_recruiter_count ".
				"FROM ".$db_tables["data_list"]." d ".
				"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".$sql.get_country_limit_by_loc_search()." and d.isstaffing_agencies=$staff";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "data_list",
		"params_list"	=> array("filter"), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 10*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("employer_recruiter_count"=>$myrow["employer_recruiter_count"],
				"employer_recruiter_link"=>"./?change-filter=set&filter-name=isstaffing_agencies"
			);
		}
	}
	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

	//Add special for this process data
	add_special_for_this_proc_data($data_array,"employer_recruiter_link",'&filter_value='.urlencode($staff).'&filter_caption='.urlencode($staffC).'&'.$SLINE.'&'.search_params_url());

 return $data_array;
}

//Execute Recent Job Searches filter: get info from cookie * * *
function exec_recent_job_searches_jobfilters_search()
{
 global $full_search;
	$_SESSION["sess_job_search"]["job_filter_params"]["recent_job_searches"] = array();
	$_SESSION["sess_job_search"]["job_filter_params"]["recent_job_searches_show"] = false;
	if (isset($_COOKIE["RecentJobSearchesTitle"])) {
		$recent_job_searches = array();
		for($i=0; $i<count($_COOKIE["RecentJobSearchesTitle"]); $i++)
		{
			$recent_job_searches[$i]["title"] = $_COOKIE["RecentJobSearchesTitle"][$i];
			$recent_job_searches[$i]["link"] = $_COOKIE["RecentJobSearchesLink"][$i];
		}
		$_SESSION["sess_job_search"]["job_filter_params"]["recent_job_searches"] = $recent_job_searches;
		$_SESSION["sess_job_search"]["job_filter_params"]["recent_job_searches_show"] = true;
	}
	if ($full_search) create_line_for_cookie();
}

function create_line_for_cookie()
{
 global $job_search_params,$text_info,$usersettings;
	$search_line = '';
	if ($job_search_params["search_type"] == "simple") {
		if ($job_search_params["what"] != "") $search_line .= $job_search_params["what"];
		if ( ($job_search_params["where"] != "") && (strlen($search_line) > 0) ) $search_line .= ' in '.$job_search_params["where"].' ';
		else $search_line .= $job_search_params["where"].' ';
	}
	else {
		if ($job_search_params["as_all"] != "") $search_line .= $job_search_params["as_all"].' ';
		if ($job_search_params["as_phrase"] != "") $search_line .= $job_search_params["as_phrase"].' ';
		if ($job_search_params["as_any"] != "") {
			$lst = explode(" ",$job_search_params["as_any"]);
			$search_line .= (count($lst) > 1) ? '('.implode(" or ",$lst).') ' : $job_search_params["as_any"].' ';
		}
		if ($job_search_params["as_not"] != "") {
			$lst = explode(" ",$job_search_params["as_not"]);
			if (count($lst) > 1) {
				$lst[0] = '-'.$lst[0]; $search_line .= '('.implode(" -",$lst).') ';
			}
			else $search_line .= '-'.$job_search_params["as_not"].' ';
		}
		if ($job_search_params["as_title"] != "") $search_line .= $text_info["p_title"].': '.$job_search_params["as_title"].' ';
		if ($job_search_params["as_company"] != "") $search_line .= $text_info["p_company"].': '.$job_search_params["as_company"].' ';
		if (($job_search_params["radius"] != "") || ($job_search_params["radius"] != 0)) $search_line .= $text_info["p_radius"].': '.$job_search_params["radius"].' ';
		if (($job_search_params["jobs_category"] != "") || ($job_search_params["jobs_category"] != 0)) {
			$categories_list = get_jobcategories_list();
			$search_line .= $text_info["p_category"].': '.$categories_list[$job_search_params["jobs_category"]]["cat_name"].' ';
		}
		if ($job_search_params["jobs_type"] != "") $search_line .= $text_info["p_job_type"].': '.$job_search_params["jobs_type"].' ';
		if ($job_search_params["jobs_from"] != "") $search_line .= $text_info["p_job_from"].': '.$job_search_params["jobs_from"].' ';
		if ($job_search_params["norecruiters"] == 1) $search_line .= $text_info["p_exclude_staffing_agencies"].' ';
		if ($job_search_params["salary"] != "") $search_line .= $text_info["p_salary"].': '.$job_search_params["salary"].' ';
		if ($job_search_params["jobs_published"] != "any") $search_line .= $text_info["p_job_published"].': '.$job_search_params["jobs_published"].' ';
	}
	//Create first cookie
	if (!isset($_COOKIE["RecentJobSearchesTitle"])) {
		setcookie("RecentJobSearchesTitle[0]", $search_line, time() + $usersettings["recent_job_searches_cookie_time"]);
		setcookie("RecentJobSearchesLink[0]", './?'.search_params_url().'&change-filter=remove_all&filter-name=no&filter-value=no&filter-caption=no', time() + $usersettings["recent_job_searches_cookie_time"]);
	}
	//Create next cookie
	else {
		$cnt = count($_COOKIE["RecentJobSearchesTitle"]);
		//Try to find this search in cookie
		for($i=0; $i<8; $i++)
		{
			if (!isset($_COOKIE["RecentJobSearchesTitle"][$i])) break;
			if ($_COOKIE["RecentJobSearchesTitle"][$i] == $search_line) return; //! Exit: we found this search in cookie already
		}
		//Add new cookie
		if ($cnt < 8) {
			setcookie("RecentJobSearchesTitle[$cnt]", "", time() + $usersettings["recent_job_searches_cookie_time"]);
			setcookie("RecentJobSearchesLink[$cnt]", "", time() + $usersettings["recent_job_searches_cookie_time"]);
		}
		for($i=$cnt; $i>0; $i--)
		{
			setcookie("RecentJobSearchesTitle[{$i}]", $_COOKIE["RecentJobSearchesTitle"][$i-1], time() + $usersettings["recent_job_searches_cookie_time"]);
			setcookie("RecentJobSearchesLink[{$i}]", $_COOKIE["RecentJobSearchesLink"][$i-1], time() + $usersettings["recent_job_searches_cookie_time"]);
		}
		setcookie("RecentJobSearchesTitle[0]", $search_line, time() + $usersettings["recent_job_searches_cookie_time"]);
		setcookie("RecentJobSearchesLink[0]", './?'.search_params_url().'&change-filter=remove_all&filter-name=no&filter-value=no&filter-caption=no', time() + $usersettings["recent_job_searches_cookie_time"]);
		//Remove coookie more then 8
		for($i=$cnt; $i>=8; $i--)
		{
			setcookie("RecentJobSearchesTitle[{$i}]", "", time() - 3600);
			setcookie("RecentJobSearchesLink[{$i}]", "", time() - 3600);
		}
	}
}


function filter_work()
{
	$change_filter	= get_get_post_value("change_filter","");				//change_filter - use filter in search
	$filter_value		= html_chars(get_get_post_value("filter_value",""));	//filter_value - filter value
	if (($change_filter != "set") && ($change_filter != "remove") && ($change_filter != "remove_all")) return 0;
 return 1;
}

function correct_values_using_filter()
{
	$change_filter	= get_get_post_value("change_filter","");				//change_filter - use filter in search
	$filter_name		= html_chars(get_get_post_value("filter_name",""));	//filter_name - filter value
	$filter_value		= html_chars(get_get_post_value("filter_value",""));	//filter_value - filter value
	$filter_caption	= html_chars(get_get_post_value("filter_caption",""));	//filter_caption - filter caption
	if ( ($filter_name == "") || ($filter_value == "") || ($filter_caption == "") ) return;
	switch ($change_filter) {
		case "set":
				$_SESSION["sess_job_search"]["search_filter"][$filter_name] = $filter_value;
				$_SESSION["sess_job_search"]["search_filter_captions"][$filter_name] = $filter_caption;
			break;
		case "remove":
				if (isset($_SESSION["sess_job_search"]["search_filter"][$filter_name])) {
					$_SESSION["sess_job_search"]["search_filter"][$filter_name] = "";
					$_SESSION["sess_job_search"]["search_filter_captions"][$filter_name] = "";
					unset($_SESSION["sess_job_search"]["search_filter"][$filter_name]);
					unset($_SESSION["sess_job_search"]["search_filter_captions"][$filter_name]);
				}
			break;
		case "remove_all":
				if (isset($_SESSION["sess_job_search"]["search_filter"]) && is_array($_SESSION["sess_job_search"]["search_filter"])) {
					$_SESSION["sess_job_search"]["search_filter"] = array();
					$_SESSION["sess_job_search"]["search_filter_captions"] = array();
					unset($_SESSION["sess_job_search"]["search_filter"]);
					unset($_SESSION["sess_job_search"]["search_filter_captions"]);
				}
			break;
	}
}

function get_jobs_jobfilteredby_params()
{
 global $SLINE;
	$search_filter = array();
	if (isset($_SESSION["sess_job_search"]["search_filter"]) && is_array($_SESSION["sess_job_search"]["search_filter"])) {
		foreach ($_SESSION["sess_job_search"]["search_filter"] as $nm=>$val)
		{
			$search_filter[] = array(
				"filter_caption"=>$_SESSION["sess_job_search"]["search_filter_captions"][$nm],
				"filter_undolink"=>"./?change-filter=remove&filter-name={$nm}&filter-value=".urlencode($val).'&filter-caption='.urlencode($val).'&'.$SLINE."&".search_params_url(),
				"filter_undoalllinks"=>"./?change-filter=remove_all&filter-name=no&filter-value=no&filter-caption=no&".$SLINE."&".search_params_url(),
			);
		}
	}
 return $search_filter;
}


// * * * * * * * * * * * * * //
// Advertiser Ads by search  //
// * * * * * * * * * * * * * //

function get_jobs_keyword_ads()
{
 global $db_tables,$SLINE;
	if (isset($_SESSION["sess_job_search"]["keywordads_list"]) && is_array($_SESSION["sess_job_search"]["keywordads_list"])) {
		//Create shown ads list
		return create_shownads_list();
	}

	$keywords = get_keywords_list_from_visitor_enter();
	$phrase_keywords = get_phrase_keywords_from_visitor_enter();

	/* a) SELECT All positive keyword s*/
	$sql = "";
	//Keywords list
	for ($i=0; $i<count($keywords); $i++)
	{
		if ( ($keywords[$i] == "") || ($keywords[$i] == " ") )continue;
		$sql .= "(keyword like '%{$keywords[$i]}%' and soptions=1) or (keyword='{$keywords[$i]}' and soptions=2) or ";
	}
	//Phrase list
	for ($i=0; $i<count($phrase_keywords); $i++)
	{
		if ( ($phrase_keywords[$i] == "") || ($phrase_keywords[$i] == " ") ) continue;
		$sql .= "(keyword='{$phrase_keywords[$i]}' and soptions=3) or ";
	}

	if (strlen($sql) > 0) $sql = substr($sql, 0, -3);

	$positive_keywords_array = array();
	if (strlen($sql) > 0) {
/*	$sql = "SELECT kads_id,ad_id ".
					"FROM ".$db_tables["keyword_ads"]." ".
					"WHERE ad_status=1 and kads_status=1 and (".$sql.")";*/
		$sql = "SELECT ka.kads_id,ka.ad_id, a.daily_budget,a.monthly_budget,a.uid_adv,ua.balance, ".
				"IFNULL((SELECT sum(dsc.cost) FROM ".$db_tables["stats_adv_click_keywords"]." dsc ".
				"          WHERE ka.kads_id=dsc.kads_id and ka.ad_id=dsc.ad_id and dsc.actiontime>CURDATE() ".
				"       ),-1) as daily_cost, ".
				"IFNULL((SELECT sum(msc.cost) FROM ".$db_tables["stats_adv_click_keywords"]." msc ".
				"          WHERE ka.kads_id=msc.kads_id and ka.ad_id=msc.ad_id and TO_DAYS(msc.actiontime)>=TO_DAYS(DATE_FORMAT(CURDATE(),\"%Y-%m-01\")) ".
				"       ),-1) as monthly_cost ".
				"FROM ".$db_tables["keyword_ads"]." ka ".
				"LEFT JOIN ".$db_tables["ads"]." a ON ka.ad_id=a.ad_id ".
				"LEFT JOIN ".$db_tables["users_advertiser"]." ua ON a.uid_adv=ua.uid_adv and ua.balance>=0.0 and ua.isenable=1 and ua.isconfirmed=1 and ua.isdeleted=0 ".
				"WHERE ka.ad_status=1 and ka.kads_status=1 and  (".$sql.") ".
				"HAVING a.daily_budget>daily_cost and a.monthly_budget>monthly_cost ";

		// * * Check cache * * //
		$cache_params_array = array(
			"user"				=> 3, //$_SESSION["sess_user"]
			"cache_group"	=> "smarty_frontend",
			"userid"			=> 0, //$_SESSION["sess_userid"]
			"section"			=> "search_result",
			"table_name"	=> "keyword_ads",
			"params_list"	=> array("ads"), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
			"query"				=> $sql,
			"actual_time"	=> 5*60, //Время актуальности в сек.
			"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
		);
  
		// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
		if (!read_mydata_cache($cache_params_array,$positive_keywords_array)) {
			//Get global settings list
			$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
			while ($myrow = mysql_fetch_array($qr_res))
			{
				if (isset($myrow["balance"]) && check_float($myrow["balance"]) && ($myrow["balance"] > 0))
					$positive_keywords_array[] = array("kads_id"=>$myrow["kads_id"], "ad_id"=>$myrow["ad_id"]);
			}
		}
		// * * Write cache * * //
		//if use cache - save data
		write_mydata_cache($cache_params_array,$positive_keywords_array);
	}


	/* b) SELECT All negative keywords */
	$sql = "";
	//Keywords list
	for ($i=0; $i<count($keywords); $i++)
	{
		if ( ($keywords[$i] == "") || ($keywords[$i] == " ") ) continue;
		$sql .= "(keyword='{$keywords[$i]}' and soptions=4) or ";
	}

	if (strlen($sql) > 0) $sql = substr($sql, 0, -3);

	$negative_keywords_array = array();
	if (strlen($sql) > 0) {
		$sql = "SELECT DISTINCT ad_id ".
					"FROM ".$db_tables["keyword_ads"]." ".
					"WHERE ad_status=1 and kads_status=1 and (".$sql.")";
  
		// * * Check cache * * //
		$cache_params_array = array(
			"user"				=> 3, //$_SESSION["sess_user"]
			"cache_group"	=> "smarty_frontend",
			"userid"			=> 0, //$_SESSION["sess_userid"]
			"section"			=> "search_result",
			"table_name"	=> "keyword_ads",
			"params_list"	=> array("ads"), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
			"query"				=> $sql,
			"actual_time"	=> 5*60, //Время актуальности в сек.
			"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
		);
  
		// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
		if (!read_mydata_cache($cache_params_array,$negative_keywords_array)) {
			//Get global settings list
			$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
			while ($myrow = mysql_fetch_array($qr_res))
			{
				$negative_keywords_array[$myrow["ad_id"]] = array("1"=>"1");
			}
		}
		// * * Write cache * * //
		//if use cache - save data
		write_mydata_cache($cache_params_array,$negative_keywords_array);
	}


	/* b) SELECT possible keywords */
	$possible_keywords_array = array();
	for($i=0; $i<count($positive_keywords_array); $i++)
	{
		if (isset($negative_keywords_array[$positive_keywords_array[$i]["ad_id"]])) continue;
		$possible_keywords_array[] = $positive_keywords_array[$i];
	}
	$_SESSION["sess_job_search"]["possible_keywords"] = $possible_keywords_array;

	/* c) Get possible keywords info from DB */
	$sql = "";
	//Keywords list
	for ($i=0; $i<count($possible_keywords_array); $i++)
	{
		$sql .= "ad_id='{$possible_keywords_array[$i]["ad_id"]}' or ";
	}

	if (strlen($sql) > 0) $sql = substr($sql, 0, -3);

	$keywordads_array = array();
	if (strlen($sql) > 0) {
		$sql = "SELECT * ".
					"FROM ".$db_tables["ads"]." ".
					"WHERE status=1 and (".$sql.")";
  
		// * * Check cache * * //
		$cache_params_array = array(
			"user"				=> 3, //$_SESSION["sess_user"]
			"cache_group"	=> "smarty_frontend",
			"userid"			=> 0, //$_SESSION["sess_userid"]
			"section"			=> "search_result",
			"table_name"	=> "keyword_ads",
			"params_list"	=> array("ads"), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
			"query"				=> $sql,
			"actual_time"	=> 5*60, //Время актуальности в сек.
			"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
		);
  
		// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
		if (!read_mydata_cache($cache_params_array,$keywordads_array)) {
			//Get global settings list
			$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
			while ($myrow = mysql_fetch_array($qr_res))
			{
				$keywordads_array[] = array("headline"=>$myrow["headline"], "line_1"=>$myrow["line_1"], "line_2"=>$myrow["line_2"],
					"display_url"=>$myrow["display_url"], "destination_url"=>$myrow["destination_url"], "max_cpc"=>$myrow["max_cpc"],
					"daily_budget"=>$myrow["daily_budget"], "ad_id"=>$myrow["ad_id"], "uid_adv"=>$myrow["uid_adv"],
					"clickurl"=>$_SESSION["globsettings"]["site_url"]."job-details/?ad-id=".$myrow["ad_id"]);
			}
		}
  
		// * * Write cache * * //
		//if use cache - save data
		write_mydata_cache($cache_params_array,$keywordads_array);

		//Add special for this process data
		add_special_for_this_proc_data($keywordads_array,"clickurl",'&'.$SLINE.get_sskvalues('&'));
	}

	//Sort keywordads
	usort($keywordads_array, "adscmp");
  $_SESSION["sess_job_search"]["keywordads_list"] = $keywordads_array;

	//Set vales for adv statistic
	set_sess_for_stats_keyword($keywordads_array,$positive_keywords_array);

	//Insert statistic info ()
	set_stats_adv_maybe_pageview_keywords();

	//Create shown ads list
	return create_shownads_list();
}

//Sort keywordads desc by max_cpc
function adscmp($a, $b) {
	if ($a["max_cpc"] == $b["max_cpc"]) return 0;
	return ($a["max_cpc"] > $b["max_cpc"]) ? -1 : 1;
}

function create_shownads_list()
{
	//Create shownads
	if (!isset($_SESSION["sess_job_search"]["shownads_list"])) $_SESSION["sess_job_search"]["shownads_list"] = array();
	//Create list of ads for current page
	$this_page_show_ads_list = array();
	create_this_page_show_ads_list($this_page_show_ads_list);

	$_SESSION["sess_job_search"]["stats_keywordads_list_page"] = $this_page_show_ads_list;

	//Insert statistic info (stats_adv_pageview_keywords)
	set_stats_adv_pageview_keywords($this_page_show_ads_list);

 return $this_page_show_ads_list;
}

//Create list of ads for current page
function create_this_page_show_ads_list(&$this_page_show_ads_list)
{
 global $AdsCnt;
	$i = 1;
	if (count($_SESSION["sess_job_search"]["keywordads_list"]) == count($_SESSION["sess_job_search"]["shownads_list"])) $_SESSION["sess_job_search"]["shownads_list"] = array();
	//create this page ads list
	foreach ($_SESSION["sess_job_search"]["keywordads_list"] as $k=>$v)
	{
		if (isset($_SESSION["sess_job_search"]["shownads_list"][$k])) continue;
		$this_page_show_ads_list[$k] = $v;
		$_SESSION["sess_job_search"]["shownads_list"][$k] = 1;
		if ($i > $AdsCnt) break;
		$i++;
	}
	//try to find another ads... with the same max_cpc
	if ( ($i > $AdsCnt) && (count($this_page_show_ads_list) > 0) ) {
		//get last element
		end($this_page_show_ads_list);
		//last elem key
		$last_show_ads_key = key($this_page_show_ads_list);
		//last elem value
		$last_show_ads = $this_page_show_ads_list[$last_show_ads_key];
		//competitor list
		$concurent_ads_list = array();
		//fill it
		foreach ($_SESSION["sess_job_search"]["keywordads_list"] as $k=>$v)
		{
			if (isset($_SESSION["sess_job_search"]["shownads_list"][$k])) continue;
			if (isset($this_page_show_ads_list[$k])) continue;
			if ($v["max_cpc"] == $last_show_ads["max_cpc"]) $concurent_ads_list[] = $k;
		}
		//selet one from competitor (if we have them)
		if (count($concurent_ads_list) > 0) {
			$concurent_ads_list[] = $last_show_ads_key;
			$rnd = rand(0,count($concurent_ads_list)-1);
			$rnd_key = $concurent_ads_list[$rnd];
			//select random
			$this_page_show_ads_list[$last_show_ads_key] = $_SESSION["sess_job_search"]["keywordads_list"][$rnd_key];
			$_SESSION["sess_job_search"]["shownads_list"][$rnd_key] = 1;
		}
	}
	//set smallest max_cpc for all keywords
	if (count($this_page_show_ads_list) > 0) {
		end($this_page_show_ads_list);
		$min_cpc_key = key($this_page_show_ads_list);
		$min_cpc = $this_page_show_ads_list[$min_cpc_key]["max_cpc"];
		foreach ($this_page_show_ads_list as $k=>$v)
		{
			$this_page_show_ads_list[$k]["max_cpc"] = $min_cpc;
		}
	}
}

//Discount click cost for advertiser job
function discount_this_job_adv(&$adv_data_array)
{
	if (count($adv_data_array) == 0) return;
	//set smallest max_cpc for all keywords
	$c = end($adv_data_array);
	$min_cpc = $c["max_cpc"];
	foreach ($adv_data_array as $k=>$v)
	{
		$adv_data_array[$k]["max_cpc"] = $min_cpc;
	}
}

function get_keywords_list_from_visitor_enter()
{
	$keyword_str = "";
	$job_search_params = $_SESSION["sess_job_search"]["job_search_params"];
	if ($job_search_params["search_type"] == "simple") {
		$keyword_str = $job_search_params["what"].' '.$job_search_params["title"].' '.$job_search_params["company_name"].' '.
			$job_search_params["where"];
	}
	elseif ($job_search_params["search_type"] == "advanced") {
		$keyword_str = $job_search_params["title"].' '.$job_search_params["company_name"].' '.$job_search_params["as_all"].' '.
			$job_search_params["as_phrase"].' '.$job_search_params["as_any"].' '.$job_search_params["as_not"].' '.
			$job_search_params["as_title"].' '.$job_search_params["as_company"].' '.$job_search_params["where"];
	}
	$keyword_str = preg_replace("/ +/", " ", $keyword_str);
 return explode(' ',$keyword_str);
}

function get_phrase_keywords_from_visitor_enter()
{
	$phrase_keywords = array();
	$job_search_params = $_SESSION["sess_job_search"]["job_search_params"];
	if ($job_search_params["search_type"] == "simple") {
		$phrase_keywords[] = $job_search_params["what"];
		$phrase_keywords[] = $job_search_params["title"];
		$phrase_keywords[] = $job_search_params["company_name"];
		$phrase_keywords[] = $job_search_params["where"];
	}
	elseif ($job_search_params["search_type"] == "advanced") {
		$phrase_keywords[] = $job_search_params["title"];
		$phrase_keywords[] = $job_search_params["company_name"];
		$phrase_keywords[] = $job_search_params["as_all"];
		$phrase_keywords[] = $job_search_params["as_phrase"];
		$phrase_keywords[] = $job_search_params["as_any"];
		$phrase_keywords[] = $job_search_params["as_company"];
		$phrase_keywords[] = $job_search_params["where"];
	}
 return $phrase_keywords;
}

function get_advertiser_jobs_list_ads($p)
{
 global $adv_job_list,$JobsAdsTopCnt,$JobsAdsBottomCnt;
	if (count($adv_job_list) == 0) return array();
	if ($p == 0) {
		$i_s = 0; $i_e = $JobsAdsTopCnt;
	}
	elseif ($p == 1) {
		$i_s = $JobsAdsTopCnt; $i_e = $JobsAdsTopCnt+$JobsAdsBottomCnt;
	}
	$k = -1; $result = array();
	foreach ($adv_job_list as $kv=>$v)
	{
		$k++;
		if ($k < $i_s) continue;
		if ($k >= $i_e) break;
		$result[$kv] = $v;
	}
	//Insert statistic info (stats_adv_pageview_jobs)
	set_stats_adv_pageview_jobs($result);
 return $result;
}


// * * * * * * * * //
// JobRoll search  //
// * * * * * * * * //

//Do random search: when we have not keyword and location
function do_random_job_search(&$job_search_params,&$rnd_data_array,$rnd_num)
{
  global $db_tables, $jobs_published_default, $jobroll_more_link;
	$result = true;
	//Jobroll more link
	$jobroll_more_link = get_base_site_url().'?fjr=1'.get_jobroll_publisher_id();
	//Prepare new search
	slash_job_values($job_search_params);
	$sql = $show_another_sql = "";
	//Create WHERE part
	$sql .= " 1=1  ";
	if ($job_search_params["search_type"] == "simple") {
		if ($job_search_params["title"] != "") {
			$sql .= "d.title='{$job_search_params["title"]}' and "; //needs for +locations search
		}
		if ($job_search_params["company_name"] != "") {
			$sql .= "d.company_name='{$job_search_params["company_name"]}' and "; //needs for +locations search
		}
		if (($job_search_params["jobs_category"] != "") && ($job_search_params["jobs_category"] != 0)) {
			$sql .= "d.cat_id='{$job_search_params["jobs_category"]}' and ";
		}
	}
	elseif ($job_search_params["search_type"] == "advanced") {
		if ($job_search_params["title"] != "") {
			$sql .= "d.title='{$job_search_params["title"]}' and ";
		}
		if ($job_search_params["company_name"] != "") {
			$sql .= "d.company_name='{$job_search_params["company_name"]}' and ";
		}
		if ($job_search_params["as_company"] != "") {
			$sql .= "/*match_against_part_start*/MATCH(d.company_name) AGAINST ('{$job_search_params["as_company"]}')/*match_against_part_end*/ and ";
		}
		if (($job_search_params["jobs_category"] != "") && ($job_search_params["jobs_category"] != 0)) {
			$sql .= "d.cat_id='{$job_search_params["jobs_category"]}' and ";
		}
		if (($job_search_params["jobs_type"] != "") && ($job_search_params["jobs_type"] != "all")) {
			$sql .= "d.job_type='{$job_search_params["jobs_type"]}' and ";
		}
		if (($job_search_params["jobs_from"] != "") && ($job_search_params["jobs_from"] != "all")) {
			$sql .= "d.site_type='{$job_search_params["jobs_from"]}' and ";
		}
		if ($job_search_params["norecruiters"]) {
			$sql .= "d.isstaffing_agencies=0 and ";
		}
	}
	if ($job_search_params["where"] != "") {
		if (isset($job_search_params["where_jobroll_location"]) && $job_search_params["where_jobroll_location"])
			$sql .= $job_search_params["where"];
		else
			$sql .= " and ".city_search_sql($job_search_params["where"]);
	}
	if (isset($job_search_params["job_country"]) && ($job_search_params["job_country"] != "") && ($job_search_params["job_country"] != "--")) {
		$sql .= "and (c.country='{$job_search_params["job_country"]}' or d.country='{$job_search_params["job_country"]}') and "; //needs for jobroll search
	}
	if (strlen($sql) > 3) $sql = " WHERE ".substr($sql, 0, -4);

	if ($rnd_num < 11) $rnd_sel = 25*$rnd_num;
	else $rnd_sel = $rnd_num + 750;

	$_SESSION["sess_job_search"]["job_search_params"] = $job_search_params;

	//Execute job SQL (data select)
	exec_sqljob_search($sql,"",0,$rnd_sel,$data_array,$adv_data_array,1);

	//Get some random values from selection
	$rnd_sel = count($data_array);
	if ($rnd_sel == 0) return;
	if ($rnd_sel < $rnd_num) $rnd_num = $rnd_sel;
	$i = 0;
	while ($i < $rnd_num)
	{
		$rnd = rand(0,$rnd_sel);
		$j = -1;
		foreach ($data_array as $k=>$v)
		{
			$j++;
			if ($j < $rnd) continue;
			if ($j == $rnd) {
				if (isset($rnd_data_array[$k])) break;
				$rnd_data_array[$k] = $v; $i++; break;
			}
		}
	}
}

function get_viewers_locId()
{
 global $db_tables;
	//Get IP address
	$realip = getip();
	$realip = substr($realip,0,14); 

	$sql = "SELECT locId ".
				"FROM ".$db_tables["city_ip"]." ".
				"WHERE startIpNum<=inet_aton('$realip') and endIpNum>=inet_aton('$realip') ".
				"LIMIT 100";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "city_ip",
		"params_list"	=> array(""), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 37*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("value"=>$myrow["locId"]);
		}
	}
	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

 return $data_array;
}

function get_viewers_location()
{
 global $donotslash_where;
	$data_array = get_viewers_locId();

	$str = "";
	for ($i=0; $i<count($data_array); $i++)
	{
		if ( !isset($data_array[$i]["value"]) || ($data_array[$i]["value"] == "") ) continue;
		$str .= "c.locId='{$data_array[$i]["value"]}' or ";
	}

	if (strlen($str) > 0) {
		$str = substr($str, 0, -3);
		$str = '('.$str.') and ';
		$donotslash_where = true;
	}
// return " 1=1 and ".$str;
 return (strlen($str) > 0) ? " and ".$str : " and ";
}

function get_viewers_location_city()
{
 global $db_tables;
	$city_sql = get_viewers_location();

	if (($city_sql == "") || ($city_sql == " and ")) return "";

	if (strlen($city_sql) > 0) $city_sql = substr($city_sql, 0, -4);

	if (substr($city_sql, 0, 4) == ' and') $city_sql = ' 1=1 '.$city_sql;

	$sql = "SELECT c.country,c.region,c.city, r.name as regionname ".
				"FROM ".$db_tables["city"]." c ".
				"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region and c.country=r.country ".
				"WHERE ".$city_sql." LIMIT 5";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "city",
		"params_list"	=> array(""), //╬эш єўртёЄтє■Є т яюёЄЁюхэшш яєЄш (ъръ яряъш) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 36*60, //┬Ёхь  ръЄєры№эюёЄш т ьшышёхъ.
		"store_type"	=> "as_array" //╥шя ўЄхэш : 1)"as_table" - т ЄрсышЎє (шь  "table_name") 2)"as_array" - т ьрёёшт 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("city"=>$myrow["city"], "country"=>$myrow["country"], "region"=>$myrow["region"]);
		}
	}
	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

	$tmp = "";
	for($i=0; $i<count($data_array); $i++)
	{
		$tmp .= ($data_array[$i]["city"] != "") ? $data_array[$i]["city"].", " : "";
		$tmp .= ($data_array[$i]["region"] != "") ? $data_array[$i]["region"].", " : "";
		$tmp .= ($data_array[$i]["country"] != "") ? $data_array[$i]["country"]." " : "";
	}
 return $tmp;
}

//Resturn line with "stats_search_keywords" ids - see common_statistic.php -- function set_stats_search_keywords
function get_sskvalues($presymbol)
{
	if (!isset($_SESSION["sess_job_search"]["stats_search_keywords_ids"])) return "";
	$tmp = "";
	for ($i=0; $i<count($_SESSION["sess_job_search"]["stats_search_keywords_ids"]); $i++)
	{
		if (strlen($_SESSION["sess_job_search"]["stats_search_keywords_ids"][$i]) == 0) continue;
		$tmp .= "ssk[]=".$_SESSION["sess_job_search"]["stats_search_keywords_ids"][$i]."&";
	}
	return (strlen($tmp) > 0) ? $presymbol.substr($tmp, 0, -1) : "";
}

//Design job statistic values in job details URL
function do_job_design_in_url()
{
 global $job_list,$adv_job_list;
	if (count($job_list) > 0)	{
		foreach ($job_list as $k=>$v)
		{
			$job_list[$k]["clickurl"] .= get_sskvalues('&');
		}
	}
	if (count($adv_job_list) > 0) {
		foreach ($adv_job_list as $k=>$v)
		{
			$adv_job_list[$k]["clickurl"] .= get_sskvalues('&');
		}
	}
}

function get_kads_id_list(&$positive_keywords_array,$ad_id)
{
	$result = array();
	for ($i=0; $i<count($positive_keywords_array); $i++)
	{
		if ($positive_keywords_array[$i]["ad_id"] == $ad_id) $result[] = $positive_keywords_array[$i]["kads_id"];
	}
 return $result;
}

function get_jobroll_publisher_id()
{
 global $jobroll_publisher_id,$job_channel;
	$tmp = '';
	$tmp .= (isset($jobroll_publisher_id) && ($jobroll_publisher_id != "")) ? '&jobroll-publisher-id='.$jobroll_publisher_id : '';
	$tmp .= (isset($job_channel) && ($job_channel != "")) ? '&job-channel='.$job_channel : '';
 return $tmp;
}


// * * * * * * * //
// MyJobs search //
// * * * * * * * //
function do_myjobs_job_search(&$job_search_params,&$data_array,&$adv_data_array,&$result)
{
  global $db_tables;
	$result = true;
	$sql = $city_sql = $show_another_sql = "";
	if (!isset($_COOKIE["MyJobs_save"]) || ($_COOKIE["MyJobs_save"] == "")) return;

	$c_job_keys = $a_job_keys = array();
	foreach($_COOKIE["MyJobs_save"] as $k=>$v)
	{
		if ($v == "") continue;
		if ($k[0] == 'c') $c_job_keys[] = substr($k, 1);
		elseif ($k[0] == 'a') $a_job_keys[] = substr($k, 1);
	}

	$num = $num_a = 0;
	$sql = $sql_a = "";
	if (count($c_job_keys) > 0) {
		$sql = "WHERE data_id IN (".implode(",",$c_job_keys).")";
		//Final SELECT part: a) count pages select
		/*
		$sql_cnt = "SELECT count(*) as num ".
					"FROM ".$db_tables["data_list"]." d ".
					"INNER JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".$sql;
		$sql_cnt = preg_replace("/ +/", " ", $sql_cnt);
		*/
		$sql_cnt = "SELECT count(*) as num ".
					"FROM ".$db_tables["data_list"]." d ".$sql;
		$num = get_query_num_count($sql_cnt);
	}
	if (count($a_job_keys) > 0) {
		$sql_a = "WHERE data_id IN (".implode(",",$a_job_keys).")";
		//Final SELECT part: a) count pages select
		/*
		$sql_cnt = "SELECT count(*) as num ".
					"FROM ".$db_tables["data_list_advertiser"]." d ".
					"INNER JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".$sql_a;
		$sql_cnt = preg_replace("/ +/", " ", $sql_cnt);
		*/
		$sql_cnt = "SELECT count(*) as num ".
					"FROM ".$db_tables["data_list_advertiser"]." d ".$sql_a;
		$sql_cnt = preg_replace("/ +/", " ", $sql_cnt);
		$num_a = get_query_num_count($sql_cnt);
	}
  $_SESSION["sess_job_search"]["results_count"] = $num + $num_a;
	$page_count = ceil(($num+$num_a)/$job_search_params["number_results"]);
	
	//Save job query params
	$_SESSION["sess_job_search"]["search_started"] = true;
//	$_SESSION["sess_job_search"]["order_sql"] = $order_sql;
	$_SESSION["sess_job_search"]["order_sql"] = "";
	$_SESSION["sess_job_search"]["sql"] = $sql;
	$_SESSION["sess_job_search"]["sql_a"] = $sql_a;
//	$_SESSION["sess_job_search"]["sql_cnt"] = $sql_cnt;
	$_SESSION["sess_job_search"]["sql_cnt"] = "";
	$_SESSION["sess_job_search"]["from_count"] = 0;
	$_SESSION["sess_job_search"]["row_count"] = $job_search_params["number_results"];
	$_SESSION["sess_job_search"]["page_count"] = $page_count;
	$_SESSION["sess_job_search"]["page_start"] = 0;
	$_SESSION["sess_job_search"]["job_search_params"] = $job_search_params;
	//Execute job SQL (b) data select)
	$from_count = (isset($job_search_params["start"])) ? $job_search_params["start"] : 0;
	exec_sqlmyjob_search($sql,$sql_a,0,$job_search_params["number_results"],$data_array,$adv_data_array);
}


//Execute myjobs search query
/*
$sql - main sql query
$from_count,$row_count - limit
$data_array - data result
*/
function exec_sqlmyjob_search($sql,$sql_a,$from_count,$row_count,&$data_array,&$adv_data_array)
{
  global $db_tables,$SLINE,$job_search_params;
	//Final SELECT part: b.1.) data select from common data table
	$sql = preg_replace("/ +/", " ", $sql);
	$sql = preg_replace("/&#039;/", '\\\'', $sql);
	$sql = unhtmlentities($sql);
	$data_array = array();
	if (strlen($sql) > 3) {
		if ($_SESSION["globsettings"]["allow_cities_in_db"]) {
			//all variants
			$mainsql = "SELECT d.*,".format_sql_datetime("d.registered")." as myregtime, UNIX_TIMESTAMP(d.registered) as registered_sec, ".
						"r.name as rregionname, d.region as dregionname, ".
						"c.country as ccountry, c.region as cregion, c.city as ccity, c.postalCode, c.latitude, c.longitude, ".
						"d.country as dcountry, d.city as dcity ".
						"FROM ".$db_tables["data_list"]." d ".
						"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".
						"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region and c.country=r.country ";
		}
		else {
			//the same as all variants, but without "city" and "region" and their fields
			$mainsql = "SELECT d.*,".format_sql_datetime("d.registered")." as myregtime, UNIX_TIMESTAMP(d.registered) as registered_sec, ".
						"d.region as dregionname, ".
						"d.country as dcountry, d.city as dcity ".
						"FROM ".$db_tables["data_list"]." d ";
		}
		$mainsql .=	$sql;
		$mainsql = preg_replace("/and\s+and/", 'and', $mainsql);
//echo "mj 1)".$sql."<br>\n\n";
//echo "mj 1.1)".$mainsql."<br>\n\n";
	
		// * * Check cache * * //
		$cache_params_array = array(
			"user"				=> 3, //$_SESSION["sess_user"]
			"cache_group"	=> "smarty_frontend",
			"userid"			=> 0, //$_SESSION["sess_userid"]
			"section"			=> "search_result",
			"table_name"	=> "data_list",
			"params_list"	=> array(""), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
			"query"				=> $mainsql,
			"actual_time"	=> 12*60, //Время актуальности в сек.
			"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
		);
	
		// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
		if (!read_mydata_cache($cache_params_array,$data_array)) {
			//Get global settings list
			$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
			while ($myrow = mysql_fetch_array($qr_res))
			{
				$data_array['c'.$myrow["data_id"]] = array("feed_id"=>$myrow["feed_id"], "title"=>$myrow["title"], "company_name"=>$myrow["company_name"],
					"description"=>$myrow["description"], "clickurl"=>$_SESSION["globsettings"]["site_url"]."job-details/?data-id=".$myrow["data_id"],
					"url"=>$myrow["url"], "salary"=>$myrow["salary"],	"registered_sec"=>$myrow["registered_sec"], "myregtime"=>$myrow["myregtime"],
					"country"=>((isset($myrow["ccountry"]) && ($myrow["ccountry"] != '')) ? $myrow["ccountry"] : $myrow["dcountry"]),
					"region"=>((isset($myrow["cregion"]) && ($myrow["cregion"] != '')) ? $myrow["cregion"] : $myrow["dregionname"]),
					"city"=>((isset($myrow["ccity"]) && ($myrow["ccity"] != '')) ? $myrow["ccity"] : $myrow["dcity"]),
					"regionname"=>((isset($myrow["rregionname"]) && ($myrow["rregionname"] != '')) ? $myrow["rregionname"] : $myrow["dregionname"]),
					"postalCode"=>((isset($myrow["postalCode"]) && ($myrow["postalCode"] != '')) ? $myrow["postalCode"] : ''),
					"latitude"=>((isset($myrow["latitude"]) && ($myrow["latitude"] != '')) ? $myrow["latitude"] : ''),
					"longitude"=>((isset($myrow["longitude"]) && ($myrow["longitude"] != '')) ? $myrow["longitude"] : ''),
					"jobkey"=>'c'.$myrow["data_id"],"source"=>$myrow["source"],"locId"=>$myrow["locId"],"job_type"=>$myrow["job_type"]);
			}
		}
		// * * Write cache * * //
		//if use cache - save data
		write_mydata_cache($cache_params_array,$data_array);

		//Add special for this process data
		add_special_for_this_proc_data($data_array,"clickurl",'&'.$SLINE.get_jobroll_publisher_id());
	}

	$adv_data_array = array();
	if (strlen($sql_a) > 3) {
		//Final SELECT part: b.2.) data select from advertiser jobs data table
		$adv_mainsql = "SELECT d.*,".format_sql_datetime("d.registered")." as myregtime, UNIX_TIMESTAMP(d.registered) as registered_sec, ".
					"c.country,c.region,c.city,c.postalCode,c.latitude,c.longitude, j.max_cpc,j.job_ads_id,j.uid_adv,j.destination_url, r.name as regionname ".
					"FROM ".$db_tables["data_list_advertiser"]." d ".
					"INNER JOIN ".$db_tables["job_ads"]." j ON d.feed_id=j.job_ads_id and j.status=1 ".
					"INNER JOIN ".$db_tables["users_advertiser"]." ua ON j.uid_adv=ua.uid_adv and ua.balance>0.0 and ua.isenable=1 and ua.isconfirmed=1 and ua.isdeleted=0 ".
					"INNER JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".
					"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region and c.country=r.country ".
					$sql_a;
	if ($_SESSION["globsettings"]["allow_cities_in_db"]) {
		//all variants
		$adv_mainsql = "SELECT d.*,".format_sql_datetime("d.registered")." as myregtime, UNIX_TIMESTAMP(d.registered) as registered_sec, ".
					"r.name as rregionname, d.region as dregionname, ".
					"c.country as ccountry, c.region as cregion, c.city as ccity, c.postalCode, c.latitude, c.longitude, ".
					"d.country as dcountry, d.city as dcity, ".
					"j.max_cpc,j.job_ads_id,j.uid_adv,j.destination_url ".
					"FROM ".$db_tables["data_list_advertiser"]." d ".
					"INNER JOIN ".$db_tables["job_ads"]." j ON d.feed_id=j.job_ads_id and j.status=1 ".
					"INNER JOIN ".$db_tables["users_advertiser"]." ua ON j.uid_adv=ua.uid_adv and ua.balance>0.0 and ua.isenable=1 and ua.isconfirmed=1 and ua.isdeleted=0 ".
					"INNER JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".
					"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region and c.country=r.country ";
	}
	else {
		//the same as all variants, but without "city" and "region" and their fields
		$adv_mainsql = "SELECT d.*,".format_sql_datetime("d.registered")." as myregtime, UNIX_TIMESTAMP(d.registered) as registered_sec, ".
					"d.region as dregionname, ".
					"d.country as dcountry, d.city as dcity, ".
					"j.max_cpc,j.job_ads_id,j.uid_adv,j.destination_url ".
					"FROM ".$db_tables["data_list_advertiser"]." d ".
					"INNER JOIN ".$db_tables["job_ads"]." j ON d.feed_id=j.job_ads_id and j.status=1 ".
					"INNER JOIN ".$db_tables["users_advertiser"]." ua ON j.uid_adv=ua.uid_adv and ua.balance>0.0 and ua.isenable=1 and ua.isconfirmed=1 and ua.isdeleted=0 ";
	}
	$adv_mainsql .= $sql_a;
//echo "mj 2)".$adv_mainsql."<br>\n\n";

		// * * Check cache * * //
		$cache_params_array = array(
			"user"				=> 3, //$_SESSION["sess_user"]
			"cache_group"	=> "smarty_frontend",
			"userid"			=> 0, //$_SESSION["sess_userid"]
			"section"			=> "search_result",
			"table_name"	=> "data_list_advertiser",
			"params_list"	=> array(""), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
			"query"				=> $adv_mainsql,
			"actual_time"	=> 6*60, //Время актуальности в сек.
			"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
		);

		// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
		if (!read_mydata_cache($cache_params_array,$adv_data_array)) {
			//Get global settings list
			$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
			while ($myrow = mysql_fetch_array($qr_res))
			{
				$adv_data_array['a'.$myrow["data_id"]] = array("feed_id"=>$myrow["feed_id"], "title"=>$myrow["title"], "company_name"=>$myrow["company_name"],
					"description"=>$myrow["description"], "clickurl"=>$_SESSION["globsettings"]["site_url"]."job-details/?data-id-adv=".$myrow["data_id"],
					"url"=>$myrow["url"], "salary"=>$myrow["salary"], "registered_sec"=>$myrow["registered_sec"], "myregtime"=>$myrow["myregtime"],
					"country"=>((isset($myrow["ccountry"]) && ($myrow["ccountry"] != '')) ? $myrow["ccountry"] : $myrow["dcountry"]),
					"region"=>((isset($myrow["cregion"]) && ($myrow["cregion"] != '')) ? $myrow["cregion"] : $myrow["dregionname"]),
					"city"=>((isset($myrow["ccity"]) && ($myrow["ccity"] != '')) ? $myrow["ccity"] : $myrow["dcity"]),
					"regionname"=>((isset($myrow["rregionname"]) && ($myrow["rregionname"] != '')) ? $myrow["rregionname"] : $myrow["dregionname"]),
					"postalCode"=>((isset($myrow["postalCode"]) && ($myrow["postalCode"] != '')) ? $myrow["postalCode"] : ''),
					"latitude"=>((isset($myrow["latitude"]) && ($myrow["latitude"] != '')) ? $myrow["latitude"] : ''),
					"longitude"=>((isset($myrow["longitude"]) && ($myrow["longitude"] != '')) ? $myrow["longitude"] : ''),
					"max_cpc"=>$myrow["max_cpc"],	"uid_adv"=>$myrow["uid_adv"],
					"destination_url"=>$myrow["destination_url"],	"job_ads_id"=>$myrow["job_ads_id"],	"jobkey"=>'a'.$myrow["data_id"], "source"=>"");
			}
		}
		// * * Write cache * * //
		//if use cache - save data
		write_mydata_cache($cache_params_array,$adv_data_array);

		//Add special for this process data
		add_special_for_this_proc_data($adv_data_array,"clickurl",'&'.$SLINE.get_jobroll_publisher_id());
	}

	$_SESSION["sess_job_search"]["adv_job_list"] = $adv_data_array;

	//Discount job click cost
	discount_this_job_adv($adv_data_array);
}

function do_myjob_design(&$job_search_params,&$job_list,&$adv_job_list)
{
  global $db_tables, $SLINE;
	$already_more_locations = array();
	//jobs list
	if (count($job_list) > 0) {
		foreach($job_list as $job_id=>$job_data)
		{
			//check search mode
			$job_list[$job_id]["plus_locations"]["count"] = 0;
			//check other values
			$feed_name = get_feed_name_by_id($job_data["feed_id"]);
			$job_list[$job_id]["feed_name"] = (isset($feed_name["title"])) ? $feed_name["title"] : "";
			$registered_ago = get_registered_ago($job_data["registered_sec"]);
			$job_list[$job_id]["registered_ago"] = $registered_ago["text"];
			$job_list[$job_id]["isnew"] = $registered_ago["isnew"];
		}
	}
	//adv jobs list
	if (count($adv_job_list) > 0) {
		foreach($adv_job_list as $job_id=>$job_data)
		{
			$adv_job_list[$job_id]["plus_locations"]["count"] = 0;
			$adv_job_list[$job_id]["feed_name"] = $job_data["destination_url"];
			$registered_ago = get_registered_ago($job_data["registered_sec"]);
			$adv_job_list[$job_id]["registered_ago"] = $registered_ago["text"];
			$adv_job_list[$job_id]["isnew"] = 0;
		}
	}
}

//Next | Prev page select FOR My Jobs page
function next_page_query_myjob(&$job_search_params,&$job_list)
{
	//Check prev search
	$nextpg = get_get_true_false("nextpg");
	$start = html_chars(get_get_value("start",""));
	if ($nextpg && ($start != "") && check_int($start) && check_fill_some_sess_values()) {
		if ($start > ($_SESSION["sess_job_search"]["page_count"]-1)) $start = $_SESSION["sess_job_search"]["page_count"]-1;
		if ($start < 0) $start = 0;
		$from_count = $start*$_SESSION["sess_job_search"]["row_count"];
		$_SESSION["sess_job_search"]["page_start"] = $start;
	}
	else {
		$from_count = 0;
		$_SESSION["sess_job_search"]["page_start"] = 0;
	}
	$job_list_temp = array();
	$i = 0;
	foreach ($job_list as $k=>$v)
	{
		if (($i >= $from_count) && ($i < $from_count+$_SESSION["sess_job_search"]["row_count"])) $job_list_temp[$k] = $job_list[$k];
		$i++;
	}
	$job_list = $job_list_temp;
}


// * * * * * * //
// Site Header //
// * * * * * * //
function create_site_header_search()
{
	create_site_title_search();
	create_site_description_search();
	create_site_keywords_search();
}

function create_site_title_search()
{
 global $job_search_params,$text_info,$SiteTitle;
	if (isset($job_search_params) && isset($job_search_params["search_type"])) $jsp = $job_search_params;
	elseif (isset($_SESSION["sess_job_search"]["job_search_params"]) && isset($_SESSION["sess_job_search"]["job_search_params"]["search_type"])) $jsp = $_SESSION["sess_job_search"]["job_search_params"];
	else { $SiteTitle = ''; return; }
	$site_title = '';
	if ($jsp["search_type"] == "simple") {
		if ($jsp["what"] != "") $site_title .= $jsp["what"];
		if ( ($jsp["where"] != "") && (strlen($site_title) > 0) ) $site_title .= ' in '.$jsp["where"].' ';
		else $site_title .= $jsp["where"].' ';
	}
	else {
		$vals = array("as_all","as_phrase","as_any","as_title","as_company");
		for ($i=0; $i<count($vals); $i++)
		{
			if ($jsp[$vals[$i]] != "") $site_title .= $jsp[$vals[$i]].' ';
		}
		if ( ($jsp["where"] != "") && (strlen($site_title) > 0) ) $site_title .= ' in '.$jsp["where"].' ';
		else $site_title .= $jsp["where"].' ';
	}
	$site_title = trim($site_title);
	if (strlen($site_title) > 0) 	$SiteTitle = str_replace("{*SiteTitle*}", $site_title, $text_info["html_site_title_fill"]);
	elseif (($jsp["jobs_category"] != "") && ($jsp["jobs_category"] > 0)) $SiteTitle = str_replace("{*SiteTitle*}", get_jobcatname_by_id($jsp["jobs_category"]), $text_info["html_site_title_fill"]);
	else $SiteTitle = '';
}

function get_jobcatname_by_id($cat_id)
{
 global $db_tables,$text_info;
	//Cache settings
	$sql = "SELECT cat_name FROM ".$db_tables["jobcategories"]." WHERE cat_id='$cat_id'";
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
		$data_array["0"] = array("cat_name"=>"");
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[0] = array("cat_name"=>$myrow["cat_name"]);
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);
 return $data_array[0]["cat_name"];
}

function create_site_description_search()
{
 global $text_info,$SiteDescription,$SiteTitle;
	if (isset($SiteTitle) && ($SiteTitle != "")) $SiteDescription = str_replace("{*SiteDescription*}", $SiteTitle, $text_info["html_site_description_fill"]);
	else $SiteDescription = '';
}

function create_site_keywords_search()
{
 global $text_info,$SiteKeywords,$SiteTitle;
	if (isset($SiteTitle) && ($SiteTitle != "")) $SiteKeywords = $SiteTitle;
	else $SiteKeywords = '';
}

function get_country_limit($cond)
{
 global $JobrollPublisherID;
	if (isset($JobrollPublisherID) && ($JobrollPublisherID != "")) return "";
	if ($_SESSION["globsettings"]["selected_country"] == "all") return "";
	else return $cond."'".strtoupper($_SESSION["globsettings"]["selected_country"])."' ";
}

function get_country_limit_by_loc_search()
{
 global $JobrollPublisherID;
	if (isset($JobrollPublisherID) && ($JobrollPublisherID != "")) return "";
	if ($_SESSION["globsettings"]["selected_country"] == "all") return "";
	else {
		$country_sql = $country_sql1 = "";
		if ($_SESSION["globsettings"]["allow_cities_in_db"]) {
			$country_sql = "c.country='".strtoupper($_SESSION["globsettings"]["selected_country"])."'";
		}
		if ($_SESSION["globsettings"]["allow_cities_not_in_db"]) {
			$country_sql1 = "d.country='".strtoupper($_SESSION["globsettings"]["selected_country"])."'";
		}
		if (($country_sql != '') && ($country_sql1 != '')) {
			return " and ({$country_sql} or {$country_sql1}) ";
		}
		if ($country_sql != '') return " and ".$country_sql;
		if ($country_sql1 != '') return " and ".$country_sql1;
	}
	return "";
}
?>