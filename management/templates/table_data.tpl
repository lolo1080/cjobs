<!-- Data table -->
<table border="1" borderColor="{$tbl1bgcolor}" cellPadding="0" cellSpacing="0" height="100%" width="100%">
<tr>
	<td valign="top">

		{include file="table_caption.tpl"}

		{strip}
			{if $error}
				{include file="table_info.tpl"}
			{/if}
		{/strip}

		{*Table body*}
		{if $curpage == "welcome"}
			{include file="page_welcome.tpl"}					{*Welcome (Admin area)*}
		{elseif $curpage == "admsettings"}
			{include file="page_admsettings.tpl"}			{*Admin settings (Admin area)*}
		{elseif $curpage == "globsettings"}
			{include file="page_globsettings.tpl"}		{*Global settings (Admin area)*}
		{elseif $curpage == "jobrollsettings"}
			{include file="page_jobrollsettings.tpl"}	{*Jobroll settings (Admin area)*}
		{elseif $curpage == "commonsettings"}
			{include file="page_commonsettings.tpl"}	{*Common settings (Admin area)*}
		{elseif $curpage == "paymentsettings"}
			{include file="page_paymentsettings.tpl"}	{*Payment settings (Admin area)*}
		{elseif $curpage == "ipfirewall"}
			{include file="page_ipfirewall.tpl"}			{*IP firewall (Admin area)*}
		{elseif $curpage == "ipfirewall_work"}
			{include file="page_ipfirewall_work.tpl"}	{*IP firewall work (Admin area)*}
		{elseif $curpage == "news"}
			{include file="page_news.tpl"}						{*News (Admin area)*}
		{elseif $curpage == "advertisers"}
			{include file="page_advertisers.tpl"}			{*Advertisers (Admin area)*}
		{elseif $curpage == "advertisers_work"}
			{include file="page_advertisers_work.tpl"}{*Advertisers work (Admin area)*}
		{elseif $curpage == "publishers"}
			{include file="page_publishers.tpl"}			{*Publishers (Admin area)*}
		{elseif $curpage == "members"}
			{include file="page_members.tpl"}					{*Memebers (Admin area)*}
		{elseif $curpage == "members_work"}
			{include file="page_members_work.tpl"}		{*Members work (Admin area)*}
		{elseif $curpage == "categories"}
			{include file="page_categories.tpl"}			{*Categories (Admin area)*}
		{elseif $curpage == "categories_work"}
			{include file="page_categories_work.tpl"}	{*Categories work (Admin area)*}
		{elseif $curpage == "feeds"}
			{include file="page_feeds.tpl"}						{*Feeds (Admin area)*}
		{elseif $curpage == "feeds_work"}
			{include file="page_feeds_work.tpl"}			{*Feeds work (Admin area)*}

		{elseif $curpage == "feeds_xml"}
			{include file="page_feeds_xml.tpl"}				{*Feeds xml1 (Admin area)*}
		{elseif $curpage == "feeds_xml2"}
			{include file="page_feeds_xml2.tpl"}			{*Feeds xml2 (Admin area)*}
		{elseif $curpage == "feeds_html"}
			{include file="page_feeds_html.tpl"}			{*Feeds html (Admin area)*}
		{elseif $curpage == "feeds_html2"}
			{include file="page_feeds_html2.tpl"}			{*Feeds html2 (Admin area)*}

		{elseif $curpage == "advertisers_balance_work"}
			{include file="page_advertisers_balance_work.tpl"}{*Advertisers balance work (Admin area)*}
		{elseif $curpage == "publishers_balance_work"}
			{include file="page_publishers_balance_work.tpl"}{*Publishers balance work (Admin area)*}
		{elseif $curpage == "manage_templates"}
			{include file="page_manage_templates.tpl"}{*Manage templates (Admin area)*}
		{elseif $curpage == "manage_templates_work"}
			{include file="page_manage_templates_work.tpl"}	{*Manage templates work (Admin area)*}
		{elseif $curpage == "publishers_work"}
			{include file="page_publishers_work.tpl"}	{*Publishers work (Admin area)*}
		{elseif $curpage == "subm_users"}
			{include file="page_subm_users.tpl"}			{*Submissions Users (Admin area)*}
		{elseif $curpage == "subm_xmlfeed"}
			{include file="page_subm_xmlfeed.tpl"}				{*Submissions XML Feed (Admin area)*}
		{elseif $curpage == "mail"}
			{include file="page_mail.tpl"}								{*E-mail templates (Admin area)*}
		{elseif $curpage == "statistics_search"}
			{include file="page_statistics_search.tpl"}		{*Search statistics /all users/ (Admin area)*}
		{elseif $curpage == "statistics_visitor_work"}
			{include file="page_statistics_visitor_work.tpl"}	{*Visitor work (Admin area)*}
		{elseif $curpage == "statistics_clicks"}
			{include file="page_statistics_clicks.tpl"}		{*Clicks statistics /all users/ (Admin area)*}
		{elseif $curpage == "statistics_clicks_work"}
			{include file="page_statistics_clicks_work.tpl"} 	{*Show Clicks statistics work (Admin area)*}
		{elseif $curpage == "statistics_earn_money"}
			{include file="page_statistics_earn_money.tpl"}		{*Earn money statistics /all users/ (Admin area)*}
		{elseif $curpage == "statistics_earn_money_work"}
			{include file="page_statistics_earn_money_work.tpl"}{*Show Earn money statistics work (Admin area)*}
		{elseif $curpage == "payment_request"}
			{include file="page_payment_request.tpl"}			{*Payment request list  (Admin area)*}
		{elseif $curpage == "fund_history"}
			{include file="page_fund_history.tpl"}		 		{*Show Fund account history (Admin area)*}
		{elseif $curpage == "template"}
			{include file="page_template.tpl"}						{*Templates (Admin area)*}
		{elseif $curpage == "statistics_common"}					
			{include file="page_statistics_common.tpl"}		{*Common statistics  (Admin area)*}
		{elseif $curpage == "statistics_users"}					
			{include file="page_statistics_users.tpl"}		{*Users statistics  (Admin area)*}
		{elseif $curpage == "statistics_jobs_by_cat"}					
			{include file="page_statistics_jobs_by_cat.tpl"}	{*Jobs by categories statistics  (Admin area)*}
		{elseif $curpage == "statistics_ip"}					
			{include file="page_statistics_ip.tpl"}				{*IP address statistics  (Admin area)*}
		{elseif $curpage == "job_search_log"}					
			{include file="page_job_search_log.tpl"}			{*Job search log (Admin area)*}
		{elseif $curpage == "job_search_log_work"}					
			{include file="page_job_search_log_work.tpl"}	{*Job search log work (Admin area)*}
		{elseif $curpage == "job_search_emails"}					
			{include file="page_job_search_emails.tpl"}		{*Job search alert E-mails (Admin area)*}
		{elseif $curpage == "job_search_emails_work"}					
			{include file="page_job_search_emails_work.tpl"}	{*Job search alert E-mails work (Admin area)*}
		{elseif $curpage == "subm_job_ads"}					
			{include file="page_subm_job_ads.tpl"}				{*Submit Job ads (Admin area)*}
		{elseif $curpage == "jobs_list_common"}					
			{include file="page_jobs_list_common.tpl"}		{*Common jobs list (Admin area)*}
		{elseif $curpage == "jobs_list_common_work"}
			{include file="page_jobs_list_common_work.tpl"}	{*Common jobs work (Admin area)*}


		{elseif $curpage == "adv_info"}					
			{include file="page_adv_info.tpl"}						{*Info (Advertiser area)*}
		{elseif $curpage == "adv_profile"}						
			{include file="page_adv_profile.tpl"}					{*Profile (Advertiser area)*}
		{elseif $curpage == "adv_advertisements"}						
			{include file="page_adv_advertisements.tpl"}	{*Advertisements (Advertiser area)*}
		{elseif $curpage == "adv_advertisement_keyword_ad_work"}						
			{include file="page_adv_advertisement_keyword_ad_work.tpl"}	{*Keyword Advertisement (Advertiser area)*}
		{elseif $curpage == "adv_advertisement_keyword_ad"}						
			{include file="page_adv_advertisement_keyword_ad.tpl"}	{*Advertisements Keyword List (Advertiser area)*}
		{elseif $curpage == "adv_advertisement_jobs_from_my_site_work"}
			{include file="page_adv_advertisement_jobs_from_my_site_work.tpl"}	{* Sponsor jobs from my site work (Advertiser area)*}
		{elseif $curpage == "adv_advertisement_report_keyword"}						
			{include file="page_adv_advertisement_report_keyword.tpl"}	{*Advertisements Keyword Report (Advertiser area)*}
		{elseif $curpage == "adv_advertisement_report_job"}						
			{include file="page_adv_advertisement_report_job.tpl"}	{*Advertisements Job Report (Advertiser area)*}
		{elseif $curpage == "adv_fund_account"}					
			{include file="page_adv_fund_account.tpl"}			{*Fund account (Advertiser area)*}
		{elseif $curpage == "adv_fund_history"}					
			{include file="page_adv_fund_history.tpl"}			{*Fund history (Advertiser area)*}
		{elseif $curpage == "adv_fund_history_work"}					
			{include file="page_adv_fund_history_work.tpl"}	{*Fund history info (Advertiser area)*}


		{elseif $curpage == "pub_info"}					
			{include file="page_pub_info.tpl"}							{*Info (Publisher area)*}
		{elseif $curpage == "pub_profile"}
			{include file="page_pub_profile.tpl"}						{*Profile (Publisher area)*}
		{elseif $curpage == "pub_get_jobroll"}
			{include file="page_pub_get_jobroll.tpl"}				{*Create Jobroll (Publisher area)*}
		{elseif $curpage == "pub_get_job_searchbox"}						
			{include file="page_pub_get_job_searchbox.tpl"}	{*Create Job Searchbox (Publisher area)*}
		{elseif $curpage == "pub_get_job_textlink"}						
			{include file="page_pub_get_job_textlink.tpl"}	{*Create Text Link (Publisher area)*}
		{elseif $curpage == "pub_get_xmlfeed"}						
			{include file="page_pub_get_xmlfeed.tpl"}				{*Create XML Feed (Publisher area)*}
		{elseif $curpage == "pub_payment_request"}					
			{include file="page_pub_payment_request.tpl"}		{*Payment request (Publisher area)*}
		{elseif $curpage == "pub_payment_history"}					
			{include file="page_pub_payment_history.tpl"}		{*Payment history (Publisher area)*}
		{elseif $curpage == "pub_payment_history_work"}					
			{include file="page_pub_payment_history_work.tpl"}	{*Payment history work (Publisher area)*}
		{elseif $curpage == "pub_traffic_summary"}					
			{include file="page_pub_traffic_summary.tpl"}		{*Traffic Summary  (Publisher area)*}


		{elseif $curpage == "adv_registration"}					
			{include file="page_adv_registration.tpl"}			{*Regiter new advertiser (Visitor area)*}
		{elseif $curpage == "pub_registration"}					
			{include file="page_pub_registration.tpl"}			{*Regiter new publisher (Visitor area)*}
		{elseif $curpage == "mem_registration"}					
			{include file="page_mem_registration.tpl"}			{*Regiter new member (Visitor area)*}
		{elseif $curpage == "m_email_confirm"}					
			{include file="page_m_email_confirm.tpl"}				{*E-mail confirmation (Visitor area)*}
		{elseif $curpage == "m_registration_success"}					
			{include file="page_m_registration_success.tpl"}{*Successful new member registration (Visitor area)*}
		{elseif $curpage == "forgotpass"}					
			{include file="page_forgotpass.tpl"}						{*Forgot password (Visitor area)*}
		{elseif $curpage == "forgot_pass_success"}				
			{include file="page_forgot_pass_success.tpl"}		{*Successful forgot password (Visitor area)*}


		{elseif $curpage == "mem_info"}					
			{include file="page_mem_info.tpl"}							{*Info (Member area)*}
		{elseif $curpage == "member_profile"}						
			{include file="page_member_profile.tpl"}				{*Profile (Member area)*}
		{elseif $curpage == "member_jobalert"}
			{include file="page_member_jobalert.tpl"}				{*Job Alerts (Member area)*}
		{elseif $curpage == "member_jobalert_work"}					
			{include file="page_member_jobalert_work.tpl"}	{*Job Alerts work (Member area)*}


		{elseif $curpage == "m_xmlfeed"}					
			{include file="page_m_xmlfeed.tpl"}							{*XML Feed (Member area)*}

		{elseif $curpage == "sendbugreport"}					
			{include file="page_sendbugreport.tpl"}					{*Send Bug Report (Admin and Member area)*}

		{/if}
	</td>
</tr>
</table>
<!-- Data table -->