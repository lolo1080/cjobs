<?
function create_xmsearch_error_result($message)
{
echo '<?xml version="1.0" encoding="ISO-8859-1"?>
<response>
   <error>'.$message.'</error>
</response>
';
}

function xmlfeed_alloved_for_user()
{
  global $db_tables,$SLINE,$jobroll_publisher_id;

	$sql = "SELECT isxmlfeed_enable FROM ".$db_tables["users_publisher"]." WHERE uid_pub='$jobroll_publisher_id'";

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_result",
		"table_name"	=> "users_publisher",
		"params_list"	=> array(""), //Ž­¨ ãç ¢áâ¢ãîâ ¢ ¯®áâà®¥­¨¨ ¯ãâ¨ (ª ª ¯ ¯ª¨) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 14*60, //‚à¥¬ï  ªâã «ì­®áâ¨ ¢ á¥ª.
		"store_type"	=> "as_array" //’¨¯ çâ¥­¨ï: 1)"as_table" - ¢ â ¡«¨æã (¨¬ï "table_name") 2)"as_array" - ¢ ¬ áá¨¢ 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[] = array("isxmlfeed_enable"=>$myrow["isxmlfeed_enable"]);
		}
	}
	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

	if (!isset($data_array[0]["isxmlfeed_enable"])) return 0;
	return $data_array[0]["isxmlfeed_enable"];
}

function get_job_xmlsearch_params()
{
 global $number_results_default;
	//Get search params (Advenced search values)
	$job_search_params["error_code"] = "none";
	$job_search_params["search_type"] = "advanced";
	$job_search_params["as_any"]			= html_chars(get_get_post_value("what",""));			//with at least one of these words
	$job_search_params["where"]				= html_chars(get_get_post_value("where",""));			//what - search keyword
	$job_search_params["sort_by"]			= html_chars(get_get_post_value("sort_by",""));		//sort jobs by (relevance, date). Empty means - relevance
	$job_search_params["radius"]			= html_chars(get_get_post_value("radius",0));			//within
	$job_search_params["jobs_from"]		= html_chars(get_get_post_value("site_type",""));	//show jobs from
	$job_search_params["jobs_type"]		= html_chars(get_get_post_value("job_type",""));	//show jobs of type
	$job_search_params["number_results"]= html_chars(get_get_post_value("max_number",$number_results_default));//number results per page
	$job_search_params["jobs_published"]= html_chars(get_get_post_value("fromage",""));	//jobs published (anytime, within 30 days, ..., since my last visit)
	//Get Special values for XML search
	$job_search_params["start"]			= html_chars(get_get_post_value("start",0));			//results at this result number
	$job_search_params["highlight"]	= html_chars(get_get_post_value("highlight",0));	//value to 1 will bold terms
	$job_search_params["latlong"]		= html_chars(get_get_post_value("latlong",0));		//1 - returns latitude and longitude information for each job result
	$job_search_params["userip"]		= html_chars(get_get_post_value("userip",""));		//the IP number of the end-user to whom the job results will be displayed
	$job_search_params["useragent"]	= html_chars(get_get_post_value("useragent",""));	//the user-agent (browser) of the end-user to whom the job results will be displayed
	//+ Locations search values and jobroll (job_country)
	$job_search_params["title"] = $job_search_params["company_name"] = $job_search_params["job_country"] = "";
	//Blank values
	$job_search_params["as_all"] = $job_search_params["as_phrase"] = $job_search_params["what"] = $job_search_params["as_not"] =
	$job_search_params["as_title"] = $job_search_params["as_company"] = $job_search_params["salary"] = "";
	$job_search_params["jobs_category"] = $job_search_params["norecruiters"] = 0;
 return $job_search_params;
}

function xml_highlight($description,&$job_search_params)
{
	if (!$job_search_params["highlight"]) return $description;
	$terms = search_keywords_as_array($job_search_params["as_any"].' '.$job_search_params["where"]);
	if (count($terms) == 0) return $description;
	for ($i=0; $i<count($terms); $i++)
	{
		if (strlen($terms[$i]) == 0) continue;
		$description = str_replace($terms[$i], '<b>'.$terms[$i].'</b>', $description);
	}
 return $description;
}

function get_xml_snippet($description,&$job_search_params)
{
	if (strlen($description) < 151) return xml_highlight($description,$job_search_params);
	$description = substr($description, 0, 148);
	$pos = strrpos($description, " ");
	if ($pos === false) {
		return xml_highlight($description.'...',$job_search_params);
	}
	else {
		$description = substr($description, 0, $pos);
		return xml_highlight($description.'...',$job_search_params);
	}
}

function do_xmljob_output(&$job_search_params,&$job_list,&$adv_job_list)
{
	//Create data for output
	$xml_job_list = $xml_adv_job_list = array();
	$jobI = $advjobI = 0;
	foreach($job_list as $job_id=>$job_data)
	{
		$xml_job_list[] = $job_data;
	}
	foreach($adv_job_list as $job_id=>$job_data)
	{
		$xml_adv_job_list[] = $job_data;
	}
	//Add Ads to output
	if (count($xml_adv_job_list) > 0) {
		//Add method
		if (count($xml_job_list) >= $job_search_params["number_results"]) $method = array("method"=>'insert');
		elseif ( (count($xml_job_list)+count($xml_adv_job_list)) <= $job_search_params["number_results"]) $method = array("method"=>'add',"amount"=>count($xml_adv_job_list));
		elseif ( (count($xml_job_list)+count($xml_adv_job_list)) > $job_search_params["number_results"]) $method = array("method"=>'add',"amount"=>(abs(count($xml_job_list)-count($xml_adv_job_list))));
		do_xml_merge_arrays($job_search_params,$xml_job_list,$xml_adv_job_list,$method);
	}
	//Check (if we have mistake and add more record then we need)
	while(count($xml_job_list) > $job_search_params["number_results"])
	{
		$tmp = array_pop($xml_job_list);
	}
	$result_tag = '';

	/* Create XML Output */
	for($i=0; $i<count($xml_job_list); $i++)
	{
		$result_tag .= '
      <result>
         <jobtitle>'.$xml_job_list[$i]["title"].'</jobtitle>
         <company>'.$xml_job_list[$i]["company_name"].'</company>
         <city>'.$xml_job_list[$i]["city"].'</city>
         <state>'.$xml_job_list[$i]["region"].'</state>
         <country>'.$xml_job_list[$i]["country"].'</country>
         <source>'.$xml_job_list[$i]["feed_name"].'</source>
         <date>'.$xml_job_list[$i]["registered_gmt_datetime"].'</date>
         <snippet>'.get_xml_snippet($xml_job_list[$i]["description"],$job_search_params).'</snippet>
         <url>'.$xml_job_list[$i]["clickurl"].'</url>';
		if (isset($job_search_params["latlong"]) && $job_search_params["latlong"])
		$result_tag .= '
         <latitude>'.$xml_job_list[$i]["latitude"].'</latitude>
         <longitude>'.$xml_job_list[$i]["longitude"].'</longitude>';
		$result_tag .= '
         <jobkey>'.$xml_job_list[$i]["jobkey"].'</jobkey>
      </result>';
	}

echo '<?xml version="1.0" encoding="ISO-8859-1"?>
<response>
   <query>'.$job_search_params["as_any"].'</query>
   <location>'.$job_search_params["where"].'</location>
   <highlight>'.$job_search_params["highlight"].'</highlight>
   <totalresults>'.($_SESSION["sess_job_search"]["results_count"]+count($xml_adv_job_list)).'</totalresults>
   <start>'.$job_search_params["start"].'</start>
   <end>'.($job_search_params["start"]+count($xml_job_list)).'</end>
   <results>'.$result_tag.'
   </results>
</response>';
/*
<?xml version="1.0" encoding="ISO-8859-1"?>
<response>
   <query>java</query>
   <location>austin, tx</location>
   <highlight>false</highlight>
   <totalresults>547</totalresults>
   <start>1</start>
   <end>10</end>
   <results>
      <result>
         <jobtitle>Java Developer</jobtitle>
         <company>XYZ Corp.</company>
         <city>Austin</city>
         <state>TX</state>
         <country>US</country>
         <source>Dice</source>
         <date>Sat, 22 Mar 2011 11:46:27 GMT</date>
         <snippet>looking for an object-oriented Java Developer...
         Java Servlets, HTML, JavaScript, AJAX, Struts, Struts2, JSF)
         desirable. Familiarity with Tomcat and the Java...</snippet>
         <url>http://localhost/esprojects/casinojobs/viewjob?jk=12345
         &indpubnum=2717704648944419</url>
         <onmousedown>indeed_clk(this,'0000');</onmousedown>
         <latitude>30.27127</latitude>
         <longitude>-97.74103</longitude>
         <jobkey>12345</jobkey>
      </result>
      ...
   </results>
</response>
*/
}

//Merge 2 arrays (Jobs - $xml_job_list and Jobs Ads - $xml_adv_job_list using $method)
function do_xml_merge_arrays(&$job_search_params,&$xml_job_list,&$xml_adv_job_list,&$method)
{
	/* Method: INSERT */
	if ($method["method"] == 'insert') {
		//Calculate insert count
		$cnt = round(count($xml_job_list)/5);
		$cnt = ($cnt > count($xml_adv_job_list)) ? count($xml_adv_job_list) : $cnt;
		//Check sort
		if (($job_search_params["sort_by"] == "registered") || ($job_search_params["sort_by"] == "date")) {
			//Order by Date
			$xml_job_list_tmp = array();
			$j = 0;
			for($i=0; $i<count($xml_job_list); $i++)
			{
				if ( isset($xml_adv_job_list[$j]["registered_sec"]) && ($j<=($cnt-1)) && ($xml_adv_job_list[$j]["registered_sec"] >= $xml_job_list[$i]["registered_sec"]) ) {
					$xml_job_list_tmp[] = $xml_adv_job_list[$i]; $j++;
				}
				else $xml_job_list_tmp[] = $xml_job_list[$i];
			}
			$xml_job_list = $xml_job_list_tmp;
		}
		else {
			//Order by Relevant
			$max = count($xml_job_list)-1;
			for($i=0; $i<$cnt; $i++)
			{
				$xml_job_list[rand(0,$max)] = $xml_adv_job_list[$i];
			}
		}
	}
	/* Method: ADD */
	elseif ($method["method"] == 'add') {
		$cnt = $method["method"]["amount"];
		//Check sort
		if (($job_search_params["sort_by"] == "registered") || ($job_search_params["sort_by"] == "date")) {
			//Order by Date
			$xml_job_list_tmp = array();
			$i = $j = 0;
			for($k=0; $i<count($xml_job_list)+$cnt; $k++)
			{
				if (!isset($xml_job_list[$i]["registered_sec"])) {
					$xml_job_list_tmp[] = $xml_adv_job_list[$j]; $j++;
				}
				elseif (!isset($xml_adv_job_list[$j]["registered_sec"])) {
					$xml_job_list_tmp[] = $xml_job_list[$i]; $i++;
				}
				elseif ($xml_adv_job_list[$j]["registered_sec"] >= $xml_job_list[$i]["registered_sec"]) {
					$xml_job_list_tmp[] = $xml_adv_job_list[$j]; $j++;
				}
				else {
					$xml_job_list_tmp[] = $xml_job_list[$i]; $i++;
				}
			}
			$xml_job_list = $xml_job_list_tmp;
		}
		else {
			//Order by Relevant - add to the end
			for($j=0; $j<$cnt; $j++)
			{
				$xml_job_list[] = $xml_adv_job_list[$j];
			}
		}
	}
}
?>