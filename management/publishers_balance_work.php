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
require_once "publishers_balance_func.php";
require_once "app_cache_functions.php";
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
$action	= get_get_post_value("action","");
$uid_pub= data_addslashes(get_get_post_value("uid_pub",""));
if (($action == "") && ($uid_pub == "")) critical_error(__FILE__,__LINE__,"Action or ID not found.");
$start = get_start();

//Check action
if ($cancel) { header("Location: publishers_work.php?action=edit&uid_pub={$uid_pub}&{$SLINE}"); exit; }
if ($save) try_save($uid_pub);

//The first entry - create form -->>
if (!$save) {
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["users_publisher"]." WHERE uid_pub='$uid_pub'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
	$myrow = mysql_fetch_array($qr_res);
	//Create form
	create_values($uid_pub,$myrow["email"],$myrow["company"],$myrow["name"],$myrow["balance"]);
	//Create buttons
	create_page_buttons("save",$text_info["btn_save"]);
}
//The first entry - create form <<--

//Publishers work page
$smarty->assign("curpage","publishers_work");

//Create line with page navigation
$name_text = (isset($myrow["name"])) ? $myrow["name"] : html_chars(get_post_value("name",""));
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"publishers.php?$SLINE","text"=>$text_info["publishers"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
	array("islink"=>1,"href"=>"publishers_work.php?action=edit&uid_pub={$uid_pub}&{$SLINE}","text"=>$text_info["c_edit"].$text_info["publishers"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
	array("islink"=>0,"href"=>"","text"=>$name_text,"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("publishers_work.html");

//Create form
smarty_create_cform("frm","mainform","POST","publishers_balance_work.php","","",5,$text_info["c_edit"].$text_info["publishers"].' '.$text_info["p_balance"],3,200,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",$FormButtons);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>$action),
	array("fname"=>"uid_pub","fvalue"=>$uid_pub),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Save session values for current page
save_session_values("publishers_balance_work");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>