<?php
//Set path to Smarty directory
define("SMARTY_DIR",$script_dir."/smarty/libs/");
require_once(SMARTY_DIR."Smarty.class.php");

//Main settings for Smarty
$smarty = new Smarty;
$smarty->template_dir = $script_dir."/templates/";
$smarty->compile_dir = $script_dir."/templates_c/";
$smarty->compile_check = true;
$smarty->caching = false;

//Main values
$smarty->assign("error",false);
$smarty->assign("SLINE",$SLINE);
$smarty->assign("SNAME",$SNAME);
$smarty->assign("SID",$SID);
$smarty->assign("LoadCalendarScript",false);
$smarty->assign("LoadEditorScript",false);
$smarty->assign("LoadColorPickerScript",false);
$smarty->assign("LoadFeedsScript",false);
$smarty->assign("LoadHTML2FeedScript",false);
$smarty->assign("PageTopKeywords",false);
$smarty->assign("PageNavigation",false);
$smarty->assign("PageNavigation_and_PagePeriodSelect",false);
$smarty->assign("GrayMenuButtons_PageNavigation_and_PagePeriodSelect",false);
$smarty->assign("PagePeriodSelect",false);
$smarty->assign("AddBodyScript","");
$smarty->assign("AddHTMLEditorBody","");
$smarty->assign("FormElements",array());
$smarty->assign("FormHidden",array());
$smarty->assign("FormButtons",array());
$smarty->assign("FilterColspan",2);
$smarty->assign("AddFilter",true);
$smarty->assign("AddTopMenu",true);
//Table...
$smarty->assign("tbl1bgcolor","#bfbfbf"); //7a52c7
$smarty->assign("tbl1user","[ Visitor ]");
$smarty->assign("FrmNum",0);
//Line...
$smarty->assign("leftwidth","5");
$smarty->assign("lheight","1");
$smarty->assign("lcolor1","#b47e82"); //b67e82
$smarty->assign("lcolor2","#b6bab4");
$smarty->assign("rightwidth","0");
?>