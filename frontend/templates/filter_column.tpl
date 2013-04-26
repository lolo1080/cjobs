{*[start_template_header_part]*}
{*[end_template_header_part]*}

{*[start_template_content_part]*}

            {assign var="show_filter_records" value=5}
            {assign var="max_shown_filter_records" value=15}
            <table cellpadding="0" cellspacing="0" width="100%" border="0" style="font-family: Georgia, 'Times New Roman', Times, serif; font-size: 12px;">

            <tr>
              <td>
                {foreach from=$JobFilteredBy key=key item=item name="company_foreach"}
                {if $smarty.foreach.company_foreach.first}
                <div style="border: dashed 1px #9f0101; margin-top:8px; margin-right:3px; padding:1px 3px 4px 3px;">
                {/if}
                {if $smarty.foreach.company_foreach.first} <div style="padding-top: 3px;"><b>You refined by:</b></div>{/if}
                <div style="padding-top: 3px;"><span class="gray">{$item.filter_caption}</span> (<a class="simple_link" href="{$item.filter_undolink}">undo</a>)</div>
                {/foreach}
                {if $smarty.foreach.company_foreach.total > 1}<div><a class="simple_link" href="{$item.filter_undoalllinks}">undo all</a></div>{/if}
                {if $smarty.foreach.company_foreach.total > 0}</div>{/if}
              </td>
            </tr>
            <tr>
              <td class="filter_data">&nbsp;</td>
            </tr>

            {if $JobFilterParams.company_name_show && $JobFilterParams.company}
            <tr>
              <td>
                <div><span class="filter_title" onClick="toggleRefineBy('http://localhost/cjobs/frontend/images/','company_filter_img','company_filter_record'); hide_and_show_elements('more_company_filter','more_link_company');"><img id="company_filter_img" src="http://localhost/cjobs/frontend/images/arrow_bottom.gif" align="baseline" /><b>Company</b></span></div>
              </td>
            </tr>
            <tr id="company_filter_record">
              <td class="filter_data">
                {foreach from=$JobFilterParams.company key=key item=item name="company_foreach"}
                {if $smarty.foreach.company_foreach.iteration <= $max_shown_filter_records}
                {if $smarty.foreach.company_foreach.iteration == ($show_filter_records+1)} <span id="more_link_company"><a href="javascript: hide_and_show_elements('more_link_company','more_company_filter'); void(0);" class="ajs">more &raquo;</a></span> <div id="more_company_filter" style="display: none;"> {/if}
                <div>
                  {if ($item.company_name_count > 0)}
                    <a class="filterlink" href="{$item.company_link}">{$item.company_name}</a>
                  {else}
                    <span class="gray">{$item.company_name}</span>
                  {/if}
                  <span class="gray">({$item.company_name_count})</span>
                </div>
                {/if}
                {/foreach}
                {if $smarty.foreach.company_foreach.total > $show_filter_records} </div> {/if}
              </td>
            </tr>
            <tr>
              <td class="filter_data">&nbsp;</td>
            </tr>
            {/if}

            {if $JobFilterParams.title_show && $JobFilterParams.title}
            <tr>
              <td>
                <div><span class="filter_title" onClick="toggleRefineBy('http://localhost/cjobs/frontend/images/','title_filter_img','title_filter_record'); hide_and_show_elements('more_title_filter','more_link_title');"><img id="title_filter_img" src="http://localhost/cjobs/frontend/images/arrow_bottom.gif" align="baseline" /><b>Title</b></span></div>
              </td>
            </tr>
            <tr id="title_filter_record">
              <td class="filter_data">
                {foreach from=$JobFilterParams.title key=key item=item name="title_foreach"}
                {if $smarty.foreach.title_foreach.iteration <= $max_shown_filter_records}
                {if $smarty.foreach.title_foreach.iteration == ($show_filter_records+1)} <span id="more_link_title"><a href="javascript: hide_and_show_elements('more_link_title','more_title_filter'); void(0);" class="ajs">more &raquo;</a></span> <div id="more_title_filter" style="display: none;"> {/if}
                <div>
                  {if ($item.title_count > 0)}
                    <a class="filterlink" href="{$item.title_link}">{$item.title}</a>
                  {else}
                    <span class="gray">{$item.title}</span>
                  {/if}
                  <span class="gray">({$item.title_count})</span>
                </div>
                {/if}
                {/foreach}
                {if $smarty.foreach.title_foreach.total > $show_filter_records} </div> {/if}
              </td>
            </tr>
            <tr>
              <td class="filter_data">&nbsp;</td>
            </tr>
            {/if}

            {if $JobFilterParams.locId_show && $JobFilterParams.locId}
            <tr>
              <td>
                <div><span class="filter_title" onClick="toggleRefineBy('http://localhost/cjobs/frontend/images/','location_filter_img','location_filter_record'); hide_and_show_elements('more_location_filter','more_link_location');"><img id="location_filter_img" src="http://localhost/cjobs/frontend/images/arrow_bottom.gif" align="baseline" /><b>Location</b></span></div>
              </td>
            </tr>
            <tr id="location_filter_record">
              <td class="filter_data">
                {foreach from=$JobFilterParams.locId key=key item=item name="location_foreach"}
                {if $smarty.foreach.location_foreach.iteration <= $max_shown_filter_records}
                {if $smarty.foreach.location_foreach.iteration == ($show_filter_records+1)} <span id="more_link_location"><a href="javascript: hide_and_show_elements('more_link_location','more_location_filter'); void(0);" class="ajs">more &raquo;</a></span> <div id="more_location_filter" style="display: none;"> {/if}
                <div>
                  {if ($item.locIdcount > 0)}
                    <a class="filterlink" href="{$item.location_link}">{$item.city}, {$item.country}, {if ($item.regionname && ($item.regionname != ''))}{$item.regionname}{else}{$item.region}{/if}</a>
                  {else}
                    <span class="gray">{$item.city}, {$item.country}, {if ($item.regionname && ($item.regionname != ''))}{$item.regionname}{else}{$item.region}{/if}</span>
                  {/if}
                  <span class="gray">({$item.locIdcount})</span>
                </div>
                {/if}
                {/foreach}
                {if $smarty.foreach.location_foreach.total > $show_filter_records} </div> {/if}
              </td>
            </tr>
            <tr>
              <td class="filter_data">&nbsp;</td>
            </tr>
            {/if}

            {if $JobFilterParams.job_type_show && $JobFilterParams.job_type}
            <tr>
              <td>
                <div><span class="filter_title" onClick="toggleRefineBy('http://localhost/cjobs/frontend/images/','job_type_filter_img','job_type_filter_record')"><img id="job_type_filter_img" src="http://localhost/cjobs/frontend/images/arrow_bottom.gif" align="baseline" /><b>Job Type</b></span></div>
              </td>
            </tr>
            <tr id="job_type_filter_record">
              <td class="filter_data">
                {foreach from=$JobFilterParams.job_type key=key item=item}
                <div>
                  {if ($item.job_type_count > 0)}
                    <a class="filterlink" href="{$item.job_type_link}">{$item.job_caption}</a>
                  {else}
                    <span class="gray">{$item.job_caption}</span>
                  {/if}
                  <span class="gray">({$item.job_type_count})</span>
                </div>
                {/foreach}
              </td>
            </tr>
            <tr>
              <td class="filter_data">&nbsp;</td>
            </tr>
            {/if}

            {if $JobFilterParams.salary_show}
            <tr>
              <td>
                <div><span class="filter_title" onClick="toggleRefineBy('http://localhost/cjobs/frontend/images/','salary_filter_img','salary_filter_record')"><img id="salary_filter_img" src="http://localhost/cjobs/frontend/images/arrow_bottom.gif" align="baseline" /><b>Salary Estimate</b></span></div>
              </td>
            </tr>
            <tr id="salary_filter_record">
              <td class="filter_data">
                {foreach from=$JobFilterParams.salary key=key item=item name=company_foreach}
                <div>
                  {if ($item[0].salary_count > 0)}
                    <a class="filterlink" href="{$item[0].salary_link}">{$key}</a>
                  {else}
                    <span class="gray">{$key}</span>
                  {/if}
                  <span class="gray">({$item[0].salary_count})</span></div>
                {/foreach}
              </td>
            </tr>
            <tr>
              <td class="filter_data">&nbsp;</td>
            </tr>
            {/if}

            {if $JobFilterParams.isstaffing_agencies_show}
            <tr>
              <td nowrap>
                <div><span class="filter_title" onClick="toggleRefineBy('http://localhost/cjobs/frontend/images/','isstaffing_agencies_filter_img','isstaffing_agencies_filter_record')"><img id="isstaffing_agencies_filter_img" src="http://localhost/cjobs/frontend/images/arrow_bottom.gif" align="baseline" /><b>Employer/Recruiter</b></span></div>
              </td>
            </tr>
            <tr id="isstaffing_agencies_filter_record">
              <td class="filter_data">
                {foreach from=$JobFilterParams.employer_recruiter key=key item=item}
                <div>
                  {if ($item[0].employer_recruiter_count > 0)}
                    <a class="filterlink" href="{$item[0].employer_recruiter_link}">{$key}</a>
                  {else}
                    <span class="gray">{$key}</span>
                  {/if}
                  <span class="gray">({$item[0].employer_recruiter_count})</span>
                </div>
                {/foreach}
              </td>
            </tr>
            <tr>
              <td class="filter_data">&nbsp;</td>
            </tr>
            {/if}

            {if $JobFilterParams.recent_job_searches_show}
            <tr id="recent_job_searches_filter_title">
              <td nowrap>
                <div><span class="filter_title" onClick="toggleRefineBy('http://localhost/cjobs/frontend/images/','recent_job_searches_filter_img','recent_job_searches_filter_record')"><img id="recent_job_searches_filter_img" src="http://localhost/cjobs/frontend/images/arrow_bottom.gif" align="baseline" /><b>Recent Job Searches</b></span></div>
              </td>
            </tr>
            <tr id="recent_job_searches_filter_record">
              <td class="filter_data">
                {foreach from=$JobFilterParams.recent_job_searches key=key item=item}
                <div>
                  <a class="filterlink" href="{$item.link}">{$item.title}</a>
                </div>
                {/foreach}
                <div id="more_searches_filter">
                  <a href="javascript: delSearchCookie(); hide_recent_job_searches(); void(0);" title="Remove all previous searches">&raquo; clear searches</a>
                </div>
              </td>
            </tr>
            {/if}
            </table>

{*[end_template_content_part]*}

{*[start_template_footer_part]*}
{*[end_template_footer_part]*}