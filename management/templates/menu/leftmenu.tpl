<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Left menu.</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>

<body bgColor="#ffffff" leftMargin="0" topMargin="0" text="#000000" marginheight="0" marginwidth="0" class="MenuLeftBk">

{include file="menu/leftmenu_header.tpl"}

<table align="center" border="0" cellspacing="0" cellpadding="3" width="{$lmtblwidth}">
<tr>
	<td>
<!-- menu section -->
{section name=i loop=$LeftMenuElements}
<!-- menu header -->
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr><td><img width="1" height="15" src="images/spacer.gif" align="absmiddle" alt="" /></td></tr>
	<tr>
		<td width="162" height="24" class="TdMenuHead">
			<a class="MenuHead" href="{$LeftMenuElements[i].href}">{$LeftMenuElements[i].text}</a>
		</td>
		<td class="TdMenuHeadRight">
			<a href="{$LeftMenuElements[i].href}">
				<img src="images/{$LeftMenuElements[i].img}" width="{$LeftMenuElements[i].img_w}" height="{$LeftMenuElements[i].img_h}" border="0" alt="{$LeftMenuElements[i].title}" title="{$LeftMenuElements[i].title}" />
			</a>
		</td>
	</tr>
</table>
<!-- menu header -->
{if $LeftMenuElements[i].isdown}
<!-- menu body -->
<table border="0" cellspacing="0" cellpadding="0" width="100%" class="TblMenuBody">
	<tr><td height="10" class="TdMenuItem"><img width="4" height="10" src="images/spacer.gif" align="absmiddle" /></td></tr>
		{section name=j loop=$LeftMenuElements[i].Items}
			<tr>
				<td height="18" class="TdMenuItem">
					<img src="images/{$LeftMenuElements[i].Items[j].img}" width="16" height="16" alt="{$LeftMenuElements[i].Items[j].text}" title="{$LeftMenuElements[i].Items[j].text}" border="0" align="absmiddle" />
					<img width="0" height="16" src="images/spacer.gif" align="absmiddle" />
					<a class="MenuItem" href="{$LeftMenuElements[i].Items[j].href}" target="{$LeftMenuElements[i].Items[j].target}">{$LeftMenuElements[i].Items[j].text}</a>
				</td>
			</tr>
		{/section}
	<tr><td height="10" class="TdMenuItem"><img width="4" height="10" src="images/spacer.gif" align="absmiddle" alt="" /></td></tr>
</table>
<!-- menu body -->
{else}
<table border="0" cellspacing="0" cellpadding="0" width="100%" class="TblMenuBody">
	<tr><td></td></tr>
</table>
{/if}
{/section}
<!-- menu section -->
	</td>
</tr>
</table>
 
</body>
</html>