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
require_once "topmenu_func.php";
require_once "job_search_emails_func.php";
check_access(array(0));

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
$email_id		= data_addslashes(get_get_post_value("email_id",""));
if (($action == "") && ($email_id == "")) critical_error(__FILE__,__LINE__,"Action or E-mail ID id not found.");
$start = get_start();

//Check action
if ($cancel) { header("Location: job_search_emails.php?$SLINE"); exit; }
if ($save) try_save($email_id);
elseif ($add) try_add();

//The first entry - create form -->>
if (!$save && !$add) {
	switch($action) {
		case "edit":
			$ereadonly = $disabled = "";
			$qr_res = mysql_query("SELECT email FROM ".$db_tables["sites_feed_alert_emials"]." WHERE email_id='$email_id'") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
			$myrow = mysql_fetch_array($qr_res);
			//Create form
			create_values($email_id,$myrow["email"]);
			//Create buttons
			create_page_buttons("save",$text_info["btn_save"]);
			break;
		case "add":
			$ereadonly = $disabled = "";
			//Create form
			create_values("(auto)","");
			//Create buttons
			create_page_buttons("add",$text_info["btn_save"]);
			break;
		default: critical_error(__FILE__,__LINE__,"Action is invalid.");
	}
}
//The first entry - create form <<--

//IP FireWall work page
$smarty->assign("curpage","job_search_emails_work");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"job_search_emails.php?$SLINE","text"=>$text_info["job_search_emails"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("job_search_emails.html");

//Create form
$form_capt = ($action != "add") ? $text_info["c_edit"].$text_info["job_search_emails"] : $text_info["c_add"].$text_info["job_search_emails"];
smarty_create_cform("frm","mainform","POST","job_search_emails_work.php","","",5,$form_capt,3,200,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",$FormButtons);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>$action),
	array("fname"=>"email_id","fvalue"=>$email_id),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Save session values for current page
save_session_values("job_search_emails_work");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>