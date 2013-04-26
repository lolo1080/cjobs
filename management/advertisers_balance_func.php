<?
function create_values($uid_adv,$email,$company,$name,$balance)
{
 global $smarty,$calendar_button,$text_info,$SLINE;
	$FormElements = array(
	array("flabel"=>show_cell_caption("email"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"email", "ereadonly"=>"readonly", "evalue"=>$email, "emaxlength"=>"100",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("company"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"company", "ereadonly"=>"readonly", "evalue"=>$company, "emaxlength"=>"150",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("name"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"name", "ereadonly"=>"readonly", "evalue"=>$name, "emaxlength"=>"250",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("acc_balance",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"balance", "ereadonly"=>"", "evalue"=>$balance, "emaxlength"=>"15",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>"")
	);
	$smarty->assign("FormElements",$FormElements);
}

function update_user_info($uid_adv,$balance)
{
 global $db_tables, $SLINE;
	mysql_query("UPDATE ".$db_tables["users_advertiser"]." SET balance='$balance' WHERE uid_adv='$uid_adv'") or query_die(__FILE__,__LINE__,mysql_error());
	//Send event
	$event_array = array("event"=>"update", "source"=>"advertisers", "table"=>"users_advertiser", "ad_id"=>0);
	event_handler($event_array);
	header("Location: advertisers_work.php?action=edit&uid_adv={$uid_adv}&{$SLINE}"); exit;
}

function try_save($uid_adv)
{
 global $smarty,$Error_messages,$text_info,$db_tables;
	$my_error = "";
	$email		= html_chars(get_post_value("email",""));
	$company	= html_chars(get_post_value("company",""));
	$name			= html_chars(get_post_value("name",""));
	$balance	= html_chars(get_post_value("balance","0.00"));

	if (!check_float($balance)) $my_error .= $Error_messages["invalid_acc_balance"];

	//If no errors - save
	if ($my_error == "") {
		$balance	= data_addslashes($balance);
		//Update data
		update_user_info($uid_adv,$balance);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		create_values($uid_adv,$email,$company,$name,$balance);
		create_page_buttons("save",$text_info["btn_save"]);
	}
}
?>