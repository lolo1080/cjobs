<?php /* Smarty version 2.6.19, created on 2013-05-01 01:06:08
         compiled from form/form_body.tpl */ ?>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['FormElements']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
	<?php if ($this->_tpl_vars['FormElements'][$this->_sections['i']['index']]['isheadline']): ?>
		<tr>
			<td colspan="5" class="<?php echo $this->_tpl_vars['FormElements'][$this->_sections['i']['index']]['hlclass']; ?>
"><?php echo $this->_tpl_vars['FormElements'][$this->_sections['i']['index']]['hlmessage']; ?>
</td>
		</tr>
		<?php echo $this->_tpl_vars['FormElements'][$this->_sections['i']['index']]['after_html']; ?>

	<?php else: ?>
		<tr>
			<td width="<?php echo $this->_tpl_vars['fbefore_width']; ?>
">&nbsp;</td>
			<td width="<?php echo $this->_tpl_vars['finfo1_width']; ?>
"><?php echo $this->_tpl_vars['FormElements'][$this->_sections['i']['index']]['flabel']; ?>
</td>
			<td width="<?php echo $this->_tpl_vars['finner_width']; ?>
">&nbsp;</td>
			<td width="<?php echo $this->_tpl_vars['finfo2_width']; ?>
">
				<?php echo $this->_tpl_vars['FormElements'][$this->_sections['i']['index']]['before_html']; ?>

				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/input_type.tpl", 'smarty_include_vars' => array('CurFormElement' => $this->_tpl_vars['FormElements'][$this->_sections['i']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				<?php echo $this->_tpl_vars['FormElements'][$this->_sections['i']['index']]['after_html']; ?>

			</td>
			<td width="<?php echo $this->_tpl_vars['fafter_width']; ?>
">&nbsp;</td>
		</tr>
	<?php endif; ?>
<?php endfor; endif; ?>