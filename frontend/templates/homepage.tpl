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
      <td width="216">
        <a href="http://localhost/cjobs/"><img src="http://localhost/cjobs/frontend/images/es_job_search_engine.gif" alt="ES Job Search Engine" title="ES Job Search Engine" width="216" height="100" border="0" /></a>
      </td>
      <td valign="top" align="right">
        {$JobsInCountry}<br />
        <a class="toplinks" href="http://localhost/cjobs/myjobs/">My Jobs</a> - <a class="toplinks" href="http://localhost/cjobs/myarea/">My Area</a><br />
        <div class="across_web_div">Search Jobs Across the Web</div>
      </td>
    </tr>
    </table>
  </td>
  <td width="10%">&nbsp;</td>
</tr>

<!-- Search boxes -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%" class="sb_td" align="center">
    <form action="http://localhost/cjobs/jobs/" method="GET">
    <input type="hidden" name="{$SNAME}" value="{$SID}" class="input width96" />
    <table>
    <tr>
      <td valign="bottom">
        <span class="before_input_text"><label for="what">What</label></span><br />
        <span class="font_size70">Company, Keyword</span><br />
        <input type="text" id="what" name="what" class="main_input" />
      </td>
      <td valign="bottom">
        <span class="before_input_text"><label for="where">Where</label></span><br />
        <span class="font_size70">City, State or Zip</span><br />
        <input type="text" id="where" name="where" class="main_input" />
      </td>
      <td valign="bottom" nowrap>
 	      <input type="submit" value="Search Jobs " class="searchButton">
        <a href="http://localhost/cjobs/advanced_search.php" class="ajs">Advanced Job Search</a>
      </td>
    </tr>
    </table>
    </form>
  </td>
  <td width="10%">&nbsp;</td>
</tr>

<!-- Categories list -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%" class="cat_list">

    {assign var="rows" value="9"}
    {assign var="cur_row" value="0"}
    <table width="100%">
    <tr>
      {foreach from=$JobCategoriesArrayList key=key item=item name=catitems}
        {if ($cur_row == 0)}
          <td valign="top" width="{math equation="ceil(x/y*z)" x=100 y=$smarty.foreach.catitems.total z=$rows}%">
        {/if}
						{assign var="cur_row" value=`$cur_row+1`}
            <a href="http://localhost/cjobs/category/{$item.cat_key}/" class="catlinks">{$item.cat_name}</a><br />
        {if ($cur_row == $rows)}
          </td>
					{assign var="cur_row" value="0"}
        {/if}
      {/foreach}
      {if (($smarty.foreach.catitems.index > 0) && (!$smarty.foreach.catitems.index is div by $rows))}
          </td>
      {/if}
    </tr>
    </table>

  </td>
  <td width="10%">&nbsp;</td>
</tr>

<!-- Footer links -->
<tr>
  <td width="10%">&nbsp;</td>
  <td width="80%" align="center">
    <div class="clear_div"></div>
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
  <td width="80%" align="center" class="pad_top25">
    <span class="footer_span">
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