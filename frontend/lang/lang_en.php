<?php
/*
###############################
# lang_en.php
# Language file (English)
###############################
*/

//Include settings for English
require_once "lang_settings_en.php";

$text_info["p_sec_ago1"] = "second ago";
$text_info["p_min_ago1"] = "minute ago";
$text_info["p_hour_ago1"] = "hour ago";
$text_info["p_day_ago1"] = "day ago";
$text_info["p_month_ago1"] = "month ago";
$text_info["p_year_ago1"] = "year ago";
$text_info["p_sec_agoN"] = "seconds ago";
$text_info["p_min_agoN"] = "minutes ago";
$text_info["p_hour_agoN"] = "hours ago";
$text_info["p_day_agoN"] = "days ago";
$text_info["p_month_agoN"] = "months ago";
$text_info["p_year_agoN"] = "years ago";
$text_info["n_next"] = "Next &gt;&gt;";
$text_info["n_prev"] = "&lt;&lt; Prev";
$text_info["p_show_new_jobs"] = "Show: <b>all jobs</b> - <a href='{*URL*}'>{*Num*} new jobs</a>";
$text_info["p_show_all_jobs"] = "Show: <a href='{*URL*}'>all {*Num*} jobs</a> - <b>only new jobs</b>";
$text_info["p_show_all_jobs_only"] = "Show: <b>all jobs</b>";
$text_info["p_sort_by_relevance"] = "Sort by: <b>relevance</b> - <a href='{*URL*}'>date</a>";
$text_info["p_sort_by_date"] = "Sort by: <a href='{*URL*}'>relevance</a> - <b>date</b>";
$text_info["p_search_results_stats"] = "Jobs {*FromTo*} of {*Of*} for <b>{*Keywords*}</b> {*radius*} {*within*}";
$text_info["p_categories"] = "Categories:";
$text_info["p_all_categories"] = "All Categories";
//$text_info["p_categories_links"] = "Categories: {*Categories*}";
$text_info["p_jobs_per_page"] = " Show {*jobs_per_page*} jobs per page";
$text_info["p_employer"] = "Employer";
$text_info["p_recruiter"] = "Recruiter";
$text_info["p_title"] = "Title";
$text_info["p_company"] = "Company";
$text_info["p_in"] = "in";
$text_info["p_jobs"] = "jobs";
$text_info["p_casinojobs_jobs"] = "ES Job Search Engine Jobs";
$text_info["p_radius"] = "Radius";
$text_info["p_category"] = "Category";
$text_info["p_job_type"] = "Job type";
$text_info["p_job_from"] = "Job from";
$text_info["p_exclude_staffing_agencies"] = "Exclude staffing agencies";
$text_info["p_salary"] = "Salary";
$text_info["p_job_published"] = "Job published";
$text_info["html_site_title"] = "Job Search";
$text_info["html_site_title_fill"] = "All {*SiteTitle*} jobs";
$text_info["html_site_description"] = "ES Job Search Engine Jobs";
$text_info["html_site_description_fill"] = "Job search for {*SiteDescription*} jobs at es-job-search-engine.com.";
$text_info["html_site_keywords"] = "ES Jobs";
$text_info["html_my_jobs"] = "My Jobs";
$text_info["html_advanced_search"] = "Advanced Job Search";
$text_info["html_advertisers"] = "Advertisers - Accont Home";
$text_info["html_publishers"] = "Publishers - Accont Home";
$text_info["html_browse_jobs"] = "Browse Jobs";
$text_info["html_members"] = "Members Area";
$text_info["deliver_1"] = "Daily";
$text_info["deliver_7"] = "Weekly";


function get_fname_fr($field)
{
 global $text_info;
 return "<b>".$text_info[$field]."</b>";
}
$Error_messages["search_empty_keyword"]	= "<h2>Hmm. A minimalist, eh?</h2><p>Sorry, we can't show you <em>every</em> job.  That would make the system run slow for other folks. <br />Please enter at least one keyword and/or location.</p>";
$Error_messages["search_empty_location"] = "<h2>Hmm. We've never been there before.</h2><h2>What's the weather like?</h2><p>We couldn't find the location <strong>{*Location*}</strong>. You're probably a good speller, but check the location terms you entered.</p><p>(Hint: Try entering a \"City, State\" or you can try entering just the ZIP Code.)</p><p>It's also possible we made an error somewhere. Sometimes computers are human too... we just smell better.</p>";
$Error_messages["search_empty_result"] = "<h2>Dang. We didn't find anything for you.</h2><p>We couldn't find any jobs {*what*} {*where*}.</p><p>You're probably a good speller, but check the keyword terms you entered.</p><p>You can also try using some other keywords, or enter fewer words to expand your search.</p><p>It's also possible we made an error somewhere.</p><p>Sometimes computers are human too... just shinier.</p>";
$Error_messages["search_empty_result_job_alert_1"] = "<h2>Dang. We didn't find anything for you.</h2><p>We couldn't find any jobs {*what*} {*where*} since yesterday.</p><p>Try search with more jobs published period.</p>";
$Error_messages["search_empty_result_job_alert_7"] = "<h2>Dang. We didn't find anything for you.</h2><p>We couldn't find any jobs {*what*} {*where*} for last 7 days .</p><p>Try search with more jobs published period.</p>";
$Error_messages["search_empty_result_what"] = "for ";
$Error_messages["search_empty_result_where"] = "in ";
$Error_messages["search_no_data_id"]	= "<h2>Hmm. Problem with job which you have selected.</h2><p>Sorry, we can't show you selected job, because your request do not conatain all necessary data.</p>";
$Error_messages["search_no_data_found"]	= "<h2>Hmm. Problem with job which you have selected.</h2><p>Sorry, the requested job was not found on this server.</p>";
$Error_messages["xml_blocked_ip"]					= "Your IP address is blocked.";
$Error_messages["xml_empty_keyword"]			= "No search keywords. Please enter at least one keyword and/or location.";
$Error_messages["xml_empty_location"]			= "We couldn't find the location <strong>{*Location*}</strong>. Check the location terms you entered. It's also possible we made an error somewhere.";
$Error_messages["xml_xmlfeed_notallowed"] = "Sorry, but XML feed not active for you. Please, activate XML feed for your account.";
$Error_messages["myjobs_empty"]	= "<h2>No stored jobs</h2><p>Sorry, but you have not stored jobs yet. Please do seach and store some jobs.</p>";
$Error_messages["se_nojobkey"]	= "Jobkey should not be empty<br>";
$Error_messages["se_email_from"]= "Please enter a from email address<br>";
$Error_messages["se_email_to"]	= "Please enter a to email address<br>";
$Error_messages["se_email_from_invalid"]= "Email from address in invalid<br>";
$Error_messages["se_email_to_invalid"]	= "Email to address in invalid<br>";
$Error_messages["se_nojobdata"]					= "No job data for this job. Please, contact to administrator<br>";
$Error_messages["se_jobkey_invalid"]		= "Jobkey is invalid<br>";
$Error_messages["se_email_sending_error"] = "Email sending error: ";
?>