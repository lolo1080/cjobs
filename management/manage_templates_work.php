<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "app_events_handler.php";
require_once "language.php";
require_once "lang/templates_{$_SESSION["sess_lang"]}.php";
require_once "connect.inc";
require_once "functions.php";
require_once "functions_mini.php";
require_once "functions_mini2.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "include/other/table_mini.php";
require_once "topmenu_func.php";
require_once "manage_templates_func.php";
require_once "include/other/filter.php"; //needed for checking date value
require_once $frontend_dir."app_cache_functions.php";
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
$template_id= data_addslashes(get_get_post_value("template_id",""));
if (($action == "") && ($template_id == "")) critical_error(__FILE__,__LINE__,"Action or ID not found.");
$start = get_start();

//Check action
if ($cancel) { header("Location: manage_templates.php?$SLINE"); exit; }
if ($save) try_save($template_id,"save");
elseif ($add) try_save($template_id,"add");

//The first entry - create form -->>
if (!$save && !$add) {
	switch($action) {
		case "edit":
			$qr_res = mysql_query("SELECT * FROM ".$db_tables["templates"]." WHERE template_id='$template_id'") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
			$myrow = mysql_fetch_array($qr_res);
			//Create form
			create_values($template_id,$myrow["title"],$myrow["diskname"],$myrow["description"],$myrow["caution_level"],$myrow["show_type"],
				$myrow["issystem"],$myrow["template_type"],$myrow["php_file"],"");
			//Create buttons
			create_page_buttons("save",$text_info["btn_save"]);
			break;
		case "add":
			//Create form
			$tplbody["header"] = $tplbody["content"] = $tplbody["footer"] = "";
			create_values("","","","","","1","0","1","",$tplbody);
			//Create buttons
			create_page_buttons("add",$text_info["btn_add"]);
			break;
		default: critical_error(__FILE__,__LINE__,"Action is invalid.");
	}

}
//The first entry - create form <<--

//manage_templates work page
$smarty->assign("curpage","manage_templates_work");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"manage_templates.php?$SLINE","text"=>$text_info["manage_templates"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("manage_templates_work.html");

//Create form
smarty_create_cform("frm","mainform","POST","manage_templates_work.php","","",5,$text_info["c_edit"].$text_info["manage_templates"],3,150,5,650,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",$FormButtons);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>$action),
	array("fname"=>"template_id","fvalue"=>$template_id),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Save session values for current page
save_session_values("manage_templates_work");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>