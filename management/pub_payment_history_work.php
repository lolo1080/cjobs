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
require_once "pub_payment_history_func.php";
check_access(array(0,2));

doconnect();

$pid	= get_get_post_value("pid","");
$pr		= get_get_post_value("pr","");

if ($pid == "") critical_error(__FILE__,__LINE__,"No PID");
if (($_SESSION["sess_user"] == "2") && (!is_this_user_payment_request($pid,$_SESSION["sess_userid"]))) critical_error(__FILE__,__LINE__,"Access violation. Another payment ID.");

//Check admin access
if ($_SESSION["sess_user"] == "0") $uid_pub = $_SESSION["sess_uid_pub"];
elseif ($_SESSION["sess_user"] == "2") $uid_pub = $_SESSION["sess_userid"];
if ($uid_pub == "all") $sql = "1=1";
else $sql = "uid_pub='$uid_pub'";


//Create Form
$qr_res = mysql_query("SELECT *,".format_sql_datetime("regtime")." as regdate, ".format_sql_datetime("paytime")." as paydate FROM ".$db_tables["payments_pub"]." WHERE pid='$pid' and $sql") 
		or query_die(__FILE__,__LINE__,mysql_error());
$myrow = mysql_fetch_array($qr_res);
create_values($myrow["regdate"],$myrow["paydate"],$myrow["amount"],$myrow["paytype"],$myrow["payee_account"],$myrow["payinfo"],
		$myrow["batchnum"],$myrow["status"]);

//Publisher payment request page
$smarty->assign("curpage","pub_payment_history_work");

//Create line with page navigation
$smarty->assign("navwidth","10");
if ($_SESSION["sess_user"] == "0") {
	if ($uid_pub == "all") { //One publisher payment history
		if ($pr == 1)
			$smarty->assign("Pages",array(
				array("islink"=>1,"href"=>"payment_request.php?".$SLINE,"text"=>$text_info["payment_request"],"spacer"=>"")
			));
		else
			$smarty->assign("Pages",array(
				array("islink"=>1,"href"=>"pub_payment_history.php?uid_pub=all&".$SLINE,"text"=>$text_info["pub_payment_history"],"spacer"=>"")
			));
	}
	else { //All publishers payment history
		$user_info = get_member_main_info($db_tables["users_publisher"],"uid_pub",$uid_pub);
		$c_einfo = ($user_info["result"]) ? $text_info["c_einfo"].' ('.$user_info["email"].')' : $text_info["c_einfo"];
		$smarty->assign("Pages",array(
			array("islink"=>1,"href"=>"publishers.php?$SLINE","text"=>$text_info["publishers"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
			array("islink"=>1,"href"=>"publishers_work.php?action=edit&uid_pub=".$uid_pub."&".$SLINE,"text"=>$c_einfo,"spacer"=>"&nbsp;&raquo;&nbsp;"),
			array("islink"=>1,"href"=>"pub_payment_history.php?uid_pub=".$uid_pub."&".$SLINE,"text"=>$text_info["c_payment_history"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
			array("islink"=>0,"href"=>"","text"=>$text_info["c_payment_details"],"spacer"=>""),
		));
	}
}
else //This publisher payment history
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"adv_fund_history.php?$SLINE","text"=>$text_info["adv_fund_history"],"spacer"=>"")
	));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("pub_payment_history_work.html");

//Create form
$form_capt = $text_info["c_payment_details"];
smarty_create_cform("frm","mainform","POST","pub_payment_history.php","","",5,$form_capt,3,200,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"ok","bvalue"=>$text_info["btn_ok"] ,"bscript"=>""),
));

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID),
	array("fname"=>"pr","fvalue"=>$pr)
));

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>