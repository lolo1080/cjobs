<?
function create_values($data_id,$regdate,$feed_id,$title,$company_name,$location,$location_not_db,$description,$url,$cat_name,$salary,$dateinsert,$feed_title)
{
 global $smarty,$calendar_button,$text_info,$usersettings,$confirmed_not_confirmed_array,$SLINE;
	$date_format_str		= "(".$usersettings["dateformat_c_info"].")";
//	$country_selectbox	= get_country_selectbox_data($country_id);
	$FormElements = array(
	array("flabel"=>show_cell_caption("regdate",true,true).'<br />'.$date_format_str, "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"regdate", "ereadonly"=>"readonly", "evalue"=>$regdate, "emaxlength"=>"150",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("feed_title",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"feed_title", "ereadonly"=>"readonly", "evalue"=>$feed_title, "emaxlength"=>"150",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("job_title",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"title", "ereadonly"=>"", "evalue"=>$title, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("company_name",true,false), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"company_name", "ereadonly"=>"", "evalue"=>$company_name, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("location_db",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"location", "ereadonly"=>"readonly", "evalue"=>$location, "emaxlength"=>"100",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("location_not_db",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"location_not_db", "ereadonly"=>"readonly", "evalue"=>$location_not_db, "emaxlength"=>"100",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("job_description",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"textarea",
				"ename"=>"description", "ereadonly"=>"", "evalue"=>$description,
				"estyle"=>"width:300px;height:85px;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("url",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"textarea",
				"ename"=>"url", "ereadonly"=>"", "evalue"=>$url,
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("job_salary",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"salary", "ereadonly"=>"", "evalue"=>$salary, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("category",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"cat_name", "ereadonly"=>"readonly", "evalue"=>$cat_name, "emaxlength"=>"100",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>"")
	);
	$smarty->assign("FormElements",$FormElements);
}

function update_job_info($data_id,$title,$company_name,$description,$url,$salary)
{
 global $db_tables, $SLINE;
	mysql_query("UPDATE ".$db_tables["data_list"]." SET title='$title',company_name='$company_name',description='$description',".
				"url='$url',salary='$salary' WHERE data_id='$data_id'") or query_die(__FILE__,__LINE__,mysql_error());
	header("Location: jobs_list_common.php?$SLINE"); exit;
}

function try_save($data_id)
{
 global $smarty,$Error_messages,$text_info,$db_tables,$confirmed_not_confirmed_array;
	$my_error = "";
	$title				= html_chars(get_post_value("title",""));
	$company_name	= html_chars(get_post_value("company_name",""));
	$description	= html_chars(get_post_value("description",""));
	$url					= html_chars(get_post_value("url",""));
	$salary				= html_chars(get_post_value("salary",""));

	//Check values on emptiness
	$vallist = array($title,$description,$url,$salary);
	$errlist = array($Error_messages["no_title"],$Error_messages["no_description"],
		$Error_messages["no_url"],$Error_messages["no_salary"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)

//	if (!check_country_id($country)) $my_error .= $Error_messages["invalid_country_id"];
	if ($url != "") {
		$url_ = $url; $url = check_url($url); 
		if ($url === false) {
			$my_error .= $Error_messages["invalid_url"]; $url = $url_;
		}
	}
//	if (!check_date($regdate)) $my_error .= $Error_messages["invalid_regdate"];

	//If no errors - save
	if ($my_error == "") {
		$title	= data_addslashes($title);
		$company_name = data_addslashes($company_name);
		$description	= data_addslashes($description);
		$url					= data_addslashes($url);
		$salary				= data_addslashes($salary);
//		$sql_regdate = get_mysql_date($regdate,$my_error);
		//Update data
		update_job_info($data_id,$title,$company_name,$description,$url,$salary);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		$qr_res = mysql_query("SELECT d.*,".format_sql_datetime("d.dateinsert")." as regdate, f.title as feed_title, r.name as rregionname, c.city as ccity, c.region as cregion, c.country as ccountry, cat.cat_name ".
			"FROM ".$db_tables["data_list"]." d ".
			"INNER JOIN ".$db_tables["sites_feed_list"]." f ON d.feed_id=f.feed_id ".
			"LEFT JOIN ".$db_tables["city"]." c ON d.locId=c.locId ".
			"LEFT JOIN ".$db_tables["region"]." r ON c.region=r.region ".
			"INNER JOIN ".$db_tables["jobcategories"]." cat ON d.cat_id=cat.cat_id ".
			"WHERE data_id='$data_id'") or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data (update error).");
		$myrow = mysql_fetch_array($qr_res);

		//Create form
		$location_db = '';
		if ($myrow["ccity"] != '') $location_db = $myrow["ccity"];
		$region = ($myrow["rregionname"] && ($myrow["rregionname"] != '')) ? $myrow["rregionname"] : $myrow["cregion"];
		if ($region != '') $location_db .= ( ($location_db != '') ? ', ' : '' ).$region;
		$location_db = $location_db.( ($location_db != '') ? ', ' : '' ).$myrow["ccountry"];
		$location_not_db = '';
		if ($myrow["city"] != '') $location_not_db = $myrow["city"];
		if ($myrow["region"] != '') $location_not_db .= ( ($location_not_db != '') ? ', ' : '' ).$myrow["region"];
		$location_not_db = $location_not_db.( ($location_not_db != '') ? ', ' : '' ).$myrow["country"];

		create_values($data_id,$myrow["regdate"],$myrow["feed_id"],$title,$company_name,$location_db,$location_not_db,$description,$url,
				$myrow["cat_name"],$salary,$myrow["dateinsert"],$myrow["feed_title"]);
		create_page_buttons("save",$text_info["btn_save"]);
	}
}
?>