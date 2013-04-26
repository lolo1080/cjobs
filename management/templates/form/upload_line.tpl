<form name="rfrmupload" id="rfrmupload" enctype="multipart/form-data" method="POST" action="{$upaction}">
<table border="0" cellspacing="4" cellpadding="0">
<tr>
	<td>
		<table class="dbform" width="100%">
			<tr>
				<td collspan="2" class="label">
					<table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td class="regular" nowrap="nowrap">{$upmessage}</td>
								<td>
									{include file="form/input_type.tpl" CurFormElement=$UpElement}
								</td>
								<td width="5">&nbsp;</td>
								<td>
									{include file="form/buttons.tpl" CurFormButtons=$UploadButtons}
								</td>
							</tr>
					</table>
				</td>
			</tr>
				<input type="hidden" name="action" value="upload" />
				<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
				<input type="hidden" name="{$upname}" value="{$upvalue}" />
				<input type="hidden" name="{$SNAME}" value="{$SID}" />
				<input type="hidden" name="form_num" value="{$FrmNum}" />
		</table>
	</td>
</tr>
</form>
</table>