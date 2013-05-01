<?php /* Smarty version 2.6.19, created on 2013-05-01 01:06:07
         compiled from page_navigation.tpl */ ?>
<table border="0" cellPadding="0" cellSpacing="0" width="100%">
<tr>
	<td width="<?php echo $this->_tpl_vars['navwidth']; ?>
"><img height="1" src="images/spacer.gif" width="<?php echo $this->_tpl_vars['navwidth']; ?>
" /></td>
	<td width="100%">
		<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['Pages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
			<?php if ($this->_tpl_vars['Pages'][$this->_sections['i']['index']]['islink']): ?>
				<a class="BlackMenu" href="<?php echo $this->_tpl_vars['Pages'][$this->_sections['i']['index']]['href']; ?>
" title=""><?php echo $this->_tpl_vars['Pages'][$this->_sections['i']['index']]['text']; ?>
</a><?php echo $this->_tpl_vars['Pages'][$this->_sections['i']['index']]['spacer']; ?>

			<?php else: ?>
				<span class="navtextfrom"><?php echo $this->_tpl_vars['Pages'][$this->_sections['i']['index']]['text']; ?>
</span><?php echo $this->_tpl_vars['Pages'][$this->_sections['i']['index']]['spacer']; ?>

			<?php endif; ?>
		<?php endfor; endif; ?>
	</td>
</tr>
</table>