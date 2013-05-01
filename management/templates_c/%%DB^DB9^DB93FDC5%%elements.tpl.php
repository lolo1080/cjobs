<?php /* Smarty version 2.6.19, created on 2013-05-01 01:06:07
         compiled from menu/elements.tpl */ ?>
<?php if ($this->_tpl_vars['PageTopKeywords']): ?>
<td width="100%" align="left" nowrap valign="center">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/topkeywords_line.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>
<?php endif; ?>

<?php if ($this->_tpl_vars['PageNavigation']): ?>
<td width="100%" align="right" nowrap valign="center">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/navigation_line.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>

<?php elseif ($this->_tpl_vars['PageNavigation_and_PagePeriodSelect']): ?>
<td width="70%" align="left" nowrap valign="left">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/period_line.tpl", 'smarty_include_vars' => array('PagesItems' => $this->_tpl_vars['PeriodPagesItems'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>
<td width="10%" align="right" nowrap valign="center">&nbsp;</td>
<td width="20%" align="left" nowrap valign="center">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/navigation_line.tpl", 'smarty_include_vars' => array('PagesItems' => $this->_tpl_vars['NavPagesItems'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>

<?php elseif ($this->_tpl_vars['GrayMenuButtons_PageNavigation_and_PagePeriodSelect']): ?>
<td width="100%" nowrap valign="center">
	<table border="0" class="frm" width="100%">
		<tr>
			<td width="70%" align="left" nowrap valign="center">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/period_line.tpl", 'smarty_include_vars' => array('PagesItems' => $this->_tpl_vars['PeriodPagesItems'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			</td>
			<td width="10%" align="right" nowrap valign="center">&nbsp;</td>
			<td width="20%" align="right" nowrap valign="center">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/navigation_line.tpl", 'smarty_include_vars' => array('PagesItems' => $this->_tpl_vars['NavPagesItems'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			</td>
		</tr>
		<tr>
			<td width="100%" align="left" nowrap valign="center" colspan="3">
				<table class="frm">
				<tr>
					<td>
						<?php if ($this->_tpl_vars['SubGrayMenuItems']): ?>
							<hr style="width:100%; height: 2px;" />
							<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/sub_graymenu_line.tpl", 'smarty_include_vars' => array('GrayMenuItems' => $this->_tpl_vars['SubGrayMenuItems'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
						<?php endif; ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
	</table>
</td>


<?php elseif ($this->_tpl_vars['PagePeriodSelect']): ?>
<td width="100%" align="center" nowrap valign="left">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/period_line.tpl", 'smarty_include_vars' => array('PagesItems' => $this->_tpl_vars['PeriodPagesItems'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>

<?php else: ?>
<td width="100%" align="right" nowrap valign="center">&nbsp;</td>
<?php endif; ?>
