<?
function get_selectbox_data($yes_no_array,$default_value)
{
  $val = $sel = $capt = array();
	foreach ($yes_no_array as $v=>$c) {
		$val[] = $v;
		$sel[] = ($v == $default_value) ? "selected" : "";
		$capt[] = $c;
	}
 return array("val"=>$val, "sel"=>$sel, "capt"=>$capt);
}

//Get countries list -->>
function get_countries_selectbox()
{
 global $db_tables;
  $val = $sel = $capt = array();
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["country"]." ORDER BY cname") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$val[] = $myrow["country_code2"];
		$sel[] = ($myrow["active"]) ? "selected" : "";
		$capt[] = $myrow["cname"];
	}
 return array("val"=>$val, "sel"=>$sel, "capt"=>$capt);
}
function get_country_selectbox_data($country_id)
{
 global $db_tables;
  $val = $sel = $capt = array();
	$qr_res = mysql_query("SELECT cid,cname FROM ".$db_tables["country"]." ORDER BY cname") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$val[] = $myrow["cid"];
		$sel[] = ($myrow["cid"] == $country_id) ? "selected" : "";
		$capt[] = $myrow["cname"];
	}
 return array("val"=>$val, "sel"=>$sel, "capt"=>$capt);
}
function get_country_jobroll_list()
{
 global $db_tables;
  $result = array();
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["country"]." ORDER BY cname") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$result[] = array("value"=>$myrow["country_code2"], "caption"=>$myrow["cname"]);
	}
 return $result;
}
//Get countries list <<--

function get_advertisers_selectbox_data($uid_adv)
{
 global $db_tables;
  $val = $sel = $capt = array();
	$qr_res = mysql_query("SELECT uid_adv,name,email FROM ".$db_tables["users_advertiser"]." ORDER BY name,email") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$val[] = $myrow["uid_adv"];
		$sel[] = ($myrow["uid_adv"] == $uid_adv) ? "selected" : "";
		$capt[] = $myrow["name"].' ('.$myrow["email"].')';
	}
 return array("val"=>$val, "sel"=>$sel, "capt"=>$capt);
}

function get_job_ads_selectbox_data($job_ads_id)
{
 global $db_tables,$text_info;
  $val = $sel = $capt = array();
	$qr_res = mysql_query("SELECT j.job_ads_id,j.ad_name,j.status, u.name,u.email ".
												"FROM ".$db_tables["job_ads"]." j ".
												"INNER JOIN ".$db_tables["users_advertiser"]." u ON j.uid_adv=u.uid_adv ".
												"ORDER BY j.ad_name,u.name") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$val[] = $myrow["job_ads_id"];
		$sel[] = ($myrow["job_ads_id"] == $job_ads_id) ? "selected" : "";
		switch ($myrow["status"]) {
			case '0': $status = $text_info["f_Disable"]; break;
			case '1': $status = $text_info["f_Active"]; break;
			case '2': $status = $text_info["f_Pending"]; break;
		}
		$capt[] = $myrow["ad_name"].' ('.$myrow["name"].', '.$myrow["email"].') - '.$status;
	}
 return array("val"=>$val, "sel"=>$sel, "capt"=>$capt);
}

//Get job channels list
function get_job_channel_list()
{
 global $db_tables, $text_info;
	$result = array();
  $result[] = array("value"=>0, "caption"=>$text_info["p_job_channel_none"]);
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["users_publisher_channels"]." WHERE uid_pub='{$_SESSION["sess_userid"]}' ORDER BY name") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$result[] = array("value"=>$myrow["channel_id"], "caption"=>$myrow["name"]);
	}
 return $result;
}
function get_std_job_channel_list($channel_id)
{
 global $db_tables, $text_info;
  $val[] = ""; $sel[] = "" ; $capt[] = $text_info["c_all"];
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["users_publisher_channels"]." WHERE uid_pub='{$_SESSION["sess_userid"]}' ORDER BY name") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$val[] = $myrow["channel_id"];
		$sel[] = ($myrow["channel_id"] == $channel_id) ? "selected" : "";
		$capt[] = $myrow["name"];
	}
 return array("val"=>$val, "sel"=>$sel, "capt"=>$capt);
}


function get_jobroll_colors($add_quote)
{
 $result = "";
	$colors = get_jobroll_settings();
	foreach ($colors as $k=>$v)
	{
		if ($add_quote) $result[] = '"'.$v.'"';
		else $result[] = $v;
	}
 return implode(',', $result);
}


function is_this_user_current_ad($ad_id,$userid)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT ad_name FROM ".$db_tables["ads"]." WHERE ad_id='$ad_id' and uid_adv='$userid'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) return true;
 return false;
}

function is_this_user_current_job_ad($job_ads_id,$userid)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT ad_name FROM ".$db_tables["job_ads"]." WHERE job_ads_id='$job_ads_id' and uid_adv='$userid'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) return true;
 return false;
}


function is_this_user_current_keyword($kads_id,$userid)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT k.ad_status FROM ".$db_tables["keyword_ads"]." k ".
		"INNER JOIN ".$db_tables["ads"]." a ON a.ad_id=k.ad_id and a.uid_adv='$userid' ".
		"WHERE kads_id='$kads_id'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) return true;
 return false;
}

function is_this_user_payment_info($pid,$userid)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT pid FROM ".$db_tables["payments_adv"]." WHERE pid='$pid' and uid_adv='$userid'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) return true;
 return false;
}

function is_this_user_payment_request($pid,$userid)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT pid FROM ".$db_tables["payments_pub"]." WHERE pid='$pid' and uid_pub='$userid'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) return true;
 return false;
}

function get_ad_name($ad_id)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT ad_name FROM ".$db_tables["ads"]." WHERE ad_id='$ad_id'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
	$myrow = mysql_fetch_array($qr_res);
 return $myrow["ad_name"];
}

function get_job_ad_name($job_ads_id)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT ad_name FROM ".$db_tables["job_ads"]." WHERE job_ads_id='$job_ads_id'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
	$myrow = mysql_fetch_array($qr_res);
 return $myrow["ad_name"];
}

function find_period($period,&$period_val,&$period_sel)
{
	$period_val = array("d","w","m","y","a","c");
	$period_sel = array();
	for ($i=0; $i<count($period_val); $i++) {
		$period_sel[$i] = ($period_val[$i] == $period) ? "selected" : "";
	}
}

function create_report_date($year,$month,$day)
{
 global $usersettings;
	$mydate = $usersettings["dateformat"];
	$mydate = str_replace("%y", $year, $mydate);
	$mydate = str_replace("%m", $month, $mydate);
	$mydate = str_replace("%d", $day, $mydate);
 return $mydate;
}

//Stats functions -->
function calc_adv_ctr(&$clicks_array,&$adviews_array,$id)
{
	return (isset($clicks_array[$id]) && isset($adviews_array[$id])) ? $clicks_array[$id]/$adviews_array[$id]*100 : 0;
}

function calc_adv_avg_cpc(&$costs_array,&$clicks_array,$id)
{
	return (isset($costs_array[$id]) && isset($clicks_array[$id])) ? $costs_array[$id]/$clicks_array[$id]*100 : 0;
}

function calc_adv_clicks(&$clicks_array,$id)
{
	return (isset($clicks_array[$id])) ? $clicks_array[$id] : 0;
}

function calc_adv_adviews(&$adviews_array,$id)
{
	return (isset($adviews_array[$id])) ? $adviews_array[$id] : 0;
}

function calc_adv_costs(&$costs_array,$id)
{
	return (isset($costs_array[$id])) ? $costs_array[$id] : 0;
}

function calc_adv_avg_pos(&$avg_pos_array,$id)
{
	return (isset($avg_pos_array[$id])) ? $avg_pos_array[$id] : 0;
}

function calc_pub_earnclicks(&$earnclicks_array,$id)
{
	return (isset($earnclicks_array[$id])) ? $earnclicks_array[$id] : 0;
}

function calc_pub_earn(&$earn_array,$id)
{
	return (isset($earn_array[$id])) ? $earn_array[$id] : 0;
}

function calc_pub_cmp($earn,$pubviews)
{
	return ($pubviews > 0) ? $earn*1000/$pubviews : 0;
}

function safe_division($a,$b)
{
	if ($b == 0) return 0;
	else return $a/$b;
}

function calc_data_amount(&$data_array,$id)
{
	return (isset($data_array[$id])) ? $data_array[$id] : 0;
}
// <--

function get_months_selectbox($ename,$estyle,$jscipt,$cc_expiration_month)
{
 global $month_array;
	$res = '<select name="'.$ename.'" id="'.$ename.'" style="'.$estyle.'" '.$jscipt.'>';
	foreach ($month_array as $k=>$v)
	{
		if ($k == $cc_expiration_month) $selected = 'selected';
		else $selected = '';
		$res .= '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
	}
 return $res.'</select>';
}
function get_yeas_selectbox($ename,$estyle,$jscipt,$cc_expiration_year)
{
 global $usersettings;
	if (function_exists('date_default_timezone_set')) date_default_timezone_set($usersettings["timezone_identifier"]);
	$res = '<select name="'.$ename.'" id="'.$ename.'" style="'.$estyle.'" '.$jscipt.'>';
	for ($i=0; $i<11; $i++)
	{
		$year4 = date("Y", mktime(0,0,0,date("m"),date("d"),date("Y")+$i));
		$year2 = substr($year4,-2);
		if ($year2 == $cc_expiration_year) $selected = 'selected';
		else $selected = '';
		$res .= '<option value="'.$year2.'" '.$selected.'>'.$year4.'</option>';
	}
 return $res.'</select>';
}

function get_member_main_info($table,$uid_field_name,$uid_field_value)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT * FROM $table WHERE $uid_field_name='$uid_field_value'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return array("result"=>1, "email"=>$myrow["email"], "name"=>$myrow["name"], "site"=>$myrow["site"], "balance"=>$myrow["balance"]);
	}
 return array("result"=>0);
}

function get_member_main_info2($table,$uid_field_name,$uid_field_value)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT * FROM $table WHERE $uid_field_name='$uid_field_value'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return array("result"=>1, "email"=>$myrow["email"], "first_name"=>$myrow["first_name"]);
	}
 return array("result"=>0);
}

function get_stored_cc_data_by_id($sccid)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["payments_adv_stored_cc"]." WHERE sccid='$sccid' and uid_adv='{$_SESSION["sess_userid"]}'") or logfile("error",__FILE__,__LINE__,__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return array("result"=>1, "cc_number"=>$myrow["cc_number"], "payinfo"=>$myrow["payinfo"]);
	}
 return array("result"=>0);
}

function get_tepl_diskname_by_id($template_id)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT diskname FROM ".$db_tables["templates"]." WHERE template_id='$template_id'") or logfile("error",__FILE__,__LINE__,__FILE__,__LINE__,"MySQL Error. ".mysql_error()."\n");
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return $myrow["diskname"];
	}
 return "";
}

function get_feed_name_by_id($feed_id)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["sites_feed_list"]." WHERE feed_id='$feed_id'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return $myrow["title"];
	}
	return '';
}

function find_adv_email_in_db($email)
{
 global $db_tables;
	$email = data_addslashes($email);
	$qr_res = mysql_query("SELECT uid_adv FROM ".$db_tables["users_advertiser"]." WHERE email='$email'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) return true;
 return false;
}

function find_pub_email_in_db($email)
{
 global $db_tables;
	$email = data_addslashes($email);
	$qr_res = mysql_query("SELECT uid_pub FROM ".$db_tables["users_publisher"]." WHERE email='$email'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) return true;
 return false;
}

function find_mem_email_in_db($email)
{
 global $db_tables;
	$email = data_addslashes($email);
	$qr_res = mysql_query("SELECT uid_mem FROM ".$db_tables["users_member"]." WHERE email='$email'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) return true;
 return false;
}

function check_user_subm_script()
{
	if (isset($_SESSION["sess_user_subm_type"])) {
		$type = $_SESSION["sess_user_subm_type"];
		unset($_SESSION["sess_user_subm_type"]);
		header("Location: subm_users.php?type={$type["type"]}&{$SLINE}");
		exit;
	}
 return false;
}

function check_user_xmlfeedsubm_script()
{
	if (isset($_SESSION["sess_xmlfeed_subm_type"])) {
		unset($_SESSION["sess_xmlfeed_subm_type"]);
		header("Location: subm_xmlfeed.php?{$SLINE}");
		exit;
	}
 return false;
}

function get_advertiser_name_by_id($uid_adv)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT name FROM ".$db_tables["users_advertiser"]." WHERE uid_adv='$uid_adv' and isdeleted=0") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return $myrow["name"];
	}
 return '';
}

function get_publisher_name_by_id($uid_pub)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT name FROM ".$db_tables["users_publisher"]." WHERE uid_pub='$uid_pub' and isdeleted=0") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return $myrow["name"];
	}
 return '';
}

function get_ap_login_info($table,$email)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT email,pass FROM $table WHERE email='$email'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return array("result"=>true, "email"=>$myrow["email"], "password"=>$myrow["pass"]);
	}
 return array("result"=>false);
}

function get_countries_code_list()
{
 global $db_tables;
  $val = array();
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["country"]." ORDER BY cname") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$val[] = $myrow["country_code2"];
	}
 return $val;
}

function find_other_emails_in_db($sel_field,$sel_table,$condition,$email)
{
	$email = data_addslashes($email);
	$qr_res = mysql_query("SELECT $sel_field FROM $sel_table WHERE email='$email' $condition") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) return true;
 return false;
}

function this_user_job_alert($uid_mem,$ja_id)
{
 global $db_tables;
	$ja_id = data_addslashes($ja_id);
	$qr_res = mysql_query("SELECT ja_id FROM ".$db_tables["member_job_alerts"]." WHERE ja_id='$ja_id' and uid_mem='$uid_mem'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) return true;
 return false;
}

function check_job_category($job_category)
{
 global $db_tables;
	$job_category = data_addslashes($job_category);
	$qr_res = mysql_query("SELECT cat_id FROM ".$db_tables["jobcategories"]." WHERE cat_id='$job_category'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) return true;
 return false;
}

function get_job_categories_list()
{
 global $db_tables;
	$val = array();
	$qr_res = mysql_query("SELECT cat_id,cat_name,cat_key FROM ".$db_tables["jobcategories"]." ORDER BY cat_name") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$val[$myrow["cat_id"]] = $myrow["cat_name"];
	}
 return $val;
}
?>