<?
require_once "registration_func_adv_pub.php";

function create_values($email,$company,$name,$phone,$fax,$site,$address1,$address2,$country_id,$city,$state,$zipcode,$promotioncode)
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
	//Billing info
	array("isheadline"=>true, "hlclass"=>"form_hlclass","hlmessage"=>$text_info["p_billing_info"], "after_html"=>""),
	array("flabel"=>show_cell_caption("company",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"company", "ereadonly"=>"", "evalue"=>$company, "emaxlength"=>"150",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("name",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"name", "ereadonly"=>"", "evalue"=>$name, "emaxlength"=>"250",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("phone",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"phone", "ereadonly"=>"", "evalue"=>$phone, "emaxlength"=>"50",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("fax"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"fax", "ereadonly"=>"", "evalue"=>$fax, "emaxlength"=>"50",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("site"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"site", "ereadonly"=>"", "evalue"=>$site, "emaxlength"=>"200",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("address1"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"address1", "ereadonly"=>"", "evalue"=>$address1, "emaxlength"=>"200",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("address2"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"address2", "ereadonly"=>"", "evalue"=>$address2, "emaxlength"=>"200",
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
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	//Additional user info
	array("isheadline"=>true, "hlclass"=>"form_hlclass","hlmessage"=>$text_info["p_additional_user_info"], "after_html"=>""),
	array("flabel"=>show_cell_caption("promotioncode"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"promotioncode", "ereadonly"=>"", "evalue"=>$promotioncode, "emaxlength"=>"50",
				"estyle"=>"width:300px;", "isheadline"=>false, "edisabled"=>"")
	);
	$smarty->assign("FormElements",$FormElements);
	$smarty->assign("reginfo",$text_info["i_adv_signup"]);
}

function try_register()
{
 global $Error_messages, $db_tables;
	$my_error = "";
	$pass				= html_chars(get_post_value("pass",""));
	$repass			= html_chars(get_post_value("repass",""));
	$company		= html_chars(get_post_value("company",""));
	$name				= html_chars(get_post_value("name",""));
	$phone			= html_chars(get_post_value("phone",""));
	$fax				= html_chars(get_post_value("fax",""));
	$site				= html_chars(get_post_value("site",""));
	$address1		= html_chars(get_post_value("address1",""));
	$address2		= html_chars(get_post_value("address2",""));
	$country		= html_chars(get_post_value("country",""));
	$city				= html_chars(get_post_value("city",""));
	$state			= html_chars(get_post_value("state",""));
	$zipcode		= html_chars(get_post_value("zipcode",""));
	$promotioncode	= html_chars(get_post_value("promotioncode",""));
	//Check values on emptiness
	$vallist = array($pass,$repass,$company,$name,$phone);
	$errlist = array($Error_messages["no_pass"],$Error_messages["no_repass"],$Error_messages["no_company"],
			$Error_messages["no_name"],$Error_messages["no_phone"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)

	if (!check_country_id($country)) $my_error .= $Error_messages["invalid_country_id"];

	is_url($site,$Error_messages["invalid_site"],$my_error);
	if (($pass != "") && ($repass != "") && ($pass != $repass)) $my_error .= $Error_messages["not_match_pass"];

	if (find_adv_email_in_db($_SESSION["sess_confirm_email"])) $my_error .= $Error_messages["find_email_in_db"];

	//If no errors - save data
	if ($my_error == "") {
		$pass			= data_addslashes($pass);
		$repass		= data_addslashes($repass);
		$company	= data_addslashes($company);
		$name			= data_addslashes($name);
		$phone		= data_addslashes($phone);
		$fax			= data_addslashes($fax);
		$site			= data_addslashes($site);
		$address1 = data_addslashes($address1);
		$address2 = data_addslashes($address2);
		$country	= data_addslashes($country);
		$city			= data_addslashes($city);
		$state		= data_addslashes($state);
		$zipcode	= data_addslashes($zipcode);
		//add data
		add_data($_SESSION["sess_confirm_email"],$pass,$repass,$company,$name,$phone,$fax,$site,$address1,$address2,$country,$city,
			$state,$zipcode,$promotioncode);
	}
	else {//else - try again
		smarty_create_message("error","abort.gif",$my_error);
	}
	create_values($_SESSION["sess_confirm_email"],$company,$name,$phone,$fax,$site,$address1,$address2,$country,$city,$state,
		$zipcode,$promotioncode);
}

function singup_send_email($name,$username,$pass,$uid,$email)
{
 global $parse_values;
	//Parse values
	$admin_email = get_admin_email_free();
	$parse_values["{*site_title*}"]	= $_SESSION["globsettings"]["site_title"];
	$parse_values["{*name*}"]				= $name;
	$parse_values["{*username*}"]		= $username;
	$parse_values["{*password*}"]		= $pass;
	$parse_values["{*webmaster_email_txt*}"]	= $admin_email;
	$parse_values["{*login_url_txt*}"]				= $_SESSION["globsettings"]["site_url"].'advertisers/';
	$parse_values["{*site_url_txt*}"]					= $_SESSION["globsettings"]["site_url"];
	$parse_values["{*webmaster_email_html*}"]	= "<a href=\"mailto:".$parse_values["{*webmaster_email_txt*}"]."\">".$parse_values["{*webmaster_email_txt*}"]."</a>";
	$parse_values["{*login_url_html*}"]				= "<a href=\"".$parse_values["{*login_url_txt*}"]."\">".$parse_values["{*login_url_txt*}"]."</a>";
	$parse_values["{*site_url_html*}"]				= "<a href=\"".$parse_values["{*site_url_txt*}"]."\">".$parse_values["{*site_url_txt*}"]."</a>";
	//Sign Up welcome
	$subj	= get_mailsubject("sign_up_welcome_adv");
	$htmlmessage = get_email_file("sign_up_welcome_adv","html");
	$textmessage = get_email_file("sign_up_welcome_adv","txt");
	$attach_files= get_mail_attach("sign_up_welcome_adv");
	$subj = str_replace("{*site_title*}",$parse_values["{*site_title*}"],$subj);
	create_and_send_email($email,$admin_email,$subj,$htmlmessage,$textmessage,$attach_files);
}

function add_data($email,$pass,$company,$name,$phone,$fax,$site,$address1,$address2,$country_id,$city,$state,
		$zipcode,$promotioncode)
{
 global $db_tables, $SLINE;
	//Start balaance
	$amount = $_SESSION["globsettings"]["adv_start_balance"];
	//Member approved:
	$m_a = (int)!$_SESSION["globsettings"]["member_approved"];
	//Insert new member data
	if ($country_id == '') $country_id = 1;
	mysql_query("INSERT INTO ".$db_tables["users_advertiser"]." VALUES(NULL,'$email','$pass','$company','$name',".
		"'$phone','$fax','$site','$address1','$address2','$country_id','$city','$state','$zipcode','$promotioncode',".
		"'$amount',NOW(),NULL,$m_a,1,0)") or query_die(__FILE__,__LINE__,mysql_error());
	$uid = mysql_insert_id();
	//Send event
	$event_array = array("event"=>"insert", "source"=>"advertisers", "table"=>"users_advertiser", "ad_id"=>0);
	event_handler($event_array);
	//Insert member personal configuration
	mysql_query("INSERT INTO ".$db_tables["users_advertiser_settings"]." VALUES('$uid','1')")	or query_die(__FILE__,__LINE__,mysql_error());
	//Send e-mail
	singup_send_email($name,$email,$pass,$uid,$email);
	header("Location: adv_registration_success.php?$SLINE"); exit;
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
	if (find_adv_email_in_db($email)) $my_error .= $Error_messages["find_email_in_db"];

	//If no errors - save data
	if ($my_error == "") {
		$email		= data_addslashes($email);
		$emailkey = uniqid("");
		//Insert new confirm email
		mysql_query("INSERT INTO ".$db_tables["users_confirm_email"]." VALUES(NULL,'$emailkey','$email')") or query_die(__FILE__,__LINE__,mysql_error());
		//Send e-mail
		signup_send_confirm_email($email,$emailkey,"adv");
		header("Location: adv_registration_success.php?$SLINE"); exit;
	}
	//else - try again
	smarty_create_message("error","abort.gif",$my_error);
	create_confirm_values($email);
}
?>