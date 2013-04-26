<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "functions.php";
require_once "include/other/filter.php";
require_once "include/other/table_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";
require_once "app_cache_functions.php";
check_access(array(0));

//Check first entry
check_first_entry("manage_templates",array());

doconnect();

$start 			= get_start();
$action			= get_get_post_value("action","");
$template_id= get_get_post_value("template_id","");

//Check action. Action == "delete" - delete temlate
if ( ($action == "delete") && ($template_id != "") && check_int($template_id) ) {
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["templates"]." WHERE template_id='$template_id'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		if (!$myrow["issystem"]) {
			//Delete php script
			if (strlen($myrow["php_file"]) > 4) {
				if (is_file($frontend_phpscripts_dir.$myrow["php_file"])) @unlink($frontend_phpscripts_dir.$myrow["php_file"]);
			}
			//Delete template file
			if (strlen($myrow["diskname"]) > 4) {
				if (is_file($frontend_template_dir.$myrow["diskname"])) @unlink($frontend_template_dir.$myrow["diskname"]);
			}
			//Delete db record
			mysql_query("DELETE FROM ".$db_tables["templates"]." WHERE template_id='$template_id'") or query_die(__FILE__,__LINE__,mysql_error());
		}
	}
}

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"title"=>"title"
); //"code name" => "database field"
$sortfield_array_default = "title"; //default sorting field
$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder",""))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$smarty->assign("DataHead", array(
	array("tdw"=>"20", "tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"255","tdclass"=>"tbl_td_head","data"=>sort_link("manage_templates.php","title",$text_info["th_title"])),
	array("tdw"=>"255","tdclass"=>"tbl_td_head","data"=>$text_info["th_description"]),
	array("tdw"=>"60", "tdclass"=>"tbl_td_head","data"=>$text_info["th_options"])
));

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("ftitle"=>"title"); //"code name" => "database field"
$filter_errorfields = array("ftitle"=>$text_info["th_title"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_filter_td("tbl_td_bottom","filter","ftitle",   "filter_checkbox","filter_text_255px",$select_txt_array,$select_txt_values,"filter_select_text255px","text")
);
smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"manage_templates.php",2,$FilterElements);
$smarty->assign("FilterColspan",1);
//Check error
if ($my_error != "") {
	//Load start filter
	load_old_filter($filter_field,$text_field,$select_field,$filter_array);
	//Create error message
	smarty_create_message("error","abort.gif",$my_error);
}
//Save filter in session
set_old_session($filter_field,$text_field,$select_field,$filter_array);
//SQL query (with filter limitation)
$having_limitation = "";
$limitation = create_filter_limitation($accordance,$filter_field,$text_field,$select_field,$filter_array," WHERE ",array(""),$having_limitation);
////////////////////// Filter //////////////////////

//Table of content
$num  = 0;
$DataBody = array();
$dev = "&nbsp;/&nbsp;";
$page_count = get_page_count("SELECT count(*) as num FROM ".$db_tables["templates"]." ".$limitation,$row_count);
if ($start > ($page_count-1)) $start = $page_count-1;
if ($start < 0) $start = 0;
$from_count = $start*$row_count;
$qr_res = mysql_query("SELECT * FROM ".$db_tables["templates"]." $limitation ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
		or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	$str_link_del = ($myrow["issystem"]) ? '<img height="10" src="images/spacer.gif" width="20" alt="" border="0" />' : '<a href="manage_templates.php?action=delete&template_id='.$myrow["template_id"].'&'.$SLINE.'" onClick="return window.confirm(\''.$text_info["i_delete_template"].'\')">'.get_img("reject.gif",20,20,$text_info["c_delete"],get_js_action(2)).'</a>';
	$str_link_edit = '<a href="manage_templates_work.php?action=edit&template_id='.$myrow["template_id"].'&'.$SLINE.'">'.get_img("infedit.gif",20,20,$text_info["c_einfo"],get_js_action(6)).'</a>';
	$title_text = '&nbsp;<b>'.$text_info["p_title"].':</b> '.$myrow["title"].'<br />&nbsp;<b>'.$text_info["p_diskname"].':</b> '.$myrow["diskname"];
	$DataBody[$num] = array(
		array("tdw"=>"20", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
		array("tdw"=>"255","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>$title_text),
		array("tdw"=>"255","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["description"]),
		array("tdw"=>"60", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$str_link_edit.'&nbsp;'.$str_link_del)
	);
	$num++;
}
$smarty->assign("DataBodyCount",$num);
$smarty->assign("DataBody",$DataBody);

//manage_templates page
$smarty->assign("curpage","manage_templates");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"manage_templates.php?$SLINE","text"=>$text_info["manage_templates"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
smarty_create_standard_pagenavigation(true,$text_info["n_pages"],$start,$page_count,"manage_templates.php",$text_info["n_pages_from"],"",$text_info["n_go"],5);
  $addlink = 'manage_templates_work.php?action=add&'.$SLINE;
$smarty->assign("GrayMenuItems",array(
	array("link"=>$addlink, "text"=>$text_info["n_add_template"], "title"=>$text_info["n_add_new_template"],
			"img_name"=>"check_on.gif","img_w"=>"20","img_h"=>"20",
			"jsaction"=>get_js_action(1),"ascript"=>""),
));

//Create help button
smarty_create_helpbutton("manage_templates.html");

//Create hidden values for form
$smarty->assign("fdata_action","manage_templates.php");
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>"delete"),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
smarty_create_session_data();

//Save session values for current page
save_session_values("manage_templates");

$smarty->display('s_content_top.tpl');
?>