{*[start_template_header_part]*}
{*[end_template_header_part]*}

{*[start_template_content_part]*}

            <table cellpadding="0" cellspacing="0" width="100%" border="0" style="font-family: Georgia, 'Times New Roman', Times, serif; font-size: 12px;">
            <tr>
              <td>
                  <b>Saved Jobs</b>
              </td>
            </tr>
            {if $SearchJobsList}
            <tr id="myjobs_clear_link_tr">
              <td>
                  <a href="javascript: deleteAllJobsFromCookie(); hide_element('myjobs_table'); hide_element('myjobs_clear_link_tr'); hide_element('myjobs_bottom_navigation'); void(0);"><b>Clear saved Jobs</b></a>
              </td>
            </tr>
            {/if}
            </table>

{*[end_template_content_part]*}

{*[start_template_footer_part]*}
{*[end_template_footer_part]*}