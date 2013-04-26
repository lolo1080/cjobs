<?
//Set path to Smarty directory
define("SMARTY_DIR",$admin_dir_path."/smarty/libs/");
require_once(SMARTY_DIR."Smarty.class.php");

// includes some constants
require_once ('./consts.php'); // @todo: added by kenn

//Main settings for Smarty
$smarty = new Smarty;
$smarty->template_dir = $frontend_script_dir."/templates/";
$smarty->compile_dir = $frontend_script_dir."/templates_c/";
$smarty->compile_check = true;
$smarty->caching = false;

//Main values
$smarty->assign("error",false);
$smarty->assign("SLINE",$SLINE);
$smarty->assign("SNAME",$SNAME);
$smarty->assign("SID",$SID);
?>