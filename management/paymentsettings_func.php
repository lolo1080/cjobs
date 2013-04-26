<?
function create_values($credit_card_accept,$credit_card_login,$credit_card_minwithdraw,$credit_card_mindeposit,
	$paypal_accept,$paypal_email,$paypal_minwithdraw,$paypal_mindeposit,
	$egold_accept,$egold_id,$egold_passphrase,$egold_minwithdraw,$egold_mindeposit,
	$_2checkout_accept,$_2checkout_id,$_2checkout_minwithdraw,$_2checkout_mindeposit,$_2checkout_url,
	$_2checkout_test)
{
 global $smarty,$text_info,$yes_no_array;
	$credit_card_accept_selectbox	= get_selectbox_data($yes_no_array,$credit_card_accept);
	$paypal_accept_selectbox			= get_selectbox_data($yes_no_array,$paypal_accept);
	$egold_accept_selectbox				= get_selectbox_data($yes_no_array,$egold_accept);
	$_2checkout_accept_selectbox	= get_selectbox_data($yes_no_array,$_2checkout_accept);
	$_2checkout_test_selectbox		= get_selectbox_data($yes_no_array,$_2checkout_test);
	$FormElements = array(
	//Payment Settings
	//Credit Card
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_credit_card_settings"], "after_html"=>""),
	array("flabel"=>show_cell_caption("credit_card_accept"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"credit_card_accept", "edisabled"=>"", "evalue"=>$credit_card_accept_selectbox["val"],
				"eselected"=>$credit_card_accept_selectbox["sel"], "ecaption"=>$credit_card_accept_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false),
	array("flabel"=>show_cell_caption("credit_card_login"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"credit_card_login",	"ereadonly"=>"", "evalue"=>$credit_card_login, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("credit_card_minwithdraw"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"credit_card_minwithdraw", "ereadonly"=>"", "evalue"=>$credit_card_minwithdraw, "emaxlength"=>"12",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("credit_card_mindeposit"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"credit_card_mindeposit", "ereadonly"=>"", "evalue"=>$credit_card_mindeposit, "emaxlength"=>"12",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	//PayPal
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_paypal_settings"], "after_html"=>""),
	array("flabel"=>show_cell_caption("paypal_accept"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"paypal_accept", "edisabled"=>"", "evalue"=>$paypal_accept_selectbox["val"],
				"eselected"=>$paypal_accept_selectbox["sel"], "ecaption"=>$paypal_accept_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false),
	array("flabel"=>show_cell_caption("paypal_email"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"paypal_email",	"ereadonly"=>"", "evalue"=>$paypal_email, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("paypal_minwithdraw"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"paypal_minwithdraw", "ereadonly"=>"", "evalue"=>$paypal_minwithdraw, "emaxlength"=>"12",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("paypal_mindeposit"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"paypal_mindeposit", "ereadonly"=>"", "evalue"=>$paypal_mindeposit, "emaxlength"=>"12",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	//E-Gold
/*
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_egold_settings"], "after_html"=>""),
	array("flabel"=>show_cell_caption("egold_accept"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"egold_accept", "edisabled"=>"", "evalue"=>$egold_accept_selectbox["val"],
				"eselected"=>$egold_accept_selectbox["sel"], "ecaption"=>$egold_accept_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false),
	array("flabel"=>show_cell_caption("egold_id"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"egold_id",	"ereadonly"=>"", "evalue"=>$egold_id, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("egold_passphrase"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"egold_passphrase", "ereadonly"=>"", "evalue"=>$egold_passphrase, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("egold_minwithdraw"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"egold_minwithdraw", "ereadonly"=>"", "evalue"=>$egold_minwithdraw, "emaxlength"=>"12",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("egold_mindeposit"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"egold_mindeposit", "ereadonly"=>"", "evalue"=>$egold_mindeposit, "emaxlength"=>"12",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	//2checkout
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_2checkout_settings"], "after_html"=>""),
	array("flabel"=>show_cell_caption("2checkout_accept"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"2checkout_accept", "edisabled"=>"", "evalue"=>$_2checkout_accept_selectbox["val"],
				"eselected"=>$_2checkout_accept_selectbox["sel"], "ecaption"=>$_2checkout_accept_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false),
	array("flabel"=>show_cell_caption("2checkout_id"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"2checkout_id",	"ereadonly"=>"", "evalue"=>$_2checkout_id, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("2checkout_minwithdraw"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"2checkout_minwithdraw", "ereadonly"=>"", "evalue"=>$_2checkout_minwithdraw, "emaxlength"=>"12",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("2checkout_mindeposit"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"2checkout_mindeposit", "ereadonly"=>"", "evalue"=>$_2checkout_mindeposit, "emaxlength"=>"12",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("2checkout_url"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"2checkout_url", "ereadonly"=>"", "evalue"=>$_2checkout_url, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("2checkout_test"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"2checkout_test", "edisabled"=>"", "evalue"=>$_2checkout_test_selectbox["val"],
				"eselected"=>$_2checkout_test_selectbox["sel"], "ecaption"=>$_2checkout_test_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false)
*/
	);
	$smarty->assign("FormElements",$FormElements);
}

function update_data($credit_card_accept,$credit_card_login,$credit_card_minwithdraw,$credit_card_mindeposit,
		$paypal_accept,$paypal_email,$paypal_minwithdraw,$paypal_mindeposit,$egold_accept,
		$egold_id,$egold_passphrase,$egold_minwithdraw,$egold_mindeposit,$_2checkout_accept,$_2checkout_id,
		$_2checkout_minwithdraw,$_2checkout_mindeposit,$_2checkout_url,$_2checkout_test)
{
 global $db_tables;
	mysql_query("UPDATE ".$db_tables["paymentsettings"]." SET ".
				"credit_card_accept='$credit_card_accept',credit_card_login='$credit_card_login',".
				"credit_card_minwithdraw='$credit_card_minwithdraw',credit_card_mindeposit='$credit_card_mindeposit',".
				"paypal_accept='$paypal_accept',".
				"paypal_email='$paypal_email',paypal_minwithdraw='$paypal_minwithdraw',".
				"paypal_mindeposit='$paypal_mindeposit',egold_accept='$egold_accept',egold_id='$egold_id',".
				"egold_passphrase='$egold_passphrase',".
				"egold_minwithdraw='$egold_minwithdraw',egold_mindeposit='$egold_mindeposit',".
				"2checkout_accept='$_2checkout_accept',2checkout_id='$_2checkout_id',".
				"2checkout_minwithdraw='$_2checkout_minwithdraw',2checkout_mindeposit='$_2checkout_mindeposit',".
				"2checkout_url='$_2checkout_url',2checkout_test='$_2checkout_test'") or query_die(__FILE__,__LINE__,mysql_error());
	get_payment_settings();
}

function try_change()
{
 global $Error_messages,$yes_no_array;
	$my_error = "";
	$credit_card_accept			= html_chars(get_post_value("credit_card_accept",""));
	$credit_card_login			= html_chars(get_post_value("credit_card_login",""));
	$credit_card_minwithdraw= html_chars(get_post_value("credit_card_minwithdraw",""));
	$credit_card_mindeposit	= html_chars(get_post_value("credit_card_mindeposit",""));
	$paypal_accept					= html_chars(get_post_value("paypal_accept",""));
	$paypal_email						= html_chars(get_post_value("paypal_email",""));
	$paypal_minwithdraw			= html_chars(get_post_value("paypal_minwithdraw",""));
	$paypal_mindeposit			= html_chars(get_post_value("paypal_mindeposit",""));
	$egold_accept						= html_chars(get_post_value("egold_accept","0"));
	$egold_id								= html_chars(get_post_value("egold_id",""));
	$egold_passphrase				= html_chars(get_post_value("egold_passphrase",""));
	$egold_minwithdraw			= html_chars(get_post_value("egold_minwithdraw",""));
	$egold_mindeposit				= html_chars(get_post_value("egold_mindeposit",""));
	$_2checkout_accept			= html_chars(get_post_value("2checkout_accept","0"));
	$_2checkout_id					= html_chars(get_post_value("2checkout_id",""));
	$_2checkout_minwithdraw	= html_chars(get_post_value("2checkout_minwithdraw",""));
	$_2checkout_mindeposit	= html_chars(get_post_value("2checkout_mindeposit",""));
	$_2checkout_url					= html_chars(get_post_value("2checkout_url",""));
	$_2checkout_test				= html_chars(get_post_value("2checkout_test","0"));
	//Check values on emptiness
	$vallist = array($credit_card_accept,$paypal_accept,$egold_accept,$_2checkout_accept,$_2checkout_test);
	$errlist = array($Error_messages["no_credit_card_accept"],$Error_messages["no_paypal_accept"],
				$Error_messages["no_egold_accept"],$Error_messages["no_2checkout_accept"],
				$Error_messages["no_2checkout_test"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)
	//Check values on a correctness
	$vallist = array($credit_card_accept,$paypal_accept,$egold_accept,$_2checkout_accept,$_2checkout_test);
	$errlist = array($Error_messages["invalid_credit_card_accept"],$Error_messages["invalid_paypal_accept"],
				$Error_messages["invalid_egold_accept"],$Error_messages["invalid_2checkout_accept"],
				$Error_messages["invalid_2checkout_test"]);
	$check_array = array($yes_no_array,$yes_no_array,$yes_no_array,$yes_no_array,$yes_no_array);
	is_not_array($vallist,$errlist,$check_array,$my_error); //Check values on a correctness (function)
	if ($my_error == "") {
		if ($credit_card_accept) {
			if ($credit_card_login == "") $my_error .= $Error_messages["no_credit_card_login"];
			if ($credit_card_minwithdraw == "") $my_error .= $Error_messages["no_credit_card_minwithdraw"];
			elseif (!check_float($credit_card_minwithdraw)) $my_error .= $Error_messages["invalid_credit_card_minwithdraw"];
			if ($credit_card_mindeposit == "") $my_error .= $Error_messages["no_credit_card_mindeposit"];
			elseif (!check_float($credit_card_minwithdraw)) $my_error .= $Error_messages["invalid_credit_card_mindeposit"];
		}
		if ($paypal_accept) {
			if ($paypal_email == "") $my_error .= $Error_messages["no_paypal_email"];
			elseif (!check_mail($paypal_email)) $my_error .= $Error_messages["invalid_paypal_email"];
			if ($paypal_minwithdraw == "") $my_error .= $Error_messages["no_paypal_minwithdraw"];
			elseif (!check_float($paypal_minwithdraw)) $my_error .= $Error_messages["invalid_paypal_minwithdraw"];
			if ($paypal_mindeposit == "") $my_error .= $Error_messages["no_paypal_mindeposit"];
			elseif (!check_float($paypal_minwithdraw)) $my_error .= $Error_messages["invalid_paypal_mindeposit"];
		}
		if ($egold_accept) {
			if ($egold_id == "") $my_error .= $Error_messages["no_egold_id"];
			if ($egold_passphrase == "") $my_error .= $Error_messages["no_egold_passphrase"];
			if ($egold_minwithdraw == "") $my_error .= $Error_messages["no_egold_minwithdraw"];
			elseif (!check_float($egold_minwithdraw)) $my_error .= $Error_messages["invalid_egold_minwithdraw"];
			if ($egold_mindeposit == "") $my_error .= $Error_messages["no_egold_mindeposit"];
			elseif (!check_float($egold_minwithdraw)) $my_error .= $Error_messages["invalid_egold_mindeposit"];
		}
		if ($_2checkout_accept) {
			if ($_2checkout_id == "") $my_error .= $Error_messages["no_2checkout_id"];
			if ($_2checkout_minwithdraw == "") $my_error .= $Error_messages["no_2checkout_minwithdraw"];
			elseif (!check_float($_2checkout_minwithdraw)) $my_error .= $Error_messages["invalid_2checkout_minwithdraw"];
			if ($_2checkout_mindeposit == "") $my_error .= $Error_messages["no_2checkout_mindeposit"];
			elseif (!check_float($_2checkout_minwithdraw)) $my_error .= $Error_messages["invalid_2checkout_mindeposit"];
			if ($_2checkout_url == "") $my_error .= $Error_messages["no_2checkout_url"];
			else is_url($_2checkout_url,$Error_messages["invalid_2checkout_url"],$my_error);
		}
	}
	//If no errors - save data
	if ($my_error == "") {
		$credit_card_accept				= data_addslashes($credit_card_accept);
		$credit_card_login				= data_addslashes($credit_card_login);
		$credit_card_minwithdraw	= data_addslashes($credit_card_minwithdraw);
		$credit_card_mindeposit		= data_addslashes($credit_card_mindeposit);
		$paypal_accept				= data_addslashes($paypal_accept);
		$paypal_email					= data_addslashes($paypal_email);
		$paypal_minwithdraw		= data_addslashes($paypal_minwithdraw);
		$paypal_mindeposit		= data_addslashes($paypal_mindeposit);
		$egold_accept					= data_addslashes($egold_accept);
		$egold_id							= data_addslashes($egold_id);
		$egold_passphrase			= data_addslashes($egold_passphrase);
		$egold_minwithdraw		= data_addslashes($egold_minwithdraw);
		$egold_mindeposit			= data_addslashes($egold_mindeposit);
		$_2checkout_accept		= data_addslashes($_2checkout_accept);
		$_2checkout_id				= data_addslashes($_2checkout_id);
		$_2checkout_minwithdraw= data_addslashes($_2checkout_minwithdraw);
		$_2checkout_mindeposit= data_addslashes($_2checkout_mindeposit);
		$_2checkout_url				= data_addslashes($_2checkout_url);
		$_2checkout_test			= data_addslashes($_2checkout_test);
		//Update data
		update_data($credit_card_accept,$credit_card_login,$credit_card_minwithdraw,$credit_card_mindeposit,
			$paypal_accept,$paypal_email,$paypal_minwithdraw,$paypal_mindeposit,$egold_accept,
			$egold_id,$egold_passphrase,$egold_minwithdraw,$egold_mindeposit,$_2checkout_accept,$_2checkout_id,
			$_2checkout_minwithdraw,$_2checkout_mindeposit,$_2checkout_url,$_2checkout_test);
	}
	else {//else - try again
		smarty_create_message("error","abort.gif",$my_error);
	}
	create_values($credit_card_accept,$credit_card_login,$credit_card_minwithdraw,$credit_card_mindeposit,
			$paypal_accept,$paypal_email,$paypal_minwithdraw,$paypal_mindeposit,$egold_accept,
			$egold_id,$egold_passphrase,$egold_minwithdraw,$egold_mindeposit,$_2checkout_accept,$_2checkout_id,
			$_2checkout_minwithdraw,$_2checkout_mindeposit,$_2checkout_url,$_2checkout_test);
}
?>