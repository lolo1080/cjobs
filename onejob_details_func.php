<?
function do_onejob_data_search($data_id)
{
 global $db_tables, $SiteTitle, $SiteDescription;
	$sql = "SELECT d.*,UNIX_TIMESTAMP(d.registered) as registered_sec, ".
						"r.name as rregionname, d.region as dregionname, ".
						"c.country as ccountry, c.region as cregion, c.city as ccity, ".
						"d.country as dcountry, d.city as dcity ".
				"FROM ".$db_tables["data_list"]." d ".
				"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".
				"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region and c.country=r.country ".
				"WHERE d.data_id='$data_id'";

	//Get job list
	$data_array = array();
	$qr_res = mysql_query($sql) or query_die(__FILE__,__LINE__,mysql_error());
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

	if (count($data_array) > 0) {
		$SiteTitle = $data_array[0]["title"];
		$SiteDescription = substr($data_array[0]["description"], 0, 50);
		return $data_array;
	}

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

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	//Get job list
	$qr_res = mysql_query($sql) or query_die(__FILE__,__LINE__,mysql_error());
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

	if (count($data_array) > 0) {
		$SiteTitle = $data_array[0]["title"];
		$SiteDescription = substr($data_array[0]["description"], 0, 50);
		return $data_array;
	}
	else return array();
}

function do_onejob_design(&$job_list)
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
		$job_list[$job_id]["isnew"] = $registered_ago["isnew"];
	}
}
?>