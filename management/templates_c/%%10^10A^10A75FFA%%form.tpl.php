<?php /* Smarty version 2.6.19, created on 2013-05-01 01:06:08
         compiled from form/form.tpl */ ?>
<!-- Form -->
<?php echo '<center>'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "spacer_img.tpl", 'smarty_include_vars' => array('sheight' => '7','swidth' => '1')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '  '; ?><?php echo '<form class="'; ?><?php echo $this->_tpl_vars['fclass']; ?><?php echo '" name="'; ?><?php echo $this->_tpl_vars['fname']; ?><?php echo '" method="'; ?><?php echo $this->_tpl_vars['fmethod']; ?><?php echo '" action="'; ?><?php echo $this->_tpl_vars['faction']; ?><?php echo '" '; ?><?php echo $this->_tpl_vars['fenctype']; ?><?php echo ' '; ?><?php echo $this->_tpl_vars['ftarget']; ?><?php echo '>'; ?><?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['FormHidden']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?><?php echo '<input type="hidden" name="'; ?><?php echo $this->_tpl_vars['FormHidden'][$this->_sections['i']['index']]['fname']; ?><?php echo '" value="'; ?><?php echo $this->_tpl_vars['FormHidden'][$this->_sections['i']['index']]['fvalue']; ?><?php echo '" />'; ?><?php endfor; endif; ?><?php echo '<table class="tblborder1" cellPadding="0" cellSpacing="0"><tr><td><table class="tblborder2" cellPadding="0" cellSpacing="0"><tr><td><table class="tblborder3" cellPadding="3" cellSpacing="0">'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/form_caption.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/form_body.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '<tr><td colspan="'; ?><?php echo $this->_tpl_vars['hcolspan']; ?><?php echo '" align="'; ?><?php echo $this->_tpl_vars['btnalign']; ?><?php echo '">'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/buttons.tpl", 'smarty_include_vars' => array('CurFormButtons' => $this->_tpl_vars['FormButtons'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '</td></tr></table></td></tr></table></td></tr></table></form></center>'; ?>

<!-- Form -->