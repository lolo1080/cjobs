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
check_access(array(3));

function get_user_shownews()
{
 global $db_tables;
	$qr_res = mysql_query("SELECT shownews FROM ".$db_tables["users_member_settings"]." WHERE uid_mem='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return $myrow["shownews"];
	}
 return 0;
}

function get_amount_alerts_str()
{
 global $db_tables, $text_info;
	$qr_res = mysql_query("SELECT count(*) as num FROM ".$db_tables["member_job_alerts"]." WHERE uid_mem='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return ($myrow["num"] == 0) ? $text_info["c_account_alert_nothave"] : $text_info["c_account_alert_have"].' '.$myrow["num"];
	}
 return $text_info["c_account_alert_nothave"];
}

doconnect();

$shownews = get_user_shownews();

//Check News page
$news_info = "";
if ($shownews) {
	//Read news page
	$fp = fopen($news_filename,"r");
	$body = fread($fp,filesize($news_filename));
	fclose($fp);
	$news_info = str_replace("{*News*}",$body,$text_info["c_account_news"]);
	mysql_query("UPDATE ".$db_tables["users_member_settings"]." SET shownews=0 WHERE uid_mem='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
}
//Info page
$smarty->assign("curpage","mem_info");
$smarty->assign("Greetings",$text_info["c_greetings"]);
$smarty->assign("welcome_cp",$text_info["c_welcome_cp"]);
$smarty->assign("info_cp",$text_info["c_info_cp"]);
$smarty->assign("info_help",$text_info["c_info_help"]);
$smarty->assign("account_info",$text_info["c_account_info"]);
$alert_amount = get_amount_alerts_str();
$smarty->assign("account_alerts",str_replace("{*Amount*}",$alert_amount,$text_info["c_account_alert"]));
$smarty->assign("news_info",$news_info);

//Info page
$smarty->assign("curpage","mem_info");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
array("islink"=>1,"href"=>"member_info.php?$SLINE","text"=>$text_info["member_info"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("mem_info.html");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>