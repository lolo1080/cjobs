{if $CurFormElement.etype == "text"}
	<input class="data" type="text" name="{$CurFormElement.ename}" id="{$CurFormElement.ename}" {$CurFormElement.ereadonly} value="{$CurFormElement.evalue}" maxlength="{$CurFormElement.emaxlength}" style="{$CurFormElement.estyle}" {$CurFormElement.edisabled} />
{elseif $CurFormElement.etype == "password"}
	<input class="data" type="password" name="{$CurFormElement.ename}" id="{$CurFormElement.ename}" {$CurFormElement.ereadonly} value="{$CurFormElement.evalue}" maxlength="{$CurFormElement.emaxlength}" style="{$CurFormElement.estyle}" />
{elseif $CurFormElement.etype == "textarea"}
	<textarea class="data" name="{$CurFormElement.ename}" id="{$CurFormElement.ename}" {$CurFormElement.ereadonly} style="{$CurFormElement.estyle}">{$CurFormElement.evalue}</textarea>
{elseif $CurFormElement.etype == "checkbox"}
	<input class="checkbox_data" type="checkbox" name="{$CurFormElement.ename}" id="{$CurFormElement.ename}" {$CurFormElement.edisabled} value="{$CurFormElement.evalue}" {$CurFormElement.echecked} />
{elseif $CurFormElement.etype == "file"}
	<input class="data" type="file" name="{$CurFormElement.ename}" id="{$CurFormElement.ename}" style="{$CurFormElement.estyle}" />
{elseif $CurFormElement.etype == "select"}
	<select name="{$CurFormElement.ename}" id="{$CurFormElement.ename}" {$CurFormElement.edisabled} style="{$CurFormElement.estyle}" {$CurFormElement.multiple} {$CurFormElement.jscipt}>
	{strip}
		{section name=j loop=$CurFormElement.evalue}
			<option value="{$CurFormElement.evalue[j]}" {$CurFormElement.eselected[j]}>{$CurFormElement.ecaption[j]}</option>
		{/section}
	{/strip}
	</select>
{elseif $CurFormElement.etype == "none"}
	&nbsp;
{/if}