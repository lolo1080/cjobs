<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "functions.php";
require_once "functions_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "include/other/table_mini.php";
require_once "topmenu_func.php";
require_once "paymentsettings_func.php";
check_access(array(0));

doconnect();

$change = get_post_true_false("change","");

//Check action
if ($change) try_change();
else {
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["paymentsettings"]) or query_die(__FILE__,__LINE__,mysql_error());
	$myrow = mysql_fetch_array($qr_res);
	create_values(
		$myrow["credit_card_accept"],$myrow["credit_card_login"],$myrow["credit_card_minwithdraw"],$myrow["credit_card_mindeposit"],
		$myrow["paypal_accept"],$myrow["paypal_email"],$myrow["paypal_minwithdraw"],$myrow["paypal_mindeposit"],
		$myrow["egold_accept"],$myrow["egold_id"],$myrow["egold_passphrase"],$myrow["egold_minwithdraw"],
		$myrow["egold_mindeposit"],$myrow["2checkout_accept"],$myrow["2checkout_id"],
		$myrow["2checkout_minwithdraw"],$myrow["2checkout_mindeposit"],$myrow["2checkout_url"],$myrow["2checkout_test"]);
}

//Payment settings page
$smarty->assign("curpage","paymentsettings");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"paymentsettings.php?$SLINE","text"=>$text_info["paymentsettings"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("paymentsettings.html");

//Create form
$form_capt = $text_info["c_edit"].$text_info["paymentsettings"];
smarty_create_cform("frm","mainform","POST","paymentsettings.php","","",5,$form_capt,3,300,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"change","bvalue"=>$text_info["btn_change"] ,"bscript"=>""),
));

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>