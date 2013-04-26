<!-- Job Text Link page -->
{strip}
<center>
{include file="spacer_img.tpl" sheight="7" swidth="1"}  {*Space*}

<table width="100%" cellPadding="0" cellSpacing="0">
<tr>
<td valign="top" align="center">

<form>
{*Create a Job Text table (main)*}
<table name="create_job_textlink_table" id="create_job_textlink_table" class="tblborder1" cellPadding="0" cellSpacing="0">
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
					<td colspan="5" class="form_hlclass">{$FormElements.job_get_textlink_code}</td>
				</tr>

				<tr>
					<td colspan="5">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="100"><b>{$FormElements.job_channel}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="440">
						<select name="job_channel" id="job_channel" style="width:280px" onChange="updateTextLinkCode();">
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
					<td width="100"><b>{$FormElements.job_target}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="440">
						<select name="job_target" id="job_target" style="width:280px" onChange="updateTextLinkCode();">
						{strip}
							{section name=j loop=$FormElements.job_target_items}
								<option value="{$FormElements.job_target_items[j].value}">{$FormElements.job_target_items[j].caption}</option>
							{/section}
						{/strip}
						</select>
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
					<td width="100"><b>{$FormElements.job_simple_link}:</b><br /><small>{$FormElements.job_simple_link_help}</small></td>
					<td width="5">&nbsp;</td>
					<td width="440">
						<table>
						<tr>
							<td width="100">
								<span class="text_data">{$FormElements.job_textlink_title}:</span>
							</td>
							<td>
								<input class="data" type="text" name="job_simple_link" id="job_simple_link" value="{$site_title}" onchange="updateTextLinkCode();" style="width:330px;" />
							</td>
						</tr>
						<tr>
							<td width="100">
								<span class="text_data"><i>{$FormElements.job_textlink_preview}:</i></span>
							</td>
							<td>
								<span name="job_simple_link_preview" id="job_simple_link_preview"></span>
							</td>
						</tr>
						</table>

						<br />

						Code (<a class="codelink" href="javascript:show_hide_html_code('job_code_simple_link');void(0);">show / hide HTML code</a>):<br />
						<textarea class="data" name="job_code_simple_link" id="job_code_simple_link" readonly style="width:440px;height:50px;" onFocus="this.select()" onClick="this.select()">&nbsp;</textarea>

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
					<td width="100"><b>{$FormElements.job_custom_link}:</b><br /><small>{$FormElements.job_custom_link_help}</small></td>
					<td width="5">&nbsp;</td>
					<td width="440">
						<table>
						<tr>
							<td width="100">
								<span class="text_data">{$FormElements.job_textlink_what}:</span>
							</td>
							<td>
								<input class="data" type="text" name="job_custom_link_what" id="job_custom_link_what" value="" onchange="updateTextLinkCode();" style="width:330px;" />
							</td>
						</tr>
						<tr>
							<td width="100">
								<span class="text_data">{$FormElements.job_textlink_where}:</span>
							</td>
							<td>
								<input class="data" type="text" name="job_custom_link_where" id="job_custom_link_where" value="" onchange="updateTextLinkCode();" style="width:330px;" />
							</td>
						</tr>
						<tr>
							<td width="100">
								<span class="text_data">{$FormElements.job_textlink_title}:</span>
							</td>
							<td>
								<input class="data" type="text" name="job_custom_link_title" id="job_custom_link_title" value="{$site_title}" onchange="updateTextLinkCode();" style="width:330px;" />
							</td>
						</tr>
						<tr>
							<td width="100">
								<span class="text_data"><i>{$FormElements.job_textlink_preview}:</i></span>
							</td>
							<td>
								<span name="job_custom_link_preview" id="job_custom_link_preview"></span>
							</td>
						</tr>
						</table>

						<br />

						Code (<a class="codelink" href="javascript:show_hide_html_code('job_code_custom_link');void(0);">show / hide HTML code</a>):<br />
						<textarea class="data" name="job_code_custom_link" id="job_code_custom_link" readonly style="width:440px;height:50px;" onFocus="this.select()" onClick="this.select()">&nbsp;</textarea>

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
</form>
{/strip}
<script type="text/javascript">
	var publisher_id = "{$publisher_id}";
	var site_url = "{$site_url}";
	updateTextLinkCode();
</script>

</center>

<!-- Job Text Link page -->