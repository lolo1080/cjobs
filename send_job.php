<?
require_once "consts.php";
require_once $admin_dir_path."app_errors_handler.php";
require_once "consts_smarty.php";
require_once $admin_dir_path."consts_mail.php";
require_once $admin_dir_path."include/mail/send_mail.php";
require_once "language.php";
require_once "template_vals.php";
require_once "index_functions.php";
require_once "job_details_func.php";
require_once "search_functions.php";
require_once $admin_dir_path."connect.inc";
require_once $admin_dir_path."include/functions/functions_main.php";
require_once $frontend_script_dir."app_cache_functions.php";
require_once $frontend_script_dir."common_functions.php";
// Load JsHttpRequest backend.
require_once $frontend_script_dir."JsHttpRequest/lib/JsHttpRequest/JsHttpRequest.php";


function return_result()
{
 global $my_error, $email_to;
	// Store resulting data in $_RESULT array (will appear in req.responseJs).
	$GLOBALS['_RESULT'] = array(
 	 "my_error"     => $my_error,
 	 "email_to"     => $email_to
	); 
}

function gen_sp_notice($f,$l)
{
 global $jobkey, $email_from, $email_to;
	none_critical_notice($f,$l,"No data for job. jobkey=$jobkey, email_from=$email_from, email_to=$email_to");
}

function send_job_email(&$job_data)
{
 global $parse_values,$email_from,$email_to,$comments,$email_cc;
	//----Send mail---->>
	$admin_email = get_admin_email_free();
	$parse_values["{*site_title*}"]				= $_SESSION["globsettings"]["site_title"];
	$parse_values["{*site_url_txt*}"]			= $_SESSION["globsettings"]["site_url"];
	$parse_values["{*site_url_html*}"]		= "<a href=\"".$parse_values["{*site_url_txt*}"]."\">".$parse_values["{*site_url_txt*}"]."</a>";
	$parse_values["{*webmaster_email_txt*}"]= $admin_email;
	$parse_values["{*job_title*}"]				= $job_data["title"];
	$parse_values["{*job_sender_email*}"]	= $email_from;
	$parse_values["{*job_sender_message*}"]= $comments;
	$parse_values["{*job_company_name*}"]	= $job_data["company_name"];
	$parse_values["{*job_city*}"]					= $job_data["city"];
	$parse_values["{*job_region*}"]				= $job_data["region"];
	$parse_values["{*job_description*}"]	= $job_data["description"];
	$parse_values["{*job_clickurl*}"]			= $job_data["clickurl"];
	$parse_values["{*job_feed_name*}"]		= $job_data["feed_name"];
	$parse_values["{*job_registered_ago*}"]= $job_data["registered_ago"];
	//----Send mail to visitor (E-mail job to to yourself or a friend)
	$subj	= get_mailsubject("email_job");
	release_content_parse_values($subj);
	$htmlmessage = get_email_file("email_job","html");
	$textmessage = get_email_file("email_job","txt");
	$attach_files = get_mail_attach("email_job");
	create_and_send_email($email_to,$email_from,$subj,$htmlmessage,$textmessage,$attach_files);
	if ($email_cc) create_and_send_email($email_from,$email_from,$subj,$htmlmessage,$textmessage,$attach_files);
	//----Send mail----<<
}

//Get data from send e-mail form
$jobkey			= html_chars(get_post_value("jobkey",""));
$email_from	= html_chars(get_post_value("email_from",""));
$email_to		= html_chars(get_post_value("email_to",""));
$email_cc		= html_chars(get_post_value("email_cc",0));
$comments		= html_chars(get_post_value("comments",""));

//Check values
$my_error = "";
if ($jobkey == "") $my_error .= $Error_messages["se_nojobkey"];
if ($email_from == "") $my_error .= $Error_messages["se_email_from"];
if ($email_to == "") $my_error .= $Error_messages["se_email_to"];
if (($email_from != "") && !check_mail($email_from)) $my_error .= $Error_messages["se_email_from_invalid"];
if (($email_to != "") && !check_mail($email_to)) $my_error .= $Error_messages["se_email_to_invalid"];

doconnect();

// Create main library object. You MUST specify page encoding!
$JsHttpRequest =& new JsHttpRequest("windows-1251");

//Check IPFW
if (!check_visitor_ipfw_cache()) $my_error .= $Error_messages["xml_blocked_ip"]; 

//Check settings
get_global_settings();

if ($my_error != "") { return_result(); exit; }

$data_type = $jobkey[0];
$data_id = substr($jobkey,1);

if ($data_type == "c") {
	//Select this job
	$job_data = do_job_data_search($data_id);
	if (count($job_data) > 0) {
		do_sendjob_design_c($job_data);
		send_job_email($job_data[0]);
	}
	else {
		$my_error .= $Error_messages["se_nojobdata"];
		gen_sp_notice(__FILE__,__LINE__);
	}
}
elseif ($data_type == "a") {
	//Select this job
	$job_data = do_job_adv_data_search($data_id);
	if (count($job_data) > 0) {
		do_sendjob_design_a($job_data);
		send_job_email($job_data[0]);
	}
	else {
		$my_error .= $Error_messages["se_nojobdata"];
		gen_sp_notice(__FILE__,__LINE__);
	}
}
else {
	$my_error .= $Error_messages["se_jobkey_invalid"];
	gen_sp_notice(__FILE__,__LINE__);
}

//Check e-mail sending error
if (strcmp($email_sending_error, "")) {
	$my_error .= $Error_messages["se_email_sending_error"].$email_sending_error;
	none_critical_notice(__FILE__,__LINE__,$Error_messages["se_email_sending_error"].$email_sending_error);
}

return_result();
?>