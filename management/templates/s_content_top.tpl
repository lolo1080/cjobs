{*Main page*}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>ES Job Search Engine Control Center</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="css/main.css" rel="stylesheet" type="text/css" />
	<link href="css/filter.css" rel="stylesheet" type="text/css" />
	<script src="js/content.js" language="JavaScript"></script>
	<script src="js/JSCookMenu/JSCookMenu.js" language="JavaScript"></script>
	<link href="js/JSCookMenu/ThemeOffice/theme.css" rel="stylesheet" type="text/css" />
	<script src="js/JSCookMenu/ThemeOffice/theme.js" language="JavaScript"></script>
{* --- Calendar script ---   --- Calendar script --- *}
	{if $LoadCalendarScript}
		<style type="text/css">@import url(jscalendar/calendar-win2k-1.css);</style>
		<script type="text/javascript" src="jscalendar/calendar.js"></script>
		<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
		<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
	{/if}
{* --- FCKEditor ---   --- FCKEditor --- *}
	{if $LoadEditorScript}
		<script type="text/javascript" src="fckeditor/fckeditor.js"></script>
		<script type="text/javascript">
			window.onload = function()
			{ldelim}
				{$AddHTMLEditorBody}
			{rdelim}
		</script>
	{/if}
{* --- Color Picker ---   --- Color Picker --- *}
	{if $LoadColorPickerScript}
		<script type="text/javascript" src="js/ColorPicker/ColorPicker2.js"></script>
		<script type="text/javascript" src="js/ColorPicker/AnchorPosition.js"></script>
		<script type="text/javascript" src="js/ColorPicker/PopupWindow.js"></script>
		<script type="text/javascript" src="js/JsHttpRequest/lib/JsHttpRequest/JsHttpRequest.js"></script>
		<script type="text/javascript" src="js/content_jobroll.js"></script>
	{/if}
{* --- Feeds scrits ---   --- Feeds scrits --- *}
	{if $LoadFeedsScript}
		<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
		<script type="text/javascript" src="js/ajax_error_handler.js"></script>
		<script type="text/javascript" src="js/urlEncode.js"></script>
	{/if}
{* --- Feeds scrits (HTML 2 only) ---   --- Feeds scrits --- *}
	{if $LoadHTML2FeedScript}
		{literal}
		<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
		<script type="text/javascript" src="js/ajax_error_handler.js"></script>
		<script type="text/javascript" src="js/urlEncode.js"></script>
		<link rel="stylesheet" type="text/css" href="js/treeview/jquery.treeview.css" />
		<style>
			.tag { color: blue; }
			.attr { color: #990033; }
		</style>
		<script type="text/javascript" src="js/treeview/jquery.treeview.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){	
			$("#html_tree").treeview({
					control:"#sidetreecontrol",
					collapsed: true,
					prerendered: true
				});
			});
		</script>
		{/literal}
	{/if}
</head>

<body bgColor="#f8f5f8" text="#000000" leftMargin="0" topMargin="0" marginheight="0" marginwidth="0" {$AddBodyScript}>

		{include file="menu/topmenu.tpl"}	{*Top menu*}

		{include file="page_navigation.tpl"}	{*Page navigation*}

		{include file="spacer_img.tpl" sheight="2" swidth="1"}  {*Space*}

		{include file="line1.tpl"}	{*Line*}

		{include file="spacer_img.tpl" sheight="2" swidth="1"}	{*Space*}

		{*Table*}
		<table align="center" border="0" cellPadding="0" cellSpacing="0" height="94%" width="100%">
		<tr valign="top">
			<td align="middle">
				<img src="images/spacer.gif" width="5" height="1"   border="0" alt="" />
			</td>
			<td width="100%">

			{include file="menu/graymenu.tpl"}	{*Gray menu*}

			{include file="table_data.tpl"}		{*Data table*}

			</td>
		</tr>
		</table>

</body>
</html>