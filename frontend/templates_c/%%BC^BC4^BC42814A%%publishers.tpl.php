<?php /* Smarty version 2.6.19, created on 2013-05-01 01:05:50
         compiled from publishers.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


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
        <?php echo $this->_tpl_vars['JobsInCountry']; ?>
<br />
        <a class="toplinks" href="http://localhost/cjobs/myjobs/">My Jobs</a> - <a class="toplinks" href="http://localhost/cjobs/myarea/">My Area</a><br />
        <div style="font-size:150%;color:#999;float:left;padding-top:30px;padding-left:50px;">Publishers: Include Our Jobs to Your Website and Get Paid</div>
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
    <div align="left" style="color:#400000;font-weight:bold;font-size:95%;padding-left:10px;padding-bottom:5px;">Publisher Login</div>
    <form action="http://localhost/cjobs/management/index.php" method="POST">
    <input type="hidden" name="<?php echo $this->_tpl_vars['SNAME']; ?>
" value="<?php echo $this->_tpl_vars['SID']; ?>
" />
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
        <a href="http://localhost/cjobs/management/pub_registration.php" class="ajs">Create an Account</a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="http://localhost/cjobs/management/forgot_pass.php?user_type=publishers" class="ajs">Forgot password</a>
      </td>
    </tr>
    </table>
    </form>
  </td>
  <td width="10%">&nbsp;</td>
</tr>

<!-- Info -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%" style="line-height:105%;padding-top:5px;padding-left:5px;background-color:#f8f8f8;font-size:95%;">
		<div align="center" style="padding-bottom:5px;"><b>Add Jobs to Your Site and Get Paid!</b></div>
		Our publisher program makes it easy to add relevant job listings to your website. You can add a Jobroll, Job Search Box,
		Job Text Link, XML Job Search Feed.<br />

		<div align="center">
		<table style="width: 95%; border-right: #ebe8db 1px solid; border-top: #ebe8db 1px solid; border-left: #ebe8db 1px solid; border-bottom: #ebe8db 1px solid; background-color: #fafaf4; margin-left: 0px; margin-top: 10px; margin-bottom: 10px;">
		<tr>
			<td colspan="2">
				<p><b>Jobroll</b></p>
				<p style="padding-top:5px;">
					The Jobroll is an easy way to add fresh and relevant job listings to your website or blog. The Jobroll can be added to
					your site in minutes, is continuously updated with new pay-per-click job links and can be targeted to the interests and
					locations of your users. You get paid when your users click on the jobs in your Jobroll - there's no need to sell job
					postings or deal with clients.
				</p>
			</td>
		</tr>
		<tr>
			<td align="center">
				<p style="padding-top:5px;">
					<small><b>Jobroll management area</b></small>
				</p>
			</td>
			<td align="center">
				<p style="padding-top:5px;">
					<small><b>Your website</b></small>
				</p>
			</td>
		</tr>
		<tr>
			<td align="center">
				<img src="http://localhost/cjobs/frontend/images/jobroll_admin_area.gif" alt="JobRoll: Admin Area" title="JobRoll: Admin Area" width="523" height="400" style="border:0px;padding-top:5px;padding-bottom:5px;" />
			</td>
			<td align="center">
				<img src="http://localhost/cjobs/frontend/images/jobroll_frontend_area.gif" alt="JobRoll: Publisher Website" title="JobRoll: Publisher Website" width="300" height="400" style="border:0px;padding-top:5px;padding-bottom:5px;" />
			</td>
		</tr>
		</table>
		</div>

		<div align="center">
		<table style="width: 95%; border-right: #ebe8db 1px solid; border-top: #ebe8db 1px solid; border-left: #ebe8db 1px solid; border-bottom: #ebe8db 1px solid; background-color: #fafaf4; margin-left: 0px; margin-top: 10px; margin-bottom: 10px;">
		<tr>
			<td colspan="2">
				<p><b>Job Search Box</b></p>
				<p style="padding-top:5px;">
					You may add our Job Search Boxes to your website.
				</p>
			</td>
		</tr>
		<tr>
			<td align="center">
				<p style="padding-top:5px;">
					<small><b>Job Search Box management area</b></small>
				</p>
			</td>
			<td align="center">
				<p style="padding-top:5px;">
					<small><b>Your website</b></small>
				</p>
			</td>
		</tr>
		<tr>
			<td align="center">
	      <img src="http://localhost/cjobs/frontend/images/jobsearchbox_admin_area.gif" alt="Job Search Box: Admin Area" title="Job Search Box: Admin Area" width="560" height="400" style="border:0px;padding-top:5px;padding-bottom:5px;" />
			</td>
			<td align="center" valign="top">
	      <img src="http://localhost/cjobs/frontend/images/jobsearchbox_frontend_area.gif" alt="Job Search Box: Publisher Website" title="Job Search Box: Publisher Website" width="300" height="233" style="border:0px;padding-top:5px;padding-bottom:5px;" />
			</td>
		</tr>
		</table>
		</div>

		<div align="center">
		<table style="width: 95%; border-right: #ebe8db 1px solid; border-top: #ebe8db 1px solid; border-left: #ebe8db 1px solid; border-bottom: #ebe8db 1px solid; background-color: #fafaf4; margin-left: 0px; margin-top: 10px; margin-bottom: 10px;">
		<tr>
			<td colspan="2">
				<p><b>Job Text Link</b></p>
				<p style="padding-top:5px;">
					You may add our Job Text Links to your website.
				</p>
			</td>
		</tr>
		<tr>
			<td align="center">
				<p style="padding-top:5px;">
					<small><b>Job Text Link management area</b></small>
				</p>
			</td>
			<td align="center">
				<p style="padding-top:5px;">
					<small><b>Your website</b></small>
				</p>
			</td>
		</tr>
		<tr>
			<td align="center">
	      <img src="http://localhost/cjobs/frontend/images/jobtextlink_admin_area.gif" alt="Job Text Link: Admin area" title="Job Text Link: Admin area" width="621" height="400" style="border:0px;padding-top:5px;padding-bottom:5px;" />
			</td>
			<td align="center" valign="top">
	      <img src="http://localhost/cjobs/frontend/images/jobtextlink_frontend_area.gif" alt="Job Text Link: Publisher Website" title="Job Text Link: Publisher Website" width="300" height="130" style="border:0px;padding-top:5px;padding-bottom:5px;" />
			</td>
		</tr>
		</table>
		</div>

		<div align="center">
		<table style="width: 95%; border-right: #ebe8db 1px solid; border-top: #ebe8db 1px solid; border-left: #ebe8db 1px solid; border-bottom: #ebe8db 1px solid; background-color: #fafaf4; margin-left: 0px; margin-top: 10px; margin-bottom: 10px;">
		<tr>
			<td colspan="2">
				<p><b>XML Job Search Feed API</b></p>
				<p style="padding-top:5px;">
					You may also use our publisher XML Job Search Feed API to develop your own custom job search solutions. The XML feeds
					is activated by websiote admin after your request.
				</p>
			</td>
		</tr>
		<tr>
			<td align="center">
				<p style="padding-top:5px;">
					<small><b>XML Job Search Feed API management area</b></small>
				</p>
			</td>
			<td align="center">
				<p style="padding-top:5px;">
					<small><b>Your website</b></small>
				</p>
			</td>
		</tr>
		<tr>
			<td align="center">
	      <img src="http://localhost/cjobs/frontend/images/jobxmlfeed_admin_area.gif" alt="Job XML Feed: Admina area" title="Job XML Feed: Admina area" width="463" height="400" style="border:0px;padding-top:5px;padding-bottom:5px;" />	      
			</td>
			<td align="center" valign="top">
	      <img src="http://localhost/cjobs/frontend/images/jobxmlfeed_frontend_area.gif" alt="Job XML Feed: Publisher Website" title="Job XML Feed: Publisher Website" width="300" height="375" style="border:0px;padding-top:5px;padding-bottom:5px;" />
			</td>
		</tr>
		</table>
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


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>