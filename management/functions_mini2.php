<?
//Get IP address
function getip() {
	if (isset($_SERVER)) $realip = $_SERVER["REMOTE_ADDR"];
	else $realip = getenv('REMOTE_ADDR');
 return $realip; 
}

//Check IPFW
function check_ipfw_by_ip($ip)
{
 global $db_tables;
	list($ip1,$ip2,$ip3,$ip4) = preg_split("/\./",$ip);
	$iplist = array();
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["ipfirewall"]) or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) return false;
	while ($myrow = mysql_fetch_array($qr_res))
	{
	 	$iplist = explode(".",$myrow["ip"]);
		if ( ( ($ip1 == $iplist[0]) || ($iplist[0] == '*') ) && ( ($ip2 == $iplist[1]) || ($iplist[1] == '*') ) &&
				( ($ip3 == $iplist[2]) || ($iplist[2] == '*') ) && ( ($ip4 == $iplist[3]) || ($iplist[3] == '*') ) )
 		return true;
	}
}

//Get country name by IP
function getcountry($realip)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT c.*,ipc.country_code3 FROM ".$db_tables["iptocountry"]." ipc, ".$db_tables["country"]." c ".
			"WHERE ipc.ip_from<=inet_aton('$realip') and ipc.ip_to>=inet_aton('$realip') and ipc.country_code2=c.country_code2") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return array("id"=>$myrow["cid"], "name"=>$myrow["cname"], "code2"=>$myrow["country_code2"],
				"code3"=>$myrow["country_code3"], "active"=>$myrow["active"]);
	}
	else {
		$qr_res = mysql_query("SELECT active FROM ".$db_tables["country"]." WHERE cid='1'") or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res) > 0) {
			$myrow = mysql_fetch_array($qr_res);
			return array("id"=>1, "name"=>"Unknown", "code2"=>"Unknown", "code3"=>"Unknown", "active"=>$myrow["active"]);
		}
	}
 return array("id"=>0, "name"=>"Unknown", "code2"=>"Unknown", "code3"=>"Unknown", "active"=>0);
}

//Encrypt data
function Encrypt($data)
{
 global $crypt_settings;
	$Secrypt = new Secrypt();
	$EncryptedData = $Secrypt->Encrypt($data, $crypt_settings["encryptkey"]["private"], $crypt_settings["encryptkey"]["public"]);
 return $EncryptedData;
}

//Decrypt data
function Decrypt($data)
{
 global $crypt_settings;
	$Secrypt = new Secrypt();
	$DecryptedData = $Secrypt->Decrypt($data, $crypt_settings["encryptkey"]["private"], $crypt_settings["encryptkey"]["public"]);
 return $DecryptedData;
}

function load_front_end_template($diskname)
{
 global $frontend_template_dir;
	//Get content
	$f = fopen($frontend_template_dir.$diskname, "r");
	if (!$f) return "";
	$content = fread($f,filesize($frontend_template_dir.$diskname));
	fclose($f);
	//Parse - create 3 parts
	ini_set('pcre.backtrack_limit', 1000000);
	if ($c = preg_match_all("~\{\*\[start_template_header_part\]\*\}(.*?)\{\*\[end_template_header_part\]\*\}.*?\{\*\[start_template_content_part\]\*\}(.*?)\{\*\[end_template_content_part\]\*\}.*?\{\*\[start_template_footer_part\]\*\}(.*?)\{\*\[end_template_footer_part\]\*\}~si", $content, $res)) {
		$result["header"] = $res[1][0];
		$result["content"] = $res[2][0];
		$result["footer"] = $res[3][0];
	}
	else critical_error(__FILE__,__LINE__,"Incorrect template structure.");
 return $result;
}
?>