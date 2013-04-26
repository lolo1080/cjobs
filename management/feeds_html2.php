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
require_once "feeds_html_func.php";
require_once "include/other/filter.php"; //needed for checking date value
require_once "app_cache_functions.php";
check_access(array(0));

doconnect();

$feed_id= data_addslashes(get_get_post_value("feed_id",""));

//feeds work page
$smarty->assign("curpage","feeds_html2");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"feeds.php?$SLINE","text"=>$text_info["feeds"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("feeds_html2.html");

//* * * Blank data * * *
$Configuration = array(
	"headtitle"			=> $text_info["p_configuration_headtitle"],
	"sendtitle"			=> $text_info["p_configuration_sendtitle"],
	"dataurl"				=> $text_info["p_data_url"],
	"debugmodetitle"=> $text_info["p_configuration_debugmodetitle"],
	"err_no_url"		=> $Error_messages["no_url"],
	"selectedfieldtitle"=>$text_info["p_selectedfieldtitle"],
	"html_rawdata"		=> "",
	"html_parse_regular_expression"	=> "",
	"div_html_parsedfields"=> "",
	"save_btn"			=> $text_info["btn_save"],
	"fieldstitle"		=> $text_info["p_configuration_fieldstitle"],
	"acttype"				=> 'new',
	"feed_id"				=> $feed_id,
	"feed_name"			=> get_feed_name_by_id($feed_id),
	"fields"	=> array(
		array(
			"title"		=> $text_info["p_configuration_fld_title"],
			"note"		=> "",
			"name"		=> "title",
			"content"	=> ""
		),
		array(
			"title" 	=> $text_info["p_configuration_fld_company_name"],
			"note"		=> "Use empty string if you have not company",
			"name"		=> "company_name",
			"content"	=> ""
		),
		array(
			"title"		=> $text_info["p_configuration_fld_locId"],
			"note"		=> "Format: one from next text lines (without [ and ]):<br />[city, state(region), country]<br />[city, state(region)]<br />[city, country]<br />[city]<br />[country]<br /><br />Possible country values <a target=\"blank\" href=\"".$_SESSION["globsettings"]["site_url"].$help_url.$_SESSION["sess_lang"]."/countrylist.html\">list</a><br /><br />Country name is mandatory for locations not present in DB and locations without city",
			"name"		=> "locId",
			"content"	=> ""
		),
		array(
			"title"		=> $text_info["p_configuration_fld_description"],
			"note"		=> "",
			"name"		=> "description",
			"content"	=> ""
		),
		array(
			"title"		=> $text_info["p_configuration_fld_url"],
			"note"		=> "",
			"name"		=> "url",
			"content"	=> ""
		),
		array(
			"title" 	=> $text_info["p_configuration_fld_job_type"],
			"note"		=> "Return values: [fulltime, parttime, contract, internship, temporary]",
			"name"		=> "job_type",
			"content"	=> ""
		),
		array(
			"title"		=> $text_info["p_configuration_fld_site_type"],
			"note"		=> "Return values: [jobboard, employer].<br />jobboard - Job boards only,<br />employer - Employer web sites only<br /><br />Note: you can return string constant. Use text area for this.",
			"name"		=> "site_type",
			"content"	=> ""
		),
		array(
			"title" 	=> $text_info["p_configuration_fld_isstaffing_agencies"],
			"note"		=> "Return values: [0, 1].<br />0 - Job is not from staffing agencies,<br />1 - Job is from staffing agencies<br /><br />Note: you can return numeric constant. Use text area for this.",
			"name"		=> "isstaffing_agencies",
			"content"	=> ""
		),
		array(
			"title" 	=> $text_info["p_configuration_fld_salary"],
			"note"		=> "Format: digit. For example: 70000<br />Enter 0 if you have not salary.",
			"name"		=> "salary",
			"content"	=> ""
		),
		array(
			"title" 	=> $text_info["p_configuration_fld_nextpage"],
			"note"		=> "Next page URL",
			"name"		=> "nextpage",
			"content"	=> ""
		)
	),
	"configuration_list"=> get_configuration_list('html2'),
	"categories_list" 	=> get_categories_list()
);

//* * * Data from DB data * * *
$qr_res = mysql_query("SELECT * FROM ".$db_tables["html_feeds_data"]." WHERE feed_id='$feed_id' ORDER BY cat_id") or query_die(__FILE__,__LINE__,mysql_error());
if (mysql_num_rows($qr_res) > 0) {
	//Get data
	$Configuration["acttype"] = 'save';
	$Configuration["save"]["eroror"] = '';
	$category_url_d = $category_id_d = array();
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$category_url_d[$myrow['cat_id']][] = $myrow['url'];
		$category_id_d[$myrow['cat_id']][] = $myrow['config_id'];
	}
	//Prepare data
	$category_url = $category_id = array();
	$categories_list = get_categories_list();
	for ($i=0; $i<count($categories_list); $i++)
	{
		$cat_id = $categories_list[$i]['cat_id'];
		if (isset($category_url_d[$cat_id])) {
			for ($j=0; $j<count($category_url_d[$cat_id]); $j++)
			{
				$category_url[$cat_id][] = $category_url_d[$cat_id][$j];
				$category_id[$cat_id][] = $category_id_d[$cat_id][$j];
			}
		}
		else {
			$category_url[$cat_id][] = '';
			$category_id[$cat_id][] = '';
		}
	}
	$Configuration["save"]["category_url"] = $category_url;
	$Configuration["save"]["category_id"] = $category_id;
}


//* * * Save data * * *
$save = html_chars(get_post_true_false("save"));
if ($save) {
	$my_error = '';
	$category_url = get_post_value2("category_url",array());
	$category_id = get_post_value2("category_id",array());
	$i = 0;
	foreach ($category_url as $k=>$v)
	{
		$i++;
		for ($j=0; $j<count($v); $j++)
		{
//			if ($v[$j] == '') $my_error .= str_replace("{*Num*}",$i.':'.$j,$Error_messages["no_category_url"]);
			if ($v[$j] != '') {
				$url_ = $v[$j]; $category_url[$k][$j] = check_url($v[$j]); 
				if ($category_url[$k][$j] === false) {
					$category_url[$k][$j] = $url_;
					$my_error .= str_replace("{*Num*}",$i.':'.($j+1),$Error_messages["incorrect_category_url"]);
				}
			}
			if ($category_id[$k][$j] == '') $my_error .= str_replace("{*Num*}",$i.':'.$j,$Error_messages["no_category_id"]);
			elseif (!check_int($category_id[$k][$j])) {
				$my_error .= str_replace("{*Num*}",$i.':'.$j,$Error_messages["incorrect_category_id"]);
			}
		}
	}

	if ($my_error == '') {
		$err_urls_num = array();
		$i=0;
		foreach ($category_url as $k=>$v)
		{
			$i++;
			for ($j=0; $j<count($v); $j++)
			{
				if ($v[$j] == '') continue;
				$i1=0;
				foreach ($category_url as $k1=>$v1)
				{
					$i1++;
					for ($j1=0; $j1<count($v1); $j1++)
					{
						if ($v1[$j1] == '') continue;
						if (($k == $k1) && ($j == $j1)) continue;
						$key1 = $i.':'.$j.':'.$i1.':'.$j1;
						$key2 = $i1.':'.$j1.':'.$i.':'.$j;
						if (in_array($key1,$err_urls_num)) continue; //already have this error
						if (in_array($key2,$err_urls_num)) continue; //already have this error
						if ($v[$j] == $v1[$j1]) {
							$err_urls_num[] = $key1; $err_urls_num[] = $key2;
							$er = str_replace("{*Num1*}",$i.':'.$j,$Error_messages["url_mathes"]);
							$er = str_replace("{*Num2*}",$i1.':'.$j1,$er);
							$my_error .= $er;
						}
					}
				}
			}
		}
	}

	if ($my_error == '') {
		mysql_query("DELETE FROM ".$db_tables["html_feeds_data"]." WHERE feed_id='{$feed_id}'") or query_die(__FILE__,__LINE__,mysql_error());
		foreach ($category_url as $k=>$v)
		{
			for ($j=0; $j<count($v); $j++)
			{
				if ($v[$j] == '') continue;
				mysql_query("INSERT INTO ".$db_tables["html_feeds_data"]." VALUES(NULL,'".$feed_id."','".$k."','".$v[$j]."','".$category_id[$k][$j]."')") or query_die(__FILE__,__LINE__,mysql_error());
			}
		}
		header("Location: feeds.php?$SLINE"); exit;
	}
	else {
		$Configuration["acttype"] = 'save';
		$Configuration["save"]["category_url"] = $category_url;
		$Configuration["save"]["category_id"] = $category_id;
		$Configuration["save"]["eroror"] = $my_error;
	}
}

$smarty->assign("Configuration",$Configuration);
$smarty->assign("LoadHTML2FeedScript",true);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"feed_id","fvalue"=>$feed_id),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Save session values for current page
save_session_values("feeds_html2");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>