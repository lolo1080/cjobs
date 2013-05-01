<?php /* Smarty version 2.6.19, created on 2013-05-01 01:06:36
         compiled from browse_jobs.tpl */ ?>
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
        <div style="font-size:150%;color:#999;float:left;padding-top:30px;padding-left:50px;">Browse Jobs</div>
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
    <form action="http://localhost/cjobs/jobs/" method="GET">
    <input type="hidden" name="<?php echo $this->_tpl_vars['SNAME']; ?>
" value="<?php echo $this->_tpl_vars['SID']; ?>
" class="input" style="width:96px;" />
    <table>
    <tr>
      <td valign="bottom">
        <span style="color:#720000;font-weight:bold;font-size:95%;"><label for="what">What</label></span><br />
        <span style="font-size:70%;">Company, Keyword</span><br />
        <input type="text" id="what" name="what" style="width: 280px; height:18px; background-color: #f9f9f9; border: 1px solid #999999;" />
      </td>
      <td valign="bottom">
        <span style="color:#720000;font-weight:bold;font-size:95%;"><label for="where">Where</label></span><br />
        <span style="font-size:70%;">City, State or Zip</span><br />
        <input type="text" id="where" name="where" style="width: 280px; height:18px; background-color: #f9f9f9; border: 1px solid #999999;" />
      </td>
      <td valign="bottom" nowrap>
 	      <input type="submit" value="Search Jobs " class="searchButton">
        <a href="http://localhost/cjobs/advanced_search.php" class="ajs">Advanced Job Search</a>
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
  <td width="80%" style="line-height:105%;padding-top:10px;background-color:#f8f8f8;">
    <table width="100%">
    <tr>
      <td valign="top">
        <span style="color:#333;font-weight:bold;font-size:95%;">Jobs by keyword</span><br />
        <div style="clear: both;margin: 5px 10px; border-top: 1px solid #ccc; color: #333;"></div>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "browse_keword.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      </td>
    </tr>
    </table>
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