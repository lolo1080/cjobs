<?
function get_configuration_list($feed_format='html1')
{
 global $db_tables, $SLINE;
	$result = array();
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["html_feeds_configuration"]." WHERE feed_format='{$feed_format}' ORDER BY config_name") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$result[] = $myrow;
	}
	return $result;
}

function get_configuration_by_id($id)
{
 global $db_tables, $SLINE, $Error_messages;
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["html_feeds_configuration"]." WHERE config_id='{$id}'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) return_error_msg($Error_messages["no_config_data"]);
	while ($myrow = mysql_fetch_array($qr_res,MYSQL_ASSOC))
	{
		$result = $myrow;
	}
	return $result;
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

function parse_content_by_regexpr($regular_expression)
{
	global $Result, $Error_messages;
	$matches = array();
	try {
		if ( preg_match_all($regular_expression, $Result['html_rawdata'], $matches) ) {
			return $matches;
		}
	}
	catch(Exception $ex)
	{
		$Result['status'] = 'error';
		$Result['errormsg'][] = $Error_messages["invalid_regular_expression"];
	}
	return $matches;
}
?>