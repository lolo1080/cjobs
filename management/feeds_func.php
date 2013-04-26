<?
function create_values($title,$description,$url,$registered,$refresh_rate,$max_recursion_depths,$isactive,$feed_type,$job_ads_id,$feed_format)
{
 global $smarty,$usersettings,$calendar_button,$active_disable_array,$common_advertiser_array,$xml_html_array;
	$date_format_str		= "(".$usersettings["dateformat_c_info"].")";
	$feed_status_selectbox	= get_selectbox_data($active_disable_array,$isactive);
	$feed_type_selectbox	= get_selectbox_data($common_advertiser_array,$feed_type);
	$feed_job_ads_id_selectbox	= get_job_ads_selectbox_data($job_ads_id);
	$feed_format_selectbox	= get_selectbox_data($xml_html_array,$feed_format);

	$FormElements = array(
	array("flabel"=>show_cell_caption("feed_title",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"title", "ereadonly"=>"", "evalue"=>$title, "emaxlength"=>"150",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("feed_description"), "before_html"=>"", "after_html"=>"", "etype"=>"textarea",
				"ename"=>"description", "ereadonly"=>"", "evalue"=>$description,
				"estyle"=>"width:300px;height:60px", "isheadline"=>false),
	array("flabel"=>show_cell_caption("feed_url",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"url", "ereadonly"=>"", "evalue"=>$url, "emaxlength"=>"200",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("regdate",true,true).'<br />'.$date_format_str, "before_html"=>"", "after_html"=>"&nbsp;".$calendar_button, "etype"=>"text",
				"ename"=>"registered", "ereadonly"=>"", "evalue"=>$registered, "emaxlength"=>"8",
				"estyle"=>"width:274px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("refresh_rate",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"refresh_rate", "ereadonly"=>"", "evalue"=>$refresh_rate, "emaxlength"=>"150",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("max_recursion_depths",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"max_recursion_depths", "ereadonly"=>"", "evalue"=>$max_recursion_depths, "emaxlength"=>"150",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("feed_status",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"isactive", "edisabled"=>"", "evalue"=>$feed_status_selectbox["val"],
				"eselected"=>$feed_status_selectbox["sel"], "ecaption"=>$feed_status_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("feed_type",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"feed_type", "edisabled"=>"", "evalue"=>$feed_type_selectbox["val"],
				"eselected"=>$feed_type_selectbox["sel"], "ecaption"=>$feed_type_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("job_ads_id",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"job_ads_id", "edisabled"=>"", "evalue"=>$feed_job_ads_id_selectbox["val"],
				"eselected"=>$feed_job_ads_id_selectbox["sel"], "ecaption"=>$feed_job_ads_id_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("feed_format",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"feed_format", "edisabled"=>"", "evalue"=>$feed_format_selectbox["val"],
				"eselected"=>$feed_format_selectbox["sel"], "ecaption"=>$feed_format_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>"")
	);
	$smarty->assign("FormElements",$FormElements);
}

function add_feed_info($title,$description,$url,$registered,$refresh_rate,$max_recursion_depths,$isactive,$feed_type,$feed_code,$job_ads_id,$feed_format)
{
 global $db_tables, $SLINE;
	if ($job_ads_id == '') $job_ads_id = 0;
	mysql_query("INSERT INTO ".$db_tables["sites_feed_list"]." VALUES(NULL,'$feed_code','$title','$description','$url','$registered','$refresh_rate','$max_recursion_depths','$isactive','0','0000-00-00 00:00:00','$feed_type','$job_ads_id','$feed_format')") or query_die(__FILE__,__LINE__,mysql_error());
	//Send event
	$event_array = array("event"=>"insert", "source"=>"sites_feed_list", "table"=>"sites_feed_list", "ad_id"=>0);
	event_handler($event_array);
	header("Location: feeds.php?$SLINE"); exit;
}

function update_feed_info($feed_id,$title,$description,$url,$registered,$refresh_rate,$max_recursion_depths,$isactive,$feed_type,$feed_code,$job_ads_id,$feed_format)
{
 global $db_tables, $SLINE;
	if ($job_ads_id == '') $job_ads_id = 0;
	mysql_query("UPDATE ".$db_tables["sites_feed_list"]." SET feed_code='$feed_code',title='$title',description='$description',url='$url',registered='$registered',refresh_rate='$refresh_rate',max_recursion_depths='$max_recursion_depths',isactive='$isactive',feed_type='$feed_type',job_ads_id='$job_ads_id',feed_format='$feed_format' WHERE feed_id='$feed_id'") or query_die(__FILE__,__LINE__,mysql_error());
	//Send event
	$event_array = array("event"=>"update", "source"=>"jobcategories", "table"=>"jobcategories", "ad_id"=>0);
	event_handler($event_array);
	header("Location: feeds.php?$SLINE"); exit;
}

function get_cur_values(&$title,&$description,&$url,&$registered,&$refresh_rate,&$max_recursion_depths,&$isactive,&$feed_type,&$job_ads_id,&$feed_format)
{
 global $Error_messages,$text_info;
 global $active_disable_array,$common_advertiser_array;
	$title = html_chars(get_post_value("title",""));
	$description = html_chars(get_post_value("description",""));
	$url = html_chars(get_post_value("url",""));
	$registered = html_chars(get_post_value("registered",""));
	$refresh_rate = html_chars(get_post_value("refresh_rate",""));
	$max_recursion_depths = html_chars(get_post_value("max_recursion_depths",""));
	$isactive = html_chars(get_post_value("isactive",""));
	$feed_type = html_chars(get_post_value("feed_type",""));
	$job_ads_id = html_chars(get_post_value("job_ads_id",""));
	$feed_format = html_chars(get_post_value("feed_format",""));
}

function slash_cur_values(&$title,&$description,&$url,&$registered,&$refresh_rate,&$max_recursion_depths,&$isactive,&$feed_type,&$job_ads_id,&$feed_format)
{
	$title = data_addslashes($title);
	$description = data_addslashes($description);
	$url = data_addslashes($url);
	$registered = data_addslashes($registered);
	$refresh_rate = data_addslashes($refresh_rate);
	$max_recursion_depths = data_addslashes($max_recursion_depths);
	$isactive = data_addslashes($isactive);
	$feed_type = data_addslashes($feed_type);
	$job_ads_id = data_addslashes($job_ads_id);
	$feed_format = data_addslashes($feed_format);
}

function check_cur_values($feed_id,&$my_error,&$title,&$description,&$url,&$registered,&$refresh_rate,&$max_recursion_depths,&$isactive,&$feed_type,&$feed_code,&$job_ads_id,&$feed_format)
{
 global $Error_messages,$text_info;
 global $active_disable_array,$common_advertiser_array,$xml_html_array;
	//Check values on emptiness
	$vallist = array($title,$url,$registered,$refresh_rate,$max_recursion_depths,$isactive,$feed_type);
	$errlist = array($Error_messages["no_title"],$Error_messages["no_url"],$Error_messages["no_registered"],$Error_messages["no_refresh_rate"],
									$Error_messages["no_max_recursion_depths"],$Error_messages["no_isactive"],$Error_messages["no_feed_type"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)

	is_url($url,$Error_messages["invalid_feed_url"],$my_error);

	if (!isset($active_disable_array[$isactive])) $my_error .= $Error_messages["invalid_feed_status"];
	if (!isset($common_advertiser_array[$feed_type])) $my_error .= $Error_messages["invalid_feed_feed_type"];

	if (!check_date($registered)) $my_error .= $Error_messages["invalid_regdate"];

	$feed_code = preg_replace("/[^\w\d-_]/si", "", $title);
	if ($feed_code == "") $feed_code = preg_replace("/[^\w\d-_]/si", "", $url);
	if (find_feed_code_in_db($feed_id,$feed_code)) $my_error .= $Error_messages["find_in_db_feed_code"];

	if ( ($feed_type == 'advertiser') && (($job_ads_id == '') || ($job_ads_id == '0')) ) $my_error .= $Error_messages["invalid_job_ads_id"];

	if (!isset($xml_html_array[$feed_format])) $my_error .= $Error_messages["invalid_feed_format"];
}

function find_feed_code_in_db($feed_id,$feed_code)
{
 global $db_tables;
	$feed_code = data_addslashes($feed_code);
	$qr_res = mysql_query("SELECT feed_id FROM ".$db_tables["sites_feed_list"]." WHERE feed_code='$feed_code' and feed_id<>'$feed_id'") or query_die(__FILE__,__LINE__,mysql_error());
	return (mysql_num_rows($qr_res) > 0) ? true : false;
}

function try_add()
{
 global $smarty,$Error_messages,$text_info;
	$my_error = "";

	get_cur_values($title,$description,$url,$registered,$refresh_rate,$max_recursion_depths,$isactive,$feed_type,$job_ads_id,$feed_format);
	check_cur_values(0,$my_error,$title,$description,$url,$registered,$refresh_rate,$max_recursion_depths,$isactive,$feed_type,$feed_code,$job_ads_id,$feed_format);

	//If no errors - save
	if ($my_error == "") {
		slash_cur_values($title,$description,$url,$registered,$refresh_rate,$max_recursion_depths,$isactive,$feed_type,$job_ads_id,$feed_format);
		$sql_regdate = get_mysql_date($registered,$my_error);
		//Update data
		add_feed_info($title,$description,$url,$sql_regdate,$refresh_rate,$max_recursion_depths,$isactive,$feed_type,$feed_code,$job_ads_id,$feed_format);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		create_values($title,$description,$url,$registered,$refresh_rate,$max_recursion_depths,$isactive,$feed_type,$job_ads_id,$feed_format);
		create_page_buttons("add",$text_info["btn_save"]);
	}
}

function try_save($feed_id)
{
 global $smarty,$Error_messages,$text_info;
	$my_error = "";

	get_cur_values($title,$description,$url,$registered,$refresh_rate,$max_recursion_depths,$isactive,$feed_type,$job_ads_id,$feed_format);
	check_cur_values($feed_id,$my_error,$title,$description,$url,$registered,$refresh_rate,$max_recursion_depths,$isactive,$feed_type,$feed_code,$job_ads_id,$feed_format);

	//If no errors - save
	if ($my_error == "") {
		slash_cur_values($title,$description,$url,$registered,$refresh_rate,$max_recursion_depths,$isactive,$feed_type,$job_ads_id,$feed_format);
		$sql_regdate = get_mysql_date($registered,$my_error);
		//Update data
		update_feed_info($feed_id,$title,$description,$url,$sql_regdate,$refresh_rate,$max_recursion_depths,$isactive,$feed_type,$feed_code,$job_ads_id,$feed_format);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		create_values($title,$description,$url,$registered,$refresh_rate,$max_recursion_depths,$isactive,$feed_type,$job_ads_id,$feed_format);
		create_page_buttons("save",$text_info["btn_save"]);
	}
}
?>