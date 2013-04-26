function toggle_job_search($idname)
{
	var job_search_basic = get_element('job_search_basic');
	var job_search_advanced = get_element('job_search_advanced');

	switch ($idname) {
		case "job_search_basic":
			job_search_basic.style.display = '';
			job_search_advanced.style.display = 'none';
		break;
		case "job_search_advanced":
			job_search_advanced.style.display = '';
			job_search_basic.style.display = 'none';
		break;
	}

	updatePreview();
}

function toggle_job_where($idname)
{
	var job_where_set_location = get_element('job_where_set_location');

	if ($idname == "job_where_set_location") job_where_set_location.style.display = '';
	else job_where_set_location.style.display = 'none';

	updatePreview();
}

function toggle_job_colors($idname)
{
	var job_sr_colors_set_colors = get_element('job_sr_colors_set_colors');

	if ($idname == "job_sr_colors_set_colors") job_sr_colors_set_colors.style.display = '';
	else job_sr_colors_set_colors.style.display = 'none';

	updatePreview();
}

function set_start_clor_values()
{
/*	var job_start_colors = new Array("#000000","#000033","#000066","#000099","#0000CC","#0000FF","#330000","#330033","#330066","#330099");*/
	var job_start_targets= new Array("job_set_colors_bg","job_set_colors_title","job_set_colors_border","job_set_colors_job_title","job_set_colors_text",
		"job_set_colors_company","job_set_colors_link","job_set_colors_source","job_set_colors_accent","job_set_colors_location");
	var total = job_start_targets.length;

	for (var i=0; i<total; i++)
	{
		ColorPicker_targetInput = get_element(job_start_targets[i]);
		pickColor(job_start_colors[i]);
	}
}

function escape_value(val)
{
	if (typeof(encodeURIComponent) == 'function') {
		return encodeURIComponent(val);
	}
	else {
		return escape(val);
	}
}

function append(name,val)
{
	if (!val) return '';
 return '&' + name + '=' + escape_value(val);
}

function updatePreview()
{
	var jobroll_iframe = get_element('jobroll_iframe');

	var jobroll_iframe_src = '?jobroll_publisher_id=' + jobroll_publisher_id;

	var jobroll_format = '120x600';

	/*Format*/
	for (var i=0; i<document.forms[0].job_format.length; i++) {
		if (document.forms[0].job_format[i].checked)
		{
			jobroll_format = document.forms[0].job_format[i].value;
			jobroll_iframe_src += append('job_format',document.forms[0].job_format[i].value);
			break;
		}
	}

	/*Set Jobroll size*/
	var jrf = "";
	switch(jobroll_format) {
		case "120x600": jrf = "width:120px;height:600px;"; break;
		case "160x600": jrf = "width:160px;height:600px;"; break;
		case "300x250": jrf = "width:300px;height:250px;"; break;
		case "728x90":  jrf = "width:728px;height:90px;"; break;
	}
	jobroll_iframe.style.cssText = jrf;

	/*Job Search*/
	job_search_value = "";
	for (var i=0; i<document.forms[0].job_search.length; i++) {
		if (document.forms[0].job_search[i].checked)
		{
			job_search_value = document.forms[0].job_search[i].value;
			jobroll_iframe_src += append('job_search',document.forms[0].job_search[i].value);
			break;
		}
	}

	/*basic job search*/
	if (job_search_value == "job_search_basic") {
		jobroll_iframe_src += append('job_what',get_element('job_what').value);
	}
	/*advanced job search*/
	else if (job_search_value == "job_search_advanced") {
		jobroll_iframe_src += append('job_with_all',get_element('job_with_all').value);
		jobroll_iframe_src += append('job_exact_phrase',get_element('job_exact_phrase').value);
		jobroll_iframe_src += append('job_at_least_one',get_element('job_at_least_one').value);
		jobroll_iframe_src += append('job_none',get_element('job_none').value);
		jobroll_iframe_src += append('job_title',get_element('job_title').value);
		jobroll_iframe_src += append('job_company',get_element('job_company').value);
 		job_type_elem = get_element('job_type');
		jobroll_iframe_src += append('job_type',job_type_elem.options[job_type_elem.selectedIndex].value);
 		job_show_from_elem = get_element('job_show_from');
		jobroll_iframe_src += append('job_show_from',job_show_from_elem.options[job_show_from_elem.selectedIndex].value);
		job_exclude_staffing_chbox = get_element('job_exclude_staffing');
		if (job_exclude_staffing_chbox.checked) jobroll_iframe_src += append('job_exclude_staffing',job_exclude_staffing_chbox.value);
	}

	/*Where*/
	job_where_value = "";
	for (var i=0; i<document.forms[0].job_where.length; i++) {
		if (document.forms[0].job_where[i].checked)
		{
			job_where_value = document.forms[0].job_where[i].value;
			jobroll_iframe_src += append('job_where',document.forms[0].job_where[i].value);
			break;
		}
	}

	/*set location*/
	if (job_where_value == "job_where_set_location") {
 		job_country_elem = get_element('job_country');
		jobroll_iframe_src += append('job_country',job_country_elem.options[job_country_elem.selectedIndex].value);
		jobroll_iframe_src += append('job_city_state',get_element('job_city_state').value);
	}

	/*set channel*/
	job_channel_elem = get_element('job_channel');
	jobroll_iframe_src += append('job_channel',job_channel_elem.options[job_channel_elem.selectedIndex].value);


	/*Where*/
	job_sr_colors_value = "";
	for (var i=0; i<document.forms[0].job_sr_colors.length; i++) {
		if (document.forms[0].job_sr_colors[i].checked)
		{
			job_sr_colors_value = document.forms[0].job_sr_colors[i].value;
			jobroll_iframe_src += append('job_sr_colors',document.forms[0].job_sr_colors[i].value);
			break;
		}
	}

	/*customize colors*/
	if (job_sr_colors_value == "job_sr_colors_set_colors") {
		jobroll_iframe_src += append('job_set_colors_bg',get_element('job_set_colors_bg').value);
		jobroll_iframe_src += append('job_set_colors_title',get_element('job_set_colors_title').value);
		jobroll_iframe_src += append('job_set_colors_border',get_element('job_set_colors_border').value);
		jobroll_iframe_src += append('job_set_colors_job_title',get_element('job_set_colors_job_title').value);
		jobroll_iframe_src += append('job_set_colors_text',get_element('job_set_colors_text').value);
		jobroll_iframe_src += append('job_set_colors_company',get_element('job_set_colors_company').value);
		jobroll_iframe_src += append('job_set_colors_link',get_element('job_set_colors_link').value);
		jobroll_iframe_src += append('job_set_colors_source',get_element('job_set_colors_source').value);
		jobroll_iframe_src += append('job_set_colors_accent',get_element('job_set_colors_accent').value);
		jobroll_iframe_src += append('job_set_colors_location',get_element('job_set_colors_location').value);
	}

	jobroll_iframe_src = adsshowjobs_php_script + jobroll_iframe_src;

	/*write javascript code*/
	job_code = get_element('job_code');
	job_code.value = '<script type="text/javascript"><!--\n\
jobroll_format = "' + jobroll_format + '";\n\
jobroll_params_list = "' + jobroll_iframe_src + '";\n\
//--></scr' + 'ipt>\n\
<script type="text/javascript" src="' + adsshowjobs_js_script +'"></scr' + 'ipt>\n\
<noscript><a href="http://www.es-job-search-engine.com/">Jobs</a> ES Job Search Engine</noscript>\n';

	/*upadate iframe*/
	jobroll_iframe.src = jobroll_iframe_src;
}

function add_new_channel(channel_script, SLINE)
{
	function checkChannel(ch) {
		var badchar = /[:"\\<>]/;
		var result = ch.match(badchar);
		if (result != null) {
			alert("Channel name may not contain colons, double quotes, greater-than/less-than symbols, or backslashes");
			return false;
		}
		var select = get_element('job_channel');
		var lcch = ch.toLowerCase();
		for (i = 0; i < select.options.length; i++) {
			if (select.options[i].value.toLowerCase() == lcch) { // duplicate
				select.options[i].selected = true;
				updatePreview();
				return false;
			}
		}
		return true;
	}

	function add_channel_to_server(channel_script, channel_name)
	{
		JsHttpRequest.query(
			channel_script, // backend
			{
				// pass a text value 
				'action': 'add',
				'channel_name': channel_name
			},
			// Function is called when an answer arrives. 
			function(result, errors)
			{
				if (errors != "") alert(errors);
	    	// Write errors to the debug div.
			 // get_element("debug").innerHTML = errors;
		    // Write the answer.
		    if (result) {
					if (result["new_data"] == "1") {
						channel_elem = get_element('job_channel');
						channel_elem.options[channel_elem.length] = new Option(result["name"],result["value"],false,true);
					}
				}
			},
			true  // do not disable caching
		);
	}

	var new_channel = prompt("Enter new channel name", "Untitled");
	while (new_channel !== null && new_channel.length > 32) {
		new_channel = prompt("Enter new channel name (less than 32 characters)", new_channel);
	}

	if (new_channel == null || new_channel == "") return false; 

	if (!checkChannel(new_channel)) return false; 

	add_channel_to_server(channel_script + '?' + SLINE, new_channel);
}

function delete_channel(channel_script, SLINE)
{
	function delete_channel_from_server(channel_script, channel_id, option_index)
	{
		JsHttpRequest.query(
			channel_script, // backend
			{
				// pass a text value 
				'action': 'delete',
				'channel_id': channel_id
			},
			// Function is called when an answer arrives. 
			function(result, errors)
			{
				if (errors != "") alert(errors);
	    	// Write errors to the debug div.
			 // get_element("debug").innerHTML = errors;
		    // Write the answer.
		    if (result) {
					if (result["new_data"] == "1") {
						channel_elem = get_element('job_channel');
						channel_elem.options[option_index] = null;
						channel_elem.selectedIndex = 0;
					}
				}
			},
			true  // do not disable caching
		);
	}

	channel_elem = get_element('job_channel');
	var id = channel_elem.options[channel_elem.selectedIndex].value;
	if (id == 0) return false;

	delete_channel_from_server(channel_script, id, channel_elem.selectedIndex);
}

function show_hide_jobroll_code(table)
{
	var create_jobroll_table = get_element('create_jobroll_table');
	var editnew_jobroll_table = get_element('editnew_jobroll_table');

	if (table == "create_jobroll_table") {
		create_jobroll_table.style.display = 'none';
		editnew_jobroll_table.style.display = '';
	}
	else if (table == "editnew_jobroll_table") {
		editnew_jobroll_table.style.display = 'none';
		create_jobroll_table.style.display = '';
	}
}

function show_hide_html_code(area)
{
	var area = get_element(area);
	if (area.style.display != '') area.style.display = '';
	else area.style.display = 'none';
}

/*Update code on "Create Job Search Box" form*/
function updateSearchBoxsCode()
{
	/*set channel*/
	job_channel_elem = get_element('job_channel');
	job_channel_value = job_channel_elem.options[job_channel_elem.selectedIndex].value;


	/*option 1*/
	job_option = get_element('job_option1');
	job_option.value = '<form action="' + site_url + 'jobs/" method="GET">\n\
<input type="hidden" name="publisher_id" value="' + publisher_id + '" />\n\
<input type="hidden" name="job_channel" value="' + job_channel_value + '" />\n\
<table cellspacing="0" style="font-family:arial">\n\
<tr>\n\
  <td colspna="2" style="font-size:16px;color:#FF6600"><b>Job Search</b></td>\n\
</tr>\n\
<tr>\n\
  <td><input name="what_where" value="" size="25" /></td>\n\
  <td><input type="submit" value="Find Jobs" /></td>\n\
</tr>\n\
<tr>\n\
  <td valign="top" style="font-size:10px">job title, keywords, company, location</td>\n\
  <td valign="top" style="font-size:10px;"><span><a href="' + site_url + '?publisher_id=' + publisher_id + '&job_channel=' + job_channel_value + '" style="text-decoration:none;color:#000">jobs</a> by <a href="' + site_url + '?publisher_id=' + publisher_id + '&job_channel=' + job_channel_value + '" title="Job Search"><img src="' + site_url + 'frontend/images/jobsearch.gif" style="border:0;vertical-align:middle;" alt="ES Job Search Engine search"></a></span></td>\n\
</tr>\n\
</table>\n\
</form>';


	/*option 2*/
	job_option = get_element('job_option2');
	job_option.value = '<form action="' + site_url + 'jobs/" method="GET">\n\
<input type="hidden" name="publisher_id" value="' + publisher_id + '" />\n\
<input type="hidden" name="job_channel" value="' + job_channel_value + '" />\n\
<table cellspacing="0" style="font-family:arial">\n\
<tr>\n\
  <td style="font-size:16px;color:#FF6600"><b>what</b></td>\n\
  <td style="font-size:16px;color:#FF6600"><b>where</b></td>\n\
  <td>&nbsp;</td>\n\
</tr>\n\
<tr>\n\
  <td><input name="what" value="" size="25" /></td>\n\
  <td><input name="where" value="" size="" /></td>\n\
  <td><input type="submit" value="Find Jobs" /></td>\n\
</tr>\n\
<tr>\n\
  <td valign="top" style="font-size:10px">job title, keywords or company</td>\n\
  <td valign="top" style="font-size:10px">city, state or zip</td>\n\
  <td valign="top" style="font-size:10px;"><span><a href="' + site_url + '?publisher_id=' + publisher_id + '&job_channel=' + job_channel_value + '" style="text-decoration:none;color:#000">jobs</a> by <a href="' + site_url + '?publisher_id=' + publisher_id + '&job_channel=' + job_channel_value + '" title="Job Search"><img src="' + site_url + 'frontend/images/jobsearch.gif" style="border:0;vertical-align:middle;" alt="ES Job Search Engine search"></a></span></td>\n\
</tr>\n\
</table>\n\
</form>';


	/*option 3*/
	job_option = get_element('job_option3');
	job_option.value = '<form action="' + site_url + 'jobs/" method="GET">\n\
<input type="hidden" name="publisher_id" value="' + publisher_id + '" />\n\
<input type="hidden" name="job_channel" value="' + job_channel_value + '" />\n\
<table cellspacing="0" style="font-family:arial">\n\
<tr>\n\
  <td style="font-size:16px;color:#FF6600"><b>what</b></td>\n\
</tr>\n\
<tr>\n\
  <td><input name="what" value="" size="20"></td>\n\
</tr>\n\
<tr>\n\
  <td valign="top" style="font-size:10px">job title, keywords</td></tr>\n\
<tr>\n\
  <td style="font-size:16px;color:#FF6600"><b>where</b></td>\n\
</tr>\n\
<tr>\n\
  <td><input name="where" value="" size="20"></td>\n\
</tr>\n\
<tr>\n\
  <td valign="top" style="font-size:10px">city, state, zip</td>\n\
</tr>\n\
<tr>\n\
  <td><input type="submit" value="Find Jobs"></td>\n\
</tr>\n\
<tr>\n\
  <td valign="top" style="font-size:10px;"><span><a href="' + site_url + '?publisher_id=' + publisher_id + '&job_channel=' + job_channel_value + '" style="text-decoration:none;color:#000">jobs</a> by <a href="' + site_url + '?publisher_id=' + publisher_id + '&job_channel=' + job_channel_value + '" title="Job Search"><img src="' + site_url + 'frontend/images/jobsearch.gif" style="border:0;vertical-align:middle;" alt="ES Job Search Engine search"></a></span></td>\n\
</tr>\n\
</table>\n\
</form>';
}

/*Update code on "Create Text Link" form*/
function updateTextLinkCode()
{
	/*set channel*/
	job_channel_elem = get_element('job_channel');
	job_channel_value = job_channel_elem.options[job_channel_elem.selectedIndex].value;

	/*check job target*/
	job_target = get_element('job_target').value;
	if (job_target == "0") job_target = ' target="_blank"';
	else job_target = '';

	/*search link url*/
	var homepage_url = site_url + '?publisher_id=' + publisher_id + '&job_channel=' + job_channel_value;
	var search_url = site_url + 'jobs/?publisher_id=' + publisher_id + '&job_channel=' + job_channel_value;

	/*simple link*/
	job_text = get_element('job_simple_link').value;
	job_option = get_element('job_code_simple_link');
	if (job_text == '')	job_text = 'No Title';
	job_option.value = '<a href="' + homepage_url + '"' + job_target + '>' + job_text + '</a>';
	get_element('job_simple_link_preview').innerHTML = '<a href="' + homepage_url + '" class="text_data"' + job_target + '>' + job_text + '</a>';

	/*custom link*/
	job_text = get_element('job_custom_link_title').value;
	job_option = get_element('job_code_custom_link');
	if (job_text == '')	job_text = 'No Title';

	search_url += append('what',get_element('job_custom_link_what').value);
	search_url += append('where',get_element('job_custom_link_where').value);

	job_option.value = '<a href="' + search_url + '"' + job_target + '>' + job_text + '</a>';;
	get_element('job_custom_link_preview').innerHTML = '<a href="' + search_url + '" class="text_data"' + job_target + '>' + job_text + '</a>';
}
