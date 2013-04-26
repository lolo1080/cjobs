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
	}
	elseif ($stattype == "a") $cond_line = "1=1";
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
	}
	if (!isset($a)) $a = "";
	if (!isset($b)) $b = "";


	// - - - - - - - - - - - -//
	// * * Get statistics * * //

	//Create temporary table
	$qr_res = mysql_query("CREATE TEMPORARY TABLE IF NOT EXISTS stats_keyword_ads_tmp (".
  	"kads_id MEDIUMINT UNSIGNED NOT NULL,".
		"keyword CHAR(100) NOT NULL,".
		"soptions SMALLINT UNSIGNED NOT NULL,".
		"kads_status BOOL NOT NULL,".
		"clicks INT UNSIGNED NOT NULL,".
		"adviews INT UNSIGNED NOT NULL,".
		"ctr DECIMAL(12,2) UNSIGNED NOT NULL,".
		"avg_cpc DECIMAL(12,2) UNSIGNED NOT NULL,".
		"cost DECIMAL(12,2) UNSIGNED NOT NULL,".
		"avg_pos DECIMAL(12,2) UNSIGNED NOT NULL)") or query_die(__FILE__,__LINE__,mysql_error());
	mysql_query("TRUNCATE stats_keyword_ads_tmp") or query_die(__FILE__,__LINE__,mysql_error());

	// * * Check cache * * //
	$cache_params_array = array(
		"user"					=> $_SESSION["sess_user"],
		"userid"				=> $_SESSION["sess_userid"],
		"stats_query"		=> "adv_advertisement_keyword_ad",
		"stats_type"		=> $stattype,
		"stats_type_a"	=> $a,
		"stats_type_b"	=> $b,
		"params_list"		=> array($ad_id),
		"table_name"		=> "stats_keyword_ads_tmp"
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into temp stats table
	if (!read_stats_cache($cache_params_array)) {

		// * * Get stats data * *	//
		//Clicks and cost
		$clicks_array = array();
		$costs_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT kads_id, count(*) as clicks, sum(cost) as costs FROM ".$db_tables["stats_adv_click_keywords"]." ".
			"WHERE ad_id='$ad_id' and $cur_cond_line ".
			"GROUP BY kads_id")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$clicks_array[$myrow["kads_id"]] = $myrow["clicks"];
			$costs_array[$myrow["kads_id"]] = $myrow["costs"];
		}
  
		//Ad Views
		$adviews_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT kads_id, count(*) as adviews FROM ".$db_tables["stats_adv_pageview_keywords"]." ".
			"WHERE ad_id='$ad_id' and $cur_cond_line ".
			"GROUP BY kads_id")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$adviews_array[$myrow["kads_id"]] = $myrow["adviews"];
		}
  
		//Avg Pos
		$avg_pos_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT kads_id, count(*) as maybe_adviews, sum(page) as maybe_pages FROM ".$db_tables["stats_adv_maybe_pageview_keywords"]." ".
			"WHERE ad_id='$ad_id' and $cur_cond_line ".
			"GROUP BY kads_id")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$avg_pos_array[$myrow["kads_id"]] = $myrow["maybe_pages"]/$myrow["maybe_adviews"];
		}
  
		//Main select from Ads
		$num = 0;
		$qr_res = mysql_query("SELECT kads_id,kads_status,soptions,keyword FROM ".$db_tables["keyword_ads"]." ".
			"WHERE ad_id='$ad_id'")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			//Calculate data
			$ctr = calc_adv_ctr($clicks_array,$adviews_array,$myrow["kads_id"]);
			$avg_cpc = calc_adv_avg_cpc($costs_array,$clicks_array,$myrow["kads_id"]);
			$clicks = calc_adv_clicks($clicks_array,$myrow["kads_id"]);
			$adviews = calc_adv_adviews($adviews_array,$myrow["kads_id"]);
			$costs = calc_adv_costs($costs_array,$myrow["kads_id"]);
			$avg_pos = calc_adv_avg_pos($avg_pos_array,$myrow["kads_id"]);
  
			//Insert data
			mysql_query("INSERT INTO stats_keyword_ads_tmp(kads_id,keyword,soptions,kads_status,clicks,adviews,ctr,avg_cpc,cost,avg_pos) ".
				"VALUES ('{$myrow["kads_id"]}','{$myrow["keyword"]}', '{$myrow["soptions"]}', '{$myrow["kads_status"]}', '$clicks',".
				"'$adviews', '$ctr', '$avg_cpc', '$costs', '$avg_pos')") or query_die(__FILE__,__LINE__,mysql_error());
			$num++;
		}
	}


	// - - - - - - - - - - //
	// * * Get content * * //

	//Table of content [Records]
	$DataBody = array();
	$page_count = get_page_count("SELECT count(*) as num FROM stats_keyword_ads_tmp ".$limitation,$row_count);
//	$page_count = ceil($num/$row_count);
	if ($start > ($page_count-1)) $start = $page_count-1;
	if ($start < 0) $start = 0;
	$from_count = $start*$row_count;

	$num  = 0;
	$qr_res = mysql_query("SELECT * FROM stats_keyword_ads_tmp  ".
		" $limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
			or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		//Set status and change status link
		if ($myrow["kads_status"]) {
			$led_onoff = get_img("ledon.gif",20,20,$text_info["c_chstatus"],get_js_action(7));
			$kads_status = $text_info["f_Active"];
		}
		else {
			$led_onoff = get_img("ledoff.gif",20,20,$text_info["c_chstatus"],get_js_action(8));
			$kads_status = $text_info["f_Disable"];
		}
		switch ($myrow["soptions"]) {
			case "1":	$soptions = $text_info["f_broad_m"]; break;
			case "2":	$soptions = $text_info["f_exact_m"]; break;
			case "3":	$soptions = $text_info["f_phrase_m"]; break;
			case "4":	$soptions = $text_info["f_negative_m"]; break;
			default: $soptions = "unknown";
		}

		//Set other links
		$str_link_chstatus = '<a href="adv_advertisement_keyword_ad.php?action=chstatus&ad_id='.$ad_id.'&kads_id='.$myrow["kads_id"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_ch_keyword_status"].'\')">'.$led_onoff.'</a>';
		$str_link_del = '<a href="adv_advertisement_keyword_ad.php?action=delete&ad_id='.$ad_id.'&kads_id='.$myrow["kads_id"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_delete_keyword"].'\')">'.get_img("reject.gif",20,20,$text_info["c_delete"],get_js_action(2)).'</a>';

		$DataBody[$num] = array(
			array("tdw"=>"55", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
			array("tdw"=>"180","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["keyword"]),
			array("tdw"=>"100","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$soptions),
			array("tdw"=>"85", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$kads_status),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["clicks"] ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["adviews"]),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["ctr"]).'%' ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["avg_cpc"]).'%' ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["cost"]) ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["avg_pos"]) ),
			array("tdw"=>"60", "tdclass"=>"tbl_td_head_nowrap","tdalign"=>"","data"=>$str_link_chstatus.'&nbsp;'.$str_link_del)
		);
		$num++;
	}

	if ($num > 0) {
		//Table of content [Total]
		$qr_res = mysql_query("SELECT sum(clicks) as clicks, sum(adviews) as adviews, sum(ctr) as ctr, sum(avg_cpc) as avg_cpc,".
			"sum(cost) as cost, sum(avg_pos) as avg_pos FROM stats_keyword_ads_tmp  ".
			" $limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
				or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$DataBody[$num] = array(
				array("tdw"=>"55", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$text_info["p_total"]),
				array("tdw"=>"180","tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'),
				array("tdw"=>"100","tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'),
				array("tdw"=>"85", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%d", $myrow["clicks"])),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%d", $myrow["adviews"])),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["ctr"]).'%' ),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["avg_cpc"]).'%' ),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["cost"]) ),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["avg_pos"]) ),
				array("tdw"=>"60", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>'&nbsp;')
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