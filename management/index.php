<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "leftmenu_const.php";
require_once "consts_smarty.php";
require_once "functions.php";
require_once "functions_mini2.php";
require_once "include/functions/functions_smartry.php";
require_once "connect.inc";

function isreguser($username,$userpass)
{
 global $db_tables,$Error_messages,$addmessage;
	$username = data_addslashes($username);
	$userpass = data_addslashes($userpass);
	switch ($_SESSION["sess_curlogintype"]) {
		case "admin":
			//Check admin login
			$qr_res = mysql_query("SELECT admid,admname FROM ".$db_tables["admins"]." WHERE admname='$username' and admpass=password('$userpass')") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) > 0) {
				$myrow = mysql_fetch_array($qr_res);
				$_SESSION["sess_user"] = "0"; $_SESSION["sess_userid"] = $myrow["admid"]; $_SESSION["sess_username"] = $myrow["admname"];
				return true;
			}
		break;
		case "advertiser":
			//Check advertiser login
			if (!check_visitor_ipfw($addmessage)) return false;
			$qr_res = mysql_query("SELECT uid_adv,email,isconfirmed,isenable,isdeleted FROM ".$db_tables["users_advertiser"]." WHERE email='$username' and pass='$userpass'") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) > 0) {
				$myrow = mysql_fetch_array($qr_res);
				if (!$myrow["isconfirmed"]) { $addmessage = $Error_messages["account_not_confirmed"]; return false; }
				elseif (!$myrow["isenable"]) { $addmessage = $Error_messages["disable_account"]; return false; }
				elseif ($myrow["isdeleted"]) { $addmessage = $Error_messages["deleted_account"]; return false; }
				else {
					$_SESSION["sess_user"] = "1"; $_SESSION["sess_userid"] = $myrow["uid_adv"]; $_SESSION["sess_username"] = $myrow["email"];
					return true;
				}
			}
		break;
		case "publisher":
			//Check publisher login
			if (!check_visitor_ipfw($addmessage)) return false;
			$qr_res = mysql_query("SELECT uid_pub,email,isconfirmed,isenable,isdeleted FROM ".$db_tables["users_publisher"]." WHERE email='$username' and pass='$userpass'") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) > 0) {
				$myrow = mysql_fetch_array($qr_res);
				if (!$myrow["isconfirmed"]) { $addmessage = $Error_messages["account_not_confirmed"]; return false; }
				elseif (!$myrow["isenable"]) { $addmessage = $Error_messages["disable_account"]; return false; }
				elseif ($myrow["isdeleted"]) { $addmessage = $Error_messages["deleted_account"]; return false; }
				else {
					$_SESSION["sess_user"] = "2"; $_SESSION["sess_userid"] = $myrow["uid_pub"]; $_SESSION["sess_username"] = $myrow["email"];
					return true;
				}
			}
		break;
		case "member":
			//Check member login
			if (!check_visitor_ipfw($addmessage)) return false;
			$qr_res = mysql_query("SELECT uid_mem,email,isconfirmed FROM ".$db_tables["users_member"]." WHERE email='$username' and pass='$userpass'") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) > 0) {
				$myrow = mysql_fetch_array($qr_res);
				if (!$myrow["isconfirmed"]) { $addmessage = $Error_messages["account_not_confirmed"]; return false; }
				else {
					$_SESSION["sess_user"] = "3"; $_SESSION["sess_userid"] = $myrow["uid_mem"]; $_SESSION["sess_username"] = $myrow["email"];
					return true;
				}
			}
		break;
	}
	$addmessage = $Error_messages["no_access"]; 
 return false;
}

function check_visitor_ipfw(&$addmessage)
{
 global $Error_messages;
	$realip = getip();
	$realip = substr($realip,0,14); 
	//Check IPFW
	if (check_ipfw_by_ip($realip)) {
		$admin_email = get_admin_email_free();
		$addmessage = str_replace("{*Email*}",'<a href="'.$admin_email.'">'.$admin_email.'</a>',$Error_messages["blocked_ip"]);
		return false;
	}
 return true;
}

function get_text_by_user_type()
{
 global $text_info;
	if ($_SESSION["sess_curlogintype"] == "admin") return $text_info["username"];
	else return $text_info["p_email"];
}

if (!isset($_SESSION["sess_curlogintype"]) || ($_SESSION["sess_curlogintype"] == "") || !in_array($_SESSION["sess_curlogintype"], array("admin","advertiser","publisher","member"))) { header("Location: ../"); exit; }

$username = get_post_value("username","");
$userpass = get_post_value("userpass","");
$login = get_post_true_false("login");
$clrsess = get_post_true_false("clrsess");
$addmessage = "";

//Check clear session value
if ($clrsess) {
	if (isset($_SESSION["sess_user"])) unset($_SESSION["sess_user"]);
	if (isset($_SESSION["sess_userid"])) unset($_SESSION["sess_userid"]);
}

doconnect();

//Check settings
get_global_settings();
get_payment_settings();

$smarty->assign("SiteTitle",$_SESSION["globsettings"]["site_title"].' :: '.$text_info["c_user_login_".$_SESSION["sess_curlogintype"]]);
$fadv = '<a class="simplelink" href="'.$_SESSION["globsettings"]["site_url"].'management/forgot_pass.php?user_type=advertisers">'.$text_info["c_forgot_pass_adv"].'</a>';
$fpub = '<a class="simplelink" href="'.$_SESSION["globsettings"]["site_url"].'management/forgot_pass.php?user_type=publishers">'.$text_info["c_forgot_pass_pub"].'</a>';
$smarty->assign("forgot_pass_adv",$fadv);
$smarty->assign("forgot_pass_pub",$fpub);

//Create table
$smarty->assign("tblloginbgcolor","#bfbfbf");
//$smarty->assign("tbllogincaption",$text_info["adm_cp"]);

//Create form
smarty_create_cform("frm","mainform","POST","index.php","","",5,$text_info["adm_ind"],3,60,5,200,3);
$smarty->assign("FormElements",array(
array("flabel"=>get_text_by_user_type().":",	"before_html"=>"",	"after_html"=>"",	"etype"=>"text",
			"ename"=>"username",	"ereadonly"=>"",	"evalue"=>$username,	"emaxlength"=>"30",
			"estyle"=>"width:200px", "isheadline"=>false, "edisabled"=>""),
array("flabel"=>$text_info["password"].":","before_html"=>"",	"after_html"=>"",	"etype"=>"password",
			"ename"=>"userpass",	"ereadonly"=>"",	"evalue"=>"",	"emaxlength"=>"30",
			"estyle"=>"width:200px", "isheadline"=>false, "edisabled"=>"")
));

//Buttons
smarty_create_cbuttons("right",10);
$smarty->assign("FormButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"login","bvalue"=>$text_info["btn_login"],"bscript"=>"")
));

//Create hidden values for form
$smarty->assign("FormHidden",array());


//If session is live...
if (isset($_SESSION["sess_user"]) && ($_SESSION["sess_user"] != "" ) && 
		(isset($_SESSION["sess_userid"]) && ($_SESSION["sess_userid"]) != "")) {
	$smarty->display('s_mainpage.tpl');
	exit;
}

//Check login...
if ($login && isreguser($username, $userpass)) {
	$_SESSION["sess_curmenu_status"] = $default_menu_status[$_SESSION["sess_user"]];
	$smarty->display('s_mainpage.tpl');
}
elseif ($login) smarty_create_message("error","abort.gif",$addmessage);

$smarty->display('login/s_login.tpl');
?>