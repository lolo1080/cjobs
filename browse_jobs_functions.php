<?
function get_browse_keyword_settings()
{
 global $browse_keyword_sect_cnt,$browse_keyword_sub_sect_cnt,$browse_keyword_more_sub_sect_cnt;
	$BrowseKeywordSettings["sect_cnt"] = $browse_keyword_sect_cnt; //First page section count (10)
	$BrowseKeywordSettings["sub_sect_cnt"] = $browse_keyword_sub_sect_cnt; //First page sub-section count (5)
	$BrowseKeywordSettings["more_sub_sect_cnt"] = $browse_keyword_more_sub_sect_cnt; //All pages (except first) section count (200)
}

function get_browse_keyword_list()
{
 global $keyword_id;
	return ($keyword_id == "") ? get_browse_keyword_list_page("level>=0 and level<=1",0,0) : get_browse_keyword_list_page("parent='$keyword_id'",1,$keyword_id);
}

function get_browse_keyword_list_page($sql_cond,$page,$keyword_id)
{
 global $db_tables,$text_info;
 global $browse_keyword_sect_cnt,$browse_keyword_sub_sect_cnt,$browse_keyword_more_sub_sect_cnt;
	$site_url = $_SESSION["globsettings"]["site_url"];
	/* * * Select keywords count * * */
	//Cache settings
	$sql = "SELECT * FROM ".$db_tables["browse_keyword"]." WHERE ".$sql_cond." ORDER BY link";
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "browse_jobs",
		"table_name"	=> "browse_keyword",
		"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 50*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get values list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$url = ($myrow["type"] == 2) ? $site_url."jobs/?what=".urlencode($myrow["link"]) : $site_url."browse_jobs/browse_keyword/".urlencode($myrow["link"])."/".$myrow["kid"];
			$data_array[] = array("kid"=>$myrow["kid"], "type"=>$myrow["type"], "level"=>$myrow["level"], "parent"=>$myrow["parent"],
				"path"=>$myrow["path"], "link"=>$myrow["link"], "url"=>$url);
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

	$browse_keyword = $tmp = array();
	$k = $s = 0;
	if ($page == 0) {
		for ($i=0; $i<count($data_array); $i++)
		{
			if ($data_array[$i]["level"] == 0) {
				//$browse_keyword[LEVEL][ID]["main"]
				$tmp[$data_array[$i]["kid"]]["main"] = $data_array[$i];
			}
			else {
				//$browse_keyword[LEVEL-1][ID]["main"]
				$tmp[$data_array[$i]["parent"]]["node"][] = $data_array[$i];
			}
		}
		$spl = ceil($browse_keyword_sect_cnt/2);
		foreach ($tmp as $k1=>$v1)
		{
			$browse_keyword[$k][$k1] = $v1;
			$s++;
			if ($s >= $spl) { $k++; $s = -1;}
		}
	}
	else {
		get_browsekeywordlistnavigation($keyword_id);
		get_browsekeywordlistmostpopular($keyword_id);
		$spl = ceil(count($data_array)/2);
		for ($i=0; $i<count($data_array); $i++)
		{
			$browse_keyword[$k][$data_array[$i]["kid"]]["node"][] = $data_array[$i];
			$s++;
			if ($s >= $spl) { $k++; $s = -1;}
		}
	}

 return $browse_keyword;
}

function get_browse_keyword_data_by_id($keyword_id)
{
 global $db_tables;
	/* * * Select path to current keyword * * */
	//Cache settings
	$sql = "SELECT * FROM ".$db_tables["browse_keyword"]." WHERE kid='$keyword_id'";
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "browse_jobs",
		"table_name"	=> "browse_keyword",
		"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 50*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get values list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[0] = array("kid"=>$myrow["kid"], "type"=>$myrow["type"], "path"=>$myrow["path"], "link"=>$myrow["link"]);
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

 return $data_array;
}

function get_browsekeywordlistnavigation($keyword_id)
{
 global $BrowseKeywordListNavigation;
	$BrowseKeywordListNavigation = array();

	$data_array = get_browse_keyword_data_by_id($keyword_id);

	if (!isset($data_array[0])) return;

	$nav_list = explode(";",$data_array[0]["path"]);

	for ($i=0; $i<count($nav_list); $i++)
	{
		$data_array = get_browse_keyword_data_by_id($nav_list[$i]);
		if (!isset($data_array[0])) continue;
		$site_url = $_SESSION["globsettings"]["site_url"];
		$url = ($data_array[0]["type"] == 0) ? $site_url."browse_jobs/browse_types/" : $site_url."browse_jobs/browse_keyword/".urlencode($data_array[0]["link"])."/".$data_array[0]["kid"];
		$BrowseKeywordListNavigation[] = array("url"=>$url, "keyword"=>$data_array[0]["link"]);
	}
}

function get_browsekeywordlistmostpopular($keyword_id)
{
 global $db_tables,$BrowseKeywordListMostPopular;
	$site_url = $_SESSION["globsettings"]["site_url"];
	/* * * Select path to current keyword * * */
	//Cache settings
	$sql = "SELECT * FROM ".$db_tables["browse_keyword_most_popular"]." WHERE kid='$keyword_id'";
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "browse_jobs",
		"table_name"	=> "browse_keyword_most_popular",
		"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 48*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get values list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("url"=>$site_url."jobs/?what=".urlencode($myrow["keyword"]), "keyword"=>$myrow["keyword"]);
		}
	}

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

	$BrowseKeywordListMostPopular = $data_array;
}
?>