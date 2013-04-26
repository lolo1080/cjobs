<!-- Job Search Box Code page -->
{strip}
<center>
{include file="spacer_img.tpl" sheight="7" swidth="1"}  {*Space*}

<table width="100%" cellPadding="0" cellSpacing="0">
<tr>
<td valign="top" align="center">

{*Create a Job Search Box table (main)*}
<table name="create_job_searchbox_table" id="create_job_searchbox_table"  class="tblborder1" cellPadding="0" cellSpacing="0">
<tr>
	<td>
		<table class="tblborder2" cellPadding="0" cellSpacing="0">
		<tr>
			<td>
				<table class="tblborder3" cellPadding="3" cellSpacing="0">
				<tr>
					<td class="tblborder_td_head" colspan="5">{$form_header}</td>
				</tr>
				<tr>
					<td height="2" colspan="5" class="tblborder_td_head_line"></td>
				</tr>

				<tr>
					<td colspan="5" class="form_hlclass">{$FormElements.job_get_searchbox_code}</td>
				</tr>

				<tr>
					<td colspan="5">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="100"><b>{$FormElements.job_channel}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<select name="job_channel" id="job_channel" style="width:180px" onChange="updateSearchBoxsCode();">
						{strip}
							{section name=j loop=$FormElements.job_channel_items}
								<option value="{$FormElements.job_channel_items[j].value}">{$FormElements.job_channel_items[j].caption}</option>
							{/section}
						{/strip}
						</select>
						&nbsp;<a href="javascript:add_new_channel('{$channel_script}','{$SLINE}'); void(0);">add new</a>&nbsp;| 
						&nbsp;<a href="javascript:delete_channel('{$channel_script}','{$SLINE}'); void(0);">delete selected</a>
					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="100">&nbsp;</td>
					<td width="5">&nbsp;</td>
					<td width="440"><div style="padding:1ex; border-top:1px solid #bcbccc;"></div></td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="100"><b>{$FormElements.job_option1}:</b><br /><small>{$FormElements.job_option1_help}</small></td>
					<td width="5">&nbsp;</td>
					<td width="440">

						Example:<br />
						<form method="GET" action="{$site_url}jobs/?publisher_id={$publisher_id}" target="_blank">
						<table cellspacing="0" style="font-family:arial">
						<tr>
							<td colspna="2" style="font-size:16px;color:#FF6600"><b>Job Search</b></td>
						</tr>
            <tr>
						  <td><input name="what_where" value="" size="25"></td>
						  <td><input type="submit" value="Find Jobs"></td>
						</tr>
	          <tr>
					    <td valign="top" style="font-size:10px">job title, keywords, company, location</td>
						  <td valign="top" style="font-size:13px;"><span><a href="{$site_url}?publisher_id={$publisher_id}" style="text-decoration:none;color:#000">jobs</a> by <a href="{$site_url}?publisher_id={$publisher_id}" title="{$FormElements.job_search}"><img src="{$site_url}/frontend/images/jobsearch.gif" style="border:0;vertical-align:middle;" alt="{$FormElements.job_search_alt}"></a></span></td>
						</tr>
						</table>
						</form>

						Code (<a class="codelink" href="javascript:show_hide_html_code('job_option1');void(0);">show / hide HTML code</a>):<br />
						<textarea class="data" name="job_option1" id="job_option1" readonly style="width:440px;height:150px;" onFocus="this.select()" onClick="this.select()">&nbsp;</textarea>

					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="100">&nbsp;</td>
					<td width="5">&nbsp;</td>
					<td width="440"><div style="padding:1ex; border-top:1px solid #bcbccc;"></div></td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="100"><b>{$FormElements.job_option2}:</b><br /><small>{$FormElements.job_option2_help}</small></td>
					<td width="5">&nbsp;</td>
					<td width="440">

						Example:<br />
						<form method="GET" action="{$site_url}jobs/?publisher_id={$publisher_id}" target="_blank">
						<table cellspacing="0" style="font-family:arial">
						<tr>
							<td style="font-size:16px;color:#FF6600"><b>what</b></td>
              <td style="font-size:16px;color:#FF6600"><b>where</b></td>
						  <td>&nbsp;</td>
						</tr>
						<tr>
							<td><input name="what" value="" size="25"></td>
						  <td><input name="where" value="" size=""></td>
						  <td><input type="submit" value="Find Jobs"></td>
						</tr>
						<tr>
							<td valign="top" style="font-size:10px">job title, keywords or company</td>
					    <td valign="top" style="font-size:10px">city, state or zip</td>
						  <td valign="top" style="font-size:13px;"><span><a href="{$site_url}?publisher_id={$publisher_id}" style="text-decoration:none;color:#000">jobs</a> by <a href="{$site_url}?publisher_id={$publisher_id}" title="{$FormElements.job_search}"><img src="{$site_url}/frontend/images/jobsearch.gif" style="border:0;vertical-align:middle;" alt="{$FormElements.job_search_alt}"></a></span></td>
						</tr>
						</table>
						</form>
						
						Code (<a class="codelink" href="javascript:show_hide_html_code('job_option2');void(0);">show / hide HTML code</a>):<br />
						<textarea class="data" name="job_option2" id="job_option2" readonly style="width:440px;height:150px;" onFocus="this.select()" onClick="this.select()">&nbsp;</textarea>

					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="100">&nbsp;</td>
					<td width="5">&nbsp;</td>
					<td width="440"><div style="padding:1ex; border-top:1px solid #bcbccc;"></div></td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="100"><b>{$FormElements.job_option3}:</b><br /><small>{$FormElements.job_option3_help}</small></td>
					<td width="5">&nbsp;</td>
					<td width="440">

						Example:<br />
						<form method="GET" action="{$site_url}jobs/?publisher_id={$publisher_id}" target="_blank">
						<table cellspacing="0" style="font-family:arial">
						<tr>
							<td style="font-size:16px;color:#FF6600"><b>what</b></td>
						</tr>
						<tr>
							<td><input name="what" value="" size="20"></td>
						</tr>
            <tr>
							<td valign="top" style="font-size:10px">job title, keywords</td></tr>
						<tr>	
							<td style="font-size:16px;color:#FF6600"><b>where</b></td>
						</tr>
						<tr>
							<td><input name="where" value="" size="20"></td>
						</tr>
						<tr>
							<td valign="top" style="font-size:10px">city, state, zip</td>
						</tr>
						<tr>
							<td><input type="submit" value="Find Jobs"></td>
						</tr>
						<tr>
						  <td valign="top" style="font-size:13px;"><span><a href="{$site_url}?publisher_id={$publisher_id}" style="text-decoration:none;color:#000">jobs</a> by <a href="{$site_url}?publisher_id={$publisher_id}" title="{$FormElements.job_search}"><img src="{$site_url}/frontend/images/jobsearch.gif" style="border:0;vertical-align:middle;" alt="{$FormElements.job_search_alt}"></a></span></td>
						</tr>
						</table>
						</form>
						
						Code (<a class="codelink" href="javascript:show_hide_html_code('job_option3');void(0);">show / hide HTML code</a>):<br />
						<textarea class="data" name="job_option3" id="job_option3" readonly style="width:440px;height:150px;" onFocus="this.select()" onClick="this.select()">&nbsp;</textarea>

					</td>
					<td width="3">&nbsp;</td>
				</tr>

				</table>

			</td>
		</tr>
		</table>
	</td>
</tr>
</table>

</td>

</tr>
</table>

<script type="text/javascript">
	var publisher_id = "{$publisher_id}";
	var site_url = "{$site_url}";
	updateSearchBoxsCode();
</script>

</center>

{/strip}
<!-- Job Search Box Code page -->