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
require_once "member_profile_func.php";
check_access(array(3));

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

$uid_mem = $_SESSION["sess_userid"];
//Check action
if ($cancel) { 
	header("Location: member_profile.php?$SLINE"); exit;
}
if ($save) try_save($uid_mem);

//The first entry - create form -->>
if (!$save) {
	$qr_res = mysql_query("SELECT *,".format_sql_date("regdate")." as regdate FROM ".$db_tables["users_member"]." WHERE uid_mem='$uid_mem'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
	$myrow = mysql_fetch_array($qr_res);
	//Create form
	create_values($uid_mem,$myrow["email"],$myrow["first_name"],$myrow["last_name"],$myrow["site"],
			$myrow["country_id"],$myrow["city"],$myrow["state"],$myrow["zipcode"]);
	//Create buttons
	create_page_buttons("save",$text_info["btn_save"]);
}
//The first entry - create form <<--

//members work page
$smarty->assign("curpage","member_profile");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"member_profile.php?$SLINE","text"=>$text_info["member_profile"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("member_profile.html");

//Create form
smarty_create_cform("frm","mainform","POST","member_profile.php","","",5,$text_info["c_edit"].$text_info["member_profile"],3,200,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",$FormButtons);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Save session values for current page
save_session_values("member_profile");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>