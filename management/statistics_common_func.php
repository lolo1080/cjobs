<?
function get_period_info($stattype)
{
 global $smarty,$db_tables,$usersettings,$Error_messages,$my_error,$limitation,$having_limitation,
		$from_count,$start,$page_count,$row_count,$num,$date_from,$date_to,$text_info,$SLINE,$ad_id;

	if ($stattype == "") $stattype = "d";
	if (($stattype == "d") || ($stattype == "w") || ($stattype == "m") || ($stattype == "y")) {
		switch ($stattype) {
			case "d": $a = 0; $b = 1; break;
			case "w": $a = 0; $b = 7; break;
			case "m":	$a = 0; $b = 30; break;
			case "y":	$a = 0; $b = 365; break;
		}
		$cond_line = "TO_DAYS(DATE_SUB(NOW(), INTERVAL $a DAY))>=TO_DAYS({*field*}) and TO_DAYS(DATE_SUB(NOW(), INTERVAL $b DAY))<=TO_DAYS({*field*})";
		$report_date["type"] = "1";
		$report_date["sql"] = "SELECT DATE_FORMAT(DATE_SUB(NOW(), INTERVAL $a DAY),'%Y-%m-%d') as date_end, DATE_FORMAT(DATE_SUB(NOW(), INTERVAL $b DAY), '%Y-%m-%d') as date_start";
	}
	elseif ($stattype == "a") {
		$cond_line = "1=1";
		$report_date["type"] = "2";
		$report_date["sql"] = "SELECT DATE_FORMAT(actiontime, '%Y-%m-%d') as date_start, DATE_FORMAT(NOW(), '%Y-%m-%d') as date_end FROM ".$db_tables["stats_adv_maybe_pageview_keywords"]." ORDER BY actiontime ASC LIMIT 1";
	}
	elseif ($stattype == "c") {
		if (!check_date($date_from)) $my_error .= $Error_messages["from_date_invalid"];
		else $a = get_mysql_date($date_from,$my_error);
		if (!check_date($date_to)) $my_error .= $Error_messages["to_date_invalid"];
		else $b = get_mysql_date($date_to,$my_error);

		if ($my_error != "") {
			$smarty->assign("error",true);
			$smarty->assign("iimgmane","abort.gif");
			$smarty->assign("imessage",$my_error);
			//Set necessary values
			$smarty->assign("DataBodyCount",0);
			$smarty->assign("DataBody",array());
			return;
		}
		$cond_line = "'$a'<={*field*} and '$b'>={*field*}";
		$report_date["type"] = "3";
		$report_date["sql"] = "SELECT DATE_FORMAT(DATE_SUB('$a', INTERVAL 0 DAY), '%Y-%m-%d') as date_start, DATE_FORMAT(DATE_SUB('$b', INTERVAL 0 DAY), '%Y-%m-%d') as date_end";
	}
	if (!isset($a)) $a = "";
	if (!isset($b)) $b = "";


	// - - - - - - - - - - - -//
	// * * Get statistics * * //

	//Create temporary table
	$qr_res = mysql_query("CREATE TEMPORARY TABLE IF NOT EXISTS stats_report_commmon_tmp (".
		"actiondate DATE NOT NULL,".
		"visitor_total INT UNSIGNED NOT NULL,".
		"visitor_unique_ip INT UNSIGNED NOT NULL,".
		"search INT UNSIGNED NOT NULL,".
		"clicks INT UNSIGNED NOT NULL,".
		"adviews_keywords INT UNSIGNED NOT NULL,".
		"adviews_jobs INT UNSIGNED NOT NULL,".
		"adclicks_keywords INT UNSIGNED NOT NULL,".
		"adclicks_jobs INT UNSIGNED NOT NULL,".
		"pub_views INT UNSIGNED NOT NULL,".
		"pub_clicks INT UNSIGNED NOT NULL,".
		"pub_earn_money DECIMAL(12,2) UNSIGNED NOT NULL)") or query_die(__FILE__,__LINE__,mysql_error());
	mysql_query("TRUNCATE stats_report_commmon_tmp") or query_die(__FILE__,__LINE__,mysql_error());

	// * * Check cache * * //
	$cache_params_array = array(
		"user"					=> $_SESSION["sess_user"],
		"userid"				=> $_SESSION["sess_userid"],
		"stats_query"		=> "admin_stats_report_commmon_tmp",
		"stats_type"		=> $stattype,
		"stats_type_a"	=> $a,
		"stats_type_b"	=> $b,
		"params_list"		=> array(),
		"table_name"		=> "stats_report_commmon_tmp"
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into temp stats table
	if (!read_stats_cache($cache_params_array)) {

		// * * Get stats[Common] data * *	//
		//Visitor total, Visitor unique ip
		$visitor_total_array = array();
		$visitor_unique_ip_array = array();
		$cur_cond_line = str_replace("{*field*}", "entertime", $cond_line);
		$qr_res = mysql_query("SELECT count(*) as visitor_total, count(DISTINCT ip) as visitor_unique_ip, DATE_FORMAT(entertime, '%Y-%m-%d') as actiondate ".
 			"FROM ".$db_tables["stats_visitor_info"]." ".
			"WHERE 1=1 and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$visitor_total_array[$myrow["actiondate"]] = $myrow["visitor_total"];
			$visitor_unique_ip_array[$myrow["actiondate"]] = $myrow["visitor_unique_ip"];
		}

		//Search total
		$search_array = array();
		$cur_cond_line = str_replace("{*field*}", "searchtime", $cond_line);
		$qr_res = mysql_query("SELECT count(*) as search, DATE_FORMAT(searchtime, '%Y-%m-%d') as actiondate ".
			"FROM ".$db_tables["stats_search_keywords"]." ".
			"WHERE 1=1 and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$search_array[$myrow["actiondate"]] = $myrow["search"];
		}

		//Clicks total
		$clicks_array = array();
		$cur_cond_line = str_replace("{*field*}", "clicktime", $cond_line);
		$qr_res = mysql_query("SELECT count(*) as clicks, DATE_FORMAT(clicktime, '%Y-%m-%d') as actiondate ".
			"FROM ".$db_tables["stats_clicks"]." ".
			"WHERE 1=1 and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$clicks_array[$myrow["actiondate"]] = $myrow["clicks"];
		}

		//Advertisements pageview: keywords ad
		$adviews_keywords_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT count(*) as adviews_keywords, DATE_FORMAT(actiontime, '%Y-%m-%d') as actiondate ".
			"FROM ".$db_tables["stats_adv_pageview_keywords"]." ".
			"WHERE 1=1 and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$adviews_keywords_array[$myrow["actiondate"]] = $myrow["adviews_keywords"];
		}

		//Advertisements pageview: jobs ad
		$adviews_jobs_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT count(*) as adviews_jobs, DATE_FORMAT(actiontime, '%Y-%m-%d') as actiondate ".
			"FROM ".$db_tables["stats_adv_pageview_jobs"]." ".
			"WHERE 1=1 and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$adviews_jobs_array[$myrow["actiondate"]] = $myrow["adviews_jobs"];
		}

		//Advertisements clicks: keywords ad
		$adclicks_keywords_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT count(*) as adclicks_keywords, DATE_FORMAT(actiontime, '%Y-%m-%d') as actiondate ".
			"FROM ".$db_tables["stats_adv_click_keywords"]." ".
			"WHERE 1=1 and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$adclicks_keywords_array[$myrow["actiondate"]] = $myrow["adclicks_keywords"];
		}

		//Advertisements clicks: jobs ad
		$adclicks_jobs_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT count(*) as adclicks_jobs, DATE_FORMAT(actiontime, '%Y-%m-%d') as actiondate ".
			"FROM ".$db_tables["stats_adv_click_jobs"]." ".
			"WHERE 1=1 and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$adclicks_jobs_array[$myrow["actiondate"]] = $myrow["adclicks_jobs"];
		}

		//Publisher Traffic Summary: pageviews
		$pub_views_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT count(*) as pub_views, DATE_FORMAT(actiontime, '%Y-%m-%d') as actiondate ".
			"FROM ".$db_tables["stats_pub_pageview"]." ".
			"WHERE 1=1 and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$pub_views_array[$myrow["actiondate"]] = $myrow["pub_views"];
		}

		//Publisher Traffic Summary: clicks
		$pub_clicks_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT count(*) as pub_clicks, DATE_FORMAT(actiontime, '%Y-%m-%d') as actiondate ".
			"FROM ".$db_tables["stats_pub_click_keywords"]." ".
			"WHERE 1=1 and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$pub_clicks_array[$myrow["actiondate"]] = $myrow["pub_clicks"];
		}

		//Publisher Traffic Summary: earn money
		$pub_earn_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT sum(amount) as pub_earn, DATE_FORMAT(actiontime, '%Y-%m-%d') as actiondate ".
			"FROM ".$db_tables["stats_pub_earn_clicks"]." ".
			"WHERE 1=1 and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$pub_earn_array[$myrow["actiondate"]] = $myrow["pub_earn"];
		}

		//Main select: create dataes list
		$num = 0;
		if (isset($report_date["type"])) {
			$qr_res = mysql_query($report_date["sql"]) or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) > 0) {
				$myrow = mysql_fetch_array($qr_res);
				$date_start = explode("-",$myrow["date_start"]);
				$date_end = explode("-",$myrow["date_end"]);
				if (function_exists('date_default_timezone_set')) date_default_timezone_set($usersettings["timezone_identifier"]);
				$i_cur_date = mktime(0,0,0, $date_start[1],$date_start[2],$date_start[0]);
				$i_end_date = mktime(0,0,0, $date_end[1],$date_end[2],$date_end[0]);

				while ($i_cur_date <= $i_end_date)
				{
					//Calculate data
					$i_cur_date_str = date("Y-m-d", $i_cur_date);
					$visitor_total = calc_data_amount($visitor_total_array,$i_cur_date_str);
					$visitor_unique_ip = calc_data_amount($visitor_unique_ip_array,$i_cur_date_str);
					$search = calc_data_amount($search_array,$i_cur_date_str);
					$clicks = calc_data_amount($clicks_array,$i_cur_date_str);
					$adviews_keywords = calc_data_amount($adviews_keywords_array,$i_cur_date_str);
					$adviews_jobs = calc_data_amount($adviews_jobs_array,$i_cur_date_str);
					$adclicks_keywords = calc_data_amount($adclicks_keywords_array,$i_cur_date_str);
					$adclicks_jobs = calc_data_amount($adclicks_jobs_array,$i_cur_date_str);
					$pub_views = calc_data_amount($pub_views_array,$i_cur_date_str);
					$pub_clicks = calc_data_amount($pub_clicks_array,$i_cur_date_str);
					$pub_earn_money = calc_data_amount($pub_earn_array,$i_cur_date_str);

					//Insert data[Keyword]
					$i_cur_date_sql = date("Y-m-d", $i_cur_date);

					mysql_query("INSERT INTO stats_report_commmon_tmp(actiondate,visitor_total,visitor_unique_ip,search,clicks,".
							"adviews_keywords,adviews_jobs,adclicks_keywords,adclicks_jobs,pub_views,pub_clicks,pub_earn_money) ".
						"VALUES ('$i_cur_date_sql','$visitor_total','$visitor_unique_ip','$search','$clicks','$adviews_keywords',".
							"'$adviews_jobs','$adclicks_keywords','$adclicks_jobs','$pub_views','$pub_clicks','$pub_earn_money')") or query_die(__FILE__,__LINE__,mysql_error());
					$num++;
					$i_cur_date += 60 * 60 * 24;
				}
			}
		}
	}

	// - - - - - - - - - - //
	// * * Get content * * //

	//Table of content [Records]
	$DataBody = array();
	$page_count = get_page_count("SELECT count(*) as num FROM stats_report_commmon_tmp ".$limitation,$row_count);
//	$page_count = ceil($num/$row_count);
	if ($start > ($page_count-1)) $start = $page_count-1;
	if ($start < 0) $start = 0;
	$from_count = $start*$row_count;

	$num  = 0;
	$qr_res = mysql_query("SELECT *,actiondate as datesort,".format_sql_date("actiondate")." as actiondate FROM stats_report_commmon_tmp  ".
		" $limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
			or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$DataBody[$num] = array(
			array("tdw"=>"55", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["actiondate"]),
			array("tdw"=>"100","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["visitor_total"].'&nbsp;/&nbsp;'.$myrow["visitor_unique_ip"] ),
			array("tdw"=>"90", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["search"] ),
			array("tdw"=>"90", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["clicks"] ),
			array("tdw"=>"150","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["adviews_keywords"].'&nbsp;/&nbsp;'.$myrow["adviews_jobs"] ),
			array("tdw"=>"130","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["adclicks_keywords"].'&nbsp;/&nbsp;'.$myrow["adclicks_jobs"] ),
			array("tdw"=>"160","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["pub_views"].'&nbsp;/&nbsp;'.$myrow["pub_clicks"].'&nbsp;/&nbsp;'.sprintf("%01.2f",$myrow["pub_earn_money"]) )
		);
		$num++;
	}

	if ($num > 0) {
		//Table of content [Total]
		$qr_res = mysql_query("SELECT sum(visitor_total) as visitor_total, sum(visitor_unique_ip) as visitor_unique_ip, ".
			"sum(search) as search, sum(clicks) as clicks, sum(adviews_keywords) as adviews_keywords, sum(adviews_jobs) as adviews_jobs,".
			"sum(adclicks_keywords) as adclicks_keywords, sum(adclicks_jobs) as adclicks_jobs, sum(pub_views) as pub_views, ".
			"sum(pub_clicks) as pub_clicks, sum(pub_earn_money) as pub_earn_money ".
			"FROM stats_report_commmon_tmp ".
			" $limitation ") 
				or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$DataBody[$num] = array(
				array("tdw"=>"55", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$text_info["p_total"]),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'),
				array("tdw"=>"100","tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.$myrow["visitor_total"].'&nbsp;/&nbsp;'.$myrow["visitor_unique_ip"]),
				array("tdw"=>"90", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.$myrow["search"]),
				array("tdw"=>"90", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.$myrow["clicks"]),
				array("tdw"=>"150","tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.$myrow["adviews_keywords"].'&nbsp;/&nbsp;'.$myrow["adclicks_jobs"]),
				array("tdw"=>"130","tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.$myrow["adclicks_keywords"].'&nbsp;/&nbsp;'.$myrow["adclicks_jobs"] ),
				array("tdw"=>"160","tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.$myrow["pub_views"].'&nbsp;/&nbsp;'.$myrow["pub_clicks"].'&nbsp;/&nbsp;'.sprintf("%01.2f",$myrow["pub_earn_money"]) )
			);
			$num++;
		}
	}

	$smarty->assign("DataBodyCount",$num);
	$smarty->assign("DataBody",$DataBody);

	// * * Write cache * * //
	//if use cache - save stats
	write_stats_cache($cache_params_array);
}
?>