<table class="TopSpan">
<tr align="center">
  <td><b>XML Feed</b></td>
</tr>
<tr>
  <td>
To retrieve search results from our affiliate XML Feed you will need to issue an HTTP Get request as described below.
They are returned in an XML format. Please email any technical questions to {*webmaster_email_html*}.
The URL for the request is:
  </td>
</tr>
<tr>
  <td>
<br />
<pre>
{*xmlsearch_url*}?keywords=<b>keyword</b>&pageresult=<b>number_of_show_links</b>&<br />startat=<b>number_of_links_with_show</b>&adultfilter=<b>set_adult_filter</b>&uid=<b>uid</b>&ip_address=<b>ip_address</b>
</pre>
  </td>
</tr>
<tr>
  <td>
  The parameters are:
  <ul style="margin: 15px;">
    <li><b>keywords</b> - The search term that is being searched for. Please separate multi-worded search terms with a "+". <font color="red">This is a required parameter</font>.</li>
    <li><b>pageresult</b> - Use this parameter to specify the number of results to return. By default, the search returns {*pageresult_default*} results.</li>
    <li><b>startat</b> - Use this parameter to specify the number of missing links in results to return. By default, the startat = {*startat_default*}.</li>
    <li><b>adultfilter</b> - Use this parameter to turn on/off adult filter, on = 1, off = 0. By default, the adultfilter = {*adultfilter_default*}.</li>
    <li><b>uid</b> - This parameter allows you to earn money when people will click the bidded url. Your uid = {*uid*}. <font color="red">This is a required parameter</font>.</li>
    <li><b>ip_address</b> - Use this parameter to specify the IP address of the actual user performing the search.</li>
  </ul>
  </td>
</tr>
<tr>
  <td>
<b>Examples:</b><br />
You wants to obtain 10 results for the search term "web design"
<pre>
{*xmlsearch_url*}?keywords=<b>web+design</b>&pageresult=<b>10</b>&<br />uid=<b>{*uid*}</b>&ip_address=<b>123.143.43.13</b>
</pre>
<b>Search Results Format:</b><br />
    <textarea readonly rows="19" cols="90">
&lt;?xml version="1.0" encoding="ISO-8859-1" ?&gt;
&lt;XMLSearchResult&gt;
&lt;Service&gt;{*site_title*} xml feed version 1.0&lt;/Service&gt;
&lt;RecordTotal&gt;1&lt;/RecordTotal&gt; 
&lt;Keyword&gt;search+engines&lt;/Keyword&gt; 
&lt;Country&gt;Ireland (IE)&lt;/Country&gt; 
&lt;IP&gt;POST_IP: 123.143.43.13, REMOTE_IP 213.49.59.123&lt;/IP&gt; 
&lt;response&gt;
&lt;record&gt;
&lt;url&gt;http://www.myengine.com&lt;/url&gt;
&lt;source&gt;MyEngine.com&lt;/source&gt;
&lt;title&gt;MyEngine PPC&lt;/title&gt;
&lt;description&gt;MyEngine - Pay Per Click Search Engines developing&lt;/description&gt;
&lt;bid&gt;0.03000&lt;/bid&gt;
&lt;/record&gt;
&lt;/response&gt;
&lt;/XMLSearchResult&gt;
    </textarea>
  </td>
</tr>
</table>