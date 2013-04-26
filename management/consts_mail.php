<?
//Mail settings
require_once "consts.php";
require_once dirname(__FILE__)."/language.php";

//Associate pages and menu items
$mail_array = array(
	"sign_up_confirm"	=> "sign_up_confirm.html",
	"sign_up_appoved"	=> "sign_up_appoved.html",
	"sign_up_welcome_adv"	=> "sign_up_welcome_adv.html",
	"sign_up_welcome_pub"	=> "sign_up_welcome_pub.html",
	"sign_up_welcome_mem"	=> "sign_up_welcome_mem.html",
	"credited_notification"				=> "credited_notification.html",
	"admin_credited_notification"	=> "admin_credited_notification.html",
	"request_payment"	=> "request_payment.html",
	"email_job"				=> "email_job.html",
	"forgotpass"			=> "forgotpass.html",
	"member_job_alert"=> "member_job_alert.html",
	"job_search_alert"=> "job_search_alert.html"
);

//Associate pages and text info
$mail_array_text = array(
	"sign_up_confirm"	=> $text_info["sign_up_confirm"],
	"sign_up_appoved"	=> $text_info["sign_up_appoved"],
	"sign_up_welcome_adv"	=> $text_info["sign_up_welcome_adv"],
	"sign_up_welcome_pub"	=> $text_info["sign_up_welcome_pub"],
	"sign_up_welcome_mem"	=> $text_info["sign_up_welcome_mem"],
	"credited_notification"				=> $text_info["credited_notification"],
	"admin_credited_notification"	=> $text_info["admin_credited_notification"],
	"request_payment"	=> $text_info["request_payment"],
	"email_job"				=> $text_info["email_job"],
	"forgotpass"			=> $text_info["forgotpass"],
	"member_job_alert"=> $text_info["member_job_alert"],
	"job_search_alert"=> $text_info["job_search_alert"]
);

//Values in e-mails for parsing
$parse_values = array(
	"{*site_title*}"						=> "", //Site title. 'Global Settings' -> Site Title.
	"{*name*}"									=> "", //Name
	"{*username*}"							=> "", //Username (== login)
	"{*password*}"							=> "", //Password
	"{*site_url_txt*}"					=> "", //Site URL. 'Global Settings' -> Site URL. (text format)
	"{*site_url_html*}"					=> "", //Site URL. 'Global Settings' -> Site URL. (html format)
	"{*login_url_txt*}"					=> "", //URL to login page (text format)
	"{*login_url_html*}"				=> "", //URL to login page (html format)
	"{*refer_url_txt*}"					=> "", //URL for referrals for member (text format)
	"{*refer_url_html*}"				=> "", //URL for referrals for member (html format)
	"{*email_confirm_url_txt*}"	=> "", //E-mail confirmation URL (text format)
	"{*email_confirm_url_html*}"=> "", //E-mail confirmation URL (html format)
	"{*sign_up_url*}"						=> "", //Sign up URL.
	"{*uid*}"										=> "", //User id
	"{*search_url*}"						=> "", //URL to search script
	"{*xmlsearch_url*}"					=> "", //URL to xml search script
	"{*webmaster_email_txt*}"		=> "", //Webmaster e-mail (Admin e-mail) 'Admin Settings' -> E-mail. (text format)
	"{*webmaster_email_html*}"	=> "", //Webmaster e-mail (Admin e-mail) 'Admin Settings' -> E-mail. (html format)
	"{*pageresult_default*}"		=> "", //This is default value for "pageresult" parameter for XML Feed
	"{*startat_default*}"				=> "", //This is default value for "startat" parameter for XML Feed
	"{*payment_system*}"				=> "", //Payment system name (example: E-Gold, PayPal)
	"{*payment_info_text*}"			=> "", //Payment info (text format)
	"{*payment_info_html*}"			=> "", //Payment info (html format)
	"{*amount*}"								=> "", //Payment amount

	"{*member_first_name*}"			=> "", //Member first name
	"{*period*}"								=> "", //Period
	"{*member_login_url*}"			=> "", //Member login URL
	"{*view_jobs_since_yesterday*}"=> "", //View jobs: since yesterday
	"{*view_jobs_since_7days*}"	=> "", //View jobs: for last 7 days
	"{*view_jobs_all*}"					=> "", //View jobs: all jobs
	"{*jobs_list*}"							=> "", //Jobs list


	"{*job_title*}"							=> "", //Job title
	"{*job_sender_email*}"			=> "", //Job senter email
	"{*job_sender_message*}"		=> "", //Job sender message
	"{*job_company_name*}"			=> "", //Job company_name
	"{*job_city*}"							=> "", //Job city
	"{*job_region*}"						=> "", //Job region
	"{*job_description*}"				=> "", //Job description
	"{*job_clickurl*}"					=> "", //Job clickurl
	"{*job_feed_name*}"					=> "", //Job feed_name
	"{*job_registered_ago*}"		=> "", //Job registered_ago
);
?>