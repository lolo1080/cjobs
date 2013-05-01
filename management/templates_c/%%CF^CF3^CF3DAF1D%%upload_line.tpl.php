<?php /* Smarty version 2.6.19, created on 2013-05-01 01:35:16
         compiled from form/upload_line.tpl */ ?>
<form name="rfrmupload" id="rfrmupload" enctype="multipart/form-data" method="POST" action="<?php echo $this->_tpl_vars['upaction']; ?>
">
<table border="0" cellspacing="4" cellpadding="0">
<tr>
	<td>
		<table class="dbform" width="100%">
			<tr>
				<td collspan="2" class="label">
					<table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td class="regular" nowrap="nowrap"><?php echo $this->_tpl_vars['upmessage']; ?>
</td>
								<td>
									<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/input_type.tpl", 'smarty_include_vars' => array('CurFormElement' => $this->_tpl_vars['UpElement'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
								</td>
								<td width="5">&nbsp;</td>
								<td>
									<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/buttons.tpl", 'smarty_include_vars' => array('CurFormButtons' => $this->_tpl_vars['UploadButtons'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
								</td>
							</tr>
					</table>
				</td>
			</tr>
				<input type="hidden" name="action" value="upload" />
				<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
				<input type="hidden" name="<?php echo $this->_tpl_vars['upname']; ?>
" value="<?php echo $this->_tpl_vars['upvalue']; ?>
" />
				<input type="hidden" name="<?php echo $this->_tpl_vars['SNAME']; ?>
" value="<?php echo $this->_tpl_vars['SID']; ?>
" />
				<input type="hidden" name="form_num" value="<?php echo $this->_tpl_vars['FrmNum']; ?>
" />
		</table>
	</td>
</tr>
</form>
</table>