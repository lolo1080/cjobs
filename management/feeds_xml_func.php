<?
function get_configuration_list($wherepart = '')
{
 global $db_tables, $SLINE;
	$result = array();
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["xml_feeds_configuration"]." ".$wherepart." ORDER BY config_name") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$result[] = $myrow;
	}
	return $result;
}

function get_configuration_by_id($id)
{
 global $db_tables, $SLINE, $Error_messages;
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["xml_feeds_configuration"]." WHERE config_id='{$id}'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) return_error_msg($Error_messages["no_config_data"]);
	while ($myrow = mysql_fetch_array($qr_res,MYSQL_ASSOC))
	{
		$result = $myrow;
	}
	return $result;
}

function get_configuration_by_id_xml2($id)
{
 global $db_tables, $SLINE, $Error_messages;
	$categories = array();
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["xml2_feeds_category_keywords"]." WHERE config_id='{$id}'") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res,MYSQL_ASSOC))
	{
		$categories[] = $myrow;
	}
	$configuration = array();
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["xml_feeds_configuration"]." WHERE config_id='{$id}'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) return_error_msg($Error_messages["no_config_data"]);
	while ($myrow = mysql_fetch_array($qr_res,MYSQL_ASSOC))
	{
		$configuration = $myrow;
	}
	return array('configuration'=>$configuration, 'categories'=>$categories);
}

function get_categories_list()
{
 global $db_tables, $SLINE;
	$result = array();
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["jobcategories"]." ORDER BY cat_name") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$result[] = $myrow;
	}
	return $result;
}
?>