<tr align="center">
	<td colspan="6">
			<table class="tbl" cellpadding="0" cellspacing="0">
		<form class="frm" name="mainform" method="POST" action="{$fdata_action}">
			{section name=i loop=$FormHidden}
				<input type="hidden" name="{$FormHidden[i].fname}" value="{$FormHidden[i].fvalue}" />
			{/section}
				{include file="table/table_data_head.tpl"}
				{include file="table/table_data_body.tpl"}
		</form>
			{if $AddFilter}
				{include file="filter/filter.tpl"}
			{/if}
			</table>
		</td>
</tr>
