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
require_once "categories_func.php";
require_once "include/other/filter.php"; //needed for checking date value
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
$add		= get_post_true_false("add","");
$save		= get_post_true_false("save","");
$action	= get_get_post_value("action","");
$cat_id	= data_addslashes(get_get_post_value("cat_id",""));
if (($action == "") && ($cat_id == "")) critical_error(__FILE__,__LINE__,"Action or ID not found.");
$start = get_start();

//Check action
if ($cancel) { header("Location: categories.php?$SLINE"); exit; }
if ($save) try_save($cat_id);
elseif ($add) try_add();

//The first entry - create form -->>
if (!$save && !$add) {
	switch($action) {
		case "edit":
			$ereadonly = $disabled = "";
			$qr_res = mysql_query("SELECT * FROM ".$db_tables["jobcategories"]." WHERE cat_id='$cat_id'") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
			$myrow = mysql_fetch_array($qr_res);
			//Create form
			create_values($cat_id,$myrow["cat_name"],$myrow["cat_key"]);
			//Create buttons
			create_page_buttons("save",$text_info["btn_save"]);
			break;
		case "add":
			$ereadonly = $disabled = "";
			//Create form
			create_values("(auto)","","");
			//Create buttons
			create_page_buttons("add",$text_info["btn_save"]);
			break;
		default: critical_error(__FILE__,__LINE__,"Action is invalid.");
	}
}
//The first entry - create form <<--

//categories work page
$smarty->assign("curpage","categories_work");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"categories.php?$SLINE","text"=>$text_info["categories"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("categories_work.html");

//Create form
smarty_create_cform("frm","mainform","POST","categories_work.php","","",5,$text_info["c_edit"].$text_info["categories"],3,200,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",$FormButtons);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>$action),
	array("fname"=>"cat_id","fvalue"=>$cat_id),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Save session values for current page
save_session_values("categories_work");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>