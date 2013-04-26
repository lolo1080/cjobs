{section name=i loop=$DataBody}
<tr>
	{section name=j loop=$DataBody[i]}
	<td width="{$DataBody[i][j].tdw}" class="{$DataBody[i][j].tdclass}" {$DataBody[i][j].tdalign}>{$DataBody[i][j].data}</td>
	{/section}
</tr>
{/section}