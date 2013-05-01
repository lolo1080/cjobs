<?php /* Smarty version 2.6.19, created on 2013-05-01 01:12:14
         compiled from table_info.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "spacer_img.tpl", 'smarty_include_vars' => array('sheight' => '4','swidth' => '1')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table width="100%" cellspacing="4" cellpadding="0" class="messageborder">
<tr>
	<td valign="top" width="50">
		<img src="images/<?php echo $this->_tpl_vars['iimgmane']; ?>
" border="0" width="32" height="32">
	</td>
	<td class="errmsg"><?php echo $this->_tpl_vars['imessage']; ?>
</td>
</tr>
</table>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "spacer_img.tpl", 'smarty_include_vars' => array('sheight' => '2','swidth' => '1')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>