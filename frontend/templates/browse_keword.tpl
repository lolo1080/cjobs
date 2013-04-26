{*[start_template_header_part]*}
{*[end_template_header_part]*}

{*[start_template_content_part]*}

            {if $BrowseKeywordListNavigation}
            <div style="padding-top: 7px; padding-bottom: 7px;">
            <span style="color:#333;font-weight:bold;font-size:90%;">Navigation:</span>
            {section name=i loop=$BrowseKeywordListNavigation}
              <a href="{$BrowseKeywordListNavigation[i].url}" class="addbrowselink">{$BrowseKeywordListNavigation[i].keyword}</a>{if !$smarty.section.i.last} : {/if}
            {/section}
            </div>
            {/if}

            {if $BrowseKeywordListMostPopular}
            <div style="padding-top: 3px; padding-bottom: 7px;">
            <span style="color:#333;font-weight:bold;font-size:90%;">Most Popular:</span>
            {section name=i loop=$BrowseKeywordListMostPopular}
              <a href="{$BrowseKeywordListMostPopular[i].url}" class="addbrowselink">{$BrowseKeywordListMostPopular[i].keyword}</a>{if !$smarty.section.i.last}, {/if}
            {/section}
            </div>
            {/if}

            <table width="100%" border="0">

            <tr>
            {foreach from=$BrowseKeywordList key=section_key item=section_item name=main_list}
              <td valign="top">
                <table cellpadding="0" cellspacing="0" width="100%" border="0">
                {foreach from=$section_item key=block_key item=block_item}
                  {foreach from=$block_item key=key item=item}
                    {if $key == "main"}
                      <tr>
                        <td class="browse_k0">{$item.link}</td>
                      </tr>
                    {else}
                      {foreach from=$item key=key1 item=item1}
                        <tr>
                          <td><a href="{$item1.url}" class="browselink">{$item1.link}</a></td>
                        </tr>
                      {/foreach}
                    {/if}
                  {/foreach}
                {/foreach}
                </table>
              </td>
            {/foreach}

            </tr>
            </table>

{*[end_template_content_part]*}

{*[start_template_footer_part]*}
{*[end_template_footer_part]*}