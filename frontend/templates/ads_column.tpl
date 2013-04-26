{*[start_template_header_part]*}
{*[end_template_header_part]*}

{*[start_template_content_part]*}

            <table cellpadding="0" cellspacing="0" width="100%" border="0" class="ads_column1">
            <tr>
              <td>
                <script type="text/javascript"><!--
                google_ad_client = "pub-1518402476458009";
                google_ad_slot = "5790679591";
                google_ad_width = 160;
                google_ad_height = 600;
                //-->
                </script>
                <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
              </td>
            </tr>

            <tr>
              <td class="filter_data">&nbsp;</td>
            </tr>

            {if $KeywordAds}
            <tr>
              <td>
                <div><span class="filter_title"><b>Sponsored Links</b></span></div>
              </td>
            </tr>
            {/if}

            <tr>
              <td>
                <table cellspacing="0" cellpadding="0" width="100%" border="0" class="ads_column2">
                  <tbody>
                    {foreach from=$KeywordAds key=key item=item}
                    <tr>
                      <td><a href="{$item.clickurl}" class="title" target="_blank">{$item.headline}</a></td>
                    </tr>
                    <tr>
                      <td>{$item.line_1}</td>
                    </tr>
                    <tr>
                      <td>{$item.line_2}</td>
                    </tr>
                    <tr>
                      <td><a href="{$item.clickurl}" class="searchlink" target="_blank">{$item.display_url}</a></td>
                    </tr>
                    {/foreach}
                  </tbody>
                </table>
              </td>
            </tr>
            </table>

{*[end_template_content_part]*}

{*[start_template_footer_part]*}
{*[end_template_footer_part]*}