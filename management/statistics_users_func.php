<?
function get_users_amount()
{
 global $db_tables;
	$ua = array();
	$qr_res = mysql_query("SELECT count(*) as amount, EXTRACT(YEAR_MONTH FROM regdate) as ym ".
		"FROM ".$db_tables["users_advertiser"]." GROUP BY ym") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$ua[$myrow["ym"]] = $myrow["amount"];
	}
	$qr_res = mysql_query("SELECT count(*) as amount, EXTRACT(YEAR_MONTH FROM regdate) as ym ".
		"FROM ".$db_tables["users_publisher"]." GROUP BY ym") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		if (isset($ua[$myrow["ym"]]))	$ua[$myrow["ym"]] += $myrow["amount"];
		else $ua[$myrow["ym"]] = $myrow["amount"];
	}
 return $ua;
}

function get_search_amount()
{
 global $db_tables;
	$sa = array();
	$qr_res = mysql_query("SELECT count(*) as amount, EXTRACT(YEAR_MONTH FROM searchtime) as ym ".
		"FROM ".$db_tables["stats_search_keywords"]." GROUP BY ym") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$sa[$myrow["ym"]] = $myrow["amount"];
	}
 return $sa;
}

function get_clicks_amount()
{
 global $db_tables;
	$ca = array();
	$qr_res = mysql_query("SELECT count(*) as amount, EXTRACT(YEAR_MONTH FROM clicktime) as ym ".
		"FROM ".$db_tables["stats_clicks"]." GROUP BY ym") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$ca[$myrow["ym"]] = $myrow["amount"];
	}
 return $ca;
}

function get_earn_money()
{
 global $db_tables;
	$em = array();
	$qr_res = mysql_query("SELECT sum(amount) as amount, EXTRACT(YEAR_MONTH FROM actiontime) as ym ".
		"FROM ".$db_tables["stats_pub_earn_clicks"]." GROUP BY ym") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$em[$myrow["ym"]] = $myrow["amount"];
	}
 return $em;
}
?>