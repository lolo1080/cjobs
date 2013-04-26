<table border="1" borderColor="{$tblloginbgcolor}" cellPadding="0" cellSpacing="0" height="100%" width="100%">
<tr>
	<td valign="top" align="center">
		{include file="login/table_login_caption.tpl"}
		{include file="spacer_img.tpl" sheight="140" swidth="1"}

		{strip}
			{if $error}
				{include file="table_info.tpl"}
			{/if}
		{/strip}

		{include file="form/form.tpl"}

		{include file="forgot_pass.tpl"}
	</td>
</tr>
</table>