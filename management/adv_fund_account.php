<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "functions.php";
require_once "functions_mini.php";
require_once "functions_mini2.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "include/other/table_mini.php";
require_once "include/other/ctypt.php";
require_once "topmenu_func.php";
require_once "adv_fund_account_func.php";
check_access(array(1));

//Check first entry
check_first_entry("adv_fund_account",array("sess_continue_payment"));

function create_button($bname,$bvalue) //Create form buttons
{
 global $FormButtons;
	$FormButtons = array(
		array("btn_classnum"=>"1","btype"=>"submit","bname"=>$bname,"bvalue"=>$bvalue,"bscript"=>"")
	);
}
function create_main_form() //Create form header
{
 global $smarty, $text_info;
	//Create form
	smarty_create_cform("frm","mainform","POST","adv_fund_account.php","","",5,$text_info["c_payment_details"],3,200,5,200,3);
}
function create_hidden() //Create hidden values for form
{
 global $smarty,$SNAME,$SID;
	$smarty->assign("FormHidden",array(
		array("fname"=>$SNAME,"fvalue"=>$SID)
	));
}

function create_hidden_egold($amount,$uname,$upass) //Create hidden values for E-gold form
{
 global $smarty,$SLINE,$egold_info;
	$base_url = end_url_slash($_SESSION["globsettings"]["site_url"]);
	$smarty->assign("FormHidden",array(
		array("fname"=>"PAYEE_ACCOUNT", "fvalue"=>$_SESSION["paymentsettings"]["egold_id"]),
		array("fname"=>"PAYEE_NAME",    "fvalue"=>$_SESSION["globsettings"]["site_title"]),
		array("fname"=>"PAYMENT_AMOUNT","fvalue"=>$amount),
		array("fname"=>"PAYMENT_METAL_ID","fvalue"=>$egold_info["EGoldMetal"]),
		array("fname"=>"PAYMENT_UNITS", "fvalue"=>$egold_info["EGoldPaymentUnit"]),
		array("fname"=>"STATUS_URL",    "fvalue"=>$base_url.$egold_info["EGoldStatusURL"]."?".$SLINE),
		array("fname"=>"PAYMENT_URL",   "fvalue"=>$base_url.$egold_info["EGoldPayURL"]."?".$SLINE),
		array("fname"=>"NOPAYMENT_URL", "fvalue"=>$base_url.$egold_info["EGoldNoPayURL"]."?".$SLINE),
		array("fname"=>"BAGGAGE_FIELDS","fvalue"=>"uid uname upass"),
		array("fname"=>"uid",           "fvalue"=>$_SESSION["sess_userid"]),
		array("fname"=>"uname",         "fvalue"=>$uname),
		array("fname"=>"upass",         "fvalue"=>$upass)
	));
}

function create_hidden_paypal($amount,$uname,$tmp_tbl_id) //Create hidden values for PayPal form
{
 global $smarty,$SLINE,$paypal_info;
	$base_url = end_url_slash($_SESSION["globsettings"]["site_url"]);
	$smarty->assign("FormHidden",array(
		array("fname"=>"cmd",        "fvalue"=>"_xclick"),
		array("fname"=>"business",   "fvalue"=>$_SESSION["paymentsettings"]["paypal_email"]),
		array("fname"=>"item_name",  "fvalue"=>$_SESSION["globsettings"]["site_title"]),
		array("fname"=>"amount",     "fvalue"=>$amount),
		array("fname"=>"currency_code","fvalue"=>$paypal_info["PayPalCurrency"]),
		array("fname"=>"notify_url", "fvalue"=>$base_url.$paypal_info["PayPalNotifyURL"]."?".$SLINE),
		array("fname"=>"return",     "fvalue"=>$base_url.$paypal_info["PayPalPayURL"]."?".$SLINE),
		array("fname"=>"cancel_return","fvalue"=>$base_url.$paypal_info["PayPalNoPayURL"]."?".$SLINE),
		array("fname"=>"custom",     "fvalue"=>Encrypt($_SESSION["sess_userid"]."=;=".$uname."=;=".$tmp_tbl_id))
	));
}

function create_start_form()
{
 global $payment_type_val,$payment_type_sel,$payment_type_capt,$text_info,$smarty;
	create_main_form(); //Create form header
	create_hidden(); //Create hidden values for form
	create_button("next",$text_info["btn_next"]);
	$payment_type_sel = array("selected");
	for ($i=1; $i<count($payment_type_val); $i++)
	{ $payment_type_sel[] = ""; }
	create_payment_values("50.00",$payment_type_val,$payment_type_sel,$payment_type_capt);
}

doconnect();

$next		= get_post_true_false("next");
$start	= get_post_true_false("start");

$ps = get_tbl_payment_systems();
$payment_type_val	= array_keys($ps);
$payment_type_capt= array_values($ps);

//Create javascript
$smarty->assign("funcd_account_javascript","");

//Check buttons or payment status
if ($start) {
	critical_error(__FILE__,__LINE__,"Incorrect action.");
}
elseif ($next) {
	if ( (isset($_SESSION["sess_continue_payment"]) && $_SESSION["sess_continue_payment"]) || 
				(check_amount($my_error) && check_min_amount($my_error)) )  { //Prepare payment method

		//If continue payment (next form)
		if (isset($_SESSION["sess_continue_payment"]) && $_SESSION["sess_continue_payment"]) {
			$amount = $_SESSION["sess_adv_fndacc_amount"];
			$paytype = $_SESSION["sess_adv_fndacc_paytype"];
		}

		//If first or next form
		create_button("start",$text_info["btn_start"]);
		$paytype_txt = "Unknown";
		for ($i=0; $i<count($payment_type_val); $i++) {
			if ($paytype == $payment_type_val[$i]) {
				$paytype_txt = $payment_type_capt[$i]; break;
			}
		}
		$qr_res = mysql_query("SELECT * FROM ".$db_tables["users_advertiser"]." WHERE uid_adv=".$_SESSION["sess_userid"]." and isenable=1 and isdeleted=0") or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"User not found.");
		$myrow = mysql_fetch_array($qr_res);
		$uname = $myrow["email"];
		$upass = md5($myrow["pass"]);
		switch ($paytype) {
			case "credit_card":
							//If first form
							if (!isset($_SESSION["sess_continue_payment"]) || !$_SESSION["sess_continue_payment"]) {
								create_credit_card_form1("","","","",$myrow["name"],$myrow["email"],$myrow["phone"],$myrow["address1"],$myrow["address2"],
										$myrow["country_id"],$myrow["city"],$myrow["state"],$myrow["zipcode"]); //Create form for credit card payment (next)
								$_SESSION["sess_adv_fndacc_amount"] = $amount;
								$_SESSION["sess_adv_fndacc_paytype"] = $paytype;
								$_SESSION["sess_continue_payment"] = 1;
								create_button("next",$text_info["btn_next"]);
							}
							//If continue payment (next form)
							else {
								get_credit_card_values($cc_option,$sccid,$cc_number,$cc_cvv2,$cc_expiration_month,$cc_expiration_year,$name,$email,$phone,$address1,
										$address2,$country,$city,$state,$zipcode);
								if ($cc_option == "stored") {
									$stored_cc_data = get_stored_cc_data_by_id($sccid);
									if ($stored_cc_data["result"]) {
										if ($c = preg_match_all("~<(.+?)>(.*?)</(.+?)>~si", $stored_cc_data["payinfo"], $res)) {
											//Add payment data to temp table
											$ptf = array_flip($payment_types);	
											$tmp_tbl_id = insert_data_to_tmp_payment_table($amount,$paytype,$stored_cc_data["payinfo"]);
											//Write payment data to session
											$resids = array_flip($res[1]);	
											write_credit_card_values_to_session($amount,$tmp_tbl_id,Decrypt($res[2][$resids["cc_number_crypt"]]),
													$res[2][$resids["cc_cvv2"]],$res[2][$resids["cc_expiration_month"]],$res[2][$resids["cc_expiration_year"]],
													$res[2][$resids["name"]],$res[2][$resids["email"]],$res[2][$resids["phone"]],$res[2][$resids["address1"]],
													$res[2][$resids["address2"]],$res[2][$resids["country"]],$res[2][$resids["city"]],$res[2][$resids["state"]],
													$res[2][$resids["zipcode"]],$amount);

											header("Location: include/payment/".$credit_card_info["cc_script_name"]."?".$SLINE); exit;
										}
									}
								}

								check_credit_card_values($my_error,$cc_number,$cc_cvv2,$cc_expiration_month,$cc_expiration_year,$name,$email,$phone,
										$address1,$address2,$country,$city,$state,$zipcode);
								//If no errors - save
								if ($my_error == "") {
									slash_credit_card_values($cc_number,$cc_cvv2,$cc_expiration_month,$cc_expiration_year,$name,$email,$phone,$address1,
											$address2,$country,$city,$state,$zipcode);
									//Add payment data to temp table
									$tmp_payment_data = array("cc_number_crypt"=>data_addslashes(Encrypt($cc_number)),"cc_number_last4"=>substr($cc_number, -4),
											"cc_cvv2"=>$cc_cvv2,"cc_expiration_month"=>$cc_expiration_month,"cc_expiration_year"=>$cc_expiration_year,
											"name"=>$name,"email"=>$email,"phone"=>$phone,"address1"=>$address1,"address2"=>$address2,
											"country"=>$country,"city"=>$city,"state"=>$state,"zipcode"=>$zipcode);
									$ptf = array_flip($payment_types);	
									$tmp_tbl_id = add_data_to_tmp_payment_table($amount,$ptf["Credit Card"],$tmp_payment_data);
									//Write payment data to session
									write_credit_card_values_to_session($amount,$tmp_tbl_id,$cc_number,$cc_cvv2,$cc_expiration_month,$cc_expiration_year,
											$name,$email,$phone,$address1,$address2,$country,$city,$state,$zipcode,$amount);

									header("Location: include/payment/".$credit_card_info["cc_script_name"]."?".$SLINE); exit;
								}
								else { //else - try again
									smarty_create_message("error","abort.gif",$my_error);
									//Create form and buttons
									create_credit_card_form1($cc_number,$cc_cvv2,$cc_expiration_month,$cc_expiration_year,$name,$email,$phone,$address1,
										$address2,$country,$city,$state,$zipcode); //Create form for credit card payment
									create_button("next",$text_info["btn_next"]);
								}
							}
							break;
			case "egold":
							create_egold_form(); //Create form header for e-gold payment
							create_hidden_egold($amount,$uname,$upass);
							$_SESSION["sess_continue_payment"] = 0;
							break;
			case "paypal":
							//Add payment data to temp table
							$tmp_payment_data = array("business"=>$_SESSION["paymentsettings"]["paypal_email"],
									"currency_code"=>$paypal_info["PayPalCurrency"],"name"=>$uname);
							$ptf = array_flip($payment_types);	
							$tmp_tbl_id = add_data_to_tmp_payment_table($amount,$ptf["PayPal"],$tmp_payment_data);
							//Create form
							create_paypal_form(); //Create form header for paypal payment
							create_hidden_paypal($amount,$uname,$tmp_tbl_id);
							$_SESSION["sess_continue_payment"] = 0;
							break;
			case "2checkout":
							$_SESSION["sess_2checkout_amount"] = $amount;
							$_SESSION["sess_2checkout_uname"] = $uname;
							$_SESSION["sess_2checkout_upass"] = $upass;
							create_2checkout_form(); //Create form header for 2checkout payment
							$_SESSION["sess_continue_payment"] = 0;
							break;
			default: critical_error(__FILE__,__LINE__,"Payment type not found");
		}
		if (!$_SESSION["sess_continue_payment"]) create_start_values($amount,$paytype_txt);
	}
	else { //Find error
		create_main_form(); //Create form header
		create_hidden(); //Create hidden values for form
		smarty_create_message("error","abort.gif",$my_error);
		create_button("next",$text_info["btn_next"]);
		for ($i=0; $i<count($payment_type_val); $i++) {
			$payment_type_sel[] = ($paytype == $payment_type_val[$i]) ? "selected" : "";
		}
		create_payment_values($amount,$payment_type_val,$payment_type_sel,$payment_type_capt);
	}
}
else {
	$_SESSION["sess_continue_payment"] = 0;
/*
	if (isset($_SESSION["sess_PAYMENT_STATUS"]) && ($_SESSION["sess_PAYMENT_STATUS"] != "") &&
			isset($_SESSION["sess_PAYMENT_AMOUNT"]) && ($_SESSION["sess_PAYMENT_AMOUNT"] != "")) {
		if ($_SESSION["sess_PAYMENT_STATUS"] == "normal")
			smarty_create_message("error","info.gif",$text_info["i_payment_success"]."<br /><b>".$text_info["i_payment_amount"]."</b>$".$_SESSION["sess_PAYMENT_AMOUNT"]."<br /><b>".$text_info["i_payment_batcnum"]."</b>".$_SESSION["sess_PAYMENT_BATCH_NUM"]);
		else
			smarty_create_message("error","abort.gif",$text_info["i_payment_not_success"]."<br /><b>".$text_info["i_payment_amount"]."</b>$".$_SESSION["sess_PAYMENT_AMOUNT"]."<br /><b>".$text_info["i_payment_batcnum"]."</b>".$_SESSION["sess_PAYMENT_BATCH_NUM"]);
		unset($_SESSION["sess_PAYMENT_STATUS"]);
		unset($_SESSION["sess_PAYMENT_AMOUNT"]);
		unset($_SESSION["sess_PAYMENT_BATCH_NUM"]);
	}
*/
	unset_session_values(
		array("sess_PAYMENT_STATUS","sess_PAYMENT_AMOUNT","sess_PAYMENT_BATCH_NUM",
					"sess_2checkout_amount","sess_2checkout_uname","sess_2checkout_upass","sess_clickbank_amount",
					"sess_tmp_tbl_id","sess_cc_amount","sess_cc_number","sess_cc_cvv2","sess_cc_expiration_month",
					"sess_cc_expiration_year","sess_cc_name","sess_cc_email","sess_cc_address1","sess_cc_zipcode",
					"sess_cc_amount"));
	create_start_form();
}

$smarty->assign("curpage","adv_fund_account");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"adv_fund_account.php?$SLINE","text"=>$text_info["adv_fund_account"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("adv_fund_account.html");

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",$FormButtons);

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>