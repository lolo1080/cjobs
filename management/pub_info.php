<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "app_events_handler.php";
require_once "functions.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "include/other/table_mini.php";
require_once "topmenu_func.php";
require_once "app_cache_functions.php";
check_access(array(2));

function get_user_shownews()
{
 global $db_tables;
	$qr_res = mysql_query("SELECT shownews FROM ".$db_tables["users_publisher_settings"]." WHERE uid_pub='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		return $myrow["shownews"];
	}
 return 0;
}

function get_current_pub_xml_feed_status()
{
 global $db_tables, $text_info, $SLINE;
	$qr_res = mysql_query("SELECT isxmlfeed_enable FROM ".$db_tables["users_publisher"]." WHERE uid_pub='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		if ($myrow["isxmlfeed_enable"]) return $text_info["c_xml_feed_active"];
		else {
			$qr_res = mysql_query("SELECT us FROM ".$db_tables["users_submissions"]." WHERE uid='".$_SESSION["sess_userid"]."' and usertype='pub' and restype='xml'") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) > 0) {
				return $text_info["c_xml_feed_waiting"];
			}
			else {
				return $text_info["c_xml_feed_notactive"].': <a class="pagelink" href="pub_info.php?'.$SLINE.'&action=xml">'.get_img("arrow.gif",20,20,$text_info["p_activate"],get_js_action(10)).$text_info["p_activate"].'</a>';
			}	
		}
	}
 return '';
}

function check_this_action($action)
{
 global $db_tables, $SLINE;
	if ($_SESSION["globsettings"]["xml_pub_approved"]) {
		mysql_query("INSERT INTO ".$db_tables["users_submissions"]." VALUES(NULL,'{$_SESSION["sess_userid"]}','pub','xml')") or query_die(__FILE__,__LINE__,mysql_error());
	}
	else {
		mysql_query("UPDATE ".$db_tables["users_publisher"]." SET isxmlfeed_enable=1 WHERE uid_pub='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
		//Send event
		$event_array = array("event"=>"update", "source"=>"publishers", "table"=>"users_publisher", "ad_id"=>0);
		event_handler($event_array);
	}
}

doconnect();

$action			= get_get_post_value("action","");
if ($action == "xml") check_this_action($action);

//Get data
$balance = get_user_balance("uid_pub",$_SESSION["sess_userid"],$db_tables["users_publisher"]);
if (!$balance["result"]) critical_error(__FILE__,__LINE__,"Cannot get user balance.");

$shownews = get_user_shownews();

//Check News page
$news_info = "";
if ($shownews) {
	//Read news page
	$fp = fopen($news_filename,"r");
	$body = fread($fp,filesize($news_filename));
	fclose($fp);
	$news_info = str_replace("{*News*}",$body,$text_info["c_account_news"]);
	mysql_query("UPDATE ".$db_tables["users_publisher_settings"]." SET shownews=0 WHERE uid_pub='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
}
//Info page
$smarty->assign("curpage","pub_info");
$smarty->assign("Greetings",$text_info["c_greetings"]);
$smarty->assign("welcome_cp",$text_info["c_welcome_cp"]);
$smarty->assign("info_cp",$text_info["c_info_cp"]);
$smarty->assign("info_help",$text_info["c_info_help"]);

$smarty->assign("account_info",$text_info["c_account_info"]);
$smarty->assign("account_balance",str_replace("{*Balance*}",' $'.$balance["balance"],$text_info["c_account_balance"]));
$smarty->assign("xml_feed_status",get_current_pub_xml_feed_status());
$smarty->assign("news_info",$news_info);

//Info page
$smarty->assign("curpage","pub_info");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
array("islink"=>1,"href"=>"pub_info.php?$SLINE","text"=>$text_info["pub_info"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("pub_info.html");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>