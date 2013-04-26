<!-- Job XML Feed page -->
{strip}
<center>
{include file="spacer_img.tpl" sheight="7" swidth="1"}  {*Space*}

<table width="100%" cellPadding="0" cellSpacing="0">
<tr>
<td valign="top" align="center">

<form>
{*Create XML Feed  table (main)*}
<table name="create_job_xmlfeed_table" id="create_job_xmlfeed_table"  class="tblborder1" cellPadding="0" cellSpacing="0">
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
					<td width="100"><b>{$FormElements.job_attribution}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="460">

						{$FormElements.job_attribution_help}

						<br />

						Code (<a class="codelink" href="javascript:show_hide_html_code('job_code_attribution');void(0);">show / hide HTML code</a>):<br />
						<textarea class="data" name="job_code_attribution" id="job_code_attribution" readonly style="width:460px;height:100px;" onFocus="this.select()" onClick="this.select()">
							&lt;span&gt;&lt;a href="{$site_url}?publisher_id={$publisher_id}"&gt;jobs&lt;/a&gt; by &lt;a href="{$site_url}?publisher_id={$publisher_id}" title="{$FormElements.job_search}"&gt;&lt;img src="{$site_url}/frontend/images/jobsearch.gif" style="border:0;vertical-align:middle;" alt="{$FormElements.job_search_alt}"&gt;&lt;/a&gt;&lt;/span&gt;
						</textarea>
						<!-- <span><a href="{$site_url}?publisher_id={$publisher_id}">jobs</a> by <a href="{$site_url}?publisher_id={$publisher_id}" title="{$FormElements.job_search}"><img src="{$site_url}/frontend/images/jobsearch.gif" style="border:0;vertical-align:middle;" alt="{$FormElements.job_search_alt}"></a></span> -->

					</td>
					<td width="3">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="5">&nbsp;</td>
				</tr>

				<tr>
					<td width="3">&nbsp;</td>
					<td width="100"><b>{$FormElements.job_sample_request}:</b></td>
					<td width="5">&nbsp;</td>
					<td width="460">

						{$FormElements.job_sample_rhelp1}

						<pre>
							{$xmlsearch_script}?publisher_id=<b>publisher_id</b>&<br />
							what=<b>search_query</b>&where=<b>location</b>&sort=<b>sort_type</b>&radius=<b>distance</b>&<br />
							site_type=<b>site_type</b>&job_type=<b>job_type</b>&start=<b>start_results</b>&<br />
							limit=<b>max_number</b>&fromage=<b>job_age</b>&highlight=<b>bold_terms</b>&<br />
							latlong=<b>latlong</b>&userip=<b>user_ip_address</b>&useragent=<b>user_bowser_agent</b>
						</pre>

						<b>{$FormElements.job_sample_rhelp2}</b> (<a class="codelink" href="javascript:show_hide_html_code('job_params_list');void(0);">show / hide params</a>)<b>:</b>

						<br />

						<table border="1" cellspacing="0" cellpadding="4" name="job_params_list" id="job_params_list" class="text_data2">
						<tr>
							<th>publisher_id</th>
							<td>{$FormElements.job_params_publisher_id}</td>
						</tr>
						<tr>
							<th>what</th>
							<td>{$FormElements.job_params_what}</td>
            </tr>
						<tr>
							<th>where</th>
							<td>{$FormElements.job_params_where}</td>
						</tr>
						<tr>
            	<th>sort</th>
							<td>{$FormElements.job_params_sort}</td>
            </tr>
            <tr>
							<th>radius</th>
							<td>{$FormElements.job_params_radius}</td>
						</tr>
						<tr>
							<th>site_type</th>
            	<td>{$FormElements.job_params_site_type}</td>
						</tr>
						<tr>
							<th>job_type</th>
							<td>{$FormElements.job_params_job_type}</td>
						</tr>
						<tr>
            	<th>start</th>
							<td>{$FormElements.job_params_start}</td>
            </tr>
						<tr>
            	<th>limit</th>
							<td>{$FormElements.job_params_limit}</td>
						</tr>
            <tr>
							<th>fromage</th>
            	<td>{$FormElements.job_params_fromage}</td>
						</tr>
            <tr>
            	<th>highlight</th>
							<td>{$FormElements.job_params_highlight}</td>
						</tr>
						<tr>
							<th>latlong</th>
							<td>{$FormElements.job_params_latlong}</td>
						</tr>
						<tr>
							<th>userip</th>
							<td>{$FormElements.job_params_userip}</td>
						</tr>
						<tr>
            	<th>useragent</th>
							<td>{$FormElements.job_params_useragent}</td>
            </tr>    
						</table>

						<br />
						
						<b>{$FormElements.job_sample_rhelp3}</b>

						<pre>
							{$xmlsearch_script}?publisher_id={$publisher_id}&<br />
							what=test&where=london&sort=&radius=&site_type=&job_type=&<br />
							start=&limit=&fromage=&highlight=&latlong=1&userip=10.10.10.10&<br />
							useragent=Mozilla/%2F4.0%28Firefox%29
						</pre>

						<b>{$FormElements.job_sample_rhelp4}</b>

						<br />

						{$FormElements.job_sample_rhelp5} (<a class="codelink" href="javascript:show_hide_html_code('job_xml_result');void(0);">show / hide result</a>):


{literal}
<pre name="job_xml_result" id="job_xml_result">&lt;?xml version="1.0" encoding="utf-8"?&gt;
&lt;trovit>
        &lt;ad&gt;
         &lt;id&gt;&lt;![CDATA[c331111]]&gt;&lt;/id&gt;
         &lt;url&gt;&lt;![CDATA[http://www.searchforjobs.co.uk/job-details/?data-id=331111&PHPSESSID=
          43df70d67c008c3f7d1035945555bc47&jobroll-publisher-id=2&ssk[]=2056&ssk[]=2057]]&gt;&lt;/url&gt;
         &lt;title&gt;&lt;![CDATA[QA Engineer / Test Engineer]]&gt;&lt;/title&gt;
         &lt;content&gt;&lt;![CDATA[QA engineer / Test Engineer for dynamic digital media company based in
          &lt;b&gt;London&lt;/b&gt; ... pension and share options. All qa engineer, &lt;b&gt;test&lt;/b&gt;...]]&gt;
         &lt;/content&gt;
         &lt;city&gt;&lt;![CDATA[London]]&gt;&lt;/city&gt;
         &lt;city_area&gt;&lt;![CDATA[]]&gt;&lt;/city_area&gt;
         &lt;region&gt;&lt;![CDATA[GB]]&gt;&lt;/region&gt;
         &lt;postcode&gt;&lt;![CDATA[]]&gt;&lt;/postcode&gt;
         &lt;salary&gt;&lt;![CDATA[]]&gt;&lt;/salary&gt;
         &lt;salary_numeric&gt;&lt;![CDATA[]]&gt;&lt;/salary_numeric&gt;
         &lt;working_hours&gt;&lt;![CDATA[]]&gt;&lt;/working_hours&gt;
         &lt;company&gt;&lt;![CDATA[Anderson Cole Recruitment]]&gt;&lt;/company&gt;
         &lt;experience&gt;&lt;![CDATA[]]&gt;&lt;/experience&gt;
         &lt;requirements&gt;&lt;![CDATA[]]&gt;&lt;/requirements&gt;
         &lt;contract&gt;&lt;![CDATA[]]&gt;&lt;/contract&gt;
         &lt;category&gt;&lt;![CDATA[Manufacturing]]&gt;&lt;/category&gt;
         &lt;date&gt;&lt;![CDATA[02/05/2010]]&gt;&lt;/date&gt;
         &lt;time&gt;&lt;![CDATA[00:37:58]]&gt;&lt;/time&gt;
         &lt;studies&gt;&lt;![CDATA[]]&gt;&lt;/studies&gt;
        &lt;/ad&gt;
      ...
&lt;/trovit&gt;</pre>            
{/literal}

						<br />

						{$FormElements.job_sample_rhelp6} (<a class="codelink" href="javascript:show_hide_html_code('job_xml_error');void(0);">show / hide result</a>):


{literal}
<pre name="job_xml_error" id="job_xml_error">&lt;?xml version="1.0" encoding="ISO-8859-1"?&gt;
&lt;response&gt;
   &lt;error&gt;Sorry, but XML feed not active for you. Please, activate
   XML feed for your account.&lt;/error&gt;
&lt;/response&gt;</pre>            
{/literal}


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

</center>

<!-- Job XML Feed page -->