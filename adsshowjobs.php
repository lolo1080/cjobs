<?
session_start();

require_once "consts.php";
require_once $admin_dir_path."app_errors_handler.php";
require_once "consts_smarty.php";
require_once "language.php";
require_once "template_vals.php";
require_once "index_functions.php";
require_once "search_functions.php";
require_once "search_functions_errpages.php";
require_once $admin_dir_path."connect.inc";
require_once $admin_dir_path."include/functions/functions_main.php";
require_once $frontend_script_dir."app_cache_functions.php";
require_once $frontend_script_dir."common_functions.php";
require_once $frontend_script_dir."common_statistic.php";

$AdsJobRollCnt = 4;
$job_search_params = array();

$possible_job_values = array(
	"job_format"	=> array(
		"values"	=> array("120x600","160x600","300x250","728x90"),
		"default"	=> "120x600"
	),
	"job_search"	=> array(
		"values"	=> array("job_search_basic","job_search_advanced"),
		"default"	=> "job_search_basic"
	),
	"job_type"	=> array(
		"values"	=> array("all","fulltime","parttime","contract","internship","temporary"),
		"default"	=> "all"
	),
	"job_show_from"	=> array(
		"values"	=> array("all","jobboard","employer"),
		"default"	=> "all"
	),
	"job_exclude_staffing"	=> array(
		"values"	=> array("0","1"),
		"default"	=> "0"
	),
	"job_where"	=> array(
		"values"	=> array("job_where_viewers_location","job_where_set_location"),
		"default"	=> "job_where_viewers_location"
	),
	"job_sr_colors"	=> array(
		"values"	=> array("job_sr_colors_default","job_sr_colors_set_colors"),
		"default"	=> "job_sr_colors_default"
	),
);

function get_jobroll_format()
{
 global $job_format;
 return $job_format;
}

function get_cur_values(&$job_format,&$job_search,&$job_what,&$job_with_all,&$job_exact_phrase,&$job_at_least_one,&$job_none,
	&$job_title,&$job_company,&$job_type,&$job_show_from,&$job_exclude_staffing,&$job_where,&$job_country,&$job_city_state,&$jobroll_publisher_id,
	&$job_channel,&$job_sr_colors,&$job_set_colors_bg,&$job_set_colors_title,&$job_set_colors_border,&$job_set_colors_job_title,
	&$job_set_colors_text,&$job_set_colors_company,&$job_set_colors_link,&$job_set_colors_source,&$job_set_colors_accent,
	&$job_set_colors_location)
{
	$job_format								= html_chars(get_get_post_value("job_format","120x600"));
	$job_search								= html_chars(get_get_post_value("job_search","job_search_basic"));
	$job_what									= html_chars(get_get_post_value("job_what",""));
	$job_with_all							= html_chars(get_get_post_value("job_with_all",""));
	$job_exact_phrase					= html_chars(get_get_post_value("job_exact_phrase",""));
	$job_at_least_one					= html_chars(get_get_post_value("job_at_least_one",""));
	$job_none									= html_chars(get_get_post_value("job_none",""));
	$job_title								= html_chars(get_get_post_value("job_title",""));
	$job_company							= html_chars(get_get_post_value("job_company",""));
	$job_type									= html_chars(get_get_post_value("job_type",""));
	$job_show_from						=	html_chars(get_get_post_value("job_show_from",""));
	$job_exclude_staffing			= html_chars(get_get_post_value("job_exclude_staffing","0"));
	$job_where								= html_chars(get_get_post_value("job_where","job_where_viewers_location"));
	$job_country							= html_chars(get_get_post_value("job_country","--"));
	$job_city_state						= html_chars(get_get_post_value("job_city_state",""));
	$jobroll_publisher_id			= html_chars(get_get_post_value("jobroll_publisher_id","")); //Publisher ID
	$job_channel							= html_chars(get_get_post_value("job_channel","0"));
	$job_sr_colors						= html_chars(get_get_post_value("job_sr_colors","job_sr_colors_default"));
	$job_set_colors_bg				= html_chars(get_get_post_value("job_set_colors_bg",""));
	$job_set_colors_title			= html_chars(get_get_post_value("job_set_colors_title",""));
	$job_set_colors_border		= html_chars(get_get_post_value("job_set_colors_border",""));
	$job_set_colors_job_title	= html_chars(get_get_post_value("job_set_colors_job_title",""));
	$job_set_colors_text			= html_chars(get_get_post_value("job_set_colors_text",""));
	$job_set_colors_company		= html_chars(get_get_post_value("job_set_colors_company",""));
	$job_set_colors_link			= html_chars(get_get_post_value("job_set_colors_link",""));
	$job_set_colors_source		= html_chars(get_get_post_value("job_set_colors_source",""));
	$job_set_colors_accent		= html_chars(get_get_post_value("job_set_colors_accent",""));
	$job_set_colors_location	= html_chars(get_get_post_value("job_set_colors_location",""));
}

function check_cur_values(&$job_format,&$job_search,&$job_what,&$job_with_all,&$job_exact_phrase,&$job_at_least_one,&$job_none,
	&$job_title,&$job_company,&$job_type,&$job_show_from,&$job_exclude_staffing,&$job_where,&$job_country,&$job_city_state,&$jobroll_publisher_id,
	&$job_channel,&$job_sr_colors,&$job_set_colors_bg,&$job_set_colors_title,&$job_set_colors_border,&$job_set_colors_job_title,
	&$job_set_colors_text,&$job_set_colors_company,&$job_set_colors_link,&$job_set_colors_source,&$job_set_colors_accent,
	&$job_set_colors_location)
{
 global $possible_job_values;
	if (!in_array($job_format, $possible_job_values["job_format"]["values"])) $job_format = $possible_job_values["job_format"]["default"];
	if (!in_array($job_search, $possible_job_values["job_search"]["values"])) $job_search = $possible_job_values["job_search"]["default"];
	if (!in_array($job_type, $possible_job_values["job_type"]["values"])) $job_type = $possible_job_values["job_type"]["default"];
	if (!in_array($job_show_from, $possible_job_values["job_show_from"]["values"])) $job_show_from = $possible_job_values["job_show_from"]["default"];
	if (!in_array($job_exclude_staffing, $possible_job_values["job_exclude_staffing"]["values"])) $job_exclude_staffing = $possible_job_values["job_exclude_staffing"]["default"];
	if (!in_array($job_where, $possible_job_values["job_where"]["values"])) $job_where = $possible_job_values["job_where"]["default"];
	if (!check_int($jobroll_publisher_id)) $jobroll_publisher_id = "";
	if (!check_int($job_channel)) $job_channel = "0";
	if (!in_array($job_sr_colors, $possible_job_values["job_sr_colors"]["values"])) $job_sr_colors = $possible_job_values["job_sr_colors"]["default"];
	$standard_colors = get_jobroll_db_settings();
	if (!check_color($job_set_colors_bg)) $job_set_colors_bg = $standard_colors["job_set_colors_bg"]["value"];
	if (!check_color($job_set_colors_title)) $job_set_colors_title = $standard_colors["job_set_colors_title"]["value"];
	if (!check_color($job_set_colors_border)) $job_set_colors_border = $standard_colors["job_set_colors_border"]["value"];
	if (!check_color($job_set_colors_text)) $job_set_colors_text = $standard_colors["job_set_colors_text"]["value"];
	if (!check_color($job_set_colors_company)) $job_set_colors_company = $standard_colors["job_set_colors_company"]["value"];
	if (!check_color($job_set_colors_link)) $job_set_colors_link = $standard_colors["job_set_colors_link"]["value"];
	if (!check_color($job_set_colors_source)) $job_set_colors_source = $standard_colors["job_set_colors_source"]["value"];
	if (!check_color($job_set_colors_accent)) $job_set_colors_accent = $standard_colors["job_set_colors_accent"]["value"];
	if (!check_color($job_set_colors_location)) $job_set_colors_location = $standard_colors["job_set_colors_location"]["value"];
}

function fill_job_search_params(&$job_format,&$job_search,&$job_what,&$job_with_all,&$job_exact_phrase,&$job_at_least_one,&$job_none,
	&$job_title,&$job_company,&$job_type,&$job_show_from,&$job_exclude_staffing,&$job_where,&$job_country,&$job_city_state)
{
 global $AdsJobRollCnt, $job_search_params;
	$job_search_params["error_code"] = "none";
	$job_search_params["search_type"] = ($job_search == "job_search_advanced") ? "advanced" : "simple";
	//Get search params (Simple search values)
	$job_search_params["what"]					= $job_what;				//what - search keyword
	//Get search params (Advenced search values)
	$job_search_params["as_all"]				= $job_with_all;			//with all of these words
	$job_search_params["as_phrase"]			= $job_exact_phrase;		//with the exact phrase
	$job_search_params["as_any"]				= $job_at_least_one;			//with at least one of these words
	$job_search_params["as_not"]				= $job_none;			//without the words
	$job_search_params["as_title"]			= $job_title;		//with these words in the title
	$job_search_params["as_company"]		= $job_company;	//from this company
		/*not used in jobroll:*/ $job_search_params["radius"] = 0;				//within
		/*not used in jobroll:*/ $job_search_params["jobs_category"] = 0;//related to category
	$job_search_params["jobs_type"]			= $job_type;		//show jobs of type
	$job_search_params["jobs_from"]			= $job_show_from;		//show jobs from
	$job_search_params["norecruiters"]	= $job_exclude_staffing;	//exclude staffing agencies
  	/*not used in jobroll:*/ $job_search_params["salary"] = "";			//salary estimate
		/*not used in jobroll:*/ $job_search_params["jobs_published"] = "any";//jobs published (anytime, within 30 days, ..., since my last visit)
	//Get search params (Common search values)
	switch($job_where) {
		case "job_where_viewers_location": $job_search_params["where_jobroll_location"] = 1; $job_search_params["where"] = get_viewers_location(); break;
		case "job_where_set_location": $job_search_params["where"] = $job_city_state;	break; //$job_where - radio buttons;	//where (location): city, state, or zip
	}
	//number results per page
	switch($job_format) {
		case "120x600":
		case "160x600":
		case "728x90":
			$job_search_params["number_results"] = $AdsJobRollCnt; break;
		case "300x250":
			$job_search_params["number_results"] = 1; break;;
		default:
			$job_search_params["number_results"] = $AdsJobRollCnt;
	}
	/*not used in jobroll: default - relevant:*/ $job_search_params["sort_by"] = "";				//sort jobs by (relevance, date). Empty means - relevance
	$job_search_params["job_country"]			= $job_country;		//job country (for jobroll)
	$job_search_params["job_where"]				= $job_where;			//job where (for jobroll): viewers location or set location
	$job_search_params["job_city_state"]	= $job_city_state;//job city state (for jobroll): city, state (province) or zip code:

	//+ Locations search values
	$job_search_params["title"]	= $job_search_params["company_name"] = "";
}

//Return Jobroll values
function get_search_term_str()
{
 global $job_what,$job_with_all,$job_exact_phrase,$job_at_least_one,$job_none,$job_title,$job_company,$job_where,$job_city_state;
 global $text_info;
	$str = "";
	if ($job_what != "") $str .= $job_what.' ';
	if ($job_with_all != "") $str .= $job_with_all.' ';
	if ($job_exact_phrase != "") $str .= '"'.$job_exact_phrase.'" ';
	if ($job_with_all != "") $str .= $job_with_all.' ';
	if ($job_at_least_one != "") {
		$job_at_least_one = ereg_replace(" +", " ", $job_at_least_one);
		$job_at_least_one_items = explode(" ", $job_at_least_one);
		$str .= '('.implode(" OR ",$job_at_least_one_items).') ';
	}
	if ($job_none != "") {
		$job_none = ereg_replace(" +", " ", $job_none);
		$job_none_items = explode(" ", $job_none);
		$str .= implode(" -",$job_none_items);
	}
	if ($job_title != "") $str .= $text_info["p_title"].' '.$job_title.' ';
	if ($job_company != "") $str .= $text_info["p_company"].' '.$job_company.' ';
	if ( ($job_where == "job_where_set_location") && ($job_city_state != "") ) $str .= $text_info["p_in"].' '.$job_city_state.' ';
	if (strlen($str) > 0) $str .= $text_info["p_jobs"];
	else $str = $text_info["p_casinojobs_jobs"];
 return $str;
}

function get_jobroll_settings()
{
 global $job_sr_colors,$job_set_colors_bg,$job_set_colors_title,$job_set_colors_border,$job_set_colors_job_title,
	$job_set_colors_text,$job_set_colors_company,$job_set_colors_link,$job_set_colors_source,$job_set_colors_accent,
	$job_set_colors_location;
 return array("colors"=>$job_sr_colors,"colors_bg"=>$job_set_colors_bg,"colors_title"=>$job_set_colors_title,
			"colors_border"=>$job_set_colors_border,"colors_job_title"=>$job_set_colors_job_title,
			"colors_text"=>$job_set_colors_text,"colors_company"=>$job_set_colors_company,
			"colors_link"=>$job_set_colors_link,"colors_source"=>$job_set_colors_source,
			"colors_accent"=>$job_set_colors_accent,"colors_location"=>$job_set_colors_location,
			"search_term"=>get_search_term_str());
}

function get_jobroll_db_settings()
{
 global $db_tables;
	$sql = "SELECT * ".
				"FROM ".$db_tables["jobrollsettings"];

	// * * Check cache * * //
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_template_values",
		"table_name"	=> "jobrollsettings",
		"params_list"	=> array(""), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 30*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);

	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[$myrow["settings_name"]] = array("value"=>$myrow["settings_value"]);
		}
	}
	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);

 return $data_array;
}

function check_ads_show_job_search()
{
 global $job_search_params;
	if (($job_search_params["what"] == "") && ($job_search_params["jobs_category"] == "") &&
			($job_search_params["as_all"] == "") && ($job_search_params["as_phrase"] == "") && ($job_search_params["as_any"] == "") && 
			($job_search_params["as_not"] == "") && ($job_search_params["as_title"] == "") && ($job_search_params["as_company"] == "")
			) return false;
	else return true;
}

doconnect();

//Check settings
get_global_settings();

//Get Jobroll values
get_cur_values($job_format,$job_search,$job_what,$job_with_all,$job_exact_phrase,$job_at_least_one,$job_none,
	$job_title,$job_company,$job_type,$job_show_from,$job_exclude_staffing,$job_where,$job_country,$job_city_state,$jobroll_publisher_id,
	$job_channel,$job_sr_colors,$job_set_colors_bg,$job_set_colors_title,$job_set_colors_border,$job_set_colors_job_title,
	$job_set_colors_text,$job_set_colors_company,$job_set_colors_link,$job_set_colors_source,$job_set_colors_accent,
	$job_set_colors_location);

//Check Jobroll values
check_cur_values($job_format,$job_search,$job_what,$job_with_all,$job_exact_phrase,$job_at_least_one,$job_none,
	$job_title,$job_company,$job_type,$job_show_from,$job_exclude_staffing,$job_where,$job_country,$job_city_state,$jobroll_publisher_id,
	$job_channel,$job_sr_colors,$job_set_colors_bg,$job_set_colors_title,$job_set_colors_border,$job_set_colors_job_title,
	$job_set_colors_text,$job_set_colors_company,$job_set_colors_link,$job_set_colors_source,$job_set_colors_accent,
	$job_set_colors_location);

//Velues for jobroll template
$JobChannel = $job_channel;
$JobrollPublisherID = $jobroll_publisher_id;

//Null search session values
null_search_sess_values();

//Get search data
fill_job_search_params($job_format,$job_search,$job_what,$job_with_all,$job_exact_phrase,$job_at_least_one,$job_none,
	$job_title,$job_company,$job_type,$job_show_from,$job_exclude_staffing,$job_where,$job_country,$job_city_state);

//Check search data
check_job_search_params($job_search_params);

//Check keyword
$_SESSION["sess_job_search"]["current_search_mode"] = "base_search";
$job_list = $adv_job_list = array();
if (($job_search_params["error_code"] == "empty_keyword") || !check_ads_show_job_search())
	//Made random job search
	do_random_job_search($job_search_params,$job_list,$job_search_params["number_results"]);
else
	//Made common job search
	do_job_search($job_search_params,$job_list,$adv_job_list,$result,1);

//Set statistic: jobroll was viewed
set_stats_pub_pageview($jobroll_publisher_id,$job_channel);

//Check results count
if (count($job_list) == 0) create_empty_jobroll_page();

//Design job search
do_jobroll_design($job_search_params,$job_list);

//Check location
if ($job_search_params["error_code"] == "empty_location") create_empty_jobroll_page();

// * * Check cache * * //
$template_id = 18;
$sql = "SELECT * FROM ".$db_tables["template_values"]." WHERE template_id='$template_id'";
$cache_params_array = array(
	"user"				=> 3, //$_SESSION["sess_user"]
	"cache_group"	=> "smarty_frontend",
	"userid"			=> 0, //$_SESSION["sess_userid"]
	"section"			=> "search_template_values",
	"table_name"	=> "template_values",
	"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
	"query"				=> $sql,
	"actual_time"	=> 35*60, //Время актуальности в сек.
	"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
);
prepare_template_values($cache_params_array,$template_id);
$smarty->display('jobrollpage.tpl');
?>