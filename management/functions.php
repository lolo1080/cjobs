<?
require_once "include/functions/functions_main.php";

function check_access($users_arr)
{
 global $menu_list;
	if ( !isset($_SESSION["sess_user"]) || $_SESSION["sess_user"] == "" || !in_array($_SESSION["sess_user"], $users_arr) ) header("Location: redirect.html");
}

function get_global_settings()
{
 global $db_tables;
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["globsettings"]) or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$_SESSION["globsettings"][$myrow["settings_name"]] = $myrow["settings_value"];
	}
}

function get_jobroll_settings()
{
 global $db_tables;
	$result = array();
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["jobrollsettings"]) or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$result[$myrow["settings_name"]] = $myrow["settings_value"];
	}
 return $result;
}

function get_payment_settings()
{
 global $db_tables;
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["paymentsettings"]) or query_die(__FILE__,__LINE__,mysql_error());
	$myrow = mysql_fetch_array($qr_res);
	$_SESSION["paymentsettings"] = array(
		"credit_card_accept"			=> $myrow["credit_card_accept"], 			"credit_card_login"			=> $myrow["credit_card_login"],
		"credit_card_minwithdraw"	=> $myrow["credit_card_minwithdraw"],	"credit_card_mindeposit"=> $myrow["credit_card_mindeposit"],
		"paypal_accept"			=> $myrow["paypal_accept"], 		"paypal_email"			=> $myrow["paypal_email"],
		"paypal_minwithdraw"=> $myrow["paypal_minwithdraw"],"paypal_mindeposit"	=> $myrow["paypal_mindeposit"],
		"egold_accept"			=> $myrow["egold_accept"],			"egold_id"					=> $myrow["egold_id"],
		"egold_passphrase"	=> $myrow["egold_passphrase"],	"egold_minwithdraw"	=> $myrow["egold_minwithdraw"],
		"egold_mindeposit"	=> $myrow["egold_mindeposit"],	
		"2checkout_accept"		=> $myrow["2checkout_accept"],			"2checkout_id"				=> $myrow["2checkout_id"],
		"2checkout_minwithdraw"	=> $myrow["2checkout_minwithdraw"],"2checkout_mindeposit"=> $myrow["2checkout_mindeposit"],
		"2checkout_url"			=> $myrow["2checkout_url"],				"2checkout_test"	=> $myrow["2checkout_test"]);
}

function check_first_entry($scriptname,$unset_val_list)
{
	if (!isset($_SESSION["sess_curscript"])) create_session_arrays();
	$_SESSION["sess_prevscript"] = isset($_SESSION["sess_curscript"]) ? $_SESSION["sess_curscript"] : $scriptname;
	$_SESSION["sess_curscript"] = $scriptname;
	if ($_SESSION["sess_prevscript"] != $_SESSION["sess_curscript"]) {
		unset_session_values(array("sess_start","sess_sortfield","sess_sortorder","sess_filter_field","sess_text_field","sess_select_field","sess_filter_array"));
		unset_session_values($unset_val_list);
		create_session_arrays();
		check_session_values($scriptname);
		$_SESSION["sess_prevscript"] = $scriptname;
	} 
}

function check_session_values($cur_name)
{
		$_SESSION["sess_sortfield"] = isset($_SESSION[$cur_name."_save_sess_sortfield"]) ? $_SESSION[$cur_name."_save_sess_sortfield"] : "";
		$_SESSION["sess_sortorder"] = isset($_SESSION[$cur_name."_save_sess_sortorder"]) ? $_SESSION[$cur_name."_save_sess_sortorder"] : "";
		$_SESSION["sess_filter_field"] = isset($_SESSION[$cur_name."_save_sess_filter_field"]) ? $_SESSION[$cur_name."_save_sess_filter_field"] : array();
		$_SESSION["sess_text_field"]   = isset($_SESSION[$cur_name."_save_sess_text_field"]) ? $_SESSION[$cur_name."_save_sess_text_field"] : array();
		$_SESSION["sess_select_field"] = isset($_SESSION[$cur_name."_save_sess_select_field"]) ? $_SESSION[$cur_name."_save_sess_select_field"] : array();
		$_SESSION["sess_filter_array"] = isset($_SESSION[$cur_name."_save_sess_filter_array"]) ? $_SESSION[$cur_name."_save_sess_filter_array"] : array();
}

function save_session_values($cur_name)
{
	$_SESSION["sess_curscript"] = $cur_name;
	$_SESSION[$cur_name."_save_sess_sortfield"] = isset($_SESSION["sess_sortfield"]) ? $_SESSION["sess_sortfield"] : "";
	$_SESSION[$cur_name."_save_sess_sortorder"] = isset($_SESSION["sess_sortorder"]) ? $_SESSION["sess_sortorder"] : "";
	$_SESSION[$cur_name."_save_sess_filter_field"] = isset($_SESSION["sess_filter_field"]) ? $_SESSION["sess_filter_field"] : "";
	$_SESSION[$cur_name."_save_sess_text_field"] = isset($_SESSION["sess_text_field"]) ? $_SESSION["sess_text_field"] : "";
	$_SESSION[$cur_name."_save_sess_select_field"] = isset($_SESSION["sess_select_field"]) ? $_SESSION["sess_select_field"] : "";
	$_SESSION[$cur_name."_save_sess_filter_array"] = isset($_SESSION["sess_filter_array"]) ? $_SESSION["sess_filter_array"] : "";
}

//Set sorting in session
function set_sort($sortfield,$sortorder)
{
 global $sortfield_array,$sortfield_array_default;
	if (($sortfield == "") && ($sortorder == "") && ($_SESSION["sess_sortfield"] != "") && ($_SESSION["sess_sortorder"] != "")) return;
	$_SESSION["sess_sortfield"] = (isset($sortfield_array[$sortfield])) ? $sortfield_array[$sortfield] : $sortfield_array_default;
	$_SESSION["sess_sortorder"] = ( ($sortorder=="asc") || ($sortorder=="desc")) ? $sortorder : "asc";
}

//Sorting links
function img_link_format($script,$fieldname,$sortorder,$img_name,$addparams)
{
 global $SLINE;
 return "<a class=\"db_sort\" href=\"$script?sortfield=$fieldname&sortorder=$sortorder&$SLINE$addparams\">".get_img($img_name,7,7,"")."</a>";
}

function sort_link($script,$fieldname,$text,$addparams="")
{
 global $sortfield_array,$sortorder_array,$SLINE;
	$cur_sort_img = "";
	if ($sortfield_array[$fieldname] == $_SESSION["sess_sortfield"]) {
		if ($_SESSION["sess_sortorder"] == "asc") {
			$sortorder = "desc";
			$cur_sort_img = '&nbsp;'.get_img("sortasc.gif",7,7,"");
		}
		else {
			$sortorder = "asc";
			$cur_sort_img = '&nbsp;'.get_img("sortdesc.gif",7,7,"");
		}
	}
	else $sortorder = "asc";
	$cur_sort_lnk = "<a class=\"db_sort\" href=\"$script?sortfield=$fieldname&sortorder=$sortorder&$SLINE$addparams\">$text</a>";
 return $cur_sort_lnk.$cur_sort_img;
}

function unset_session_values($vname)
{
	for ($i=0; $i<count($vname); $i++) {
		unset($_SESSION[$vname[$i]]);
	}
}

function create_session_arrays()
{
	$_SESSION["sess_sortfield"] = $_SESSION["sess_sortorder"] = "";
	$_SESSION["sess_filter_field"] = $_SESSION["sess_text_field"] = 
	$_SESSION["sess_select_field"] = $_SESSION["sess_filter_array"] = array();
}

//Get mail type
function get_mailtype()
{
	$mailtype = get_get_value("mail","");
	if ( ($mailtype != "") && !isset($_SESSION["sess_mail"]) ) $_SESSION["sess_mail"] = $mailtype;
	elseif ( ($mailtype != "") && isset($_SESSION["sess_mail"]) && ($_SESSION["sess_mail"] != $mailtype) ) {
		unset_session_values(array("sess_mail","sess_start","sess_sortfield","sess_sortorder","sess_filter_field","sess_text_field","sess_select_field","sess_filter_array"));
		create_session_arrays();
		$_SESSION["sess_mail"] = $mailtype;
	}
	elseif ( isset($_SESSION["sess_mail"]) && ($_SESSION["sess_mail"] != "") ) $mailtype = $_SESSION["sess_mail"];
	else {critical_error(__FILE__,__LINE__,"Mail type not found");}
 return $mailtype;
}

function check_start($start)
{
	if (!check_int($start)) return 0;
	if ($start < 0) return 0;
	$start = ($start == 0) ? 0 : ($start-1);
 return $start;
}

//Get Start value (page number)
function get_start()
{
	$start = data_addslashes(get_get_post_value("start",""));
	$fromform = get_get_true_false("fromform");
	if ( ($fromform) && ($start != "") )  return check_start(trim($start));
	if ($start == "") {
		if (isset($_SESSION["sess_start"]) && ($_SESSION["sess_start"] != "") ) return $_SESSION["sess_start"];
		else return 0;
	}
	else $_SESSION["sess_start"] = $start;
 return $start;
}

function get_page_count($curquery,$row_count)
{
	$num = 0;
	$qr_res = mysql_query($curquery) or die("<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". ".mysql_error().".<br />");
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		$num = $myrow["num"];
	}
 return ceil($num/$row_count); //total page count
}

function get_page_count_by_rows($curquery,$row_count)
{
	$num = 0;
	$qr_res = mysql_query($curquery) or die("<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". ".mysql_error().".<br />");
	$num = mysql_num_rows($qr_res);
 return ceil($num/$row_count); //total page count by rows
}

function get_navigation_line($start,$page_count,$scriptlnk)
{
 global $view_pages,$SLINE;
	$Pages = $Left = $Right = array();
	$block = floor($start/$view_pages);
	$block_element = $block*$view_pages;
	$stop = ($page_count < $block_element+$view_pages) ? $page_count : $block_element+$view_pages;
	for($i=$block_element; $i<$stop; $i++) {
		$Pages[$i]["text"] = $i+1;
		if ($i == $start)	$Pages[$i]["islink"] = false;
		else {
			$Pages[$i]["islink"] = true;
			$Pages[$i]["href"] = $scriptlnk."?start=".$i."&".$SLINE;
		}
	}
	if ($block != 0) {
			$Left[0]["islink"] = true;
      $Left[0]["href"] = $scriptlnk."?start=".($block_element-1)."&".$SLINE;
			$Left[0]["text"] = "&lt;&lt;";
	}
	if ($block*$view_pages+$view_pages <= $page_count-1) {
			$Right[0]["islink"] = true;
      $Right[0]["href"] = $scriptlnk."?start=".$i."&".$SLINE;
			$Right[0]["text"] = "&gt;&gt;";
	}
 return array_merge($Left,$Pages,$Right);
}

function get_admin_email_free()
{
 global $db_tables;
	$qr_res = mysql_query("SELECT admemail FROM ".$db_tables["admins"]) or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return $myrow["admemail"];
	}
 return "";
}

function get_admin_email()
{
 global $db_tables;
	$qr_res = mysql_query("SELECT admemail FROM ".$db_tables["admins"]."  WHERE admid='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return $myrow["admemail"];
	}
 return "";
}

function get_admin_login()
{
 global $db_tables;
	$qr_res = mysql_query("SELECT admname FROM ".$db_tables["admins"]." WHERE admid='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return $myrow["admname"];
	}
 return "";
}

function get_all_admin_ids()
{
 global $db_tables;
	$res = array();
	$qr_res = mysql_query("SELECT admid FROM ".$db_tables["admins"]) or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$res[] = $myrow["admid"];
	}
 return $res;
}

//Get mail subject for current mail
function get_mailsubject($mail)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT mailsubject FROM ".$db_tables["mailsubject"]." WHERE mailkey='$mail'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return $myrow["mailsubject"];
	}
 return "";
}

function get_mysql_date($indate,&$my_error)
{
 global $usersettings,$Error_messages;
	//Get data parts(digits)(order not specified now)
	@list($part0,$part1,$part2) = preg_split("/([^0-9])/",$indate);
	if ( !isset($part0) || !isset($part1) || !isset($part2) ) $my_error .= $Error_messages["from_date_invalid"];
	elseif ( ($part0 == "") || ($part1 == "") || ($part2 == "") ) $my_error .= $Error_messages["from_date_invalid"];
	//Get data parts (letter after "%")
	list($_dformat_,$dformat0,$dformat1,$dformat2) = preg_split("/%/",$usersettings["dateformat"]);
	$date_as_array = array("","","");
	set_date(substr($dformat0,0,1), $part0, $date_as_array, $my_error);
	set_date(substr($dformat1,0,1), $part1, $date_as_array, $my_error);
	set_date(substr($dformat2,0,1), $part2, $date_as_array, $my_error);
 return $date_as_array[0]."-".$date_as_array[1]."-".$date_as_array[2];
}

function create_html_error($my_error)
{
 return '
<table class="error" width="100%">
<tr align="center">
	<td class="errormessage">'.$my_error.'</td>
</tr>
</table>
<br />
';
}

function show_cell_caption($cell_name,$isbold=true,$addasterisk=false)
{
 global $text_info;
	$asterisk = ($addasterisk) ? $text_info["c_asterisk"] : "";
	$bt = ($isbold) ? array("<b>","</b>$asterisk:") : array("","$asterisk:");
	$cn = (isset($text_info['p_'.$cell_name])) ? $bt[0].$text_info['p_'.$cell_name].$bt[1] : '';
	$cn .= (isset($text_info['h_'.$cell_name])) ? '<br /><small>'.$text_info['h_'.$cell_name].'</small>' : '';
 return $cn;
}

function format_sql_date($field_name)
{
 global $usersettings;
 return "DATE_FORMAT($field_name,'".$usersettings["dateformat"]."')";
}

function format_sql_datetime($field_name)
{
 global $usersettings;
 return "DATE_FORMAT($field_name,'".$usersettings["datetimeformat"]."')";
}

function check_sess_id_values($v,$vname)
{
	if ( ($v == "") && isset($_SESSION["sess_$vname"]) && ($_SESSION["sess_$vname"] != "") ) return $_SESSION["sess_$vname"];
	if ($v != "") { $_SESSION["sess_$vname"] = $v; return $v; }
	return $v;
}

function check_sess_array_values($v,$vname)
{
	return (!isset($v[0]) && isset($_SESSION["sess_$vname"][0]) && ($_SESSION["sess_$vname"][0] != "")) ? $_SESSION["sess_$vname"] : $v;
}

function end_url_slash($url)
{
	if (strlen($url) == 0) return "";
	if (substr($url, -1) == "/") return $url;
	else return $url."/";
}

//Get user ($uid) balance
function get_user_balance($uid_field_name,$uid,$table)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT balance FROM $table WHERE $uid_field_name='$uid'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return array("result"=>1, "balance"=>$myrow["balance"]);
	}
 return array("result"=>0, "balance"=>0);
}

function php2js($a=false)
{
  if (is_null($a)) return 'null';
  if ($a === false) return 'false';
  if ($a === true) return 'true';
  if (is_scalar($a))
  {
    if (is_float($a))
    {
      // Always use "." for floats.
      $a = str_replace(",", ".", strval($a));
    }
    // All scalars are converted to strings to avoid indeterminism.
    // PHP's "1" and 1 are equal for all PHP operators, but
    // JS's "1" and 1 are not. So if we pass "1" or 1 from the PHP backend,
    // we should get the same result in the JS frontend (string).
    // Character replacements for JSON.
    static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'),
    array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
    return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
  }
  $isList = true;
  for ($i = 0, reset($a); $i < count($a); $i++, next($a))
  {
    if (key($a) !== $i)
    {
      $isList = false;
      break;
    }
  }
  $result = array();
  if ($isList)
  {
    foreach ($a as $v) $result[] = php2js($v);
    return '[ ' . join(', ', $result) . ' ]';
  }
  else
  {
    foreach ($a as $k => $v) $result[] = php2js($k).': '.php2js($v);
    return '{ ' . join(', ', $result) . ' }';
  }
} 

function get_tbl_payment_systems($ignor_list = array(""))
{
 global $db_tables;
	$ps = array();
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["paymentsettings"]) or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		if ($myrow["credit_card_accept"] && (!in_array("credit_card",$ignor_list))) $ps["credit_card"] = "Credit Card";
		if ($myrow["paypal_accept"] && (!in_array("paypal",$ignor_list))) $ps["paypal"] = "PayPal";
		if ($myrow["egold_accept"] && (!in_array("egold",$ignor_list))) $ps["egold"] = "E-Gold";
		if ($myrow["2checkout_accept"] && (!in_array("2checkout",$ignor_list))) $ps["2checkout"] = "2checkout";
	}
	else {critical_error(__FILE__,__LINE__,"Payment systems data not found.");}
 return $ps;
}

//Log error
function log_file_payment($ftype,$file,$line,$message)
{
 global $log_info, $usersettings;
	if ($log_info["use_payment_log"]) {
		if ($ftype == "errors") @$fp = fopen($log_info["payment_errors"],"a+");
		elseif ($ftype == "history") @$fp = fopen($log_info["payment_history"],"a+");
		else return;
		if (!$fp) return;
		$devider = "********************\n";
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($usersettings["timezone_identifier"]);
		$cur_date = "Time: ".date("j.m.Y G:i:s")." (d.m.y h:m:s)\n";
		$sysinfo = "Sytem info: File: $file Line: $line\n";
		fwrite($fp,$devider.$cur_date.$sysinfo."Message: ".$message."\n");
		fclose($fp);
		@chmod($log_info["payment_errors"],0777);
	}
}
?>