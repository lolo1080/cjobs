<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "app_events_handler.php";
require_once "functions.php";
require_once "functions_mini.php";
require_once "include/other/filter.php";
require_once "include/other/table_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";
require_once "pub_payment_request_func.php";
require_once "consts_mail.php";
require_once "include/mail/send_mail.php";
require_once "app_cache_functions.php";
check_access(array(2));


//Check first entry
check_first_entry("pub_payment_request",array());

function create_start_form()
{
 global $payment_type_val,$payment_type_sel,$payment_type_capt,$user_info;
	$payment_type_sel = array_fill(0,count($payment_type_val),"");
	$payment_type_sel[0] = "selected";
	create_values($user_info["balance"],$user_info["balance"],$payment_type_val,$payment_type_sel,$payment_type_capt,"");
}

function check_payment_request()
{
 global $my_error, $db_tables, $payment_types, $payment_info, $Error_messages, $text_info, 
		$user_info, $amount, $paytype, $payee_account, $parse_values;
	if ($user_info == false) critical_error(__FILE__,__LINE__,"User information not found");
	if (check_data()) {
		$qr_res = mysql_query("SELECT pid FROM ".$db_tables["payments_pub"]." WHERE uid_pub=".$_SESSION["sess_userid"]." and status=0") or query_die(__FILE__,__LINE__,mysql_error());
		if (mysql_num_rows($qr_res) > 0) {
			$my_error .= $Error_messages["find_request"]; return;
		}
		$ptf = array_flip($payment_types);
		switch ($paytype) {
			case "credit_card":
							if ($amount < $_SESSION["paymentsettings"]["credit_card_minwithdraw"]) {
								$my_error .= str_replace("{*Amount*}",$_SESSION["paymentsettings"]["credit_card_minwithdraw"],$Error_messages["small_minwithdraw"]);
								return;
							}
							$pt["paytype"] = $ptf["Credit Card"];
							break;
			case "paypal":
							if ($amount < $_SESSION["paymentsettings"]["paypal_minwithdraw"]) {
								$my_error .= str_replace("{*Amount*}",$_SESSION["paymentsettings"]["paypal_minwithdraw"],$Error_messages["small_minwithdraw"]);
								return;
							}
							$pt["paytype"] = $ptf["PayPal"];
							break;
			case "egold":
							if ($amount < $_SESSION["paymentsettings"]["egold_minwithdraw"]) {
								$my_error .= str_replace("{*Amount*}",$_SESSION["paymentsettings"]["egold_mindeposit"],$Error_messages["small_minwithdraw"]);
								return;
							}
							$pt["paytype"] = $ptf["E-Gold"];
							break;
			case "2checkout":
							if ($amount < $_SESSION["paymentsettings"]["2checkout_minwithdraw"]) {
								$my_error .= str_replace("{*Amount*}",$_SESSION["paymentsettings"]["2checkout_minwithdraw"],$Error_messages["small_minwithdraw"]);
								return;
							}
							$pt["paytype"] = $ptf["2checkout"];
							break;
			default : critical_error(__FILE__,__LINE__,"Payment type not found");
		}
		//Update payment info
		$batchnum = md5(uniqid("")); 
		$qr_res = mysql_query("INSERT INTO ".$db_tables["payments_pub"]." ".
			"VALUES(NULL,'".$_SESSION["sess_userid"]."',NOW(),NULL,'$amount','".$pt["paytype"]."','$payee_account','','$batchnum',0)") or query_die(__FILE__,__LINE__,mysql_error());
		$lid = mysql_insert_id();
		//Update user info
		$qr_res = mysql_query("UPDATE ".$db_tables["users_publisher"]." SET balance=balance-$amount WHERE uid_pub='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
		//Send event
		$event_array = array("event"=>"update", "source"=>"publishers", "table"=>"users_publisher", "ad_id"=>0);
		event_handler($event_array);
	//----Send mail---->>
		$parse_values["{*email*}"] = $user_info["email"];
		$parse_values["{*name*}"] = $user_info["name"];
		$parse_values["{*amount*}"] = $amount;
		$parse_values["{*payment_system*}"] = $payment_types[$pt["paytype"]];
		$admin_email = get_admin_email_free();
	//----Send mail to member (Affiliate payment request. Email to admin)
		$subj	= get_mailsubject("request_payment");
		$htmlmessage = get_email_file("request_payment","html");
		$textmessage = get_email_file("request_payment","txt");
		$attach_files = get_mail_attach("request_payment");
		create_and_send_email($admin_email,$admin_email,$subj,$htmlmessage,$textmessage,$attach_files);
	//----Send mail----<<
		smarty_create_message("error","info.gif",$text_info["i_payment_saved"]);
		$user_info = get_member_main_info($db_tables["users_publisher"],"uid_pub",$_SESSION["sess_userid"]);
		create_start_form();
	}
}

doconnect();

$start	= get_post_true_false("start","");

$ps = get_tbl_payment_systems($payment_systems_array_withdraw);
$payment_type_val	= array_keys($ps);
$payment_type_capt= array_values($ps);

$user_info = get_member_main_info($db_tables["users_publisher"],"uid_pub",$_SESSION["sess_userid"]);
$my_error = "";

//Check buttons or payment status
if ($start) check_payment_request();
else create_start_form();

if ($my_error != "") {
	smarty_create_message("error","abort.gif",$my_error);
	for ($i=0; $i<count($payment_type_val); $i++) {
		if (in_array($payment_type_val[$i],$payment_systems_array_withdraw)) continue;
		$payment_type_sel[] = ($paytype == $payment_type_val[$i]) ? "selected" : "";
	}
	create_values($user_info["balance"],$amount,$payment_type_val,$payment_type_sel,$payment_type_capt,$payee_account);
}

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"pub_payment_request.php?$SLINE","text"=>$text_info["pub_payment_request"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("pub_payment_request.html");

//Create form
smarty_create_cform("frm","mainform","POST","pub_payment_request.php","","",5,$text_info["c_payment_details"],3,180,5,200,3);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Buttons
smarty_create_cbuttons("right",5);
$FormButtons = array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"start","bvalue"=>$text_info["btn_start"],"bscript"=>"")
);
$smarty->assign("FormButtons",$FormButtons);

smarty_create_session_data();

$smarty->assign("curpage","pub_payment_request");

$smarty->display('s_content_top.tpl');
?>