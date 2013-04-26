<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>HTML Feed Check</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="css/main.css" rel="stylesheet" type="text/css" />
	<link href="css/filter.css" rel="stylesheet" type="text/css" />
	<script src="js/content.js" language="JavaScript"></script>
	<link href="js/JSCookMenu/ThemeOffice/theme.css" rel="stylesheet" type="text/css" />
</head>

<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0" {$AddBodyScript}>

	{if $error}
		{include file="table_info.tpl"}
	{else}

		{*Table*}
		<table align="center" border="0" cellPadding="0" cellSpacing="0" width="100%">
		<tr valign="top">
			<td height="100%" width="100%">
  
				<table width="100%" style="font-family: 12px arial,tahoma,verdana;">
				<tr>
					<td valign="top" style="font-size: 12px">Notes:</td><td style="font-size: 12px">Parsing limited with first page only</td>
				</tr>
				<tr>
					<td valign="top" style="font-size: 12px">Category:</td><td><pre style="font-size: 12px">{$check_result.cat_name}</pre></td>
				</tr>
				<tr>
					<td valign="top" style="font-size: 12px">Configuration name:</td><td><pre style="font-size: 12px">{$check_result.config_name}</pre></td>
				</tr>
				<tr>
					<td valign="top" style="font-size: 12px">Checked URL:</td><td><pre style="font-size: 12px">{$check_result.checked_url}</pre></td>
				</tr>
				{if (!$check_result.iserror)}
				<tr>
					<td valign="top" style="font-size: 12px">Parsing result:<br /><small>Note: description is formatted</small></td><td><pre style="font-size: 12px">{$check_result.parsing_result}</pre></td>
				</tr>
				<tr>
					<td valign="top" style="font-size: 12px">Possible Insert:</td><td><pre style="font-size: 12px">{$check_result.possible_insert}</pre></td>
				</tr>
				<tr>
					<td valign="top" style="font-size: 12px">Real Insert:</td><td><pre style="font-size: 12px">{$check_result.real_insert}</pre></td>
				</tr>
				{/if}
				<tr>
					<td valign="top" style="font-size: 12px">Log:</td><td><pre style="font-size: 12px">{$check_result.log}</pre></td>
				</tr>
				</table>
  
			</td>
		</tr>
		</table>
	{/if}

</body>
</html>