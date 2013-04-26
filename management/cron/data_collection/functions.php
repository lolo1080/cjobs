<?
//Unlock feeds where parsing was started 5 hours ago
function check_feed_list_structure()
{
 global $db_tables,$log_actions;
	debug_log_file($log_actions["feed_structure"],1,0,'Check all feeds structure','Try to find feeds which not finished in last 5 hours and flush theirs');
	$qr_res = mysql_query("SELECT registered,title FROM ".$db_tables["sites_feed_list"]." WHERE isparsednow=1 and startparsed<DATE_SUB(NOW(),INTERVAL 5 HOUR)") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		debug_log_file($log_actions["feed_structure"],0,0,'Not correct structure for '.addslashes($myrow['title']),'Feed "'.addslashes($myrow['title']).'" was started at '.$myrow['registered'].' and not finished yet. It will be stopted.');
	}
	//select broken feeds
	$qr_res = mysql_query("SELECT feed_id,startparsed,UNIX_TIMESTAMP(startparsed) as startparsed_sec FROM ".$db_tables["sites_feed_list"]." WHERE isparsednow=1 and startparsed<DATE_SUB(NOW(),INTERVAL 5 HOUR)") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$qr_res1 = mysql_query("SELECT dateinsert,UNIX_TIMESTAMP(dateinsert) as dateinsert_sec, UNIX_TIMESTAMP(NOW()) as datenow_sec FROM ".$db_tables["data_list"]." WHERE feed_id={$myrow["feed_id"]} ORDER BY dateinsert DESC LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res1) > 0) {
			$myrow1 = mysql_fetch_array($qr_res1);
			if (abs($myrow1["datenow_sec"] - $myrow1["dateinsert_sec"]) > 5*60)
				mysql_query("UPDATE ".$db_tables["sites_feed_list"]." SET isparsednow=0 WHERE feed_id='{$myrow["feed_id"]}'") or query_die(__FILE__,__LINE__,mysql_error());
		}
	}
}
/*
function check_feed_list_structure()
{
 global $db_tables,$log_actions;
	debug_log_file($log_actions["feed_structure"],1,0,'Check all feeds structure','Try to find feeds which not finished in last 5 hours and flush theirs');
	$qr_res = mysql_query("SELECT registered,title FROM ".$db_tables["sites_feed_list"]." WHERE isparsednow=1 and startparsed<DATE_SUB(NOW(),INTERVAL 5 HOUR)") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		debug_log_file($log_actions["feed_structure"],0,0,'Not correct structure for '.addslashes($myrow['title']),'Feed "'.addslashes($myrow['title']).'" was started at '.$myrow['registered'].' and not finished yet. It will be stopted.');
	}
	//select broken feeds
	$qr_res = mysql_query("SELECT feed_id,startparsed,UNIX_TIMESTAMP(startparsed) as startparsed_sec FROM ".$db_tables["sites_feed_list"]." WHERE isparsednow=1 and startparsed<DATE_SUB(NOW(),INTERVAL 5 HOUR)") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
//		$qr_res1 = mysql_query("SELECT dateinsert,UNIX_TIMESTAMP(dateinsert) as dateinsert_sec FROM ".$db_tables["data_list"]." WHERE feed_id={$myrow["feed_id"]} ORDER BY dateinsert DESC LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
//		if (mysql_num_rows($qr_res1) > 0) {
//			$myrow1 = mysql_fetch_array($qr_res1);
//			if (abs($myrow1["dateinsert_sec"] - $myrow["startparsed_sec"]) > 3*60*60)
				mysql_query("UPDATE ".$db_tables["sites_feed_list"]." SET isparsednow=0 WHERE feed_id='{$myrow["feed_id"]}'") or query_die(__FILE__,__LINE__,mysql_error());
//		}
	}
}
*/

//Get feed data
function get_next_feed()
{
 global $db_tables;
	$feed = array("result"=>0);
	//Get main feed info
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["sites_feed_list"]." WHERE isactive=1 and isparsednow=0 and ( (startparsed = 0) or (NOW() > DATE_ADD(startparsed, INTERVAL refresh_rate SECOND)) ) ORDER BY startparsed ASC") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		$feed = array("result"=>1, "feed_id"=>$myrow["feed_id"], "feed_code"=>$myrow["feed_code"], "title"=>$myrow["title"],
									"description"=>$myrow["description"], "url"=>$myrow["url"], "registered"=>$myrow["registered"],
									"refresh_rate"=>$myrow["refresh_rate"], "max_recursion_depths"=>$myrow["max_recursion_depths"],
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
 return $feed;
}

function lock_this_feed($feed_id)
{
 global $db_tables;
	mysql_query("UPDATE ".$db_tables["sites_feed_list"]." SET isparsednow=1, startparsed=NOW() WHERE feed_id='$feed_id'") or query_die(__FILE__,__LINE__,mysql_error());
}

function unlock_this_feed($feed_id)
{
 global $db_tables;
	mysql_query("UPDATE ".$db_tables["sites_feed_list"]." SET isparsednow=0 WHERE feed_id='$feed_id'") or query_die(__FILE__,__LINE__,mysql_error());
}

//Get last 2 days job recordes from DB (bu not more then 2000). Необходимо для проверки того, что при добавлении новой записи ее нет в БД
function get_last_this_jobrecords($feed_id, $cat_id, &$DataList)
{
 global $db_tables, $feed;
	doconnect();
	//Get last job update from current feed date.
	if ($feed["feed_type"] == "advertiser")	$qr_res = mysql_query("SELECT DATE_FORMAT(dateinsert,'%Y-%m-%d') as lastdate FROM ".$db_tables["data_list_advertiser"]." WHERE feed_id='{$feed["job_ads_id"]}' and cat_id='$cat_id' ORDER BY dateinsert DESC LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
	else $qr_res = mysql_query("SELECT DATE_FORMAT(dateinsert,'%Y-%m-%d') as lastdate FROM ".$db_tables["data_list"]." WHERE feed_id='$feed_id' and cat_id='$cat_id' ORDER BY dateinsert DESC LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		//Get all jobs from current feed after 2 days
		$myrow = mysql_fetch_array($qr_res);
		$lastdate = $myrow["lastdate"];
		if ($feed["feed_type"] == "advertiser")	$qr_res = mysql_query("SELECT title,company_name,url FROM ".$db_tables["data_list_advertiser"]." WHERE feed_id='{$feed["job_ads_id"]}' and cat_id='$cat_id' and dateinsert>=DATE_SUB('{$lastdate}',INTERVAL 2 DAY) ORDER BY dateinsert DESC LIMIT 2000") or query_die(__FILE__,__LINE__,mysql_error());
		else $qr_res = mysql_query("SELECT title,company_name,url FROM ".$db_tables["data_list"]." WHERE feed_id='$feed_id' and cat_id='$cat_id' and dateinsert>=DATE_SUB('{$lastdate}',INTERVAL 2 DAY) ORDER BY dateinsert DESC LIMIT 2000") or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res,MYSQL_ASSOC))
		{
			$DataList[] = array("title"=>$myrow["title"],"company_name"=>$myrow["company_name"],"url"=>$myrow["url"]);
		}
	}
}



/***********************/
/*MAIN Parsing function*/
function start_feed_parsing($feed_id,$type=true)
{
	global $NewDataCnt,$DataCollection,$feed;
	$NewDataCnt = 0; $DataCollection = array("possible_insert"=>array(),"real_insert"=>array());
	switch ($feed["feed_format"])	{
		case 'xml1': start_xml1_feed_parsing(1,$feed_id,$type); break;
		case 'xml2': start_xml2_feed_parsing(2,$feed_id,$type); break;
		case 'html1':
		case 'html2': start_html_feed_parsing($feed_id,$type); break;
		default: echo "This ({$feed["feed_format"]}) feed type is not ready yet. Please, contact to administrator.";
	}
}

function start_xml1_feed_parsing($nm,$feed_id,$type=true)
{
	global $db_tables,$feed_row;
	$tbl = ($type) ? $db_tables["xml_feeds_data"] : $db_tables["xml_feeds_data_temp"];
	$qr_res = mysql_query("SELECT fd.*, fc.*, fl.*, cat.*, fd.url as url FROM ".$tbl." fd ".
							"INNER JOIN ".$db_tables["xml_feeds_configuration"]." fc ON fd.config_id=fc.config_id ".
							"INNER JOIN ".$db_tables["sites_feed_list"]." fl ON fd.feed_id=fl.feed_id ".
							"INNER JOIN ".$db_tables["jobcategories"]." cat ON fd.cat_id=cat.cat_id ".
							"WHERE fd.feed_id='{$feed_id}' ORDER BY fd.cat_id") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res,MYSQL_ASSOC))
	{
		$feed_row = $myrow;
		start_xml_feed_row_parsing($feed_id);
	}
}

function start_xml2_feed_parsing($nm,$feed_id,$type=true)
{
	global $db_tables,$feed_row;
	$tbl = ($type) ? $db_tables["xml2_feeds_data"] : $db_tables["xml2_feeds_data_temp"];
	$qr_res = mysql_query("SELECT fd.*, fc.*, fl.*, fd.url as url FROM ".$tbl." fd ".
							"INNER JOIN ".$db_tables["xml_feeds_configuration"]." fc ON fd.config_id=fc.config_id ".
							"INNER JOIN ".$db_tables["sites_feed_list"]." fl ON fd.feed_id=fl.feed_id ".
							"WHERE fd.feed_id='{$feed_id}' ORDER BY fd.fdata_id") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res,MYSQL_ASSOC))
	{
		$feed_row = $myrow;
		$feed_row["cat_id"] = -1; // stub for category id (no need it)
		$feed_row["cat_name"] = 'no (xml2 type)'; // stub for category name (no need it)
		start_xml_feed_row_parsing($feed_id);
	}
}


function start_xml_feed_row_parsing($feed_id)
{
 global $FeedName,$usersettings,$log_actions,$CC,$FR,$DataList,$feed_row,$feed;

	$FeedName = $feed_row["title"]; //String const - feed name

	if (($feed["feed_type"] == "advertiser")  && ($feed["status"] != 1)) {
		debug_log_file($log_actions["feed_parsing"],0,0,'Feed parsing for '.$FeedName.' feed: Exit: advertiser feed not active',"Feed parsing for ".$FeedName." feed: Exit, because advertiser Job Ads (Sponsored Job Ads) related to feed is not active now \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		return 0;
	}

	if (function_exists('date_default_timezone_set')) date_default_timezone_set($usersettings["timezone_identifier"]);

	//Get already stored jobs
	$DataList = array();
	get_last_this_jobrecords($feed_id,$feed_row["cat_id"],$DataList);

	$CC = 0; $FR = 0;

	crawl_xml_feed_row_parsing();
}

function crawl_xml_feed_row_parsing()
{
 global $FeedName,$CC,$FR,$log_actions,$feed_row,$Result;

	$url = $feed_row["url"];

	if ($FR >=3 ) {
		debug_log_file($log_actions["feed_parsing"],0,0,'Feed parsing for '.$FeedName.' feed: Exit 3',"Feed parsing for ".$FeedName." feed: Exit, becouse we found 3 record which already present in DB \nURL: $url, \nCategory: {$feed_row["cat_name"]}. \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		return 0;
	}

	$CC++;
	debug_log_file($log_actions["feed_parsing"],1,1,'Feed parsing for '.$FeedName.' feed',"Feed parsing for ".$FeedName." feed. CC: $CC, \nURL: $url, \nCategory: {$feed_row["cat_name"]}. \nFile: ".addslashes(__FILE__).' Line: '.__LINE__,"**********\nDate(dd/mm/yyyy): ".date("d/m/Y H:i:s")."\n");

	$my_error = '';
	$url_parts = get_url_parts($my_error,$url);
	if ($my_error != "") {
		debug_log_file($log_actions["feed_parsing"],0,0,'Cannot get URL parts for '.$FeedName.' feed',"Cannot get URL parts for ".$FeedName." feed. \nURL: $url, \nError: $my_error \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		return 0;
	}
	debug_log_file($log_actions["feed_parsing"],1,2,'Get URL parts for '.$FeedName.' feed',"Get URL parts for ".$FeedName." feed. \nURL: $url, \nURL Parts:\n".print_r($url_parts, true)." \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);

/* LOCAL FILE CONTENT */
/*
	$f = fopen('C:\Apache\Apache2\htdocs\esjobsearchengine\management\cron\data_collection\helper\hays.co.uk',"r");
	$content = fread($f, filesize('C:\Apache\Apache2\htdocs\esjobsearchengine\management\cron\data_collection\helper\hays.co.uk'));
	fclose($f);
	$Result['xml_rawdata'] = $content;
*/

/* REAL CONTENT */
	$Result['xml_rawdata'] = '';
	get_site_content($my_error,$url_parts);


	if ($my_error != "") {
		debug_log_file($log_actions["feed_parsing"],0,0,'Cannot get data content for '.$FeedName.' feed',"Cannot get content for ".$FeedName." feed. \nURL: $url, \nError: $my_error \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		return 0;
	}
	else {
		//Parse Page
		debug_log_file($log_actions["feed_parsing"],1,2,'Get data content for '.$FeedName.' feed was successful',"Get data content for ".$FeedName." feed was successful. \nURL: $url, \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		parse_xml_feed_content($Result['xml_rawdata']);
	}
}

function parse_xml_feed_content(&$content)
{
	global $text_info,$Configuration,$ConfigurationCategoryKeywords,$feed_row,$log_actions,$FeedName;
	$url = $feed_row["url"];

	//Configuration
	$Configuration = array(
		"fields"	=> array(
			array(
				"title"		=> $text_info["p_configuration_fld_title"],
				"name"		=> "title",
				"data"		=> array()
			),
			array(
				"title" 	=> $text_info["p_configuration_fld_company_name"],
				"name"		=> "company_name",
				"data"		=> array()
			),
			array(
				"title"		=> $text_info["p_configuration_fld_locId"],
				"name"		=> "locId",
				"data"		=> array()
			),
			array(
				"title"		=> $text_info["p_configuration_fld_description"],
				"name"		=> "description",
				"data"		=> array()
			),
			array(
				"title"		=> $text_info["p_configuration_fld_url"],
				"name"		=> "url",
				"data"		=> array()
			),
			array(
				"title" 	=> $text_info["p_configuration_fld_job_type"],
				"name"		=> "job_type",
				"data"		=> array()
			),
			array(
				"title"		=> $text_info["p_configuration_fld_site_type"],
				"name"		=> "site_type",
				"data"		=> array()
			),
			array(
				"title" 	=> $text_info["p_configuration_fld_isstaffing_agencies"],
				"name"		=> "isstaffing_agencies",
				"data"		=> array()
			),
			array(
				"title" 	=> $text_info["p_configuration_fld_salary"],
				"name"		=> "salary",
				"data"		=> array()
			)
		),
	);

	//Additional part for category - xml2 feed
	$ConfigurationCategoryKeywords = array();
	if ($feed_row["feed_format"] == 'xml2') {
		$ConfigurationCategoryKeywords = get_configuration_category_keywords_for_xml2($feed_row["config_id"]);
		$conf_fld_cat = array(
			"title" 	=> $text_info["p_configuration_fld_category"],
			"name"		=> "category",
			"data"		=> array()
		);
		array_push($Configuration["fields"],$conf_fld_cat);
	}

	//Parsed XML - array
	try {
		$xml = new xml2array($content);
		$element = $xml->getResult();
	}
	catch (Exception $e) {
		debug_log_file($log_actions["feed_parsing"],0,2,"Parser error. Cannot parse XML feed {$FeedName}","Parser error. Cannot parse XML feed {$FeedName}. Internal parser error \nURL: $url, \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
	}	

	//Fill result data
	debug_log_file($log_actions["feed_parsing"],1,2,"Start XML data parsing for {$FeedName} feed","Start XML data parsing for {$FeedName} feed. \nURL: $url, \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
	find_data_container_xml($element);

	//Put data to database
	put_data_container();
}

function get_configuration_category_keywords_for_xml2($config_id)
{
 global $db_tables, $SLINE, $Error_messages;
	$categories = array();
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["xml2_feeds_category_keywords"]." WHERE config_id='{$config_id}'") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res,MYSQL_ASSOC))
	{
		$categories[] = $myrow;
	}
	return $categories;
}

function find_data_container_xml(&$element)
{
	global $Configuration,$feed_row;
	$stopcnt = ($feed_row["feed_format"] == 'xml2') ? 500000 : 100;
	//Text box - simple way
	$confcount = get_confcount('1');
	$i = 0; $break_arr = array();
	while (true)
	{
		foreach ($Configuration["fields"] as $k=>$v)
		{
			$nm = $v['name'];
			//Text box - simple way
			if ($feed_row[$nm.'_status'] == '1') {
				$field = $feed_row[$nm.'_field'];
				if (!is_replase_iteration_present($field,'?')) $break_arr[$nm] = 1;
				$field = do_field_replasement($field,$i);
				eval("if (isset(\$element".$field.")) { \$fnd = 1; \$dt = \$element".$field."; } else { \$fnd = 0; \$dt = ''; } ");
				if ($fnd) $Configuration["fields"][$k]["data"][] = $dt;
				else {
					if (isset($break_arr[$nm]) && $break_arr[$nm]) $Configuration["fields"][$k]["data"][] = $field;
					$break_arr[$nm] = 1;
				}
			}
		}
		if (count($break_arr) >= $confcount) break;
		$i++;
		if ($i > $stopcnt) {print_r($break_arr); echo "more {$stopcnt}(1)"; break;}
	}
	//Remove last incorrect value
	foreach ($Configuration["fields"] as $k=>$v)
	{
		if (isset($v["data"][$i])) unset($Configuration["fields"][$k]["data"][$i]);
	}

	//Area box - php parsed data
	$confcount = get_confcount('2');
	$i = 0; $break_arr = array();
	while (true)
	{
		foreach ($Configuration["fields"] as $k=>$v)
		{
			$nm = $v['name'];
			//Text box - simple way
			if ($feed_row[$nm.'_status'] == '2') {
				$field = $feed_row[$nm.'_phpcode'];
				if ( $c = preg_match_all("~({\*.+?\*})~si", $field, $matches) ) {
					//check ? inside {*...*} fields
					if (!is_replase_iteration_present_in_matches($matches[1],'?')) $break_arr[$nm] = 1;
					//replace ? with $i and remove {*, *}
					do_matches_replasement($matches[1],$i);
					//calculate (eval)
					$fnd = eval_matches($element,$matches[1]);
					//if no data for all matches with current $i
					if (count($matches[1]) == $fnd) $break_arr[$nm] = 1;
					else $Configuration["fields"][$k]["data"][] = eval_area_code($field,$matches);
				}
				else {
					$cntcopy = get_max_fieldscount();
					$Configuration["fields"][$k]["data"] = array();
					for ($j=0; $j<$cntcopy; $j++)
					{
						$pos = strpos($field, '$result');
						if ($pos !== false) {
							$tmp = array();
							$Configuration["fields"][$k]["data"][] = eval_area_code($field,$tmp);
						}
						else $Configuration["fields"][$k]["data"][] = $field;
					}
					$break_arr[$nm] = 1;
				}
			}
		}
		if (count($break_arr) >= $confcount) break;
		$i++;
		if ($i > $stopcnt) {print_r($break_arr); echo "more {$stopcnt}(2)"; break;}
	}
}

function get_confcount($n)
{
	global $Configuration,$feed_row;
	$i = 0;
	foreach ($Configuration["fields"] as $k=>$v)
	{
		if ($feed_row[$v['name'].'_status'] == $n) $i++;
	}
	return $i;
}

function is_replase_iteration_present($str,$ch)
{
	$pos = strpos($str, '?');
	return ($pos !== false) ? true : false;
}

function is_replase_iteration_present_in_matches(&$matches,$ch)
{
	$n = 0;
	for ($i=0; $i<count($matches); $i++)
	{
		if (!is_replase_iteration_present($matches[$i],'?')) $n++;
	}
	return ($n == count($matches)) ? false : true;
}

function do_field_replasement($field,$i)
{
	$field = str_replace('?', $i, $field);
	$field = str_replace('{*', '', $field);
	$field = str_replace('*}', '', $field);
	$field = str_replace('[', '["', $field);
	$field = str_replace(']', '"]', $field);
	return $field;
}

function do_matches_replasement(&$matches,$n)
{
	for ($i=0; $i<count($matches); $i++)
	{
		$matches[$i] = do_field_replasement($matches[$i],$n);
	}
}

function eval_matches(&$element,&$matches)
{
	$fnd = 0;
	for ($i=0; $i<count($matches); $i++)
	{
		eval("if (isset(\$element".$matches[$i].")) { \$dt = \$element".$matches[$i]."; } else { \$fnd++; \$dt = ''; } ");
		$matches[$i] = addslashes($dt);
	}
	return $fnd;
}

function eval_html_matches(&$MainRegExpr,&$matches)
{
	global $Result;
	$fnd = 0;
	for ($i=0; $i<count($matches); $i++)
	{
		eval("try {   if (isset(\$".$matches[$i].")) { \$dt = \$".$matches[$i]."; } else { \$fnd++; \$dt = ''; }   } catch (Exception \$e) { \$fnd++; \$dt = ''; } ");
		$matches[$i] = addslashes($dt);
	}
	return $fnd;
}

function eval_area_code($field,&$matches)
{
	global $feed_row,$log_actions,$FeedName,$Result;
	$url = $feed_row["url"];

	$result = '';
	if (isset($matches[0])) {
		for ($i=0; $i<count($matches[0]); $i++)
		{
			$field = str_replace($matches[0][$i], $matches[1][$i], $field);
		}
	}
	try {
		$return_eval = eval($field);
		if ( $return_eval === false ) {
			debug_log_file($log_actions["feed_parsing"],0,2,"Cannot calculate php code for {$FeedName} feed","Cannot calculate php code in PHP parsed data for {$FeedName} feed. \nURL: $url, \nCode: ".addslashes($field)." \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
			return '';
		}
		return $result;
	}
	catch (Exception $e) {
		debug_log_file($log_actions["feed_parsing"],0,2,"Cannot calculate php code for {$FeedName} feed","Cannot calculate php code in PHP parsed data for {$FeedName} feed. \nURL: $url, \nCode: ".addslashes($field)." \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
	}	
}

function get_max_fieldscount()
{
	global $Configuration;
	$max = 0;
	foreach ($Configuration["fields"] as $k=>$v)
	{
		if (count($v["data"]) > $max) $max = count($v["data"]);
	}
	return $max;
}



/***************************/
/*MAIN HTML Parsing function*/
function start_html_feed_parsing($feed_id,$type=true)
{
	global $db_tables,$feed_row;
	$tbl = ($type) ? $db_tables["html_feeds_data"] : $db_tables["html_feeds_data_temp"];

	$qr_res = mysql_query("SELECT fd.*, fc.*, fl.*, cat.*, fd.url as url FROM ".$tbl." fd ".
							"INNER JOIN ".$db_tables["html_feeds_configuration"]." fc ON fd.config_id=fc.config_id ".
							"INNER JOIN ".$db_tables["sites_feed_list"]." fl ON fd.feed_id=fl.feed_id ".
							"INNER JOIN ".$db_tables["jobcategories"]." cat ON fd.cat_id=cat.cat_id ".
							"WHERE fd.feed_id='{$feed_id}' ORDER BY fd.cat_id") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res,MYSQL_ASSOC))
	{
		$feed_row = $myrow;
		start_html_feed_row_parsing($feed_id);
	}
}

function start_html_feed_row_parsing($feed_id)
{
 global $FeedName,$usersettings,$log_actions,$CC,$FR,$DataList,$feed_row,$feed;

	$FeedName = $feed_row["title"]; //String const - feed name

	if (($feed["feed_type"] == "advertiser")  && ($feed["status"] != 1)) {
		debug_log_file($log_actions["feed_parsing"],0,0,'Feed parsing for '.$FeedName.' feed: Exit: advertiser feed not active',"Feed parsing for ".$FeedName." feed: Exit, because advertiser Job Ads (Sponsored Job Ads) related to feed is not active now \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		return 0;
	}

	if (function_exists('date_default_timezone_set')) date_default_timezone_set($usersettings["timezone_identifier"]);

	//Get already stored jobs
	$DataList = array();
	get_last_this_jobrecords($feed_id,$feed_row["cat_id"],$DataList);

	$CC = 0; $FR = 0;

	crawl_html_feed_row_parsing();
}

function crawl_html_feed_row_parsing()
{
 global $FeedName,$CC,$FR,$log_actions,$feed_row,$Result,$feed,$data_collection_config;

	$url = $feed_row["url"];

	if ($FR >=3 ) {
		debug_log_file($log_actions["feed_parsing"],0,0,'Feed parsing for '.$FeedName.' feed: Exit 3',"Feed parsing for ".$FeedName." feed: Exit, becouse we found 3 record which already present in DB \nURL: $url, \nCategory: {$feed_row["cat_name"]}. \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		return 0;
	}

	$CC++;
	debug_log_file($log_actions["feed_parsing"],1,1,'Feed parsing for '.$FeedName.' feed',"Feed parsing for ".$FeedName." feed. CC: $CC, \nURL: $url, \nCategory: {$feed_row["cat_name"]}. \nFile: ".addslashes(__FILE__).' Line: '.__LINE__,"**********\nDate(dd/mm/yyyy): ".date("d/m/Y H:i:s")."\n");

	$my_error = '';
	$url_parts = get_url_parts($my_error,$url);
	if ($my_error != "") {
		debug_log_file($log_actions["feed_parsing"],0,0,'Cannot get URL parts for '.$FeedName.' feed',"Cannot get URL parts for ".$FeedName." feed. \nURL: $url, \nError: $my_error \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		return 0;
	}
	debug_log_file($log_actions["feed_parsing"],1,2,'Get URL parts for '.$FeedName.' feed',"Get URL parts for ".$FeedName." feed. \nURL: $url, \nURL Parts:\n".print_r($url_parts, true)." \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);

/* LOCAL FILE CONTENT */
/*
	$f = fopen("cron/data_collection/helper/www.careerbuilder.com.txt","r");
	$content = fread($f, filesize("cron/data_collection/helper/www.careerbuilder.com.txt"));
	fclose($f);
	$Result['html_rawdata'] = $content;
*/

/* REAL CONTENT */
	$Result['xml_rawdata'] = '';
	get_site_content($my_error,$url_parts);
	if ($data_collection_config["should_sleep_after_connect"]) usleep($data_collection_config["sleep_after_connect_timeout"]);
	$Result['html_rawdata'] = $Result['xml_rawdata'];
	unset($Result['xml_rawdata']);

	if ($my_error != "") {
		debug_log_file($log_actions["feed_parsing"],0,0,'Cannot get data content for '.$FeedName.' feed',"Cannot get content for ".$FeedName." feed. \nURL: $url, \nError: $my_error \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		return 0;
	}
	else {
		//Parse Page
		debug_log_file($log_actions["feed_parsing"],1,2,'Get data content for '.$FeedName.' feed was successful',"Get data content for ".$FeedName." feed was successful. \nURL: $url, \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		parse_html_feed_content($Result['html_rawdata']);
		//Check mode or real mode
		if (isset($DataCollectionGlobal["mode"]) && ($DataCollectionGlobal["mode"] == 'check')) { ; }
		else {
			//Next Page
			if ( ($CC < $feed["max_recursion_depths"]) && find_nextpage_page() ) { crawl_html_feed_row_parsing(); }
		}
	}
}

function find_nextpage_page()
{
	global $Configuration,$feed_row;
	foreach ($Configuration["fields"] as $k=>$v)
	{
		$nm = $v['name'];
		if ($nm == "nextpage") {
			if (isset($v['data'][0]) && ($v['data'][0] != '') && check_url($v['data'][0])) {
				$feed_row["url"] = $v['data'][0];
				return 1;
			}
		}
	}
	return 0;
}

function parse_html_feed_content(&$content)
{
	global $text_info,$Configuration,$feed_row,$log_actions,$FeedName;
	$url = $feed_row["url"];
	//Configuration
	$Configuration = array(
		"fields"	=> array(
			array(
				"title"		=> $text_info["p_configuration_fld_title"],
				"name"		=> "title",
				"data"		=> array()
			),
			array(
				"title" 	=> $text_info["p_configuration_fld_company_name"],
				"name"		=> "company_name",
				"data"		=> array()
			),
			array(
				"title"		=> $text_info["p_configuration_fld_locId"],
				"name"		=> "locId",
				"data"		=> array()
			),
			array(
				"title"		=> $text_info["p_configuration_fld_description"],
				"name"		=> "description",
				"data"		=> array()
			),
			array(
				"title"		=> $text_info["p_configuration_fld_url"],
				"name"		=> "url",
				"data"		=> array()
			),
			array(
				"title" 	=> $text_info["p_configuration_fld_job_type"],
				"name"		=> "job_type",
				"data"		=> array()
			),
			array(
				"title"		=> $text_info["p_configuration_fld_site_type"],
				"name"		=> "site_type",
				"data"		=> array()
			),
			array(
				"title" 	=> $text_info["p_configuration_fld_isstaffing_agencies"],
				"name"		=> "isstaffing_agencies",
				"data"		=> array()
			),
			array(
				"title" 	=> $text_info["p_configuration_fld_salary"],
				"name"		=> "salary",
				"data"		=> array()
			),
			array(
				"title" 	=> $text_info["p_configuration_fld_nextpage"],
				"name"		=> "nextpage",
				"data"		=> array()
			)
		),
	);

	if (strtolower($feed_row['feed_format']) == 'html1') {
		//Parsed HTML - regexpr
		try {
			$matches = array();
			if ( preg_match_all($feed_row['html_parse_regular_expression'], $content, $matches) ) {
  
				if (isset($matches[0]) && (count($matches[0]) > 0)) {
  
					//Fill result data
					debug_log_file($log_actions["feed_parsing"],1,2,"Start HTML1 data parsing for {$FeedName} feed","Start HTML data parsing for {$FeedName} feed. \nURL: $url, \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
					find_data_container_html($matches);
  
					//Put data to database
					put_data_container();
  
				}
				else debug_log_file($log_actions["feed_parsing"],0,2,"Main regexpr error. No [0] array from Main regexpr. Feed {$FeedName}","Main regexpr error. No [0][0] array from Main regexpr. Feed {$FeedName}. Regexpr: {$feed_row['html_parse_regular_expression']} \nURL: $url, \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
  
			}
			else debug_log_file($log_actions["feed_parsing"],0,2,"Main regexpr error. No data from Main regexpr. Feed {$FeedName}","Main regexpr error. No data from Main regexpr. Feed {$FeedName}. Regexpr: {$feed_row['html_parse_regular_expression']} \nURL: $url, \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		}
		catch (Exception $e) {
			debug_log_file($log_actions["feed_parsing"],0,2,"Parser error. Cannot parse HTML feed {$FeedName}","Parser error. Cannot parse HTML feed {$FeedName}. Internal parser error \nURL: $url, \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		}	
	}
	elseif (strtolower($feed_row['feed_format']) == 'html2') {
		//Parsed HTML - DOM

		//Fill result data
		debug_log_file($log_actions["feed_parsing"],1,2,"Start HTML data parsing for {$FeedName} feed","Start HTML2 data parsing for {$FeedName} feed. \nURL: $url, \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);

		find_data_container_html2($content);

		//Put data to database
		put_data_container();

	}
	else {
		debug_log_file($log_actions["feed_parsing"],0,2,"Parser error. Incorrect HTML feed {$FeedName} type","Parser error. Incorrect HTML feed {$FeedName} type (should be html1 or html2, but we have {$feed_row['feed_format']}). Internal parser error \nURL: $url, \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
	}
}

function find_data_container_html(&$MainRegExpr)
{
	global $Configuration,$feed_row;
	//Text box - simple way
	for ($i=0; $i<count($MainRegExpr[0]); $i++)
	{
		foreach ($Configuration["fields"] as $k=>$v)
		{
			$nm = $v['name'];
			//Text box - simple way
			if ($feed_row[$nm.'_status'] == '1') {
				$field = $feed_row[$nm.'_field'];
				$field = do_field_replasement($field,$i);
				eval("try {   if (isset(\$".$field.")) { \$fnd = 1; \$dt = \$".$field."; } else { \$fnd = 0; \$dt = ''; }   } catch (Exception \$e) { \$fnd = -1; \$dt = ''; } ");
				switch ($fnd) {
					case 1: $Configuration["fields"][$k]["data"][] = $dt; break;
					case 0: $Configuration["fields"][$k]["data"][] = $field; break;
					default: $Configuration["fields"][$k]["data"][] = ''; break;
				}
			}
		}
	}

	//Area box - php parsed data
	for ($i=0; $i<count($MainRegExpr[0]); $i++)
	{
		foreach ($Configuration["fields"] as $k=>$v)
		{
			$nm = $v['name'];
			//Text box - simple way
			if ($feed_row[$nm.'_status'] == '2') {
				$field = $feed_row[$nm.'_phpcode'];
				if ( $c = preg_match_all("~({\*.+?\*})~si", $field, $matches) ) {
					//replace ? with $i and remove {*, *}
					do_matches_replasement($matches[1],$i);
					//calculate (eval)
					$fnd = eval_html_matches($MainRegExpr,$matches[1]);
					//if no data for all matches with current $i
					if (count($matches[1]) == $fnd) ;
					else $Configuration["fields"][$k]["data"][] = eval_area_code($field,$matches);
				}
				else {
					$pos = strpos($field, '$result');
					if ($pos === false) $Configuration["fields"][$k]["data"][] = $field;
					else {
						$matches = array();
						$Configuration["fields"][$k]["data"][] = eval_area_code($field,$matches);
					}
				}
			}
		}
	}
}


function find_data_container_html2(&$content)
{
 global $ResListCnt,$Rres,$script_dir,$Configuration;
	require_once($script_dir.'/html_parser/simple_html_dom.php');

	$html = str_get_html($content);

	$lang = '';
	$l=$html->find('html', 0);
	if ($l!==null) $lang = $l->lang;
	if ($lang!='') $lang = 'lang="'.$lang.'"';

	$charset = $html->find('meta[http-equiv*=content-type]', 0);
	$target = array();
	$query = '*';
	$target = $html->find($query);

	$ResListCnt = 0;
	$Rbase = ''; $Rres = array();
	foreach($target as $e)
	{
		dump_container_html_tree($Rbase, 0, $e, true);
	}

	fill_container_based_on_tree();
}

function dump_container_html_tree($Rbase, $n, $node, $show_attr=true, $deep=0, $last=true) {
	global $ResListCnt,$Rres;
    $count = count($node->nodes);

		check_dom_line_for_data('{*c*'.$Rbase.'['.htmlspecialchars($node->tag).']['.$n.']*c*}','c',$node);
		check_dom_line_for_data('{*t*'.$Rbase.'['.htmlspecialchars($node->tag).']['.$n.']*t*}','t',$node);
		//check_dom_line_for_data('{*a*'.$Rbase.'['.htmlspecialchars($node->tag).']['.$n.']*a*}','a',$node);
		check_dom_line_for_data('{*cs*'.$Rbase.'['.htmlspecialchars($node->tag).']['.$n.']*cs*}','cs',$node);
		check_dom_line_for_data('{*ts*'.$Rbase.'['.htmlspecialchars($node->tag).']['.$n.']*ts*}','ts',$node);
		//check_dom_line_for_data('{*as*'.$Rbase.'['.htmlspecialchars($node->tag).']['.$n.']*as*}','as',$node);

		$ResListCnt++;
    
    if ($node->tag==='text' || $node->tag==='comment') {
        return;
    }

    if ($count>0) {
			$Rbase .= '['.htmlspecialchars($node->tag).']['.$n.']';
		}
    $i=0;
    foreach($node->nodes as $c) {
        $last = (++$i==$count) ? true : false;
        dump_container_html_tree($Rbase, $i, $c, $show_attr, $deep+1, $last);
    }
}

function check_dom_line_for_data($pval,$type,$node)
{
	global $Configuration,$feed_row,$ResListStr;
	foreach ($Configuration["fields"] as $k=>$v)
	{
		$nm = $v['name'];
		//Text box - simple way
		if ($feed_row[$nm.'_status'] == '1') {
			$field = $feed_row[$nm.'_field'];
			$field_reg_values = array();
			$field_reg_values[] = $field;
		}
		//Area box - php parsed data
		elseif ($feed_row[$nm.'_status'] == '2') {
			$field = $feed_row[$nm.'_phpcode'];
			$field_reg_values = array();
			$field_reg_values = get_all_reg_values_from_text_box($field); //get all {*...*} from text box with code
		}
		else {
			continue;
		}
		for ($i=0; $i<count($field_reg_values); $i++)
		{
			if (pvalcmp($pval,$field_reg_values[$i])) {
//echo $pval.'='.$field_reg_values[$i]."\n";
				$ResListStr = '';
				$type = $field_reg_values[$i][2];
				$subtype = $field_reg_values[$i][3];
				switch ($type) {
					case 'c': get_node_content_c(0,$node); $ResListStr = fix_amp($ResListStr); $Configuration["comparison"][$pval] = ($subtype == 's') ? add_quote_slash($ResListStr) : $ResListStr; break;
					case 't': $Configuration["comparison"][$pval] = fix_amp(get_node_content_t($node,$subtype)); break;
					//no need
					//case 'a': $Configuration["comparison"][$pval] = fix_amp(get_node_content_a(0,$node,$subtype)); break;
				}
			}
		}
	}
}

function get_node_content_c($n, $node, $show_attr=true, $deep=0, $last=true) {
	global $ResListStr,$ResListCnt,$Rres;
    $count = count($node->nodes);
    if ($count>0) {
				$ResListStr .= '<'.htmlspecialchars($node->tag);
    }
    else {
        $laststr = ($last===false) ? '<' : '<';
        $ResListStr .= $laststr.htmlspecialchars($node->tag);
    }

    if ($show_attr) {
        foreach($node->attr as $k=>$v) {
            $ResListStr .= ' '.htmlspecialchars($k).'="'.htmlspecialchars($node->$k).'"';
        }
    }

		$ResListStr .= '>';
		$ResListCnt++;
    
    if ($node->tag==='text' || $node->tag==='comment') {
        $ResListStr .= htmlspecialchars($node->innertext).'</'.$node->tag.'>';
        return;
    }

    $i=0;
    foreach($node->nodes as $c) {
        $last = (++$i==$count) ? true : false;
        get_node_content_c($i, $c, $show_attr, $deep+1, $last);
    }

    $ResListStr .= '</'.htmlspecialchars($node->tag).'>';
}

function get_node_content_t($node,$subtype)
{
	$ResListStr = '<'.htmlspecialchars($node->tag);
	foreach($node->attr as $k=>$v) {
		$ResListStr .= ' '.htmlspecialchars($k).'="'.htmlspecialchars($node->$k).'"';
	}
	$ResListStr .= '>';
	return ($subtype == 's') ? add_quote_slash($ResListStr) : $ResListStr;
}
/*no need
function get_node_content_a($n,$node,$subtype)
{
	global $ResListStr;
	$ResListStr = '';
	get_node_content_c(0,$node); 
	$ResListStr = ($subtype == 's') ? add_quote_slash($ResListStr) : $ResListStr;
	return get_node_content_t($node,$subtype);
}
*/

//Compare current {*...*} with filed {*...*}
function pvalcmp($pval,$field)
{
	$j = 0;
	for ($i=0; $i<strlen($pval); $i++)
	{
		if (!isset($pval[$i]) || !isset($field[$j])) return false;
		if ($field[$j] == '?') {
			$j++;
			while ($i < strlen($pval))
			{
				if (($pval[$i] >= '0') && ($pval[$i] <= '9')) $i++;
				else break;
			}
		}
		if ($pval[$i] != $field[$j]) return false;
		$j++;
	}
	return true;
}

function get_all_reg_values_from_text_box($field)
{
	if ($c = preg_match_all("~\{\*.+?\*\}~si", $field, $res)) {	
		return $res[0];
	}
	return array();
}

function fill_container_based_on_tree()
{
	global $feed_row,$log_actions,$FeedName,$Result,$Configuration;
	$N = 500;
	//Fill contaner
	for ($i=0; $i<$N; $i++)
	{
		foreach ($Configuration["fields"] as $k=>$v)
		{
			$nm = $v['name'];
			$Configuration["fields"][$k]["datatmp"][$i] = '';
			//Text box - simple way
			if ($feed_row[$nm.'_status'] == '1') {
				$field = $feed_row[$nm.'_field'];
				$field = str_replace('?', $i, $field);
				if (isset($Configuration["comparison"][$field])) $Configuration["fields"][$k]["datatmp"][$i] = $Configuration["comparison"][$field];
				else continue;
			}
			//Area box - php parsed data
			elseif ($feed_row[$nm.'_status'] == '2') {
				$field = $feed_row[$nm.'_phpcode'];
				$field_reg_values = array();
				$field_reg_values = get_all_reg_values_from_text_box($field); //get all {*...*} from text box with code
				//Replace ?
				$field_reg_values_i = array();
				for ($j=0; $j<count($field_reg_values); $j++)
				{
					$field_reg_values_i[$j] = str_replace('?', $i, $field_reg_values[$j]);
				}
				//Find data
				$docontinue = false;
/*
				for ($j=0; $j<count($field_reg_values); $j++)
				{
					if (isset($Configuration["comparison"][$field_reg_values_i[$j]])) $field = str_replace($field_reg_values[$j], $Configuration["comparison"][$field_reg_values_i[$j]], $field);
					else $docontinue = true;
					if ($docontinue) break;
				}
				if ($docontinue) continue;
*/
				for ($j=0; $j<count($field_reg_values); $j++)
				{
					if (isset($Configuration["comparison"][$field_reg_values_i[$j]])) $field = str_replace($field_reg_values[$j], $Configuration["comparison"][$field_reg_values_i[$j]], $field);
					else $field = str_replace($field_reg_values[$j], "", $field);
				}

				//Just assign data, no values {*...*}, so need to calculate
				if ((count($field_reg_values) == 0) && ($field != '')) {
					$Configuration["fields"][$k]["datatmp"][$i] = $field;
					continue;
				}
				//Calculate php result
				try {
					$return_eval = eval($field);
					if ( $return_eval === false ) {
						debug_log_file($log_actions["feed_parsing"],0,2,"Cannot calculate php code for {$FeedName} feed","Cannot calculate php code in PHP parsed data for {$FeedName} feed. \nURL: $url, \nCode: ".addslashes($field)." \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
						$Configuration["fields"][$k]["datatmp"][$i] = '';
					}
					else {
						$Configuration["fields"][$k]["datatmp"][$i] = $result;
					}
				}
				catch (Exception $e) {
					debug_log_file($log_actions["feed_parsing"],0,2,"Cannot calculate php code for {$FeedName} feed","Cannot calculate php code in PHP parsed data for {$FeedName} feed. \nURL: $url, \nCode: ".addslashes($field)." \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
				}	
			}
			else {
				continue;
			}
		}
	}
	//Clear blank fields
	$fields_count = count($Configuration["fields"]) - 1;
	for ($i=0; $i<$N; $i++)
	{
		$filled_count = 0;
		foreach ($Configuration["fields"] as $k=>$v)
		{
			if ($Configuration["fields"][$k]["name"] == 'nextpage') continue;
			if ($Configuration["fields"][$k]["datatmp"][$i] != '') $filled_count++;
		}
		if ($filled_count == $fields_count) {
			foreach ($Configuration["fields"] as $k1=>$v1)
			{
				$Configuration["fields"][$k1]["data"][] = $Configuration["fields"][$k1]["datatmp"][$i];
			}
		}
	}
}

function get_url_from_tag($tag)
{
	preg_match("~(h|H)(r|R)(e|E)(f|F)\s*=[\"\'\s\\\\]*([a-zA-Z0-9_@%&:;#?=\-!%\/\.\+\,]+)~si",$tag, $matches);
	return (isset($matches[5])) ? $matches[5] : '';
}

function add_quote_slash($val)
{
	$val = str_replace('"', '\"', $val);
	$val = str_replace("'", "\'", $val);
	return $val;
}

function fix_amp($val)
{
	$val = str_replace('&amp;', '&', $val);
	return $val;
}


/**************/
/*Prepare data*/
function put_data_container()
{
	global $Configuration,$feed_row,$FeedName,$FR,$DataCollection,$DataCollectionGlobal,$log_actions,$data_collection_config,$DataListSession;
	$url = $feed_row["url"];
	$cntdata = get_max_fieldscount();
	for ($i=0; $i<$cntdata; $i++)
	{
		$data = array(
			"title"								=> prepare_insert_text_data_form_config("title",$i,97),
			"company_name"				=> prepare_insert_text_data_form_config("company_name",$i,97),
			"location"						=> prepare_insert_location_data_form_config("locId",$i),
			"description"					=> prepare_insert_text_data_form_config("description",$i,157),
			"url"									=> prepare_insert_url_data_form_config("url",$i),
			"cat_id"							=> (($feed_row["feed_format"] == 'xml2') ? find_category_by_keywords("category",$i) : $feed_row["cat_id"]),
			"job_type"						=> prepare_insert_job_type_data_form_config("job_type",$i),
			"site_type"						=> prepare_insert_site_type_data_form_config("site_type",$i),
			"isstaffing_agencies"	=> prepare_insert_bin_digit_data_form_config("isstaffing_agencies",$i),
			"salary"							=> prepare_insert_salary_data_form_config("salary",$i),
		);
		$isblank = false;

		foreach ($data as $k=>$v)
		{
			if (($k == 'location') && (($v["fixed_county"] == '') && ($v["city"] == '') && ($v["state"] == '')) ) { $isblank = true; break; }
			elseif (($k == 'cat_id') && ($v == '')) { $isblank = true; break; }
			else continue;
			if ($v == '') { $isblank = true; break; }
		}
		if ($isblank) continue;

		//Check mode
		if (isset($DataCollectionGlobal["mode"]) && ($DataCollectionGlobal["mode"] == 'check')) {
			if (find_this_job_recored($data) || find_this_session_job_recored($data)) {
				debug_log_file($log_actions["feed_parsing"],0,3,"Job ".addslashes($data['title'])." already present in our DB. {$FeedName} feed","Job ".addslashes($data['title'])." already present in our DB. {$FeedName} feed \nURL: $url, \nCategory name: {$feed_row["cat_name"]} \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
				$FR++;
			}
			else {
				$DataCollection["possible_insert"][] = $data;
				$DataListSession[] = $data;
				add_res_data($data);
				if ($data_collection_config["should_sleep_after_connect"]) usleep($data_collection_config["sleep_after_connect_timeout"]);
			}
		}
		//Real mode
		else {
			if (find_this_job_recored($data) || find_this_session_job_recored($data)) {
				debug_log_file($log_actions["feed_parsing"],0,3,"Job ".addslashes($data['title'])." already present in our DB. {$FeedName} feed","Job ".addslashes($data['title'])." already present in our DB. {$FeedName} feed \nURL: $url, \nCategory name: {$feed_row["cat_name"]} \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
				$FR++;
			}
			else {
				add_res_data($data);
				if ($data_collection_config["should_sleep_after_connect"]) usleep($data_collection_config["sleep_after_connect_timeout"]);
			}
		}
	}
}

function prepare_insert_text_data_form_config($name,$i,$maxlength)
{
	global $Configuration;
	foreach ($Configuration["fields"] as $k=>$v)
	{
		if ($v["name"] == $name) {
			return (isset($v["data"][$i])) ? addslashes(substr(convert_html_to_text(trim( $v["data"][$i] )),0,$maxlength)) : '';
		}
	}
	return '';
}

$countries_abbr_name_list = array(
	'AF'=>'Afghanistan',						'AL'=>'Albania',					'DZ'=>'Algeria',
	'AS'=>'American Samoa',					'AD'=>'Andorra',					'AO'=>'Angola',
	'AG'=>'Antigua and Barbuda',		'AR'=>'Argentina',				'AM'=>'Armenia',
	'AU'=>'Australia',							'AT'=>'Austria',					'AZ'=>'Azerbaijan',
	'BS'=>'Bahamas',								'BH'=>'Bahrain',					'BD'=>'Bangladesh',
	'BB'=>'Barbados',								'BY'=>'Belarus',					'BE'=>'Belgium',
	'BZ'=>'Belize',									'BJ'=>'Benin',						'BM'=>'Bermuda',
	'BT'=>'Bhutan',									'BO'=>'Bolivia',					'BA'=>'Bosnia and Herzegovina',
	'BW'=>'Botswana',								'BR'=>'Brazil',						'IO'=>'British Indian Ocean Territory',
	'BN'=>'Brunei Darussalam',			'BG'=>'Bulgaria',					'BF'=>'Burkina Faso',
	'BI'=>'Burundi',								'KH'=>'Cambodia',					'CM'=>'Cameroon',
	'CA'=>'Canada',									'CV'=>'Cape Verde',				'KY'=>'Cayman Islands',
	'CF'=>'Central African Republic','TD'=>'Chad',						'CL'=>'Chile',
	'CN'=>'China',									'CO'=>'Colombia',					'KM'=>'Comoros',
	'CG'=>'Congo',									'CK'=>'Cook Islands',			'CR'=>'Costa Rica',
	'CI'=>'Cote D\'Ivoire',					'HR'=>'Croatia',					'CU'=>'Cuba',
	'CY'=>'Cyprus',									'CZ'=>'Czech Republic',		'DK'=>'Denmark',
	'DJ'=>'Djibouti',								'DO'=>'Dominican Republic','TP'=>'East Timor',
	'EC'=>'Ecuador',								'EG'=>'Egypt',							'SV'=>'El Salvador',
	'GQ'=>'Equatorial Guinea',			'ER'=>'Eritrea',					'EE'=>'Estonia',
	'ET'=>'Ethiopia',								'FK'=>'Falkland Islands (Malvinas)',
	'FO'=>'Faroe Islands',					'FJ'=>'Fiji',							'FI'=>'Finland',
	'FR'=>'France',									'PF'=>'French Polynesia',	'GA'=>'Gabon',
	'GM'=>'Gambia',									'GE'=>'Georgia',					'DE'=>'Germany',
	'GH'=>'Ghana',									'GI'=>'Gibraltar',				'GR'=>'Greece',
	'GL'=>'Greenland',							'GD'=>'Grenada',					'GP'=>'Guadeloupe',
	'GU'=>'Guam',										'GT'=>'Guatemala',				'GN'=>'Guinea',
	'GW'=>'Guinea-Bissau',					'HT'=>'Haiti',						'VA'=>'Holy See (Vatican City State)',
	'HN'=>'Honduras',								'HK'=>'Hong Kong',				'HU'=>'Hungary',
	'IS'=>'Iceland',								'IN'=>'India',						'ID'=>'Indonesia',
	'IQ'=>'Iraq',										'IE'=>'Ireland',					'IR'=>'Islamic Republic Of Iran',
	'IL'=>'Israel',									'IT'=>'Italy',						'JM'=>'Jamaica',
	'JP'=>'Japan',									'JO'=>'Jordan',						'KZ'=>'Kazakhstan',
	'KE'=>'Kenya',									'KI'=>'Kiribati',					'KW'=>'Kuwait',
	'KG'=>'Kyrgyzstan',							'LA'=>'Lao People\'S Democratic Republic',
	'LV'=>'Latvia',									'LB'=>'Lebanon',					'LS'=>'Lesotho',
	'LR'=>'Liberia',								'LY'=>'Libyan Arab Jamahiriya',
	'LI'=>'Liechtenstein',					'LT'=>'Lithuania',				'LU'=>'Luxembourg',
	'MO'=>'Macao',									'MG'=>'Madagascar',				'MW'=>'Malawi',
	'MY'=>'Malaysia',								'MV'=>'Maldives',					'ML'=>'Mali',
	'MT'=>'Malta',									'MQ'=>'Martinique',				'MR'=>'Mauritania',
	'MU'=>'Mauritius',							'MX'=>'Mexico',						'MC'=>'Monaco',
	'MN'=>'Mongolia',								'MA'=>'Morocco',					'MZ'=>'Mozambique',
	'MM'=>'Myanmar',								'NA'=>'Namibia',					'NR'=>'Nauru',
	'NP'=>'Nepal',									'NL'=>'Netherlands',			'AN'=>'Netherlands Antilles',
	'NC'=>'New Caledonia',					'NZ'=>'New Zealand',			'NI'=>'Nicaragua',
	'NE'=>'Niger',									'NG'=>'Nigeria',					'MP'=>'Northern Mariana Islands',
	'NO'=>'Norway',									'OM'=>'Oman',							'PK'=>'Pakistan',
	'PW'=>'Palau',									'PS'=>'Palestinian Territory, Occupied',
	'PA'=>'Panama',									'PG'=>'Papua New Guinea',	'PY'=>'Paraguay',
	'PE'=>'Peru',										'PH'=>'Philippines',			'PL'=>'Poland',
	'PT'=>'Portugal',								'PR'=>'Puerto Rico',			'QA'=>'Qatar',
	'KR'=>'Republic Of Korea',			'MD'=>'Republic Of Moldova','RE'=>'Reunion',
	'RO'=>'Romania',								'RU'=>'Russian Federation','RW'=>'Rwanda',
	'WS'=>'Samoa',									'SM'=>'San Marino',				'ST'=>'Sao Tome and Principe',
	'SA'=>'Saudi Arabia',						'SN'=>'Senegal',					'CS'=>'Serbia and Montenegro',
	'YU'=>'Serbia and Montenegro',	'SC'=>'Seychelles',				'SL'=>'Sierra Leone',
	'SG'=>'Singapore',							'SK'=>'Slovakia',					'SI'=>'Slovenia',
	'SB'=>'Solomon Islands',				'SO'=>'Somalia',					'ZA'=>'South Africa',
	'ES'=>'Spain',									'LK'=>'Sri Lanka',				'SD'=>'Sudan',
	'SR'=>'Suriname',								'SZ'=>'Swaziland',				'SE'=>'Sweden',
	'CH'=>'Switzerland',						'SY'=>'Syrian Arab Republic','TW'=>'Taiwan',
	'TJ'=>'Tajikistan',							'TH'=>'Thailand',					'CD'=>'The Democratic Republic Of The Congo',
	'MK'=>'The Former Yugoslav Republic Of Macedonia',				'TG'=>'Togo',
	'TK'=>'Tokelau',								'TO'=>'Tonga',						'TT'=>'Trinidad and Tobago',
	'TN'=>'Tunisia',								'TR'=>'Turkey',						'TM'=>'Turkmenistan',
	'TV'=>'Tuvalu',									'UG'=>'Uganda',						'UA'=>'Ukraine',
	'AE'=>'United Arab Emirates',		'GB'=>'United Kingdom',		'TZ'=>'United Republic Of Tanzania',
	'US'=>'United States',					'UY'=>'Uruguay',					'UZ'=>'Uzbekistan',
	'VU'=>'Vanuatu',								'VE'=>'Venezuela',				'VN'=>'Viet Nam',
	'VG'=>'Virgin Islands, British','VI'=>'Virgin Islands, U.S.','EH'=>'Western Sahara',
	'YE'=>'Yemen',									'ZM'=>'Zambia',						'ZW'=>'Zimbabw');
$countries_abbr_name_list_upper = $countries_abbr_name_list;
foreach ($countries_abbr_name_list_upper as $countries_abbr_name_list_upper_k=>$countries_abbr_name_list_upper_v)
{ $countries_abbr_name_list_upper[$countries_abbr_name_list_upper_k] = strtoupper($countries_abbr_name_list_upper_v); }

$usa_canada_states = array(
	'AL'=>'ALABAMA',								'AK'=>'ALASKA',						'AZ'=>'ARIZONA',
	'AR'=>'ARKANSAS',								'CA'=>'CALIFORNIA',				'CO'=>'COLORADO',
	'CT'=>'CONNECTICUT',						'DC'=>'D.C.',							'DE'=>'DELAWARE',
	'FL'=>'FLORIDA',								'GA'=>'GEORGIA',					'HI'=>'HAWAII',
	'ID'=>'IDAHO',									'IL'=>'ILLINOIS',					'IN'=>'INDIANA',
	'IA'=>'IOWA',										'KS'=>'KANSAS',						'KY'=>'KENTUCKY',
	'LA'=>'LOUISIANA',							'ME'=>'MAINE',						'MD'=>'MARYLAND',
	'MA'=>'MASSACHUSETTS',					'MI'=>'MICHIGAN',					'MN'=>'MINNESOTA',
	'MS'=>'MISSISSIPPI',						'MO'=>'MISSOURI',					'MT'=>'MONTANA',
	'NE'=>'NEBRASKA',								'NV'=>'NEVADA',						'NH'=>'NEW HAMPSHIRE',
	'NJ'=>'NEW JERSEY',							'NM'=>'NEW MEXICO',				'NY'=>'NEW YORK',
	'NC'=>'NORTH CAROLINA',					'ND'=>'NORTH DAKOTA',			'OH'=>'OHIO',
	'OK'=>'OKLAHOMA',								'OR'=>'OREGON',						'PA'=>'PENNSYLVANIA',
	'RI'=>'RHODE ISLAND',						'SC'=>'SOUTH CAROLINA',		'SD'=>'SOUTH DAKOTA',
	'TN'=>'TENNESSEE',							'TX'=>'TEXAS',						'UT'=>'UTAH',
	'VT'=>'VERMONT',								'VA'=>'VIRGINIA',					'WA'=>'WASHINGTON',
	'WV'=>'WEST VIRGINIA',					'WI'=>'WISCONSIN',				'WY'=>'WYOMING',
	
	'AB'=>'ALBERTA',								'BC'=>'BRITISH COLUMBIA',	'MB'=>'MANITOBA',
	'NB'=>'NEW BRUNSWICK',					'NF'=>'NEWFOUNDLAND',			'NT'=>'NORTHWEST TERRITORIES',
	'NU'=>'NUNAVUT',								'NS'=>'NOVA SCOTIA',			'ON'=>'ONTARIO',
	'PE'=>'PRINCE EDWARD ISLAND',		'QC'=>'QUEBEC',						'SK'=>'SASKATCHEWAN',
	'YT'=>'YUKON');


function get_country_abbr($country)
{
	global $countries_abbr_name_list, $countries_abbr_name_list_upper;
	//2 chars - abbr
	if (strlen($country) == 2) {
		$country = strtoupper($country);
		if (isset($countries_abbr_name_list[$country])) return $country;
	}
	//many chars - name
	else {
		$country_name = array_flip($countries_abbr_name_list_upper);
		if (isset($country_name[$country])) return $country_name[$country];
	}
	return '';
}

function get_state_abbr($state)
{
	global $usa_canada_states;
	//2 chars - abbr
	if (strlen($state) == 2) {
		$state = strtoupper($state);
		if (isset($usa_canada_states[$state])) return $state;
	}
	//many chars - name
	else {
		$state_name = array_flip($usa_canada_states);
		if (isset($usa_canada_states[$state])) return $usa_canada_states[$state];
	}
	return '';
}

function prepare_insert_location_data_form_config($name,$i)
{
	global $Configuration;
	$result = array("fixed_county"=>'', "city"=>'', "state"=>'');
	foreach ($Configuration["fields"] as $k=>$v)
	{
		if ($v["name"] == $name) {
			$v["data"][$i] = convert_html_to_text(trim($v["data"][$i]));
			//$locdata = preg_split("/[,-]+/", $v["data"][$i]);
			$locdata = preg_split("/\,+/", $v["data"][$i]);
			switch (count($locdata)) {
				case 3:
					$result = array("fixed_county"=>get_country_abbr(trim($locdata[2])), "city"=>substr(trim($locdata[0]),0,40), "state"=>substr(trim($locdata[1]),0,40));
					break;
				case 2:
					$country_abbr = get_country_abbr(trim($locdata[1]));
					$state_abbr = get_state_abbr(trim($locdata[1]));
					//if we found abbr, then country, else - state(region)
					if ($state_abbr != '') $result = array("fixed_county"=>'', "city"=>substr(trim($locdata[0]),0,40), "state"=>$state_abbr);
					elseif ($country_abbr != '') $result = array("fixed_county"=>$country_abbr, "city"=>substr(trim($locdata[0]),0,40), "state"=>'');
					else $result = array("fixed_county"=>'', "city"=>substr(trim($locdata[0]),0,40), "state"=>substr(trim($locdata[1]),0,40));
					break;
				case 1:
					//if we found abbr, then country, else - city
					$abbr = get_country_abbr(trim($locdata[0]));
					if ($abbr != '') $result = array("fixed_county"=>$abbr, "city"=>'', "state"=>'');
					else $result = array("fixed_county"=>'', "city"=>substr(trim($locdata[0]),0,40), "state"=>'');
				 break;
			}
			return $result;
		}
	}
	return $result;
}

function prepare_insert_url_data_form_config($name,$i)
{
	global $Configuration;
	foreach ($Configuration["fields"] as $k=>$v)
	{
		if ($v["name"] == $name) {
			return (isset($v["data"][$i])) ? addslashes(trim( $v["data"][$i] )) : '';
		}
	}
	return '';
}

function prepare_insert_job_type_data_form_config($name,$i)
{
	global $Configuration;
	$job_types_list = array(
	 "fulltime"		=> "fulltime",
   "full-time"	=> "fulltime",
   "full time"	=> "fulltime",
   "part-time"	=> "parttime",
   "part time"	=> "parttime",
   "contract"		=> "contract",
   "contractor"	=> "contract",
   "internship"	=> "internship",
   "temporary"	=> "temporary",
   "casual"			=> "temporary",
	);
	$default_job_type = $job_types_list["fulltime"];

	foreach ($Configuration["fields"] as $k=>$v)
	{
		if ($v["name"] == $name) {
			if (!isset($v["data"][$i])) return $default_job_type;

			$str = $v["data"][$i];
			if ($str == "") return $default_job_type;
			$str = strtolower($str);

			foreach($job_types_list as $kj=>$vj)
			{
				$pos = strpos($str, $kj);
				if ($pos !== false) return $vj;
			}
		}
	}
	return $default_job_type;
}

function prepare_insert_site_type_data_form_config($name,$i)
{
	global $Configuration;
	$site_types_list = array(
	 "jobboard"		=> "jobboard",
	 "job board"	=> "jobboard",
   "employer"		=> "employer",
	);
	$default_site_type = $site_types_list["jobboard"];

	foreach ($Configuration["fields"] as $k=>$v)
	{
		if ($v["name"] == $name) {
			if (!isset($v["data"][$i])) return $default_site_type;

			$str = $v["data"][$i];
			if ($str == "") return $default_site_type;
			$str = strtolower($str);

			foreach($site_types_list as $kj=>$vj)
			{
				$pos = strpos($str, $kj);
				if ($pos !== false) return $vj;
			}
		}
	}
	return $default_site_type;
}

function prepare_insert_bin_digit_data_form_config($name,$i)
{
	global $Configuration;
	$bin_digits_list = array(0,1);
	$default_bin_digits_list = 0;
	foreach ($Configuration["fields"] as $k=>$v)
	{
		if ($v["name"] == $name) {
			if (!isset($v["data"][$i])) return $default_bin_digits_list;
			$v["data"][$i] = trim($v["data"][$i]);
			if (!in_array($v["data"][$i],$bin_digits_list)) return $default_bin_digits_list;
			return $v["data"][$i];
		}
	}
	return $default_bin_digits_list;
}

function prepare_insert_salary_data_form_config($name,$i)
{
	global $Configuration;
	foreach ($Configuration["fields"] as $k=>$v)
	{
		if ($v["name"] == $name) {
			if (!isset($v["data"][$i])) return 0;
			$v["data"][$i] = trim($v["data"][$i]);
			return understand_job_salary($v["data"][$i]);
		}
	}
	return 0;
}

//Try to correct 50,000=>50000 and 50.000=>50000
function correct_salary_by_comma($amount)
{
	$pos = strpos($amount,",000");
	if ($pos !== false) return (int)$amount*1000;
	$pos = strpos($amount,".000");
	if ($pos !== false) return (int)$amount*1000;
	if (preg_match("~(\d+)[\.,]*(\d*)[\.,]*(\d*)~si", $amount, $matches)) {
		$c = $matches[1];
		if ($matches[2] != "") $c = $c*1000+$matches[2];
		if ($matches[3] != "") $c = (float)$c.'.'.$matches[3];
		return $c;
	}
 return (int)$amount;
}

//Try to understand job salary
function understand_job_salary($salary)
{
	if ($salary == "") return 0;

	$salary = stripslashes($salary);
	$salary = strtolower($salary);

	$pos1 = strpos($salary,"-");
	$pos2 = strpos($salary,"to");
	$expr = (($pos1 !== false) || ($pos2 !== false)) ? "~(\d+[\.,]*\d*[\.,]*\d*)(k)*?\s*[\-to]+?\s*[\$]*(\d+[\.,]*\d*[\.,]*\d*)(k)*?~si" : "~(\d+[\.,]*\d*)\s*(k)*~si";
	if ( $c = preg_match($expr, $salary, $matches) ) {
		$pos = strpos($salary,"hour");
		$h = ($pos !== false) ? 8*22*12 : 1;
		$k = (isset($matches[2]) && ($matches[2] == "k")) ? 1000 : 1;
		if (($h == 1) && ($k == 1)) {
			$matches[1] = correct_salary_by_comma($matches[1]);
			if (isset($matches[3]) && ($matches[3] != "")) $matches[3] = correct_salary_by_comma($matches[3]);
		}
		if (!isset($matches[3]) || ($matches[3] == "")) $matches[3] = $matches[1];
		return ($matches[1]+round( abs($matches[3]-$matches[1])/2 ))*$h*$k;
	}
 return 0;
}

/*Get site content*/
function get_content_from_site($joburl, $method)
{
	global $log_actions, $Result, $data_collection_config;
	$url_parts = get_url_parts($my_error,$joburl);
	if ($my_error != "") {
		debug_log_file($log_actions["feed_parsing"],0,3,'Cannot get URL parts for user function "get_content_from_site"',"Cannot get URL parts for user function \"get_content_from_site\". \nURL: $joburl, \nError: $my_error \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		return 0;
	}
	debug_log_file($log_actions["feed_parsing"],1,3,'Get URL parts for user function "get_content_from_site"',"Get URL parts for user function \"get_content_from_site\". \nURL: $joburl, \nURL Parts:\n".print_r($url_parts, true)." \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);

	$method = strtolower($method);
	if ($method == 'post') $method = 'post_curl';
	else $method = 'get_curl';

	$content = get_site_content($my_error,$url_parts,$method);
	if ($data_collection_config["should_sleep_after_connect"]) usleep($data_collection_config["sleep_after_connect_timeout"]);

	if ($my_error != "") {
		debug_log_file($log_actions["feed_parsing"],0,3,'Cannot get content for user function "get_content_from_site"',"Cannot get content for user function \"get_content_from_site\". \nURL: $joburl, \nError: $my_error \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		return 0;
	}
	else {
		//Parse Page
		debug_log_file($log_actions["feed_parsing"],1,3,'Get content for user function "get_content_from_site was successful',"Get content for user function \"get_content_from_site\" was successful. \nURL: $joburl, \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		return $Result['xml_rawdata'];
	}
}

/*Try to find category id by keywords list fo xml2*/
function find_category_by_keywords($name,$i)
{
	global $Configuration,$ConfigurationCategoryKeywords;
	foreach ($Configuration["fields"] as $k=>$v)
	{
		if ($v["name"] == $name) {
			$str = strtolower(trim($v["data"][$i]));

			foreach ($ConfigurationCategoryKeywords as $k1=>$v1)
			{
				//get keywords list for current category
				if (trim($v1["keywords"]) == '') continue;
				$keywords = preg_split("/(\n|\n\r)+/", trim($v1["keywords"]));
				if (count($keywords) == 0) continue;

				for ($j=0; $j<count($keywords); $j++)
				{
					$kwd = strtolower(trim($keywords[$j]));
					if ($kwd == '') continue;
					$pos = strpos($str, $kwd);
					if ($pos !== false) {
						return $v1["cat_id"];
					}
				}
			}
		}
	}
	return '';
}



/***************/
/* Insert data */
//Return state (2 symbols), if find
function check_get_state($name, &$st)
{
 global $usa_states, $canada_provinces;
	$trans = array_flip($usa_states);
	if (isset($trans[strtoupper($name)])) { $st = $trans[strtoupper($name)]; return 1; }
	$trans = array_flip($canada_provinces);
	if (isset($trans[strtoupper($name)])) { $st = $trans[strtoupper($name)]; return 1; }
 return 0;
}

//Select state by city name
function find_state_by_city_name($city,&$st)
{
 global $db_tables;
	$st = "";
	$qr_res = mysql_query("SELECT region FROM ".$db_tables["city"]." WHERE city='".addslashes($city)."' LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		$st = $myrow["region"];
		return true;
	}
 return false;
}

function get_locstr($location)
{
	$result = array();
	foreach($location as $k=>$v)
	{
		if ($v != '') $result[] = $k;
	}
	return implode('_',$result);
}

//Search location in DB
function find_locId_in_db($location,$locstr)
{
 global $db_tables;
	//Set where for country
	switch ($locstr) {
		//Select locId by country and city and state(region)
		case 'fixed_county_city_state':
			$country_where1 = "and country='".addslashes($location["fixed_county"])."'";
			$country_where2 = "and c.country='".addslashes($location["fixed_county"])."'";
			break;
		//Select locId by city and state(region)
		case 'city_state':
			$country_where1 = "";
			$country_where2 = "";
			break;
	}

	//Try to find location
	switch ($locstr) {

		//Select locId by country and city and state(region)
		case 'fixed_county_city_state':
		//Select locId by city and state(region)
		case 'city_state':

			//Check state name
			if (strlen($location["state"]) != 2) {
				if (check_get_state($location["state"],$st)) $location["state"] = $st;
			}
			//State or region as 2 chars code
			if (strlen($location["state"]) == 2) {
				$location["state"] = strtoupper($location["state"]);
				$qr_res = mysql_query("SELECT locId FROM ".$db_tables["city"]." WHERE city='".addslashes($location["city"])."' {$country_where1} and region='".addslashes($location["state"])."' LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
				if (mysql_num_rows($qr_res) > 0) {
					$myrow = mysql_fetch_array($qr_res);
					return $myrow["locId"];
				}
				//Try to remove "city" word from $location["city"] and select again
				else {
					$location["city"] = strtolower($location["city"]);
					$pos = strpos($location["city"], "city");
					if ($pos === false) return 0;
					$location["city"] = trim(str_replace("city", "", $location["city"]));
					$qr_res = mysql_query("SELECT locId FROM ".$db_tables["city"]." WHERE city='".addslashes($location["city"])."' {$country_where1} and region='".addslashes($location["state"])."' LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
					if (mysql_num_rows($qr_res) > 0) {
						$myrow = mysql_fetch_array($qr_res);
						return $myrow["locId"];
					}
				}
			}
			//State or region as name more then 2 chars (check region table also)
			else {
				$qr_res = mysql_query("SELECT c.locId FROM ".$db_tables["city"]." c INNER JOIN ".$db_tables["region"]." r ON c.region=r.region ".
															"WHERE c.city='".addslashes($location["city"])."' {$country_where2} and r.name='".addslashes($location["state"])."' LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
				if (mysql_num_rows($qr_res) > 0) {
					$myrow = mysql_fetch_array($qr_res);
					return $myrow["locId"];
				}
				//Try to remove "city" word from $location["city"] and select again
				else {
					$location["city"] = strtolower($location["city"]);
					$pos = strpos($location["city"], "city");
					if ($pos === false) return 0;
					$location["city"] = trim(str_replace("city", "", $location["city"]));
					$qr_res = mysql_query("SELECT c.locId FROM ".$db_tables["city"]." c INNER JOIN ".$db_tables["region"]." r ON c.region=r.region ".
																"WHERE c.city='".addslashes($location["city"])."' {$country_where2} and r.name='".addslashes($location["state"])."' LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
					if (mysql_num_rows($qr_res) > 0) {
						$myrow = mysql_fetch_array($qr_res);
						return $myrow["locId"];
					}
				}
			}
			return 0;
			break;

		//Select locId by city and country
		case 'fixed_county_city':

			$qr_res = mysql_query("SELECT locId FROM ".$db_tables["city"]." WHERE city='".addslashes($location["city"])."' and country='".addslashes($location["fixed_county"])."' LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) > 0) {
				$myrow = mysql_fetch_array($qr_res);
				return $myrow["locId"];
			}
			//Try to remove "city" word from $location["city"] and select again
			else {
				$location["city"] = strtolower($location["city"]);
				$pos = strpos($location["city"], "city");
				if ($pos === false) return 0;
				$location["city"] = trim(str_replace("city", "", $location["city"]));
				$qr_res = mysql_query("SELECT locId FROM ".$db_tables["city"]." WHERE city='".addslashes($location["city"])."' and country='".addslashes($location["fixed_county"])."' LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
				if (mysql_num_rows($qr_res) > 0) {
					$myrow = mysql_fetch_array($qr_res);
					return $myrow["locId"];
				}
			}
			return 0;
			break;

		//Select locId by city name
		case 'city':

			$qr_res = mysql_query("SELECT locId FROM ".$db_tables["city"]." WHERE city='".addslashes($location["city"])."' LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) > 0) {
				$myrow = mysql_fetch_array($qr_res);
				return $myrow["locId"];
			}
			//Try to remove "city" word from $location["city"] and select again
			else {
				$location["city"] = strtolower($location["city"]);
				$pos = strpos($location["city"], "city");
				if ($pos === false) return 0;
				$location["city"] = trim(str_replace("city", "", $location["city"]));
				$qr_res = mysql_query("SELECT locId FROM ".$db_tables["city"]." WHERE city='".addslashes($location["city"])."' LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
				if (mysql_num_rows($qr_res) > 0) {
					$myrow = mysql_fetch_array($qr_res);
					return $myrow["locId"];
				}
			}
			return 0;
			break;
	}
 return 0;
}

function find_check_location_not_in_db($location,$locstr)
{
	if ($location["fixed_county"] == '') return 0;
	if (($location["city"] == '') && ($location["state"] == '')) return 0;
	return 1;
}

function find_check_job_without_location($location,$locstr)
{
	if ($location["fixed_county"] == '') return 0;
	if ($location["city"] != '') return 0;
	return 1;
}

function get_location_info($location)
{
	get_global_settings();
	$locstr = get_locstr($location);
	//City in DB?
	if ($_SESSION["globsettings"]["allow_cities_in_db"]) {
		$locId = find_locId_in_db($location,$locstr);
		if ($locId) return array('result'=>1, 'loctype'=>'cities_in_db', 'locId'=>$locId);
	}
	//City not in DB?
	if ($_SESSION["globsettings"]["allow_cities_not_in_db"]) {
		$locId = find_check_location_not_in_db($location,$locstr);
		if ($locId) return array('result'=>1, 'loctype'=>'cities_not_in_db', "fixed_county"=>$location["fixed_county"], "city"=>$location["city"], "state"=>$location["state"]);
	}
	//Job without city?
	if ($_SESSION["globsettings"]["jobs_without_city"]) {
		$locId = find_check_job_without_location($location,$locstr);
		if ($locId) return array('result'=>1, 'loctype'=>'job_without_location', "fixed_county"=>$location["fixed_county"], "city"=>'', "state"=>$location["state"]);
	}
	return array('result'=>0);
}

/*
function find_locId($location)
{
 global $db_tables;
	if (!isset($location["fixed_county"])) $location["fixed_county"] = "";
	$fc = strtoupper($location["fixed_county"]);
	switch ($fc) {
	case "IN":	case 'AD':	case 'AE':	case 'AF':	case 'AG':	case 'AI':	case 'AL':	case 'AM':
	case 'AN':	case 'AO':	case 'AP':	case 'AQ':	case 'AR':	case 'AS':	case 'AT':	case 'AU':
	case 'AW':	case 'AX':	case 'AZ':	case 'BA':	case 'BB':	case 'BD':	case 'BE':	case 'BF':
	case 'BG':	case 'BH':	case 'BI':	case 'BJ':	case 'BM':	case 'BN':	case 'BO':	case 'BR':
	case 'BS':	case 'BT':	case 'BV':	case 'BW':	case 'BY':	case 'BZ':*case 'CA':*case 'CC':
	case 'CD':	case 'CF':	case 'CG':	case 'CH':	case 'CI':	case 'CK':	case 'CL':	case 'CM':
	case 'CN':	case 'CO':	case 'CR':	case 'CU':	case 'CV':	case 'CX':	case 'CY':	case 'CZ':
	case 'DE':	case 'DJ':	case 'DK':	case 'DM':	case 'DO':	case 'DZ':	case 'EC':	case 'EE':
	case 'EG':	case 'EH':	case 'ER':	case 'ES':	case 'ET':	case 'EU':	case 'FI':	case 'FJ':
	case 'FK':	case 'FM':	case 'FO':	case 'FR':	case 'GA':	case 'GB':	case 'GD':	case 'GE':
	case 'GF':	case 'GG':	case 'GH':	case 'GI':	case 'GL':	case 'GM':	case 'GN':	case 'GP':
	case 'GQ':	case 'GR':	case 'GS':	case 'GT':	case 'GU':	case 'GW':	case 'GY':	case 'HK':
	case 'HM':	case 'HN':	case 'HR':	case 'HT':	case 'HU':	case 'ID':	case 'IE':	case 'IL':
	case 'IM':	case 'IN':	case 'IO':	case 'IQ':	case 'IR':	case 'IS':	case 'IT':	case 'JE':
	case 'JM':	case 'JO':	case 'JP':	case 'KE':	case 'KG':	case 'KH':	case 'KI':	case 'KM':
	case 'KN':	case 'KP':	case 'KR':	case 'KW':	case 'KY':	case 'KZ':	case 'LA':	case 'LB':
	case 'LC':	case 'LI':	case 'LK':	case 'LR':	case 'LS':	case 'LT':	case 'LU':	case 'LV':
	case 'LY':	case 'MA':	case 'MC':	case 'MD':	case 'ME':	case 'MG':	case 'MH':	case 'MK':
	case 'ML':	case 'MM':	case 'MN':	case 'MO':	case 'MP':	case 'MQ':	case 'MR':	case 'MS':
	case 'MT':	case 'MU':	case 'MV':	case 'MW':	case 'MX':	case 'MY':	case 'MZ':	case 'NA':
	case 'NC':	case 'NE':	case 'NF':	case 'NG':	case 'NI':	case 'NL':	case 'NO':	case 'NP':
	case 'NR':	case 'NU':	case 'NZ':	case 'O1':	case 'OM':	case 'PA':	case 'PE':	case 'PF':
	case 'PG':	case 'PH':	case 'PK':	case 'PL':	case 'PM':	case 'PR':	case 'PS':	case 'PT':
	case 'PW':	case 'PY':	case 'QA':	case 'RE':	case 'RO':	case 'RS':	case 'RU':	case 'RW':
	case 'SA':	case 'SB':	case 'SC':	case 'SD':	case 'SE':	case 'SG':	case 'SH':	case 'SI':
	case 'SJ':	case 'SK':	case 'SL':	case 'SM':	case 'SN':	case 'SO':	case 'SR':	case 'ST':
	case 'SV':	case 'SY':	case 'SZ':	case 'TC':	case 'TD':	case 'TF':	case 'TG':	case 'TH':
	case 'TJ':	case 'TK':	case 'TM':	case 'TN':	case 'TO':	case 'TR':	case 'TT':	case 'TV':
	case 'TW':	case 'TZ':	case 'UA':	case 'UG':	case 'UM':*case 'US':*case 'UY':	case 'UZ':
	case 'VA':	case 'VC':	case 'VE':	case 'VG':	case 'VI':	case 'VN':	case 'VU':	case 'WF':
	case 'WS':	case 'YE':	case 'YT':	case 'ZA':	case 'ZM':	case 'ZW':
		//Select locId by city and state
		$qr_res = mysql_query("SELECT locId FROM ".$db_tables["city"]." WHERE city='".addslashes($location["city"])."' and country='{$fc}' LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res) > 0) {
			$myrow = mysql_fetch_array($qr_res);
			return $myrow["locId"];
		}
		else { //Try to remove "city" word from $location["city"] and select again
			$location["city"] = strtolower($location["city"]);
			$pos = strpos($location["city"], "city");
			if ($pos === false) return 0;
			$location["city"] = trim(str_replace("city", "", $location["city"]));
			$qr_res = mysql_query("SELECT locId FROM ".$db_tables["city"]." WHERE city='".addslashes($location["city"])."' and country='{$fc}' LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) > 0) {
				$myrow = mysql_fetch_array($qr_res);
			return $myrow["locId"];
			}
		}
	break;
	default:
		//Check state
		if (strlen($location["state"]) == '') {
			;
		}
		elseif (strlen($location["state"]) != 2) {
			$st = "";
			if (check_get_state($location["state"],$st)) $location["state"] = $st;
			elseif (find_state_by_city_name($location["city"],$st)) $location["state"] = $st;
			else return 0;
		}
		$regionAndWhere = ($location["state"] == '') ? '' : " and region='".addslashes($location["state"])."'";
		//Select locId by city and state
		$qr_res = mysql_query("SELECT locId FROM ".$db_tables["city"]." WHERE city='".addslashes($location["city"])."' {$regionAndWhere} LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res) > 0) {
			$myrow = mysql_fetch_array($qr_res);
			return $myrow["locId"];
		}
		else { //Try to remove "city" word from $location["city"] and select again
			$location["city"] = strtolower($location["city"]);
			$pos = strpos($location["city"], "city");
			if ($pos === false) return 0;
			$location["city"] = trim(str_replace("city", "", $location["city"]));
			$qr_res = mysql_query("SELECT locId FROM ".$db_tables["city"]." WHERE city='".addslashes($location["city"])."' {$regionAndWhere} LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) > 0) {
				$myrow = mysql_fetch_array($qr_res);
			return $myrow["locId"];
			}
		}
	}
 return 0;
}
*/

function add_res_data($data,$advres=0)
{
 global $db_tables,$feed,$NewDataCnt,$log_actions,$DataCollection,$DataCollectionGlobal,$feed,$DataListSession;
	$locInfo = get_location_info($data["location"]);

	if ($locInfo['result']) {
		if ( (isset($data["do_not_validate_url"]) && $data["do_not_validate_url"]) || check_url_accessibility($data["url"]) ) {
			$data["url"] = str_replace("&amp;", "&", $data["url"]);

			if ($feed["feed_type"] == "advertiser") { //Advertiser job
				if (isset($DataCollectionGlobal["mode"]) && ($DataCollectionGlobal["mode"] == 'check'))	{
					$DataCollection["real_insert"][] = $data;
					$NewDataCnt++;
					debug_log_file($log_actions["feed_parsing"],1,2,'Job can be added',"Job can be added. title:".addslashes($data["title"]).", company: ".addslashes($data["company_name"]).", url: ".addslashes($data["url"])." \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
        	return;
				}

				try {
					//City in DB
					if ($locInfo['loctype'] == 'cities_in_db') {
						mysql_query("INSERT INTO ".$db_tables["data_list_advertiser"]." ".
							"(data_id,feed_id,title,company_name,locId,description,url,cat_id,job_type,site_type,isstaffing_agencies,salary,registered,dateinsert,".
							"country,region,city) ".
							"VALUES(NULL,'{$feed["job_ads_id"]}','".addslashes($data["title"])."', '".addslashes($data["company_name"])."',".
									"'".addslashes($locInfo['locId'])."', '".addslashes($data["description"])."',".
									"'".addslashes($data["url"])."',".
									"'".addslashes($data["cat_id"])."',".
									"'".addslashes($data["job_type"])."', '".addslashes($data["site_type"])."',".
									"'".addslashes($data["isstaffing_agencies"])."','".addslashes($data["salary"])."',NOW(),".
									"NOW(),'','','')")
								or query_not_die(__FILE__,__LINE__,mysql_error());
					}
					//City not in DB 		and   //Job without location
					elseif (($locInfo['loctype'] == 'cities_not_in_db') || ($locInfo['loctype'] == 'job_without_location')) {
						mysql_query("INSERT INTO ".$db_tables["data_list_advertiser"]." ".
							"(data_id,feed_id,title,company_name,locId,description,url,cat_id,job_type,site_type,isstaffing_agencies,salary,registered,dateinsert,".
							"country,region,city) ".
							"VALUES(NULL,'{$feed["job_ads_id"]}','".addslashes($data["title"])."', '".addslashes($data["company_name"])."',".
									"'".addslashes('0')."', '".addslashes($data["description"])."',".
									"'".addslashes($data["url"])."',".
									"'".addslashes($data["cat_id"])."',".
									"'".addslashes($data["job_type"])."', '".addslashes($data["site_type"])."',".
									"'".addslashes($data["isstaffing_agencies"])."','".addslashes($data["salary"])."',NOW(),".
									"NOW(),'".addslashes($locInfo["fixed_county"])."','".addslashes($locInfo["state"])."','".addslashes($locInfo["city"])."')")
								or query_not_die(__FILE__,__LINE__,mysql_error());
					}
				}
				catch (Exception $e) {
					debug_log_file($log_actions["feed_parsing"],0,0,"Query problem.","SQL error:".__FILE__.__LINE__.mysql_error());
				}	
			}
			else { //Commmon job
				//$source = (isset($data["source"]) && ($data["source"] != "")) ? $data["source"] : "";
				$source = "";

				if (isset($DataCollectionGlobal["mode"]) && ($DataCollectionGlobal["mode"] == 'check'))	{
					$DataCollection["real_insert"][] = $data;
					$NewDataCnt++;
					debug_log_file($log_actions["feed_parsing"],1,2,'Job can be added',"Job can be added. title:".addslashes($data["title"]).", company: ".addslashes($data["company_name"]).", url: ".addslashes($data["url"])." \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
        	return;
				}
				try {
					//City in DB
					if ($locInfo['loctype'] == 'cities_in_db') {
						mysql_query("INSERT INTO ".$db_tables["data_list"]." ".
							"(data_id,feed_id,title,company_name,locId,description,url,cat_id,job_type,site_type,isstaffing_agencies,salary,registered,source,dateinsert,".
							"country,region,city) ".
							"VALUES(NULL,'{$feed["feed_id"]}','".addslashes($data["title"])."', '".addslashes($data["company_name"])."',".
									"'".addslashes($locInfo['locId'])."', '".addslashes($data["description"])."',".
									"'".addslashes($data["url"])."',".
									"'".addslashes($data["cat_id"])."',".
									"'".addslashes($data["job_type"])."', '".addslashes($data["site_type"])."',".
									"'".addslashes($data["isstaffing_agencies"])."','".addslashes($data["salary"])."',NOW(),".
									"'".addslashes($source)."',NOW(),'','','')")
								or query_not_die(__FILE__,__LINE__,mysql_error());
					}
					//City not in DB 		and   //Job without location
					elseif (($locInfo['loctype'] == 'cities_not_in_db') || ($locInfo['loctype'] == 'job_without_location')) {
						mysql_query("INSERT INTO ".$db_tables["data_list"]." ".
							"(data_id,feed_id,title,company_name,locId,description,url,cat_id,job_type,site_type,isstaffing_agencies,salary,registered,source,dateinsert,".
							"country,region,city) ".
							"VALUES(NULL,'{$feed["feed_id"]}','".addslashes($data["title"])."', '".addslashes($data["company_name"])."',".
									"'".addslashes('0')."', '".addslashes($data["description"])."',".
									"'".addslashes($data["url"])."',".
									"'".addslashes($data["cat_id"])."',".
									"'".addslashes($data["job_type"])."', '".addslashes($data["site_type"])."',".
									"'".addslashes($data["isstaffing_agencies"])."','".addslashes($data["salary"])."',NOW(),".
									"'".addslashes($source)."',NOW(),'".addslashes($locInfo["fixed_county"])."','".addslashes($locInfo["state"])."','".addslashes($locInfo["city"])."')")
								or query_not_die(__FILE__,__LINE__,mysql_error());
					}
				}
				catch (Exception $e) {
					debug_log_file($log_actions["feed_parsing"],0,0,"Query problem.","SQL error:".__FILE__.__LINE__.mysql_error());
				}	
			}

			//Add this job to added jobs array
			$DataListSession[] = $data;

			//Count jobs
			$NewDataCnt++;
			debug_log_file($log_actions["feed_parsing"],1,2,'Job was added',"Job was added. title:".addslashes($data["title"]).", company: ".addslashes($data["company_name"]).", url: ".addslashes($data["url"])." \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
		}
		else debug_log_file($log_actions["feed_parsing"],0,2,'The page by URL is not availabile. Title: '.addslashes($data["title"]),"The page by URL ({$data["url"]}) is not availabile. Title:".addslashes($data["title"])." city: ".addslashes($data["location"]["city"])." state: ".addslashes($data["location"]["state"])." \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
	}
	else {
		debug_log_file($log_actions["feed_parsing"],0,2,'Cannot find current job location in DB. Title: '.addslashes($data["title"]),"Cannot find current job location in DB. Title:".addslashes($data["title"])." city: ".addslashes($data["location"]["city"])." state: ".addslashes($data["location"]["state"])." \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
	}
}

function add_new_res_count()
{
 global $db_tables,$feed,$NewDataCnt;
	mysql_query("INSERT INTO ".$db_tables["data_list_stats"]." (stats_data_id,feed_id,added_count,registered) ".
		"VALUES(NULL,'{$feed["feed_id"]}','$NewDataCnt',NOW())")	or query_die(__FILE__,__LINE__,mysql_error());
}

//Chech URL accessibility
function check_url_accessibility($url)
{
 global $script_dir;

	$cookie_file_path = $script_dir."/templates_c/mycookie.txt";

	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_HEADER, 1); // читать заголовок
	curl_setopt($c, CURLOPT_NOBODY, 1); // читать ТОЛЬКО заголовок без тела
	//curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);// allow redirects --> cannot be activated when in safe_mode or an open_basedir is set 
	if ( (ini_get('open_basedir') == '') && ((ini_get('safe_mode') == 'Off') || (ini_get('safe_mode') == '')) ) curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);// allow redirects 
	curl_setopt($c, CURLOPT_RETURNTRANSFER,1); // return into a variable 
	curl_setopt($c, CURLOPT_FRESH_CONNECT, 1); // не использовать cache
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($c, CURLOPT_TIMEOUT, 10); // times out after 10s
	curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($c, CURLOPT_COOKIEJAR, $cookie_file_path); 
	curl_setopt($c, CURLOPT_USERAGENT, get_random_user_agent());
	if ( (ini_get('open_basedir') == '') && ((ini_get('safe_mode') == 'Off') || (ini_get('safe_mode') == '')) ) $content = curl_exec($c); // run the whole process
	else $content = curl_redir_exec($c); // run the whole process
	//curl_exec($c);
/*
print_r(curl_getinfo($c));  
echo "\n\ncURL error number:" .curl_errno($c);  
echo "\n\ncURL error:" . curl_error($c);   
*/
	$httpcode = curl_getinfo($c, CURLINFO_HTTP_CODE);
	curl_close($c);
 return ($httpcode < 400);
}

function find_this_job_recored($data)
{
 global $db_tables,$DataList;
	if (!isset($DataList) || !isset($DataList[0]["title"])) return false;
	$cnt = count($DataList);
	for ($i=0; $i<$cnt; $i++)
	{
//		if ( ($data["title"] == $DataList[$i]["title"]) && ($data["company_name"] == $DataList[$i]["company_name"]) && ($data["url"] == $DataList[$i]["url"]) ) return true;
		//I check (strlen(url)==strlen(url)), not (url==url), because indeed RSS returns different urls for the same work during 2 queries!
		if ( ($data["title"] == $DataList[$i]["title"]) && ($data["company_name"] == $DataList[$i]["company_name"]) && (strlen($data["url"]) == strlen($DataList[$i]["url"])) ) return true;
	}
 return false;
}

function find_this_session_job_recored($data)
{
 global $db_tables,$DataListSession;
	if (!isset($DataListSession) || !isset($DataListSession[0]["title"])) return false;
	$cnt = count($DataListSession);
	for ($i=0; $i<$cnt; $i++)
	{
//		if ( ($data["title"] == $DataList[$i]["title"]) && ($data["company_name"] == $DataList[$i]["company_name"]) && ($data["url"] == $DataList[$i]["url"]) ) return true;
		//I check (strlen(url)==strlen(url)), not (url==url), because indeed RSS returns different urls for the same work during 2 queries!
		if ( ($data["title"] == $DataListSession[$i]["title"]) && ($data["company_name"] == $DataListSession[$i]["company_name"]) && ($data["location"]["city"] == $DataListSession[$i]["location"]["city"]) && (strlen($data["url"]) == strlen($DataListSession[$i]["url"])) ) return true;
	}
 return false;
}

function check_new_res_quality()
{
 global $db_tables,$feed,$NewDataCnt,$log_info,$bug_report_email,$Error_messages;
	$alert = false;
	$stats = array();
	//Get stats data list
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["data_list_stats"]." WHERE feed_id='{$feed["feed_id"]}' ORDER BY registered DESC LIMIT 7") or query_die(__FILE__,__LINE__,mysql_error());
	$num_rows = mysql_num_rows($qr_res);
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$stats[] = $myrow["added_count"];
	}
	//Check stats
	for($i=0; $i<count($stats); $i++)
	{
		if ($NewDataCnt < round($stats[$i]/2)) {
			$alert = true;
			break;
		}
	}
	//Send email notify
	if ($alert) {
		//Get feed name
		$feednm = "";
		$qr_res = mysql_query("SELECT feed_code,title FROM ".$db_tables["sites_feed_list"]." WHERE feed_id='{$feed["feed_id"]}'") or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res) > 0) {
			$myrow = mysql_fetch_array($qr_res);
			$feednm = ' Info: '.$myrow["title"].'('.$myrow["feed_code"].')';
		}
		$err_str = str_replace("{*data_cnt_now*}", $NewDataCnt, $Error_messages["small_amount_of_added_data"].$feednm);
		$err_str = str_replace("{*data_cnt_prev*}", implode(", ",$stats), $err_str);
		@mail($bug_report_email, $log_info["email_se_error_notification_subject"], $err_str);
	}
}

//Delete all html tags
function convert_html_to_text($document)
{
	if ($document == "") return "";
 	$document = preg_replace("~(<style.+?</style>)~si", "", $document);
	$search = array ("'<script[^>]*?>.*?</script>'si",  // Вырезается javascript
                   "'<[\/\!]*?[^<>]*?>'si",           // Вырезаются html-тэги
                   "'([\r\n])[\s]+'",                 // Вырезается пустое пространство
                   "'&(quot|#34);'i",                 // Замещаются html-элементы
                   "'&(amp|#38);'i",
                   "'&(lt|#60);'i",
                   "'&(gt|#62);'i",
                   "'&(nbsp|#160);'i",
                   "'&(iexcl|#161);'i",
                   "'&(cent|#162);'i",
                   "'&(pound|#163);'i",
                   "'&(copy|#169);'i",
                   "'&#(\d+);'e");                    // вычисляется как php
	$replace = array ("",
                    "",
                    "\\1",
                    "\"",
                    "&",
                    "<",
                    ">",
                    " ",
                    chr(161),
                    chr(162),
                    chr(163),
                    chr(169),
                    "chr(\\1)");
 $document = preg_replace ($search, $replace, $document);
 return str_replace("<!--", "", $document);
}



/*******************/
/* Crawl functions */

//Log error or message to file
function debug_log_file($action,$status,$detail_level,$short_message,$long_message,$additional_msg_for_file_top="")
{
 global $crawl_log_info,$log_actions_text,$CronEmailSendByFeed;
 global $DataCollectionGlobal;
	$status_num = $status;
	//Check data collection mode (do not log check mode)
	if ($DataCollectionGlobal['mode'] == 'check') {
		$status = ($status) ? '<font style="color:#00ff00">Normal</font>' : '<font style="color:#ff0000">Error</font>';
		$message = $additional_msg_for_file_top.'> Action: '.$log_actions_text[$action].' Satus: '.$status.' Detail level: '.$detail_level."\nShort message: ".$short_message."\nLong message: ".$long_message;
		$DataCollectionGlobal['messages'][] = $message;
	}
	//Log to database
	debug_log_database($action,$status_num,$detail_level,$short_message,$long_message);
	//Check Cron E-mail alert value
	if (($status_num == 0) and ($detail_level == 0)) $CronEmailSendByFeed = true;

	if (!$crawl_log_info["uselog"]) return;

  //проверка на максимальный размер
  if (is_file($crawl_log_info["file"]) AND filesize($crawl_log_info["file"])>=($crawl_log_info["maxsize"]*1024)) {
  	//проверяем настройки, если установлен лог_ротэйт,
  	//то "сдвигаем" старые файлы на один вниз и создаем пустой лог
  	//если нет - чистим и пишем вместо старого лога
  	if ($crawl_log_info["log_rotate"]===true) {
  	    $i=1;
  	    //считаем старые логи в каталоге
  	    while (is_file($crawl_log_info["file"].'.'.$i)) { $i++; }
          $i--;
  	    //у каждого из них по очереди увеличиваем номер на 1
  	    while ($i>0) {
  		   @rename($crawl_log_info["file"].'.'.$i,$crawl_log_info["file"].'.'.(1+$i--));
  	    }
  	    @rename ($crawl_log_info["file"],$crawl_log_info["file"].'.1');
  	    @touch($crawl_log_info["file"]);
  	}
  	elseif(is_file($crawl_log_info["file"])) {
  	    //если пишем логи сверху, то удалим 
  	    //и создадим заново пустой файл
  	    @unlink($crawl_log_info["file"]);
  	    @touch($crawl_log_info["file"]);
  	}
  }

  /*
  проверяем есть ли такой файл
  если нет - можем ли мы его создать
  если есть - можем ли мы писать в него
  */
  if(!is_file($crawl_log_info["file"])) {
  	if (!@touch($crawl_log_info["file"])) {
  	    trigger_error ('can\'t create log file');
  	}
  }
  elseif(!is_writable($crawl_log_info["file"])) {
  	trigger_error ('can\'t write to log file');
  }

  //обратите внимание на функцию, которой мы пишем лог.
	$status = ($status_num) ? 'Normal' : 'Error';
	$message = $additional_msg_for_file_top.'> Action: '.$log_actions_text[$action].' Satus: '.$status.' Detail level: '.$detail_level."\n Short message: ".$short_message."\n Long message: ".$long_message;
  @error_log($message."\n\n", 3, $crawl_log_info["file"]);
	@chmod($crawl_log_info["file"],0777);
}

//Log error or message to database
function debug_log_database($action,$status,$detail_level,$short_message,$long_message)
{
 global $db_tables,$feed,$NewDataCnt;
	if (!mysql_ping()) {
  	trigger_error ('Can\'t ping to MySQL. Try to reconnect.');
		doconnect();
	}
	try {
		mysql_query("INSERT INTO ".$db_tables["sites_feed_log"]." (log_id,actiontime,action,status,detail_level,short_message,long_message) ".
			"VALUES(NULL,NOW(),'$action','$status','$detail_level','".addslashes($short_message)."','".addslashes($long_message)."')")	or query_not_die(__FILE__,__LINE__,mysql_error());
	}
	catch (Exception $e) {
		debug_log_file($log_actions["feed_parsing"],0,0,"Query problem.","SQL error:".__FILE__.__LINE__.mysql_error());
	}	
}

function return_error_msg($curlerror)
{
	global $log_actions;
	debug_log_file($log_actions["feed_parsing"],0,3,'Cannot get content for function "read_form_url_get_curl"',"Cannot get content for function \"read_form_url_get_curl\". \nMsg: {$curlerror} \nFile: ".addslashes(__FILE__).' Line: '.__LINE__);
}

function send_cron_alert($title)
{
 global $db_tables,$parse_values,$usersettings,$Cron_text;
	if (function_exists('date_default_timezone_set')) date_default_timezone_set($usersettings["timezone_identifier"]);
	//Get e-mails list
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["sites_feed_alert_emials"]) or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		//----Send mail---->>
			$parse_values["{*feed_title*}"] = $title;
			$parse_values["{*message_time*}"] = date("F d, Y H:i:s");
			$admin_email = get_admin_email_free();
		//----Send mail to member (Affiliate payment request. Email to admin)
			$subj	= get_mailsubject("job_search_alert");
			$subj = str_replace("{*site_title*}",$Cron_text["site_title"],$subj);
			$htmlmessage = get_email_file("job_search_alert","html");
			$textmessage = get_email_file("job_search_alert","txt");
			$attach_files = get_mail_attach("job_search_alert");
			create_and_send_email($myrow["email"],$admin_email,$subj,$htmlmessage,$textmessage,$attach_files);
		//----Send mail----<<
	}
}
?>