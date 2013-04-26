<center>
<table class="tblborder1" cellPadding="0" cellSpacing="0">
<tr>
	<td>
		<table class="tblborder2" cellPadding="0" cellSpacing="0">
		<tr>
			<td>
				<table class="tblborder3" cellPadding="0" cellSpacing="0">
				<tr>
					<td class="tblborder_td_head">{$imgform_header}</td>
				</tr>
				<tr>
					<td>
						<form name="fileform" method="{$imgform_method}" action="{$imgform_action}">
						{section name=i loop=$ImgFormHidden}
							<input type="hidden" name="{$ImgFormHidden[i].fname}" value="{$ImgFormHidden[i].fvalue}" />
						{/section}
						<table border="0" class="tbl" cellpadding="0" cellspacing="0">
							{include file="table/table_data_head.tpl"}
							{include file="table/table_data_body.tpl"}
							<script>
							</script>
							<noscript>
							<tr>
								<td class="tbl_td_bottom" colspan="7">
									<input class="tbl" type="submit" name="Delete" value="'.$text_info["nt_delsel"].'" />
								</td>
							</tr>
							</noscript>
						</form>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						{include file="form/upload_line.tpl"}
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</center>