<?
function create_values($template_id,$title,$diskname,$description,$caution_level,$show_type,$issystem,$template_type,$php_file,
	$templatebody)
{
 global $smarty,$text_info,$SLINE,$template_type_array,$caution_level_array,$show_type_array;
	$template_type_selectbox	= get_selectbox_data($template_type_array,$template_type);
	$caution_level_selectbox	= get_selectbox_data($caution_level_array,$caution_level);
	$show_type_selectbox	= get_selectbox_data($show_type_array,$show_type);
	//Template body: load from file or get from value
	$templatebody = ($templatebody == "") ? load_front_end_template($diskname) : $templatebody;
	$preg_search = array("~<(textarea[^>]*?)>~si","~</textarea>~");
	$preg_replace = array("~&lt;\\1&gt;~si","~&lt;/textarea&gt;~");
	$templatebody = preg_replace($preg_search, $preg_replace, $templatebody);
	if ($show_type == 1) {
		$smarty->assign("AddHTMLEditorBody",get_html_editor("templatebody_body",660,300,$_SESSION["globsettings"]["site_url"].'management/'));
		$smarty->assign("LoadEditorScript",true);
	}
	//Set readonly if it is system template
	if ($issystem == 0) {
		$tposition = $text_info["p_user_tpl"];
		$php_file_readonly = $php_file_bg = "";
	}
	else {
		$tposition = $text_info["p_system_tpl"];
		$php_file_readonly = " readonly ";
		$php_file_bg = "background-color:#EEEEE9;";
	}
	$php_file_value = $_SESSION["globsettings"]["site_url"].$php_file;
	$txt_lng = ( ($php_file == "") || ($php_file == $_SESSION["globsettings"]["site_url"]) ) ? "script_url1" : "script_url";
	$table_run_url = '<table width="100%" name="php_file_table" id="php_file_table">'.
		'<tr><td width="200" class="text_data2"><b>'.$text_info["p_".$txt_lng].':</b><br /><small>'.str_replace("{*base_url*}",$_SESSION["globsettings"]["site_url"],$text_info["h_".$txt_lng]).'</small></td>'.
		'<td><input class="data" type="text" '.$php_file_readonly.'name="php_file" id="php_file" value="'.$php_file_value.'" style="width:510px;'.$php_file_bg.'"></td></tr></table>';
	//Set selectbox or text depending on template type: system or not
	$smarty->assign("TplJavaScript","");
	if ($issystem == 0) {
		$smarty->assign("TplJavaScript","toggle_tpl_type_table_autoload()");
		$ttype_array = array("flabel"=>show_cell_caption("ttype"), "before_html"=>"", "after_html"=>$table_run_url, "etype"=>"select",
				"ename"=>"template_type", "edisabled"=>"", "evalue"=>$template_type_selectbox["val"],
				"eselected"=>$template_type_selectbox["sel"], "ecaption"=>$template_type_selectbox["capt"],
				"jscipt"=>"onChange=\"toggle_tpl_type_table(this)\"", "multiple"=>"", "estyle"=>"width:660px", "isheadline"=>false, "edisabled"=>"");
	}
	else {
		if ($template_type == 0) $table_run_url = "";
		$ttype_array = array("flabel"=>show_cell_caption("ttype",true), "before_html"=>"", "after_html"=>$table_run_url, "etype"=>"text",
				"ename"=>"template_type_line", "ereadonly"=>"readonly", "evalue"=>$template_type_array[$template_type], "emaxlength"=>"250",
				"estyle"=>"width:660px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>"");
	}
	//Form
	$FormElements = array(
	array("flabel"=>show_cell_caption("tposition",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"position", "ereadonly"=>"readonly", "evalue"=>$tposition, "emaxlength"=>"250",
				"estyle"=>"width:660px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("ttitle",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"title", "ereadonly"=>"", "evalue"=>$title, "emaxlength"=>"250",
				"estyle"=>"width:660px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("diskname",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"diskname", "ereadonly"=>"readonly", "evalue"=>$diskname, "emaxlength"=>"250",
				"estyle"=>"width:660px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("tdescription"), "before_html"=>"", "after_html"=>"", "etype"=>"textarea",
				"ename"=>"description", "ereadonly"=>"", "evalue"=>$description,
				"estyle"=>"width:660px;height:60px", "isheadline"=>false),
	$ttype_array,
	array("flabel"=>show_cell_caption("caution_level"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"caution_level", "edisabled"=>"", "evalue"=>$caution_level_selectbox["val"],
				"eselected"=>$caution_level_selectbox["sel"], "ecaption"=>$caution_level_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:660px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("show_type"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"show_type", "edisabled"=>"", "evalue"=>$show_type_selectbox["val"],
				"eselected"=>$show_type_selectbox["sel"], "ecaption"=>$show_type_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:660px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("tcontent_header"), "before_html"=>"", "after_html"=>"", "etype"=>"textarea",
				"ename"=>"templatebody_header", "ereadonly"=>"", "evalue"=>$templatebody["header"],
				"estyle"=>"width:660px; height:70px", "isheadline"=>false),
	array("flabel"=>show_cell_caption("tcontent_body"), "before_html"=>"", "after_html"=>"", "etype"=>"textarea",
				"ename"=>"templatebody_body", "ereadonly"=>"", "evalue"=>$templatebody["content"],
				"estyle"=>"width:660px; height:300px", "isheadline"=>false),
	array("flabel"=>show_cell_caption("tcontent_footer"), "before_html"=>"", "after_html"=>"", "etype"=>"textarea",
				"ename"=>"templatebody_footer", "ereadonly"=>"", "evalue"=>$templatebody["footer"],
				"estyle"=>"width:660px; height:70px", "isheadline"=>false)
	);
	$smarty->assign("FormElements",$FormElements);
}

function create_valirables_list($templatebody)
{
 global $tpl_val_list,$frontend_template_dir,$templates_var_list,$my_error;
	//Find varibales
	foreach ($templates_var_list as $k=>$v)
	{
		$pos = strpos($templatebody, $v["name"]);
		if ($pos !== false) {
			if (!in_array($k, $tpl_val_list)) $tpl_val_list[] = $k;
		}
	}
	//Find includes
	if ($c = preg_match_all("~{\s*?include\s*?file\s*?=\s*?(\"|\')+?(.+?)(\"|\')+?\s*?}~si", $templatebody, $res)) {
		if ( (isset($res[2])) && (count($res[2]) > 0) ) {
			for ($i=0; $i<count($res[2]); $i++)
			{
				$tpl_filename = $frontend_template_dir.$res[2][$i];
				if (!file_exists($tpl_filename)) $my_error .= "Cannot find ".$res[2][$i];
				$f = fopen($tpl_filename, "r");
				$tpl_content = fread($f, filesize($tpl_filename));
				fclose($f);
				create_valirables_list($tpl_content);
			}
		}
	}
}

function update_template_info($template_id,$title,$description,$caution_level,$show_type,$template_type,$php_file,$templatebody,$issystem)
{
 global $db_tables,$SLINE,$frontend_template_dir,$frontend_phpscripts_dir,$tpl_val_list;
	$add_sql = "";
	if (!$issystem) {
		$add_sql = ",template_type='$template_type'";
		if ($template_type) $add_sql .= ",php_file='$php_file'";
	}
	//Update DB - templates
	mysql_query("UPDATE ".$db_tables["templates"]." SET title='$title',description='$description',".
				"caution_level='$caution_level',show_type='$show_type' $add_sql".
				" WHERE template_id='$template_id'") or query_die(__FILE__,__LINE__,mysql_error());
	//Update template content
	$tpl_filename = get_tepl_diskname_by_id($template_id);
	if ($tpl_filename == "") critical_error(__FILE__,__LINE__,"Canno find template disk name.");
  $f = fopen($frontend_template_dir.$tpl_filename, "w");
	fwrite($f, "{*[start_template_header_part]*}\n{$templatebody["header"]}\n{*[end_template_header_part]*}\n{*[start_template_content_part]*}\n{$templatebody["content"]}\n{*[end_template_content_part]*}\n{*[start_template_footer_part]*}\n{$templatebody["footer"]}\n{*[end_template_footer_part]*}");
	fclose($f);
	@chmod($frontend_template_dir.$tpl_filename,0777);
	//Check old php file for not system templates
	if (!$issystem) {
		$qr_res = mysql_query("SELECT * FROM ".$db_tables["templates"]." WHERE template_id='$template_id'") or query_die(__FILE__,__LINE__,mysql_error());
		$myrow = mysql_fetch_array($qr_res);
		$db_php_file = $myrow["php_file"];
		if (strlen($db_php_file) > 4) {
			if (is_file($frontend_phpscripts_dir.$db_php_file)) @unlink($frontend_phpscripts_dir.$db_php_file);
		}
		//Create disk file (php)
		try_create_php_diskfile($template_type,$php_file,$template_id,$tpl_filename);
		//Update DB - templates
		mysql_query("UPDATE ".$db_tables["templates"]." SET php_file='$php_file' WHERE template_id='$template_id'") or query_die(__FILE__,__LINE__,mysql_error());
	}
	//Get templte values
	$tpl_val_list = array();
	create_valirables_list($templatebody["header"]);
	create_valirables_list($templatebody["content"]);
	create_valirables_list($templatebody["footer"]);
	//Remove templte values
	mysql_query("DELETE FROM ".$db_tables["template_values"]." WHERE template_id='$template_id'") or query_die(__FILE__,__LINE__,mysql_error());
	//Insert new templte values
	for ($i=0; $i<count($tpl_val_list); $i++)
	{
		mysql_query("INSERT INTO ".$db_tables["template_values"]." VALUES('$template_id','{$tpl_val_list[$i]}')") or query_die(__FILE__,__LINE__,mysql_error());
	}
	//Clear templates cache
	clear_manage_templates_cache();
	header("Location: manage_templates.php?$SLINE"); exit;
}

//Try to create php file for this template
function try_create_php_diskfile(&$template_type,&$php_file,$template_id,$tpl_filename)
{
 global $frontend_template_dir,$frontend_phpscripts_dir;
	if ($template_type == "1") {
		$php_file_ext = substr($php_file, 1+strrpos($php_file,"."));
		$php_file_filename = substr($php_file, 0, strrpos($php_file,"."));
		$phpdiskname = $frontend_phpscripts_dir.$php_file;
		$i = 0;
		while (file_exists($phpdiskname)) {
			$php_file_filename .= $i;
			$phpdiskname = $frontend_phpscripts_dir.$php_file_filename.'.php';
			$i++;
		}
		//Open source
	  $f = fopen($frontend_template_dir.'phpscripts_frontend_content.tpl', "r");
		$content = fread($f, filesize($frontend_template_dir.'phpscripts_frontend_content.tpl'));
		fclose($f);
		$content = str_replace("{*template_id*}", $template_id, $content);
		$content = str_replace("{*template_name*}", $tpl_filename, $content);
		//Write php file
	  $f = fopen($phpdiskname, "w");
		fwrite($f, $content);
		fclose($f);
		@chmod($phpdiskname,0777);
		$php_file = $php_file_filename.'.php';
	}
	else $php_file = "";
}

function insert_template_info($title,$description,$caution_level,$show_type,$template_type,$php_file,$templatebody)
{
 global $db_tables,$SLINE,$frontend_template_dir,$tpl_val_list;
	//Create disk file (template)
	$patterns = array ("/\W/");
	$replace = array ("_");
	$filename = preg_replace($patterns, $replace, $title);
	$diskname = $frontend_template_dir.$filename.'.tpl';
	$i = 0;
	while (file_exists($diskname)) {
		$filename .= $i;
		$diskname = $frontend_template_dir.$filename.'.tpl';
		$i++;
	}
	$filename = $filename.'.tpl';
	//Insert DB - templates
	mysql_query("INSERT INTO ".$db_tables["templates"]." VALUES(NULL,'$title','$filename','$description','$caution_level',".
		"'$show_type','0','$template_type','$php_file')") or query_die(__FILE__,__LINE__,mysql_error());
	$template_id = mysql_insert_id();
	//Create disk file (php)
	try_create_php_diskfile($template_type,$php_file,$template_id,$filename);
	//Update template content
  $f = fopen($diskname, "w");
	fwrite($f, "{*[start_template_header_part]*}\n{$templatebody["header"]}\n{*[end_template_header_part]*}\n{*[start_template_content_part]*}\n{$templatebody["content"]}\n{*[end_template_content_part]*}\n{*[start_template_footer_part]*}\n{$templatebody["footer"]}\n{*[end_template_footer_part]*}");
	fclose($f);
	@chmod($diskname,0777);
	//Get templte values
	$tpl_val_list = array();
	create_valirables_list($templatebody["header"]);
	create_valirables_list($templatebody["content"]);
	create_valirables_list($templatebody["footer"]);
	//Remove templte values
	mysql_query("DELETE FROM ".$db_tables["template_values"]." WHERE template_id='$template_id'") or query_die(__FILE__,__LINE__,mysql_error());
	//Insert new templte values
	for ($i=0; $i<count($tpl_val_list); $i++)
	{
		mysql_query("INSERT INTO ".$db_tables["template_values"]." VALUES('$template_id','{$tpl_val_list[$i]}')") or query_die(__FILE__,__LINE__,mysql_error());
	}
	//Clear templates cache
	clear_manage_templates_cache();
	header("Location: manage_templates.php?$SLINE"); exit;
}

function clear_manage_templates_cache()
{
	//Send event
	$event_array = array("event"=>"chtemplate", "source"=>"smarty_frontend", "table"=>"", "ad_id"=>"");
	event_handler($event_array);
}

function parse_input_content(&$templatebody)
{
	$patterns = array ("~{\s*?include\s*?file\s*?=\s*?(&quot;)+?(.+?)(&quot;)+?\s*?}~si");
	$replace = array ("{include file=\"\\2\"}");
	return preg_replace($patterns, $replace, $templatebody);
}

function check_header_and_footer(&$templatebody,&$my_error)
{
 global $Error_messages;
	if ($templatebody["header"] == "") $my_error .= $Error_messages["no_templatebody_header"];
	if ($templatebody["footer"] == "") $my_error .= $Error_messages["no_templatebody_footer"];
}

function try_save($template_id,$action)
{
 global $smarty,$db_tables,$Error_messages,$text_info,$caution_level_array,$show_type_array,$template_type_array;
	$my_error = "";
	$title					= html_chars(get_post_value("title",""));
	$description		= html_chars(get_post_value("description",""));
	$caution_level	= html_chars(get_post_value("caution_level",""));
	$show_type			= html_chars(get_post_value("show_type",""));
	$template_type	= html_chars(get_post_value("template_type",""));
	$php_file				= html_chars(get_post_value("php_file",""));

	$templatebody["header"]		= stripslashes(get_post_value("templatebody_header",""));
	$templatebody["content"]	= stripslashes(get_post_value("templatebody_body",""));
	$templatebody["footer"]		= stripslashes(get_post_value("templatebody_footer",""));

	$templatebody["header"] = parse_input_content($templatebody["header"]);
	$templatebody["content"] = parse_input_content($templatebody["content"]);
	$templatebody["footer"] = parse_input_content($templatebody["footer"]);

	//Check values on emptiness
	$vallist = array($title,$description,$caution_level,$show_type,$templatebody["content"]);
	$errlist = array($Error_messages["no_title"],$Error_messages["no_description"],$Error_messages["no_caution_level"],
				$Error_messages["no_show_type"],$Error_messages["no_templatebody_body"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)
	//Check array values
	$vallist = array($caution_level,$show_type);
	$errlist = array($Error_messages["invalid_caution_level"],$Error_messages["invalid_show_type"]);
	$check_array = array($caution_level_array,$show_type_array);
	is_not_array($vallist,$errlist,$check_array,$my_error); //Check values on a correctness (function)

	if ($action == "save") { 	//Get template info
		$qr_res = mysql_query("SELECT * FROM ".$db_tables["templates"]." WHERE template_id='$template_id'") or query_die(__FILE__,__LINE__,mysql_error());
		$myrow = mysql_fetch_array($qr_res);
		$issystem = $myrow["issystem"];
		$diskname = $myrow["diskname"];
		$db_template_type = $myrow["template_type"];
	}
	else {
		$issystem = 0;
		$diskname = "";
		$db_template_type = "";
	}

	//Check additional values for add template action
	if (!$issystem) { //Not system template
		if ($template_type == "") $my_error .= $Error_messages["no_ttype"];
		elseif (!isset($template_type_array[$template_type])) $my_error .= $Error_messages["invalid_ttype"];
		elseif ($template_type == "1") {
			if ($php_file == "") $my_error .= $Error_messages["no_php_file"];
			else {
				$pos = strpos($php_file, $_SESSION["globsettings"]["site_url"]);
				if ($pos !== false) {
					$php_file = substr($php_file,strlen($_SESSION["globsettings"]["site_url"]));
					$pos = strpos($php_file, "/");
					if ($pos !== false) {
						$my_error .= $Error_messages["invalid_php_file"];
					}
					$pos = strpos($php_file, "\\");
					if ($pos !== false) {
						$my_error .= $Error_messages["invalid_php_file"];
					}
					if (strlen($php_file) < 3) $my_error .= $Error_messages["invalid_php_file"];
				}
				else $my_error .= $Error_messages["invalid_php_file"];
			}
			check_header_and_footer($templatebody,$my_error);
		}
		else $php_file = "";
	}
	else { //System template
		if ($db_template_type == "1") check_header_and_footer($templatebody,$my_error);
	}

	//If no errors - save
	if ($my_error == "") {
		$title					= data_addslashes($title);
		$description 		= data_addslashes($description);
		$caution_level	= data_addslashes($caution_level);
		$show_type			= data_addslashes($show_type);
		$template_type	= data_addslashes($template_type);
		$php_file				= data_addslashes($php_file);
		if ($action == "save") //Update data
			update_template_info($template_id,$title,$description,$caution_level,$show_type,$template_type,$php_file,$templatebody,$issystem);
		else  //Insert data
			insert_template_info($title,$description,$caution_level,$show_type,$template_type,$php_file,$templatebody);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		if ($issystem) {
			$template_type = $myrow["template_type"];
			$php_file = $myrow["php_file"];
		}
		create_values($template_id,$title,$diskname,$description,$caution_level,$show_type,$issystem,$template_type,
			$php_file,$templatebody);
		if ($action == "save") create_page_buttons("save",$text_info["btn_save"]);
		else create_page_buttons("add",$text_info["btn_add"]);
	}
}
?>