<?
function get_period_info($stattype)
{
 global $smarty,$db_tables,$usersettings,$Error_messages,$my_error,$limitation,$having_limitation,
		$from_count,$start,$page_count,$row_count,$num,$date_from,$date_to,$text_info,$SLINE,$channel_id;
	global $uid_pub;

	//Check for admin access
	if (($_SESSION["sess_user"] == "0") && ($uid_pub != "")) $query_uid_pub = $uid_pub;
	else $query_uid_pub = $_SESSION["sess_userid"];


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
		$report_date["sql"] = "SELECT DATE_FORMAT(actiontime, '%Y-%m-%d') as date_start, DATE_FORMAT(NOW(), '%Y-%m-%d') as date_end FROM ".$db_tables["stats_pub_pageview"]." ORDER BY actiontime ASC LIMIT 1";
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

	//Add chanel id to condition line
	if ( ($channel_id != "") && ($channel_id != 0) ) $cond_line .= " and channel_id='$channel_id' ";

	// - - - - - - - - - - - -//
	// * * Get statistics * * //

	//Create temporary table
	$qr_res = mysql_query("CREATE TEMPORARY TABLE IF NOT EXISTS stats_traffic_summary_pub_tmp (".
		"actiondate DATE NOT NULL,".
		"clicks INT UNSIGNED NOT NULL,".
		"pubviews INT UNSIGNED NOT NULL,".
		"earnclicks INT UNSIGNED NOT NULL,".
		"ctr DECIMAL(12,2) UNSIGNED NOT NULL,".
		"cpm DECIMAL(12,2) UNSIGNED NOT NULL,".
		"earn DECIMAL(12,2) UNSIGNED NOT NULL)") or query_die(__FILE__,__LINE__,mysql_error());
	mysql_query("TRUNCATE  stats_traffic_summary_pub_tmp") or query_die(__FILE__,__LINE__,mysql_error());

	// * * Check cache * * //
	$cache_params_array = array(
		"user"					=> $_SESSION["sess_user"],
		"userid"				=> $query_uid_pub,
		"stats_query"		=> "pub_traffic_summary",
		"stats_type"		=> $stattype,
		"stats_type_a"	=> $a,
		"stats_type_b"	=> $b,
		"params_list"		=> array($channel_id),
		"table_name"		=> "stats_traffic_summary_pub_tmp"
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into temp stats table
	if (!read_stats_cache($cache_params_array)) {

		// * * Get pub traffic summary data * *	//
		//Clicks
		$clicks_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT count(*) as clicks, DATE_FORMAT(actiontime, '%Y-%m-%d') as actiondate ".
 			"FROM ".$db_tables["stats_pub_click_keywords"]." ".
			"WHERE uid_pub='{$query_uid_pub}' and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$clicks_array[$myrow["actiondate"]] = $myrow["clicks"];
		}

		//Pub Views
		$pubviews_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT count(*) as pubviews, DATE_FORMAT(actiontime, '%Y-%m-%d') as actiondate ".
			"FROM ".$db_tables["stats_pub_pageview"]." ".
			"WHERE uid_pub='{$query_uid_pub}' and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$pubviews_array[$myrow["actiondate"]] = $myrow["pubviews"];
		}

		//Earn Clicks and Earn money
		$earnclicks_array = array();
		$earn_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT count(*) as earnclicks, sum(amount) as earn, DATE_FORMAT(actiontime, '%Y-%m-%d') as actiondate ".
			"FROM ".$db_tables["stats_pub_earn_clicks"]." ".
			"WHERE uid_pub='{$query_uid_pub}' and $cur_cond_line ".
			"GROUP BY actiondate")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$earnclicks_array[$myrow["actiondate"]] = $myrow["earnclicks"];
			$earn_array[$myrow["actiondate"]] = $myrow["earn"];
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
					$ctr = calc_adv_ctr($clicks_array,$pubviews_array,$i_cur_date_str);
					$clicks = calc_adv_clicks($clicks_array,$i_cur_date_str);
					$pubviews = calc_adv_adviews($pubviews_array,$i_cur_date_str);
					$earnclicks = calc_pub_earnclicks($earnclicks_array,$i_cur_date_str);
					$earn = calc_pub_earn($earn_array,$i_cur_date_str);
					$cmp = calc_pub_cmp($earn,$pubviews,$i_cur_date_str);

					//Insert data[pub traffic summary]
					$i_cur_date_sql = date("Y-m-d", $i_cur_date);
					mysql_query("INSERT INTO stats_traffic_summary_pub_tmp(actiondate,clicks,pubviews,earnclicks,ctr,cpm,earn) ".
						"VALUES ('$i_cur_date_sql', '$clicks', '$pubviews', '$earnclicks', '$ctr', '$cmp', '$earn')") or query_die(__FILE__,__LINE__,mysql_error());
   	
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
	$page_count = get_page_count("SELECT count(*) as num FROM stats_traffic_summary_pub_tmp ".$limitation,$row_count);
//	$page_count = ceil($num/$row_count);
	if ($start > ($page_count-1)) $start = $page_count-1;
	if ($start < 0) $start = 0;
	$from_count = $start*$row_count;

	$num  = 0;
	$qr_res = mysql_query("SELECT *,actiondate as datesort,".format_sql_date("actiondate")." as actiondate FROM stats_traffic_summary_pub_tmp  ".
		" $limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
			or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$DataBody[$num] = array(
			array("tdw"=>"55", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["actiondate"]),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["clicks"] ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["pubviews"]),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["earnclicks"]),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["ctr"]).'%' ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["cpm"]) ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["earn"]) )
		);
		$num++;
	}

	if ($num > 0) {
		//Table of content [Total]
		$qr_res = mysql_query("SELECT sum(clicks) as clicks, sum(pubviews) as pubviews, sum(earnclicks) as earnclicks, sum(ctr) as ctr, ".
			"sum(cpm) as cpm, sum(earn) as earn FROM stats_traffic_summary_pub_tmp ".
			" $limitation LIMIT $from_count, $row_count") 
				or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$DataBody[$num] = array(
				array("tdw"=>"55", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$text_info["p_total"]),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%d", $myrow["clicks"])),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%d", $myrow["pubviews"])),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%d", $myrow["earnclicks"])),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["ctr"]).'%' ),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["cpm"]) ),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["earn"]) )
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