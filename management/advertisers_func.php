<?
function create_lnk($script,$uid_adv,$SLINE,$img_text,$lnk_text)
{
 return '<a class="pagelink" href="'.$script.'?uid_adv='.$uid_adv.'&'.$SLINE.'" >'.get_img("arrow.gif",20,20,$img_text,get_js_action(10)).$lnk_text.'</a>';
}

function create_values($uid_adv,$email,$pass,$company,$name,$phone,$fax,$site,$address1,$address2,$country_id,$city,$state,$zipcode,
	$promotioncode,$balance,$regdate,$isconfirmed,$isenable)
{
 global $smarty,$calendar_button,$text_info,$usersettings,$yes_no_array,$active_disable_array,$confirmed_not_confirmed_array,$SLINE;
	$user_status_selectbox	= get_selectbox_data($active_disable_array,$isenable);
	$user_confirmation_status_selectbox	= get_selectbox_data($confirmed_not_confirmed_array,$isconfirmed);
	$date_format_str		= "(".$usersettings["dateformat_c_info"].")";
	$country_selectbox	= get_country_selectbox_data($country_id);
	$edit_balance_lnk		= create_lnk("advertisers_balance_work.php",$uid_adv,$SLINE,$text_info["c_edit_balance"],$text_info["c_edit_balance"]);
	$advertisements_lnk	= create_lnk("adv_advertisements.php",$uid_adv,$SLINE,$text_info["c_advertisements"],$text_info["c_show_c_advertisements"]);
	$fund_history_lnk			= create_lnk("adv_fund_history.php",$uid_adv,$SLINE,$text_info["c_fund_history"],$text_info["c_show_fund_history"]);
	$FormElements = array(
	//Login info
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_login_info"], "after_html"=>""),
	array("flabel"=>show_cell_caption("email",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"email", "ereadonly"=>"", "evalue"=>$email, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("pass",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"pass", "ereadonly"=>"", "evalue"=>$pass, "emaxlength"=>"100",
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
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("regdate").'<br />'.$date_format_str, "before_html"=>"", "after_html"=>"&nbsp;".$calendar_button, "etype"=>"text",
				"ename"=>"regdate", "ereadonly"=>"", "evalue"=>$regdate, "emaxlength"=>"8",
				"estyle"=>"width:274px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("acc_balance"), "before_html"=>"", "after_html"=>"&nbsp;".$edit_balance_lnk, "etype"=>"text",
				"ename"=>"balance", "ereadonly"=>"readonly", "evalue"=>$balance, "emaxlength"=>"15",
				"estyle"=>"width:200px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("enable_status"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"isenable", "edisabled"=>"", "evalue"=>$user_status_selectbox["val"],
				"eselected"=>$user_status_selectbox["sel"], "ecaption"=>$user_status_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("confirmed_status"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"isconfirmed", "edisabled"=>"", "evalue"=>$user_confirmation_status_selectbox["val"],
				"eselected"=>$user_confirmation_status_selectbox["sel"], "ecaption"=>$user_confirmation_status_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	//User statistic info
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_user_statistic_info"], "after_html"=>""),
	array("flabel"=>show_cell_caption("advertisements"), "before_html"=>$advertisements_lnk, "after_html"=>"", "etype"=>"none",
				"ename"=>"", "ereadonly"=>"", "evalue"=>"", "emaxlength"=>"",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	//User payment history
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_payment_history_h"], "after_html"=>""),
	array("flabel"=>show_cell_caption("fund_history"), "before_html"=>$fund_history_lnk, "after_html"=>"", "etype"=>"none",
				"ename"=>"", "ereadonly"=>"", "evalue"=>"", "emaxlength"=>"",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>"")
	);
	$smarty->assign("FormElements",$FormElements);
}

function update_user_info($uid_adv,$email,$pass,$company,$name,$phone,$fax,$site,$address1,$address2,$country,$city,$state,$zipcode,
	$promotioncode,$balance,$regdate,$isconfirmed,$isenable)
{
 global $db_tables, $SLINE;
	mysql_query("UPDATE ".$db_tables["users_advertiser"]." SET email='$email',pass='$pass',company='$company',name='$name',".
				"phone='$phone',fax='$fax',site='$site',address1='$address1',address2='$address2',country_id='$country',city='$city',".
				"state='$state',zipcode='$zipcode',promotioncode='$promotioncode',regdate='$regdate',".
				"isconfirmed='$isconfirmed',isenable='$isenable'".
				" WHERE uid_adv='$uid_adv'") or query_die(__FILE__,__LINE__,mysql_error());
	//Send event
	$event_array = array("event"=>"update", "source"=>"advertisers", "table"=>"users_advertiser", "ad_id"=>0);
	event_handler($event_array);
	if (check_user_subm_script()) ;
	else { header("Location: advertisers.php?$SLINE"); exit; }
}

function try_save($uid_adv)
{
 global $smarty,$Error_messages,$text_info,$db_tables,$yes_no_array,$active_disable_array,$confirmed_not_confirmed_array;
	$my_error = "";
	$email		= html_chars(get_post_value("email",""));
	$pass			= html_chars(get_post_value("pass",""));
	$company	= html_chars(get_post_value("company",""));
	$name			= html_chars(get_post_value("name",""));
	$phone		= html_chars(get_post_value("phone",""));
	$fax			= html_chars(get_post_value("fax",""));
	$site			= html_chars(get_post_value("site",""));
	$address1	= html_chars(get_post_value("address1",""));
	$address2	= html_chars(get_post_value("address2",""));
	$country	= html_chars(get_post_value("country",""));
	$city			= html_chars(get_post_value("city",""));
	$state		= html_chars(get_post_value("state",""));
	$zipcode	= html_chars(get_post_value("zipcode",""));
	$promotioncode= html_chars(get_post_value("promotioncode",""));
	$balance	= html_chars(get_post_value("balance","0.00"));
	$regdate	= html_chars(get_post_value("regdate",""));
	$isconfirmed= html_chars(get_post_value("isconfirmed",""));
	$isenable	= html_chars(get_post_value("isenable",""));

	//Check values on emptiness
	$vallist = array($email,$pass,$company,$name,$phone);
	$errlist = array($Error_messages["no_email"],$Error_messages["no_pass"],$Error_messages["no_company"],
				$Error_messages["no_name"],$Error_messages["no_phone"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)
	//Check array values
	$vallist = array($isconfirmed,$isenable);
	$errlist = array($Error_messages["invalid_isconfirmed"],$Error_messages["invalid_isenable"]);
	$check_array = array($confirmed_not_confirmed_array,$active_disable_array);
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
	if (!check_float($balance)) $my_error .= $Error_messages["invalid_acc_balance"];
	if (($my_error == "") && find_other_emails_in_db("uid_adv",$db_tables["users_advertiser"],"and uid_adv<>'$uid_adv'",$email)) $my_error .= $Error_messages["find_email_in_db"];

	//If no errors - save
	if ($my_error == "") {
		$uid_adv	= data_addslashes($uid_adv);
		$email 		= data_addslashes($email);
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
		$promotioncode= data_addslashes($promotioncode);
		$balance	= data_addslashes($balance);
		$regdate	= data_addslashes($regdate);
		$isconfirmed	= data_addslashes($isconfirmed);
		$isenable	= data_addslashes($isenable);
		$sql_regdate = get_mysql_date($regdate,$my_error);
		//Update data
		update_user_info($uid_adv,$email,$pass,$company,$name,$phone,$fax,$site,$address1,$address2,$country,$city,$state,$zipcode,
				$promotioncode,$balance,$sql_regdate,$isconfirmed,$isenable);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		$qr_res = mysql_query("SELECT isconfirmed,isenable,balance,".format_sql_date("regdate")." as regdate FROM ".$db_tables["users_advertiser"]." WHERE uid_adv='$uid_adv'") or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data (update error).");
		$myrow = mysql_fetch_array($qr_res);
		create_values($uid_adv,$email,$pass,$company,$name,$phone,$fax,$site,$address1,$address2,$country,$city,$state,$zipcode,
			$promotioncode,$myrow["balance"],$myrow["regdate"],$myrow["isconfirmed"],$myrow["isenable"]);
		create_page_buttons("save",$text_info["btn_save"]);
	}
}
?>