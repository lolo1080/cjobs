<table border="0" cellPadding="0" cellSpacing="0" width="100%">
<tr>
	<td width="{$navwidth}"><img height="1" src="images/spacer.gif" width="{$navwidth}" /></td>
	<td width="100%">
		{section name=i loop=$Pages}
			{if $Pages[i].islink}
				<a class="BlackMenu" href="{$Pages[i].href}" title="">{$Pages[i].text}</a>{$Pages[i].spacer}
			{else}
				<span class="navtextfrom">{$Pages[i].text}</span>{$Pages[i].spacer}
			{/if}
		{/section}
	</td>
</tr>
</table>
