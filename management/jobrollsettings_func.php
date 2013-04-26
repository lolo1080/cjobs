<?
function get_jobrollspan($name, $color)
{
 return '<span name="'.$name.'_example" id="'.$name.'_example" onClick="cp.select('.$name.',\''.$name.'_example\');return false;" style="cursor:pointer;width:18px;height:18px;background-color:'.$color.';border:solid 1px #999;" title="Select color">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
}
function create_values($job_set_colors_bg,$job_set_colors_title,$job_set_colors_border,$job_set_colors_job_title,$job_set_colors_text,
	$job_set_colors_company,$job_set_colors_link,$job_set_colors_source,$job_set_colors_accent,$job_set_colors_location)
{
 global $smarty,$text_info,$SLINE;

	$FormElements = array(
	//Color Settings
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_color_settings"], "after_html"=>""),
	array("flabel"=>show_cell_caption("job_colors_bg"), "before_html"=>"", "after_html"=>get_jobrollspan('job_set_colors_bg',$job_set_colors_bg), "etype"=>"text",
				"ename"=>"job_set_colors_bg", "ereadonly"=>"", "evalue"=>$job_set_colors_bg, "emaxlength"=>"7",
				"estyle"=>"width:170px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("job_set_colors_title"), "before_html"=>"", "after_html"=>get_jobrollspan('job_set_colors_title',$job_set_colors_title), "etype"=>"text",
				"ename"=>"job_set_colors_title", "ereadonly"=>"", "evalue"=>$job_set_colors_title, "emaxlength"=>"7",
				"estyle"=>"width:170px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("job_set_colors_border"), "before_html"=>"", "after_html"=>get_jobrollspan('job_set_colors_border',$job_set_colors_border), "etype"=>"text",
				"ename"=>"job_set_colors_border", "ereadonly"=>"", "evalue"=>$job_set_colors_border, "emaxlength"=>"7",
				"estyle"=>"width:170px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("job_set_colors_job_title"), "before_html"=>"", "after_html"=>get_jobrollspan('job_set_colors_job_title',$job_set_colors_job_title), "etype"=>"text",
				"ename"=>"job_set_colors_job_title", "ereadonly"=>"", "evalue"=>$job_set_colors_bg, "emaxlength"=>"7",
				"estyle"=>"width:170px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("job_set_colors_text"), "before_html"=>"", "after_html"=>get_jobrollspan('job_set_colors_text',$job_set_colors_text), "etype"=>"text",
				"ename"=>"job_set_colors_text", "ereadonly"=>"", "evalue"=>$job_set_colors_text, "emaxlength"=>"7",
				"estyle"=>"width:170px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("job_set_colors_company"), "before_html"=>"", "after_html"=>get_jobrollspan('job_set_colors_company',$job_set_colors_company), "etype"=>"text",
				"ename"=>"job_set_colors_company", "ereadonly"=>"", "evalue"=>$job_set_colors_company, "emaxlength"=>"7",
				"estyle"=>"width:170px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("job_set_colors_link"), "before_html"=>"", "after_html"=>get_jobrollspan('job_set_colors_link',$job_set_colors_link), "etype"=>"text",
				"ename"=>"job_set_colors_link", "ereadonly"=>"", "evalue"=>$job_set_colors_link, "emaxlength"=>"7",
				"estyle"=>"width:170px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("job_set_colors_source"), "before_html"=>"", "after_html"=>get_jobrollspan('job_set_colors_source',$job_set_colors_source), "etype"=>"text",
				"ename"=>"job_set_colors_source", "ereadonly"=>"", "evalue"=>$job_set_colors_source, "emaxlength"=>"7",
				"estyle"=>"width:170px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("job_set_colors_accent"), "before_html"=>"", "after_html"=>get_jobrollspan('job_set_colors_accent',$job_set_colors_accent), "etype"=>"text",
				"ename"=>"job_set_colors_accent", "ereadonly"=>"", "evalue"=>$job_set_colors_accent, "emaxlength"=>"7",
				"estyle"=>"width:170px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("job_set_colors_location"), "before_html"=>"", "after_html"=>get_jobrollspan('job_set_colors_location',$job_set_colors_location), "etype"=>"text",
				"ename"=>"job_set_colors_location", "ereadonly"=>"", "evalue"=>$job_set_colors_location, "emaxlength"=>"7",
				"estyle"=>"width:170px", "isheadline"=>false, "edisabled"=>"")
	);

	$smarty->assign("FormElements",$FormElements);
}

function update_data($job_set_colors_bg,$job_set_colors_title,$job_set_colors_border,$job_set_colors_job_title,$job_set_colors_text,
	$job_set_colors_company,$job_set_colors_link,$job_set_colors_source,$job_set_colors_accent,$job_set_colors_location)
{
 global $db_tables;
	//Delete old JobrollSettings values
	mysql_query("DELETE FROM ".$db_tables["jobrollsettings"]) or query_die(__FILE__,__LINE__,mysql_error());

	$jobs_array = array("job_set_colors_bg"=>$job_set_colors_bg,"job_set_colors_title"=>$job_set_colors_title,
		"job_set_colors_border"=>$job_set_colors_border,"job_set_colors_job_title"=>$job_set_colors_job_title,
		"job_set_colors_text"=>$job_set_colors_text,"job_set_colors_company"=>$job_set_colors_company,
		"job_set_colors_link"=>$job_set_colors_link,"job_set_colors_source"=>$job_set_colors_source,
		"job_set_colors_accent"=>$job_set_colors_accent,"job_set_colors_location"=>$job_set_colors_location);

	//Insert new JobrollSettings values
	foreach ($jobs_array as $k=>$v)
	{
		mysql_query("INSERT INTO ".$db_tables["jobrollsettings"]." VALUES('$k','$v')") or query_die(__FILE__,__LINE__,mysql_error());
	}

	//Send event
	$event_array = array("event"=>"insert", "source"=>"jobrollsettings", "table"=>"jobrollsettings", "ad_id"=>0);
	event_handler($event_array);
}

function try_change()
{
 global $Error_messages,$yes_no_array,$target_array,$display_rank_array,$affiliate_type_array;
	$my_error = "";

	//Get values
	$job_set_colors_bg				= html_chars(get_post_value("job_set_colors_bg",""));
	$job_set_colors_title			= html_chars(get_post_value("job_set_colors_title",""));
	$job_set_colors_border		= html_chars(get_post_value("job_set_colors_border",""));
	$job_set_colors_job_title	= html_chars(get_post_value("job_set_colors_job_title",""));
	$job_set_colors_text			= html_chars(get_post_value("job_set_colors_text",""));
	$job_set_colors_company		= html_chars(get_post_value("job_set_colors_company",""));
	$job_set_colors_link			= html_chars(get_post_value("job_set_colors_link",""));
	$job_set_colors_source		= html_chars(get_post_value("job_set_colors_source",""));
	$job_set_colors_accent		= html_chars(get_post_value("job_set_colors_accent",""));
	$job_set_colors_location	= html_chars(get_post_value("job_set_colors_location",""));

	//Check values on emptiness
	$vallist = array($job_set_colors_bg,$job_set_colors_title,$job_set_colors_border,$job_set_colors_job_title,$job_set_colors_text,
			$job_set_colors_company,$job_set_colors_link,$job_set_colors_source,$job_set_colors_accent,$job_set_colors_location);
	$errlist = array($Error_messages["invalid_job_set_colors_bg"],$Error_messages["invalid_job_set_colors_title"],
			$Error_messages["invalid_job_set_colors_border"],$Error_messages["invalid_job_set_colors_job_title"],
			$Error_messages["invalid_job_set_colors_text"],$Error_messages["invalid_job_set_colors_company"],
			$Error_messages["invalid_job_set_colors_link"],$Error_messages["invalid_job_set_colors_source"],
			$Error_messages["invalid_job_set_colors_accent"],$Error_messages["invalid_job_set_colors_location"]);
	is_color($vallist,$errlist,$my_error); //Check values on emptiness (function)

	//If no errors - save data
	if ($my_error == "") {
		$job_set_colors_bg				= data_addslashes($job_set_colors_bg);
		$job_set_colors_title			= data_addslashes($job_set_colors_title);
		$job_set_colors_border		= data_addslashes($job_set_colors_border);
		$job_set_colors_job_title	= data_addslashes($job_set_colors_job_title);
		$job_set_colors_text			= data_addslashes($job_set_colors_text);
		$job_set_colors_company		= data_addslashes($job_set_colors_company);
		$job_set_colors_link			= data_addslashes($job_set_colors_link);
		$job_set_colors_source		= data_addslashes($job_set_colors_source);
		$job_set_colors_accent		= data_addslashes($job_set_colors_accent);
		$job_set_colors_location	= data_addslashes($job_set_colors_location);

		//Update data
		update_data($job_set_colors_bg,$job_set_colors_title,$job_set_colors_border,$job_set_colors_job_title,$job_set_colors_text,
			$job_set_colors_company,$job_set_colors_link,$job_set_colors_source,$job_set_colors_accent,$job_set_colors_location);
	}
	else {//else - try again
		smarty_create_message("error","abort.gif",$my_error);
	}
	create_values($job_set_colors_bg,$job_set_colors_title,$job_set_colors_border,$job_set_colors_job_title,$job_set_colors_text,
		$job_set_colors_company,$job_set_colors_link,$job_set_colors_source,$job_set_colors_accent,$job_set_colors_location);
}
?>