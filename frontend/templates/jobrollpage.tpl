{*[start_template_header_part]*}
<html>
<head>
<style type="text/css"><!--
body {ldelim} background-color: {$JobRollSettings.colors_bg}; margin: 0; padding: 0; {rdelim}
a, a:link {ldelim} color: {$JobRollSettings.colors_link}; {rdelim}
a:active {ldelim} color: {$JobRollSettings.colors_link}; {rdelim}
a:visited {ldelim} color: {$JobRollSettings.colors_link}; {rdelim}
.accent {ldelim} color: {$JobRollSettings.colors_accent}; background-color: {$JobRollSettings.colors_accent}; height: 2px; border-width: 0px; {rdelim}
div.content {ldelim} padding-left: 2px; {rdelim}

{if $JobrollFormat eq "120x600"}
.ad_120x600 {ldelim} border: 1px solid {$JobRollSettings.colors_border}; background-color: {$JobRollSettings.colors_bg}; {rdelim}
.ad_120x600 td {ldelim} padding-left:2px; font-size: 10px; font-family: Arial, sans-serif; color: {$JobRollSettings.colors_text}; {rdelim}
.ad_120x600 .jobtitle {ldelim} color: {$JobRollSettings.colors_job_title}; font-weight: bold; font-size: 11px; font-family: Arial, sans-serif; {rdelim}
.ad_120x600 .company {ldelim} color: {$JobRollSettings.colors_company}; {rdelim}
.ad_120x600 .location {ldelim} color: {$JobRollSettings.colors_location}; white-space: nowrap; {rdelim}
.ad_120x600 .source {ldelim} color: {$JobRollSettings.colors_source}; white-space: nowrap; {rdelim}
.ad_120x600 .title {ldelim} color: {$JobRollSettings.colors_title}; padding-left:5px; font-size: 14px; vertical-align: center; font-family: Arial, sans-serif;  overflow: hidden; {rdelim}
.ad_120x600 .more {ldelim} padding-left:5px; font-weight: bold; font-size: 11px; font-family: Arial, sans-serif; {rdelim}
.ad_120x600 label {ldelim} font: bold 11px/1.4 Arial, sans-serif; {rdelim}
.ad_120x600 .input_text {ldelim} width:106px; margin-bottom: 3px; {rdelim}
{elseif $JobrollFormat eq "160x600"}
.ad_160x600 {ldelim} border: 1px solid {$JobRollSettings.colors_border}; background-color: {$JobRollSettings.colors_bg}; {rdelim}
.ad_160x600 td {ldelim} padding-left:2px; font-size: 11px; font-family: Arial, sans-serif; color: {$JobRollSettings.colors_text}; {rdelim}
.ad_160x600 .jobtitle {ldelim} color: {$JobRollSettings.colors_job_title}; font-weight: bold; font-size: 11px; font-family: Arial, sans-serif; {rdelim}
.ad_160x600 .company {ldelim} color: {$JobRollSettings.colors_company}; {rdelim}
.ad_160x600 .location {ldelim} color: {$JobRollSettings.colors_location}; white-space: nowrap; {rdelim}
.ad_160x600 .source {ldelim} color: {$JobRollSettings.colors_source}; white-space: nowrap; {rdelim}
.ad_160x600 .title {ldelim} color: {$JobRollSettings.colors_title}; padding-left:5px; font-size: 18px; vertical-align: center; font-family: Arial, sans-serif;  overflow: hidden; {rdelim}
.ad_160x600 .more {ldelim} padding-left:5px; font-weight: bold; font-size: 11px; font-family: Arial, sans-serif; {rdelim}
.ad_160x600 label {ldelim} font: bold 14px/1.4 Arial, sans-serif; {rdelim}
.ad_160x600 .input_text {ldelim} width:146px; margin-bottom: 3px; {rdelim}
{elseif $JobrollFormat eq "300x250"}
.ad_300x250 {ldelim} border: 1px solid {$JobRollSettings.colors_border}; background-color: {$JobRollSettings.colors_bg}; {rdelim}
.ad_300x250 td {ldelim} padding-left:2px; font-size: 12px; font-family: Arial, sans-serif; color: {$JobRollSettings.colors_text}; {rdelim}
.ad_300x250 .jobtitle {ldelim} color: {$JobRollSettings.colors_job_title}; font-weight: bold; font-size: 12px; font-family: Arial, sans-serif; {rdelim}
.ad_300x250 .company {ldelim} color: {$JobRollSettings.colors_company}; {rdelim}
.ad_300x250 .desciption {ldelim} color: {$JobRollSettings.colors_company}; {rdelim}
.ad_300x250 .location {ldelim} color: {$JobRollSettings.colors_location}; white-space: nowrap; {rdelim}
.ad_300x250 .source {ldelim} color: {$JobRollSettings.colors_source}; white-space: nowrap; {rdelim}
.ad_300x250 .title {ldelim} color: {$JobRollSettings.colors_title}; padding-left:5px; font-size: 18px; vertical-align: center; font-family: Arial, sans-serif;  overflow: hidden; {rdelim}
.ad_300x250 .more {ldelim} padding-left:5px; font-weight: bold; font-size: 12px; font-family: Arial, sans-serif; {rdelim}
.ad_300x250 label {ldelim} font: bold 14px/1.4 Arial, sans-serif; {rdelim}
.ad_300x250 .input_text {ldelim} width:186px; margin-bottom: 0px; {rdelim}
{elseif $JobrollFormat eq "728x90"}
.ad_728x90 {ldelim} border: 1px solid {$JobRollSettings.colors_border}; background-color: {$JobRollSettings.colors_bg}; {rdelim}
.ad_728x90 td {ldelim} padding-left:2px; font-size: 10px; font-family: Arial, sans-serif; color: {$JobRollSettings.colors_text}; {rdelim}
.ad_728x90 .jobtitle {ldelim} color: {$JobRollSettings.colors_job_title}; font-weight: bold; font-size: 11px; font-family: Arial, sans-serif; {rdelim}
.ad_728x90 .company {ldelim} color: {$JobRollSettings.colors_company}; {rdelim}
.ad_728x90 .location {ldelim} color: {$JobRollSettings.colors_location}; white-space: nowrap; {rdelim}
.ad_728x90 .source {ldelim} color: {$JobRollSettings.colors_source}; white-space: nowrap; {rdelim}
.ad_728x90 .title {ldelim} color: {$JobRollSettings.colors_title}; padding-left:5px; font-size: 14px; vertical-align: center; font-family: Arial, sans-serif;  overflow: hidden; {rdelim}
{/if}
-->
</style>
</head>
{*[end_template_header_part]*}
{*[start_template_content_part]*}

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

{if $JobrollFormat eq "728x90"}
  <table class="ad_728x90" width="728" height="90" cellspacing="0" cellpadding="0" border="0">
    <tr>
    {foreach from=$JobRollJobsList key=key item=item name="JobRollJobsList"}
    {if $smarty.foreach.JobRollJobsList.iteration < 5}
      <td width="25%">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td class="jobtitle"><a href="{$item.clickurl}" target="_blank">{$item.title}</a></td>
        </tr>
        <tr>
          <td class="company">{$item.company_name}</td>
        </tr>
        <tr>
          <td class="location">{$item.city}, {if ($item.regionname && ($item.regionname != ''))}{$item.regionname}{else}{$item.region}{/if}</td>
        </tr>
        <tr>
          <td class="source">From {$item.feed_name}</td>
        </tr>
        </table>
      </td>
    {/if}
    {/foreach}
    </tr>
    <tr>
      <td colspan="4"><div style="float:left;">{$JobRollSettings.search_term}</div> <div style="float:right;"><a href="{$BaseSiteURL}" target="_top">Jobs by ES Job Search Engine</a></div></td>
    </tr>
  </table>

{elseif $JobrollFormat eq "300x250"}

  <table class="ad_300x250" width="300" height="250" cellspacing="0" cellpadding="0" border="0">
    <tr>
      <td class="title">{$JobRollSettings.search_term}</td>
    </tr>
    {foreach from=$JobRollJobsList key=key item=item name="JobRollJobsList"}
    {if $smarty.foreach.JobRollJobsList.iteration < 2}
    <tr>
      <td>
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td class="jobtitle"><a href="{$item.clickurl}" target="_blank">{$item.title}</a></td>
        </tr>
        <tr>
          <td class="company">{$item.company_name}</td>
        </tr>
        <tr>
          <td class="desciption">{$item.description}</td>
        </tr>
        <tr>
          <td class="location">{$item.city}, {if ($item.regionname && ($item.regionname != ''))}{$item.regionname}{else}{$item.region}{/if}</td>
        </tr>
        <tr>
          <td class="source">From {$item.feed_name}</td>
        </tr>
        </table>
      </td>
    </tr>
    {/if}
    {/foreach}
    <tr>
      <td class="more">
        <a href="{$JobRollMoreLink}" target="_top" style="font-weight:bold">more &raquo;</a>
      </td>
    </tr>
    <tr>
      <td align="left">
        <hr class="accent" />
      </td>
    </tr>
    <tr>
      <form class="jobsearch_form" name="search" method="GET" target="_top" action="{$BaseSiteURL}jobs/">
      <td>
        <div class="content">
          <label for="what_where">Job Search</label><br>
          <input class="input_text" id="what_where" name="what_where" type="text" value="Enter terms..." onfocus="this.value='';" />&nbsp;<input type="submit" value="Find Jobs" style="margin-bottom: 1px" />
          <input id="job_channel" name="job_channel" type="hidden" value="{$JobChannel}" />
          <input id="jobroll_publisher_id" name="jobroll_publisher_id" type="hidden" value="{$JobrollPublisherID}" />
        </div>
      </td>
    </tr>
    <tr>
      <td valign="bottom" align="right" nowrap style="padding-right: 2px;">
        <a href="{$BaseSiteURL}" target="_top">Jobs by ES Job Search Engine</a>
      </td>
      </form>
    </tr>
  </table>

{else}

{if $JobrollFormat eq "120x600"}
  <table class="ad_120x600" width="120" height="600" cellspacing="0" cellpadding="0" border="0">
{elseif $JobrollFormat eq "160x600"}
  <table class="ad_160x600" width="160" height="600" cellspacing="0" cellpadding="0" border="0">
{/if}
    <tr>
      <td class="title">{$JobRollSettings.search_term}</td>
    </tr>
    {foreach from=$JobRollJobsList key=key item=item name="JobRollJobsList"}
    {if $smarty.foreach.JobRollJobsList.iteration < 5}
    <tr>
      <td>
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td class="jobtitle"><a href="{$item.clickurl}" class="jobtitle" target="_blank">{$item.title}</a></td>
        </tr>
        <tr>
          <td class="company">{$item.company_name}</td>
        </tr>
        <tr>
          <td class="location">{$item.city}, {if ($item.regionname && ($item.regionname != ''))}{$item.regionname}{else}{$item.region}{/if}</td>
        </tr>
        <tr>
          <td class="source">From {$item.feed_name}</td>
        </tr>
        </table>
      </td>
    </tr>
    {/if}
    {/foreach}
    <tr>
      <td class="more">
        <a href="{$JobRollMoreLink}" target="_top" style="font-weight:bold">more &raquo;</a>
      </td>
    </tr>
    <tr>
      <td align="left">
        <hr class="accent" />
      </td>
    </tr>
    <tr>
      <form class="jobsearch_form" name="search" method="GET" target="_top" action="{$BaseSiteURL}jobs/">
      <td>
        <div class="content">
          <label for="what_where">Job Search</label><br>
          <input class="input_text" id="what_where" name="what_where" type="text" value="Enter terms..." onfocus="this.value='';" /><br />
          <input id="job_channel" name="job_channel" type="hidden" value="{$JobChannel}" />
          <input id="jobroll_publisher_id" name="jobroll_publisher_id" type="hidden" value="{$JobrollPublisherID}" />
          <input type="submit" value="Find Jobs" style="margin-bottom: 1px" />
        </div>
      </td>
    </tr>
    <tr>
      <td valign="bottom" align="right" style="padding-right: 2px;">
        <a href="{$BaseSiteURL}" target="_top">Jobs by ES Job Search</a>
      </td>
      </form>
    </tr>
  </table>

{/if}

</body>

{*[end_template_content_part]*}
{*[start_template_footer_part]*}
</html>
{*[end_template_footer_part]*}