<?
/* * * * * * */
/* GET DATA  */
/* * * * * * */
function get_url_parts(&$my_error,$url)
{
 global $Error_messages;
	$url_ = $url; $url = check_url($url); 
	if ($url === false) {
		$my_error .= $url_.": ".$Error_messages["invalid_url"]; $url = $url_;
		return 0;
	}
	//Check last symbol
	$last_ch = $url[strlen($url)-1];
	if (($last_ch == '/') || ($last_ch == '\\')) $url = substr($url, 0, -1);    
	//Parts
	$url_parts = array();
/*http://www.ics.uci.edu/pub/ietf/uri/#Related
1 = http:
2 = http
3 = //www.ics.uci.edu
4 = www.ics.uci.edu
5 = /pub/ietf/uri/
6 = <undefined>
7 = <undefined>
8 = #Related
9 = Related
*/
	if ( preg_match("~^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?~i", $url, $matches) ) {
		$url_parts["protocol"] = $matches[2];
		$url_parts["add_info"] = "";
		$url_parts["host"] = $matches[4];
		$url_parts["path"] = ($matches[5] == "") ? "/" : $matches[5];
		if (isset($matches[6]) && ($matches[5] != "")) $url_parts["path"] .= $matches[6];
	}
 	else $my_error .= $url.": ".$Error_messages["cannot_parse_url"];
 return $url_parts;
}

function get_site_content(&$my_error,$url_parts,$method="get_curl")
{
 global $Error_messages;
	$path = $url_parts["path"];
	$pos = strpos($path, "?");
	if ($pos !== false) {
		$path = substr($path, 0, $pos);
	}
	if ($method == "get_curl") read_form_url_get_curl($url_parts["host"],80,$url_parts["path"]);
}

function read_form_url_get_curl($host,$port,$path,$inv=20)
{
 global $Result,$script_dir;

	$cookie_file_path = $script_dir."/templates_c/mycookie.txt";

	$content = "";
	$url = "http://{$host}{$path}";

	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL,$url); // set url to post to 
	curl_setopt($ch, CURLOPT_FAILONERROR, 1); 
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects --> cannot be activated when in safe_mode or an open_basedir is set 
	if ( (ini_get('open_basedir') == '') && ((ini_get('safe_mode') == 'Off') || (ini_get('safe_mode') == '')) ) curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 3600); // control the timeout for the work CURL does after it's connected
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $inv); // control the timeout for the initial connection (DNS lookup, establishing the connection, etc.)
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path); 
	curl_setopt($ch, CURLOPT_USERAGENT, get_random_user_agent());
	if ( (ini_get('open_basedir') == '') && ((ini_get('safe_mode') == 'Off') || (ini_get('safe_mode') == '')) ) $content = curl_exec($ch); // run the whole process
	else $content = curl_redir_exec($ch); // run the whole process
	//$content = curl_exec($ch); // run the whole process 
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$curlerror = curl_error($ch);
	curl_close($ch);
	$Result['xml_rawdata'] = $content;
	if ($httpcode > 405) return_error_msg($curlerror);
	if ($content == "") return_error_msg($curlerror);
}

//follow on location problems workaround
function curl_redir_exec(&$ch)
{
	static $curl_loops = 0;
	static $curl_max_loops = 20;
	if ($curl_loops++ >= $curl_max_loops)
	{
		$curl_loops = 0;
		return false;
	}
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	list($header, $data) = explode("\r\n\r\n", $data, 2);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if ($http_code == 301 || $http_code == 302)	{
		$matches = array();
		preg_match('/Location:(.*?)\n/', $header, $matches);
		$url = @parse_url(trim(array_pop($matches)));
		if (!$url) {
			//couldn't process the url to redirect to
			$curl_loops = 0;
			return $data;
		}
		$last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));
/*
		if (!$url['scheme'])
		$url['scheme'] = $last_url['scheme'];
		if (!$url['host'])
		$url['host'] = $last_url['host'];
		if (!$url['path'])
		$url['path'] = $last_url['path'];
*/
		if (!isset($url['scheme']) || ($url['scheme']==''))
			$url['scheme'] = $last_url['scheme'];
		if (!isset($url['host']) || ($url['host']==''))
			$url['host'] = $last_url['host'];
		if (!isset($url['path']) || ($url['path']==''))
			$url['path'] = $last_url['path'];
		if (!isset($url['query'])) $url['query'] = '';
		$new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query']?'?'.$url['query']:'');
		curl_setopt($ch, CURLOPT_URL, $new_url);
		return curl_redir_exec($ch);
	}
	else {
		$curl_loops=0;
		return $data;
	}
}

function get_random_user_agent()
{
 $uas = array(
  'Mozilla/4.0 (compatible; MSIE 6.0; Windows 98)',
  'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; .NET CLR 1.0.3705)',
  'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Maxthon)',
  'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; bgft)',
  'Mozilla/4.5b1 [en] (X11; I; Linux 2.0.35 i586)',
  'Mozilla/5.0 (compatible; Konqueror/2.2.2; Linux 2.4.14-xfs; X11; i686)',
  'Mozilla/5.0 (Macintosh; U; PPC; en-US; rv:0.9.2) Gecko/20010726 Netscape6/6.1',
  'Mozilla/5.0 (Windows; U; Win98; en-US; rv:0.9.2) Gecko/20010726 Netscape6/6.1',
  'Mozilla/5.0 (X11; U; Linux 2.4.2-2 i586; en-US; m18) Gecko/20010131 Netscape6/6.01',
  'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:0.9.3) Gecko/20010801',
  'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.0.7) Gecko/20060909 Firefox/1.5.0.7',
  'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.6) Gecko/20040413 Epiphany/1.2.1',
  'Opera/9.0 (Windows NT 5.1; U; en)',
  'Opera/8.51 (Windows NT 5.1; U; en)',
  'Opera/7.21 (Windows NT 5.1; U)',
  'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT)',
  'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
  'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.0.6) Gecko/20060928 Firefox/1.5.0.6',
  'Opera/9.02 (Windows NT 5.1; U; en)',
  'Opera/8.54 (Windows NT 5.1; U; en)'
  );
 return $uas[rand(0, count($uas)-1)];
}

?>