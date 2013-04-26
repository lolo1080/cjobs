<?
function create_values($cat_id,$cat_name,$cat_key)
{
 global $smarty;
	$FormElements = array(
	array("flabel"=>show_cell_caption("cat_id"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"cat_id", "ereadonly"=>"readonly", "evalue"=>$cat_id, "emaxlength"=>"5",
				"estyle"=>"width:40px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("cat_name"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"cat_name", "ereadonly"=>"", "evalue"=>$cat_name, "emaxlength"=>"150",
				"estyle"=>"width:255px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("cat_key"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"cat_key", "ereadonly"=>"", "evalue"=>$cat_key, "emaxlength"=>"150",
				"estyle"=>"width:255px", "isheadline"=>false, "edisabled"=>""),
	);
	$smarty->assign("FormElements",$FormElements);
}

function find_cat_name_in_db($cat_name,$cat_id)
{
 global $db_tables;
	$cat_name = data_addslashes($cat_name);
	$qr_res = mysql_query("SELECT cat_id FROM ".$db_tables["jobcategories"]." WHERE cat_name='$cat_name' and cat_id<>'$cat_id'") or query_die(__FILE__,__LINE__,mysql_error());
	return (mysql_num_rows($qr_res) > 0) ? true : false;
}

function find_cat_key_in_db($cat_key,$cat_id)
{
 global $db_tables;
	$cat_key = data_addslashes($cat_key);
	$qr_res = mysql_query("SELECT cat_id FROM ".$db_tables["jobcategories"]." WHERE cat_key='$cat_key' and cat_id<>'$cat_id'") or query_die(__FILE__,__LINE__,mysql_error());
	return (mysql_num_rows($qr_res) > 0) ? true : false;
}

function add_category_info($cat_name,$cat_key)
{
 global $db_tables, $SLINE;
	$cat_name = data_addslashes($cat_name);
	$cat_key = data_addslashes($cat_key);
	mysql_query("INSERT INTO ".$db_tables["jobcategories"]." VALUES(NULL,'$cat_name','$cat_key')") or query_die(__FILE__,__LINE__,mysql_error());
	//Send event
	$event_array = array("event"=>"insert", "source"=>"jobcategories", "table"=>"jobcategories", "ad_id"=>0);
	event_handler($event_array);
	header("Location: categories.php?$SLINE"); exit;
}

function update_category_info($cat_id,$cat_name,$cat_key)
{
 global $db_tables, $SLINE;
	mysql_query("UPDATE ".$db_tables["jobcategories"]." SET cat_name='$cat_name',cat_key='$cat_key' WHERE cat_id='$cat_id'") or query_die(__FILE__,__LINE__,mysql_error());
	//Send event
	$event_array = array("event"=>"update", "source"=>"jobcategories", "table"=>"jobcategories", "ad_id"=>0);
	event_handler($event_array);
	header("Location: categories.php?$SLINE"); exit;
}

function try_add()
{
 global $smarty,$Error_messages,$text_info;
	$my_error = "";
	$cat_name = html_chars(get_post_value("cat_name",""));
	$cat_key = html_chars(get_post_value("cat_key",""));

	//Check values on emptiness
	$vallist = array($cat_name,$cat_key);
	$errlist = array($Error_messages["no_cat_name"],$Error_messages["no_cat_key"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)

	if (preg_match ("~[^\d\w-_]~i", $cat_key)) $my_error .= $Error_messages["invalid_symbols_in_key"];

	if ($my_error == "") {
		if (find_cat_name_in_db($cat_name,0)) $my_error .= $Error_messages["find_in_db_cat_name"];
		if (find_cat_key_in_db($cat_key,0)) $my_error .= $Error_messages["find_in_db_cat_name"];
	}

	//If no errors - save
	if ($my_error == "") {
		$cat_name = data_addslashes($cat_name);
		$cat_key = data_addslashes($cat_key);
		add_category_info($cat_name,$cat_key);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		create_values("(auto)",$cat_name,$cat_key);
		create_page_buttons("add",$text_info["btn_save"]);
	}
}

function try_save($cat_id)
{
 global $smarty,$Error_messages,$text_info;
	$my_error = "";
	$cat_name = html_chars(get_post_value("cat_name",""));
	$cat_key = html_chars(get_post_value("cat_key",""));

	//Check values on emptiness
	$vallist = array($cat_name,$cat_key);
	$errlist = array($Error_messages["no_cat_name"],$Error_messages["no_cat_key"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)

	if (preg_match ("~[^\d\w-_]~i", $cat_key)) $my_error .= $Error_messages["invalid_symbols_in_key"];

	if (find_cat_name_in_db($cat_name,$cat_id)) $my_error .= $Error_messages["find_in_db_cat_name"];
	if (find_cat_key_in_db($cat_key,$cat_id)) $my_error .= $Error_messages["find_in_db_cat_key"];

	//If no errors - save
	if ($my_error == "") {
		$cat_name = data_addslashes($cat_name);
		$cat_key = data_addslashes($cat_key);
		update_category_info($cat_id,$cat_name,$cat_key);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		create_values($cat_id,$cat_name,$cat_key);
		create_page_buttons("save",$text_info["btn_save"]);
	}
}
?>