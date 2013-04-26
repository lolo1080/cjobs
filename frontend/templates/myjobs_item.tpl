{*[start_template_header_part]*}

{*[end_template_header_part]*}
{*[start_template_content_part]*}
      <table cellspacing="0" cellpadding="0" width="100%" border="0" style="font-family: Georgia, 'Times New Roman', Times, serif; font-size: 13px;" id="myjobs_table">
        <tbody>
          {foreach from=$SearchJobsList key=key item=item}
          <tr>
            <td><a href="{$item.clickurl}" class="title" target="_blank">{$item.title}</a>{if $item.isnew} - <span>New</span>{/if}</td>
          </tr>
          <tr>
            <td>{$item.company_name} - {$item.city}, {if ($item.regionname && ($item.regionname != ''))}{$item.regionname}{else}{$item.region}{/if}{if ($item.plus_locations.count > 1)} <a href="{$item.plus_locations.url}">+{$item.plus_locations.count} locations</a>{/if}</td>
          </tr>
          <tr>
            <td>{$item.description}</td>
          </tr>
          <tr>
            <td>From <a href="{$item.clickurl}" class="searchlink" target="_blank">{$item.feed_name}</a> - {$item.registered_ago} - <span class="ajs"><b>saved</b></span> - <a href="./?jobkey={$item.jobkey}" class="ajs" title="Remove this job to from My Jobs">remove job</a><!-- - <a href="#" class="ajs">email</a>--></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          {/foreach}
        </tbody>
      </table>
{*[end_template_content_part]*}
{*[start_template_footer_part]*}

{*[end_template_footer_part]*}
