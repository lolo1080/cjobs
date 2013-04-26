<?
session_start();

require_once "consts.php";
require_once "consts_mail.php"; //mail settings
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "functions.php";
require_once "functions_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "include/other/table_mini.php";
require_once "include/mail/send_mail.php";
require_once "forgot_pass_func.php";

doconnect();

$user_type= get_get_post_value("user_type","");
$send			= get_post_true_false("send","");
$email 		= get_post_value("email","");

if (($user_type == "") || !in_array($user_type,array("advertisers","publishers","myarea"))) { header("Location: ../"); exit; }

$my_error = "";

//Check settings
get_global_settings();

//Forgot password page
$smarty->assign("curpage","forgotpass");

if ($send) {
	if ($email == "") $my_error .= $Error_messages["no_email"];
	elseif (!check_mail($email)) $my_error .= $Error_messages["invalid_email"];

	if ($my_error == "") {
		if ($user_type == "advertisers") $result = get_ap_login_info($db_tables["users_advertiser"],$email);
		elseif ($user_type == "publishers") $result = get_ap_login_info($db_tables["users_publisher"],$email);
		elseif ($user_type == "myarea") $result = get_ap_login_info($db_tables["users_member"],$email);

		if ($result["result"]) recovery_password($user_type,$result);
		else $my_error .= $Error_messages["no_member_with_email"];
	}
	if ($my_error != "") {
		smarty_create_message("error","abort.gif",$my_error);
		create_values($email);
	}
	else { header("Location: forgot_pass_success.php?user_type={$user_type}"); exit; }
}
else create_values("");

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
$smarty->assign("forgotinfo",$_SESSION["globsettings"]["site_title"]." : ".$text_info[$user_type]);
//Create help button
smarty_create_helpbutton("forgotpass.html");
 
//Create form
smarty_create_cform("frm","mainform","POST","forgot_pass.php","","",5,$text_info["c_forgotpass"],3,130,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"send","bvalue"=>$text_info["btn_send_password"] ,"bscript"=>""),
));

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID),
	array("fname"=>"user_type","fvalue"=>$user_type)
));

$smarty->assign("AddTopMenu",false);

$smarty->display('s_content_top.tpl');
?>