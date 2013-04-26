<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "functions.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "include/other/table_mini.php";
require_once "topmenu_func.php";
check_access(array(1));

function get_user_shownews()
{
 global $db_tables;
	$qr_res = mysql_query("SELECT shownews FROM ".$db_tables["users_advertiser_settings"]." WHERE uid_adv='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return $myrow["shownews"];
	}
 return 0;
}

doconnect();

//Get data
$balance = get_user_balance("uid_adv",$_SESSION["sess_userid"],$db_tables["users_advertiser"]);
if (!$balance["result"]) critical_error(__FILE__,__LINE__,"Cannot get user balance.");

$shownews = get_user_shownews();

//Return after payment system process
if (isset($_SESSION["sess_PAYMENT_RETURN"]) && $_SESSION["sess_PAYMENT_RETURN"]) {
	$smarty->assign("Greetings",""); $smarty->assign("welcome_cp","");
	$smarty->assign("info_cp",""); $smarty->assign("info_help","");
	if (@$_SESSION["sess_PAYMENT_STATUS"] == "error") {
		smarty_create_message("error","abort.gif",$text_info["i_payment_not_success"]);
	}
	else {
		$message = "";
		switch ($_SESSION["sess_PAYMENT_SYSTEM"]) {
			case $payment_systems_array["credit_card"]:
				$message = 
					$text_info["c_payment_system"].$_SESSION["sess_PAYMENT_SYSTEM"].'<br />'.
					$text_info["c_payment_amount"].'$'.$_SESSION["sess_PAYMENT_AMOUNT"].'<br />'.
					$text_info["c_payment_batch_num"].$_SESSION["sess_PAYMENT_BATCH_NUM"];
				break;
			case $payment_systems_array["paypal"]:
				$message = 
					$text_info["c_payment_system"].$_SESSION["sess_PAYMENT_SYSTEM"].'<br />'.
					$text_info["c_payment_amount"].'$'.$_SESSION["sess_PAYMENT_AMOUNT"].'<br />'.
					$text_info["c_payment_payee_account"].$_SESSION["sess_PAYMENT_PAYEE_ACCOUNT"].'<br />'.
					$text_info["c_payment_payer_account"].$_SESSION["sess_PAYMENT_PAYER_ACCOUNT"].'<br />'.
					$text_info["c_payment_batch_num"].$_SESSION["sess_PAYMENT_BATCH_NUM"].'<br />'.
					$text_info["c_payment_note"];
				break;
		}
		smarty_create_message("error","info.gif",$message);
	}
	$_SESSION["sess_PAYMENT_RETURN"] = 0;
	unset_session_values(array("sess_PAYMENT_RETURN","sess_PAYMENT_SYSTEM","sess_PAYMENT_STATUS","sess_PAYMENT_MESSAGE","sess_PAYMENT_AMOUNT","sess_PAYMENT_BATCH_NUM","sess_PAYMENT_PAYEE_ACCOUNT","sess_PAYMENT_PAYER_ACCOUNT"));
	$news_info = "";
}
else {
	//Check News page
	$news_info = "";
	if ($shownews) {
		//Read news page
		$fp = fopen($news_filename,"r");
		$body = fread($fp,filesize($news_filename));
		fclose($fp);
		$news_info = str_replace("{*News*}",$body,$text_info["c_account_news"]);
		mysql_query("UPDATE ".$db_tables["users_advertiser_settings"]." SET shownews=0 WHERE uid_adv='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
	}
	//Info page
	$smarty->assign("curpage","adv_info");
	$smarty->assign("Greetings",$text_info["c_greetings"]);
	$smarty->assign("welcome_cp",$text_info["c_welcome_cp"]);
	$smarty->assign("info_cp",$text_info["c_info_cp"]);
	$smarty->assign("info_help",$text_info["c_info_help"]);
}
$smarty->assign("account_info",$text_info["c_account_info"]);
$smarty->assign("account_balance",str_replace("{*Balance*}",' $'.$balance["balance"],$text_info["c_account_balance"]));
$smarty->assign("news_info",$news_info);

//Info page
$smarty->assign("curpage","adv_info");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
array("islink"=>1,"href"=>"adv_info.php?$SLINE","text"=>$text_info["adv_info"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("adv_info.html");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>