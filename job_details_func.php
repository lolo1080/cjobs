<?
function Encrypt($data)
{
	$cry = new get_crypt(); // Encoded data is good for an hour
 return $cry->encode(array("sdt"=>$data));
}

function Decrypt($data)
{
	$cry = new get_crypt(); // Encoded data is good for an hour
	$dec = $cry->decode($data);
 return $dec["sdt"];
}

function do_job_data_search($data_id)
{
 global $db_tables;
	$sql = "SELECT d.*,UNIX_TIMESTAMP(d.registered) as registered_sec, ".
						"r.name as rregionname, d.region as dregionname, ".
						"c.country as ccountry, c.region as cregion, c.city as ccity, ".
						"d.country as dcountry, d.city as dcity ".
				"FROM ".$db_tables["data_list"]." d ".
				"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".
				"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region and c.country=r.country ".
				"WHERE d.data_id='$data_id'";
	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "data_list",
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
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("data_id"=>$myrow["data_id"], "feed_id"=>$myrow["feed_id"], "title"=>$myrow["title"],
				"company_name"=>$myrow["company_name"], "description"=>$myrow["description"], "clickurl"=>$_SESSION["globsettings"]["site_url"]."job-details/?data_id=".$myrow["data_id"],
				"url"=>$myrow["url"], "registered_sec"=>$myrow["registered_sec"], 
				"country"=>((isset($myrow["ccountry"]) && ($myrow["ccountry"] != '')) ? $myrow["ccountry"] : $myrow["dcountry"]),
				"region"=>((isset($myrow["cregion"]) && ($myrow["cregion"] != '')) ? $myrow["cregion"] : $myrow["dregionname"]),
				"regionname"=>((isset($myrow["rregionname"]) && ($myrow["rregionname"] != '')) ? $myrow["rregionname"] : $myrow["dregionname"]),
				"city"=>((isset($myrow["ccity"]) && ($myrow["ccity"] != '')) ? $myrow["ccity"] : $myrow["dcity"]),
				"jobkey"=>'c'.$myrow["data_id"], "source"=>$myrow["source"]);
		}
	}
	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

	if (count($data_array) > 0) return $data_array;

	// - - - - - - - - - - - - - //
	// * * Get deleted data * * //
	$sql = "SELECT d.*,UNIX_TIMESTAMP(d.registered) as registered_sec, ".
						"r.name as rregionname, d.region as dregionname, ".
						"c.country as ccountry, c.region as cregion, c.city as ccity, ".
						"d.country as dcountry, d.city as dcity ".
				"FROM ".$db_tables["data_list_deleted"]." d ".
				"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".
				"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region and c.country=r.country ".
				"WHERE d.data_id='$data_id'";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "data_list_deleted",
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
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("data_id"=>$myrow["data_id"], "feed_id"=>$myrow["feed_id"], "title"=>$myrow["title"],
				"company_name"=>$myrow["company_name"], "description"=>$myrow["description"], "clickurl"=>$_SESSION["globsettings"]["site_url"]."job-details/?data_id=".$myrow["data_id"],
				"url"=>$myrow["url"], "registered_sec"=>$myrow["registered_sec"], "country"=>$myrow["country"],
				"country"=>((isset($myrow["ccountry"]) && ($myrow["ccountry"] != '')) ? $myrow["ccountry"] : $myrow["dcountry"]),
				"region"=>((isset($myrow["cregion"]) && ($myrow["cregion"] != '')) ? $myrow["cregion"] : $myrow["dregionname"]),
				"regionname"=>((isset($myrow["rregionname"]) && ($myrow["rregionname"] != '')) ? $myrow["rregionname"] : $myrow["dregionname"]),
				"city"=>((isset($myrow["ccity"]) && ($myrow["ccity"] != '')) ? $myrow["ccity"] : $myrow["dcity"]),
				"jobkey"=>'c'.$myrow["data_id"], "source"=>$myrow["source"]);
		}
	}
	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

	if (count($data_array) > 0) return $data_array;
	else return array();
}

function do_job_adv_data_search($data_id_adv)
{
 global $db_tables;
	$sql = "SELECT d.*,UNIX_TIMESTAMP(d.registered) as registered_sec, j.destination_url, ".
					"r.name as rregionname, d.region as dregionname, ".
					"c.country as ccountry, c.region as cregion, c.city as ccity, c.postalCode, c.latitude, c.longitude, ".
					"d.country as dcountry, d.city as dcity ".
				"FROM ".$db_tables["data_list_advertiser"]." d ".
				"INNER JOIN ".$db_tables["job_ads"]." j ON d.feed_id=j.job_ads_id ".
				"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".
				"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region and c.country=r.country ".
				"WHERE d.data_id='$data_id_adv'";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "data_list_advertiser",
		"params_list"	=> array(""), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 4*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("data_id"=>$myrow["data_id"], "feed_id"=>$myrow["feed_id"], "title"=>$myrow["title"],
				"company_name"=>$myrow["company_name"], "description"=>$myrow["description"], "clickurl"=>$_SESSION["globsettings"]["site_url"]."job-details/?data_id_adv=".$myrow["data_id"],
				"url"=>$myrow["url"], "destination_url"=>$myrow["destination_url"], "registered_sec"=>$myrow["registered_sec"],
				"country"=>((isset($myrow["ccountry"]) && ($myrow["ccountry"] != '')) ? $myrow["ccountry"] : $myrow["dcountry"]),
				"region"=>((isset($myrow["cregion"]) && ($myrow["cregion"] != '')) ? $myrow["cregion"] : $myrow["dregionname"]),
				"city"=>((isset($myrow["ccity"]) && ($myrow["ccity"] != '')) ? $myrow["ccity"] : $myrow["dcity"]),
				"regionname"=>((isset($myrow["rregionname"]) && ($myrow["rregionname"] != '')) ? $myrow["rregionname"] : $myrow["dregionname"]),
				"jobkey"=>'a'.$myrow["data_id"]);
		}
	}
	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

	if (count($data_array) > 0) return $data_array;
	else return array();
}

function do_job_sponsored_adv_data_search($ad_id)
{
 global $db_tables;
	$sql = "SELECT ad_id,destination_url ".
				"FROM ".$db_tables["ads"]." ".
				"WHERE ad_id='$ad_id'";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "ads",
		"params_list"	=> array(""), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 5*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("data_id"=>$myrow["ad_id"],"url"=>$myrow["destination_url"]);
		}
	}
	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

	if (count($data_array) > 0) return $data_array;
	else return array();
}
?>