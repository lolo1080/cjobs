<?
session_start();

require_once "consts.php";
require_once "consts_mail.php"; //mail settings
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "app_events_handler.php";
require_once "functions.php";
require_once "mail_func.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";
require_once "include/other/table_mini.php";
require_once "include/mail/send_mail.php";
require_once "app_cache_functions.php";
check_access(array(0));

function send_email($subj,$htmlbody,$textbody)
{
 global $mail,$db_tables;
	$admin_email = get_admin_email();
//	$subj	= get_mailsubject($mail);
	$subj = str_replace("{*site_title*}",$_SESSION["globsettings"]["site_title"],$subj);
	$attach_files = get_mail_attach($mail);
	$qr_res = mysql_query("SELECT name,email,username FROM ".$db_tables["users"]." WHERE isnew=0 and isdeleted=0") or die("<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". ".mysql_error().".<br />");
	set_time_limit(0);
	while ($myrow = mysql_fetch_array($qr_res)) {
		//----Send mail---->>
		$htmlbody1 = $htmlbody; $textbody1 = $textbody;
		$htmlbody1 = str_replace("{*name*}",$myrow["name"],$htmlbody1);
		$htmlbody1 = str_replace("{*username*}",$myrow["username"],$htmlbody1);
		$htmlbody1 = str_replace("{*site_title*}",$_SESSION["globsettings"]["site_title"],$htmlbody1);
		$textbody1 = str_replace("{*name*}",$myrow["name"],$textbody1);
		$textbody1 = str_replace("{*username*}",$myrow["username"],$textbody1);
		$textbody1 = str_replace("{*site_title*}",$_SESSION["globsettings"]["site_title"],$textbody1);
		//----Send mail to member (Send mail)
		create_and_send_email($myrow["email"],$admin_email,$subj,$htmlbody1,$textbody1,$attach_files);
		//----Send mail----<<
	}
}

//Check first entry
check_first_entry("mail",array());

//Get values...
$mail		= get_mailtype();
$action	= get_get_post_value("action","");
$elist	= get_get_post_value2("elist",array());
$save		= get_post_true_false("save","");
$send 	= get_post_true_false("send","");
$upload = get_post_true_false("upload");

if ($mail == "") critical_error(__FILE__,__LINE__,"Can not find mail.");

doconnect();
$subj = get_mailsubject($mail);

//Script for html editor
$smarty->assign("AddHTMLEditorBody",get_html_editor("htmlbody",650,300,$_SESSION["globsettings"]["site_url"].'management/'));

$filenamehtml = $mail_dir.$mail_array[$mail];
$filenametext = $mail_dir.$mail_array[$mail].".txt";
//If save mail...
if ($save) {
	//Write selected page
	$htmlbody = stripslashes(get_post_value2("htmlbody",""));
	$textbody = get_post_value2("textbody","");
	$subj = stripslashes(html_chars(get_post_value("subj","")));
	mysql_query("UPDATE ".$db_tables["mailsubject"]." SET mailsubject=\"$subj\" WHERE mailkey='$mail'") or die("<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". ".mysql_error().".<br />");
	//Send event
	$event_array = array("event"=>"update", "source"=>"mail", "table"=>"mailsubject", "ad_id"=>0);
	event_handler($event_array);
	$fp = fopen($filenamehtml,"w");
	fputs($fp,$htmlbody);
	fclose($fp);
	$fp = fopen($filenametext,"w");
	fputs($fp,stripslashes($textbody));
	fclose($fp);
	smarty_create_message("error","info.gif",$text_info["i_data_saved"]);
}

//If send e-mail
if ($send) {
	$htmlbody = stripslashes(get_post_value2("htmlbody",""));
	$textbody = get_post_value2("textbody","");
	$subj = get_post_value("subj","");
	if (($htmlbody == "") && ($textbody == "")) $my_error .= $Error_messages["no_body"];
	send_email($subj,$htmlbody,$textbody); //Sent e-mail
	$smarty->assign("error",true);
	$smarty->assign("iimgmane","info.gif");
	$smarty->assign("imessage",$text_info["email_send"]);
}

//Read selected page
$fp = fopen($filenamehtml,"r");
$htmlbody = html_chars(fread($fp,filesize($filenamehtml)));
fclose($fp);
$fp = fopen($filenametext,"r");
$textbody = html_chars(fread($fp,filesize($filenametext)));
fclose($fp);

//If upload ...
if ($upload) {
	do_upload($mail,$my_error);
	if ($my_error != "") smarty_create_message("error","abort.gif",$my_error);
}

//If delete...
if ($action == "delete") delete_attach($elist);

//Create Page
//-----------------------------------
//Edit Mail
//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"mail.php?$SLINE","text"=>$mail_array_text[$mail],"spacer"=>"")
));

//Create help button
smarty_create_helpbutton("news.html");

//Create form
smarty_create_cform("frm","mainform","POST","mail.php","","",5,$text_info["c_edit"].$mail_array_text[$mail],3,75,5,600,3);
$smarty->assign("LoadEditorScript",true);
$smarty->assign("FormElements",array(
	array("flabel"=>show_cell_caption("subj"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
			"ename"=>"subj", "ereadonly"=>"", "evalue"=>$subj, "emaxlength"=>"150",
			"estyle"=>"width:650px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("text_html"), "before_html"=>"", "after_html"=>"", "etype"=>"textarea",
			"ename"=>"htmlbody", "ereadonly"=>"", "evalue"=>$htmlbody,
			"estyle"=>"width: 600px; height: 200px", "isheadline"=>false),
	array("flabel"=>show_cell_caption("text_text"), "before_html"=>"", "after_html"=>"", "etype"=>"textarea",
			"ename"=>"textbody", "ereadonly"=>"", "evalue"=>$textbody,
			"estyle"=>"width: 650px; height: 200px", "isheadline"=>false),
));

//Buttons
smarty_create_cbuttons("right",5);
if ($mail == "massmail")
	$smarty->assign("FormButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"save","bvalue"=>$text_info["btn_save_template"],"bscript"=>""),
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"send","bvalue"=>$text_info["btn_send_current"],"bscript"=>""),
));
else
	$smarty->assign("FormButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"save","bvalue"=>$text_info["btn_save"],"bscript"=>""),
));

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"mail","fvalue"=>$mail),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
//-----------------------------------


//-----------------------------------
//Add Attach
//Sorting. Check query for sorting
$sortfield_array = array("aid"=>"aid", "path"=>"path"); //"code name" => "database field"
$sortfield_array_default = "aid"; //default sorting field
$sortfield = get_get_value("sortfield","");
$sortorder = get_get_value("sortorder","");
set_sort($sortfield,$sortorder); //Set forting values in session

//Table with attach (header)
$smarty->assign("DataHead",array(
	array("tdw"=>"25","tdclass"=> "tbl_td_head","data"=>"#"),
	array("tdw"=>"35","tdclass"=> "tbl_td_head","data"=>'<input type="checkbox" class="checkbox_data" name="allcheckbox" align="absmiddle" onclick="group_check(1,this.checked)" />'.get_img("arrow_down.gif",9,12,$text_info["th_sel_group"])),
	array("tdw"=>"40","tdclass"=> "tbl_td_head","data"=>sort_link("mail.php","aid",$text_info["th_id"],"")),
	array("tdw"=>"558","tdclass"=>"tbl_td_head","data"=>sort_link("mail.php","path",$text_info["th_path"],"")),
	array("tdw"=>"50","tdclass"=> "tbl_td_head","data"=>$text_info["th_action"])
));

//Table with attach (content)
$num = 0;
$DataBody = array();
$qr_res = mysql_query("SELECT *  FROM ".$db_tables["attach"]." WHERE mailname='$mail' ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]) or die("<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". ".mysql_error().".<br />");
while ($myrow = mysql_fetch_array($qr_res)) {
	$DataBody[$num] = array(
	array("tdw"=>"25", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
	array("tdw"=>"35", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>get_type_checkbox("elist[]",$myrow["aid"],"").'&nbsp;'),
	array("tdw"=>"40", "tdclass"=>"tbl_td_data","tdalign"=>"","data"=>$myrow["aid"]),
	array("tdw"=>"558","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>$myrow["path"]),
	array("tdw"=>"50", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>'<a href="mail.php?action=delete&elist[]='.$myrow["aid"].'&'.$SLINE.'">'.get_img("reject.gif",20,20,$text_info["del_attach"],get_js_action(2)).'</a>'),
	);
	$num++;
}
//Upload line
$smarty->assign("upaction","mail.php");
$smarty->assign("upmessage","<b>".$text_info["c_uploadfile"]."</b>");
$smarty->assign("upname","mail");
$smarty->assign("upvalue",$mail);
$smarty->assign("UpElement",array("etype"=>"file","ename"=>"userfile","estyle"=>"width: 614px"));
//Upload button
$smarty->assign("btnalign","right");
$smarty->assign("btnspace","5");
$smarty->assign("UploadButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"upload","bvalue"=>"Upload","bscript"=>"")
));
$smarty->assign("attachtable",true);
$smarty->assign("DataBody",$DataBody);
//Create form (upload)
$smarty->assign("imgform_header",$text_info["c_alist"]);
$smarty->assign("fdata_action","mail.php");
$smarty->assign("imgform_method","POST");
$smarty->assign("imgform_action","mail.php");
//Create hidden values for form
$smarty->assign("ImgFormHidden",array(
	array("fname"=>"action","fvalue"=>"delete"),
	array("fname"=>"mail","fvalue"=>$mail),
  array("fname"=>$SNAME,"fvalue"=>$SID)
));
//-----------------------------------

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
	$exdellnk = "javascript: if (confirm('".$text_info["i_start_delete_a"]."')) { submit_form('delete','fileform'); } void(0)";
$smarty->assign("GrayMenuItems",array(
array("link"=>"$exdellnk","text"=>$text_info["i_delsel"],"title"=>$text_info["i_delsel_a"],
			"img_name"=>"reject.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(2),"ascript"=>""),
));
smarty_create_session_data();

//Save session values for current page
save_session_values("mail");

$smarty->assign("curpage","mail");
$smarty->display('s_content_top.tpl');
?>