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
        <div style="font-size:150%;color:#999;float:left;padding-top:30px;padding-left:50px;">Receive Job Alerts to e-mail</div>
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
    <div align="left" style="color:#400000;font-weight:bold;font-size:95%;padding-left:10px;padding-bottom:5px;">Member Login</div>
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
	      <a href="http://localhost/cjobs/management/mem_registration.php" class="ajs">Create an Account</a>&nbsp;&nbsp;&nbsp;&nbsp;
  	    <a href="http://localhost/cjobs/management/forgot_pass.php?user_type=myarea" class="ajs">Forgot password</a>
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
		<div align="center" style="padding-bottom:5px;"><b>Get E-mail Job Alerts from our website!</b></div>
			It is an easy way to receive relevant job listings to your mail box. You can create own Job Alerts based on various settings.
    <div align="center">
      <img src="http://localhost/cjobs/frontend/images/emial_member_area.gif" alt="ES Member E-mail Job Alert" title="ES Member E-mail Job Alert" width="700" height="533" style="border:0px;padding-top:10px;padding-bottom:10px;" />
    </div>

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