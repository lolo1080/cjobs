{*[start_template_header_part]*}
{include file="main_header.tpl"}
{*[end_template_header_part]*}

{*[start_template_content_part]*}

<table width="100%" border="0">
<!-- Header -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%">
    <table width="100%">
    <tr>
      <td width="216">
        <a href="http://localhost/cjobs/"><img src="http://localhost/cjobs/frontend/images/es_job_search_engine.gif" alt="ES Job Search Engine" title="ES Job Search Engine" width="216" height="100" style="border: 0px;" /></a>
      </td>
      <td valign="top" align="right">
        {$JobsInCountry}<br />
        <a class="toplinks" href="http://localhost/cjobs/myjobs/">My Jobs</a> - <a class="toplinks" href="http://localhost/cjobs/myarea/">My Area</a><br />
        <div style="font-size:150%;color:#999;float:left;padding-top:30px;padding-left:50px;">Advertise on ES Job Search Engine</div>
      </td>
    </tr>
    </table>
  </td>
  <td width="10%">&nbsp;</td>
</tr>

<!-- Search boxes -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%" style="background-color:#e6e6e6;padding-top:10px;padding-bottom:10px;" align="center">
    <div align="left" style="color:#400000;font-weight:bold;font-size:95%;padding-left:10px;padding-bottom:5px;">Advertiser Login</div>
    <form action="http://localhost/cjobs/management/index.php" method="POST">
    <input type="hidden" name="{$SNAME}" value="{$SID}" />
    <input type="hidden" name="login" value="1" />
    <input type="hidden" name="clrsess" value="1" />
    <table>
    <tr>
      <td valign="bottom">
        <span style="color:#720000;font-weight:bold;font-size:95%;"><label for="what">E-mail</label></span><br />
        <span style="font-size:70%;">Enter your e-mail address</span><br />
        <input type="text" id="username" name="username" style="width: 280px; height:18px; background-color: #f9f9f9; border: 1px solid #999999;" />
      </td>
      <td valign="bottom">
        <span style="color:#720000;font-weight:bold;font-size:95%;"><label for="where">Password</label></span><br />
        <span style="font-size:70%;">Enter your password</span><br />
        <input type="password" id="userpass" name="userpass" style="width: 280px; height:18px; background-color: #f9f9f9; border: 1px solid #999999;" />
      </td>
      <td valign="bottom" nowrap>
 	      <input type="submit" value="   Login   " class="simpleButton" style="width:90px;">
      </td>
    </tr>
    <tr>
      <td valign="bottom" nowrap colspna="3">
        <a href="http://localhost/cjobs/management/adv_registration.php" class="ajs">Create an Account</a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="http://localhost/cjobs/management/forgot_pass.php?user_type=advertisers" class="ajs">Forgot password</a>
      </td>
    </tr>
    </table>
    </form>
  </td>
  <td width="10%">&nbsp;</td>
</tr>

<!-- Categories list -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%" style="line-height:105%;padding-top:5px;padding-left:5px;background-color:#f8f8f8;font-size:95%;">
		<div align="center" style="padding-bottom:5px;"><b>Increase your job visibility and be seen by millions of quality candidates!</b></div>
    Drive targeted job seekers to your jobs.
    Pay only when candidates click to visit your site. Track the results.
    <div align="center">
      <img src="http://localhost/cjobs/frontend/images/advertiser_advertisements.gif" alt="ES Job Search Advertiser Advertisements" title="ES Job Search Advertiser Advertisements" width="700" height="503" style="border:0px;padding-top:10px;padding-bottom:10px;" />
    </div>

     <span style="padding: 0px 4px; font-weight: bold; text-decoration: none; color: #fff; background: #ff6600; border: #CCC 1px solid;"> 1</span>

     Sponsored jobs are displayed on this site and reach millions of qualified candidates. Your sponsored jobs are highlighted
     in yellow and appear when they are match to job seekers queries. You pay only when a candidate clicks on your job.

     <p style="padding-top:5px;padding-bottom:2px;"><b>How it Works</b></p>
     <ul style="padding-left:15px;font-size:95%;">
       <li>Give us your jobs - we'll wrap or crawl your site</li>
       <li>Your jobs are highlighted on site above the job search results</li>
       <li>Pay only on performance - when users click on your sponsored jobs to find out more</li>
       <li>Manage your sponsored jobs with daily and monthly budgets</li>
       <li>Track and fine-tune your sponsored job campaign using your online account</li>
       <li>Built-in Discounter monitors your competition and automatically reduces your actual cost-per-click so you pay the
           lowest price possible for your sponsored job</li>
     </ul>

     <p style="padding-top:5px;padding-bottom:2px;"><b>Sponsored Jobs Benefits</b></p>
     <ul style="padding-left:15px;font-size:95%;">
       <li>Provides maximum exposure for your opportunities</li>
       <li>Drive more of the right candidates at lower cost</li>
       <li>Quick and easy setup - no need to write ad copy</li>
       <li>Promotes all your jobs at once - no need to post jobs</li>
       <li>Protects and builds your brand by sending candidates directly to your jobs</li>
       <li>Provides highly targeted candidates based on job seekers search queries</li>
       <li>Set your budget - no minimums or contracts</li>
     </ul>

     <div style="padding-top:5px;">&nbsp;</div>

     <span style="padding: 0px 4px; font-weight: bold; text-decoration: none; color: #fff; background: #ff6600; border: #CCC 1px solid;"> 2</span>

     Create advertisements with text relevant to your needs. Your ads appear when job seekers queries match keywords or phrases
     chosen by you. Job seekers click through to any page on your Web site that you want and you only pay when your keyword ads
     are clicked on.

     <p style="padding-top:5px;padding-bottom:2px;"><b>How it Works</b></p>
     <ul style="padding-left:15px;font-size:95%;">
       <li>Keyword ads appear at the right column of job search results page as Sponsored Links</li>
       <li>Your ads are only shown when users search with your chosen keywords</li>
       <li>Pay only on performance - when users click on your ads to visit your website</li>
       <li>Manage your keyword ads with daily and monthly budgets</li>
       <li>Track and fine-tune your keyword ads campaign using your online account</li>
       <li>Built-in Discounter monitors your competition and automatically reduces your actual cost-per-click so you pay the
           lowest price possible for your sponsored job</li>
     </ul>

     <p style="padding-top:5px;padding-bottom:2px;"><b>Keyword Ads Benefits</b></p>
     <ul style="padding-left:15px;font-size:95%;">
       <li>Reach millions of targeted job seekers</li>
       <li>Provides highly targeted traffic based on job seekers search queries</li>
       <li>Drive more of the right candidates at lower cost</li>
       <li>Delivers local job seekers based on optional location targeting</li>
       <li>Quick and easy setup</li>
       <li>Protects and builds your brand by sending candidates directly to your website</li>
       <li>Highly measurable - you choose a landing page for each keyword ad</li>
       <li>Low cost per click and per conversion</li>
     </ul>


  </td>
  <td width="10%">&nbsp;</td>
</tr>

<!-- Footer links -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%" align="center">
    <div style="clear: both;margin: 5px 10px; border-top: 1px solid #ccc; color: #333;"></div>
    <table width="60%">
    <tr>
      <td><a class="footer" href="http://localhost/cjobs/">Home Page</a></td>
      <td><a class="footer" href="http://localhost/cjobs/browse_jobs/browse_types/">Browse Jobs</a></td>
      <td><a class="footer" href="http://localhost/cjobs/advertisers/">Advertisers</a></td>
      <td><a class="footer" href="http://localhost/cjobs/publishers/">Publishers</a></td>
    </tr>
    </table>
  </td>
  <td width="10%">&nbsp;</td>
</tr>

<!-- Footer text -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%" align="center" style="padding-top:25px;">
    <span style="font-size:11px;color:#700000;">
      Copyright &copy; 2011 <a href="http://www.es-job-search-engine.com" class="bottominfo_link" title="Job Search Engine script. Read more information">ES Job Search Engine</a>. All rights reserved.<br />
      Powered by <a href="http://www.energyscripts.com" class="bottominfo_link" title="EnergyScripts: web development company">EnergyScripts</a>.
    </span>
  </td>
  <td width="10%">&nbsp;</td>
</tr>
</table>

{*[end_template_content_part]*}

{*[start_template_footer_part]*}
{include file="main_footer.tpl"}
{*[end_template_footer_part]*}