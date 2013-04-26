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
require_once "topmenu_func.php";
require_once "adv_fund_history_func.php";
check_access(array(0,1));

doconnect();

$change = get_post_true_false("change","");
$pid		= get_get_post_value("pid","");

if ($pid == "") critical_error(__FILE__,__LINE__,"No PID");
if (($_SESSION["sess_user"] == "1") && (!is_this_user_payment_info($pid,$_SESSION["sess_userid"]))) critical_error(__FILE__,__LINE__,"Access violation. Another payment ID.");

//Check admin access
if ($_SESSION["sess_user"] == "0") $uid_adv = $_SESSION["sess_uid_adv"];
elseif ($_SESSION["sess_user"] == "1") $uid_adv = $_SESSION["sess_userid"];

//Check action
if ($change) try_change();
else {
	$qr_res = mysql_query("SELECT *,".format_sql_datetime("regtime")." as regdate FROM ".$db_tables["payments_adv"].
			" WHERE pid='$pid' and uid_adv='$uid_adv'") or query_die(__FILE__,__LINE__,mysql_error());
	$myrow = mysql_fetch_array($qr_res);
	create_values($myrow["regdate"],$myrow["amount"],$myrow["paytype"],$myrow["payinfo"],$myrow["batchnum"]);
}
//Member profile page
$smarty->assign("curpage","adv_fund_history_work");

//Create line with page navigation
$smarty->assign("navwidth","10");
if ($_SESSION["sess_user"] == "0") {
	$user_info = get_member_main_info($db_tables["users_advertiser"],"uid_adv",$uid_adv);
	$c_einfo = ($user_info["result"]) ? $text_info["c_einfo"].' ('.$user_info["email"].')' : $text_info["c_einfo"];
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"advertisers.php?$SLINE","text"=>$text_info["advertisers"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>1,"href"=>"advertisers_work.php?action=edit&uid_adv=".$uid_adv."&".$SLINE,"text"=>$c_einfo,"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>1,"href"=>"adv_fund_history.php?uid_adv=".$uid_adv."&".$SLINE,"text"=>$text_info["c_fund_history"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>0,"href"=>"","text"=>$text_info["c_payment_details"],"spacer"=>""),
	));
}
else
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"adv_fund_history.php?$SLINE","text"=>$text_info["adv_fund_history"],"spacer"=>"")
	));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("adv_fund_history_work.html");

//Create form
$form_capt = $text_info["c_payment_details"];
smarty_create_cform("frm","mainform","POST","adv_fund_history.php","","",5,$form_capt,3,200,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"ok","bvalue"=>$text_info["btn_ok"] ,"bscript"=>""),
));

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>