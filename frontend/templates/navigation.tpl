{*[start_template_header_part]*}
{*[end_template_header_part]*}

{*[start_template_content_part]*}
{if $PagesList}
Results Page: 
{section name=i loop=$PagesList}
	{if $PagesList[i].islink}
		<a href="{$PagesList[i].url}" class="{$PagesList[i].class}">{$PagesList[i].title}</a>&nbsp;
	{else}
		<span class="{$PagesList[i].class}">{$PagesList[i].title}</span>&nbsp;
	{/if}
{/section}
{/if}
{*[end_template_content_part]*}

{*[start_template_footer_part]*}
{*[end_template_footer_part]*}
