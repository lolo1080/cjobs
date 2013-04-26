<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "app_events_handler.php";
require_once "functions.php";
require_once "functions_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "include/other/table_mini.php";
require_once "topmenu_func.php";
require_once $cron_dir."data_collection/functions.php";
require_once "member_jobalert_functions.php";
require_once "member_jobalert_func.php";
check_access(array(0,3));

doconnect();

function create_page_buttons($bname,$bvalue)
{
 global $FormButtons, $text_info;
	$FormButtons = array(
		array("btn_classnum"=>"1","btype"=>"submit","bname"=>$bname,"bvalue"=>$bvalue,"bscript"=>""),
		array("btn_classnum"=>"2","btype"=>"submit","bname"=>"cancel","bvalue"=>$text_info["btn_cancel"],"bscript"=>"")
	);
}

$cancel	= get_post_true_false("cancel","");
$save		= get_post_true_false("save","");
$add		= get_post_true_false("add","");
$action	= get_get_post_value("action","");
$ja_id	= get_get_post_value("ja_id","");
$ja_id	= check_sess_id_values($ja_id,"ja_id");

//Check admin user
if ($_SESSION["sess_user"] == "0") {
	$uid_mem = "";
	$uid_mem = check_sess_id_values($uid_mem,"uid_mem");
	if ($uid_mem == "") critical_error(__FILE__,__LINE__,"No Member ID");
}
elseif ($_SESSION["sess_user"] == "3") {
	$uid_mem = $_SESSION["sess_userid"];
}

//Check job alert type
if (!isset($_SESSION["sess_job_alert_type"])) $_SESSION["sess_job_alert_type"] = 'simple';
$job_alert_type	= get_get_post_value("job_alert_type","");
if ( ($job_alert_type != "") && in_array($job_alert_type, array("simple","advanced")) ) $_SESSION["sess_job_alert_type"] = $job_alert_type;

if ( ($ja_id != "") &&  (($_SESSION["sess_user"] == 0) || ( ($_SESSION["sess_user"] == 3) && this_user_job_alert($uid_mem,$ja_id))) ) $s = 1;
elseif ($ja_id != "") critical_error(__FILE__,__LINE__,"User or Job ID is incorrect.");

//Check action
if ($cancel) { header("Location: member_jobalert.php?$SLINE"); exit; }
if ($save) try_save($ja_id);
elseif ($add) try_add();

//The first entry - create form -->>
if (!$save && !$add) {
	switch($action) {
		case "edit":
			$qr_res = mysql_query("SELECT *,".format_sql_date("regdate")." as regdate FROM ".$db_tables["member_job_alerts"]." WHERE ja_id='$ja_id'") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
			$myrow = mysql_fetch_array($qr_res);
			//Create form
			$js = unpack_jobalert($myrow["job_alert"]);
			if ($job_alert_type == "") $_SESSION["sess_job_alert_type"] = $js["job_alert_type"];
			create_values($myrow["ja_id"],$myrow["name"],$js,$myrow["deliver"],$myrow["status"]);
			//Create buttons
			create_page_buttons("save",$text_info["btn_save"]);
			break;
		case "add":
			//Create form
			$js = unpack_jobalert("");
			if ($job_alert_type == "") $_SESSION["sess_job_alert_type"] = $js["job_alert_type"];
			create_values("","",$js,"",1);
			//Create buttons
			create_page_buttons("add",$text_info["btn_add"]);
			break;
		default: critical_error(__FILE__,__LINE__,"Action is invalid.");
	}
}
//The first entry - create form <<--

//members work page
$smarty->assign("curpage","member_jobalert_work");

//Create line with page navigation
$smarty->assign("navwidth","10");
if ($_SESSION["sess_user"] == "0") {
	$user_info = get_member_main_info2($db_tables["users_member"],"uid_mem",$uid_mem);
	$c_einfo = ($user_info["result"]) ? $text_info["c_einfo"].' ('.$user_info["email"].')' : $text_info["c_einfo"];
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"members.php?$SLINE","text"=>$text_info["members"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>1,"href"=>"members_work.php?action=edit&uid_mem=".$uid_mem."&".$SLINE,"text"=>$c_einfo,"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>1,"href"=>"member_jobalert.php?uid_mem=".$uid_mem."&".$SLINE,"text"=>$text_info["member_jobalert"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>0,"href"=>"","text"=>$text_info["c_job_alert_details"],"spacer"=>""),
	));
}
else
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"member_jobalert.php?$SLINE","text"=>$text_info["member_jobalert"],"spacer"=>"")
	));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("member_jobalert.html");

//Create form
smarty_create_cform("frm","mainform","POST","member_jobalert_work.php","","",5,$text_info["c_edit"].$text_info["member_jobalert"],3,200,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",$FormButtons);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>$action),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Save session values for current page
save_session_values("member_jobalert_work");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>