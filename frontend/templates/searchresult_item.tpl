{*[start_template_header_part]*}

{literal}
<script src="http://localhost/cjobs/frontend/JsHttpRequest/lib/JsHttpRequest/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">
function doMailSend(value,jobkey) {
  // Create new JsHttpRequest object.
  var req = new JsHttpRequest();
  // Code automatically called on load finishing.
  req.onreadystatechange = function() {
      if (req.readyState == 4) {
        // Write debug information too (output become responseText).
        // document.getElementById('debug').innerHTML = req.responseText;
				if (req.responseJS.my_error == "") {
					openJobHasBeenSendForm(jobkey,req.responseJS.email_to);
				}
				else {
          document.getElementById('error_msg_'+jobkey).innerHTML = req.responseJS.my_error;
				}
      }
  }
  // Prepare request object (automatically choose GET or POST).
  req.open(null, 'http://localhost/cjobs/send_job.php', true);
  // Send data to backend.
  req.send( { q: value } );
}
</script>
{/literal}

{*[end_template_header_part]*}
{*[start_template_content_part]*}
			<!--
			<div id="debug" style="border:1px dashed red; padding:2px">
		    Debug info
			</div>
			-->

      <table cellspacing="0" cellpadding="0" width="100%" border="0" style="font-family: Georgia, 'Times New Roman', Times, serif; font-size: 13px;">
        <tbody>
          {foreach from=$AdvSearchJobsListTop key=key item=item name=AdvJLTop}
          {if $smarty.foreach.AdvJLTop.first}
          <tr class="jobs_adv">
            <td><div class="jobs_adv_featured">Sponsored Jobs</div></td>
          </tr>
          {/if}
          <tr class="jobs_adv">
            <td><a href="{$item.clickurl}" class="title" target="_blank">{$item.title}</a></td>
          </tr>
          <tr class="jobs_adv">
            <td>
							{strip}
							{if (($item.company_name != '') && ($item.company_name != 'no'))}{$item.company_name}{/if}
							{if (($item.city != '') || ($item.regionname != '') || ($item.region != ''))} - {/if}
							{$item.city}
							{if ($item.regionname && ($item.regionname != ''))}{if ($item.city != '')}, {/if}{$item.regionname}{else}{if (($item.city != '') && ($item.region != ''))}, {/if}{$item.region}{/if}
							{/strip}
						</td>
          </tr>
          <tr class="jobs_adv">
            <td>{$item.description}</td>
          </tr>
          <tr class="jobs_adv">
            <td>From <a href="{$item.clickurl}" class="searchlink" target="_blank">{$item.feed_name}</a> - {$item.registered_ago} - {assign var="jk" value=$item.jobkey} <span id="savedlnk_{$item.jobkey}" class="ajs" style="display:{if !isset($MyJobs_save.$jk)}none{else}inline{/if};"><b>saved</b> to <a href="http://localhost/cjobs/myjobs/">My Jobs</a></span>{if !isset($MyJobs_save.$jk)}<span id="savelnk_{$item.jobkey}"><a href="javascript: saveJobToCookie('{$item.jobkey}'); hide_and_show_elements('savelnk_{$item.jobkey}','savedlnk_{$item.jobkey}'); void(0);" class="ajs" title="Save this job to My Jobs">save job</a></span>{/if} - <a href="javascript: openJobEmailForm('{$item.jobkey}','',''); void(0);" class="ajs">email</a></td>
          </tr>
          <tr class="jobs_adv">
            <td id="email_job_{$item.jobkey}" class="email_form_content" style="display: none;">&nbsp;</td>
          </tr>
          <tr class="jobs_adv">
            <td id="email_job_send_{$item.jobkey}" class="email_form_content" style="display: none;">&nbsp;</td>
          </tr>
          <tr class="jobs_adv">
            <td>&nbsp;</td>
          </tr>
          {/foreach}

          {foreach from=$SearchJobsList key=key item=item}
          <tr>
            <td><a href="{$item.clickurl}" class="title" target="_blank">{$item.title}</a>{if $item.isnew} - <span style="color:#e50908">New</span>{/if}</td>
          </tr>
          <tr>
            <td>
							{strip}
							{if (($item.company_name != '') && ($item.company_name != 'no'))}{$item.company_name}{/if}
							{if (($item.city != '') || ($item.regionname != '') || ($item.region != ''))} - {/if}
							{$item.city}
							{if ($item.regionname && ($item.regionname != ''))}{if ($item.city != '')}, {/if}{$item.regionname}{else}{if (($item.city != '') && ($item.region != ''))}, {/if}{$item.region}{/if}
							{if ($item.plus_locations.count > 1)} <a href="{$item.plus_locations.url}">+{$item.plus_locations.count} locations</a>{/if}
							{/strip}
						</td>
          </tr>
          <tr>
            <td>{$item.description}</td>
          </tr>
          <tr>
            <td>From <a href="{$item.clickurl}" class="searchlink" target="_blank">{$item.feed_name}</a> - {$item.registered_ago} - {assign var="jk" value=$item.jobkey} <span id="savedlnk_{$item.jobkey}" class="ajs" style="display:{if !isset($MyJobs_save.$jk)}none{else}inline{/if};"><b>saved</b> to <a href="http://localhost/cjobs/myjobs/">My Jobs</a></span>{if !isset($MyJobs_save.$jk)}<span id="savelnk_{$item.jobkey}"><a href="javascript: saveJobToCookie('{$item.jobkey}'); hide_and_show_elements('savelnk_{$item.jobkey}','savedlnk_{$item.jobkey}'); void(0);" class="ajs" title="Save this job to My Jobs">save job</a></span>{/if} - <a href="javascript: openJobEmailForm('{$item.jobkey}','',''); void(0);" class="ajs">email</a></td>
          </tr>
          <tr>
            <td id="email_job_{$item.jobkey}" class="email_form_content" style="display: none;">&nbsp;</td>
          </tr>
          <tr>
            <td id="email_job_send_{$item.jobkey}" class="email_form_content" style="display: none;">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          {/foreach}

          <tr>
            <td style="padding-bottom:10px;">
             <script type="text/javascript"><!--
             google_ad_client = "ca-pub-1518402476458009";
             google_ad_slot = "9766450083";
             google_ad_width = 700;
             google_ad_height = 90;
             //-->
             </script>
             <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
						</td>
          </tr>

          {foreach from=$AdvSearchJobsListBottom key=key item=item name=AdvJLBottom}
          {if $smarty.foreach.AdvJLBottom.first}
          <tr class="jobs_adv">
            <td><div class="jobs_adv_featured">Sponsored Jobs</div></td>
          </tr>
          {/if}
          <tr class="jobs_adv">
            <td><a href="{$item.clickurl}" class="title" target="_blank">{$item.title}</a></td>
          </tr>
          <tr class="jobs_adv">
            <td>
							{strip}
							{if (($item.company_name != '') && ($item.company_name != 'no'))}{$item.company_name}{/if}
							{if (($item.city != '') || ($item.regionname != '') || ($item.region != ''))} - {/if}
							{$item.city}
							{if ($item.regionname && ($item.regionname != ''))}{if ($item.city != '')}, {/if}{$item.regionname}{else}{if (($item.city != '') && ($item.region != ''))}, {/if}{$item.region}{/if}
							{/strip}
						</td>
          </tr>
          <tr class="jobs_adv">
            <td>{$item.description}</td>
          </tr>
          <tr class="jobs_adv">
            <td>From <a href="{$item.clickurl}" class="searchlink" target="_blank">{$item.feed_name}</a> - {$item.registered_ago} - {assign var="jk" value=$item.jobkey} <span id="savedlnk_{$item.jobkey}" class="ajs" style="display:{if !isset($MyJobs_save.$jk)}none{else}inline{/if};"><b>saved</b> to <a href="http://localhost/cjobs/myjobs/">My Jobs</a></span>{if !isset($MyJobs_save.$jk)}<span id="savelnk_{$item.jobkey}"><a href="javascript: saveJobToCookie('{$item.jobkey}'); hide_and_show_elements('savelnk_{$item.jobkey}','savedlnk_{$item.jobkey}'); void(0);" class="ajs" title="Save this job to My Jobs">save job</a></span>{/if} - <a href="javascript: openJobEmailForm('{$item.jobkey}','',''); void(0);" class="ajs">email</a></td>
          </tr>
          <tr class="jobs_adv">
            <td id="email_job_{$item.jobkey}" class="email_form_content" style="display: none;">&nbsp;</td>
          </tr>
          <tr class="jobs_adv">
            <td id="email_job_send_{$item.jobkey}" class="email_form_content" style="display: none;">&nbsp;</td>
          </tr>
          <tr class="jobs_adv">
            <td>&nbsp;</td>
          </tr>
          {/foreach}
        </tbody>
      </table>
{*[end_template_content_part]*}
{*[start_template_footer_part]*}

{*[end_template_footer_part]*}
