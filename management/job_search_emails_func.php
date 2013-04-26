<?
function create_values($email_id,$email)
{
 global $smarty;
	$FormElements = array(
	array("flabel"=>show_cell_caption("id"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"email_id", "ereadonly"=>"readonly", "evalue"=>$email_id, "emaxlength"=>"5",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("email"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"email", "ereadonly"=>"", "evalue"=>$email, "emaxlength"=>"250",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	);
	$smarty->assign("FormElements",$FormElements);
}

function add_email_info($email)
{
 global $db_tables, $SLINE;
	mysql_query("INSERT INTO ".$db_tables["sites_feed_alert_emials"]." VALUES(NULL,'$email')") or query_die(__FILE__,__LINE__,mysql_error());
	header("Location: job_search_emails.php?$SLINE"); exit;
}

function update_email_info($email_id,$email)
{
 global $db_tables, $SLINE;
	mysql_query("UPDATE ".$db_tables["sites_feed_alert_emials"]." SET email='$email' WHERE email_id='$email_id'") or query_die(__FILE__,__LINE__,mysql_error());
	header("Location: job_search_emails.php?$SLINE"); exit;
}

function try_add()
{
 global $smarty,$Error_messages,$text_info;
	$my_error = "";
	$email = html_chars(get_post_value("email",""));

	if (!check_mail($email)) $my_error .= $Error_messages["invalid_email"];

	//If no errors - save
	if ($my_error == "") {
		$email = data_addslashes($email);
		add_email_info($email);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		create_values("(auto)",$email);
		create_page_buttons("add",$text_info["btn_save"]);
	}
}

function try_save($email_id)
{
 global $smarty,$Error_messages,$text_info;
	$my_error = "";
	$email = html_chars(get_post_value("email",""));

	if (!check_mail($email)) $my_error .= $Error_messages["invalid_email"];

	//If no errors - save
	if ($my_error == "") {
		$email = data_addslashes($email);
		update_email_info($email_id,$email);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		create_values($email_id,$email);
		create_page_buttons("save",$text_info["btn_save"]);
	}
}
?>