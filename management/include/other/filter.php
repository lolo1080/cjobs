<?
//Filter
$select_txt_array = array($text_info["f_Like"],$text_info["f_Equal"],$text_info["f_Begin"],$text_info["f_Not_Equal"],$text_info["f_Not_Like"]);
$select_txt_values = array("like","equal","begin","not_equal","not_like");
$select_digit_array = array("=","&gt;","&gt=","&lt;","&lt;=","&lt;&gt;");
$select_float_array = array("&gt;","&gt=","&lt;","&lt;=");
$select_digit_values = array("=",">", ">=","<","<=","<>");
$select_float_values = array(">", ">=","<","<=");
$select_pendproc_array = array($text_info["f_Pending"],$text_info["f_Processed"]);
$select_pendproc_values = array("pending", "processed");
$select_active_array = array($text_info["f_Active"],$text_info["f_Disable"]);
$select_active_values = array("active", "disable");
$select_approve_array = array($text_info["f_Approve"],$text_info["f_Pending"]);
$select_approve_values = array("approve", "pending");
$select_highlight_array = array($text_info["f_highlight"],$text_info["f_standard"]);
$select_highlight_values = array("highlight", "standard");
$select_searchtype_array = array("Site","XML");
$select_searchtype_values = array("site","xml");
$select_clicktype_array = array($text_info["f_common_job"],$text_info["f_advertiser_job"],$text_info["f_keyword_ads"]);
$select_clicktype_values = array("common_job","advertiser_job","keyword_ads");
$select_active_panding_array = array($text_info["f_Active"],$text_info["f_Pending"],$text_info["f_Disable"]);
$select_active_panding_values = array("active","pending2","disable");
$select_paytype_array = array("Click","Monthly","Referral","Search");
$select_paytype_values = array("click","monthly","referral","search");
$select_adtype_array = array($text_info["f_Keyword_Ad"],$text_info["f_Sponsored_Jobs"]);
$select_adtype_values = array("keyword_ad", "sponsored_jobs");
$select_soptions_array = array($text_info["f_broad"],$text_info["f_exact"],$text_info["f_phrase"],$text_info["f_negative"]);
$select_soptions_values = array("broad","exact","phrase","negative");
$select_action_array = array($text_info["f_run"],$text_info["f_feed_structure"],$text_info["f_select_feed"],$text_info["f_start_feed_parsing"],$text_info["f_start_feed_parsing_part"],$text_info["f_feed_parsing"],$text_info["f_finish"]);
$select_action_values = array("run","feed_structure","select_feed","start_feed_parsing","start_feed_parsing_part","feed_parsing","finish");
$select_statusoe_array = array($text_info["f_normal"],$text_info["f_error"]);
$select_statusoe_values = array("normal","error");
$select_detail_level_array = array($text_info["f_0"],$text_info["f_1"],$text_info["f_2"],$text_info["f_3"]);
$select_detail_level_values = array("_0","_1","_2","_3");
$select_deliver_array = array($text_info["f_daily"],$text_info["f_weekly"]);
$select_deliver_values = array("daily","weekly");
$select_smbactive_array = array($text_info["f_Disable"],$text_info["f_Active"],$text_info["f_Pending"]);
$select_smbactive_values = array("disable","active","pending2");
$ptf = array_flip($payment_types);
$select_paysystem_array = array("Credit Card","PayPal","E-Gold","2checkout");
$select_paysystem_values = array("ps_".$ptf["Credit Card"],"ps_".$ptf["PayPal"],"ps_".$ptf["E-Gold"],"ps_".$ptf["2checkout"]);
$filter_dateformat = $usersettings["dateformat"];
//$filter_timezone = $usersettings["timezone"];
$mysql_date_field = array(); //Здесь хранятся даты при работке фильтра

/* 
External values:
	$Error_messages["filter_error"], $usersettings["dateformat"], 
	$usersettings["timezone"], $text_info["filter"]
*/

//$date_as_array convert date from user date format to MySQL:[YYYY/YY][MM][DD]
function set_date($dformat,$part,&$date_as_array,&$my_error)
{
 global $text_info;
	switch ($dformat) {
	case "d": case "e": $date_as_array[2] = $part; return;
	case "m": case "c": $date_as_array[1] = $part; return;
	case "y": case "Y": $date_as_array[0] = $part; return;
	}
 $my_error .= $text_info["f_Unknown_format_filter"];
}
//Check errors in filter
function check_filter_values($filter_field,&$text_field,$filter_array,$filter_errorfields,&$my_error)
{
 global $filter_dateformat, $mysql_date_field, $Error_messages;
	for ($i=0; $i<count($filter_field); $i++) {
		switch ($filter_array[$filter_field[$i]]) {
		case "text": $text_field[$filter_field[$i]] = trim($text_field[$filter_field[$i]]); break;
		case "int": 
			$int = trim($text_field[$filter_field[$i]]);
			if (!check_int($int)) $my_error .= $Error_messages["filter_error"]."[\"".$filter_errorfields[$filter_field[$i]]."\"]: ".$Error_messages["int_ivalid"];
			else $text_field[$filter_field[$i]] = strval(intval($int));
			break;
		case "float": 
			$float = trim($text_field[$filter_field[$i]]);
			if (!check_float($float)) $my_error .= $Error_messages["filter_error"]."[\"".$filter_errorfields[$filter_field[$i]]."\"]: ".$Error_messages["int_ivalid"];
			else $text_field[$filter_field[$i]] = strval(floatval($float));
			break;
		case "date": 
			$fdate = eregi_replace(" ","",data_addslashes(html_chars(trim($text_field[$filter_field[$i]]))));
			//Get data parts(digits)(order not specified now)
			if ($fdate == "") { $my_error .= $Error_messages["filter_error"]."[\"".$filter_errorfields[$filter_field[$i]]."\"]: ".$Error_messages["date_empty"]; break; }
			@list($part0,$part1,$part2) = preg_split("/([^0-9])/",$fdate);
			if ( !isset($part0) || !isset($part1) || !isset($part2) ) { $my_error .= $Error_messages["filter_error"]."[\"".$filter_errorfields[$filter_field[$i]]."\"]: ".$Error_messages["date_ivalid"]; break; }
			elseif ( ($part0 == "") || ($part1 == "") || ($part2 == "") ) { $my_error .= $Error_messages["filter_error"]."[\"".$filter_errorfields[$filter_field[$i]]."\"]: ".$Error_messages["date_ivalid"]; break; }
			//Get data parts (letter after "%")
			list($_dformat_,$dformat0,$dformat1,$dformat2) = preg_split("/%/",$filter_dateformat);
			$variant = preg_split("/%/",$filter_dateformat);
			$date_as_array = array("","","");
			set_date(substr($dformat0,0,1), $part0, $date_as_array, $my_error);
			set_date(substr($dformat1,0,1), $part1, $date_as_array, $my_error);
			set_date(substr($dformat2,0,1), $part2, $date_as_array, $my_error);
			$mysql_date_field[$filter_field[$i]] = $date_as_array[0]."-".$date_as_array[1]."-".$date_as_array[2];
			$qr_res = mysql_query("SELECT DATE_ADD( DATE_FORMAT(\"$fdate\",\"$filter_dateformat\"), INTERVAL \"0\" DAY ) as cd")
				or die("<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". ".mysql_error().".<br />");
			$myrow = mysql_fetch_array($qr_res);
			$dt = $myrow["cd"];
			break;
		}
	}
}

//Print filter element <td>[x][.......][...+]</td>
function get_filter_td($tr_class,$span_class,$filter_name,$filter_field_class,$filter_text_class,$select_array,$select_values,$filter_select_class,$filter_fieldtype,$addtext=true)
{
 global $filter_field, $text_field, $select_field;
$text_field1 = $text_field;
add_html_array($text_field1);
//Array filter_field[] contain fields names for filter
//Array filter_array[$filter_name] - contain filter type
$td  = "<td class=\"$tr_class\" nowrap>";
//Att span?
$td .= ($span_class != "") ? "<span class=\"$span_class\">\n" : "";
$checked = "";
for ($i=0; $i<count($filter_field); $i++) {
	if ($filter_field[$i] == $filter_name) {
		$checked = "checked"; break;
	}
}
//Text element
if ($addtext) {
	$value = (isset($text_field1[$filter_name])) ? $text_field1[$filter_name] : "";
	$td .= "<input type=\"text\" name=\"text_field[$filter_name]\" class=\"$filter_text_class\" value=\"$value\" /><br />\n";
}
//else $td .= '<img height="17" src="images/spacer.gif" width="20" alt="" border="0" /><br />';
else $td .= '<span style="padding:0px;margin:0px;height:18px;font-size:0;" /></span><br />';
$td .= "<input type=\"checkbox\" name=\"filter_field[]\" value=\"$filter_name\" class=\"$filter_field_class\" $checked />\n";
//Select element
$td .= "<select name=\"select_field[$filter_name]\" class=\"$filter_select_class\" />\n";
	for ($i=0; $i<count($select_array); $i++) {
		if ( (!isset($select_field[$filter_name])) && ($i == 0) ) $selected = "selected";
		else $selected = ((isset($select_field[$filter_name])) && ($select_field[$filter_name] == $select_values[$i])) ? "selected" : "";
		$td .= "<option value=\"$select_values[$i]\" $selected>$select_array[$i]</option>\n";
	}
$td .="</select>";
$td .= ($span_class != "") ? "</span>" : "";
$td .= "<input type=\"hidden\" name=\"filter_array[$filter_name]\" value=\"$filter_fieldtype\" />\n";
$td .= "</td> ";
 return $td;
}

//Print blank filter field
function get_blank_filter_td($tr_class)
{
 return "<td class=\"$tr_class\" nowrap>&nbsp;</td>";
}

function load_old_filter(&$filter_field,&$text_field,&$select_field,&$filter_array)
{
	$filter_field = (isset($_SESSION["sess_filter_field"])) ? $_SESSION["sess_filter_field"] : array();
	$text_field   = (isset($_SESSION["sess_text_field"])) ? $_SESSION["sess_text_field"] : array();
	$select_field = (isset($_SESSION["sess_select_field"])) ? $_SESSION["sess_select_field"] : array();
	$filter_array = (isset($_SESSION["sess_filter_array"])) ? $_SESSION["sess_filter_array"] : array();
}

//Get filter values from form (else from session)
function get_filter_values_from_form($filter,$removefilter,&$filter_field,&$text_field,&$select_field,&$filter_array)
{
	if ($filter) {
		$filter_field = get_post_value2("filter_field", array());
		$text_field   = get_post_value2("text_field", array());
		$select_field = get_post_value2("select_field", array());
		$filter_array = get_post_value2("filter_array", array());
	}
	elseif ($removefilter) {
		$filter_field = $text_field = $select_field = $filter_array = array();
	}
	else
	{
		$filter_field = $_SESSION["sess_filter_field"];
		$text_field   = $_SESSION["sess_text_field"];
		$select_field = $_SESSION["sess_select_field"];
		$filter_array = $_SESSION["sess_filter_array"];
	}
}

function set_old_session($filter_field,$text_field,$select_field,$filter_array)
{
 global $filterfield_array;
	$_SESSION["sess_filter_field"] = $filter_field;
	$_SESSION["sess_text_field"] = $text_field;
	$_SESSION["sess_select_field"] = $select_field;
	$_SESSION["sess_filter_array"] = $filter_array;
}

//Create limitation, when we use filter
function create_filter_limitation($accordance,$filter_field,$text_field,$select_field,$filter_array,$where,$ignorlist,&$having_limitation)
{
 global $filter_dateformat, $filter_timezone, $mysql_date_field, $ptf;
	addslashes_and_html_array($text_field);
	$limitation = $having_limitation = "";
	for ($i=0; $i<count($filter_field); $i++) {
		if (in_array($accordance[$filter_field[$i]],$ignorlist)) {
			switch ($select_field[$filter_field[$i]]) {
			case "=": $having_limitation .= $accordance[$filter_field[$i]]." = ".$text_field[$filter_field[$i]]." and "; break;
			case ">": $having_limitation .= $accordance[$filter_field[$i]]." > ".$text_field[$filter_field[$i]]." and "; break;
			case ">=": $having_limitation.= $accordance[$filter_field[$i]]." >=".$text_field[$filter_field[$i]]." and "; break;
			case "<": $having_limitation .= $accordance[$filter_field[$i]]." < ".$text_field[$filter_field[$i]]." and "; break;
			case "<=": $having_limitation.= $accordance[$filter_field[$i]]." <=".$text_field[$filter_field[$i]]." and "; break;
			case "<>": $having_limitation.= $accordance[$filter_field[$i]]." <>.".$text_field[$filter_field[$i]]." and "; break;
			}
			continue;
		}
		switch ($filter_array[$filter_field[$i]]) {
		case "date":
			switch ($select_field[$filter_field[$i]]) {
			case "=": $limitation .= "( (TO_DAYS(DATE_ADD(".$accordance[$filter_field[$i]].",INTERVAL \"$filter_timezone\" HOUR_MINUTE)) - TO_DAYS('".$mysql_date_field[$filter_field[$i]]."')) = 0 ) and "; break;
			case ">": $limitation .= "( (TO_DAYS(DATE_ADD(".$accordance[$filter_field[$i]].",INTERVAL \"$filter_timezone\" HOUR_MINUTE)) - TO_DAYS('".$mysql_date_field[$filter_field[$i]]."')) > 0 ) and "; break;
			case ">=": $limitation.= "( (TO_DAYS(DATE_ADD(".$accordance[$filter_field[$i]].",INTERVAL \"$filter_timezone\" HOUR_MINUTE)) - TO_DAYS('".$mysql_date_field[$filter_field[$i]]."')) >= 0 ) and "; break;
			case "<": $limitation .= "( (TO_DAYS(DATE_ADD(".$accordance[$filter_field[$i]].",INTERVAL \"$filter_timezone\" HOUR_MINUTE)) - TO_DAYS('".$mysql_date_field[$filter_field[$i]]."')) < 0 ) and "; break;
			case "<=": $limitation.= "( (TO_DAYS(DATE_ADD(".$accordance[$filter_field[$i]].",INTERVAL \"$filter_timezone\" HOUR_MINUTE)) - TO_DAYS('".$mysql_date_field[$filter_field[$i]]."')) <= 0 ) and "; break;
			case "<>": $limitation.= "( (TO_DAYS(DATE_ADD(".$accordance[$filter_field[$i]].",INTERVAL \"$filter_timezone\" HOUR_MINUTE)) - TO_DAYS('".$mysql_date_field[$filter_field[$i]]."')) <> 0 ) and "; break;
			}
			break;
		default:
			switch ($select_field[$filter_field[$i]]) {
			case "=": $limitation .= $accordance[$filter_field[$i]]." = ".$text_field[$filter_field[$i]]." and "; break;
			case ">": $limitation .= $accordance[$filter_field[$i]]." > ".$text_field[$filter_field[$i]]." and "; break;
			case ">=": $limitation.= $accordance[$filter_field[$i]]." >=".$text_field[$filter_field[$i]]." and "; break;
			case "<": $limitation .= $accordance[$filter_field[$i]]." < ".$text_field[$filter_field[$i]]." and "; break;
			case "<=": $limitation.= $accordance[$filter_field[$i]]." <=".$text_field[$filter_field[$i]]." and "; break;
			case "<>": $limitation.= $accordance[$filter_field[$i]]." <>.".$text_field[$filter_field[$i]]." and "; break;
			case "equal": $limitation .= $accordance[$filter_field[$i]]." = '".$text_field[$filter_field[$i]]."' and "; break;
			case "begin": $limitation .= $accordance[$filter_field[$i]]." LIKE '".$text_field[$filter_field[$i]]."%' and "; break;
			case "like": $limitation .=  $accordance[$filter_field[$i]]."  LIKE '%".$text_field[$filter_field[$i]]."%' and "; break;
			case "not_equal": $limitation .= $accordance[$filter_field[$i]]." <> '".$text_field[$filter_field[$i]]."' and "; break;
			case "not_like": $limitation .=  $accordance[$filter_field[$i]]." NOT LIKE '%".$text_field[$filter_field[$i]]."%' and "; break;
			case "pending": $limitation .= $accordance[$filter_field[$i]]." = 0 and "; break;
			case "pending2": $limitation .= $accordance[$filter_field[$i]]." = 2 and "; break;
			case "processed": $limitation .= $accordance[$filter_field[$i]]." = 1 and "; break;
			case "active": $limitation .= $accordance[$filter_field[$i]]." = 1 and "; break;
			case "disable": $limitation .= $accordance[$filter_field[$i]]." = 0 and "; break;
			case "approve": $limitation .= $accordance[$filter_field[$i]]." = 1 and "; break;
			case "highlight": $limitation .= $accordance[$filter_field[$i]]." = 1 and "; break;
			case "standard": $limitation .= $accordance[$filter_field[$i]]." = 0 and "; break;
			case "site": $limitation .= $accordance[$filter_field[$i]]." = 1 and "; break;
			case "xml": $limitation .= $accordance[$filter_field[$i]]." = 2 and "; break;
			case "common_job": $limitation .= $accordance[$filter_field[$i]]." = 0 and "; break;
			case "advertiser_job": $limitation .= $accordance[$filter_field[$i]]." = 1 and "; break;
			case "keyword_ads": $limitation .= $accordance[$filter_field[$i]]." = 2 and "; break;
			case "keyword_ad": $limitation .= $accordance[$filter_field[$i]]." = 1 and "; break;
			case "sponsored_jobs": $limitation .= $accordance[$filter_field[$i]]." = 2 and "; break;
			case "broad": $limitation .= $accordance[$filter_field[$i]]." = 1 and "; break;
			case "exact": $limitation .= $accordance[$filter_field[$i]]." = 2 and "; break;
			case "phrase": $limitation .= $accordance[$filter_field[$i]]." = 3 and "; break;
			case "negative": $limitation .= $accordance[$filter_field[$i]]." = 4 and "; break;
			case "click": $limitation .= $accordance[$filter_field[$i]]." = ".$ptf["Click"]." and "; break;
			case "monthly": $limitation .= $accordance[$filter_field[$i]]." = ".$ptf["Monthly"]." and "; break;
			case "referral": $limitation .= $accordance[$filter_field[$i]]." = ".$ptf["Referral"]." and "; break;
			case "search": $limitation .= $accordance[$filter_field[$i]]." = ".$ptf["Search"]." and "; break;
			case "ps_".$ptf["Credit Card"]: $limitation .= $accordance[$filter_field[$i]]." = ".$ptf["Credit Card"]." and "; break;
			case "ps_".$ptf["PayPal"]: $limitation .= $accordance[$filter_field[$i]]." = ".$ptf["PayPal"]." and "; break;
			case "ps_".$ptf["E-Gold"]: $limitation .= $accordance[$filter_field[$i]]." = ".$ptf["E-Gold"]." and "; break;
			case "ps_".$ptf["2checkout"]: $limitation .= $accordance[$filter_field[$i]]." = ".$ptf["2checkout"]." and "; break;
			case "run": $limitation .= $accordance[$filter_field[$i]]." = 1 and "; break;
			case "feed_structure": $limitation .= $accordance[$filter_field[$i]]." = 2 and "; break;
			case "select_feed": $limitation .= $accordance[$filter_field[$i]]." = 3 and "; break;
			case "start_feed_parsing": $limitation .= $accordance[$filter_field[$i]]." = 4 and "; break;
			case "start_feed_parsing_part": $limitation .= $accordance[$filter_field[$i]]." = 5 and "; break;
			case "feed_parsing": $limitation .= $accordance[$filter_field[$i]]." = 6 and "; break;
			case "finish": $limitation .= $accordance[$filter_field[$i]]." = 7 and "; break;
			case "normal": $limitation .= $accordance[$filter_field[$i]]." = 1 and "; break;
			case "error": $limitation .= $accordance[$filter_field[$i]]." = 0 and "; break;
			case "_0": $limitation .= $accordance[$filter_field[$i]]." = 0 and "; break;
			case "_1": $limitation .= $accordance[$filter_field[$i]]." = 1 and "; break;
			case "_2": $limitation .= $accordance[$filter_field[$i]]." = 2 and "; break;
			case "_3": $limitation .= $accordance[$filter_field[$i]]." = 3 and "; break;
			case "daily": $limitation .= $accordance[$filter_field[$i]]." = 1 and "; break;
			case "weekly": $limitation .= $accordance[$filter_field[$i]]." = 7 and "; break;
			default: $limitation .= "";
			}
		}
	}
	if (strlen($limitation) > 4) $limitation = $where.substr($limitation,0,-4);
	if (strlen($having_limitation) > 4) $having_limitation = "HAVING ".substr($having_limitation,0,-4);
 return $limitation;
}
?>