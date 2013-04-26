<!-- Top menu -->
<table border="0" cellPadding="0" cellSpacing="0" width="100%">
<tr bgcolor="{$topmenucolor}"> <!--top menu color -->
	<td>
		<table border="0" cellPadding="0" cellSpacing="0" width="100%">
		<tr>
			<td>
				<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><img height="20" src="images/spacer.gif" width="3" /></td>
					<td><span class="TopSpan">{$menucopyright}</span></td>
					<td><img height="20" src="images/spacer.gif" width="3" /></td>
				</tr>
				</table>
			</td>
{if $AddTopMenu}
			<td align="right"> <!-- JS menu -->
<div name="myMenuID" id="myMenuID" style="z-index:1;"></div>
<!-- menu section -->
<script language="JavaScript"><!--
var myMenu =
[
{section name=i loop=$LeftMenuElements}
		[null, '{$LeftMenuElements[i].text}', null, null, null,
			{section name=j loop=$LeftMenuElements[i].Items}
				{if $LeftMenuElements[i].Items[j].split}
		    _cmSplit,
				{/if}
				['<img src="images/{$LeftMenuElements[i].Items[j].img}" />', "{$LeftMenuElements[i].Items[j].text}", '{$LeftMenuElements[i].Items[j].href}&{$SLINE}', '{$LeftMenuElements[i].Items[j].target}', null],
			{/section}
	{if $smarty.section.i.index == ($smarty.section.i.total - 1)}
		]
	{else}
		],
    _cmSplit,
	{/if}
{/section}
];
--></script>
<!-- menu section -->
<script language="JavaScript"><!--
cmDraw ('myMenuID', myMenu, 'hbl', cmThemeOffice, 'ThemeOffice');
--></script>
			</td>
{/if}
			<td>&nbsp;</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<!-- Top menu -->