<!-- Jobroll page -->
{strip}
<center>
{include file="spacer_img.tpl" sheight="7" swidth="1"}  {*Space*}

<table width="100%" cellPadding="0" cellSpacing="0">
<tr>
<td valign="top" align="center">

<form>
{*Create Jobroll table (main)*}
<table name="create_jobroll_table" id="create_jobroll_table"  class="tblborder1" cellPadding="0" cellSpacing="0">
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
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_format}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<input type="radio" name="job_format" value="120x600" checked onClick="updatePreview()" />120(px)&nbsp; X &nbsp;600(px)<br />
						<input type="radio" name="job_format" value="160x600" onClick="updatePreview()" />160(px)&nbsp; X &nbsp;600(px)<br />
						<input type="radio" name="job_format" value="300x250" onClick="updatePreview()" />300(px)&nbsp; X &nbsp;250(px)<br />
						<input type="radio" name="job_format" value="728x90" onClick="updatePreview()" />728(px)&nbsp; X &nbsp;90(px)
					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_search}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<input type="radio" name="job_search" value="job_search_basic" onClick="toggle_job_search('job_search_basic');" checked />{$FormElements.job_search_basic}<br />
						<input type="radio" name="job_search" value="job_search_advanced" onClick="toggle_job_search('job_search_advanced');" />{$FormElements.job_search_advanced}
					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tbody name="job_search_basic" id="job_search_basic">
				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_what}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<input class="data" type="text" name="job_what" id="job_what" value="" maxlength="100" style="width:330px" onchange="updatePreview()" />
					</td>
					<td width="3">&nbsp;</td>
				</tr>
				</tbody>

				<tbody name="job_search_advanced" id="job_search_advanced" style="display:none;">
				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_with_all}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<input class="data" type="text" name="job_with_all" id="job_with_all" value="" maxlength="100" style="width:330px" onchange="updatePreview()" />
					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_exact_phrase}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<input class="data" type="text" name="job_exact_phrase" id="job_exact_phrase" value="" maxlength="100" style="width:330px" onchange="updatePreview()" />
					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_at_least_one}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<input class="data" type="text" name="job_at_least_one" id="job_at_least_one" value="" maxlength="100" style="width:330px" onchange="updatePreview()" />
					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_none}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<input class="data" type="text" name="job_none" id="job_none" value="" maxlength="100" style="width:330px" onchange="updatePreview()" />
					</td>
					<td width="3">&nbsp;</td>
				</tr>


				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_title}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<input class="data" type="text" name="job_title" id="job_title" value="" maxlength="100" style="width:330px" onchange="updatePreview()" />
					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_company}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<input class="data" type="text" name="job_company" id="job_company" value="" maxlength="100" style="width:330px" onchange="updatePreview()" />
					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_type}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<select name="job_type" id="job_type" style="width:330px" onchange="updatePreview()">
						{strip}
							{section name=j loop=$FormElements.job_type_items}
								<option value="{$FormElements.job_type_items[j].value}">{$FormElements.job_type_items[j].caption}</option>
							{/section}
						{/strip}
						</select>
					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_show_from}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<select name="job_show_from" id="job_show_from" style="width:330px" onchange="updatePreview()">
						{strip}
							{section name=j loop=$FormElements.job_show_from_items}
								<option value="{$FormElements.job_show_from_items[j].value}">{$FormElements.job_show_from_items[j].caption}</option>
							{/section}
						{/strip}
						</select>
					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_exclude_staffing}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<input class="data" type="checkbox" name="job_exclude_staffing" id="job_exclude_staffing" value="1" onchange="updatePreview()" />
					</td>
					<td width="3">&nbsp;</td>
				</tr>
				</tbody>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_where}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<input type="radio" name="job_where" value="job_where_viewers_location" onClick="toggle_job_where('job_where_viewers_location');" checked />{$FormElements.job_where_viewers_location}<br />
						<input type="radio" name="job_where" value="job_where_set_location" onClick="toggle_job_where('job_where_set_location');" />{$FormElements.job_where_set_location}
					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tbody name="job_where_set_location" id="job_where_set_location" style="display:none;">
				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_country}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<select name="job_country" id="job_country" style="width:330px" onchange="updatePreview()">
						{strip}
							{section name=j loop=$FormElements.job_country_items}
								<option value="{$FormElements.job_country_items[j].value}">{$FormElements.job_country_items[j].caption}</option>
							{/section}
						{/strip}
						</select>
					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_city_state}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<input class="data" type="text" name="job_city_state" id="job_city_state" value="" maxlength="100" style="width:330px" onchange="updatePreview()" />
					</td>
					<td width="3">&nbsp;</td>
				</tr>
				</tbody>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_channel}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<select name="job_channel" id="job_channel" style="width:180px">
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
					<td width="200"><b>{$FormElements.job_sr_colors}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="330">
						<input type="radio" name="job_sr_colors" value="job_sr_colors_default" onClick="toggle_job_colors('job_sr_colors_default');" checked />{$FormElements.job_sr_colors_default}<br />
						<input type="radio" name="job_sr_colors" value="job_sr_colors_set_colors" onClick="toggle_job_colors('job_sr_colors_set_colors');" />{$FormElements.job_sr_colors_set_colors}
					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tbody name="job_sr_colors_set_colors" id="job_sr_colors_set_colors" style="display:none;">
				<tr>
					<td width="3">&nbsp;</td>
					<td width="200"><b>{$FormElements.job_set_colors}:</b></td>
					<td width="8">&nbsp;</td>
					<td width="330">

						<script type="text/javascript">var cp = new ColorPicker();</script>
						<table class="regular">
							<tr>
								<td>{$FormElements.job_set_colors_bg}:</td>
								<td>
									<input class="data" type="text" name="job_set_colors_bg" id="job_set_colors_bg" value="" maxlength="7" style="width:60px" />
								</td>
								<td>
									<span name="job_set_colors_bg_example" id="job_set_colors_bg_example" onClick="cp.select(job_set_colors_bg,'job_set_colors_bg_example'); return false;" style="cursor:pointer;width:18px;height:18px;background-color:#FFF;border:solid 1px #999;" title="Select color">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>

								<td width="5">&nbsp;</td>

								<td>{$FormElements.job_set_colors_title}:</td>
								<td>
									<input class="data" type="text" name="job_set_colors_title" id="job_set_colors_title" value="" maxlength="7" style="width:60px" />
								</td>
								<td>
									<span name="job_set_colors_title_example" id="job_set_colors_title_example" onClick="cp.select(job_set_colors_title,'job_set_colors_title_example');return false;" style="cursor:pointer;width:18px;height:18px;background-color:#FFF;border:solid 1px #999;" title="Select color">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>

							<tr>
								<td>{$FormElements.job_set_colors_border}:</td>
								<td>
									<input class="data" type="text" name="job_set_colors_border" id="job_set_colors_border" value="" maxlength="7" style="width:60px" />
								</td>
								<td>
									<span name="job_set_colors_border_example" id="job_set_colors_border_example" onClick="cp.select(job_set_colors_border,'job_set_colors_border_example'); return false;" style="cursor:pointer;width:18px;height:18px;background-color:#FFF;border:solid 1px #999;" title="Select color">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>

								<td width="5">&nbsp;</td>

								<td>{$FormElements.job_set_colors_job_title}:</td>
								<td>
									<input class="data" type="text" name="job_set_colors_job_title" id="job_set_colors_job_title" value="" maxlength="7" style="width:60px" />
								</td>
								<td>
									<span name="job_set_colors_job_title_example" id="job_set_colors_job_title_example" onClick="cp.select(job_set_colors_job_title,'job_set_colors_job_title_example');return false;" style="cursor:pointer;width:18px;height:18px;background-color:#FFF;border:solid 1px #999;" title="Select color">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>

							<tr>
								<td>{$FormElements.job_set_colors_text}:</td>
								<td>
									<input class="data" type="text" name="job_set_colors_text" id="job_set_colors_text" value="" maxlength="7" style="width:60px" />
								</td>
								<td>
									<span name="job_set_colors_text_example" id="job_set_colors_text_example" onClick="cp.select(job_set_colors_text,'job_set_colors_text_example'); return false;" style="cursor:pointer;width:18px;height:18px;background-color:#FFF;border:solid 1px #999;" title="Select color">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>

								<td width="5">&nbsp;</td>

								<td>{$FormElements.job_set_colors_company}:</td>
								<td>
									<input class="data" type="text" name="job_set_colors_company" id="job_set_colors_company" value="" maxlength="7" style="width:60px" />
								</td>
								<td>
									<span name="job_set_colors_company_example" id="job_set_colors_company_example" onClick="cp.select(job_set_colors_company,'job_set_colors_company_example');return false;" style="cursor:pointer;width:18px;height:18px;background-color:#FFF;border:solid 1px #999;" title="Select color">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>

							<tr>
								<td>{$FormElements.job_set_colors_link}:</td>
								<td>
									<input class="data" type="text" name="job_set_colors_link" id="job_set_colors_link" value="" maxlength="7" style="width:60px" />
								</td>
								<td>
									<span name="job_set_colors_link_example" id="job_set_colors_link_example" onClick="cp.select(job_set_colors_link,'job_set_colors_link_example'); return false;" style="cursor:pointer;width:18px;height:18px;background-color:#FFF;border:solid 1px #999;" title="Select color">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>

								<td width="5">&nbsp;</td>

								<td>{$FormElements.job_set_colors_source}:</td>
								<td>
									<input class="data" type="text" name="job_set_colors_source" id="job_set_colors_source" value="" maxlength="7" style="width:60px" />
								</td>
								<td>
									<span name="job_set_colors_source_example" id="job_set_colors_source_example" onClick="cp.select(job_set_colors_source,'job_set_colors_source_example');return false;" style="cursor:pointer;width:18px;height:18px;background-color:#FFF;border:solid 1px #999;" title="Select color">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>

							<tr>
								<td>{$FormElements.job_set_colors_accent}:</td>
								<td>
									<input class="data" type="text" name="job_set_colors_accent" id="job_set_colors_accent" value="" maxlength="7" style="width:60px" />
								</td>
								<td>
									<span name="job_set_colors_accent_example" id="job_set_colors_accent_example" onClick="cp.select(job_set_colors_accent,'job_set_colors_accent_example'); return false;" style="cursor:pointer;width:18px;height:18px;background-color:#FFF;border:solid 1px #999;" title="Select color">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>

								<td width="5">&nbsp;</td>

								<td>{$FormElements.job_set_colors_location}:</td>
								<td>
									<input class="data" type="text" name="job_set_colors_location" id="job_set_colors_location" value="" maxlength="7" style="width:60px" />
								</td>
								<td>
									<span name="job_set_colors_location_example" id="job_set_colors_location_example" onClick="cp.select(job_set_colors_location,'job_set_colors_location_example');return false;" style="cursor:pointer;width:18px;height:18px;background-color:#FFF;border:solid 1px #999;" title="Select color">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
							</tr>
						</table>

						{literal}
						<script type="text/javascript">cp.writeDiv();</script>

						<script type="text/javascript">
						function get_cssText(color)
						{
							return "'cursor:pointer;width:18px;height:18px;background-color:'+color+';border:solid 1px #999;'";
						}
						function pickColor(color)
						{
							ColorPicker_targetInput.value = color;
							eval('get_element("' + ColorPicker_targetInput.name + '_example").style.cssText = ' + get_cssText(color) + ';');
				 			if (do_preview) updatePreview();
						}
						</script>
            {/literal}
					</td>

				</tr>
        </tbody>

				{*Get Jobroll code button*}
				<tr>
					<td colspan="5" align="right">
						{include file="form/buttons.tpl" CurFormButtons=$GetJobrollCodeButton}
					</td>
				</tr>

				</table>

			</td>
		</tr>
		</table>
	</td>
</tr>
</table>

{*Get Jobroll textarea*}
<table name="editnew_jobroll_table" id="editnew_jobroll_table" class="tblborder1" cellPadding="0" cellSpacing="0" style="display: none;">
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
					<td width="3">&nbsp;</td>
					<td width="100"><b>{$FormElements.job_code}:</b><br /><small>{$FormElements.job_code_help}</small></td>
					<td width="5">&nbsp;</td>
					<td width="430">
						<textarea class="data" name="job_code" id="job_code" readonly style="width:430px;height:200px;" onFocus="this.select()" onClick="this.select()">&nbsp;</textarea>
					</td>
					<td width="3">&nbsp;</td>
				</tr>

				{*Get Jobroll code button*}
				<tr>
					<td colspan="5" align="right">
						{include file="form/buttons.tpl" CurFormButtons=$EtidNewJobrollCodeButtons}
					</td>
				</tr>

				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>

</td>

<td width="10">&nbsp;</td>

<td valign="top" align="center">


<table class="tblborder1" cellPadding="0" cellSpacing="0">
<tr>
	<td>
		<table class="tblborder2" cellPadding="0" cellSpacing="0">
		<tr>
			<td>
				<table class="tblborder3" cellPadding="3" cellSpacing="0">
				<tr>
					<td class="tblborder_td_head">{$form_preview_header}</td>
				</tr>
				<tr>
					<td height="2" class="tblborder_td_head_line"></td>
				</tr>

				<tr>
					<td>
					   <iframe name="jobroll_iframe" id="jobroll_iframe" style="width:720px;height:600px;" frameborder="0" src="" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true" scrolling="no"></iframe>
					</td>
				</tr>

				</table>
			</td>
		</td>
		</table>
	</td>
</tr>
</table>

</td>
</tr>
</table>
</form>

<script type="text/javascript">
	var adsshowjobs_php_script = "{$adsshowjobs_php_script}";
	var adsshowjobs_js_script = "{$adsshowjobs_js_script}";
	var jobroll_publisher_id = "{$jobroll_publisher_id}";
	var job_start_colors = new Array({$jobroll_start_colors});
	var do_preview = false;
	set_start_clor_values();
	do_preview = true;
	updatePreview();
</script>

</center>
{/strip}
<!-- Jobroll page -->