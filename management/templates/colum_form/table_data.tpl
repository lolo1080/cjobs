<table class="regular" cellpadding="5" border="0" width="100%">
<tr align="center">
	<td>
			<table class="tbl" cellpadding="0" cellspacing="0">
		<form class="frm" name="mainform" method="POST" action="{$fdata_action}">
			{section name=i loop=$FormHidden}
				<input type="hidden" name="{$FormHidden[i].fname}" value="{$FormHidden[i].fvalue}" />
			{/section}
				{include file="table/table_data_head.tpl"}
				{include file="colum_form/table_data_body.tpl"}
				<tr>
					<td colspan="{$tdTableButton.bcolspan}" class="{$tdTableButton.bclass}" align="{$tdTableButton.balign}">
						{include file="form/buttons.tpl" CurFormButtons=$FormButtons}
					</td>
				</tr>
				<script>
				</script>
				<noscript>
					<tr>
						<td class="tbl_td_bottom" colspan="9">
							{if $ApproveButtonVisible}
								<input class="tbl" type="submit" name="Approve" value="{$ApproveButton}" />&nbsp;
							{/if}
							{if $DelButtonVisible}
								<input class="tbl" type="submit" name="Delete" value="{$DelButton}" />
							{/if}
						</td>
					</tr>
				</noscript>
		</form>
			</table>
		</td>
</tr>
</table>