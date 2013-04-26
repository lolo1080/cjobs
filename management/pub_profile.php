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
require_once "pub_profile_func.php";
require_once "app_cache_functions.php";
check_access(array(2));

doconnect();

$change = get_post_true_false("change","");

//Check action
if ($change) try_change();
else {
	$qr_res = mysql_query("SELECT *,".format_sql_date("regdate")." as regdate FROM ".$db_tables["users_publisher"]." WHERE uid_pub='".$_SESSION["sess_userid"]."'") or query_die(__FILE__,__LINE__,mysql_error());
	$myrow = mysql_fetch_array($qr_res);
	create_values($myrow["email"],$myrow["pass"],$myrow["company"],$myrow["name"],$myrow["phone"],$myrow["fax"],
			$myrow["site"],$myrow["address1"],$myrow["address2"],$myrow["country_id"],$myrow["city"],$myrow["state"],
			$myrow["zipcode"],$myrow["promotioncode"],$myrow["ssn"],$myrow["regdate"]);
}

//Member profile page
$smarty->assign("curpage","pub_profile");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"pub_profile.php?$SLINE","text"=>$text_info["pub_profile"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("pub_profile.html");

//Create form
$form_capt = $text_info["c_edit"].$text_info["pub_profile"];
smarty_create_cform("frm","mainform","POST","pub_profile.php","","",5,$form_capt,3,200,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"change","bvalue"=>$text_info["btn_change"] ,"bscript"=>""),
));

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>