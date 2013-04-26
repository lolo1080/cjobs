<?
function create_payment_values($amount,$paytype_val,$paytype_sel,$paytype_capt)
{
 global $smarty, $common_payment_systems_info;
	$FormElements = array(
	array("flabel"=>show_cell_caption("pay_to"), "before_html"=>$_SESSION["globsettings"]["site_title"],	"after_html"=>"",	"etype"=>"",
				"ename"=>"",	"ereadonly"=>"",	"evalue"=>"",	"emaxlength"=>"",
				"estyle"=>"", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("payment_system"),"before_html"=>"", "after_html"=>"",	"etype"=>"select",
				"ename"=>"paytype",	"edisabled"=>"",	"evalue"=>$paytype_val,	"eselected"=>$paytype_sel, 
				"ecaption"=>$paytype_capt, "jscipt"=>"", "multiple"=>"",
				"estyle"=>"width:200px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("currency"), "before_html"=>$common_payment_systems_info["Currency"],	"after_html"=>"",	"etype"=>"",
				"ename"=>"", "ereadonly"=>"", "evalue"=>"",	"emaxlength"=>"",
				"estyle"=>"", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("amount_ps"),"before_html"=>"", "after_html"=>"",	"etype"=>"text",
				"ename"=>"amount",	"ereadonly"=>"", "evalue"=>$amount, "emaxlength"=>"8",
				"estyle"=>"width:200px", "isheadline"=>false, "edisabled"=>"")
	);
	$smarty->assign("FormElements",$FormElements);
}
function check_amount(&$my_error)
{
 global $Error_messages,$amount,$paytype;
	$my_error = "";
	$amount		= html_chars(get_post_value("amount",""));
	$paytype	= html_chars(get_post_value("paytype",""));
	if ($paytype == "") $my_error .= $Error_messages["no_payment_system"];
	else {
		if ($amount == "") $my_error .= $Error_messages["no_amount"];
		elseif (!check_float($amount)) $my_error .= $Error_messages["invalid_amount"];
		elseif ($amount <= 0) $my_error .= $Error_messages["small_amount"];
		else $amount = sprintf("%01.2f", $amount);
	}
	//If no errors - next page
	if ($my_error == "") return true;
	else return false;
}
function check_min_amount(&$my_error)
{
 global $Error_messages,$amount,$paytype;
	switch ($paytype) {
		case "credit_card":
							if ($amount < $_SESSION["paymentsettings"]["credit_card_mindeposit"]) $my_error .= str_replace("{*Amount*}",$_SESSION["paymentsettings"]["credit_card_mindeposit"],$Error_messages["small_mindeposit"]);
						break;
		case "egold":
							if ($amount < $_SESSION["paymentsettings"]["egold_mindeposit"]) $my_error .= str_replace("{*Amount*}",$_SESSION["paymentsettings"]["egold_mindeposit"],$Error_messages["small_mindeposit"]);
						break;
		case "paypal":
							if ($amount < $_SESSION["paymentsettings"]["paypal_mindeposit"]) $my_error .= str_replace("{*Amount*}",$_SESSION["paymentsettings"]["egold_mindeposit"],$Error_messages["small_mindeposit"]);
						break;
		case "2checkout":
							if ($amount < $_SESSION["paymentsettings"]["2checkout_mindeposit"]) $my_error .= str_replace("{*Amount*}",$_SESSION["paymentsettings"]["2checkout_mindeposit"],$Error_messages["small_mindeposit"]);
						break;
		case "": critical_error(__FILE__,__LINE__,"Payment type not found");
	}
	if ($my_error == "") return true;
	else return false;
}
function create_start_values($amount,$paytype_txt)
{
 global $smarty,$text_info,$common_payment_systems_info;
	$FormElements = array(
	array("flabel"=>show_cell_caption("pay_to"), "before_html"=>$_SESSION["globsettings"]["site_title"]."<span style=\"width:120px\">&nbsp;</span>",	"after_html"=>"",	"etype"=>"",
				"ename"=>"",	"ereadonly"=>"", "evalue"=>"", "emaxlength"=>"",
				"estyle"=>"", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("currency"),	"before_html"=>$common_payment_systems_info["Currency"], "after_html"=>"", "etype"=>"",
				"ename"=>"",	"ereadonly"=>"", "evalue"=>"", "emaxlength"=>"",
				"estyle"=>"", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("amount"),"before_html"=>$amount,	"after_html"=>"",	"etype"=>"",
				"ename"=>"",	"ereadonly"=>"", "evalue"=>$amount,	"emaxlength"=>"",
				"estyle"=>"", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("payment_system"), "before_html"=>$paytype_txt,	"after_html"=>"",	"etype"=>"",
				"ename"=>"",	"ereadonly"=>"", "evalue"=>"", "emaxlength"=>"",
				"estyle"=>"", "isheadline"=>false, "edisabled"=>"")
	);
	$smarty->assign("FormElements",$FormElements);
}

function get_advertiser_store_cc_list($userid)
{
 global $db_tables, $text_info, $smarty;
	$FormElements = array(
		array("flabel"=>show_cell_caption("pay_to"), "before_html"=>$_SESSION["globsettings"]["site_title"], "after_html"=>"",	"etype"=>"",
				"ename"=>"",	"ereadonly"=>"",	"evalue"=>"",	"emaxlength"=>"",
				"estyle"=>"", "isheadline"=>false, "edisabled"=>"")
	);
	$cc_data = array();
	$qr_res = mysql_query("SELECT DISTINCT sccid,cc_number_last4,cc_expiration_month,cc_expiration_year FROM ".$db_tables["payments_adv_stored_cc"]." WHERE uid_adv='$userid'") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$cc_data[] = array("sccid"=>$myrow["sccid"],"cc_number_last4"=>$myrow["cc_number_last4"],"cc_expiration_month"=>$myrow["cc_expiration_month"],"cc_expiration_year"=>$myrow["cc_expiration_year"]);
	}
	if (count($cc_data) > 0) {
		$card_list = "";
		for ($i=0; $i<count($cc_data); $i++)
		{
			$card_list .= '<option value="'.$cc_data[$i]["sccid"].'">XXXX-XXXX-XXXX-'.$cc_data[$i]["cc_number_last4"].' Exp: '.$cc_data[$i]["cc_expiration_month"].'/20'.$cc_data[$i]["cc_expiration_year"].'</option>';
		}
		//Get cc option
		$cc_option= html_chars(get_post_value("cc_option","stored"));
		$option = '<input type="radio" name="cc_option" value="stored" '.( ($cc_option == "stored") ? "checked" : "").' onChange="change_cc_active_fields(\'0\')" />'.$text_info["p_cc_options_stored"].'<select name="sccid" id="sccid" style="width:300px">'.$card_list.'</select><br /><input type="radio" name="cc_option" value="new_cc" '.( ($cc_option == "new_cc") ? "checked" : "").' onChange="change_cc_active_fields(\'1\')" />'.$text_info["p_cc_options_new_cc"];
		array_push($FormElements,
			array("flabel"=>show_cell_caption("cc_options"), "before_html"=>$option, "after_html"=>"", "etype"=>"",
					"ename"=>"",	"ereadonly"=>"",	"evalue"=>"",	"emaxlength"=>"",
					"estyle"=>"", "isheadline"=>false, "edisabled"=>"")
		);
		$js_val = ($cc_option == "stored") ? 0 : 1;
		$funcd_account_javascript = '
			<script language="javascript">
			<!--
				var cc_stored_field_list = new Array("sccid");
				var cc_enter_new_field_list = new Array("cc_number","cc_cvv2","cc_expiration_month","cc_expiration_year","name","email","phone","address1","address2","country","city","state","zipcode");
				change_cc_active_fields(\''.$js_val.'\');
			-->
			</script>
		';
		//Create javascript
		$smarty->assign("funcd_account_javascript",$funcd_account_javascript);
	}
 return $FormElements;
}
function create_credit_card_form1($cc_number,$cc_cvv2,$cc_expiration_month,$cc_expiration_year,
		$name,$email,$phone,$address1,$address2,$country_id,$city,$state,$zipcode)
{
 global $smarty, $text_info, $common_payment_systems_info;
	//Create form
	smarty_create_cform("frm","mainform","POST","adv_fund_account.php","","",5,$text_info["c_payment_details"],3,200,5,300,3);

	$cc_expiration_month = get_months_selectbox("cc_expiration_month","width:100px;","",$cc_expiration_month);
	$cc_expiration_year = get_yeas_selectbox("cc_expiration_year","width:100px;","",$cc_expiration_year);

	$country_selectbox	= get_country_selectbox_data($country_id);

	$FormElementsBegin = get_advertiser_store_cc_list($_SESSION["sess_userid"]);

	$FormElements = array(
	//Credit card info
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_cc_information"], "after_html"=>""),
	array("flabel"=>show_cell_caption("cc_number",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"cc_number", "ereadonly"=>"", "evalue"=>$cc_number, "emaxlength"=>"20",
				"estyle"=>"width:300px;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("cc_cvv2",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"cc_cvv2", "ereadonly"=>"", "evalue"=>$cc_cvv2, "emaxlength"=>"4",
				"estyle"=>"width:300px;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("cc_expiration",true,true), "before_html"=>$cc_expiration_month.$cc_expiration_year, "after_html"=>"", "etype"=>"",
				"ename"=>"", "ereadonly"=>"", "evalue"=>"", "emaxlength"=>"",
				"estyle"=>"", "isheadline"=>false, "edisabled"=>""),
	//Billing info
	array("isheadline"=>true, "hlclass"=>"form_hlclass","hlmessage"=>$text_info["p_billing_info"], "after_html"=>""),
	array("flabel"=>show_cell_caption("name",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"name", "ereadonly"=>"", "evalue"=>$name, "emaxlength"=>"250",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("email",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"email", "ereadonly"=>"", "evalue"=>$email, "emaxlength"=>"100",
				"estyle"=>"width:300px;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("phone",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"phone", "ereadonly"=>"", "evalue"=>$phone, "emaxlength"=>"50",
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
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>"")
	);
	$smarty->assign("FormElements",array_merge($FormElementsBegin,$FormElements));
}
function get_credit_card_values(&$cc_option,&$sccid,&$cc_number,&$cc_cvv2,&$cc_expiration_month,&$cc_expiration_year,&$name,&$email,&$phone,
		&$address1,&$address2,&$country,&$city,&$state,&$zipcode)
{
	$cc_option= html_chars(get_post_value("cc_option","new_cc"));
	$sccid		= html_chars(get_post_value("sccid",""));
	$cc_number= html_chars(get_post_value("cc_number",""));
	$cc_cvv2	= html_chars(get_post_value("cc_cvv2",""));
	$cc_expiration_month	= html_chars(get_post_value("cc_expiration_month",""));
	$cc_expiration_year		= html_chars(get_post_value("cc_expiration_year",""));
	$name			= html_chars(get_post_value("name",""));
	$email		= html_chars(get_post_value("email",""));
	$phone		= html_chars(get_post_value("phone",""));
	$address1	= html_chars(get_post_value("address1",""));
	$address2	= html_chars(get_post_value("address2",""));
	$country	= html_chars(get_post_value("country",""));
	$city			= html_chars(get_post_value("city",""));
	$state		= html_chars(get_post_value("state",""));
	$zipcode	= html_chars(get_post_value("zipcode",""));
}
function check_credit_card_values(&$my_error,&$cc_number,&$cc_cvv2,&$cc_expiration_month,&$cc_expiration_year,&$name,&$email,&$phone,
		&$address1,&$address2,&$country,&$city,&$state,&$zipcode)
{
 global $Error_messages,$text_info,$usersettings;
	//Check values on emptiness
	$vallist = array($cc_number,$cc_cvv2,$cc_expiration_month,$cc_expiration_year,$name);
	$errlist = array($Error_messages["no_cc_number"],$Error_messages["no_cc_cvv2"],
				$Error_messages["no_cc_expiration_month"],$Error_messages["no_cc_expiration_year"],
				$Error_messages["no_name"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)

	if (($cc_number != "") && (strlen($cc_number) < 14)) $my_error .= $Error_messages["invalid_cc_number_length"];
	elseif (!check_bigint($cc_number)) $my_error .= $Error_messages["invalid_cc_number"];

	if (($cc_cvv2 != "") && (strlen($cc_cvv2) < 3)) $my_error .= $Error_messages["invalid_cc_cvv2_length"];
	elseif (!check_int($cc_cvv2)) $my_error .= $Error_messages["invalid_cc_cvv2"];

 	if (($email != "") && !check_mail($email)) $my_error .= $Error_messages["invalid_email"];

	if (!check_country_id($country)) $my_error .= $Error_messages["invalid_country_id"];

	if ($cc_expiration_month != "") {
		if (!check_int($cc_expiration_month)) $my_error .= $Error_messages["invalid_cc_expiration_month"];
		elseif ( ($cc_expiration_month < 1) && ($cc_expiration_month > 12) ) $my_error .= $Error_messages["invalid_rcc_expiration_month"];
	}
	if ($cc_expiration_year != "") {
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($usersettings["timezone_identifier"]);
		$year4 = date("Y", mktime(0,0,0,date("m"),date("d"),date("Y")));
		$year2 = substr($year4,-2);
		if (!check_int($cc_expiration_year)) $my_error .= $Error_messages["invalid_cc_expiration_year"];
		elseif ( ($cc_expiration_year < $year2) && ($cc_expiration_year > ($year2+20)) ) $my_error .= $Error_messages["invalid_rcc_expiration_year"];
	}
}
function slash_credit_card_values(&$cc_number,&$cc_cvv2,&$cc_expiration_month,&$cc_expiration_year,&$name,&$email,&$phone,
		&$address1,&$address2,&$country,&$city,&$state,&$zipcode)
{
	$cc_number		= data_addslashes($cc_number);
	$cc_cvv2			= data_addslashes($cc_cvv2);
	$cc_expiration_month	= data_addslashes($cc_expiration_month);
	$cc_expiration_year		= data_addslashes($cc_expiration_year);
	$name					= data_addslashes($name);
	$email				= data_addslashes($email);
	$phone				= data_addslashes($phone);
	$address1			= data_addslashes($address1);
	$address2			= data_addslashes($address2);
	$country			= data_addslashes($country);
	$city					= data_addslashes($city);
	$state				= data_addslashes($state);
	$zipcode			= data_addslashes($zipcode);
}
function write_credit_card_values_to_session($amount,$tmp_tbl_id,$cc_number,$cc_cvv2,$cc_expiration_month,$cc_expiration_year,$name,
	$email,$phone,$address1,$address2,$country,$city,$state,$zipcode,$amount)
{
	$_SESSION["sess_tmp_tbl_id"]	= $tmp_tbl_id;
	$_SESSION["sess_cc_amount"]		= $amount;
	$_SESSION["sess_cc_number"]		= $cc_number;
	$_SESSION["sess_cc_cvv2"]			= $cc_cvv2;
	$_SESSION["sess_cc_expiration_month"]	= $cc_expiration_month;
	$_SESSION["sess_cc_expiration_year"]	= $cc_expiration_year;
	$_SESSION["sess_cc_name"]			= $name;
	$_SESSION["sess_cc_email"]		= $email;
	$_SESSION["sess_cc_address1"]	= $address1;
	$_SESSION["sess_cc_zipcode"]	= $zipcode;
	$_SESSION["sess_cc_amount"]		= $amount;
}

function create_egold_form()
{
 global $smarty, $text_info;
	smarty_create_cform("frm","mainform","POST","https://www.e-gold.com/sci_asp/payments.asp","",'target="_top"',5,$text_info["c_payment_details"],3,200,5,300,3);
}
function create_paypal_form()
{
 global $smarty, $text_info;
	smarty_create_cform("frm","mainform","POST","https://www.paypal.com/cgi-bin/webscr","",'target="_top"',5,$text_info["c_payment_details"],3,200,5,300,3);
//	smarty_create_cform("frm","mainform","POST","https://www.sandbox.paypal.com/cgi-bin/webscr","",'target="_top"',5,$text_info["c_payment_details"],3,200,5,300,3);
}
function create_2checkout_form()
{
 global $smarty, $text_info;
	smarty_create_cform("frm","mainform","POST","m_fund_account_2checkout.php","",'target="_top"',5,$text_info["c_payment_details"],3,200,5,300,3);
}

function insert_data_to_tmp_payment_table($amount,$paytype,$payinfo)
{
 global $db_tables;
	mysql_query("INSERT INTO ".$db_tables["payments_tmp_adv"]." VALUES(NULL,'{$_SESSION["sess_userid"]}',NOW(),'$amount','$paytype','$payinfo')") or query_die(__FILE__,__LINE__,mysql_error());
	return mysql_insert_id();
}
function add_data_to_tmp_payment_table($amount,$paytype,$tmp_payment_data)
{
	$payinfo = "";
	foreach ($tmp_payment_data as $k=>$v)
	{
		$v = str_replace("<","",$v); $v = str_replace(">","",$v); $v = str_replace("/","",$v);
		$payinfo .= "<{$k}>{$v}</{$k}>";
	}
	return insert_data_to_tmp_payment_table($amount,$paytype,$payinfo);
}
?>