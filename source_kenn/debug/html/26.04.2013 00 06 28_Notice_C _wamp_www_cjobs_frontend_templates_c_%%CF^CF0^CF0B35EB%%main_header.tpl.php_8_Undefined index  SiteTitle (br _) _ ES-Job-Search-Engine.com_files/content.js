function get_element(s_id)
{
	return (document.all ? document.all[s_id] : (document.getElementById ? document.getElementById(s_id) : null));
}

function show_hide_categories(num)
{
	if (num == 1) {
		get_element("more_categories_link").style.display = 'none';
		get_element("more_categories_list").style.display = '';
	}
	else {
		get_element("more_categories_list").style.display = 'none';
		get_element("more_categories_link").style.display = '';
	}
}


function show_hide_leftside(num)
{
	if (num == 1) {
		get_element("left_side").style.display = 'none';
		get_element("refine_your_search_space").style.display = 'none';
		get_element("refine_your_search").style.display = '';
		var center_side = get_element("center_side");
    center_side.style.width = (parseInt(center_side.style.width) + 150) + 'px';
	}
	else {
		get_element("refine_your_search").style.display = 'none';
		get_element("refine_your_search_space").style.display = '';
		get_element("left_side").style.display = '';
		var center_side = get_element("center_side");
    center_side.style.width = (parseInt(center_side.style.width) - 150) + 'px';
	}
}

function hide_recent_job_searches()
{
	get_element("recent_job_searches_filter_record").style.display = 'none';
	get_element("recent_job_searches_filter_title").style.display = 'none';
}

function toggleRefineBy(img_url,img_elem,tr_elem)
{
	tr_disp = get_element(tr_elem).style.display;
	if (tr_disp == 'none') {
		get_element(tr_elem).style.display = '';
		get_element(img_elem).src = img_url+'arrow_bottom.gif';
	}
	else {
		get_element(tr_elem).style.display = 'none';
		get_element(img_elem).src = img_url+'arrow_right.gif';
	}
}

/*Delete stored in cookie "Recent Job Searches"*/
function delSearchCookie()
{
	var a = new Array();
	var c = new Date();
	var a = window.document.cookie.split(';');
	var	p = -1;
	for (var i=0; i<a.length; i++)
	{
		p = a[i].indexOf("RecentJobSearchesTitle[");
		if (p >= 0) {
			window.document.cookie = a[i] + "; expires=" + c.toGMTString() + ";";
			continue;
		}
		p = a[i].indexOf("RecentJobSearchesLink[");
		if (p >= 0) {
			window.document.cookie = a[i] + "; expires=" + c.toGMTString() + ";";
		}
	}
}

var caution = false;
function setCookie(name, value, expires, path, domain, secure)
{
	var curCookie = name + "=" + escape(value) +
			((expires) ? "; expires=" + expires.toGMTString() : "") +
			((path) ? "; path=" + path : "") +
			((domain) ? "; domain=" + domain : "") +
			((secure) ? "; secure" : "")
	if (!caution || (name + "=" + escape(value)).length <= 4000) document.cookie = curCookie
	else 
	if (confirm("Cookie length more then 4KB! Saved as many as possible.")) document.cookie = curCookie
}

// this fixes an issue with the old method, ambiguous values 
// with this test document.cookie.indexOf( name + "=" );
function getCookie(check_name) {
	// first we'll split this cookie up into name/value pairs
	// note: document.cookie only returns name=value, not the other components
	var a_all_cookies = document.cookie.split( ';' );
	var a_temp_cookie = '';
	var cookie_name = '';
	var cookie_value = '';
	var b_cookie_found = false; // set boolean t/f default f
	
	for (i=0; i<a_all_cookies.length; i++)
	{
		// now we'll split apart each name=value pair
		a_temp_cookie = a_all_cookies[i].split('=');
		// and trim left/right whitespace while we're at it
		cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');
		// if the extracted name matches passed check_name
		if (cookie_name == check_name)
		{
			b_cookie_found = true;
			// we need to handle case where cookie has no value but exists (no = sign, that is):
			if (a_temp_cookie.length > 1)
			{
				cookie_value = unescape(a_temp_cookie[1].replace(/^\s+|\s+$/g, ''));
			}
			// note that in cases where cookie is initialized but no value, null is returned
			return cookie_value;
			break;
		}
		a_temp_cookie = null;
		cookie_name = '';
	}
	if (!b_cookie_found)
	{
		return null;
	}
}

function deleteCookie(name, path, domain)
{
	if (getCookie(name)) {
		document.cookie = name + "=" +
		((path) ? ";path=" + path : "") +
		((domain) ? ";domain=" + domain : "" ) +
		";expires=Thu, 01-Jan-1970 00:00:01 GMT";
	}
}

/*Save job to cookie (My Jobs)*/
function saveJobToCookie(jobkey)
{
	var exdate = new Date();
	exdate.setDate(exdate.getDate()+30*12);
	setCookie("MyJobs_save[" + jobkey + "]", "1", exdate, "/");
}

function hide_and_show_elements(elem1_id,elem2_id)
{
	get_element(elem1_id).style.display = 'none';
	get_element(elem2_id).style.display = '';
}

function hide_element(elem_id)
{
	get_element(elem_id).style.display = 'none';
}

function show_element(elem_id)
{
	get_element(elem_id).style.display = '';
}

/*Delete all jobs from cookie (My Jobs)*/
function deleteAllJobsFromCookie()
{
	var a = new Array();
	var c = new Date();
	var a = window.document.cookie.split(';');
	var	p = -1;
	for (var i=0; i<a.length; i++)
	{
		p = a[i].indexOf("MyJobs_save[");
		if (p >= 0) {
			var b = a[i].split('=');
			var cookie_name = b[0].replace(/^\s+|\s+$/g, '');
			deleteCookie(cookie_name, "/");
		}
	}
}

/*Job E-mail form*/
var alreadyOpenEmails = new Array();
function is_alreay_open_email(jobkey)
{
	for (i=0; i<alreadyOpenEmails.length; i++)
	{
		if (alreadyOpenEmails[i] == jobkey) return 1;
	}
 return 0;
}
function openJobEmailForm(jobkey,lastemail_from,lastemail_to)
{
if (!is_alreay_open_email(jobkey)) {
	get_element('email_job_'+jobkey).innerHTML = '<div class="tt_display"><a href="javascript: hide_element(\'email_job_'+jobkey+'\')" title="Close" class="closeLink"></a>'+
	'<form method="post" id="email_form_'+jobkey+'" enctype="multipart/form-data" onsubmit="return false";>'+
	'<div class="email_text_content">'+
	'<p style="margin-bottom:1em;font-weight:bold;">Send this job to yourself or a friend:</p>'+
	'<span id="error_msg_'+jobkey+'" style="color:#c00;"></span>'+
	'</div>'+
	'<input type="hidden" name="jobkey" value="'+jobkey+'" />'+
	'<table cellpadding="0" cellspacing="0" class="email_text_content">'+
	'<tr>'+
	'	<td valign="top" style="padding-right:3px;"><label for="email_fa_'+jobkey+'">From my email address</label></td>'+
	'	<td><input type="text" id="email_fa_'+jobkey+'" name="email_from" value="'+lastemail_from+'" style="width:335px;" /></td>'+
	'</tr>'+
	'<tr>'+
	'	<td valign="top"><label for="email_ta_'+jobkey+'">To email address</label></td>'+
	'	<td><input type="text" id="email_ta_'+jobkey+'" name="email_to" value="'+lastemail_to+'" style="width:335px;" /></td>'+
	'</tr>'+
	'<tr>'+
	'	<td valign="top">&nbsp;</td>'+
	'	<td nowrap><input type="checkbox" value="1" id="email_sc_'+jobkey+'" name="email_cc" />'+
	'	<label for="email_sc_'+jobkey+'">Send a copy to my email address</label></td>'+
	'</tr>'+
	'<tr>'+
	'	<td valign="top"><label for="email_comment_'+jobkey+'">Comment (optional)</label></td>'+
	'	<td><textarea id="email_comment_'+jobkey+'" name="comments" style="width:335px;height:70px;"></textarea></td>'+
	'</tr>'+
	'<tr>'+
	'	<td valign="top">&nbsp;</td>'+
	'	<td align="right"><input type="submit" name="email_submit_'+jobkey+'" value="  Send  " onclick="doMailSend(get_element(\'email_form_'+jobkey+'\'),\''+jobkey+'\')" /></td>'+
	'</tr>'+
	'</table>'+
	'</form>'+
	'</div>';
	alreadyOpenEmails[alreadyOpenEmails.length] = jobkey;
}
get_element('email_job_'+jobkey).style.display = '';
hide_element('email_job_send_'+jobkey);
}

function openJobHasBeenSendForm(jobkey,email_to)
{
	get_element('email_job_send_'+jobkey).innerHTML = '<div class="tt_display"><a href="javascript: hide_element(\'email_job_send_'+jobkey+'\')" title="Close" class="closeLink"></a>'+
	'<div class="email_text_content">'+
	'<p style="margin-bottom:1em;">Your email to <b>'+email_to+'</b> has been sent! <a href="javascript: SendAnotherEmail(\''+jobkey+'\')">Send another email</a></p>'+
	'<span id="error_msg_'+jobkey+'" style="color:#c00;"></span>'+
	'</div>'+
	'</div>';
	hide_element('email_job_'+jobkey);
	show_element('email_job_send_'+jobkey);
}

function SendAnotherEmail(jobkey)
{
	hide_element('email_job_send_'+jobkey);
	get_element('email_ta_'+jobkey).value = '';
	show_element('email_job_'+jobkey);
}