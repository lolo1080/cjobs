<!-- Form -->
{strip}
<center>
{include file="spacer_img.tpl" sheight="7" swidth="1"}  {*Space*}
<form class="{$fclass}" name="{$fname}" method="{$fmethod}" action="{$faction}" {$fenctype} {$ftarget}>
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