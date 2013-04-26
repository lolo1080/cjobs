<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "functions.php";
require_once "consts_smarty.php";
require_once "include/other/table_mini.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";
check_access(array(0));

doconnect();

function create_values(&$htmlbody)
{
 global $smarty;
	//Script for html editor
	$smarty->assign("AddHTMLEditorBody",get_html_editor("htmlbody",650,300,$_SESSION["globsettings"]["site_url"].'management/'));
	$FormElements = array(
	array("flabel"=>show_cell_caption("message_html"), "before_html"=>"", "after_html"=>"", "etype"=>"textarea",
				"ename"=>"htmlbody", "ereadonly"=>"", "evalue"=>$htmlbody,
				"estyle"=>"width: 500px; height: 150px", "isheadline"=>false)
	);
	$smarty->assign("FormElements",$FormElements);
}

$save = get_post_true_false("save","");

//Check action
if ($save) {
	//Write news page
	$htmlbody = stripslashes(get_post_value2("htmlbody",""));
	$fp = fopen($news_filename,"w");
	fputs($fp,$htmlbody);
	fclose($fp);
	if (strlen($htmlbody) > 0) {
		mysql_query("UPDATE ".$db_tables["users_advertiser_settings"]." SET shownews='1'") or query_die(__FILE__,__LINE__,mysql_error());
		mysql_query("UPDATE ".$db_tables["users_publisher_settings"]." SET shownews='1'") or query_die(__FILE__,__LINE__,mysql_error());
		mysql_query("UPDATE ".$db_tables["users_advertiser_settings"]." SET shownews='1'") or query_die(__FILE__,__LINE__,mysql_error());
	}
	smarty_create_message("error","info.gif",$text_info["i_news_saved"]);
}
//Read news page
$fp = fopen($news_filename,"r");
$htmlbody = html_chars(fread($fp,filesize($news_filename)));
fclose($fp);

//News page
$smarty->assign("curpage","news");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"news.php?$SLINE","text"=>$text_info["news"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("news.html");

//Create form
$form_capt = $text_info["c_edit"].$text_info["news"];
smarty_create_cform("frm","mainform","POST","news.php","","",5,$form_capt,3,100,5,650,3);
$smarty->assign("LoadEditorScript",true);
create_values($htmlbody);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",array(
	array("btn_classnum"=>"1","btype"=>"submit","bname"=>"save","bvalue"=>$text_info["btn_save"] ,"bscript"=>""),
));

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

smarty_create_session_data();
 
$smarty->display('s_content_top.tpl');
?>