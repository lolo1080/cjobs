<?php /* Smarty version 2.6.19, created on 2013-05-01 01:06:07
         compiled from menu/topmenu.tpl */ ?>
<!-- Top menu -->
<table border="0" cellPadding="0" cellSpacing="0" width="100%">
<tr bgcolor="<?php echo $this->_tpl_vars['topmenucolor']; ?>
"> <!--top menu color -->
	<td>
		<table border="0" cellPadding="0" cellSpacing="0" width="100%">
		<tr>
			<td>
				<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><img height="20" src="images/spacer.gif" width="3" /></td>
					<td><span class="TopSpan"><?php echo $this->_tpl_vars['menucopyright']; ?>
</span></td>
					<td><img height="20" src="images/spacer.gif" width="3" /></td>
				</tr>
				</table>
			</td>
<?php if ($this->_tpl_vars['AddTopMenu']): ?>
			<td align="right"> <!-- JS menu -->
<div name="myMenuID" id="myMenuID" style="z-index:1;"></div>
<!-- menu section -->
<script language="JavaScript"><!--
var myMenu =
[
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['LeftMenuElements']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		[null, '<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['text']; ?>
', null, null, null,
			<?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['Items']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?>
				<?php if ($this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['Items'][$this->_sections['j']['index']]['split']): ?>
		    _cmSplit,
				<?php endif; ?>
				['<img src="images/<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['Items'][$this->_sections['j']['index']]['img']; ?>
" />', "<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['Items'][$this->_sections['j']['index']]['text']; ?>
", '<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['Items'][$this->_sections['j']['index']]['href']; ?>
&<?php echo $this->_tpl_vars['SLINE']; ?>
', '<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['Items'][$this->_sections['j']['index']]['target']; ?>
', null],
			<?php endfor; endif; ?>
	<?php if ($this->_sections['i']['index'] == ( $this->_sections['i']['total'] - 1 )): ?>
		]
	<?php else: ?>
		],
    _cmSplit,
	<?php endif; ?>
<?php endfor; endif; ?>
];
--></script>
<!-- menu section -->
<script language="JavaScript"><!--
cmDraw ('myMenuID', myMenu, 'hbl', cmThemeOffice, 'ThemeOffice');
--></script>
			</td>
<?php endif; ?>
			<td>&nbsp;</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<!-- Top menu -->