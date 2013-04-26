<?
function create_values($ipid,$ip)
{
 global $smarty;
	$FormElements = array(
	array("flabel"=>show_cell_caption("ipid"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"ipid", "ereadonly"=>"readonly", "evalue"=>$ipid, "emaxlength"=>"5",
				"estyle"=>"width:40px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("ipaddress"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"ip", "ereadonly"=>"", "evalue"=>$ip, "emaxlength"=>"15",
				"estyle"=>"width:100px", "isheadline"=>false, "edisabled"=>""),
	);
	$smarty->assign("FormElements",$FormElements);
}

function check_ip_with_mask($ip) {
	if (strlen($ip) == 0) return false;
   if (preg_match("!^([0-9]{1,3}|\*{1})\.([0-9]{1,3}|\*{1})\.([0-9]{1,3}|\*{1})\.([0-9]{1,3}|\*{1})$!",$ip)) return true;
   return false;
}

function find_ip_in_db($ip,$ipid) {
 global $db_tables;
	$ip = data_addslashes($ip);
	$qr_res = mysql_query("SELECT ipid FROM ".$db_tables["ipfirewall"]." WHERE ip='$ip' and ipid<>'$ipid'") or query_die(__FILE__,__LINE__,mysql_error());
	return (mysql_num_rows($qr_res) > 0) ? true : false;
}

function add_ip_info($ip)
{
 global $db_tables, $SLINE;
	$ip = data_addslashes($ip);
	mysql_query("INSERT INTO ".$db_tables["ipfirewall"]." VALUES(NULL,'$ip')") or query_die(__FILE__,__LINE__,mysql_error());
	//Send event
	$event_array = array("event"=>"insert", "source"=>"ipfirewall", "table"=>"ipfirewall", "ad_id"=>0);
	event_handler($event_array);
	header("Location: ipfirewall.php?$SLINE"); exit;
}

function update_ip_info($ipid,$ip)
{
 global $db_tables, $SLINE;
	mysql_query("UPDATE ".$db_tables["ipfirewall"]." SET ip='$ip' WHERE ipid='$ipid'") or query_die(__FILE__,__LINE__,mysql_error());
	//Send event
	$event_array = array("event"=>"update", "source"=>"ipfirewall", "table"=>"ipfirewall", "ad_id"=>0);
	event_handler($event_array);
	header("Location: ipfirewall.php?$SLINE"); exit;
}

function try_add()
{
 global $smarty,$Error_messages,$text_info;
	$my_error = "";
	$ip = html_chars(get_post_value("ip",""));

	if (!check_ip_with_mask($ip)) $my_error .= $Error_messages["invalid_ipaddess"];
	if (find_ip_in_db($ip,0)) $my_error .= $Error_messages["find_in_db_ip"];

	//If no errors - save
	if ($my_error == "") {
		$ip = data_addslashes($ip);
		add_ip_info($ip);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		create_values("(auto)",$ip);
		create_page_buttons("add",$text_info["btn_save"]);
	}
}

function try_save($ipid)
{
 global $smarty,$Error_messages,$text_info;
	$my_error = "";
	$ip = html_chars(get_post_value("ip",""));

	if (!check_ip_with_mask($ip)) $my_error .= $Error_messages["invalid_ipaddess"];
	if (find_ip_in_db($ip,$ipid)) $my_error .= $Error_messages["find_in_db_ip"];

	//If no errors - save
	if ($my_error == "") {
		$ip = data_addslashes($ip);
		update_ip_info($ipid,$ip);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		create_values($ipid,$ip);
		create_page_buttons("save",$text_info["btn_save"]);
	}
}
?>