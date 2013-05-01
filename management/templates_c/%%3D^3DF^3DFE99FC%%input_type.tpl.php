<?php /* Smarty version 2.6.19, created on 2013-05-01 01:06:08
         compiled from form/input_type.tpl */ ?>
<?php if ($this->_tpl_vars['CurFormElement']['etype'] == 'text'): ?>
	<input class="data" type="text" name="<?php echo $this->_tpl_vars['CurFormElement']['ename']; ?>
" id="<?php echo $this->_tpl_vars['CurFormElement']['ename']; ?>
" <?php echo $this->_tpl_vars['CurFormElement']['ereadonly']; ?>
 value="<?php echo $this->_tpl_vars['CurFormElement']['evalue']; ?>
" maxlength="<?php echo $this->_tpl_vars['CurFormElement']['emaxlength']; ?>
" style="<?php echo $this->_tpl_vars['CurFormElement']['estyle']; ?>
" <?php echo $this->_tpl_vars['CurFormElement']['edisabled']; ?>
 />
<?php elseif ($this->_tpl_vars['CurFormElement']['etype'] == 'password'): ?>
	<input class="data" type="password" name="<?php echo $this->_tpl_vars['CurFormElement']['ename']; ?>
" id="<?php echo $this->_tpl_vars['CurFormElement']['ename']; ?>
" <?php echo $this->_tpl_vars['CurFormElement']['ereadonly']; ?>
 value="<?php echo $this->_tpl_vars['CurFormElement']['evalue']; ?>
" maxlength="<?php echo $this->_tpl_vars['CurFormElement']['emaxlength']; ?>
" style="<?php echo $this->_tpl_vars['CurFormElement']['estyle']; ?>
" />
<?php elseif ($this->_tpl_vars['CurFormElement']['etype'] == 'textarea'): ?>
	<textarea class="data" name="<?php echo $this->_tpl_vars['CurFormElement']['ename']; ?>
" id="<?php echo $this->_tpl_vars['CurFormElement']['ename']; ?>
" <?php echo $this->_tpl_vars['CurFormElement']['ereadonly']; ?>
 style="<?php echo $this->_tpl_vars['CurFormElement']['estyle']; ?>
"><?php echo $this->_tpl_vars['CurFormElement']['evalue']; ?>
</textarea>
<?php elseif ($this->_tpl_vars['CurFormElement']['etype'] == 'checkbox'): ?>
	<input class="checkbox_data" type="checkbox" name="<?php echo $this->_tpl_vars['CurFormElement']['ename']; ?>
" id="<?php echo $this->_tpl_vars['CurFormElement']['ename']; ?>
" <?php echo $this->_tpl_vars['CurFormElement']['edisabled']; ?>
 value="<?php echo $this->_tpl_vars['CurFormElement']['evalue']; ?>
" <?php echo $this->_tpl_vars['CurFormElement']['echecked']; ?>
 />
<?php elseif ($this->_tpl_vars['CurFormElement']['etype'] == 'file'): ?>
	<input class="data" type="file" name="<?php echo $this->_tpl_vars['CurFormElement']['ename']; ?>
" id="<?php echo $this->_tpl_vars['CurFormElement']['ename']; ?>
" style="<?php echo $this->_tpl_vars['CurFormElement']['estyle']; ?>
" />
<?php elseif ($this->_tpl_vars['CurFormElement']['etype'] == 'select'): ?>
	<select name="<?php echo $this->_tpl_vars['CurFormElement']['ename']; ?>
" id="<?php echo $this->_tpl_vars['CurFormElement']['ename']; ?>
" <?php echo $this->_tpl_vars['CurFormElement']['edisabled']; ?>
 style="<?php echo $this->_tpl_vars['CurFormElement']['estyle']; ?>
" <?php echo $this->_tpl_vars['CurFormElement']['multiple']; ?>
 <?php echo $this->_tpl_vars['CurFormElement']['jscipt']; ?>
>
	<?php echo ''; ?><?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['CurFormElement']['evalue']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?><?php echo '<option value="'; ?><?php echo $this->_tpl_vars['CurFormElement']['evalue'][$this->_sections['j']['index']]; ?><?php echo '" '; ?><?php echo $this->_tpl_vars['CurFormElement']['eselected'][$this->_sections['j']['index']]; ?><?php echo '>'; ?><?php echo $this->_tpl_vars['CurFormElement']['ecaption'][$this->_sections['j']['index']]; ?><?php echo '</option>'; ?><?php endfor; endif; ?><?php echo ''; ?>

	</select>
<?php elseif ($this->_tpl_vars['CurFormElement']['etype'] == 'none'): ?>
	&nbsp;
<?php endif; ?>