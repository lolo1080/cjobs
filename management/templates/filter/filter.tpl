<!-- Filter -->
<form name="filterform" method="POST" action="{$filteraction}">
	<input type="hidden" name="{$SNAME}" value="{$SID}" />
	<tr>
		{strip}
		<td class="tbl_td_head" align="center" nowrap colspan="{$FilterColspan}">
			<a href="lang/faq_en.html#filter" target="_blank">
				<img src="images/help.gif" width="16" height="16" alt="{$FilterTitle}" title="{$FilterTitle}" border="0" align="absmiddle" />
			</a>&nbsp;
			<input type="submit" class="filterbutton" name="Filter" align="absmiddle" value="{$FilterText}" /><br />
			<input type="submit" class="removefilterbutton" name="RemoveFilter" align="absmiddle" value="{$RemoveFilterText}" />
		</td>
		{/strip}
		{section name=i loop=$FilterElements}
			{$FilterElements[i]}
		{/section}
		{section name=i loop=$FilterTDCount}
			<td class="tbl_td_head">&nbsp;</td>
		{/section}
	</tr>
</form>
<!-- Filter -->
