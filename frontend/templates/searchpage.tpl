{*[start_template_header_part]*}
{include file="main_header.tpl"}
{*[end_template_header_part]*}
{*[start_template_content_part]*}

<table width="100%" border="0">
<!-- Header -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%">
    <table width="100%">
    <tr>
      <td width="216">&nbsp;</td>
      <td valign="top" align="right">
        {$JobsInCountry}<br />
        <a class="toplinks" href="http://localhost/cjobs/myjobs/">My Jobs</a> - <a class="toplinks" href="http://localhost/cjobs/myarea/">My Area</a><br />
      </td>
    </tr>
    </table>
  </td>
  <td width="10%">&nbsp;</td>
</tr>

<!-- Search boxes -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%" style="background-color:#e6e6e6;padding-top:10px;padding-bottom:10px;" align="center">
    <table width="100%">
    <tr>
      <td width="163" valign="top">
        <a href="http://localhost/cjobs/"><img src="http://localhost/cjobs/frontend/images/es_job_search_engine_small1.gif" alt="ES Job Search Engine" title="ES Job Search Engine" width="163" height="75" style="border:0px;" /></a>
      </td>
      <td>
        <form action="http://localhost/cjobs/jobs/" method="GET">
        <input type="hidden" name="{$SNAME}" value="{$SID}" class="input" style="width:96px;" />
        <table>
        <tr>
          <td valign="bottom">
            <span style="color:#720000;font-weight:bold;font-size:95%;"><label for="what">What</label></span><br />
            <span style="font-size:70%;">Company, Keyword</span><br />
            <input type="text" id="what" name="what" style="width: 280px; height:18px; background-color: #f9f9f9; border: 1px solid #999999;" value="{$input_value_what}" />
          </td>
          <td valign="bottom">
            <span style="color:#720000;font-weight:bold;font-size:95%;"><label for="where">Where</label></span><br />
            <span style="font-size:70%;">City, State or Zip</span><br />
            <input type="text" id="where" name="where" style="width: 280px; height:18px; background-color: #f9f9f9; border: 1px solid #999999;" value="{$input_value_where}" />
          </td>
          <td valign="bottom" nowrap>
     	      <input type="submit" value="Search Jobs " class="searchButton">
            <a href="http://localhost/cjobs/advanced_search.php" class="ajs">Advanced Job Search</a>
          </td>
        </tr>
        </table>
        </form>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="left">
       <div style="float:left; width:750px; font-size:90%; padding-left:5px;">
           {$ShowSearchResultStats}<br />
           <div style="padding-top:5px;">{$ShowCategoriesLinks}</div>
       </div>
       <div style="float:right; width:200px; font-size:90%; padding-right:5px;">
           {$ShowSearchType}<br />
           <div style="padding-top:5px;">{$ShowSearchOrder}</div>
       </div>
      </td>
    </tr>
    </table>
  </td>
  <td width="10%">&nbsp;</td>
</tr>

<!-- Data list -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%" style="line-height:105%;padding-top:10px;background-color:#f8f8f8;">

    <!-- 3 BLOCKS-->
    <table width="100%">
    <tr>
      <!-- Left side -->
      <td id="left_side" style="width:150px;vertical-align:top;">
        <div><span style="color: #F60; font-size:larger; font-weight:bolder;">&laquo;</span><a class="simple_link" href="javascript:show_hide_leftside(1)">Hide Refinements</a></div>
        <div>{include file="filter_column.tpl"}</div>
      </td>
      <!-- Center side -->
      <td id="center_side" style="vertical-align:top;">
        <div id="refine_your_search_space" style="font-size:larger; font-weight:bolder;">&nbsp;</div>
        <div id="refine_your_search" style="display: none;"><span style="color: #F60; font-size:larger; font-weight: bolder;">&raquo;</span><a class="simple_link" href="javascript:show_hide_leftside(2)">Refine your search</a></div>
        <div>{include file="searchresult_item.tpl"}</div>
        <p>
          <div style="font-size:90%; padding-left:10px;">{$ShowJobsPerPage}</div>
          <div style="font-size:90%; text-align:center; padding-top:10px;">{include file="navigation.tpl"}</div>
        </p>
      </td>
      <!-- Right side -->
      <td style="width:170px;vertical-align:top;">
        <div>{include file="ads_column.tpl"}</div>
      </td>
    </tr>
    </table>
    <!-- 3 BLOCKS-->

  </td>
  <td width="10%">&nbsp;</td>
</tr>

<!-- Footer links -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%" align="center">
    <div style="clear: both;margin: 5px 10px; border-top: 1px solid #ccc; color: #333;"></div>
    <table width="60%">
    <tr>
      <td><a class="footer" href="http://localhost/cjobs/">Home Page</a></td>
      <td><a class="footer" href="http://localhost/cjobs/browse_jobs/browse_types/">Browse Jobs</a></td>
      <td><a class="footer" href="http://localhost/cjobs/advertisers/">Advertisers</a></td>
      <td><a class="footer" href="http://localhost/cjobs/publishers/">Publishers</a></td>
    </tr>
    </table>
  </td>
  <td width="10%">&nbsp;</td>
</tr>

<!-- Footer text -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%" align="center" style="padding-top:25px;">
    <span style="font-size:11px;color:#700000;">
      Copyright &copy; 2011 <a href="http://www.es-job-search-engine.com" class="bottominfo_link" title="Job Search Engine script. Read more information">ES Job Search Engine</a>. All rights reserved.<br />
      Powered by <a href="http://www.energyscripts.com" class="bottominfo_link" title="EnergyScripts: web development company">EnergyScripts</a>.
    </span>
  </td>
  <td width="10%">&nbsp;</td>
</tr>
</table>


{*[end_template_content_part]*}
{*[start_template_footer_part]*}
{include file="main_footer.tpl"}
{*[end_template_footer_part]*}