<?
//File page with content
$filename = 'www.careerbuilder.com.txt';

$f = fopen($filename,'r');
$content = fread($f, filesize($filename));
fclose($f);

/*
$maigregexpr = '~<TR>\s*?<TD[^<]+?><FONT[^<]+?>[^<]+?</FONT>\s*?<TD[^<]+?VALIGN=MIDDLE[^<]+?><FONT.+?>(<B>)*?<A HREF=\"(.+?)\">(.+?)</A>\s*?</FONT>.*?<TD.+?><FONT.+?>(.+?)-(.+?)-(.+?)</FONT>.*?<TD.+?><FONT.+?><A HREF=.*?>(.+?)</A>.*?<TD.+?><FONT.+?>(.*?)</FONT>~si';
preg_match_all($maigregexpr, $content, $matches);
*/
/*
$nextpgregexpr = '~<form method="post" action="([^\"]+?)"[^>]+?>.*?<input type="hidden" name="offset" value="(.+?)"[^>]+?/>~si';
preg_match_all($nextpgregexpr, $content, $matches);
*/

/*
$desregexpr1 = '~Click Here to Apply Online</a>.+?<P>(.+?)<P>~si';
preg_match_all($desregexpr1, $content, $matches);
*/

/*
$desregexpr2 = '~Resume Guide</a>(.+)~si';
preg_match_all($desregexpr2, $content, $matches);
*/

/*
if ( preg_match('~<span class=\'nav_arrows\'><a href=\'([^>]+?)\' rel=\'nofollow\'>Next &gt;&gt;</a></span>~si', $content, $matches) ) {
//  if (isset($pgmatches[1])) $result = 'http://www.jobbind.com'.$pgmatches[1];
}
*/

/*$maigregexpr = '~<tr.+?<td><a.+?class="jt"\s*href="([^"]+?)">(.+?)</a>.*?<div[^<]+?>\s*?Job type:([\s\w-]+?)([|\s]*?Pay:(.+?))*?<br />(.+?)<br />.+?<strong>(.+?)</strong>\s*?</td>.+?<td[^<]+?>.*?(\w{2})\s+?-\s+?([\w\s]+)[^<]*?</td>.*?</tr>~si';*/
/*
$maigregexpr = '~<tr.+?<td><a.+?class="jt"\s*href="([^"]+?)">(.+?)</a>.*?<div[^<]+?>\s*?Job type:([\s\w-]+?)([|\s]*?Pay:(.+?))*?<br />(.+?)<br />.+?<strong>(.+?)</strong>\s*?</td>.+?<td[^<]+?>.*?(\w{2})\s+?-\s+?([\w\s]+)[^<]*?</td>.*?</tr>~si';
preg_match_all($maigregexpr, $content, $matches);
*/

/*
$maigregexpr = '~<div class=\'listing.+?<a id="ctl00_ctl00_ctl00_body_body_wacCenterStage_.+?href="(.+?)">([^>]+?)</a>.+?<span class="company">(.+?)</span>\s*<span class="jobplace">\- <a[^>]+?>(.+?)</a></span>(.+?)</p>\s*<p class="jobDesc">(.+?)</p>~si';
preg_match_all($maigregexpr, $content, $matches);
*/

/*
$result = '';
$citystate = explode(',','{*MainRegExpr[4][?]*}');
if (isset($citystate[0]) && isset($citystate[1]) (trim($citystate[0]) != '') && (trim($citystate[1]) != '')) $result = trim($citystate[0].','.$citystate[1]);
*/
/*
$desregexpr1 = '~<span class=\'Salary\'>- (.+?)</span>~si';
$result = "";
$salregexpr = '~<span class=\'Salary\'>- (.+?)</span>~si';
if ( preg_match($salregexpr, $content, $matches[5][1]) ) {
  if (isset($matches[1]) && ($matches[1] != '')) $result = $matches[1];
}
*/

/*
$salregexpr = '~<span class=\'Salary\'>- (.+?)</span>~si';
if ( preg_match($salregexpr, $content, '
                    <span class=\"postingdate\">- Posted today</span>
                    <span class=\'Salary\'>- $70,000.00 - $80,000.00 Per Year</span>
                ') ) {
  if (isset($matches[1]) && ($matches[1] != '')) $result = $matches[1];
}

*/

/*
$Result['html_rawdata'] = $content;

$result = "";
if ( preg_match('~<a href=\'([^\'>]+?)\'\s*rel=\'Next\'> Next </a>~si', $Result['html_rawdata'], $pgmatches) ) {
  if (isset($pgmatches[1])) $result = $pgmatches[1];
}
echo ">>".$result."<<";
*/

/*
$maigregexpr = '~<div class="JobRow">\s*<h3><a[^>]+?href="([^>]+?)">(.+?)</a></h3>\s*<span[^>]+?>(.+?)</span>[^<]+?<span[^>]+?>(.+?)</span>.+?<br />(.+?)<span.+?</div>~si';
preg_match_all($maigregexpr, $content, $matches);
*/

/*
$maigregexpr = '~<a href="([^\"]+?)" id="jobListLink" rel="nofollow">>> More jobs in that category >></a>~si';
preg_match_all($maigregexpr, $content, $matches);


$maigregexpr = '~<a href="([^\"]+?)">Next</a>~si';
preg_match_all($maigregexpr, $content, $matches);

$Result['html_rawdata'] = $content;




$nextpgregexpr1 = '~<a href="([^\"]+?)" id="jobListLink" rel="nofollow">>> More jobs in that category >></a>~si';
$nextpgregexpr2 = '~<a href="([^\"]+?)">Next</a>~si';

$result = "";
if ( preg_match($nextpgregexpr1, $Result['html_rawdata'], $pgmatches) ) {
  if (isset($pgmatches[1])) $result = 'http://www.postjobfree.com'.$pgmatches[1];
}
elseif ( preg_match($nextpgregexpr2, $Result['html_rawdata'], $pgmatches) ) {
  if (isset($pgmatches[1])) $result = 'http://www.postjobfree.com/JobList.aspx'.$pgmatches[1];
}

echo $result;
*/


/*
$result = "";
if ( preg_match('~<a href="([^>]+?)"[^>]+?><span class=pn><span class=np>Next&nbsp;&raquo;</span></span></a>~si', $Result['html_rawdata'], $pgmatches) ) {
  if (isset($pgmatches[1])) $result = 'http://www.indeed.com'.$pgmatches[1];
}
*/


/*
$maigregexpr = '~<div><a href="(/jobsearch[^\"]+?)">(.+?)</a></div>\s*</td>\s*<td>(.+?)</td>\s*<td>(.+?)</td>~si';
preg_match_all($maigregexpr, $content, $matches);
*/

/*
$maigregexpr = '~<a class="nextPage" href="(/jobsearch[^\"]+?)"[^>]+?>Next</a>~si';
preg_match_all($maigregexpr, $content, $matches);
*/



//$maigregexpr = "~<tr>\s*<td class='JobColumn'>\s*<span class='JobColumnTitleLink'><a href='([^\']+?)'>([^<]+?)</a></span>.+?(<span class='JobColumnCompany'>([^<]+?)</span> - )*<span class='JobColumnLocation'>(.+?),([^<]+?)</span>(.+?)<span class='JobColumnDateCategory'>.+?</tr>~si";



/*
$maigregexpr = "~<tr>\s*<td class='JobColumn'>\s*<span class='JobColumnTitleLink'><a href='([^\']+?)'>([^<]+?)</a></span>.+?(<span class='JobColumnCompany'>([^<]+?)</span> - )*<span class='JobColumnLocation'>([^<]+?)</span>(.+?)<span class='JobColumnDateCategory'>.+?</tr>~si";
*/
/*
$maigregexpr = "~<BR>Position Type:\s+?(.+?)[\s\n]+?~si";
*/
/*
$maigregexpr = '~<a href="([^\"]+?)">Next &gt;</a>~si';
*/

//$maigregexpr = '~<span class="job_list_title" >.*?<a href="([^\"]+?)" target="_blank">(.+?)</a>.+?<span class="dullbluetext">(.+?)</span>\s*\|\s*<b>(.+?)</b>.+?<span class="job_list_small_print">(.+?)</span>~si';

/*
$Result['html_rawdata'] = $content;


$nextpgregexpr1 = '~<a href="([^\"]+?)">Next 10 Results >> </a>~si';
$nextpgregexpr2 = '~<span style="color: #ee6e0d">.+?<a class="testbluelink" href="([^\"]+?)">\d+</a>~si';

$result = "";
if ( preg_match($nextpgregexpr1, $Result['html_rawdata'], $pgmatches) ) {
  if (isset($pgmatches[1])) $result = $pgmatches[1];
}
elseif ( preg_match($nextpgregexpr2, $Result['html_rawdata'], $pgmatches) ) {
  if (isset($pgmatches[1])) $result = $pgmatches[1];
}

echo $result;
exit;

preg_match_all($maigregexpr, $content, $matches);
*/

/*
$maigregexpr = '~<li class=\"[^\"]*"><a href="([^"]+?)">(.+?)</a><p><strong><span>(.+?)</span><span> \|(.+?)</span><br />.+?</p><p><span>(.+?)</span></p>~si';
preg_match_all($maigregexpr, $content, $matches);
*/

/*
if ( preg_match_all('~<h2.+?jobtitle><a[^>]+?href="(.+?)"[^>]+?>(.+?)</a>.*?<span class=company>(.+?)</span>.+?<span class="location">(.+?),(.+?)</span>.*?<span class=summary>(.+?)</span>~si', $content, $matches) ) {
	print_R($matches);
}
*/
/*
    <div class="row " id="p_118915d0e8405308">
    
        
        <h2 id=jl_118915d0e8405308 class=jobtitle><a rel="nofollow"   href="/rc/clk?jk=118915d0e8405308" target="_blank" 
onmousedown="return rclk(this,jobmap[0]);" onclick="logFocus(); return rclk(this,jobmap[0],true);" title="Manager, Finance"><b>Manager</b>, <b>Finance</b></a>
    

        - <span class=new>new</span>
        
        
        
    </h2>

<span class=company>Qualcomm</span> -
     <span class="location">San Diego, CA</span>
        
        <table cellpadding=0 cellspacing=0 border=0><tr><td class=snip>
        
        <div><span class=summary>Qualcomm is hiring a <b>Finance</b> <b>Manager</b> to support our global business operations FP&amp;A group. 
This is a... MBA or Masters Degree in <b>Finance</b> or Accounting</span></div>

        <span class=source>Qualcomm</span>&nbsp;-&nbsp;<span class=date>10 days ago</span>
        <span id="tt_set_0" class=tt_set>
            

- <span id="savelink2_118915d0e8405308">
    
    
        
        
        <a id="sj_118915d0e8405308" href="#" class=sl onclick="anonSaveJobLabel(this, jobmap[0]); toggleResultTab(this, 'anon_save_job0', 0);return false;" title="Save this job to my.indeed">save job</a> -
    
</span>



<span id="addedit2_118915d0e8405308"></span>




<div id="editsaved2_118915d0e8405308" class="edit_note_content" style="display:none;"></div> 



    <a href="#block_job0" class="sl" onclick="blockJobsLabel( this, jobmap[0], false ); toggleResultTab( this, 'block_job0', 0 ); return false;">block</a> - 
    
    <a href="mailto:?subject=I found this job on Indeed.com&body=http://www.indeed.com/viewjob?jk=118915d0e8405308" class="sl" onclick="if ( !hasAjax() ) { return; } toggle_email_job_content( this, 0, { 'a': 'tellafriend', 'tk' : '16c206qqt0k12100', jobkey: '118915d0e8405308', q: 'Finance Manager', permalink: 'http:\/\/www.indeed.com\/viewjob?jk=118915d0e8405308' }, {fbRef: 'search'} ); toggleResultTab(this, 'email_job_content_0', 0); return false;">email</a> -
    <a href="#" id="tog_0" class=sl onmousedown="if ( window.rpc ) { rpc('/rpc/log?a=opma&tk=16c206qqt0k12100&p=0&ts=1318622292827'); this.onmousedown=null; }" onclick="toggle(jobmap[0]); toggleResultTab(this, 'more_0', 0);return false;">more...</a>
</span>

<div id="tt_display_0" class=tt_display style="display:none;">
*/

$maigregexpr = '~<tr.+?<td><a.+?class="jt.+?href="([^"]+?)">(.+?)</a>.*?<div[^<]+?>\s*?Job type:([\s\w-]+?)([|\s]*?Pay:(.+?))*?<br />(.+?)<br />.+?<strong>(.+?)</strong>\s*?</td>.+?<td[^<]+?>.*?(\w{2})\s+?-\s+?([\w\s]+)[^<]*?</td>.*?</tr>~si';
preg_match_all($maigregexpr, $content, $matches);
print_r($matches);

?>