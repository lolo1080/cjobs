<?
function get_period_info($stattype)
{
 global $smarty,$db_tables,$usersettings,$Error_messages,$my_error,$limitation,$having_limitation,
		$from_count,$start,$page_count,$row_count,$num,$date_from,$date_to,$text_info,$SLINE,$job_ads_id;
	global $uid_adv;

	//Check for admin access
	if (($_SESSION["sess_user"] == "0") && ($uid_adv != "")) $query_uid_adv = $uid_adv;
	else $query_uid_adv = $_SESSION["sess_userid"];

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
		$report_date["sql"] = "SELECT DATE_FORMAT(actiontime, '%Y-%m-%d') as date_start, DATE_FORMAT(NOW(), '%Y-%m-%d') as date_end FROM ".$db_tables["stats_adv_maybe_pageview_jobs"]." ORDER BY actiontime ASC LIMIT 1";
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
	$qr_res = mysql_query("CREATE TEMPORARY TABLE IF NOT EXISTS stats_report_job_ads_tmp (".
		"actiondate DATE NOT NULL,".
		"clicks INT UNSIGNED NOT NULL,".
		"adviews INT UNSIGNED NOT NULL,".
		"ctr DECIMAL(12,2) UNSIGNED NOT NULL,".
		"avg_cpc DECIMAL(12,2) UNSIGNED NOT NULL,".
		"cost DECIMAL(12,2) UNSIGNED NOT NULL,".
		"avg_pos DECIMAL(12,2) UNSIGNED NOT NULL)") or query_die(__FILE__,__LINE__,mysql_error());
	mysql_query("TRUNCATE stats_report_job_ads_tmp") or query_die(__FILE__,__LINE__,mysql_error());

	// * * Check cache * * //
	$cache_params_array = array(
		"user"					=> $_SESSION["sess_user"],
		"userid"				=> $query_uid_adv,
		"stats_query"		=> "adv_stats_report_job_ads",
		"stats_type"		=> $stattype,
		"stats_type_a"	=> $a,
		"stats_type_b"	=> $b,
		"params_list"		=> array($job_ads_id),
		"table_name"		=> "stats_report_job_ads_tmp"
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into temp stats table
	if (!read_stats_cache($cache_params_array)) {

		// * * Get stats[Job] data * *	//
		//Clicks and cost
		$clicks_array = array();
		$costs_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT count(*) as clicks, sum(cost) as costs, DATE_FORMAT(actiontime, '%Y-%m-%d') as actiondate ".
 			"FROM ".$db_tables["stats_adv_click_jobs"]." ".
			"WHERE uid_adv='{$query_uid_adv}' and job_ads_id='$job_ads_id' and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$clicks_array[$myrow["actiondate"]] = $myrow["clicks"];
			$costs_array[$myrow["actiondate"]] = $myrow["costs"];
		}

		//Ad Views
		$adviews_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT count(*) as adviews, DATE_FORMAT(actiontime, '%Y-%m-%d') as actiondate ".
			"FROM ".$db_tables["stats_adv_pageview_jobs"]." ".
			"WHERE uid_adv='{$query_uid_adv}' and job_ads_id='$job_ads_id' and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$adviews_array[$myrow["actiondate"]] = $myrow["adviews"];
		}
  
		//Avg Pos
		$avg_pos_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT count(*) as maybe_adviews, sum(page) as maybe_pages, DATE_FORMAT(actiontime, '%Y-%m-%d') as actiondate ".
			"FROM ".$db_tables["stats_adv_maybe_pageview_jobs"]." ".
			"WHERE uid_adv='{$query_uid_adv}' and job_ads_id='$job_ads_id' and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$avg_pos_array[$myrow["actiondate"]] = $myrow["maybe_pages"]/$myrow["maybe_adviews"];
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
					$ctr = calc_adv_ctr($clicks_array,$adviews_array,$i_cur_date_str);
					$avg_cpc = calc_adv_avg_cpc($costs_array,$clicks_array,$i_cur_date_str);
					$clicks = calc_adv_clicks($clicks_array,$i_cur_date_str);
					$adviews = calc_adv_adviews($adviews_array,$i_cur_date_str);
					$costs = calc_adv_costs($costs_array,$i_cur_date_str);
					$avg_pos = calc_adv_avg_pos($avg_pos_array,$i_cur_date_str);

					//Insert data[Keyword]
					$i_cur_date_sql = date("Y-m-d", $i_cur_date);
					mysql_query("INSERT INTO stats_report_job_ads_tmp(actiondate,clicks,adviews,ctr,avg_cpc,cost,avg_pos) ".
						"VALUES ('$i_cur_date_sql', '$clicks', '$adviews', '$ctr', '$avg_cpc', '$costs', '$avg_pos')") or query_die(__FILE__,__LINE__,mysql_error());
   	
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
	$page_count = get_page_count("SELECT count(*) as num FROM stats_report_job_ads_tmp ".$limitation,$row_count);
//	$page_count = ceil($num/$row_count);
	if ($start > ($page_count-1)) $start = $page_count-1;
	if ($start < 0) $start = 0;
	$from_count = $start*$row_count;

	$num  = 0;
	$qr_res = mysql_query("SELECT *,actiondate as datesort,".format_sql_date("actiondate")." as actiondate FROM stats_report_job_ads_tmp  ".
		" $limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
			or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$DataBody[$num] = array(
			array("tdw"=>"55", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["actiondate"]),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["clicks"] ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["adviews"]),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["ctr"]).'%' ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["avg_cpc"]).'%' ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["cost"]) ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["avg_pos"]) )
		);
		$num++;
	}

	if ($num > 0) {
		//Table of content [Total]
/*
		$qr_res = mysql_query("SELECT sum(clicks) as clicks, sum(adviews) as adviews, sum(ctr) as ctr, sum(avg_cpc) as avg_cpc,".
			"sum(cost) as cost, sum(avg_pos) as avg_pos FROM stats_report_job_ads_tmp ".
			" $limitation LIMIT $from_count, $row_count") 
				or query_die(__FILE__,__LINE__,mysql_error());
*/
		$qr_res = mysql_query("SELECT sum(clicks) as clicks, sum(adviews) as adviews, sum(cost) as cost, avg(avg_pos) as avg_pos FROM stats_report_job_ads_tmp ".
			" $limitation ") 
				or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$DataBody[$num] = array(
				array("tdw"=>"55", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$text_info["p_total"]),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%d", $myrow["clicks"])),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%d", $myrow["adviews"])),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", safe_division($myrow["clicks"],$myrow["adviews"])*100).'%' ),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", safe_division($myrow["cost"],$myrow["clicks"])*100).'%' ),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["cost"]) ),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", (($limitation == "") ? get_total_avg_pos($cond_line,$query_uid_adv,$job_ads_id) : $myrow["avg_pos"]) ) )
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

function get_total_avg_pos($cond_line,$query_uid_adv,$job_ads_id)
{
 global $db_tables;
	//Avg Pos
	$maybe_pages = $maybe_adviews = 0;
	$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
	$qr_res = mysql_query("SELECT count(*) as maybe_adviews, sum(page) as maybe_pages, DATE_FORMAT(actiontime, '%Y-%m-%d') as actiondate ".
		"FROM ".$db_tables["stats_adv_maybe_pageview_jobs"]." ".
		"WHERE uid_adv='{$query_uid_adv}' and job_ads_id='$job_ads_id' and $cur_cond_line ".
		"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$maybe_pages += $myrow["maybe_pages"];
		$maybe_adviews += $myrow["maybe_adviews"];
	}
 return safe_division($maybe_pages,$maybe_adviews);
}
?>
