<?php /* Smarty version 2.6.19, created on 2013-05-01 01:06:08
         compiled from form/buttons.tpl */ ?>
<?php echo '<table border="0" cellspacing="0" cellpadding="0"><tr>'; ?><?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['CurFormButtons']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?><?php echo '<td><table class="buttonborder'; ?><?php echo $this->_tpl_vars['CurFormButtons'][$this->_sections['i']['index']]['btn_classnum']; ?><?php echo '1" cellPadding="0" cellSpacing="0"><tr><td><table class="buttonborder'; ?><?php echo $this->_tpl_vars['CurFormButtons'][$this->_sections['i']['index']]['btn_classnum']; ?><?php echo '2" cellPadding="0" cellSpacing="0"><tr><td><table class="buttonborder'; ?><?php echo $this->_tpl_vars['CurFormButtons'][$this->_sections['i']['index']]['btn_classnum']; ?><?php echo '3" cellPadding="0" cellSpacing="0"><tr><td><table class="buttonborder'; ?><?php echo $this->_tpl_vars['CurFormButtons'][$this->_sections['i']['index']]['btn_classnum']; ?><?php echo '4" cellPadding="0" cellSpacing="0"><tr><td><input class="button" type="'; ?><?php echo $this->_tpl_vars['CurFormButtons'][$this->_sections['i']['index']]['btype']; ?><?php echo '" name="'; ?><?php echo $this->_tpl_vars['CurFormButtons'][$this->_sections['i']['index']]['bname']; ?><?php echo '" value="'; ?><?php echo $this->_tpl_vars['CurFormButtons'][$this->_sections['i']['index']]['bvalue']; ?><?php echo '" '; ?><?php echo $this->_tpl_vars['CurFormButtons'][$this->_sections['i']['index']]['bscript']; ?><?php echo ' /></td></tr></table></td></tr></table></td></tr></table></td></tr></table></td><td width="'; ?><?php echo $this->_tpl_vars['btnspace']; ?><?php echo '">&nbsp;</td>'; ?><?php endfor; endif; ?><?php echo '</tr></table>'; ?>