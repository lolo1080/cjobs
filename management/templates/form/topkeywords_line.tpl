<!-- Top Keywords line -->
<form class="frm" name="frmtopkeywords" method="POST" action="{$PagesInput.action}">
	{section name=i loop=$TopKeywordsPagesItems}
		&nbsp;&nbsp;
		<span class="navtktext">{$TopKeywordsPagesItems[i].flabel}</span>
		<span class="navtktext">
			{$TopKeywordsPagesItems[i].before_html}{include file="form/input_type.tpl" CurFormElement=$TopKeywordsPagesItems[i]}{$TopKeywordsPagesItems[i].after_html}
		</span>
	{/section}
		<input type="hidden" name="{$SNAME}" value="{$SID}" />
		<input type="image" src="images/{$TopKeywordsGo.img}" name="{$TopKeywordsGo.name}" alt="{$TopKeywordsGo.imgtitle}" title="{$TopKeywordsGo.imgtitle}" width="{$TopKeywordsGo.img_w}" height="{$TopKeywordsGo.img_h}" border="0" align="top" {$TopKeywordsGo.jsaction} />
&nbsp;&nbsp;
</form>
<!-- Top Keywords line -->
