{*[start_template_header_part]*}
{include file="main_header.tpl"}
{*[end_template_header_part]*}
{*[start_template_content_part]*}
<table border="0" width="100%">
    <!-- Header -->
    <tbody>
        <tr>
            <td width="10%">&nbsp;</td>
            <td width="80%">
            <table width="100%">
                <tbody>
                    <tr>
                        <td width="216">&nbsp;</td>
                        <td align="right" valign="top">{$JobsInCountry}<br />
                        <a class="toplinks" href="http://localhost/cjobs/myjobs/">My Jobs</a> - <a class="toplinks" href="http://localhost/cjobs/myarea/">My Area</a></td>
                    </tr>
                </tbody>
            </table>
            </td>
            <td width="10%">&nbsp;</td>
        </tr>
        <!-- Search boxes -->
        <tr>
            <td width="10%">&nbsp;</td>
            <td align="center" width="80%" style="background-color: rgb(230, 230, 230); padding-top: 10px; padding-bottom: 10px;">
            <table width="100%">
                <tbody>
                    <tr>
                        <td width="163" valign="top"><a href="http://localhost/cjobs/"><img height="75" width="163" src="http://localhost/cjobs/frontend/images/es_job_search_engine_small1.gif" alt="ES Job Search Engine" title="ES Job Search Engine" style="border: 0px none;" /></a></td>
                        <td>
                        <form action="http://localhost/cjobs/jobs/" method="GET">
                            <input type="hidden" name="{$SNAME}" value="{$SID}" class="input" style="width: 96px;" />
                            <table>
                                <tbody>
                                    <tr>
                                        <td valign="bottom"><span style="color: rgb(114, 0, 0); font-weight: bold; font-size: 95%;"><label for="what">What</label></span><br />
                                        <span style="font-size: 70%;">Company, Keyword</span><br />
                                        <input type="text" id="what" name="what" style="width: 280px; height: 18px; background-color: rgb(249, 249, 249); border: 1px solid rgb(153, 153, 153);" value="{$input_value_what}" /></td>
                                        <td valign="bottom"><span style="color: rgb(114, 0, 0); font-weight: bold; font-size: 95%;"><label for="where">Where</label></span><br />
                                        <span style="font-size: 70%;">City, State or Zip</span><br />
                                        <input type="text" id="where" name="where" style="width: 280px; height: 18px; background-color: rgb(249, 249, 249); border: 1px solid rgb(153, 153, 153);" value="{$input_value_where}" /></td>
                                        <td nowrap="" valign="bottom"><input type="submit" value="Search Jobs " class="searchButton" />             <a href="http://localhost/cjobs/advanced_search.php" class="ajs">Advanced Job Search</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                        </td>
                    </tr>
                </tbody>
            </table>
            </td>
            <td width="10%">&nbsp;</td>
        </tr>
        <!-- Data list -->
        <tr>
            <td width="10%">&nbsp;</td>
            <td width="80%" style="line-height: 105%; padding-top: 10px; background-color: rgb(248, 248, 248);"><!-- 3 BLOCKS-->
            <table width="100%">
                <tbody>
                    <tr>
                        <td id="center_side" style="vertical-align: top;">
                        <div>{include file="onejob_item.tpl"}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- 3 BLOCKS--></td>
            <td width="10%">&nbsp;</td>
        </tr>
        <!-- Footer links -->
        <tr>
            <td width="10%">&nbsp;</td>
            <td align="center" width="80%">
            <div style="clear: both; margin: 5px 10px; border-top: 1px solid rgb(204, 204, 204); color: rgb(51, 51, 51);">&nbsp;</div>
            <table width="60%">
                <tbody>
                    <tr>
                        <td><a class="footer" href="http://localhost/cjobs/">Home Page</a></td>
                        <td><a class="footer" href="http://localhost/cjobs/browse_jobs/browse_types/">Browse Jobs</a></td>
                        <td><a class="footer" href="http://localhost/cjobs/advertisers/">Advertisers</a></td>
                        <td><a class="footer" href="http://localhost/cjobs/publishers/">Publishers</a></td>
                    </tr>
                </tbody>
            </table>
            </td>
            <td width="10%">&nbsp;</td>
        </tr>
        <!-- Footer text -->
        <tr>
            <td width="10%">&nbsp;</td>
            <td align="center" width="80%" style="padding-top: 25px;"><span style="font-size: 11px; color: rgb(112, 0, 0);">       Copyright &copy; 2011 <a href="http://www.es-job-search-engine.com" class="bottominfo_link" title="Job Search Engine script. Read more information">ES Job Search Engine</a>. All rights reserved.<br />
            Powered by <a href="http://www.energyscripts.com" class="bottominfo_link" title="EnergyScripts: web development company">EnergyScripts</a>.     </span></td>
            <td width="10%">&nbsp;</td>
        </tr>
    </tbody>
</table>
{*[end_template_content_part]*}
{*[start_template_footer_part]*}
{include file="main_footer.tpl"}
{*[end_template_footer_part]*}