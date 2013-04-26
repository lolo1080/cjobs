<?
/*
###############################
# leftmenu_const.php
# Constant values for left menu
###############################
*/

//Users: 0->administrator; 1->member(advertiser,affiliate(webmaster))
$UserNameList = array("[ Administrator ]","[ Advertiser ]","[ Publisher ]","[ Member ]");
//Menu list (code name and order)
$menu_list = array();
$menu_list[0] = array("main","users","settings","submissions","statistics","payment","jobs","templates","mail");
$menu_list[1] = array("main","advertiser","payment");
$menu_list[2] = array("main","affiliate","traffic","payment");
$menu_list[3] = array("main");
//Menu section status (1-down, 0-up)
$default_menu_status[0] = array("main"=>1,"users"=>1,"settings"=>1,"submissions"=>1,"statistics"=>1,"payment"=>1,"jobs"=>1,"templates"=>1,"mail"=>1);
$default_menu_status[1] = array("main"=>1,"advertiser"=>1,"payment"=>1);
$default_menu_status[2] = array("main"=>1,"affiliate"=>1,"traffic"=>1,"payment"=>1);
$default_menu_status[3] = array("main"=>1);
//Menu section -> menu items
$active_menu[0] = array(
	"main"				=> array("welcome","news","sendbugreport","logout"),
	"users"				=> array("advertisers","publishers","members"),
	"settings"		=> array("admsettings","globsettings","jobrollsettings","paymentsettings","ipfirewall","job_search_emails","job_search_log"),
	"submissions"	=> array("subm_users_adv","subm_users_pub","subm_users_mem","subm_xmlfeed","subm_job_ads"),
	"statistics"	=> array("statistics_common","statistics_keywords","statistics_clicks","statistics_earn_money",
									"statistics_users","statistics_ip"),
	"payment"			=> array("payment_request","payment_history"),
	"jobs"				=> array("categories","feeds","jobs_list_common"/*,"jobs_list_clear_tasks"*/),
	"templates"		=> array("manage_templates"),
	"mail"				=> array("sign_up_confirm","sign_up_welcome_adv","sign_up_welcome_pub","sign_up_welcome_mem","sign_up_appoved",
									"credited_notification","request_payment","email_job","forgotpass","member_job_alert","job_search_alert")
);
$active_menu[1] = array(
	"main"			=> array("adv_info","adv_profile","sendbugreport","logout"),
	"advertiser"=> array("adv_advertisements"),
	"payment"		=> array("adv_fund_account","adv_fund_history")
);
$active_menu[2] = array(
	"main"			=> array("pub_info","pub_profile","sendbugreport","logout"),
	"affiliate"	=> array("pub_get_jobroll","pub_get_job_searchbox","pub_get_job_textlink","pub_get_xmlfeed"),
	"traffic"		=> array("pub_traffic_summary"),
	"payment"		=> array("pub_payment_request","pub_payment_history")
);
$active_menu[3] = array(
	"main"			=> array("member_info","member_profile","member_jobalert","logout")
);

//Menu items: links
$menu_links = array(
	"welcome"						=> "welcome.php?action=0",
	"news"							=> "news.php?action=0",
	"sendbugreport"			=> "sendbugreport.php?action=0",
	"advertisers"				=> "advertisers.php?action=0",
	"publishers"				=> "publishers.php?action=0",
	"members"						=> "members.php?action=0",
	"admsettings"				=> "admsettings.php?action=0",
	"globsettings"			=> "globsettings.php?action=0",
	"categories"				=> "categories.php?action=0",
	"feeds"							=> "feeds.php?action=0",
	"jobrollsettings"		=> "jobrollsettings.php?action=0",
	"paymentsettings"		=> "paymentsettings.php?action=0",
	"ipfirewall"				=> "ipfirewall.php?action=0",
	"job_search_emails"	=> "job_search_emails.php?action=0",
	"job_search_log"		=> "job_search_log.php?action=0",
	"subm_users_adv"		=> "subm_users.php?type=adv",
	"subm_users_pub"		=> "subm_users.php?type=pub",
	"subm_users_mem"		=> "subm_users.php?type=mem",
	"subm_xmlfeed"			=> "subm_xmlfeed.php?action=0",
	"subm_job_ads"			=> "subm_job_ads.php?action=0",
	"statistics_common"		=> "statistics_common.php?action=0",
	"statistics_keywords"	=> "statistics_search.php?action=0",
	"statistics_clicks"		=> "statistics_clicks.php?action=0",
	"statistics_earn_money"	=> "statistics_earn_money.php?uid=all",
	"statistics_users"			=> "statistics_users.php?action=0",
	"statistics_ip"					=> "statistics_ip.php?action=0",
	"payment_request"		=> "payment_request.php?action=0",
	"payment_history"		=> "pub_payment_history.php?uid_pub=all",
	"jobs_list_common"			=> "jobs_list_common.php?action=0",
	"jobs_list_clear_tasks"	=> "jobs_list_clear_tasks.php?action=0",
	"sign_up_confirm"				=> "mail.php?mail=sign_up_confirm",
	"sign_up_welcome_adv"		=> "mail.php?mail=sign_up_welcome_adv",
	"sign_up_welcome_pub"		=> "mail.php?mail=sign_up_welcome_pub",
	"sign_up_welcome_mem"		=> "mail.php?mail=sign_up_welcome_mem",
	"sign_up_appoved"				=> "mail.php?mail=sign_up_appoved",
	"credited_notification"	=> "mail.php?mail=credited_notification",
	"request_payment"				=> "mail.php?mail=request_payment",
	"email_job"							=> "mail.php?mail=email_job",
	"forgotpass"						=> "mail.php?mail=forgotpass",
	"member_job_alert"			=> "mail.php?mail=member_job_alert",
	"job_search_alert"			=> "mail.php?mail=job_search_alert",
	"logout"						=> "logout.php?action=0",
	"manage_templates"	=> "manage_templates.php?action=0",

	"adv_info"						=> "adv_info.php?action=0",
	"adv_profile"					=> "adv_profile.php?action=0",
	"adv_advertisements"	=> "adv_advertisements.php?action=0",
	"adv_fund_account"		=> "adv_fund_account.php?action=0",
	"adv_fund_history"		=> "adv_fund_history.php?action=0",

	"pub_info"						=> "pub_info.php?action=0",
	"pub_profile"					=> "pub_profile.php?action=0",
	"pub_get_jobroll"			=> "pub_get_jobroll.php?action=0",
	"pub_get_job_searchbox"=> "pub_get_job_searchbox.php?action=0",
	"pub_get_job_textlink"=> "pub_get_job_textlink.php?action=0",
	"pub_get_xmlfeed"			=> "pub_get_xmlfeed.php?action=0",
	"pub_traffic_summary"	=> "pub_traffic_summary.php?action=0",
	"pub_payment_request"	=> "pub_payment_request.php?action=0",
	"pub_payment_history"	=> "pub_payment_history.php?action=0",

	"member_info"					=> "member_info.php?action=0",
	"member_profile"			=> "member_profile.php?action=0",
	"member_jobalert"			=> "member_jobalert.php?action=0"
);
//Menu items: images - <name><width><height>
$menu_images = array(
	"welcome"					=> array("welcome.gif",16,16),
	"news"						=> array("mail.gif",16,16),
	"sendbugreport"		=> array("mail.gif",16,16),
	"advertisers"			=> array("mail.gif",16,16),
	"publishers"			=> array("mail.gif",16,16),
	"members"					=> array("mail.gif",16,16),
	"admsettings"			=> array("password.gif",16,16),
	"globsettings"		=> array("settings1.gif",16,16),
	"categories"			=> array("listings.gif",16,16),
	"feeds"						=> array("stats.gif",16,16),
	"jobrollsettings"	=> array("settings1.gif",16,16),
	"paymentsettings"	=> array("settings1.gif",16,16),
	"ipfirewall"			=> array("settings1.gif",16,16),
	"job_search_emails"=> array("settings1.gif",16,16),
	"job_search_log"	=> array("settings1.gif",16,16),
	"subm_users_adv"	=> array("listings.gif",16,16),
	"subm_users_pub"	=> array("listings.gif",16,16),
	"subm_users_mem"	=> array("listings.gif",16,16),
	"subm_xmlfeed"		=> array("listings.gif",16,16),
	"subm_job_ads"		=> array("listings.gif",16,16),
	"statistics_common"			=> array("stats.gif",16,16),
	"statistics_keywords"		=> array("stats.gif",16,16),
	"statistics_clicks"			=> array("stats.gif",16,16),
	"statistics_earn_money"	=> array("stats.gif",16,16),
	"statistics_users"			=> array("stats.gif",16,16),
	"statistics_ip"					=> array("stats.gif",16,16),
	"payment_request"	=> array("request.gif",16,16),
	"payment_history"	=> array("history.gif",16,16),
	"jobs_list_common"			=> array("referals.gif",16,16),
	"jobs_list_clear_tasks"	=> array("request.gif",16,16),
	"sign_up_confirm"				=> array("mail.gif",16,16),
	"sign_up_welcome_adv"		=> array("mail.gif",16,16),
	"sign_up_welcome_pub"		=> array("mail.gif",16,16),
	"sign_up_welcome_mem"		=> array("mail.gif",16,16),
	"sign_up_appoved"				=> array("mail.gif",16,16),
	"credited_notification"	=> array("mail.gif",16,16),
	"request_payment"				=> array("mail.gif",16,16),
	"email_job"							=> array("mail.gif",16,16),
	"forgotpass"						=> array("mail.gif",16,16),
	"member_job_alert"			=> array("mail.gif",16,16),
	"job_search_alert"			=> array("mail.gif",16,16),
	"logout"					=> array("logout1.gif",16,16),
	"manage_templates"	=> array("gethtml.gif",16,16),

	"adv_info"					=> array("info_m.gif",16,16),
	"adv_profile"				=> array("profile.gif",16,16),
	"adv_advertisements"=> array("listings.gif",16,16),
	"adv_fund_account"		=> array("request.gif",16,16),
	"adv_fund_history"		=> array("history.gif",16,16),

	"pub_info"						=> array("info_m.gif",16,16),
	"pub_profile"					=> array("profile.gif",16,16),
	"pub_get_jobroll"			=> array("gethtml.gif",16,16),
	"pub_get_job_searchbox"=> array("gethtml.gif",16,16),
	"pub_get_job_textlink"=> array("gethtml.gif",16,16),
	"pub_get_xmlfeed"				=> array("gethtml.gif",16,16),
	"pub_traffic_summary"	=> array("stats.gif",16,16),
	"pub_payment_request"	=> array("request.gif",16,16),
	"pub_payment_history"	=> array("history.gif",16,16),

	"member_info"					=> array("info_m.gif",16,16),
	"member_profile"			=> array("profile.gif",16,16),
	"member_jobalert"			=> array("mail.gif",16,16)
);
//Menu items: target
$menu_target = array(
	"welcome"					=> "content_top",
	"news"						=> "content_top",
	"sendbugreport"		=> "content_top",
	"advertisers"			=> "content_top",
	"publishers"			=> "content_top",
	"members"					=> "content_top",
	"admsettings"			=> "content_top",
	"globsettings"		=> "content_top",
	"categories"			=> "content_top",
	"feeds"						=> "content_top",
	"jobrollsettings"	=> "content_top",
	"paymentsettings"	=> "content_top",
	"ipfirewall"			=> "content_top",
	"job_search_emails"=>"content_top",
	"job_search_log"	=> "content_top",
	"subm_users_adv"	=> "content_top",
	"subm_users_pub"	=> "content_top",
	"subm_users_mem"	=> "content_top",
	"subm_xmlfeed"		=> "content_top",
	"subm_job_ads"		=> "content_top",
	"statistics_common"			=> "content_top",
	"statistics_keywords"		=> "content_top",
	"statistics_clicks"			=> "content_top",
	"statistics_earn_money"	=> "content_top",
	"statistics_users"			=> "content_top",
	"statistics_ip"					=> "content_top",
	"payment_request"	=> "content_top",
	"payment_history"	=> "content_top",
	"jobs_list_common"			=> "content_top",
	"jobs_list_clear_tasks"	=> "content_top",
	"sign_up_confirm"				=> "content_top",
	"sign_up_welcome_adv"		=> "content_top",
	"sign_up_welcome_pub"		=> "content_top",
	"sign_up_welcome_mem"		=> "content_top",
	"sign_up_appoved"				=> "content_top",
	"credited_notification"	=> "content_top",
	"request_payment"				=> "content_top",
	"email_job"							=> "content_top",
	"forgotpass"						=> "content_top",
	"member_job_alert"			=> "content_top",
	"job_search_alert"			=> "content_top",
	"logout"					=> "_top",
	"manage_templates"	=> "content_top",

	"adv_info"					=> "content_top",
	"adv_profile"				=> "content_top",
	"adv_advertisements"=> "content_top",
	"adv_fund_account"		=> "content_top",
	"adv_fund_history"		=> "content_top",

	"pub_info"					=> "content_top",
	"pub_profile"				=> "content_top",
	"pub_get_jobroll"		=> "content_top",
	"pub_get_xmlfeed"			=> "content_top",
	"pub_get_job_searchbox"=> "content_top",
	"pub_get_job_textlink"=> "content_top",
	"pub_traffic_summary"	=> "content_top",
	"pub_payment_request"	=> "content_top",
	"pub_payment_history"	=> "content_top",

	"member_info"					=> "content_top",
	"member_profile"			=> "content_top",
	"member_jobalert"			=> "content_top"
);
//Menu item "_split": devider before this menu item /only for JavaScript top menu/
$menu_split = array(
	"welcome"					=> 0,
	"news"						=> 0,
	"sendbugreport"		=> 0,
	"advertisers"			=> 0,
	"publishers"			=> 0,
	"members"					=> 0,
	"admsettings"			=> 0,
	"globsettings"		=> 0,
	"categories"			=> 0,
	"feeds"						=> 0,
	"jobrollsettings"	=> 0,
	"paymentsettings"	=> 0,
	"ipfirewall"			=> 0,
	"job_search_emails"=> 0,
	"job_search_log"	=> 0,
	"subm_users_adv"	=> 0,
	"subm_users_pub"	=> 0,
	"subm_users_mem"	=> 0,
	"subm_xmlfeed"		=> 0,
	"subm_job_ads"		=> 0,
	"statistics_common"			=> 0,
	"statistics_keywords"		=> 0,
	"statistics_clicks"			=> 0,
	"statistics_earn_money"	=> 0,
	"statistics_users"			=> 0,
	"statistics_ip"					=> 0,
	"payment_request"	=> 0,
	"payment_history"	=> 0,
	"jobs_list_common"			=> 0,
	"jobs_list_clear_tasks"	=> 0,
	"sign_up_confirm"				=> 0,
	"sign_up_welcome_adv"		=> 0,
	"sign_up_welcome_pub"		=> 0,
	"sign_up_welcome_mem"		=> 0,
	"sign_up_appoved"				=> 0,
	"credited_notification"	=> 0,
	"request_payment"	=> 0,
	"email_job"				=> 0,
	"forgotpass"			=> 0,
	"member_job_alert"=> 0,
	"job_search_alert"=> 0,
	"logout"					=> 1,
	"manage_templates"	=> 0,

	"adv_info"					=> 0,
	"adv_profile"				=> 0,
	"adv_advertisements"=> 0,
	"adv_fund_account"		=> 0,
	"adv_fund_history"		=> 0,

	"pub_info"					=> 0,
	"pub_profile"				=> 0,
	"pub_get_jobroll"		=> 0,
	"pub_get_job_searchbox"=> 0,
	"pub_get_job_textlink"=> 0,
	"pub_get_xmlfeed"			=> 0,
	"pub_traffic_summary"	=> 0,
	"pub_payment_request"	=> 0,
	"pub_payment_history"	=> 0,

	"member_info"					=> 0,
	"member_profile"			=> 0,
	"member_jobalert"			=> 0
);
//Default start page
$start_content[0] = "welcome.php";
$start_content[1] = "adv_info.php";
$start_content[2] = "pub_info.php";
$start_content[3] = "member_info.php";
?>