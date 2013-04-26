<?
function get_period_info($stattype)
{
 global $smarty,$db_tables,$usersettings,$Error_messages,$my_error,$limitation,$having_limitation,
		$from_count,$start,$page_count,$row_count,$num,$date_from,$date_to,$text_info,$SLINE;
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
	$qr_res = mysql_query("CREATE TEMPORARY TABLE IF NOT EXISTS stats_adv_tmp (".
  	"ad_id MEDIUMINT UNSIGNED NOT NULL,".
		"ad_name VARCHAR(150) NOT NULL,".
		"ad_type SMALLINT UNSIGNED NOT NULL,".
		"clicks INT UNSIGNED NOT NULL,".
		"adviews INT UNSIGNED NOT NULL,".
		"ctr DECIMAL(12,2) UNSIGNED NOT NULL,".
		"avg_cpc DECIMAL(12,2) UNSIGNED NOT NULL,".
		"cost DECIMAL(12,2) UNSIGNED NOT NULL,".
		"avg_pos DECIMAL(12,2) UNSIGNED NOT NULL,".
		"ad_status BOOL NOT NULL)") or query_die(__FILE__,__LINE__,mysql_error());
	mysql_query("TRUNCATE stats_adv_tmp") or query_die(__FILE__,__LINE__,mysql_error());

	// * * Check cache * * //
	$cache_params_array = array(
		"user"					=> $_SESSION["sess_user"],
		"userid"				=> $_SESSION["sess_userid"],//$query_uid_adv,
		"stats_query"		=> "adv_advertisements",
		"stats_type"		=> $stattype,
		"stats_type_a"	=> $a,
		"stats_type_b"	=> $b,
		"params_list"		=> array(),
		"actual_time"		=> 1,
		"table_name"		=> "stats_adv_tmp"
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into temp stats table
	if (!read_stats_cache($cache_params_array)) {

		// * * Get stats[Keyword] data * *	//
		//Clicks and cost
		$clicks_array = array();
		$costs_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT ad_id, count(*) as clicks, sum(cost) as costs FROM ".$db_tables["stats_adv_click_keywords"]." ".
			"WHERE uid_adv='{$query_uid_adv}' and $cur_cond_line ".
			"GROUP BY ad_id")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$clicks_array[$myrow["ad_id"]] = $myrow["clicks"];
			$costs_array[$myrow["ad_id"]] = $myrow["costs"];
		}
  
		//Ad Views
		$adviews_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT ad_id, count(*) as adviews FROM ".$db_tables["stats_adv_pageview_keywords"]." ".
			"WHERE uid_adv='{$query_uid_adv}' and $cur_cond_line ".
			"GROUP BY ad_id")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$adviews_array[$myrow["ad_id"]] = $myrow["adviews"];
		}
  
		//Avg Pos
		$avg_pos_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT ad_id, count(*) as maybe_adviews, sum(page) as maybe_pages FROM ".$db_tables["stats_adv_maybe_pageview_keywords"]." ".
			"WHERE uid_adv='{$query_uid_adv}' and $cur_cond_line ".
			"GROUP BY ad_id")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$avg_pos_array[$myrow["ad_id"]] = $myrow["maybe_pages"]/$myrow["maybe_adviews"];
		}
  
		//Main select from Ads
		$num = 0;
		$qr_res = mysql_query("SELECT ad_id,ad_name,status FROM ".$db_tables["ads"]." ".
			"WHERE uid_adv='{$query_uid_adv}'")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			//Calculate data
			$ctr = calc_adv_ctr($clicks_array,$adviews_array,$myrow["ad_id"]);
			$avg_cpc = calc_adv_avg_cpc($costs_array,$clicks_array,$myrow["ad_id"]);
			$clicks = calc_adv_clicks($clicks_array,$myrow["ad_id"]);
			$adviews = calc_adv_adviews($adviews_array,$myrow["ad_id"]);
			$costs = calc_adv_costs($costs_array,$myrow["ad_id"]);
			$avg_pos = calc_adv_avg_pos($avg_pos_array,$myrow["ad_id"]);
  
			//Insert data[Keyword]
			mysql_query("INSERT INTO stats_adv_tmp(ad_id,ad_name,ad_type,clicks,adviews,ctr,avg_cpc,cost,avg_pos,ad_status) ".
				"VALUES ('{$myrow["ad_id"]}','{$myrow["ad_name"]}', '1', '$clicks', '$adviews',".
				"'$ctr', '$avg_cpc', '$costs', '$avg_pos', '{$myrow["status"]}')") or query_die(__FILE__,__LINE__,mysql_error());
  
			$num++;
		}


		// * * Get stats[Job] data * *	//
		//Clicks and cost
		$clicks_array = array();
		$costs_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT job_ads_id, count(*) as clicks, sum(cost) as costs FROM ".$db_tables["stats_adv_click_jobs"]." ".
			"WHERE uid_adv='{$query_uid_adv}' and $cur_cond_line ".
			"GROUP BY job_ads_id")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$clicks_array[$myrow["job_ads_id"]] = $myrow["clicks"];
			$costs_array[$myrow["job_ads_id"]] = $myrow["costs"];
		}
  
		//Ad Views
		$adviews_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT job_ads_id, count(*) as adviews FROM ".$db_tables["stats_adv_pageview_jobs"]." ".
			"WHERE uid_adv='{$query_uid_adv}' and $cur_cond_line ".
			"GROUP BY job_ads_id")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$adviews_array[$myrow["job_ads_id"]] = $myrow["adviews"];
		}
  
		//Avg Pos
		$avg_pos_array = array();
		$cur_cond_line = str_replace("{*field*}", "actiontime", $cond_line);
		$qr_res = mysql_query("SELECT job_ads_id, count(*) as maybe_adviews, sum(page) as maybe_pages FROM ".$db_tables["stats_adv_maybe_pageview_jobs"]." ".
			"WHERE uid_adv='{$query_uid_adv}' and $cur_cond_line ".
			"GROUP BY job_ads_id")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$avg_pos_array[$myrow["job_ads_id"]] = $myrow["maybe_pages"]/$myrow["maybe_adviews"];
		}
  
		//Main select from Ads
		$qr_res = mysql_query("SELECT job_ads_id,ad_name,status FROM ".$db_tables["job_ads"]." ".
			"WHERE uid_adv='{$query_uid_adv}'")	or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			//Calculate data
			$ctr = calc_adv_ctr($clicks_array,$adviews_array,$myrow["job_ads_id"]);
			$avg_cpc = calc_adv_avg_cpc($costs_array,$clicks_array,$myrow["job_ads_id"]);
			$clicks = calc_adv_clicks($clicks_array,$myrow["job_ads_id"]);
			$adviews = calc_adv_adviews($adviews_array,$myrow["job_ads_id"]);
			$costs = calc_adv_costs($costs_array,$myrow["job_ads_id"]);
			$avg_pos = calc_adv_avg_pos($avg_pos_array,$myrow["job_ads_id"]);
			//Insert data[Job]
			mysql_query("INSERT INTO stats_adv_tmp(ad_id,ad_name,ad_type,clicks,adviews,ctr,avg_cpc,cost,avg_pos,ad_status) ".
				"VALUES ('{$myrow["job_ads_id"]}','{$myrow["ad_name"]}', '2', '$clicks', '$adviews',".
				"'$ctr', '$avg_cpc', '$costs', '$avg_pos', '{$myrow["status"]}')") or query_die(__FILE__,__LINE__,mysql_error());
  
			$num++;
		}

	}


	// - - - - - - - - - - //
	// * * Get content * * //

	//Table of content [Records]
	$DataBody = array();
	$page_count = get_page_count("SELECT count(*) as num FROM stats_adv_tmp ".$limitation,$row_count);
//	$page_count = ceil($num/$row_count);
	if ($start > ($page_count-1)) $start = $page_count-1;
	if ($start < 0) $start = 0;
	$from_count = $start*$row_count;

	$num  = 0;
	$qr_res = mysql_query("SELECT * FROM stats_adv_tmp  ".
		" $limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
			or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		//Set status and change status link
		switch ($myrow["ad_status"]) {
			case "0":
				$ad_status = $text_info["f_Disable"];
				$led_onoff = get_img("ledoff.gif",20,20,$text_info["c_chstatus"],get_js_action(8));
				$str_link_chstatus = '<a href="adv_advertisements.php?action=chstatus&type='.$myrow["ad_type"].'&ad_id='.$myrow["ad_id"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_ch_ad_status"].'\')">'.$led_onoff.'</a>';
				break;
			case "1":
				$ad_status = $text_info["f_Active"];
				$led_onoff = get_img("ledon.gif",20,20,$text_info["c_chstatus"],get_js_action(7));
				$str_link_chstatus = '<a href="adv_advertisements.php?action=chstatus&type='.$myrow["ad_type"].'&ad_id='.$myrow["ad_id"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_ch_ad_status"].'\')">'.$led_onoff.'</a>';
				break;
			case "2":
				$ad_status = $text_info["f_Pending"];
				$str_link_chstatus = '<img height="10" src="images/spacer.gif" width="20" alt="" border="0" />';
				break;
			default: $ad_status = 'Unknown'; $str_link_chstatus = '<img height="10" src="images/spacer.gif" width="20" alt="" border="0" />';
		}

		//Set Ad type
		if ($myrow["ad_type"] == 1) $ad_type = $text_info["f_Keyword_Ad"];
		else $ad_type = $text_info["f_Sponsored_Jobs"];

		//Set other links
		$str_link_edit = create_adv_str_link_edit($myrow["ad_type"],$myrow["ad_id"]);
		$str_link_del = '<a href="adv_advertisements.php?action=delete&type='.$myrow["ad_type"].'&ad_id='.$myrow["ad_id"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_delete_ad"].'\')">'.get_img("reject.gif",20,20,$text_info["c_delete"],get_js_action(2)).'</a>';
		$str_link_report = create_adv_str_link_report($myrow["ad_type"],$myrow["ad_id"]);

		$DataBody[$num] = array(
			array("tdw"=>"55", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
			array("tdw"=>"120","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["ad_name"]),
			array("tdw"=>"128","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$ad_type),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["clicks"] ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["adviews"]),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["ctr"]).'%' ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["avg_cpc"]).'%' ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["cost"]) ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["avg_pos"]) ),
			array("tdw"=>"75", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$ad_status),
			array("tdw"=>"100","tdclass"=>"tbl_td_head_nowrap","tdalign"=>"","data"=>$str_link_edit.'&nbsp;'.$str_link_report.'&nbsp;'.$str_link_chstatus.'&nbsp;'.$str_link_del)
		);
		$num++;
	}

	if ($num > 0) {
		//Table of content [Total]
		$qr_res = mysql_query("SELECT sum(clicks) as clicks, sum(adviews) as adviews, sum(ctr) as ctr, sum(avg_cpc) as avg_cpc,".
			"sum(cost) as cost, avg(avg_pos) as avg_pos FROM stats_adv_tmp  ".
			" $limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
				or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$DataBody[$num] = array(
				array("tdw"=>"55", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$text_info["p_total"]),
				array("tdw"=>"120","tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'),
				array("tdw"=>"128","tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%d", $myrow["clicks"])),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%d", $myrow["adviews"])),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["ctr"]).'%' ),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["avg_cpc"]).'%' ),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["cost"]) ),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["avg_pos"]) ),
				array("tdw"=>"75", "tdclass"=>"tbl_td_data_total","tdalign"=>"","data"=>'&nbsp;'),
				array("tdw"=>"100","tdclass"=>"tbl_td_head","tdalign"=>"","data"=>'&nbsp;')
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

function create_adv_str_link_edit($type,$id)
{
 global $SLINE, $text_info, $uid_adv;
	$uid_adv_str = (isset($uid_adv) && ($uid_adv != "")) ? "&uid_adv=".$uid_adv : "";
	if ($type == 1)
		return '<a href="adv_advertisement_keyword_ad.php?action=edit&ad_id='.$id.$uid_adv_str.'&'.$SLINE.'">'.get_img("infedit.gif",20,20,$text_info["c_einfo"],get_js_action(6)).'</a>';
	else
		return '<a href="adv_advertisement_jobs_from_my_site_work.php?action=edit&job_ads_id='.$id.$uid_adv_str.'&'.$SLINE.'">'.get_img("infedit.gif",20,20,$text_info["c_einfo"],get_js_action(6)).'</a>';
}

function create_adv_str_link_report($type,$id)
{
 global $SLINE, $text_info, $uid_adv;
	$uid_adv_str = (isset($uid_adv) && ($uid_adv != "")) ? "&uid_adv=".$uid_adv : "";
	if ($type == 1)
		return '<a href="adv_advertisement_report_keyword.php?ad_id='.$id.$uid_adv_str.'&'.$SLINE.'">'.get_img("arrow.gif",20,20,$text_info["c_ad_report"],get_js_action(10)).'</a>';
	else
		return '<a href="adv_advertisement_report_job.php?job_ads_id='.$id.$uid_adv_str.'&'.$SLINE.'">'.get_img("arrow.gif",20,20,$text_info["c_ad_report"],get_js_action(10)).'</a>';
}
?>