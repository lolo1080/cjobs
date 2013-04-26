<?
/*
#############################
# functions_main.php
# The main file wih functions
#############################
*/

//Convert special characters to HTML entities 
function html_chars($var)
{
 global $dostripslashes;
	$dostripslashes = false;
	if (get_magic_quotes_gpc()) {
		$var = stripslashes($var);
		$dostripslashes = true;
	}
	$var = htmlspecialchars($var);
 return $var;
}

//Unconvert HTML entities to special characters
function unhtmlentities($string)
{
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	return strtr($string, $trans_tbl);
}

//Quote string with slashes (if it need...)
function data_addslashes($var)
{
 global $dostripslashes;
 return (!get_magic_quotes_gpc() || $dostripslashes) ? addslashes($var) : $var;
}

//Quote string with slashes (if it need...)
function data_addslashes_notrelated($var)
{
 global $dostripslashes;
 return (!get_magic_quotes_gpc()) ? addslashes($var) : $var;
}

//Convert special characters to HTML entities for array elements
function add_html_array(&$values_array)
{
	foreach($values_array as $k => $v) {
		$values_array[$k] = html_chars(trim($values_array[$k]));
	}
}

//Quote string with slashes and html chars (if it need...) for array elements
function addslashes_and_html_array(&$values_array)
{
	foreach($values_array as $k => $v) {
		$values_array[$k] = data_addslashes(html_chars(trim($values_array[$k])));
	}
}

//Quote string with slashes and html chars (if it need...) for array elements
function addslashes_and_html_array_notrelated(&$values_array)
{
	foreach($values_array as $k => $v) {
		$values_array[$k] = data_addslashes_notrelated(trim($values_array[$k]));
	}
}

function get_duplicate_value_name($pname)
{
	return str_replace("_", "-", $pname);
}

//Get value, using method GET
function get_get_value($pname,$pdefault)
{
//	return (isset($_GET[$pname])) ? $_GET[$pname] : $pdefault;
	if (isset($_GET[$pname])) return $_GET[$pname];
	elseif (isset($_GET[get_duplicate_value_name($pname)])) return $_GET[get_duplicate_value_name($pname)];
	else return $pdefault;
}

//Get value, using method POST
function get_post_value($pname,$pdefault)
{
//	return (isset($_POST[$pname])) ? trim($_POST[$pname]) : $pdefault;
	if (isset($_POST[$pname])) return trim($_POST[$pname]);
	elseif (isset($_POST[get_duplicate_value_name($pname)])) return trim($_POST[get_duplicate_value_name($pname)]);
	else return $pdefault;
}

//Get value, using method POST (without trim())
function get_post_value2($pname,$pdefault)
{
//	return (isset($_POST[$pname])) ? $_POST[$pname] : $pdefault;
	if (isset($_POST[$pname])) return $_POST[$pname];
	elseif (isset($_POST[get_duplicate_value_name($pname)])) return $_POST[get_duplicate_value_name($pname)];
	else return $pdefault;
}

//Get value, using method GET or POST
function get_get_post_value($pname,$pdefault)
{
//	if (isset($_GET[$pname])) return trim($_GET[$pname]);
//	elseif (isset($_POST[$pname])) return trim($_POST[$pname]);
//	else return $pdefault;
	if (isset($_GET[$pname])) return trim($_GET[$pname]);
	elseif (isset($_GET[get_duplicate_value_name($pname)])) return trim($_GET[get_duplicate_value_name($pname)]);
	elseif (isset($_POST[$pname])) return trim($_POST[$pname]);
	elseif (isset($_POST[get_duplicate_value_name($pname)])) return trim($_POST[get_duplicate_value_name($pname)]);
	else return $pdefault;
}

//Get value, using method GET or POST (without trim())
function get_get_post_value2($pname,$pdefault)
{
//	if (isset($_GET[$pname])) return $_GET[$pname];
//	elseif (isset($_POST[$pname])) return $_POST[$pname];
//	else return $pdefault;
	if (isset($_GET[$pname])) return $_GET[$pname];
	elseif (isset($_GET[get_duplicate_value_name($pname)])) return $_GET[get_duplicate_value_name($pname)];
	elseif (isset($_POST[$pname])) return $_POST[$pname];
	elseif (isset($_POST[get_duplicate_value_name($pname)])) return $_POST[get_duplicate_value_name($pname)];
	else return $pdefault;
}

//Check value transferred using method GET
function get_get_true_false($pname)
{
//	return (isset($_GET[$pname])) ? "1" : "0";
	return (isset($_GET[$pname]) || isset($_GET[get_duplicate_value_name($pname)])) ? "1" : "0";
}

//Check value transferred using method POST
function get_post_true_false($pname)
{
//	return (isset($_POST[$pname])) ? "1" : "0";
	return (isset($_POST[$pname]) || isset($_POST[get_duplicate_value_name($pname)])) ? "1" : "0";
}

//Get image
function get_img($img_name,$width,$height,$title,$add_jsscript="")
{ return '<img src="images/'.$img_name.'" width="'.$width.'" height="'.$height.'" alt="'.$title.'" title="'.$title.'" border="0" align="absmiddle" '.$add_jsscript.' />'; }

//Get special image
function get_spimg($img_name,$width,$height,$title,$add_jsscript="")
{ return '<img src="'.$img_name.'" width="'.$width.'" height="'.$height.'" alt="'.$title.'" title="'.$title.'" border="0" align="absmiddle" '.$add_jsscript.' />'; }

//Get submit image
function get_submit_img($img_name,$width,$height,$title,$add_jsscript="")
{ return '<input type="image" src="images/'.$img_name.'" width="'.$width.'" height="'.$height.'" alt="'.$title.'" title="'.$title.'" border="0" align="absmiddle" '.$add_jsscript.' />'; }

//Check int value
function check_int($int)
{
	if ("$int" == strval(intval($int))) return true;
 return false;
}

//Check big int value
function check_bigint($int)
{
	if (strlen($int) == 0) return false;
  if (preg_match("!\D!",$int)) return false;
 return true;
}

//Check float value
function check_float($float)
{
	if ("$float" == strval(floatval($float))) return true;
 return false;
}

//Check date value
function check_date($date) {
	if (strlen($date) == 0) return false;
   if (preg_match("!^[0-9]{1,2}[./-][0-9]{1,2}[./-][0-9]{2,4}$!",$date)) return true;
   return false;
}

//Check e-mail address
function check_mail($mail)
{
	if (strlen($mail) == 0) return false;
	if (!preg_match("/^[a-z0-9_.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",$mail)) return false;
 return true;
}

//Check url address
function check_url($url)
{
   if (strlen($url) == 0) return $url;
   if (!preg_match("~^(?:(?:https?|ftp|telnet)://(?:[a-z0-9_-]{1,32}(?::[a-z0-9_-]{1,32})?@)?)?(?:(?:[a-z0-9-]{1,128}\.)+(?:com|net|org|mil|edu|arpa|gov|biz|info|aero|[a-z]{2})|(?!0)(?:(?!0[^.]|255)[0-9]{1,3}\.){3}(?!0|255)[0-9]{1,3})(?:/[a-z\(\)0-9.,_@%&;?\!\[\]+=\|\~/-]*)?(?:#[^ '\"&<>]*)?$~i",$url,$ok)) return false;
   if (!strstr($url,"://")) $url="http://".$url;
   $url = preg_replace("~^[a-z]+~ie","strtolower('\\0')",$url);
 return $url;
}

//Check color value [#000000]
function check_color($color)
{
	if (strlen($color) == 0) return false;
	if (!preg_match("/#[a-fA-F0-9]{6}/is",$color)) return false;
 return true;
}

//Check ip value
function check_ip($ip) {
	if (strlen($ip) == 0) return false;
   if (preg_match("!^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$!",$ip)) return true;
   return false;
}

//Check country id
function check_country_id($country_id)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT cid FROM ".$db_tables["country"]." ORDER BY cid='".$country_id."'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) return true;
 return false;	
}

//Check values on emptiness
function isblank(&$vallist,&$errlist,&$my_error)
{
	for ($i=0; $i<count($vallist); $i++)
	{
		if ($vallist[$i] == "") $my_error .= $errlist[$i];
	}
}

//Check values on float
function isfloat(&$vallist,&$errlist,&$my_error)
{
	for ($i=0; $i<count($vallist); $i++)
	{
		if (!check_float($vallist[$i])) $my_error .= $errlist[$i];
	}
}

//Chech float value above zero values
function is_float_above_zero(&$vallist,&$errlist,&$my_error)
{
	for ($i=0; $i<count($vallist); $i++)
	{
		if (!check_float($vallist[$i])) {
			$my_error .= $errlist[$i];
			continue;
		}
		elseif ($vallist[$i] < 0) $my_error .= $errlist[$i];
	}
}

//Check values on int
function isint(&$vallist,&$errlist,&$my_error)
{
	for ($i=0; $i<count($vallist); $i++)
	{
		if (!check_int($vallist[$i])) $my_error .= $errlist[$i];
	}
}

//Chech int value above NUM values
function is_int_above_num($num,&$vallist,&$errlist,&$my_error)
{
	for ($i=0; $i<count($vallist); $i++)
	{
		if (!check_int($vallist[$i])) {
			$my_error .= $errlist[$i];
			continue;
		}
		elseif ($vallist[$i] < $num) $my_error .= $errlist[$i];
	}
}

//Check values on a correctness (in array)
function is_not_array(&$vallist,&$errlist,&$check_array,&$my_error)
{
	for ($i=0; $i<count($vallist); $i++)
	{
		if ( !in_array($vallist[$i],get_keys($check_array[$i])) ) $my_error .= $errlist[$i];
	}
}

function is_url(&$url,$err,&$my_error)
{
	if ($url != "") {
		$tmp_url = $url;
		$url = check_url($url);
		if ($url === false) {
			$url = $tmp_url; $my_error .= $err;
		}
	}
}

//Chech color values [#000000]
function is_color(&$vallist,&$errlist,&$my_error)
{
	for ($i=0; $i<count($vallist); $i++)
	{
		if (!check_color($vallist[$i])) $my_error .= $errlist[$i];
	}
}

//used in "is_not_array" function
function get_keys(&$vallist)
{
	$tmp = array();
	foreach ($vallist as $k=>$v)
	{
		$tmp[] = $k;
	}
 return $tmp;
}

function get_os_devider()
{
 global $os_devider;
	$d = realpath("./");
	if ( (strlen($d) > 1) && ($d[1] == ":") ) return $os_devider["windows"];
	else return $os_devider["unix"];
}

//Show notice
function none_critical_notice($file,$line,$mes)
{
	trigger_error("File: $file Line:$line. $mes");
}

//Show MySQL error message and die
function query_die($file,$line,$error)
{
	trigger_error("File: $file Line:$line. $error");
	die("Error during SQL query. ".mysql_error());
}

//Show MySQL error message and not die
function query_not_die($file,$line,$error)
{
	trigger_error("File: $file Line:$line. $error");
}

//Show critical error message (application error)
function critical_error($file,$line,$mes)
{
	trigger_error("Critical Error: File:$file Line:$line Message: $mes");
	die("Application Critical Error. Please, contact to administrator.");
}
?>