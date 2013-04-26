<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "connect.inc";
require_once "language.php";
require_once "consts_smarty.php";
require_once "topmenu_func.php";
require_once "app_events_handler.php";
require_once "functions.php";
require_once "functions_mini.php";
require_once "include/functions/functions_smartry.php";
require_once "include/other/table_mini.php";
require_once "adv_advertisement_keyword_ad_func.php";
require_once "include/other/filter.php"; //needed for checking date value
require_once "app_cache_functions.php";
check_access(array(1));

function get_keyword_ads($ad_id)
{
 global $db_tables, $keywors;
	$keywors = array();
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["keyword_ads"]." WHERE ad_id='$ad_id' ORDER BY soptions") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$keywors[] = array("kads_id"=>$myrow["kads_id"], "soptions"=>$myrow["soptions"], "keyword"=>$myrow["keyword"]);
	}
 return $keywors;
}

function keywords_array_to_text($keywords)
{
	$keywords_text = "";
	for($i=0; $i<count($keywords); $i++)
	{
		switch ($keywords[$i]["soptions"]) {
			case "1": $keywords_text .= $keywords[$i]["keyword"]."\n"; break;
			case "2": $keywords_text .= '['.$keywords[$i]["keyword"]."]\n"; break;
			case "3": $keywords_text .= '"'.$keywords[$i]["keyword"]."\"\n"; break;
			case "4": $keywords_text .= '-'.$keywords[$i]["keyword"]."\n"; break;
			default:  $keywords_text .= $keywords[$i]["keyword"]."\n";
		}
	}
 return $keywords_text;
}


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
$ad_id	= data_addslashes(get_get_post_value("ad_id",""));
if (($action == "") && ($ad_id == "")) critical_error(__FILE__,__LINE__,"Action or ID not found.");
if (($ad_id != "") && !is_this_user_current_ad($ad_id,$_SESSION["sess_userid"])) critical_error(__FILE__,__LINE__,"Access violation. Another advertiser Ad ID.");
$start = get_start();

//Check action
if ($cancel) {
	if ($action == "edit") header("Location: adv_advertisement_keyword_ad.php?action=edit&ad_id={$ad_id}&{$SLINE}");
	else header("Location: adv_advertisements.php?{$SLINE}");
	exit;
}
if ($save) try_save($ad_id);
elseif ($add) try_add();

//The first entry - create form -->>
if (!$save && !$add) {
	switch($action) {
		case "edit":
			$qr_res = mysql_query("SELECT * FROM ".$db_tables["ads"]." WHERE ad_id='$ad_id'") or query_die(__FILE__,__LINE__,mysql_error());
			if (mysql_num_rows($qr_res) == 0) critical_error(__FILE__,__LINE__,"No data.");
			$myrow = mysql_fetch_array($qr_res);
			$keywords = keywords_array_to_text(get_keyword_ads($ad_id));
			//Create form
			create_values($ad_id,$myrow["ad_name"],$myrow["headline"],$myrow["line_1"],$myrow["line_2"],$myrow["display_url"],
				$myrow["destination_url"],$myrow["max_cpc"],$myrow["daily_budget"],$myrow["monthly_budget"],$myrow["status"],$keywords);
			//Create buttons
			create_page_buttons("save",$text_info["btn_save"]);
			break;
		case "add":
			//Create form
			create_values("","","","","","","","","","","1","");
			//Create buttons
			create_page_buttons("add",$text_info["btn_add"]);
			break;
		default: critical_error(__FILE__,__LINE__,"Action is invalid.");
	}
}
//The first entry - create form <<--

//adv_advertisement_keyword_ad_work work page
$smarty->assign("curpage","adv_advertisement_keyword_ad_work");

//Create line with page navigation
$smarty->assign("navwidth","10");
if ($action == "edit") {
	$ad_name_text = get_ad_name($ad_id);
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"adv_advertisements.php?$SLINE","text"=>$text_info["adv_advertisements"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>1,"href"=>"adv_advertisement_keyword_ad.php?action=edit&ad_id={$ad_id}&{$SLINE}","text"=>$text_info["adv_advertisement_keyword_ad"],"spacer"=>"&nbsp;&raquo;&nbsp;"),
		array("islink"=>0,"href"=>"","text"=>$ad_name_text,"spacer"=>""),
	));
}
else {
	$smarty->assign("Pages",array(
		array("islink"=>1,"href"=>"adv_advertisements.php?$SLINE","text"=>$text_info["adv_advertisements"],"spacer"=>"")
	));
}

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("adv_advertisement_keyword_ad_work.html");

//Create form
$form_capt = ($action != "add") ? $text_info["c_edit"].$text_info["adv_advertisement_keyword_ad_work"] : $text_info["c_add"].$text_info["adv_advertisement_keyword_ad_work"];
smarty_create_cform("frm","mainform","POST","adv_advertisement_keyword_ad_work.php","","",5,$form_capt,3,200,5,300,3);

//Buttons
smarty_create_cbuttons("right",5);
$smarty->assign("FormButtons",$FormButtons);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"action","fvalue"=>$action),
	array("fname"=>"ad_id","fvalue"=>$ad_id),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Save session values for current page
save_session_values("adv_advertisement_keyword_ad_work");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>