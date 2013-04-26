{*Main page*}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>{$reg_title}</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>

<body aLink="#000000" link="#000000" vLink="#000000" bgColor="#ffffff" text="#000000"
	leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">

	{strip}
		{if $error}
			{include file="table_info.tpl"}
		{/if}
	{/strip}


<!-- Form -->
{strip}
<center>
<form name="{$fname}" method="{$fmethod}" action="{$faction}">
	{section name=i loop=$FormHidden}
		<input type="hidden" name="{$FormHidden[i].fname}" value="{$FormHidden[i].fvalue}" />
	{/section}
<table class="tblborder1" cellPadding="0" cellSpacing="0">
<tr>
	<td>
		<table class="tblborder2" cellPadding="0" cellSpacing="0">
		<tr>
			<td>
				<table class="tblborder3" cellPadding="3" cellSpacing="0">
					{include file="form/form_caption.tpl"}
					{include file="form/form_body.tpl"}
					<tr>
						<td colspan="{$hcolspan}" align="{$btnalign}">
						{include file="form/buttons.tpl" CurFormButtons=$FormButtons}
						</td>
					</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</form>
</center>
{/strip}
<!-- Form -->


</body>
</html>