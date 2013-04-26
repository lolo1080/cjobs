{section name=i loop=$FormElements}
	{if $FormElements[i].isheadline}
		<tr>
			<td colspan="5" class="{$FormElements[i].hlclass}">{$FormElements[i].hlmessage}</td>
		</tr>
		{$FormElements[i].after_html}
	{else}
		<tr>
			<td width="{$fbefore_width}">&nbsp;</td>
			<td width="{$finfo1_width}">{$FormElements[i].flabel}</td>
			<td width="{$finner_width}">&nbsp;</td>
			<td width="{$finfo2_width}">
				{$FormElements[i].before_html}
				{include file="form/input_type.tpl" CurFormElement=$FormElements[i]}
				{$FormElements[i].after_html}
			</td>
			<td width="{$fafter_width}">&nbsp;</td>
		</tr>
	{/if}
{/section}