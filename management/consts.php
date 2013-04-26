<?php

/*
#################
# consts.php
# Constant values
# (Main)
#################
# Tab size = 2
#################
*/

//Housekeeping information
$dbhost = "localhost";
$dbuser = "root";
$dbpassword = "";
$dbname = "casinojobs";

// - - - - - - - - - - - - - - -//
// * * Database tables list * * //
$db_tables = array(
	"admins"											=> "esjobsearchengine_admins",
	"attach"											=> "esjobsearchengine_attach",
	"mailsubject"									=> "esjobsearchengine_mailsubject",
	"users_advertiser"						=> "esjobsearchengine_users_advertiser",
	"users_publisher"							=> "esjobsearchengine_users_publisher",
	"users_member"								=> "esjobsearchengine_users_member",
	"globsettings"								=> "esjobsearchengine_globsettings",
	"jobrollsettings"							=> "esjobsearchengine_jobrollsettings",
	"paymentsettings"							=> "esjobsearchengine_paymentsettings",
	"users_advertiser_settings"		=> "esjobsearchengine_users_advertiser_settings",
	"users_publisher_settings"		=> "esjobsearchengine_users_publisher_settings",
	"users_publisher_channels"		=> "esjobsearchengine_users_publisher_channels",
	"users_confirm_email"					=> "esjobsearchengine_users_confirm_email",
	"users_submissions"						=> "esjobsearchengine_users_submissions",
	"users_member_settings"				=> "esjobsearchengine_users_member_settings",
	"member_job_alerts"						=> "esjobsearchengine_member_job_alerts",
	"country"											=> "esjobsearchengine_country",
	"state"												=> "esjobsearchengine_state",
	"city"												=> "esjobsearchengine_city",
	"region"											=> "esjobsearchengine_region",
	"city_ip"											=> "esjobsearchengine_city_ip",
	"ipfirewall"									=> "esjobsearchengine_ipfirewall",
	"ads"													=> "esjobsearchengine_ads",
	"keyword_ads"									=> "esjobsearchengine_keyword_ads",
	"job_ads"											=> "esjobsearchengine_job_ads",

	"payments_adv"								=> "esjobsearchengine_payments_adv",
	"payments_tmp_adv"						=> "esjobsearchengine_payments_tmp_adv",
	"payments_adv_stored_cc"			=> "esjobsearchengine_payments_adv_stored_cc",
	"payments_pub"								=> "esjobsearchengine_payments_pub",
	"payments_tmp_pub"						=> "esjobsearchengine_payments_tmp_pub",

	"stats_visitor_info"					=> "esjobsearchengine_stats_visitor_info",
	"stats_search_keywords"				=> "esjobsearchengine_stats_search_keywords",
	"stats_search_success_keywords"=> "esjobsearchengine_stats_search_success_keywords",
	"stats_clicks"								=> "esjobsearchengine_stats_clicks",
	"stats_adv_pageview_keywords"	=> "esjobsearchengine_stats_adv_pageview_keywords",
	"stats_adv_pageview_jobs"			=> "esjobsearchengine_stats_adv_pageview_jobs",
	"stats_adv_maybe_pageview_keywords"	=> "esjobsearchengine_stats_adv_maybe_pageview_keywords",
	"stats_adv_maybe_pageview_jobs"			=> "esjobsearchengine_stats_adv_maybe_pageview_jobs",
	"stats_adv_click_keywords"		=> "esjobsearchengine_stats_adv_click_keywords",
	"stats_adv_click_jobs"				=> "esjobsearchengine_stats_adv_click_jobs",

	"stats_pub_pageview"					=> "esjobsearchengine_stats_pub_pageview",
	"stats_pub_click_keywords"		=> "esjobsearchengine_stats_pub_click_keywords",
	"stats_pub_earn_clicks"				=> "esjobsearchengine_stats_pub_earn_clicks",
	"stats_earned_ips"						=> "esjobsearchengine_stats_earned_ips",

	"templates"										=> "esjobsearchengine_templates",
	"template_values"							=> "esjobsearchengine_template_values",

	"jobcategories"								=> "esjobsearchengine_jobcategories",

	"sites_feed_list"							=> "esjobsearchengine_sites_feed_list",
	"sites_feed_log"							=> "esjobsearchengine_sites_feed_log",
	"sites_feed_alert_emials"			=> "esjobsearchengine_sites_feed_alert_emials",
	"data_list"										=> "esjobsearchengine_data_list",
	"data_list_stats"							=> "esjobsearchengine_data_list_stats",
	"data_list_advertiser"				=> "esjobsearchengine_data_list_advertiser",
	"data_list_deleted"						=> "esjobsearchengine_data_list_deleted",

	"xml_feeds_configuration"			=> "esjobsearchengine_xml_feeds_configuration",
	"xml_feeds_data"							=> "esjobsearchengine_xml_feeds_data",
	"xml_feeds_data_temp"					=> "esjobsearchengine_xml_feeds_data_temp",
	"html_feeds_configuration"		=> "esjobsearchengine_html_feeds_configuration",
	"html_feeds_data"							=> "esjobsearchengine_html_feeds_data",
	"html_feeds_data_temp"				=> "esjobsearchengine_html_feeds_data_temp",
	"common_php_code"							=> "esjobsearchengine_common_php_code",
	"xml2_feeds_data"							=> "esjobsearchengine_xml2_feeds_data",
	"xml2_feeds_data_temp"				=> "esjobsearchengine_xml2_feeds_data_temp",
	"xml2_feeds_category_keywords"=> "esjobsearchengine_xml2_feeds_category_keywords",

	"browse_keyword"							=> "esjobsearchengine_browse_keyword",
	"browse_keyword_temp"					=> "esjobsearchengine_browse_keyword_temp",
	"browse_keyword_most_popular"	=> "esjobsearchengine_browse_keyword_most_popular",
	"browse_keyword_most_popular_temp"	=> "esjobsearchengine_browse_keyword_most_popular_temp"
);

$usersettings["timezone_identifier"] = "America/New_York"; //The timezone identifier for "date_default_timezone_set()" function: "America/New_York" | "Europe/Moscow" | "Europe/Kiev"
																													//please, see possible values here: http://php.net/manual/en/timezones.php

// - - - - - - - - - - - - - - - //
// * * Directory information * * //
$script_dir		= dirname(__FILE__);
$mail_dir			= $script_dir."/mail/";
$news_filename= $script_dir."/news/news.html";
$help_dir			= $script_dir."/lang/help/";
$htmltemplate_dir = $script_dir."/htmltemplate/"; //path to html template files
$frontend_dir = $script_dir."/../frontend/"; //path to front-end
$frontend_template_dir = $script_dir."/../frontend/templates/"; //path to front-end html templates
$frontend_phpscripts_dir = $script_dir."/../"; //path to front-end php scripts (these scripts will show templates)
$bug_report_email = "michael@energyscripts.com"; //Bug report E-mail: bugreports@energyscripts.com
$cron_dir			= $script_dir."/cron/"; //path to cron dir
$sitemap_dir	= $script_dir."/../"; //path to sitemap dir: should be root folder always (not $script_dir."/../sitemap/")


// - - - - - - - - - - - - - - - -//
// * * OS devider information * * //
$os_devider["unix"] = "/";
$os_devider["windows"] = "\\";


// - - - - - - - - - - - - - //
// * * Log file settings * * //
//1) payment log settings
$log_info["payment_errors"]	= $script_dir."/logs/payment_errors.txt";
$log_info["payment_history"]= $script_dir."/logs/payment_history.txt";
$log_info["use_payment_log"]	= true; //Write logs in log file (true(1) == yes, false(0) == no)
//2) application errors log settings
$log_info["application_errors"]	= $script_dir."/logs/application_errors.txt";
$log_info["application_maxsize"] = 1024; //max application log file size (in KB)
$log_info["application_log_rotate"]	= true; //what to do after we have file more then "maxsize" (rewrite or rotate)
$log_info["use_apperrors_log"]	= true; //Write logs in log file (true(1) == yes, false(0) == no)
	// send allpication errors to e-mail settings
$log_info["send_email_error_notification"] = true; //Sent error notification to developer e-mail (true(1) == yes, false(0) == no)
$log_info["email_error_notification_timeout"] = 750; //Timeout (in sec.) before e-mail sending
$log_info["last_email_error_notification_time_file"] = $script_dir."/logs/email_timeout.txt";
$log_info["email_error_notification_subject"] = "ES Job Search Engine error notification"; //Subject for error message
$log_info["email_se_error_notification_subject"] = "ES Job Search Engine error notification"; //Subject for error message for Search Engine applicaion
	// developer mode - show all errors in browser
$log_info["use_developer_error_mode"]	= true; //Show all errors in Browser


// - - - - - - - - - - - - - - -//
// * * Cache folder settings ** //
$cache_info["cache_dir"] = $script_dir."/cache/";
$cache_info["use_cache_default"] = true; //если не установлена переменная сесси использовать это значение


//Script information
$html_template_file			= $script_dir."/htmltemplate/gethtml.tpl";
$xml_template_file			= $script_dir."/htmltemplate/xmlfeed.tpl";
$banner_template_file		= $script_dir."/htmltemplate/getbanner.tpl";

//URL information
$help_url		= "management/lang/help/";
$help_url_no= "management/lang/nohelp.html";
$sitemap_url_part= ""; //sitemap path from root

//Row count
$row_count = 30; //Recordes per page
$view_pages = 5; //Max count items between "<<" , ">>"  ("<<"[$view_pages]">>")

//XML Feed
$pageresult_default = 20; //This is default value for "pageresult" parameter for XML Feed
$startat_default = 1; //This is default value for "startat" parameter for XML Feed

//Browse jobs Page - Keyword
$browse_keyword_sect_cnt = 8;  //First page section count (10)
$browse_keyword_sub_sect_cnt = 3;  //First page section count (5)
$browse_keyword_more_sub_sect_cnt = 8;  //All pages (except first) section count (200)


// - - - - - - - - - - - -//
// * * Crypt settings * * //
$crypt_settings["encryptkey"] = array("private"=>"0d5904T54841N56", "public"=>"2U490J959L27223");


// - - - - - - - - - - - - -//
// * * Payment settings ** //
$payment_types = array("1"=>"Credit Card", "2"=>"PayPal", "3"=>"E-Gold", "4"=>"2checkout");
$payment_systems_array = array("credit_card"=>"Credit Card", "paypal"=>"PayPal", "egold"=>"E-Gold", "2checkout"=>"2checkout");

//Member can not request money using payment systems from $payment_systems_array_withdraw
$payment_systems_array_withdraw = array("clickbank");
$common_payment_systems_info["Currency"] = "USD";
//Credit Card settings
$credit_card_info["cc_script_name"] = "cc_payment_quantumgateway.php"; //www.quantumgateway.com
$credit_card_info["cc_gateway_url"] = "https://secure.quantumgateway.com/cgi/tqgwdbe.php"; //www.quantumgateway.com
//E-gold settings
$egold_info["EGoldMetal"] = 1; //1-Gold; 2-Silver; 3-Platinum; 4-Palladium
$egold_info["EGoldPaymentUnit"] = 1; //1-USD; 2-CAD; 44-GBP; 85-EUR
$egold_info["EGoldStatusURL"]	= "management/include/payment/e-goldstatus.php";
$egold_info["EGoldPayURL"]		= "management/include/payment/e-goldpay.php";
$egold_info["EGoldNoPayURL"]	= "management/include/payment/nopay.php";
//PayPal settings
$paypal_info["PayPalCurrency"] = "USD"; //USD; CAD; GBP; EUR;
$paypal_info["PayPalNotifyURL"]	= "management/include/payment/paypalstatus.php";
$paypal_info["PayPalPayURL"]		= "management/include/payment/paypalpay.php";
$paypal_info["PayPalNoPayURL"]	= "management/include/payment/nopay.php";
$paypal_info["AdminReturnPayPalURL"] = "management/include/payment/paypalstatus_admin.php";
$paypal_info["AdminPayPalNoPayURL"]	= "management/include/payment/nopay_admin.php";
//Credit Card settings
$credit_card_info["PayPalCurrency"] = "USD"; //USD; CAD; GBP; EUR;
$credit_card_info["PayPalNotifyURL"]	= "management/include/payment/paypalstatus.php";
$credit_card_info["PayPalPayURL"]		= "management/include/payment/paypalpay.php";
$credit_card_info["PayPalNoPayURL"]	= "management/include/payment/nopay.php";
$credit_card_info["AdminReturnPayPalURL"] = "management/include/payment/paypalstatus_admin.php";
$credit_card_info["AdminPayPalNoPayURL"]	= "management/include/payment/nopay_admin.php";

//Common settings
$default_language = "en"; //Default language
$usersettings["dateformat"] = "%m/%d/%y";   //Date format for MySQL
$usersettings["datetimeformat"] = "%m/%d/%y (%T)";   //Date and time format for MySQL
$usersettings["dateformat_php"] = "m/d/y";   //Date format for PHP
$usersettings["dateformat_c"] = "%m/%d/%y"; //Date format for Calendar
$usersettings["dateformat_c_info"] = "MM/DD/YY"; //Date format for Calendar (info line)
date_default_timezone_set($usersettings["timezone_identifier"]);


// Not to changing -->>
//Session settings
$SNAME = session_name();
$SID = session_id();
$SLINE = $SNAME."=".$SID;
// Not to changing <<--
?>