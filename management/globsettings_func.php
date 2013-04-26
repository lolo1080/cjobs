<?
function create_values($site_title,$site_url,$adv_start_balance,$min_adv_cost_per_click,$max_adv_headline_length,
		$max_adv_line1_length,$max_adv_line2_length,$amount_of_listings,$amount_of_adv_sponsor_jobs_top,$amount_of_adv_sponsor_jobs_bottom,
		$amount_of_adv_keyword_ads,$member_approved,$window_target,$pub_start_balance,$xml_pub_approved,$pub_referal_percent,
		$use_stats_cache,$cache_actualtime_admin,$cache_actualtime_adv,$cache_actualtime_pub,$use_frontend_cache,
		$cache_frontend_actualtime_pages,$cache_frontend_actualtime_primitives,$earn_ip_protection,
		$allow_cities_in_db,$allow_cities_not_in_db,$jobs_without_city)
{
 global $smarty,$text_info,$yes_no_array,$target_array,$SLINE;
	//Selectboxes
	$member_approved_selectbox	= get_selectbox_data($yes_no_array,$member_approved);
	$window_target_selectbox		= get_selectbox_data($target_array,$window_target);
	$xml_pub_approved_selectbox	= get_selectbox_data($yes_no_array,$xml_pub_approved);
	$use_stats_cache_selectbox	= get_selectbox_data($yes_no_array,$use_stats_cache);
	$use_frontend_cache_selectbox	= get_selectbox_data($yes_no_array,$use_frontend_cache);
	$allow_cities_in_db_selectbox	= get_selectbox_data($yes_no_array,$allow_cities_in_db);
	$allow_cities_not_in_db_selectbox	= get_selectbox_data($yes_no_array,$allow_cities_not_in_db);
	$jobs_without_city_selectbox	= get_selectbox_data($yes_no_array,$jobs_without_city);

	$FormElements = array(
	//Site Settings
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_site_settings"], "after_html"=>""),
	array("flabel"=>show_cell_caption("site_title"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"site_title", "ereadonly"=>"", "evalue"=>$site_title, "emaxlength"=>"150",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("site_url"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"site_url", "ereadonly"=>"", "evalue"=>$site_url, "emaxlength"=>"200",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),

	//Advertiser Settings
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_advertisers_settings"], "after_html"=>""),
	array("flabel"=>show_cell_caption("adv_start_balance"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"adv_start_balance", "ereadonly"=>"", "evalue"=>$adv_start_balance, "emaxlength"=>"9",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("min_adv_cost_per_click"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"min_adv_cost_per_click", "ereadonly"=>"", "evalue"=>$min_adv_cost_per_click, "emaxlength"=>"9",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("max_adv_headline_length"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"max_adv_headline_length", "ereadonly"=>"", "evalue"=>$max_adv_headline_length, "emaxlength"=>"9",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("max_adv_line1_length"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"max_adv_line1_length", "ereadonly"=>"", "evalue"=>$max_adv_line1_length, "emaxlength"=>"9",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("max_adv_line2_length"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"max_adv_line2_length", "ereadonly"=>"", "evalue"=>$max_adv_line2_length, "emaxlength"=>"9",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),

	//Search Page Settings
	array("isheadline"=>true, "hlclass"=>"form_hlclass","hlmessage"=>$text_info["p_search_page_settings"], "after_html"=>""),
	array("flabel"=>show_cell_caption("amount_of_listings"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"amount_of_listings", "ereadonly"=>"", "evalue"=>$amount_of_listings, "emaxlength"=>"9",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("amount_of_adv_sponsor_jobs_top"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"amount_of_adv_sponsor_jobs_top", "ereadonly"=>"", "evalue"=>$amount_of_adv_sponsor_jobs_top, "emaxlength"=>"9",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("amount_of_adv_sponsor_jobs_bottom"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"amount_of_adv_sponsor_jobs_bottom", "ereadonly"=>"", "evalue"=>$amount_of_adv_sponsor_jobs_bottom, "emaxlength"=>"9",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("amount_of_adv_keyword_ads"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"amount_of_adv_keyword_ads", "ereadonly"=>"", "evalue"=>$amount_of_adv_keyword_ads, "emaxlength"=>"9",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("window_target"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"window_target", "edisabled"=>"", "evalue"=>$window_target_selectbox["val"],
				"eselected"=>$window_target_selectbox["sel"], "ecaption"=>$window_target_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false),

	//Confirmations Settings
	array("isheadline"=>true, "hlclass"=>"form_hlclass","hlmessage"=>$text_info["p_confirmations_settings"], "after_html"=>""),
	array("flabel"=>show_cell_caption("member_approved"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"member_approved", "edisabled"=>"", "evalue"=>$member_approved_selectbox["val"],
				"eselected"=>$member_approved_selectbox["sel"], "ecaption"=>$member_approved_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false),

	//Publisher Settings
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_publishers_settings"], "after_html"=>""),
	array("flabel"=>show_cell_caption("pub_start_balance"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"pub_start_balance", "ereadonly"=>"", "evalue"=>$pub_start_balance, "emaxlength"=>"9",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("xml_pub_approved"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"xml_pub_approved", "edisabled"=>"", "evalue"=>$xml_pub_approved_selectbox["val"],
				"eselected"=>$xml_pub_approved_selectbox["sel"], "ecaption"=>$xml_pub_approved_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false),
	array("flabel"=>show_cell_caption("pub_referal_percent"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"pub_referal_percent", "ereadonly"=>"", "evalue"=>$pub_referal_percent, "emaxlength"=>"9",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),

	//Cache Settings
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_cache_settings"], "after_html"=>""),
	array("flabel"=>show_cell_caption("use_stats_cache"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"use_stats_cache", "edisabled"=>"", "evalue"=>$use_stats_cache_selectbox["val"],
				"eselected"=>$use_stats_cache_selectbox["sel"], "ecaption"=>$use_stats_cache_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false),
	array("flabel"=>show_cell_caption("cache_actualtime_admin"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"cache_actualtime_admin", "ereadonly"=>"", "evalue"=>$cache_actualtime_admin, "emaxlength"=>"20",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("cache_actualtime_adv"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"cache_actualtime_adv", "ereadonly"=>"", "evalue"=>$cache_actualtime_adv, "emaxlength"=>"20",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("cache_actualtime_pub"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"cache_actualtime_pub", "ereadonly"=>"", "evalue"=>$cache_actualtime_pub, "emaxlength"=>"20",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("use_frontend_cache"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"use_frontend_cache", "edisabled"=>"", "evalue"=>$use_frontend_cache_selectbox["val"],
				"eselected"=>$use_frontend_cache_selectbox["sel"], "ecaption"=>$use_frontend_cache_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false),
	array("flabel"=>show_cell_caption("cache_frontend_actualtime_pages"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"cache_frontend_actualtime_pages", "ereadonly"=>"", "evalue"=>$cache_frontend_actualtime_pages, "emaxlength"=>"20",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("cache_frontend_actualtime_primitives"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"cache_frontend_actualtime_primitives", "ereadonly"=>"", "evalue"=>$cache_frontend_actualtime_primitives, "emaxlength"=>"20",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),

	//Earn IP Protection
	array("isheadline"=>true, "hlclass"=>"form_hlclass","hlmessage"=>$text_info["p_ip_protection"], "after_html"=>""),
	array("flabel"=>show_cell_caption("earn_ip_protection"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"earn_ip_protection", "ereadonly"=>"", "evalue"=>$earn_ip_protection, "emaxlength"=>"9",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),

	//Job location
	array("isheadline"=>true, "hlclass"=>"form_hlclass","hlmessage"=>$text_info["p_job_location"], "after_html"=>""),
	array("flabel"=>show_cell_caption("allow_cities_in_db"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"allow_cities_in_db", "edisabled"=>"", "evalue"=>$allow_cities_in_db_selectbox["val"],
				"eselected"=>$allow_cities_in_db_selectbox["sel"], "ecaption"=>$allow_cities_in_db_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false),
	array("flabel"=>show_cell_caption("allow_cities_not_in_db"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"allow_cities_not_in_db", "edisabled"=>"", "evalue"=>$allow_cities_not_in_db_selectbox["val"],
				"eselected"=>$allow_cities_not_in_db_selectbox["sel"], "ecaption"=>$allow_cities_not_in_db_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false),
	array("flabel"=>show_cell_caption("jobs_without_city"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"jobs_without_city", "edisabled"=>"", "evalue"=>$jobs_without_city_selectbox["val"],
				"eselected"=>$jobs_without_city_selectbox["sel"], "ecaption"=>$jobs_without_city_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false),
	);

	$smarty->assign("FormElements",$FormElements);
}

function update_data($site_title,$site_url,$adv_start_balance,$min_adv_cost_per_click,$max_adv_headline_length,
		$max_adv_line1_length,$max_adv_line2_length,$amount_of_listings,$amount_of_adv_sponsor_jobs_top,$amount_of_adv_sponsor_jobs_bottom,
		$amount_of_adv_keyword_ads,$member_approved,$window_target,$pub_start_balance,$xml_pub_approved,$pub_referal_percent,
		$use_stats_cache,$cache_actualtime_admin,$cache_actualtime_adv,$cache_actualtime_pub,$use_frontend_cache,$cache_frontend_actualtime_pages,
		$cache_frontend_actualtime_primitives,$earn_ip_protection,$allow_cities_in_db,$allow_cities_not_in_db,$jobs_without_city)
{
 global $db_tables;
	//Delete old GlobSettings values
	mysql_query("DELETE FROM ".$db_tables["globsettings"]) or query_die(__FILE__,__LINE__,mysql_error());

	//Insert new  GlobSettings values
	foreach ($_SESSION["globsettings"] as $k=>$v)
	{
		if (isset($$k)) mysql_query("INSERT INTO ".$db_tables["globsettings"]." VALUES('$k','".$$k."')") or query_die(__FILE__,__LINE__,mysql_error());
		elseif ($k != "selected_country") trigger_error("File: ".__FILE__." Line: ".__LINE__.". Cannot find GlobSettings value: ".$k);
	}

	get_global_settings();

	//Send event
	$event_array = array("event"=>"insert", "source"=>"globsettings", "table"=>"globsettings", "ad_id"=>0);
	event_handler($event_array);
}

function try_change()
{
 global $Error_messages,$yes_no_array,$target_array,$display_rank_array,$affiliate_type_array;
	$my_error = "";

	//Get values
	$site_title								= html_chars(get_post_value("site_title",""));
	$site_url									= html_chars(get_post_value("site_url",""));
	$adv_start_balance				= html_chars(get_post_value("adv_start_balance",""));
	$min_adv_cost_per_click		= html_chars(get_post_value("min_adv_cost_per_click",""));
	$max_adv_headline_length	= html_chars(get_post_value("max_adv_headline_length",""));
	$max_adv_line1_length			= html_chars(get_post_value("max_adv_line1_length",""));
	$max_adv_line2_length			= html_chars(get_post_value("max_adv_line2_length",""));
	$amount_of_listings				= html_chars(get_post_value("amount_of_listings",""));
	$amount_of_adv_sponsor_jobs_top = html_chars(get_post_value("amount_of_adv_sponsor_jobs_top",""));
	$amount_of_adv_sponsor_jobs_bottom = html_chars(get_post_value("amount_of_adv_sponsor_jobs_bottom",""));
	$amount_of_adv_keyword_ads= html_chars(get_post_value("amount_of_adv_keyword_ads",""));
	$member_approved					= html_chars(get_post_value("member_approved",""));
	$window_target						= html_chars(get_post_value("window_target",""));
	$pub_start_balance				= html_chars(get_post_value("pub_start_balance",""));
	$xml_pub_approved					= html_chars(get_post_value("xml_pub_approved",""));
	$pub_referal_percent			= html_chars(get_post_value("pub_referal_percent",""));
	$use_stats_cache					= html_chars(get_post_value("use_stats_cache",""));
	$cache_actualtime_admin		= html_chars(get_post_value("cache_actualtime_admin",""));
	$cache_actualtime_adv			= html_chars(get_post_value("cache_actualtime_adv",""));
	$cache_actualtime_pub			= html_chars(get_post_value("cache_actualtime_pub",""));
	$use_frontend_cache				= html_chars(get_post_value("use_frontend_cache",""));
	$cache_frontend_actualtime_pages	= html_chars(get_post_value("cache_frontend_actualtime_pages",""));
	$cache_frontend_actualtime_primitives	= html_chars(get_post_value("cache_frontend_actualtime_primitives",""));
	$earn_ip_protection				= html_chars(get_post_value("earn_ip_protection",""));
	$allow_cities_in_db				= html_chars(get_post_value("allow_cities_in_db",""));
	$allow_cities_not_in_db		= html_chars(get_post_value("allow_cities_not_in_db",""));
	$jobs_without_city				= html_chars(get_post_value("jobs_without_city",""));

	//Check values on emptiness
	$vallist = array($site_title,$site_url,$adv_start_balance,$min_adv_cost_per_click,$max_adv_headline_length,
		$max_adv_line1_length,$max_adv_line2_length,$amount_of_listings,$amount_of_adv_sponsor_jobs_top,$amount_of_adv_sponsor_jobs_bottom,
		$amount_of_adv_keyword_ads,$member_approved,$window_target,$pub_start_balance,$xml_pub_approved,
		$pub_referal_percent,$use_stats_cache,$cache_actualtime_admin,$cache_actualtime_adv,$cache_actualtime_pub,
		$use_frontend_cache,$cache_frontend_actualtime_pages,$cache_frontend_actualtime_primitives,$earn_ip_protection,
		$allow_cities_in_db,$allow_cities_not_in_db,$jobs_without_city);
	$errlist = array($Error_messages["no_site_title"],	$Error_messages["no_site_url"],
		$Error_messages["no_adv_start_balance"],					$Error_messages["no_min_adv_cost_per_click"],
		$Error_messages["no_max_adv_headline_length"],		$Error_messages["no_max_adv_line1_length"],
		$Error_messages["no_max_adv_line2_length"],				$Error_messages["no_amount_of_listings"],
		$Error_messages["no_amount_of_adv_sponsor_jobs_top"],	$Error_messages["no_amount_of_adv_sponsor_jobs_bottom"],	
		$Error_messages["no_amount_of_adv_keyword_ads"],
		$Error_messages["no_member_approved"],
		$Error_messages["no_window_target"],              $Error_messages["no_pub_start_balance"],
		$Error_messages["no_xml_pub_approved"],           $Error_messages["no_pub_referal_percent"],
		$Error_messages["no_use_stats_cache"],            $Error_messages["no_cache_actualtime_admin"],
		$Error_messages["no_cache_actualtime_adv"],       $Error_messages["no_cache_actualtime_pub"],
		$Error_messages["no_use_frontend_cache"],       	$Error_messages["no_cache_frontend_actualtime_pages"],
		$Error_messages["no_cache_frontend_actualtime_primitives"],$Error_messages["no_earn_ip_protection"],
		$Error_messages["no_allow_cities_in_db"],					$Error_messages["no_allow_cities_not_in_db"],
		$Error_messages["no_jobs_without_city"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)

	//Check values on a correctness
	$vallist = array($member_approved,$window_target,$xml_pub_approved,$use_stats_cache,$use_frontend_cache,
		$allow_cities_in_db,$allow_cities_not_in_db,$jobs_without_city);
	$errlist = array($Error_messages["invalid_member_approved"],
				$Error_messages["invalid_window_target"],								$Error_messages["invalid_xml_pub_approved"],
				$Error_messages["invalid_use_stats_cache"],							$Error_messages["invalid_use_frontend_cache"],
				$Error_messages["invalid_allow_cities_in_db"],					$Error_messages["invalid_allow_cities_not_in_db"],
				$Error_messages["invalid_jobs_without_city"]);
	$check_array = array($yes_no_array,$target_array,$yes_no_array,$yes_no_array,$yes_no_array,$yes_no_array,$yes_no_array,$yes_no_array);
	is_not_array($vallist,$errlist,$check_array,$my_error); //Check values on a correctness (function)

	//Check URLs
	is_url($site_url,$Error_messages["invalid_site_url"],$my_error);

	//Check float value above zero values
	if ($my_error == "") {
		$vallist = array($adv_start_balance,$min_adv_cost_per_click,$pub_start_balance,$pub_referal_percent);
		$errlist = array($Error_messages["invalid_adv_start_balance"],$Error_messages["invalid_min_adv_cost_per_click"],
					$Error_messages["invalid_pub_start_balance"],$Error_messages["invalid_pub_referal_percent"]);
		is_float_above_zero($vallist,$errlist,$my_error); //Check float value above zero values (function)
	}

	//Check int value < 2
	if ($my_error == "") {
		$vallist = array($max_adv_headline_length,$max_adv_line1_length,$max_adv_line2_length);
		$errlist = array($Error_messages["invalid_adv_start_balance"],$Error_messages["invalid_min_adv_cost_per_click"],
					$Error_messages["invalid_pub_start_balance"]);
		is_int_above_num(2,$vallist,$errlist,$my_error); //Check int value above 0 values (function)
	}

	//Check int value < 1
	if ($my_error == "") {
		$vallist = array($amount_of_listings,$amount_of_adv_sponsor_jobs_top,$amount_of_adv_sponsor_jobs_bottom,$amount_of_adv_keyword_ads,
					$cache_actualtime_admin,$cache_actualtime_adv,$cache_actualtime_pub,$cache_frontend_actualtime_pages,
					$cache_frontend_actualtime_primitives,$earn_ip_protection);
		$errlist = array($Error_messages["invalid_amount_of_listings"],$Error_messages["invalid_amount_of_adv_sponsor_jobs_top"],
					$Error_messages["invalid_amount_of_adv_sponsor_jobs_bottom"],
					$Error_messages["invalid_amount_of_adv_keyword_ads"],$Error_messages["invalid_cache_actualtime_admin"],
					$Error_messages["invalid_cache_actualtime_adv"],$Error_messages["invalid_cache_actualtime_pub"],
					$Error_messages["invalid_cache_frontend_actualtime_pages"],$Error_messages["invalid_cache_frontend_actualtime_primitives"]);
		is_int_above_num(1,$vallist,$errlist,$my_error); //Check int value above 0 values (function)
	}

	//If no errors - save data
	if ($my_error == "") {
		$site_title									= data_addslashes($site_title);
		$site_url										= data_addslashes($site_url);
		$adv_start_balance					= data_addslashes($adv_start_balance);
		$min_adv_cost_per_click			= data_addslashes($min_adv_cost_per_click);
		$max_adv_headline_length		= data_addslashes($max_adv_headline_length);
		$max_adv_line1_length				= data_addslashes($max_adv_line1_length);
		$max_adv_line2_length				= data_addslashes($max_adv_line2_length);
		$amount_of_listings					= data_addslashes($amount_of_listings);
		$amount_of_adv_sponsor_jobs_top	= data_addslashes($amount_of_adv_sponsor_jobs_top);
		$amount_of_adv_sponsor_jobs_bottom	= data_addslashes($amount_of_adv_sponsor_jobs_bottom);
		$amount_of_adv_keyword_ads	= data_addslashes($amount_of_adv_keyword_ads);
		$member_approved						= data_addslashes($member_approved);
		$window_target							= data_addslashes($window_target);
		$pub_start_balance					= data_addslashes($pub_start_balance);
		$xml_pub_approved						= data_addslashes($xml_pub_approved);
		$pub_referal_percent				= data_addslashes($pub_referal_percent);
		$use_stats_cache						= data_addslashes($use_stats_cache);
		$cache_actualtime_admin			= data_addslashes($cache_actualtime_admin);
		$cache_actualtime_adv				= data_addslashes($cache_actualtime_adv);
		$cache_actualtime_pub				= data_addslashes($cache_actualtime_pub);
		$use_frontend_cache					= data_addslashes($use_frontend_cache);
		$cache_frontend_actualtime_pages	= data_addslashes($cache_frontend_actualtime_pages);
		$cache_frontend_actualtime_primitives	= data_addslashes($cache_frontend_actualtime_primitives);
		$earn_ip_protection					= data_addslashes($earn_ip_protection);
		$allow_cities_in_db					= data_addslashes($allow_cities_in_db);
		$allow_cities_not_in_db			= data_addslashes($allow_cities_not_in_db);
		$jobs_without_city					= data_addslashes($jobs_without_city);

		//Update data
		update_data($site_title,$site_url,$adv_start_balance,$min_adv_cost_per_click,$max_adv_headline_length,$max_adv_line1_length,
			$max_adv_line2_length,$amount_of_listings,$amount_of_adv_sponsor_jobs_top,$amount_of_adv_sponsor_jobs_bottom,
			$amount_of_adv_keyword_ads,$member_approved,$window_target,$pub_start_balance,$xml_pub_approved,
			$pub_referal_percent,$use_stats_cache,$cache_actualtime_admin,$cache_actualtime_adv,$cache_actualtime_pub,
			$use_frontend_cache,$cache_frontend_actualtime_pages,$cache_frontend_actualtime_primitives,$earn_ip_protection,
			$allow_cities_in_db,$allow_cities_not_in_db,$jobs_without_city);
	}
	else {//else - try again
		smarty_create_message("error","abort.gif",$my_error);
	}
	create_values($site_title,$site_url,$adv_start_balance,$min_adv_cost_per_click,$max_adv_headline_length,$max_adv_line1_length,
			$max_adv_line2_length,$amount_of_listings,$amount_of_adv_sponsor_jobs_top,$amount_of_adv_sponsor_jobs_bottom,
			$amount_of_adv_keyword_ads,$member_approved,$window_target,$pub_start_balance,$xml_pub_approved,
			$pub_referal_percent,$use_stats_cache,$cache_actualtime_admin,$cache_actualtime_adv,$cache_actualtime_pub,
			$use_frontend_cache,$cache_frontend_actualtime_pages,$cache_frontend_actualtime_primitives,$earn_ip_protection,
			$allow_cities_in_db,$allow_cities_not_in_db,$jobs_without_city);
}
?>