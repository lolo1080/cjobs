<table class="regular" cellpadding="5" border="0" width="100%">
<tr align="center">
	<td>
			<table class="tbl" cellpadding="0" cellspacing="0">
				{include file="table/table_data_head.tpl"}
				{include file="table/table_data_body.tpl"}
		<form class="frm" name="mainform" method="POST" action="{$fdata_action}">
			{section name=i loop=$FormHidden}
				<input type="hidden" name="{$FormHidden[i].fname}" value="{$FormHidden[i].fvalue}" />
			{/section}
		</form>
			{if $AddFilter}
				{include file="filter/filter.tpl"}
			{/if}
			</table>
		</td>
</tr>
</table>