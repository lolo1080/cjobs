{section name=i loop=$TableFormElements}
<tr>
	{section name=j loop=$TableFormElements[i]}
	<td width="{$TableFormElements[i][j].tdw}" class="{$TableFormElements[i][j].tdclass}" {$TableFormElements[i][j].tdalign} {$TableFormElements[i][j].tdvalign}>
		{$TableFormElements[i][j].before_html}
			{include file="form/input_type.tpl" CurFormElement=$TableFormElements[i][j].FormElement}
		{$TableFormElements[i][j].after_html}
	</td>
	{/section}
</tr>
{/section}