<?
$js_vals = array("job_alert_type","what","where","distance"           ,"deliver","as_all","as_phrase","as_any","as_not","as_title","as_company","jobs_category","jobs_type"             ,"jobs_from"             ,"norecruiters","salary");
$js_defs = array("simple"        ,""    ,""     ,$radius_array_default,"1"      ,""      ,""         ,""      ,""      ,""        ,""          ,"0"            ,$jobs_type_array_default,$jobs_from_array_default,"0"           ,"");

function unpack_jobalert($job_alert)
{
 global $js_vals,$js_defs;
	for ($i=0; $i<count($js_vals); $i++)
	{
		$js[$js_vals[$i]] = (preg_match("~<{$js_vals[$i]}>(.*?)</{$js_vals[$i]}>~i", $job_alert, $matches)) ? $matches[1] : $js_defs[$i];
	}
 return $js;
}

function pack_jobalert($js)
{
	$str = "";
	foreach ($js as $k=>$v)
	{
		$str .= "<{$k}>{$v}</{$k}>";
	}
 return $str;
}

function create_lnk($script,$type,$SLINE,$img_text,$lnk_text)
{
 global $action,$SLINE;
 return '<a class="pagelink" href="'.$script.'?job_alert_type='.$type.'&action='.$action.'&'.$SLINE.'" >'.get_img("arrow.gif",20,20,$img_text,get_js_action(10)).$lnk_text.'</a>';
}

function create_values($ja_id,$name,$js,$deliver,$status)
{
 global $smarty,$text_info,$usersettings,$SLINE,$active_disable_array;
	$distance_selectbox	= get_distance_selectbox_data($js["distance"]);
	$deliver_selectbox	= get_deliver_selectbox_data($deliver);
	$status_selectbox	= get_selectbox_data($active_disable_array,$status);;

	if ($_SESSION["sess_job_alert_type"] == 'simple') {
		$advanced_form_lnk	= create_lnk("member_jobalert_work.php",'advanced',$SLINE,$text_info["c_advanced_job_alert_form"],$text_info["c_show_advanced_job_alert_form"]);
		$FormElements = array(
		array("flabel"=>show_cell_caption("job_alert_form"), "before_html"=>$advanced_form_lnk, "after_html"=>"", "etype"=>"none",
					"ename"=>"", "ereadonly"=>"", "evalue"=>"", "emaxlength"=>"",
					"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("name"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
					"ename"=>"name", "ereadonly"=>"", "evalue"=>$name, "emaxlength"=>"80",
					"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("what"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
					"ename"=>"what", "ereadonly"=>"", "evalue"=>$js["what"], "emaxlength"=>"200",
					"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("where"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
					"ename"=>"where", "ereadonly"=>"", "evalue"=>$js["where"], "emaxlength"=>"200",
					"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("distance"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
					"ename"=>"distance", "edisabled"=>"", "evalue"=>$distance_selectbox["val"],
					"eselected"=>$distance_selectbox["sel"], "ecaption"=>$distance_selectbox["capt"],
					"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("deliver"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
					"ename"=>"deliver", "edisabled"=>"", "evalue"=>$deliver_selectbox["val"],
					"eselected"=>$deliver_selectbox["sel"], "ecaption"=>$deliver_selectbox["capt"],
					"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("status"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
					"ename"=>"status", "edisabled"=>"", "evalue"=>$status_selectbox["val"],
					"eselected"=>$status_selectbox["sel"], "ecaption"=>$status_selectbox["capt"],
					"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>"")
		);
	}
	else {
		$simple_form_lnk	= create_lnk("member_jobalert_work.php",'simple',$SLINE,$text_info["c_simple_job_alert_form"],$text_info["c_show_simple_job_alert_form"]);
		$jobs_category_selectbox = get_jobs_category_selectbox_data($js["jobs_category"]);
		$jobs_type_selectbox = get_jobs_type_selectbox_data($js["jobs_type"]);
		$jobs_from_selectbox = get_jobs_from_selectbox_data($js["jobs_from"]);
		$FormElements = array(
		array("flabel"=>show_cell_caption("job_alert_form"), "before_html"=>$simple_form_lnk, "after_html"=>"", "etype"=>"none",
					"ename"=>"", "ereadonly"=>"", "evalue"=>"", "emaxlength"=>"",
					"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("name"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
					"ename"=>"name", "ereadonly"=>"", "evalue"=>$name, "emaxlength"=>"80",
					"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_keywords"], "after_html"=>""),
		array("flabel"=>show_cell_caption("as_all"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
					"ename"=>"as_all", "ereadonly"=>"", "evalue"=>$js["as_all"], "emaxlength"=>"200",
					"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("as_phrase"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
					"ename"=>"as_phrase", "ereadonly"=>"", "evalue"=>$js["as_phrase"], "emaxlength"=>"200",
					"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("as_any"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
					"ename"=>"as_any", "ereadonly"=>"", "evalue"=>$js["as_any"], "emaxlength"=>"200",
					"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("as_not"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
					"ename"=>"as_not", "ereadonly"=>"", "evalue"=>$js["as_not"], "emaxlength"=>"200",
					"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("as_title"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
					"ename"=>"as_title", "ereadonly"=>"", "evalue"=>$js["as_title"], "emaxlength"=>"200",
					"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("as_company"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
					"ename"=>"as_company", "ereadonly"=>"", "evalue"=>$js["as_company"], "emaxlength"=>"200",
					"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),

		array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_location"], "after_html"=>""),
		array("flabel"=>show_cell_caption("where"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
					"ename"=>"where", "ereadonly"=>"", "evalue"=>$js["where"], "emaxlength"=>"200",
					"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("distance"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
					"ename"=>"distance", "edisabled"=>"", "evalue"=>$distance_selectbox["val"],
					"eselected"=>$distance_selectbox["sel"], "ecaption"=>$distance_selectbox["capt"],
					"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),

		array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_job"], "after_html"=>""),
		array("flabel"=>show_cell_caption("jobs_category"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
					"ename"=>"jobs_category", "edisabled"=>"", "evalue"=>$jobs_category_selectbox["val"],
					"eselected"=>$jobs_category_selectbox["sel"], "ecaption"=>$jobs_category_selectbox["capt"],
					"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("jobs_type"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
					"ename"=>"jobs_type", "edisabled"=>"", "evalue"=>$jobs_type_selectbox["val"],
					"eselected"=>$jobs_type_selectbox["sel"], "ecaption"=>$jobs_type_selectbox["capt"],
					"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("jobs_from"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
					"ename"=>"jobs_from", "edisabled"=>"", "evalue"=>$jobs_from_selectbox["val"],
					"eselected"=>$jobs_from_selectbox["sel"], "ecaption"=>$jobs_from_selectbox["capt"],
					"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("norecruiters"), "before_html"=>"", "after_html"=>"", "etype"=>"checkbox",
					"ename"=>"norecruiters", "ereadonly"=>"", "evalue"=>"1", "echecked"=>$js["norecruiters"],
					"isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("salary"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
					"ename"=>"salary", "ereadonly"=>"", "evalue"=>$js["salary"], "emaxlength"=>"20",
					"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("deliver"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
					"ename"=>"deliver", "edisabled"=>"", "evalue"=>$deliver_selectbox["val"],
					"eselected"=>$deliver_selectbox["sel"], "ecaption"=>$deliver_selectbox["capt"],
					"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
		array("flabel"=>show_cell_caption("status"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
					"ename"=>"status", "edisabled"=>"", "evalue"=>$status_selectbox["val"],
					"eselected"=>$status_selectbox["sel"], "ecaption"=>$status_selectbox["capt"],
					"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>"")
		);
	}
	$smarty->assign("FormElements",$FormElements);
}

function update_ja_info($ja_id,$name,$js,$deliver,$status)
{
 global $db_tables, $SLINE;
	$js["job_alert_type"] = $_SESSION["sess_job_alert_type"];
	$job_alert = pack_jobalert($js);
	mysql_query("UPDATE ".$db_tables["member_job_alerts"]." SET name='$name',job_alert='$job_alert',deliver='$deliver',status='$status' ".
				"WHERE ja_id='$ja_id'") or query_die(__FILE__,__LINE__,mysql_error());
	header("Location: member_jobalert.php?$SLINE"); exit;
}

function add_ja_info($name,$js,$deliver,$status)
{
 global $db_tables, $SLINE;
	if ($_SESSION["sess_user"] == "0") {
		$uid_mem = "";
		$uid_mem = check_sess_id_values($uid_mem,"uid_mem");
		if ($uid_mem == "") critical_error(__FILE__,__LINE__,"No Member ID");
	}
	elseif ($_SESSION["sess_user"] == "3") {
		$uid_mem = $_SESSION["sess_userid"];
	}
	$js["job_alert_type"] = $_SESSION["sess_job_alert_type"];
	$job_alert = pack_jobalert($js);
	mysql_query("INSERT INTO ".$db_tables["member_job_alerts"]." VALUES(NULL,'$uid_mem','$name','$job_alert',NOW(),NOW(),'$deliver','$status')") or query_die(__FILE__,__LINE__,mysql_error());
	header("Location: member_jobalert.php?$SLINE"); exit;
}

function get_cur_values(&$name,&$js,&$deliver,&$status,&$jobs_category,&$norecruiters,&$salary)
{
 global $js_vals,$js_defs;
	$name	= html_chars(get_post_value("name",""));
	for ($i=0; $i<count($js_vals); $i++)
	{
		$js[$js_vals[$i]] = html_chars(get_post_value($js_vals[$i],$js_defs[$i]));
	}
	$deliver	= html_chars(get_post_value("deliver",""));
	$status		= html_chars(get_post_value("status",""));
	$jobs_category= html_chars(get_post_value("jobs_category",""));
	$norecruiters	= html_chars(get_post_value("norecruiters",0));
	$salary		= understand_job_salary(html_chars(get_post_value("salary","")));
}

function slash_cur_values(&$name,&$js,&$deliver,&$status,&$jobs_category,&$norecruiters,&$salary)
{
 global $js_vals,$js_defs;
	$name	= data_addslashes($name);
	for ($i=0; $i<count($js_vals); $i++)
	{
		$js[$js_vals[$i]] = data_addslashes($js[$js_vals[$i]]);
	}
	$deliver 	= data_addslashes($deliver);
	$status		= data_addslashes($status);
	$jobs_category= data_addslashes($jobs_category);
	$norecruiters	= data_addslashes($norecruiters);
	$salary		= data_addslashes($salary);
}

function check_cur_values($name,&$js,$deliver,$status,$jobs_category,$norecruiters,$salary,&$my_error)
{
 global $Error_messages,$radius_array,$jobs_type_array,$jobs_from_array;
	//Check values on emptiness
	$vallist = array($name,$deliver,$status);
	$errlist = array($Error_messages["no_name"],$Error_messages["no_deliver"],$Error_messages["no_status"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)
	if (!isset($radius_array[$js["distance"]])) $my_error .= $Error_messages["invalid_distance"];

	if ($_SESSION["sess_job_alert_type"] == 'simple') {
		if (($js["what"] == "") && ($js["where"] == "")) $my_error .= $Error_messages["no_what_where"];
	}
	else {
		if (($js["where"] == "") && ($js["as_all"] == "") && ($js["as_phrase"] == "") && ($js["as_any"] == "") && ($js["as_not"] == "") && ($js["as_title"] == "") && ($js["as_company"] == "") ) $my_error .= $Error_messages["no_advanced_what_where"];
		//Check arrays
		if (!isset($jobs_type_array[$js["jobs_type"]])) $my_error .= $Error_messages["invalid_jobs_type"];
		if (!isset($jobs_from_array[$js["jobs_from"]])) $my_error .= $Error_messages["invalid_jobs_from"];

		//Check category
		if ( ($jobs_category != 0) && (!check_job_category($jobs_category)) ) $my_error .= $Error_messages["invalid_jobs_category"];
	}
}

function try_add()
{
 global $Error_messages,$text_info,$db_tables;
	$my_error = "";
	get_cur_values($name,$js,$deliver,$status,$jobs_category,$norecruiters,$salary);
	check_cur_values($name,$js,$deliver,$status,$jobs_category,$norecruiters,$salary,$my_error);
	//If no errors - save
	if ($my_error == "") {
		slash_cur_values($name,$js,$deliver,$status,$jobs_category,$norecruiters,$salary);
		//Add data
		add_ja_info($name,$js,$deliver,$status);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		create_values("",$name,$js,$deliver,$status);
		create_page_buttons("add",$text_info["btn_add"]);
	}
}

function try_save($ja_id)
{
 global $Error_messages,$text_info,$db_tables;
	$my_error = "";
	get_cur_values($name,$js,$deliver,$status,$jobs_category,$norecruiters,$salary);
	check_cur_values($name,$js,$deliver,$status,$jobs_category,$norecruiters,$salary,$my_error);
	//If no errors - save
	if ($my_error == "") {
		slash_cur_values($name,$js,$deliver,$status,$jobs_category,$norecruiters,$salary);
		//Update data
		update_ja_info($ja_id,$name,$js,$deliver,$status);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		create_values($ja_id,$name,$js,$deliver,$status);
		create_page_buttons("save",$text_info["btn_save"]);
	}
}
?>