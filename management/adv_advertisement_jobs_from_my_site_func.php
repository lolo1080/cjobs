<?
function get_job_ad_status($job_ads_id)
{
 global $db_tables;
	$qr_res = mysql_query("SELECT status FROM ".$db_tables["job_ads"]." WHERE job_ads_id='$job_ads_id'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) return 2;
	$myrow = mysql_fetch_array($qr_res);
 return $myrow["status"];
}

function create_values($action,$job_ads_id,$ad_name,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status)
{
 global $smarty,$text_info,$usersettings,$active_disable_array,$SLINE;
	if ($action == "add") {
		$ereadonly = "";
		$bgc = "";
	}
	else {
		$ereadonly = "readonly";
		$bgc = "background-color:#EEEEE9;";
	}
	$ad_status_selectbox	= get_selectbox_data($active_disable_array,$status);

	$FormElements = array(
	array("flabel"=>show_cell_caption("ad_name"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"ad_name", "ereadonly"=>"", "evalue"=>$ad_name, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("destination_url"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"destination_url", "ereadonly"=>$ereadonly, "evalue"=>$destination_url, "emaxlength"=>"100",
				"estyle"=>"width:300px;$bgc", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>str_replace("{*MinCPC*}",$_SESSION["globsettings"]["min_adv_cost_per_click"],show_cell_caption("max_cpc")), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"max_cpc", "ereadonly"=>"", "evalue"=>$max_cpc, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("daily_budget"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"daily_budget", "ereadonly"=>"", "evalue"=>$daily_budget, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("monthly_budget"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"monthly_budget", "ereadonly"=>"", "evalue"=>$monthly_budget, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("ad_status"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"status", "edisabled"=>"", "evalue"=>$ad_status_selectbox["val"],
				"eselected"=>$ad_status_selectbox["sel"], "ecaption"=>$ad_status_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>"")
	);
	if ( ($status == 2) || ($action == "add") ) array_pop($FormElements);
	$smarty->assign("FormElements",$FormElements);
}

function update_ad_info($job_ads_id,$ad_name,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status)
{
 global $db_tables, $SLINE;
	//обновляем статус сразу в двух таблицах, вторая нужна для скорости поиска
	//Update Job Ad info
	$cur_status = get_job_ad_status($job_ads_id);
	if ( ($cur_status == 2) || !in_array($status, array(0,1)) ) $status = 2;
	mysql_query("UPDATE ".$db_tables["job_ads"]." SET ad_name='$ad_name',max_cpc='$max_cpc',daily_budget='$daily_budget',".
				"monthly_budget='$monthly_budget',status='$status' WHERE job_ads_id='$job_ads_id'") or query_die(__FILE__,__LINE__,mysql_error());

	//Send event
	$event_array = array("event"=>"update", "source"=>"adv_advertisement_jobs_from_my_site", "table"=>"job_ads", "job_ads_id"=>$job_ads_id);
	event_handler($event_array);

	header("Location: adv_advertisements.php?$SLINE"); exit;
}

function add_ad_info($ad_name,$destination_url,$max_cpc,$daily_budget,$monthly_budget)
{
 global $db_tables, $SLINE;
	//Add Ad info
	mysql_query("INSERT INTO ".$db_tables["job_ads"]." VALUES(NULL,'".$_SESSION["sess_userid"]."','$ad_name','$destination_url',".
		"'$max_cpc','$daily_budget','$monthly_budget','2')") or query_die(__FILE__,__LINE__,mysql_error());

	$job_ads_id = mysql_insert_id();

	//Send event
	$event_array = array("event"=>"insert", "source"=>"adv_advertisement_jobs_from_my_site", "table"=>"job_ads", "job_ads_id"=>$job_ads_id);
	event_handler($event_array);

	header("Location: adv_advertisements.php?$SLINE"); exit;
}

function get_cur_values(&$ad_name,&$destination_url,&$max_cpc,&$daily_budget,&$monthly_budget,&$status)
{
	$ad_name				= html_chars(get_post_value("ad_name",""));
	$destination_url= html_chars(get_post_value("destination_url",""));
	$max_cpc				= html_chars(get_post_value("max_cpc",""));
	$daily_budget		= html_chars(get_post_value("daily_budget",""));
	$monthly_budget	= html_chars(get_post_value("monthly_budget",""));
	$status					= html_chars(get_post_value("status",""));
}

function check_cur_values($action,&$my_error,&$ad_name,&$destination_url,&$max_cpc,&$daily_budget,&$monthly_budget,&$status)
{
 global $Error_messages,$text_info,$active_disable_array;
	//Check values on emptiness
	$vallist = array($ad_name,$destination_url,$max_cpc,$daily_budget,$monthly_budget);
	$errlist = array($Error_messages["no_ad_name"],$Error_messages["no_destination_url"],
				$Error_messages["no_max_cpc"],$Error_messages["no_daily_budget"],$Error_messages["no_monthly_budget"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)

	if ($action == "add") {
		//Check URLs
		is_url($destination_url,$Error_messages["invalid_destination_url"],$my_error);
	}

	if (($max_cpc !="") && !check_float($max_cpc)) $my_error .= $Error_messages["invalid_max_cpc"];
	if (($daily_budget != "") && !check_float($daily_budget)) $my_error .= $Error_messages["invalid_daily_budget"];
	if (($monthly_budget != "") && !check_float($monthly_budget)) $my_error .= $Error_messages["invalid_monthly_budget"];

	if (($max_cpc < $_SESSION["globsettings"]["min_adv_cost_per_click"]))
			$my_error .= str_replace("{*Amount*}",$_SESSION["globsettings"]["min_adv_cost_per_click"],$Error_messages["small_max_cpc"]);
}

function slash_cur_values(&$ad_name,&$ad_name,&$destination_url,&$max_cpc,&$daily_budget,&$monthly_budget,&$status)
{
	$ad_name				= data_addslashes($ad_name);
	$destination_url= data_addslashes($destination_url);
	$max_cpc				= data_addslashes($max_cpc);
	$daily_budget		= data_addslashes($daily_budget);
	$monthly_budget	= data_addslashes($monthly_budget);
	$status					= data_addslashes($status);
}

function try_add()
{
 global $Error_messages,$text_info,$db_tables;
	$my_error = "";
	get_cur_values($ad_name,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status);
	check_cur_values("add",$my_error,$ad_name,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status);

	//If no errors - save
	if ($my_error == "") {
		slash_cur_values($ad_name,$ad_name,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status);
		//Update data
		add_ad_info($ad_name,$destination_url,$max_cpc,$daily_budget,$monthly_budget);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		create_values("add","",$ad_name,$destination_url,$max_cpc,$daily_budget,$monthly_budget,'2');
		create_page_buttons("add",$text_info["btn_add"]);
	}
}

function try_save($job_ads_id)
{
 global $smarty,$Error_messages,$text_info,$db_tables,$active_disable_array;
	$my_error = "";
	get_cur_values($ad_name,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status);
	check_cur_values("edit",$my_error,$ad_name,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status);

	//If no errors - save
	if ($my_error == "") {
		slash_cur_values($ad_name,$ad_name,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status);
		//Update data
		update_ad_info($job_ads_id,$ad_name,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		$cur_status = get_job_ad_status($job_ads_id);
		if ( ($cur_status == 2) || !in_array($status, array(0,1)) ) $status = 2;
		create_values("edit",$job_ads_id,$ad_name,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status);
		create_page_buttons("save",$text_info["btn_save"]);
	}
}
?>