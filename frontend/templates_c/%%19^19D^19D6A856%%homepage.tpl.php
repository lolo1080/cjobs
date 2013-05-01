<?php /* Smarty version 2.6.19, created on 2013-04-26 12:36:16
         compiled from homepage.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'homepage.tpl', 68, false),)), $this); ?>
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
        <a href="http://localhost/cjobs/"><img src="http://localhost/cjobs/frontend/images/es_job_search_engine.gif" alt="ES Job Search Engine" title="ES Job Search Engine" width="216" height="100" border="0" /></a>
      </td>
      <td valign="top" align="right">
        <?php echo $this->_tpl_vars['JobsInCountry']; ?>
<br />
        <a class="toplinks" href="http://localhost/cjobs/myjobs/">My Jobs</a> - <a class="toplinks" href="http://localhost/cjobs/myarea/">My Area</a><br />
        <div class="across_web_div">Search Jobs Across the Web</div>
      </td>
    </tr>
    </table>
  </td>
  <td width="10%">&nbsp;</td>
</tr>

<!-- Search boxes -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%" class="sb_td" align="center">
    <form action="http://localhost/cjobs/jobs/" method="GET">
    <input type="hidden" name="<?php echo $this->_tpl_vars['SNAME']; ?>
" value="<?php echo $this->_tpl_vars['SID']; ?>
" class="input width96" />
    <table>
    <tr>
      <td valign="bottom">
        <span class="before_input_text"><label for="what">What</label></span><br />
        <span class="font_size70">Company, Keyword</span><br />
        <input type="text" id="what" name="what" class="main_input" />
      </td>
      <td valign="bottom">
        <span class="before_input_text"><label for="where">Where</label></span><br />
        <span class="font_size70">City, State or Zip</span><br />
        <input type="text" id="where" name="where" class="main_input" />
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
  <td width="80%" class="cat_list">

    <?php $this->assign('rows', '9'); ?>
    <?php $this->assign('cur_row', '0'); ?>
    <table width="100%">
    <tr>
      <?php $_from = $this->_tpl_vars['JobCategoriesArrayList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['catitems'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['catitems']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['catitems']['iteration']++;
?>
        <?php if (( $this->_tpl_vars['cur_row'] == 0 )): ?>
          <td valign="top" width="<?php echo smarty_function_math(array('equation' => "ceil(x/y*z)",'x' => 100,'y' => $this->_foreach['catitems']['total'],'z' => $this->_tpl_vars['rows']), $this);?>
%">
        <?php endif; ?>
						<?php $this->assign('cur_row', ($this->_tpl_vars['cur_row']+1)); ?>
            <a href="http://localhost/cjobs/category/<?php echo $this->_tpl_vars['item']['cat_key']; ?>
/" class="catlinks"><?php echo $this->_tpl_vars['item']['cat_name']; ?>
</a><br />
        <?php if (( $this->_tpl_vars['cur_row'] == $this->_tpl_vars['rows'] )): ?>
          </td>
					<?php $this->assign('cur_row', '0'); ?>
        <?php endif; ?>
      <?php endforeach; endif; unset($_from); ?>
      <?php if (( ( ($this->_foreach['catitems']['iteration']-1) > 0 ) && ( ! !(($this->_foreach['catitems']['iteration']-1) % $this->_tpl_vars['rows']) ) )): ?>
          </td>
      <?php endif; ?>
    </tr>
    </table>

  </td>
  <td width="10%">&nbsp;</td>
</tr>

<!-- Footer links -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%" align="center">
    <div class="clear_div"></div>
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
  <td width="80%" align="center" class="pad_top25">
    <span class="footer_span">
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