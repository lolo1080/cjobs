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
require_once "include/other/filter.php";
require_once "include/other/table_mini.php";
require_once "include/functions/functions_smartry.php";
require_once "functions_mini.php";
require_once "statistics_users_func.php";
require_once "app_cache_functions.php";
check_access(array(0));

doconnect();

//Check first entry
check_first_entry("statistics_users",array());
$start = get_start();

////////////////////// Sorting //////////////////////
//Sorting. Prepare filds for sorting. Check query for sorting.
$sortfield_array = array(
	"sdate"=>"sdate", "users_amount"=>"users_amount", "search_amount"=>"search_amount",
	"clicks_amount"=>"clicks_amount", "earn_money"=>"earn_money"
); //"code name" => "database field"
$sortfield_array_default = "sdate"; //default sorting field
$sortfield = data_addslashes(html_chars(trim(get_get_value("sortfield",""))));
$sortorder = data_addslashes(html_chars(trim(get_get_value("sortorder","desc"))));
set_sort($sortfield,$sortorder); //Set sorting values in session
////////////////////// Sorting //////////////////////

//Table header
$DataHead = array(
	array("tdw"=>"55", "tdclass"=>"tbl_td_head","data"=>"#"),
	array("tdw"=>"100","tdclass"=>"tbl_td_head","data"=>sort_link("statistics_users.php","sdate",$text_info["th_date"])),
	array("tdw"=>"100","tdclass"=>"tbl_td_head","data"=>sort_link("statistics_users.php","users_amount",$text_info["th_users_amount"])),
	array("tdw"=>"100","tdclass"=>"tbl_td_head","data"=>sort_link("statistics_users.php","search_amount",$text_info["th_search_amount2"])),
	array("tdw"=>"100","tdclass"=>"tbl_td_head","data"=>sort_link("statistics_users.php","clicks_amount",$text_info["th_clicks_amount2"])),
	array("tdw"=>"100","tdclass"=>"tbl_td_head","data"=>sort_link("statistics_users.php","earn_money",$text_info["th_earn_total"])),
);
$smarty->assign("DataHead", $DataHead);

////////////////////// Filter //////////////////////
//Filter accordance
$accordance = array("fusers_amount"=>"users_amount", "fsearch_amount"=>"search_amount", "fclicks_amount"=>"clicks_amount",
	"fearn_money"=>"earn_money"); //"code name" => "database field"
$filter_errorfields = array("fusers_amount"=>$text_info["th_users_amount"],
	"fsearch_amount"=>$text_info["th_search_amount2"], "fclicks_amount"=>$text_info["th_clicks_amount2"],
	"fearn_money"=>$text_info["th_earn_total"]); //errors array ("code name" => "error message")
//Get filter values (from form)
get_filter_values_from_form(get_post_true_false("Filter"),get_post_true_false("RemoveFilter"),$filter_field,$text_field,$select_field,$filter_array);
//Check filter values
$my_error = "";
check_filter_values($filter_field,$text_field,$filter_array,$filter_errorfields,$my_error);
//Create filter
$FilterElements = array(
	get_blank_filter_td("tbl_td_bottom"),
	get_filter_td("tbl_td_bottom","filter","fusers_amount", "filter_checkbox","filter_text_100px",$select_digit_array,$select_digit_values,"filter_select_digit100px","int"),
	get_filter_td("tbl_td_bottom","filter","fsearch_amount","filter_checkbox","filter_text_100px",$select_digit_array,$select_digit_values,"filter_select_digit100px","int"),
	get_filter_td("tbl_td_bottom","filter","fclicks_amount","filter_checkbox","filter_text_100px",$select_digit_array,$select_digit_values,"filter_select_digit100px","int"),
	get_filter_td("tbl_td_bottom","filter","fearn_money",   "filter_checkbox","filter_text_100px",$select_float_array,$select_float_values,"filter_select_digit100px","float")
);
smarty_create_filter($text_info["filter_help"],$text_info["filter"],$text_info["removefilter"],"statistics_users.php",0,$FilterElements);
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

//Get current year, month
if (function_exists('date_default_timezone_set')) date_default_timezone_set($usersettings["timezone_identifier"]);
$ty = date("Y"); $tm = date("n");

//Get oldest regdate
$activ = array();
$qr_res = mysql_query("SELECT DATE_FORMAT(regdate,'%Y') as y, DATE_FORMAT(regdate,'%c') as m FROM ".
	$db_tables["users_advertiser"]." ORDER BY regdate LIMIT 1") or query_die(__FILE__,__LINE__,mysql_error());
if (mysql_num_rows($qr_res) > 0) {
	$myrow = mysql_fetch_array($qr_res);
	$ry = $myrow["y"]; $rm = $myrow["m"];
}
else {
	$ry = $ty; $rm = $tm;
}

// - - - - - - - - - - - -//
// * * Get statistics * * //

//Create temporary table
$qr_res = mysql_query("CREATE TEMPORARY TABLE IF NOT EXISTS stats_usersstats_tmp (".
	"sdate DATE NOT NULL,".
	"users_amount INT UNSIGNED NOT NULL,".
	"search_amount INT UNSIGNED NOT NULL,".
	"clicks_amount INT UNSIGNED NOT NULL,".
	"earn_money DECIMAL(12,5) NOT NULL)") or query_die(__FILE__,__LINE__,mysql_error());
mysql_query("TRUNCATE stats_usersstats_tmp") or query_die(__FILE__,__LINE__,mysql_error());

// * * Check cache * * //
$cache_params_array = array(
	"user"					=> $_SESSION["sess_user"],
	"userid"				=> $_SESSION["sess_userid"],
	"stats_query"		=> "stats_usersstats_tmp",
	"stats_type"		=> 0,
	"stats_type_a"	=> 0,
	"stats_type_b"	=> 0,
	"params_list"		=> array(),
	"table_name"		=> "stats_usersstats_tmp"
);

// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into temp stats table
if (!read_stats_cache($cache_params_array)) {
	//Users amount
	$users_amount_arr = get_users_amount();
	//Search amount
	$search_amount_arr = get_search_amount();
	//Clicks amount
	$clicks_amount_arr = get_clicks_amount();
	//Earn money
	$earn_money_arr = get_earn_money();
  //Create monthly stats
	for ($iy=$ry; $iy<=$ty; $iy++)
	{
		for ($im=$rm; $im<13; $im++)	
		{
			if (($iy == $ty) && ($im == ($tm+1))) break;
			$im_str = (strlen($im) == 1) ? '0'.$im : $im;
			$iyim_str = $iy.$im_str;
			//Correct data
  		$iua = isset($users_amount_arr[$iyim_str]) ? $users_amount_arr[$iyim_str] : 0;
  		$isa = isset($search_amount_arr[$iyim_str]) ? $search_amount_arr[$iyim_str] : 0;
			$ica = isset($clicks_amount_arr[$iyim_str]) ? $clicks_amount_arr[$iyim_str] : 0;
			$iem = isset($earn_money_arr[$iyim_str]) ? $earn_money_arr[$iyim_str] : 0.00;
			//Insert data in temporary table
			$qr_res = mysql_query("INSERT stats_usersstats_tmp VALUES ('$iy-$im_str-01','$iua','$isa','$ica','$iem')")
				or query_die(__FILE__,__LINE__,mysql_error());
		}
  	$rm = 1;
	}
}

// - - - - - - - - - - //
// * * Get content * * //

//Table of content
$num  = 0;
$DataBody = array();
$page_count = get_page_count("SELECT count(*) as num FROM stats_usersstats_tmp ".$limitation,$row_count);
if ($start > ($page_count-1)) $start = $page_count-1;
if ($start < 0) $start = 0;
$from_count = $start*$row_count;
$qr_res = mysql_query("SELECT *,DATE_FORMAT(sdate,'%Y') as y, DATE_FORMAT(sdate,'%c') as m FROM stats_usersstats_tmp $limitation ".
	"ORDER BY ".$_SESSION["sess_sortfield"]." ".$_SESSION["sess_sortorder"]." LIMIT $from_count, $row_count") 
		or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	$DataBody[$num] = array(
		array("tdw"=>"55", "tdclass"=>"tbl_td_head","tdalign"=>"","data"=>$num+1),
		array("tdw"=>"100","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$month_array[$myrow["m"]-1].", ".$myrow["y"]),
		array("tdw"=>"100","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%d",$myrow["users_amount"])),
		array("tdw"=>"100","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["search_amount"]),
		array("tdw"=>"100","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.$myrow["clicks_amount"]),
		array("tdw"=>"100","tdclass"=>"tbl_td_data","tdalign"=>"","data"=>'&nbsp;'.sprintf("%01.2f", $myrow["earn_money"]) ),
	);
	$num++;
}
$smarty->assign("DataBodyCount",$num);
$smarty->assign("DataBody",$DataBody);

//Users statistics page
$smarty->assign("curpage","statistics_users");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"statistics_users.php?action=0&$SLINE","text"=>$text_info["statistics_users"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");

//Create gray menu items
smarty_create_standard_pagenavigation(true,$text_info["n_pages"],$start,$page_count,"statistics_users.php",$text_info["n_pages_from"],"",$text_info["n_go"],5);
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("statistics_users.html");

//Create hidden values for form
$smarty->assign("fdata_action","statistics_users.php");
$smarty->assign("FormHidden",array(
	array("fname"=>$SNAME,"fvalue"=>$SID)
));
smarty_create_session_data();

// * * Write cache * * //
//if use cache - save stats
write_stats_cache($cache_params_array);

//Save session values for current page
save_session_values("statistics_users");
$smarty->display('s_content_top.tpl');
?>