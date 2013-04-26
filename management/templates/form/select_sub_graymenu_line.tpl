<!-- Sub Gray menu -->
<table bgColor="{$gmbgcolor}" border="0" cellPadding="0" cellSpacing="0" width="100%">
<tr valign="center">
	{section name=i loop=$GrayMenuItems}
	  <td><img width="15" height="20" src="images/spacer.gif" align="absmiddle" /></td>
	  <td nowrap>{$GrayMenuItems[i].text}&nbsp;&nbsp;&nbsp;{include file="form/input_type.tpl" CurFormElement=$GrayMenuItems[i].FormElements}</td>
	{/section}
</tr>
</table>
<!-- Sub Gray menu -->