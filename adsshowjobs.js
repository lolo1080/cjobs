function chval(val)
{
	if (val != null) return '"'+val+'"';
	else return '""';
}

function write_jobroll_html()
{
	var jobroll_width = '120';
	var jobroll_height = '600';
    
	if (window.jobroll_format == '728x90' ) { jobroll_width = '728'; jobroll_height = '90'; }
	if (window.jobroll_format == '120x600' ) { jobroll_width = '120'; jobroll_height = '600'; }
	if (window.jobroll_format == '160x600' ) { jobroll_width = '160'; jobroll_height = '600'; }
	if (window.jobroll_format == '300x250' ) { jobroll_width = '300'; jobroll_height = '250'; }

  document.write('<ifr' + 'ame' +
		' name="jobroll_frame"' +
		' width=' + jobroll_width +
		' height=' + jobroll_height +
		' frameborder=' + 0 +
		' src=' + chval(jobroll_params_list) +
		' marginwidth="0"' +
		' marginheight="0"' +
		' vspace="0"' +
		' hspace="0"' +
		' allowtransparency="true"' +
		' scrolling="no"></ifr' + 'ame>'
	);
}

write_jobroll_html();