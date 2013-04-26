      <table cellspacing="0" cellpadding="0" width="100%" border="0" style="font-family: Georgia, 'Times New Roman', Times, serif; font-size: 13px;">
        <tbody>
          {foreach from=$SearchJobsList key=key item=item}
          <tr>
            <td><a href="{$item.clickurl}" class="title" target="_blank">{$item.title}</a></td>
          </tr>
          <tr>
            <td><font color="#555555">{$item.company_name} - {$item.city}, {if ($item.regionname && ($item.regionname != ''))}{$item.regionname}{else}{$item.region}{/if}</font></td>
          </tr>
          <tr>
            <td>{$item.description}</td>
          </tr>
          <tr>
            <td>From <a href="{$item.clickurl}" class="searchlink" target="_blank">{$item.feed_name}</a> - <font color="#555555">{$item.registered_ago}</font></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          {/foreach}
        </tbody>
      </table>
