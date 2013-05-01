<?php /* Smarty version 2.6.19, created on 2013-05-01 01:06:07
         compiled from menu/graymenu.tpl */ ?>
<!-- Gray menu -->
<table bgColor="<?php echo $this->_tpl_vars['gmbgcolor']; ?>
" border="0" cellPadding="0" cellSpacing="0" width="100%">
<tr valign="center">
	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['GrayMenuItems']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?>
	  <td><img width="15" height="20" src="images/spacer.gif" align="absmiddle" /></td>
  	<td>
			<a href="<?php echo $this->_tpl_vars['GrayMenuItems'][$this->_sections['i']['index']]['link']; ?>
" title="<?php echo $this->_tpl_vars['GrayMenuItems'][$this->_sections['i']['index']]['title']; ?>
" <?php echo $this->_tpl_vars['GrayMenuItems'][$this->_sections['i']['index']]['ascript']; ?>
><img title="<?php echo $this->_tpl_vars['GrayMenuItems'][$this->_sections['i']['index']]['title']; ?>
" alt="" src="images/<?php echo $this->_tpl_vars['GrayMenuItems'][$this->_sections['i']['index']]['img_name']; ?>
" width="<?php echo $this->_tpl_vars['GrayMenuItems'][$this->_sections['i']['index']]['img_w']; ?>
" height="<?php echo $this->_tpl_vars['GrayMenuItems'][$this->_sections['i']['index']]['img_h']; ?>
" border="0" valign="top" <?php echo $this->_tpl_vars['GrayMenuItems'][$this->_sections['i']['index']]['jsaction']; ?>
 /></a>
		</td>
  	<td><img width="3" height="20" src="images/spacer.gif" align="absmiddle" /></td>
	  <td nowrap><a class="EditMenu" href="<?php echo $this->_tpl_vars['GrayMenuItems'][$this->_sections['i']['index']]['link']; ?>
" title="<?php echo $this->_tpl_vars['GrayMenuItems'][$this->_sections['i']['index']]['title']; ?>
" <?php echo $this->_tpl_vars['GrayMenuItems'][$this->_sections['i']['index']]['ascript']; ?>
><?php echo $this->_tpl_vars['GrayMenuItems'][$this->_sections['i']['index']]['text']; ?>
</a></td>
	<?php endfor; endif; ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu/elements.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</tr>
</table>
<!-- Gray menu -->