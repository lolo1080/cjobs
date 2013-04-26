<!-- Gray menu -->
<table bgColor="{$gmbgcolor}" border="0" cellPadding="0" cellSpacing="0" width="100%">
<tr valign="center">
	{section name=i loop=$GrayMenuItems}
	  <td><img width="15" height="20" src="images/spacer.gif" align="absmiddle" /></td>
  	<td>
			<a href="{$GrayMenuItems[i].link}" title="{$GrayMenuItems[i].title}" {$GrayMenuItems[i].ascript}><img title="{$GrayMenuItems[i].title}" alt="" src="images/{$GrayMenuItems[i].img_name}" width="{$GrayMenuItems[i].img_w}" height="{$GrayMenuItems[i].img_h}" border="0" valign="top" {$GrayMenuItems[i].jsaction} /></a>
		</td>
  	<td><img width="3" height="20" src="images/spacer.gif" align="absmiddle" /></td>
	  <td nowrap><a class="EditMenu" href="{$GrayMenuItems[i].link}" title="{$GrayMenuItems[i].title}" {$GrayMenuItems[i].ascript}>{$GrayMenuItems[i].text}</a></td>
	{/section}
	{include file="menu/elements.tpl"}
</tr>
</table>
<!-- Gray menu -->