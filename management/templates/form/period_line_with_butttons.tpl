<!-- Period line -->
<form class="frm" name="frmperiodpage" method="POST" action="{$PagesInput.action}">
	{section name=i loop=$PagesItems}
		&nbsp;&nbsp;
		<span class="navtext">{$PagesItems[i].flabel}</span>
		<span class="navtextfrom">{$PagesItems[i].flabel2}</span>
		<span class="navtextfrom_small">
			{$PagesItems[i].before_html}{include file="form/input_type.tpl" CurFormElement=$PagesItems[i]}{$PagesItems[i].after_html}
		</span>
	{/section}
		&nbsp;
		<input type="hidden" name="{$SNAME}" value="{$SID}" />
		<input type="image" src="images/{$PagesInput.img}" name="{$PagesInput.name}" alt="{$PagesInput.imgtitle}" title="{$PagesInput.imgtitle}" width="{$PagesInput.img_w}" height="{$PagesInput.img_h}" border="0" align="top" {$PagesInput.jsaction} />
&nbsp;&nbsp;
</form>
<!-- Period line -->
