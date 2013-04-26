{*[start_template_header_part]*}
<html>
<head>
<style type="text/css"><!--
{literal}
body { background-color: #fff; margin: 0; padding: 0; }  
{/literal}
{if $JobrollFormat eq "120x600"}
{literal}
.ad_120x600 { border:1px solid #aaa; background-color: #fff; }
.ad_120x600 td { font-size: 10px; font-family: Arial, sans-serif; color: #000; }
.ad_120x600 label { font: bold 13px/1.4 Arial, sans-serif; color: #f60;  margin-left: 7px; }
.ad_120x600 div.content { padding-left: 7px; }
.ad_120x600 .input_text { width: 106px; margin-bottom: 3px; margin-left: 7px; }
{/literal}
{elseif $JobrollFormat eq "160x600"}
{literal}
.ad_160x600 { border:1px solid #aaa; background-color: #fff; }
.ad_160x600 td { font-size: 11px; font-family: Arial, sans-serif; color: #000; }
.ad_160x600 label { font: bold 14px/1.4 Arial, sans-serif; color: #f60;  margin-left: 7px; }
.ad_160x600 div.content { padding-left: 7px; }
.ad_160x600 .input_text { width: 146px; margin-bottom: 3px; margin-left: 7px; }
{/literal}
{elseif $JobrollFormat eq "300x250"}
{literal}
.ad_300x250 { border:1px solid #aaa; background-color: #fff; }
.ad_300x250 td { font-size: 12px; font-family: Arial, sans-serif; color: #000; }
.ad_300x250 label { font: bold 14px/1.4 Arial, sans-serif; color: #f60;  margin-left: 7px; }
.ad_300x250 div.content { padding-left: 7px; }
.ad_300x250 .input_text { width: 236px; margin-bottom: 3px; margin-left: 7px; }
{/literal}
{elseif $JobrollFormat eq "728x90"}
{literal}
.ad_728x90 { border:1px solid #aaa; background-color: #fff; }
.ad_728x90 td { font-size: 10px; font-family: Arial, sans-serif; color: #000; }
.ad_728x90 label { font: bold 14px/1.4 Arial, sans-serif; color: #f60;  margin-left: 7px; }
.ad_728x90 div.content { padding-left: 7px; }
.ad_728x90 .input_text { width: 186px; margin-bottom: 3px; margin-left: 7px; }
{/literal}
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
      <form class="jobsearch_form" name="search" method="GET" target="_top" action="{$BaseSiteURL}jobs/">
      <td width="40">
         &nbsp;
      </td>
      <td>
        <a href="{$BaseSiteURL}" target="_top"><img src="{$BaseSiteURL}frontend/images/logo_small.gif" width="140" height="64" alt="ES Job Search Engine" border="0" /></a>
      </td>
      <td>
        <label for="indeed_query">what</label><br>
        <input class="input_text" id="what" name="what" type="text" />
        <div class="content">job title, keywords</div><br>
      </td>
      <td>
        <label for="indeed_location">where</label><br>
        <input class="input_text" id="where" name="where" type="text" />
        <div class="content">city, state, zip</div><br>
        <input id="job_channel" name="job_channel" type="hidden" value="{$JobChannel}" />
        <input id="jobroll_publisher_id" name="jobroll_publisher_id" type="hidden" value="{$JobrollPublisherID}" />
      </td>
      <td>
        <input type="submit" value="Find Jobs"><br>
        &nbsp;<br>
      </td>
      <td width="40">
         &nbsp;
      </td>
      </form>
    </tr>
  </table>

{else}

{if $JobrollFormat eq "120x600"}
  <table class="ad_120x600" width="120" height="600" cellspacing="0" cellpadding="0" border="0">
{elseif $JobrollFormat eq "160x600"}
  <table class="ad_160x600" width="160" height="600" cellspacing="0" cellpadding="0" border="0">
{elseif $JobrollFormat eq "300x250"}
  <table class="ad_300x250" width="300" height="250" cellspacing="0" cellpadding="0" border="0">
{/if}
    <tr height="30%">
      <td>&nbsp;</td>
    </tr>
    <tr height="36">
      <td>
        <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>             
          {if $JobrollFormat eq "120x600"}
          <td align="center"><a href="{$BaseSiteURL}" target="_top"><img src="{$BaseSiteURL}frontend/images/logo_very_small.gif" width="120" height="55" alt="ES Job Search Engine" border="0" /></a></td>
          {elseif $JobrollFormat eq "160x600"}
          <td align="center"><a href="{$BaseSiteURL}" target="_top"><img src="{$BaseSiteURL}frontend/images/logo_small.gif" width="140" height="64" alt="ES Job Search Engine" border="0" /></a></td>
          {elseif $JobrollFormat eq "300x250"}
          <td align="center"><a href="{$BaseSiteURL}" target="_top"><img src="{$BaseSiteURL}frontend/images/logo_small.gif" width="140" height="64" alt="ES Job Search Engine" border="0" /></a></td>
          {/if}
        </tr>
        <tr>
          <form class="jobsearch_form" name="search" method="GET" target="_top" action="{$BaseSiteURL}jobs/">
          <td>
            <label for="indeed_query">what</label><br>
            <input class="input_text" id="what" name="what" type="text" />
            <div class="content">job title, keywords</div>
          </td>
        </tr>
        <tr>
          <td>
            <label for="indeed_location">where</label><br>
            <input class="input_text" id="where" name="where" type="text" />
            <div class="content">city, state, zip</div>
            <input id="job_channel" name="job_channel" type="hidden" value="{$JobChannel}" />
            <input id="jobroll_publisher_id" name="jobroll_publisher_id" type="hidden" value="{$JobrollPublisherID}" />
          </td>
        </tr>
        <tr>
          <td>
            <div class="content"><input type="submit" value="Find Jobs" /></div>
          </td>
          </form>
        </tr>
        </table>
      <td>
    </tr>
    <tr height="34%">
      <td>&nbsp;</td>
    </tr>
  </table>

{/if}
</body>

{*[end_template_content_part]*}
{*[start_template_footer_part]*}
</html>
{*[end_template_footer_part]*}