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
require_once "ipfirewall_func.php";
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
$add		= get_post_true_false("add","");
$action	= get_get_post_value("action","");
$ipid		= data_addslashes(get_get_post_value("ipid",""));
if (($action == "") && ($ipid == "")) critical_error(__FILE__,__LINE__,"Action or IP id not found.");
$start = get_start();

//Check action
if ($cancel) { header("Location: ipfirewall.php?$SLINE"); exit; }
if ($save) try_save($ipid);
elseif ($add) try_add();

//The first entry - create form -->>
if (!$save && !$add) {
	switch($action) {
		case "edit":
			$ereadonly = $disabled = "";
			$qr_res = mysql_query("SELECT ip FROM ".$db_tables["ipfirewall"]." WHERE ipid='$ipid'") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
			$myrow = mysql_fetch_array($qr_res);
			//Create form
			create_values($ipid,$myrow["ip"]);
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
$smarty->assign("curpage","ipfirewall_work");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"ipfirewall.php?$SLINE","text"=>$text_info["ipfirewall"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("ipfirewall.html");

//Create form
$form_capt = ($action != "add") ? $text_info["c_edit"].$text_info["ipfirewall"] : $text_info["c_add"].$text_info["ipfirewall"];
smarty_create_cform("frm","mainform","POST","ipfirewall_work.php","","",5,$form_capt,3,200,5,100,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",$FormButtons);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>$action),
	array("fname"=>"ipid","fvalue"=>$ipid),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Save session values for current page
save_session_values("ipfirewall_work");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>