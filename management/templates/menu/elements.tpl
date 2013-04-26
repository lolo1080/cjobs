{if $PageTopKeywords}
<td width="100%" align="left" nowrap valign="center">
	{include file="form/topkeywords_line.tpl"}
</td>
{/if}

{if $PageNavigation}
<td width="100%" align="right" nowrap valign="center">
	{include file="form/navigation_line.tpl"}
</td>

{elseif $PageNavigation_and_PagePeriodSelect}
<td width="70%" align="left" nowrap valign="left">
	{include file="form/period_line.tpl" PagesItems=$PeriodPagesItems}
</td>
<td width="10%" align="right" nowrap valign="center">&nbsp;</td>
<td width="20%" align="left" nowrap valign="center">
	{include file="form/navigation_line.tpl" PagesItems=$NavPagesItems}
</td>

{elseif $GrayMenuButtons_PageNavigation_and_PagePeriodSelect}
<td width="100%" nowrap valign="center">
	<table border="0" class="frm" width="100%">
		<tr>
			<td width="70%" align="left" nowrap valign="center">
				{include file="form/period_line.tpl" PagesItems=$PeriodPagesItems}
			</td>
			<td width="10%" align="right" nowrap valign="center">&nbsp;</td>
			<td width="20%" align="right" nowrap valign="center">
				{include file="form/navigation_line.tpl" PagesItems=$NavPagesItems}
			</td>
		</tr>
		<tr>
			<td width="100%" align="left" nowrap valign="center" colspan="3">
				<table class="frm">
				<tr>
					<td>
						{if $SubGrayMenuItems}
							<hr style="width:100%; height: 2px;" />
							{include file="form/sub_graymenu_line.tpl" GrayMenuItems=$SubGrayMenuItems}
						{/if}
					</td>
				</tr>
				</table>
			</td>
		</tr>
	</table>
</td>


{elseif $PagePeriodSelect}
<td width="100%" align="center" nowrap valign="left">
	{include file="form/period_line.tpl" PagesItems=$PeriodPagesItems}
</td>

{else}
<td width="100%" align="right" nowrap valign="center">&nbsp;</td>
{/if}

