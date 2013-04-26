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
require_once "topmenu_func.php";
require_once "app_cache_functions.php";
check_access(array(0));

function change_password($login,$newpass,$cpass,$newemail,&$my_error)
{
 global $db_tables,$Error_messages,$smarty,$text_info;
	if ($login == "") { $my_error .= $Error_messages["no_Username"]; return; }
	if ($newemail == "") { $my_error .= $Error_messages["no_new_admemail"]; return; }
	if (!check_mail($newemail)) { $my_error .= $Error_messages["invalid_email"]; return; }
	if (($login == "") && ($newpass == "")) { $my_error .= $Error_messages["no_Newpassword"]; return; }
	if (($login == "") && ($cpass == "")) { $my_error .= $Error_messages["no_cpassword"]; return; }
	if ($newpass != $cpass) { $my_error .= $Error_messages["not_match_adm_pass"]; return; }
	$login = data_addslashes($login);
	$newpass = data_addslashes($newpass);
	$newemail = data_addslashes($newemail);
	if (($newpass == "") && ($cpass == "")) mysql_query("UPDATE ".$db_tables["admins"]." SET admname='$login', admemail='$newemail' WHERE admid='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
	else mysql_query("UPDATE ".$db_tables["admins"]." SET admname='$login', admpass=password('$newpass'), admemail='$newemail' WHERE admid='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
	//Send event
	$event_array = array("event"=>"update", "source"=>"admsettings", "table"=>"admins", "ad_id"=>0);
	event_handler($event_array);
	smarty_create_message("error","info.gif",$text_info["i_admin_settings_saved"]);
}

function get_cur_admemail()
{
 global $db_tables;
	$cur_email = "";
	$qr_res = mysql_query("SELECT admemail FROM ".$db_tables["admins"]." WHERE admid='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		$cur_email = $myrow["admemail"];
	}
 return $cur_email;
}

doconnect();

$action = get_post_true_false("action","");
$login = html_chars(get_post_value("login",""));
$newemail = html_chars(get_post_value("newemail",""));
$newpass = get_post_value("newpass","");
$cpass = get_post_value("cpass","");
if ($newemail == "") $cur_admemail = get_cur_admemail();
else $cur_admemail = $newemail;
$my_error = "";


if ($action) change_password($login,$newpass,$cpass,$newemail,$my_error);
if ($my_error != "") smarty_create_message("error","abort.gif",$my_error);
if ($login == "") $login = get_admin_login();

//Admin settings page
$smarty->assign("curpage","admsettings");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"admsettings.php?$SLINE","text"=>$text_info["admsettings"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("admsettings.html");

//Create form
smarty_create_cform("frm","mainform","POST","admsettings.php","","",5,$text_info["admsettings"],3,110,5,300,3);
$smarty->assign("FormElements",array(
	array("flabel"=>show_cell_caption("new_admemail"),"before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"newemail",	"ereadonly"=>"", "evalue"=>$cur_admemail,	"emaxlength"=>"55",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("Username"),"before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"login",	"ereadonly"=>"", "evalue"=>$login, "emaxlength"=>"35",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("Newpassword"),"before_html"=>"",	"after_html"=>"", "etype"=>"password",
				"ename"=>"newpass",	"ereadonly"=>"", "evalue"=>"",	"emaxlength"=>"35",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("cpassword"),"before_html"=>"",	"after_html"=>"", "etype"=>"password",
				"ename"=>"cpass",	"ereadonly"=>"", "evalue"=>"", "emaxlength"=>"35",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>"")
));

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"change","bvalue"=>$text_info["btn_change"] ,"bscript"=>""),
));

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>"change"),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>
