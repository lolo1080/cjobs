<?php /* Smarty version 2.6.19, created on 2013-05-01 01:24:40
         compiled from form/navigation_line.tpl */ ?>
<!-- Navigation line -->
<?php echo '<form class="frm" name="frmnavpages" method="GET" action="'; ?><?php echo $this->_tpl_vars['PagesInput']['action']; ?><?php echo '"><span class="navtext">'; ?><?php echo $this->_tpl_vars['PagesText']; ?><?php echo '</span><span class="navtextfrom">'; ?><?php echo $this->_tpl_vars['PagesFromText']; ?><?php echo '</span><span class="navtext">:</span>&nbsp;&nbsp;'; ?><?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['PagesItems']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?><?php echo ''; ?><?php if ($this->_tpl_vars['PagesItems'][$this->_sections['i']['index']]['islink']): ?><?php echo '<a class="navlink" href="'; ?><?php echo $this->_tpl_vars['PagesItems'][$this->_sections['i']['index']]['href']; ?><?php echo '">'; ?><?php echo $this->_tpl_vars['PagesItems'][$this->_sections['i']['index']]['text']; ?><?php echo '</a>&nbsp;'; ?><?php else: ?><?php echo '<span class="navtext">'; ?><?php echo $this->_tpl_vars['PagesItems'][$this->_sections['i']['index']]['text']; ?><?php echo '</span>&nbsp;'; ?><?php endif; ?><?php echo ''; ?><?php endfor; endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['PagesInput']['isneed']): ?><?php echo '<input class="data_nav" type="text" name="'; ?><?php echo $this->_tpl_vars['PagesInput']['name']; ?><?php echo '" value="'; ?><?php echo $this->_tpl_vars['PagesInput']['value']; ?><?php echo '" maxlength="'; ?><?php echo $this->_tpl_vars['PagesInput']['maxlength']; ?><?php echo '" style="'; ?><?php echo $this->_tpl_vars['PagesInput']['style']; ?><?php echo '" /><input type="image" src="images/'; ?><?php echo $this->_tpl_vars['PagesInput']['img']; ?><?php echo '" alt="'; ?><?php echo $this->_tpl_vars['PagesInput']['imgtitle']; ?><?php echo '" title="'; ?><?php echo $this->_tpl_vars['PagesInput']['imgtitle']; ?><?php echo '" width="'; ?><?php echo $this->_tpl_vars['PagesInput']['img_w']; ?><?php echo '" height="'; ?><?php echo $this->_tpl_vars['PagesInput']['img_h']; ?><?php echo '" border="0" align="absmiddle" '; ?><?php echo $this->_tpl_vars['PagesInput']['jsaction']; ?><?php echo ' /><input type="hidden" name="'; ?><?php echo $this->_tpl_vars['SNAME']; ?><?php echo '" value="'; ?><?php echo $this->_tpl_vars['SID']; ?><?php echo '" /><input type="hidden" name="fromform" value="yes" />'; ?><?php endif; ?><?php echo '&nbsp;&nbsp;&nbsp;</form>'; ?>

<!-- Navigation line -->