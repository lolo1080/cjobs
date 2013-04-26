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
require_once "feeds_xml_func.php";
require_once "include/other/filter.php"; //needed for checking date value
require_once "app_cache_functions.php";
check_access(array(0));

doconnect();

$feed_id= data_addslashes(get_get_post_value("feed_id",""));

//feeds work page
$smarty->assign("curpage","feeds_xml2");

//Create line with page navigation
$smarty->assign("navwidth","10");
$smarty->assign("Pages",array(
	array("islink"=>1,"href"=>"feeds.php?$SLINE","text"=>$text_info["feeds"],"spacer"=>"")
));

//Create gray menu
$smarty->assign("gmbgcolor","#eeeeee");
$smarty->assign("GrayMenuItems",array());

//Create help button
smarty_create_helpbutton("feeds_xml.html");

//* * * Blank data * * *
$Configuration = array(
	"headtitle"			=> $text_info["p_configuration_headtitle"],
	"sendtitle"			=> $text_info["p_configuration_sendtitle"],
	"dataurl"				=> $text_info["p_data_url"],
	"debugmodetitle"=> $text_info["p_configuration_debugmodetitle"],
	"err_no_url"		=> $Error_messages["no_url"],
	"selectedfieldtitle"=>$text_info["p_selectedfieldtitle"],
	"xml_rawdata"		=> "",
	"div_xml_parsedfields"=> "",
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
			"title" 	=> $text_info["p_configuration_fld_category"],
			"note"		=> "Fill category field and keywords list.",
			"name"		=> "category",
			"content"	=> ""
		)
	),
	"configuration_list"=> get_configuration_list("WHERE feed_format='xml2'"),
	"categories_list" 	=> get_categories_list()
);

//* * * Data from DB data * * *
//Categories
$categories = array();
$qr_res = mysql_query("SELECT * FROM ".$db_tables["jobcategories"]." ORDER BY cat_name") or query_die(__FILE__,__LINE__,mysql_error());
while ($myrow = mysql_fetch_array($qr_res))
{
	$categories[] = array("cat_id"=>$myrow["cat_id"], "cat_name"=>$myrow["cat_name"], "cat_key"=>$myrow["cat_key"]);
}
$smarty->assign("categories",$categories);

//Data
$qr_res = mysql_query("SELECT * FROM ".$db_tables["xml2_feeds_data"]." WHERE feed_id='$feed_id' ORDER BY fdata_id") or query_die(__FILE__,__LINE__,mysql_error());
if (mysql_num_rows($qr_res) > 0) {
	//Get data
	$Configuration["acttype"] = 'save';
	$Configuration["save"]["eroror"] = '';
	$data_url = $category_id = array();
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$data_url[$myrow['fdata_id']] = $myrow['url'];
		$category_id[$myrow['fdata_id']] = $myrow['config_id'];
	}
	$Configuration["save"]["category_url"] = $data_url;
	$Configuration["save"]["category_id"] = $category_id;
}


//* * * Save data * * *
$save = html_chars(get_post_true_false("save"));
if ($save) {
	$my_error = '';
	$category_url = get_post_value2("category_url",array());
	$category_id = get_post_value2("category_id",array());

	for ($j=0; $j<count($category_url); $j++)
	{
//			if ($v[$j] == '') $my_error .= str_replace("{*Num*}",$i.':'.$j,$Error_messages["no_category_url"]);
		if ($category_url[$j] != '') {
			$url_ = $category_url[$j]; $category_url[$j] = check_url($category_url[$j]); 
			if ($category_url[$j] === false) {
				$category_url[$j] = $url_;
				$my_error .= str_replace("{*Num*}",($j+1),$Error_messages["incorrect_category_url"]);
			}
		}
		if ($category_id[$j] == '') $my_error .= str_replace("{*Num*}",$j,$Error_messages["no_category_id"]);
		elseif (!check_int($category_id[$j])) {
			$my_error .= str_replace("{*Num*}",$j,$Error_messages["incorrect_category_id"]);
		}
	}

	if ($my_error == '') {
		$err_urls_num = array();
			for ($j=0; $j<count($category_url); $j++)
			{
				if ($category_url[$j] == '') continue;
					for ($j1=0; $j1<count($category_url); $j1++)
					{
						if ($category_url[$j1] == '') continue;
						if ($j == $j1) continue;
						$key1 = $j.':'.$j1;
						$key2 = $j1.':'.$j;
						if (in_array($key1,$err_urls_num)) continue; //already have this error
						if (in_array($key2,$err_urls_num)) continue; //already have this error
						if ($category_url[$j] == $category_url[$j1]) {
							$err_urls_num[] = $key1; $err_urls_num[] = $key2;
							$er = str_replace("{*Num1*}",$j,$Error_messages["url_mathes"]);
							$er = str_replace("{*Num2*}",$j1,$er);
							$my_error .= $er;
						}
					}
			}
	}

	if ($my_error == '') {
		mysql_query("DELETE FROM ".$db_tables["xml2_feeds_data"]." WHERE feed_id='{$feed_id}'") or query_die(__FILE__,__LINE__,mysql_error());
		for ($j=0; $j<count($category_url); $j++)
		{
			if ($category_url[$j] == '') continue;
			mysql_query("INSERT INTO ".$db_tables["xml2_feeds_data"]." VALUES(NULL,'".$feed_id."','".$category_url[$j]."','".$category_id[$j]."')") or query_die(__FILE__,__LINE__,mysql_error());
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
$smarty->assign("LoadFeedsScript",true);

//Create hidden values for form
$smarty->assign("FormHidden",array(
	array("fname"=>"feed_id","fvalue"=>$feed_id),
	array("fname"=>$SNAME,"fvalue"=>$SID)
));

//Save session values for current page
save_session_values("feeds_xml2");

smarty_create_session_data();

$smarty->display('s_content_top.tpl');
?>