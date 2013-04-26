<?
/*
#############################
# functions_smartry.php
# Functions for smarty values
#############################
*/

function smarty_create_cform($fclass,$fname,$fmethod,$faction,$fenctype,$ftarget,$hcolspan,
	$form_header,$fbefore_width,$finfo1_width,$finner_width,$finfo2_width,$fafter_width)
{
 global $smarty;
	$smarty->assign("fclass",$fclass);
	$smarty->assign("fname",$fname);
	$smarty->assign("fmethod",$fmethod);
	$smarty->assign("faction",$faction);
	$smarty->assign("fenctype",$fenctype);
	$smarty->assign("ftarget",$ftarget);
	$smarty->assign("hcolspan",$hcolspan);
	$smarty->assign("form_header",$form_header);
	$smarty->assign("fbefore_width",$fbefore_width);
	$smarty->assign("finfo1_width",$finfo1_width);
	$smarty->assign("finner_width",$finner_width);
	$smarty->assign("finfo2_width",$finfo2_width);
	$smarty->assign("fafter_width",$fafter_width);
}

function smarty_create_sform($fclass,$fname,$fmethod,$faction,$fenctype,
	$ftarget,$hcolspan,$form_header,$fbefore_width,$finner_width,$fafter_width)
{
 global $smarty;
	$smarty->assign("sfclass",$fclass);
	$smarty->assign("sfname",$fname);
	$smarty->assign("sfmethod",$fmethod);
	$smarty->assign("sfaction",$faction);
	$smarty->assign("sfenctype",$fenctype);
	$smarty->assign("sftarget",$ftarget);
	$smarty->assign("shcolspan",$hcolspan);
	$smarty->assign("sform_header",$form_header);
	$smarty->assign("sfbefore_width",$fbefore_width);
	$smarty->assign("sfinner_width",$finner_width);
	$smarty->assign("sfafter_width",$fafter_width);
}

function smarty_create_cbuttons($btnalign,$btnspace)
{
 global $smarty;
	$smarty->assign("btnalign",$btnalign);
	$smarty->assign("btnspace",$btnspace);
}

function smarty_create_helpbutton($page)
{
 global $smarty,$text_info,$help_dir,$help_url,$help_url_no,$dir_devider;
	$dir_devider = '/';
	$help_page_dir = $help_dir.$_SESSION["sess_lang"].$dir_devider.$page;
	$help_page_url = $_SESSION["globsettings"]["site_url"].$help_url.$_SESSION["sess_lang"]."/".$page;
	$no_help_page_url = $_SESSION["globsettings"]["site_url"].$help_url_no;
	$smarty->assign("HelpButton",array("show"=>true,"title"=>$text_info["c_help"],"help_page"=>((file_exists($help_page_dir)) ? $help_page_url : $no_help_page_url)));
}

function smarty_create_message($type,$image,$message)
{
 global $smarty;
	$smarty->assign($type,true);
	$smarty->assign("iimgmane",$image);
	$smarty->assign("imessage",$message);
}

function smarty_create_session_data()
{
global $smarty,$UserNameList;
	$smarty->assign("SiteTitle",$_SESSION["globsettings"]["site_title"]);
	$smarty->assign("UserMenu",$_SESSION["sess_user"]);
	$smarty->assign("tbl1user",$UserNameList[$_SESSION["sess_user"]]);
}

function smarty_create_filter($ftitle,$ftext,$fremtext,$faction,$ftdcount,&$felements)
{
 global $smarty;
	$smarty->assign("FilterTitle",$ftitle);
	$smarty->assign("FilterText",$ftext);
	$smarty->assign("RemoveFilterText",$fremtext);
	$smarty->assign("filteraction",$faction);
	$smarty->assign("FilterTDCount",$ftdcount);
	$smarty->assign("FilterElements",$felements);
}

function smarty_create_standard_pagenavigation($isneedpage,$ptext,$start,$page_count,$naction,$pftext,$pvalue,$pgtext,$jsact_num)
{
 global $smarty;
	$smarty->assign("PageNavigation",$isneedpage);
	$smarty->assign("PagesText",$ptext);
	$smarty->assign("PagesItems",get_navigation_line($start,$page_count,$naction));
	$smarty->assign("PagesFromText","(".($start+1).$pftext.(($page_count == 0) ? 1 : $page_count).")");
	$smarty->assign("PagesInput",array("isneed"=>true,"action"=>$naction,"name"=>"start","value"=>$pvalue,"maxlength"=>"4","style"=>"width:30px; height:18px","img"=>"page_go.gif","img_w"=>12,"img_h"=>18,"imgtitle"=>$pgtext,"jsaction"=>get_js_action($jsact_num)));
}

function smarty_create_stats_pagenavigation($isneedpage,$ptext,$start,$page_count,$naction,$pftext,$pvalue,$pgtext,$jsact_num,$period,$period_capt,$period_text,$from_text,$date_from,$to_text,$date_to)
{
 global $smarty, $usersettings, $calendar_button, $calendar_button1;
	$smarty->assign("PageNavigation_and_PagePeriodSelect",$isneedpage);
	find_period($period,$period_val,$period_sel);
	$date_format_str = "(".$usersettings["dateformat_c_info"].")";
	$smarty->assign("PeriodPagesItems",array(
	array("flabel"=>$period_text, "flabel2"=>"", "before_html"=>"", "after_html"=>"&nbsp;&nbsp;", "etype"=>"select",
				"ename"=>"period", "edisabled"=>"", "evalue"=>$period_val, "eselected"=>$period_sel, 
				"ecaption"=>$period_capt, "jscipt"=>"onchange=\"date_from_to_status(this);\"",
				"estyle"=>"width:105px; font-family: Arial; font-size: 11px;", "multiple"=>""),
	array("flabel"=>$from_text, "flabel2"=>$date_format_str, "before_html"=>"",	"after_html"=>$calendar_button,	"etype"=>"text",
				"ename"=>"date_from", "ereadonly"=>"", "evalue"=>$date_from, "emaxlength"=>"10", "edisabled"=>"",
				"estyle"=>"width:65px; font-family: Arial; font-size: 12px;"),
	array("flabel"=>$to_text, "flabel2"=>$date_format_str, "before_html"=>"", "after_html"=>$calendar_button1, "etype"=>"text",
				"ename"=>"date_to", "ereadonly"=>"", "evalue"=>$date_to, "emaxlength"=>"10", "edisabled"=>"",
				"estyle"=>"width:65px; font-family: Arial; font-size: 12px;")
	));
	$smarty->assign("PagesText",$ptext);
	$smarty->assign("NavPagesItems",get_navigation_line($start,$page_count,$naction));
	$smarty->assign("PagesFromText","(".($start+1).$pftext.(($page_count == 0) ? 1 : $page_count).")");
	$smarty->assign("PagesInput",array("isneed"=>true,"action"=>$naction,"name"=>"start","value"=>$pvalue,"maxlength"=>"4","style"=>"width:30px; height:18px","img"=>"page_go.gif","img_w"=>12,"img_h"=>18,"imgtitle"=>$pgtext,"jsaction"=>get_js_action($jsact_num)));
}

function smarty_create_stats_selecet_pagenavigation($isneedpage,$ptext,$start,$page_count,$naction,$pftext,$pvalue,$pgtext,$jsact_num,$period,$period_capt,$period_text,$from_text,$date_from,$to_text,$date_to,&$list,$list_flabel1)
{
 global $smarty, $usersettings, $calendar_button, $calendar_button1;
	$smarty->assign("PageNavigation_and_PagePeriodSelect",$isneedpage);
	find_period($period,$period_val,$period_sel);
	$date_format_str = "(".$usersettings["dateformat_c_info"].")";
	$smarty->assign("PeriodPagesItems",array(
	array("flabel"=>$list_flabel1, "flabel2"=>"", "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"channel_id", "edisabled"=>"", "evalue"=>$list["val"],
				"eselected"=>$list["sel"], "ecaption"=>$list["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:105px; font-family: Arial; font-size: 11px;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>$period_text, "flabel2"=>"", "before_html"=>"", "after_html"=>"&nbsp;&nbsp;", "etype"=>"select",
				"ename"=>"period", "edisabled"=>"", "evalue"=>$period_val, "eselected"=>$period_sel, 
				"ecaption"=>$period_capt, "jscipt"=>"onchange=\"date_from_to_status(this);\"",
				"estyle"=>"width:105px; font-family: Arial; font-size: 11px;", "multiple"=>""),
	array("flabel"=>$from_text, "flabel2"=>$date_format_str, "before_html"=>"",	"after_html"=>$calendar_button,	"etype"=>"text",
				"ename"=>"date_from", "ereadonly"=>"", "evalue"=>$date_from, "emaxlength"=>"10", "edisabled"=>"",
				"estyle"=>"width:65px; font-family: Arial; font-size: 12px;"),
	array("flabel"=>$to_text, "flabel2"=>$date_format_str, "before_html"=>"", "after_html"=>$calendar_button1, "etype"=>"text",
				"ename"=>"date_to", "ereadonly"=>"", "evalue"=>$date_to, "emaxlength"=>"10", "edisabled"=>"",
				"estyle"=>"width:65px; font-family: Arial; font-size: 12px;")
	));
	$smarty->assign("PagesText",$ptext);
	$smarty->assign("NavPagesItems",get_navigation_line($start,$page_count,$naction));
	$smarty->assign("PagesFromText","(".($start+1).$pftext.(($page_count == 0) ? 1 : $page_count).")");
	$smarty->assign("PagesInput",array("isneed"=>true,"action"=>$naction,"name"=>"start","value"=>$pvalue,"maxlength"=>"4","style"=>"width:30px; height:18px","img"=>"page_go.gif","img_w"=>12,"img_h"=>18,"imgtitle"=>$pgtext,"jsaction"=>get_js_action($jsact_num)));
}

function smarty_create_date_stats_pagenavigation($isneedpage,$ptext,$start,$page_count,$naction,$pftext,$pvalue,$pgtext,$jsact_num,$from_text,$date_from,$to_text,$date_to)
{
 global $smarty, $usersettings, $calendar_button, $calendar_button1;
	$smarty->assign("PageNavigation_and_PagePeriodSelect",$isneedpage);
	$date_format_str = "(".$usersettings["dateformat_c_info"].")";
	$smarty->assign("PeriodPagesItems",array(
	array("flabel"=>$from_text, "flabel2"=>$date_format_str, "before_html"=>"",	"after_html"=>$calendar_button,	"etype"=>"text",
				"ename"=>"date_from", "ereadonly"=>"", "evalue"=>$date_from, "emaxlength"=>"10", "edisabled"=>"",
				"estyle"=>"width:65px; font-family: Arial; font-size: 12px;"),
	array("flabel"=>$to_text, "flabel2"=>$date_format_str, "before_html"=>"", "after_html"=>$calendar_button1, "etype"=>"text",
				"ename"=>"date_to", "ereadonly"=>"", "evalue"=>$date_to, "emaxlength"=>"10", "edisabled"=>"",
				"estyle"=>"width:65px; font-family: Arial; font-size: 12px;")
	));
	$smarty->assign("PagesText",$ptext);
	$smarty->assign("NavPagesItems",get_navigation_line($start,$page_count,$naction));
	$smarty->assign("PagesFromText","(".($start+1).$pftext.(($page_count == 0) ? 1 : $page_count).")");
	$smarty->assign("PagesInput",array("isneed"=>true,"action"=>$naction,"name"=>"start","value"=>$pvalue,"maxlength"=>"4","style"=>"width:30px; height:18px","img"=>"page_go.gif","img_w"=>12,"img_h"=>18,"imgtitle"=>$pgtext,"jsaction"=>get_js_action($jsact_num)));
}

function smarty_create_only_date_stats_pagenavigation($isneedpage,$ptext,$start,$page_count,$naction,$pftext,$pvalue,$pgtext,$jsact_num,$from_text,$date_from,$to_text,$date_to)
{
 global $smarty, $usersettings, $calendar_button, $calendar_button1;
	$smarty->assign("PagePeriodSelect",$isneedpage);
	$date_format_str = "(".$usersettings["dateformat_c_info"].")";
	$smarty->assign("PeriodPagesItems",array(
	array("flabel"=>$from_text, "flabel2"=>$date_format_str, "before_html"=>"",	"after_html"=>$calendar_button,	"etype"=>"text",
				"ename"=>"date_from", "ereadonly"=>"", "evalue"=>$date_from, "emaxlength"=>"10", "edisabled"=>"",
				"estyle"=>"width:65px; font-family: Arial; font-size: 12px;"),
	array("flabel"=>$to_text, "flabel2"=>$date_format_str, "before_html"=>"", "after_html"=>$calendar_button1, "etype"=>"text",
				"ename"=>"date_to", "ereadonly"=>"", "evalue"=>$date_to, "emaxlength"=>"10", "edisabled"=>"",
				"estyle"=>"width:65px; font-family: Arial; font-size: 12px;")
	));
	$smarty->assign("PagesInput",array("isneed"=>true,"action"=>$naction,"name"=>"start","value"=>$pvalue,"maxlength"=>"4","style"=>"width:30px; height:18px","img"=>"page_go.gif","img_w"=>12,"img_h"=>18,"imgtitle"=>$pgtext,"jsaction"=>get_js_action($jsact_num)));
}
?>