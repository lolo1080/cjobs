<?
session_start();

require_once "consts_mail.php"; //mail settings
require_once "app_errors_handler.php";
require_once "connect.inc";
require_once "functions.php";
require_once "mail_func.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";
require_once "include/other/table_mini.php";
require_once "include/mail/send_mail.php";
check_access(array(0,1,2));

function bugreport_from_text()
{
	return "<b>Bug Report</b> from:<br />&nbsp;User type: ".$_SESSION["sess_curlogintype"]."<br />&nbsp;User email(name): ".$_SESSION["sess_username"]."<br />&nbsp;User ID: ".$_SESSION["sess_userid"];
}

//Check first entry
check_first_entry("sendbugreport",array());

//Get values...
$action	= get_get_post_value("action","");
$send		= get_post_true_false("send","");
$body		= get_post_value2("body","");

doconnect();

//Script for html editor
$smarty->assign("AddHTMLEditorBody",get_html_editor("body",650,300,$_SESSION["globsettings"]["site_url"].'management/'));

//If save mail...
if ($send) {
	if ($body == "") smarty_create_message("error","abort.gif",$Error_messages["no_message"]);
	elseif (strlen($body) > 1024*1024) smarty_create_message("error","abort.gif",$Error_messages["big_bugreport"]);
	else {
		$admin_email = get_admin_email_free();
		$attach_files = array(); $txtbody = "";
		$body = bugreport_from_text().$body;
		create_and_send_email($bug_report_email,$admin_email,"Bug Report Email",$body,$txtbody,$attach_files);
		$body = "";
		smarty_create_message("error","info.gif",$text_info["i_email_sended"]);
	}
}

//Create Page
//-----------------------------------
//Edit Bug Report E-Mail
//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"sendbugreport.php?$SLINE","text"=>$text_info["sendbugreport"],"spacer"=>"")
));

//Create form
smarty_create_cform("frm","mainform","POST","sendbugreport.php","","",5,$text_info["sendbugreport"],3,75,5,600,3);
$smarty->assign("LoadEditorScript",true);
$smarty->assign("FormElements",array(
	array("flabel"=>show_cell_caption("text_html"), "before_html"=>"", "after_html"=>"", "etype"=>"textarea",
			"ename"=>"body", "ereadonly"=>"", "evalue"=>$body,
			"estyle"=>"width: 600px; height: 200px", "isheadline"=>false)
));

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"send","bvalue"=>$text_info["btn_send"],"bscript"=>""),
));

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
//-----------------------------------

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("news.html");

smarty_create_session_data();

//Save session values for current page
save_session_values("sendbugreport");

$smarty->assign("curpage","sendbugreport");
$smarty->display('s_content_top.tpl');
?>