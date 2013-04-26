<?
function create_lnk($script,$uid_mem,$SLINE,$img_text,$lnk_text)
{
 return '<a class="pagelink" href="'.$script.'?uid_mem='.$uid_mem.'&'.$SLINE.'" >'.get_img("arrow.gif",20,20,$img_text,get_js_action(10)).$lnk_text.'</a>';
}

function create_values($uid_mem,$email,$pass,$first_name,$last_name,$site,$country_id,$city,$state,$zipcode,$regdate,$isconfirmed)
{
 global $smarty,$calendar_button,$text_info,$usersettings,$confirmed_not_confirmed_array,$SLINE;
	$user_confirmation_status_selectbox	= get_selectbox_data($confirmed_not_confirmed_array,$isconfirmed);
	$date_format_str		= "(".$usersettings["dateformat_c_info"].")";
	$member_ja_lnk			= create_lnk("member_jobalert.php",$uid_mem,$SLINE,$text_info["c_jobs_alert"],$text_info["c_shiow_jobs_alert"]);
	$country_selectbox	= get_country_selectbox_data($country_id);
	$FormElements = array(
	//Login info
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_login_info"], "after_html"=>""),
	array("flabel"=>show_cell_caption("email",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"email", "ereadonly"=>"", "evalue"=>$email, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("pass",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"pass", "ereadonly"=>"", "evalue"=>$pass, "emaxlength"=>"100",
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
	array("flabel"=>show_cell_caption("regdate").'<br />'.$date_format_str, "before_html"=>"", "after_html"=>"&nbsp;".$calendar_button, "etype"=>"text",
				"ename"=>"regdate", "ereadonly"=>"", "evalue"=>$regdate, "emaxlength"=>"8",
				"estyle"=>"width:274px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("zipcode"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"zipcode", "ereadonly"=>"", "evalue"=>$zipcode, "emaxlength"=>"50",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("confirmed_status"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"isconfirmed", "edisabled"=>"", "evalue"=>$user_confirmation_status_selectbox["val"],
				"eselected"=>$user_confirmation_status_selectbox["sel"], "ecaption"=>$user_confirmation_status_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	//Member job alert
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_member_job_alert"], "after_html"=>""),
	array("flabel"=>show_cell_caption("job_alert_list"), "before_html"=>$member_ja_lnk, "after_html"=>"", "etype"=>"none",
				"ename"=>"", "ereadonly"=>"", "evalue"=>"", "emaxlength"=>"",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	);
	$smarty->assign("FormElements",$FormElements);
}

function update_user_info($uid_mem,$email,$pass,$first_name,$last_name,$site,$country_id,$city,$state,$zipcode,$regdate,$isconfirmed)
{
 global $db_tables, $SLINE;
	mysql_query("UPDATE ".$db_tables["users_member"]." SET email='$email',pass='$pass',first_name='$first_name',last_name='$last_name',".
				"site='$site',country_id='$country_id',city='$city',state='$state',zipcode='$zipcode',regdate='$regdate',".
				"isconfirmed='$isconfirmed'".
				" WHERE uid_mem='$uid_mem'") or query_die(__FILE__,__LINE__,mysql_error());
	if (check_user_subm_script()) ;
	else { header("Location: members.php?$SLINE"); exit; }
}

function try_save($uid_mem)
{
 global $smarty,$Error_messages,$text_info,$db_tables,$confirmed_not_confirmed_array;
	$my_error = "";
	$email			= html_chars(get_post_value("email",""));
	$pass				= html_chars(get_post_value("pass",""));
	$first_name	= html_chars(get_post_value("first_name",""));
	$last_name	= html_chars(get_post_value("last_name",""));
	$site				= html_chars(get_post_value("site",""));
	$country		= html_chars(get_post_value("country",""));
	$city				= html_chars(get_post_value("city",""));
	$state			= html_chars(get_post_value("state",""));
	$zipcode		= html_chars(get_post_value("zipcode",""));
	$regdate		= html_chars(get_post_value("regdate",""));
	$isconfirmed= html_chars(get_post_value("isconfirmed",""));

	//Check values on emptiness
	$vallist = array($email,$pass,$first_name,$last_name);
	$errlist = array($Error_messages["no_email"],$Error_messages["no_pass"],$Error_messages["no_first_name"],$Error_messages["no_last_name"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)
	//Check array values
	$vallist = array($isconfirmed);
	$errlist = array($Error_messages["invalid_isconfirmed"]);
	$check_array = array($confirmed_not_confirmed_array);
	is_not_array($vallist,$errlist,$check_array,$my_error); //Check values on a correctness (function)

	if (!check_country_id($country)) $my_error .= $Error_messages["invalid_country_id"];
	if ($site != "") {
		$site_ = $site; $site = check_url($site); 
		if ($site === false) {
			$my_error .= $Error_messages["invalid_site"]; $site = $site_;
		}
	}
 	if (($email != "") && !check_mail($email)) $my_error .= $Error_messages["invalid_email"];
	if (!check_date($regdate)) $my_error .= $Error_messages["invalid_regdate"];
	if (($my_error == "") && find_other_emails_in_db("uid_mem",$db_tables["users_member"],"and uid_mem<>'$uid_mem'",$email)) $my_error .= $Error_messages["find_email_in_db"];

	//If no errors - save
	if ($my_error == "") {
		$uid_mem	= data_addslashes($uid_mem);
		$email 		= data_addslashes($email);
		$pass			= data_addslashes($pass);
		$first_name	= data_addslashes($first_name);
		$last_name	= data_addslashes($last_name);
		$site			= data_addslashes($site);
		$country	= data_addslashes($country);
		$city			= data_addslashes($city);
		$state		= data_addslashes($state);
		$zipcode	= data_addslashes($zipcode);
		$regdate	= data_addslashes($regdate);
		$isconfirmed	= data_addslashes($isconfirmed);
		$sql_regdate = get_mysql_date($regdate,$my_error);
		//Update data
		update_user_info($uid_mem,$email,$pass,$first_name,$last_name,$site,$country,$city,$state,$zipcode,$regdate,$isconfirmed);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		$qr_res = mysql_query("SELECT isconfirmed,".format_sql_date("regdate")." as regdate FROM ".$db_tables["users_member"]." WHERE uid_mem='$uid_mem'") or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data (update error).");
		$myrow = mysql_fetch_array($qr_res);
		create_values($uid_mem,$email,$pass,$first_name,$last_name,$site,$country,$city,$state,$zipcode,$regdate,$myrow["isconfirmed"]);
		create_page_buttons("save",$text_info["btn_save"]);
	}
}
?>