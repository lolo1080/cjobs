<?
require_once "registration_func_adv_pub.php";

function create_values($email,$first_name,$last_name,$site,$country_id,$city,$state,$zipcode)
{
 global $smarty,$text_info,$usersettings;
	$country_selectbox	= get_country_selectbox_data($country_id);

	$FormElements = array(
	//Login info
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_login_info"], "after_html"=>""),
	array("flabel"=>show_cell_caption("email",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"email", "ereadonly"=>"readonly", "evalue"=>$email, "emaxlength"=>"100",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("pass",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"password",
				"ename"=>"pass", "ereadonly"=>"", "evalue"=>"", "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("repass",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"password",
				"ename"=>"repass", "ereadonly"=>"", "evalue"=>"", "emaxlength"=>"100",
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
	$smarty->assign("reginfo",$text_info["i_mem_signup"]);
}

function try_register()
{
 global $Error_messages, $db_tables;
	$my_error = "";
	$pass				= html_chars(get_post_value("pass",""));
	$repass			= html_chars(get_post_value("repass",""));
	$first_name	= html_chars(get_post_value("first_name",""));
	$last_name	= html_chars(get_post_value("last_name",""));
	$site				= html_chars(get_post_value("site",""));
	$country		= html_chars(get_post_value("country",""));
	$city				= html_chars(get_post_value("city",""));
	$state			= html_chars(get_post_value("state",""));
	$zipcode		= html_chars(get_post_value("zipcode",""));
	//Check values on emptiness
	$vallist = array($pass,$repass,$first_name,$last_name);
	$errlist = array($Error_messages["no_pass"],$Error_messages["no_repass"],$Error_messages["no_first_name"],
			$Error_messages["no_last_name"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)

	if (!check_country_id($country)) $my_error .= $Error_messages["invalid_country_id"];

	is_url($site,$Error_messages["invalid_site"],$my_error);
	if (($pass != "") && ($repass != "") && ($pass != $repass)) $my_error .= $Error_messages["not_match_pass"];

	if (find_mem_email_in_db($_SESSION["sess_confirm_email"])) $my_error .= $Error_messages["find_email_in_db"];

	//If no errors - save data
	if ($my_error == "") {
		$pass			= data_addslashes($pass);
		$repass		= data_addslashes($repass);
		$first_name	= data_addslashes($first_name);
		$last_name	= data_addslashes($last_name);
		$site			= data_addslashes($site);
		$country	= data_addslashes($country);
		$city			= data_addslashes($city);
		$state		= data_addslashes($state);
		$zipcode	= data_addslashes($zipcode);
		//add data
		add_data($_SESSION["sess_confirm_email"],$pass,$first_name,$last_name,$site,$country,$city,$state,$zipcode);
	}
	else {//else - try again
		smarty_create_message("error","abort.gif",$my_error);
	}
	create_values($_SESSION["sess_confirm_email"],$first_name,$last_name,$site,$country,$city,$state,$zipcode);
}

function singup_send_email($name,$username,$pass,$uid,$email)
{
 global $parse_values;
	//Parse values
	$admin_email = get_admin_email_free();
	$parse_values["{*site_title*}"]	= $_SESSION["globsettings"]["site_title"];
	$parse_values["{*name*}"]				= $name;
	$parse_values["{*username*}"]		= $email;
	$parse_values["{*password*}"]		= $pass;
	$parse_values["{*webmaster_email_txt*}"]	= $admin_email;
	$parse_values["{*login_url_txt*}"]				= $_SESSION["globsettings"]["site_url"].'myarea/';
	$parse_values["{*site_url_txt*}"]					= $_SESSION["globsettings"]["site_url"];
	$parse_values["{*webmaster_email_html*}"]	= "<a href=\"mailto:".$parse_values["{*webmaster_email_txt*}"]."\">".$parse_values["{*webmaster_email_txt*}"]."</a>";
	$parse_values["{*login_url_html*}"]				= "<a href=\"".$parse_values["{*login_url_txt*}"]."\">".$parse_values["{*login_url_txt*}"]."</a>";
	$parse_values["{*site_url_html*}"]				= "<a href=\"".$parse_values["{*site_url_txt*}"]."\">".$parse_values["{*site_url_txt*}"]."</a>";
	//Sign Up welcome
	$subj	= get_mailsubject("sign_up_welcome_mem");
	$htmlmessage = get_email_file("sign_up_welcome_mem","html");
	$textmessage = get_email_file("sign_up_welcome_mem","txt");
	$attach_files= get_mail_attach("sign_up_welcome_mem");
	$subj = str_replace("{*site_title*}",$parse_values["{*site_title*}"],$subj);
	create_and_send_email($email,$admin_email,$subj,$htmlmessage,$textmessage,$attach_files);
}

function add_data($email,$pass,$first_name,$last_name,$site,$country_id,$city,$state,$zipcode)
{
 global $db_tables, $SLINE;
	//Member approved:
	$m_a = (int)!$_SESSION["globsettings"]["member_approved"];
	//Insert new member data
	mysql_query("INSERT INTO ".$db_tables["users_member"]." VALUES(NULL,'$email','$pass','$first_name','$last_name',".
		"'$site','$country_id','$city','$state','$zipcode',NOW(),$m_a)") or query_die(__FILE__,__LINE__,mysql_error());
	$uid = mysql_insert_id();
	//Send event
	$event_array = array("event"=>"insert", "source"=>"members", "table"=>"users_member", "ad_id"=>0);
	event_handler($event_array);
	//Insert member personal configuration
	mysql_query("INSERT INTO ".$db_tables["users_member_settings"]." VALUES('$uid','1')")	or query_die(__FILE__,__LINE__,mysql_error());
	//Send e-mail
	singup_send_email($first_name.' '.$last_name,$email,$pass,$uid,$email);
	header("Location: mem_registration_success.php?$SLINE"); exit;
}

function try_confirm_register()
{
 global $Error_messages,$db_tables,$SLINE;
	$my_error = "";
	$email			= html_chars(get_post_value("email",""));
	//Check values on emptiness
	$vallist = array($email);
	$errlist = array($Error_messages["no_email"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)
	//Multiple signup
	if (($email != "") && (!check_mail($email))) $my_error .= $Error_messages["invalid_email"];
	if (find_mem_email_in_db($email)) $my_error .= $Error_messages["find_email_in_db"];

	//If no errors - save data
	if ($my_error == "") {
		$email		= data_addslashes($email);
		$emailkey = uniqid("");
		//Insert new confirm email
		mysql_query("INSERT INTO ".$db_tables["users_confirm_email"]." VALUES(NULL,'$emailkey','$email')") or query_die(__FILE__,__LINE__,mysql_error());
		//Send e-mail
		signup_send_confirm_email($email,$emailkey,"mem");
		header("Location: mem_registration_success.php?$SLINE"); exit;
	}
	//else - try again
	smarty_create_message("error","abort.gif",$my_error);
	create_confirm_values($email);
}
?>