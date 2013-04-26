<?
session_start();

require_once "consts.php";
require_once "consts_mail.php"; //mail settings
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "app_events_handler.php";
require_once "functions.php";
require_once "functions_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "include/other/table_mini.php";
require_once "include/mail/send_mail.php";
require_once "adv_registration_func.php";
require_once "app_cache_functions.php";

doconnect();

$register = get_post_true_false("register","");

$my_error = "";

//Check settings
get_global_settings();

//Check action
if ($register) {
	if (isset($_SESSION["sess_confirm_email"]) && ($_SESSION["sess_confirm_email"] != "")) try_register();
	else try_confirm_register();
}
elseif (isset($_SESSION["sess_confirm_email"]) && ($_SESSION["sess_confirm_email"] != "")) create_values($_SESSION["sess_confirm_email"],"","","","","","","","","","","","");
else create_confirm_values("");

//Visitor registration page
$smarty->assign("curpage","adv_registration");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>$_SESSION["globsettings"]["site_url"],"text"=>$text_info["home"],"spacer"=>""),
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());
$smarty->assign("topmenucolor","#CfCfCf");
$smarty->assign("menucopyright",$text_info["copyright"]);
//Create help button
smarty_create_helpbutton("welcome.html");
 
//Create form
smarty_create_cform("frm","mainform","POST","adv_registration.php","","",5,$text_info["c_registration"],3,130,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"register","bvalue"=>$text_info["btn_register"] ,"bscript"=>""),
));

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

$smarty->assign("AddTopMenu",false);

//smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>