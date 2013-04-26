<!-- Navigation line -->
{strip}
<form class="frm" name="frmnavpages" method="GET" action="{$PagesInput.action}">
	<span class="navtext">{$PagesText}</span><span class="navtextfrom">{$PagesFromText}</span><span class="navtext">:</span>&nbsp;&nbsp;
	{section name=i loop=$PagesItems}
		{if $PagesItems[i].islink}
			<a class="navlink" href="{$PagesItems[i].href}">{$PagesItems[i].text}</a>&nbsp;
		{else}
			<span class="navtext">{$PagesItems[i].text}</span>&nbsp;
		{/if}
	{/section}
		{if $PagesInput.isneed}
			<input class="data_nav" type="text" name="{$PagesInput.name}" value="{$PagesInput.value}" maxlength="{$PagesInput.maxlength}" style="{$PagesInput.style}" />
			<input type="image" src="images/{$PagesInput.img}" alt="{$PagesInput.imgtitle}" title="{$PagesInput.imgtitle}" width="{$PagesInput.img_w}" height="{$PagesInput.img_h}" border="0" align="absmiddle" {$PagesInput.jsaction} />
			<input type="hidden" name="{$SNAME}" value="{$SID}" />
			<input type="hidden" name="fromform" value="yes" />
		{/if}
&nbsp;&nbsp;&nbsp;
</form>
{/strip}
<!-- Navigation line -->