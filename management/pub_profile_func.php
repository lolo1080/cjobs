<?
function create_values($email,$pass,$company,$name,$phone,$fax,$site,$address1,$address2,$country_id,$city,$state,$zipcode,
	$promotioncode,$ssn,$regdate)
{
 global $smarty,$text_info,$usersettings;
	$date_format_str		= "(".$usersettings["dateformat_c_info"].")";
	$country_selectbox	= get_country_selectbox_data($country_id);

	$FormElements = array(
	//Login info
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_login_info"], "after_html"=>""),
	array("flabel"=>show_cell_caption("email",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"email", "ereadonly"=>"readonly", "evalue"=>$email, "emaxlength"=>"100",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("pass",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"pass", "ereadonly"=>"", "evalue"=>$pass, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	//Payee info
	array("isheadline"=>true, "hlclass"=>"form_hlclass","hlmessage"=>$text_info["p_payee_info"], "after_html"=>""),
	array("flabel"=>show_cell_caption("company"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"company", "ereadonly"=>"", "evalue"=>$company, "emaxlength"=>"150",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("name",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"name", "ereadonly"=>"", "evalue"=>$name, "emaxlength"=>"250",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("phone"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"phone", "ereadonly"=>"", "evalue"=>$phone, "emaxlength"=>"50",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("fax"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"fax", "ereadonly"=>"", "evalue"=>$fax, "emaxlength"=>"50",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("site",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
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
	array("flabel"=>show_cell_caption("ssn"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"ssn", "ereadonly"=>"", "evalue"=>$ssn, "emaxlength"=>"50",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	//Additional user info
	array("isheadline"=>true, "hlclass"=>"form_hlclass","hlmessage"=>$text_info["p_additional_user_info"], "after_html"=>""),
	array("flabel"=>show_cell_caption("promotioncode"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"promotioncode", "ereadonly"=>"readonly", "evalue"=>$promotioncode, "emaxlength"=>"50",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("regdate").'<br />'.$date_format_str, "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"regdate", "ereadonly"=>"readonly", "evalue"=>$regdate, "emaxlength"=>"8",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	);
	$smarty->assign("FormElements",$FormElements);
}

function update_data($pass,$company,$name,$phone,$fax,$site,$address1,$address2,$country,$city,$state,$zipcode,$ssn)
{
 global $db_tables;
	mysql_query("UPDATE ".$db_tables["users_publisher"]." SET pass='$pass',company='$company',name='$name',".
				"phone='$phone',fax='$fax',site='$site',address1='$address1',address2='$address2',".
				"country_id='$country',city='$city',state='$state',zipcode='$zipcode',ssn='$ssn'".
				" WHERE uid_pub='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
	//Send event
	$event_array = array("event"=>"update", "source"=>"publishers", "table"=>"users_publisher", "ad_id"=>0);
	event_handler($event_array);
}

function try_change()
{
 global $Error_messages, $db_tables;
	$my_error = "";
	$pass				= html_chars(get_post_value("pass",""));
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
	$ssn				= html_chars(get_post_value("ssn",""));
	//Check values on emptiness
	$vallist = array($pass,$name,$site);
	$errlist = array($Error_messages["no_pass"],$Error_messages["no_name"],$Error_messages["no_site"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)

	if (!check_country_id($country)) $my_error .= $Error_messages["invalid_country_id"];

	is_url($site,$Error_messages["invalid_site"],$my_error);

	//If no errors - save data
	if ($my_error == "") {
		$pass			= data_addslashes($pass);
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
		$ssn			= data_addslashes($ssn);
		//Update data
		update_data($pass,$company,$name,$phone,$fax,$site,$address1,$address2,$country,$city,$state,$zipcode,$ssn);
	}
	else {//else - try again
		smarty_create_message("error","abort.gif",$my_error);
	}
	$qr_res = mysql_query("SELECT *,".format_sql_date("regdate")." as regdate FROM ".$db_tables["users_publisher"]." WHERE uid_pub='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
	$myrow = mysql_fetch_array($qr_res);
	create_values($myrow["email"],$pass,$company,$name,$phone,$fax,$site,$address1,$address2,$country,$city,$state,$zipcode,
		$myrow["promotioncode"],$ssn,$myrow["regdate"]);
}
?>