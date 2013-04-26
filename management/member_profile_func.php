<?
function create_values($uid_mem,$email,$first_name,$last_name,$site,$country_id,$city,$state,$zipcode)
{
 global $smarty,$text_info,$usersettings,$SLINE;
	$country_selectbox	= get_country_selectbox_data($country_id);
	$FormElements = array(
	//Login info
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_login_info"], "after_html"=>""),
	array("flabel"=>show_cell_caption("email",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"email", "ereadonly"=>"readonly", "evalue"=>$email, "emaxlength"=>"100",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("newpass"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"newpass", "ereadonly"=>"", "evalue"=>"", "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("renewpass"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"renewpass", "ereadonly"=>"", "evalue"=>"", "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	//Personal info
	array("isheadline"=>true, "hlclass"=>"form_hlclass","hlmessage"=>$text_info["p_personal_info"], "after_html"=>""),
	array("flabel"=>show_cell_caption("first_name",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"first_name", "ereadonly"=>"", "evalue"=>$first_name, "emaxlength"=>"150",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("last_name",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"last_name", "ereadonly"=>"", "evalue"=>$last_name, "emaxlength"=>"150",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	//Additional user info
	array("isheadline"=>true, "hlclass"=>"form_hlclass","hlmessage"=>$text_info["p_additional_user_info"], "after_html"=>""),
	array("flabel"=>show_cell_caption("site"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"site", "ereadonly"=>"", "evalue"=>$site, "emaxlength"=>"200",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("country"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"country", "edisabled"=>"", "evalue"=>$country_selectbox["val"],
				"eselected"=>$country_selectbox["sel"], "ecaption"=>$country_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("city"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"city", "ereadonly"=>"", "evalue"=>$city, "emaxlength"=>"80",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("state"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"state", "ereadonly"=>"", "evalue"=>$state, "emaxlength"=>"50",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("zipcode"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"zipcode", "ereadonly"=>"", "evalue"=>$zipcode, "emaxlength"=>"50",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>"")
	);
	$smarty->assign("FormElements",$FormElements);
}

function update_user_info($uid_mem,$newpass,$first_name,$last_name,$site,$country_id,$city,$state,$zipcode)
{
 global $db_tables, $SLINE;
	$pass_val = ($newpass != "") ? "pass='$newpass'," : "";
	mysql_query("UPDATE ".$db_tables["users_member"]." SET $pass_val first_name='$first_name',last_name='$last_name',".
				"site='$site',country_id='$country_id',city='$city',state='$state',zipcode='$zipcode'".
				" WHERE uid_mem='$uid_mem'") or query_die(__FILE__,__LINE__,mysql_error());
	header("Location: member_profile.php?$SLINE"); exit;
}

function try_save($uid_mem)
{
 global $smarty,$Error_messages,$text_info,$db_tables;
	$my_error = "";
	$newpass		= html_chars(get_post_value("newpass",""));
	$renewpass	= html_chars(get_post_value("renewpass",""));
	$first_name	= html_chars(get_post_value("first_name",""));
	$last_name	= html_chars(get_post_value("last_name",""));
	$site				= html_chars(get_post_value("site",""));
	$country		= html_chars(get_post_value("country",""));
	$city				= html_chars(get_post_value("city",""));
	$state			= html_chars(get_post_value("state",""));
	$zipcode		= html_chars(get_post_value("zipcode",""));

	//Check values on emptiness
	$vallist = array($first_name,$last_name);
	$errlist = array($Error_messages["no_first_name"],$Error_messages["no_last_name"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)
	//Check password
	if (($newpass != "") || ($renewpass != "")) {
		if ($newpass != $renewpass) $my_error .= $Error_messages["not_match_pass2"];
	}
	//Country and site
	if (!check_country_id($country)) $my_error .= $Error_messages["invalid_country_id"];
	if ($site != "") {
		$site_ = $site; $site = check_url($site); 
		if ($site === false) {
			$my_error .= $Error_messages["invalid_site"]; $site = $site_;
		}
	}

	//If no errors - save
	if ($my_error == "") {
		$uid_mem	= data_addslashes($uid_mem);
		$newpass	= data_addslashes($newpass);
		$first_name	= data_addslashes($first_name);
		$last_name	= data_addslashes($last_name);
		$site			= data_addslashes($site);
		$country	= data_addslashes($country);
		$city			= data_addslashes($city);
		$state		= data_addslashes($state);
		$zipcode	= data_addslashes($zipcode);
		//Update data
		update_user_info($uid_mem,$newpass,$first_name,$last_name,$site,$country,$city,$state,$zipcode);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		$qr_res = mysql_query("SELECT email FROM ".$db_tables["users_member"]." WHERE uid_mem='$uid_mem'") or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data (update error).");
		$myrow = mysql_fetch_array($qr_res);
		create_values($uid_mem,$myrow["email"],$first_name,$last_name,$site,$country_id,$city,$state,$zipcode);
		create_page_buttons("save",$text_info["btn_save"]);
	}
}
?>