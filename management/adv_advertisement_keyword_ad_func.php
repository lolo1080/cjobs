<?
function create_values($ad_id,$ad_name,$headline,$line_1,$line_2,$display_url,$destination_url,$max_cpc,$daily_budget,
	$monthly_budget,$status,$keyword,$was_error=0)
{
 global $smarty,$text_info,$usersettings,$active_disable_array,$SLINE,$my_error;
	$ad_status_selectbox	= get_selectbox_data($active_disable_array,$status);
	$example_table = '
		<tr>
			<td colspan="5" align="center">
				<table cellspacing="1" cellpadding="0" border="0" style="padding:5px; font-family: Georgia, \'Times New Roman\', Times, serif; font-size: 13px; border-style: solid; border-width: 1px; border-color: rgb(0,0,0);">
					<tr><td id="headline_example"><a href="" class="title" target="_blank">Land your dream job</a></td><tr>
					<tr><td id="line_1_example">Professional resume writer</td><tr>
					<tr><td id="line_2_example">Get the perfect resume now</td><tr>
					<tr><td id="display_url_example"><a href="" class="searchlink" target="_blank">www.example.com</a></td><tr>
				</table>
			</td>
		</tr>
	';
	if ($was_error) $additional_script = "<script language=\"JavaScript\"><!--\n fill_td('headline','headline_example','<a href=\'\' class=\'title\' target=\'_blank\'>','</a>&nbsp;'); fill_td('line_1','line_1_example','','&nbsp;'); fill_td('line_2','line_2_example','','&nbsp;'); fill_td('display_url','display_url_example','<a href=\'\' class=\'searchlink\' target=\'_blank\'>','</a>&nbsp;'); \n--></script>";
	else $additional_script = '';
	$FormElements = array(
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_keyword_ads_example"], "after_html"=>$example_table),
	array("isheadline"=>true, "hlclass"=>"form_hlclass", "hlmessage"=>$text_info["p_keyword_ads_data"], "after_html"=>""),
	array("flabel"=>show_cell_caption("ad_name"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"ad_name", "ereadonly"=>"", "evalue"=>$ad_name, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),

	array("flabel"=>str_replace("{*MaxNum*}",$_SESSION["globsettings"]["max_adv_headline_length"],show_cell_caption("headline")), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"headline", "ereadonly"=>"", "evalue"=>$headline, "emaxlength"=>$_SESSION["globsettings"]["max_adv_headline_length"],
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>"onkeyup=\"fill_td('headline','headline_example','<a href=\'\' class=\'title\' target=\'_blank\'>','</a>&nbsp;')\""),

	array("flabel"=>str_replace("{*MaxNum*}",$_SESSION["globsettings"]["max_adv_line1_length"],show_cell_caption("line_1")), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"line_1", "ereadonly"=>"", "evalue"=>$line_1, "emaxlength"=>$_SESSION["globsettings"]["max_adv_line1_length"],
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>"onkeyup=\"fill_td('line_1','line_1_example','','&nbsp;')\""),

	array("flabel"=>str_replace("{*MaxNum*}",$_SESSION["globsettings"]["max_adv_line2_length"],show_cell_caption("line_2")), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"line_2", "ereadonly"=>"", "evalue"=>$line_2, "emaxlength"=>$_SESSION["globsettings"]["max_adv_line2_length"],
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>"onkeyup=\"fill_td('line_2','line_2_example','','&nbsp;')\""),

	array("flabel"=>show_cell_caption("display_url"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"display_url", "ereadonly"=>"", "evalue"=>$display_url, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>"onkeyup=\"fill_td('display_url','display_url_example','<a href=\'\' class=\'searchlink\' target=\'_blank\'>','</a>&nbsp;')\""),

	array("flabel"=>show_cell_caption("destination_url"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"destination_url", "ereadonly"=>"", "evalue"=>$destination_url, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>str_replace("{*MinCPC*}",$_SESSION["globsettings"]["min_adv_cost_per_click"],show_cell_caption("max_cpc")), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"max_cpc", "ereadonly"=>"", "evalue"=>$max_cpc, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("daily_budget"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"daily_budget", "ereadonly"=>"", "evalue"=>$daily_budget, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("monthly_budget"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"monthly_budget", "ereadonly"=>"", "evalue"=>$monthly_budget, "emaxlength"=>"100",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("keywords_list"), "before_html"=>"", "after_html"=>"", "etype"=>"textarea",
				"ename"=>"keyword", "ereadonly"=>"", "evalue"=>$keyword,
				"estyle"=>"width: 300px; height: 150px", "isheadline"=>false),
	array("flabel"=>show_cell_caption("ad_status"), "before_html"=>"", "after_html"=>"", "etype"=>"select",
				"ename"=>"status", "edisabled"=>"", "evalue"=>$ad_status_selectbox["val"],
				"eselected"=>$ad_status_selectbox["sel"], "ecaption"=>$ad_status_selectbox["capt"],
				"jscipt"=>"", "multiple"=>"", "estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>"")
	);
	$smarty->assign("FormElements",$FormElements);
	$smarty->assign("additional_script",$additional_script);
}

function update_ad_info($ad_id,$ad_name,$headline,$line_1,$line_2,$display_url,$destination_url,$max_cpc,$daily_budget,
		$monthly_budget,$status,$keywords_list)
{
 global $db_tables, $SLINE;
	//обновляем статус сразу в двух таблицах, вторая нужна для скорости поиска
	//Update Ad info in Ads tabe
	mysql_query("UPDATE ".$db_tables["ads"]." SET ad_name='$ad_name',headline='$headline',line_1='$line_1',line_2='$line_2',".
				"display_url='$display_url',destination_url='$destination_url',max_cpc='$max_cpc',daily_budget='$daily_budget',".
				"monthly_budget='$monthly_budget',status='$status'".
				" WHERE ad_id='$ad_id'") or query_die(__FILE__,__LINE__,mysql_error());
	//Send event
	$event_array = array("event"=>"update", "source"=>"adv_advertisement_keyword_ad", "table"=>"ads", "ad_id"=>$ad_id);
	event_handler($event_array);
	//Update keywords in Keywords table
		//1) get keywords from table
	$qr_res = mysql_query("SELECT kads_id,soptions,keyword FROM ".$db_tables["keyword_ads"]." WHERE ad_id='$ad_id'") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$keywords_list_from_table[] = array("kads_id"=>$myrow["kads_id"], "soptions"=>$myrow["soptions"], "keyword"=>$myrow["keyword"]);
	}

		//2) compare keywords list from table with new keywords list
	$keywords_list_simple = array();
	for ($i=0; $i<count($keywords_list); $i++)
	{
		$keywords_list_simple[] = $keywords_list[$i]["keyword"];
		$isfind = false;
		for ($j=0; $j<count($keywords_list_from_table); $j++)
		{
			// old keyword and search otions == new keyword and search otions
			if ( ($keywords_list[$i]["keyword"] == $keywords_list_from_table[$j]["keyword"]) &&
					 ($keywords_list[$i]["soptions"] == $keywords_list_from_table[$j]["soptions"]) ) {
				$isfind = true;
				continue;
			}

			// old keyword == new keyword, but old search otions != new search otions
			if ( ($keywords_list[$i]["keyword"] == $keywords_list_from_table[$j]["keyword"]) &&
					 ($keywords_list[$i]["soptions"] != $keywords_list_from_table[$j]["soptions"]) ) {
				mysql_query("UPDATE ".$db_tables["keyword_ads"]." SET soptions='{$keywords_list[$i]["soptions"]}' WHERE kads_id='{$keywords_list_from_table[$j]["kads_id"]}'") or query_die(__FILE__,__LINE__,mysql_error());
				$isfind = true;
				continue;
			}
		}
		// no old keyword - add new keyword
		if (!$isfind) {
			mysql_query("INSERT INTO ".$db_tables["keyword_ads"]." VALUES(NULL,'$ad_id','$status','$status',".
				"'{$keywords_list[$i]["soptions"]}','{$keywords_list[$i]["keyword"]}')") or query_die(__FILE__,__LINE__,mysql_error());
		}
	}

		// 3) delete old keywords from keywords table
	$qr_res = mysql_query("SELECT kads_id,keyword FROM ".$db_tables["keyword_ads"]." WHERE ad_id='$ad_id'") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		if (!in_array($myrow["keyword"], $keywords_list_simple)) mysql_query("DELETE FROM ".$db_tables["keyword_ads"]." WHERE kads_id='{$myrow["kads_id"]}'") or query_die(__FILE__,__LINE__,mysql_error());
	}

	//Update status in Keywords table
	mysql_query("UPDATE ".$db_tables["keyword_ads"]." SET ad_status='$status' WHERE ad_id='$ad_id'") or query_die(__FILE__,__LINE__,mysql_error());

	//Send event
	$event_array = array("event"=>"update", "source"=>"adv_advertisement_keyword_ad", "table"=>"keyword_ads", "ad_id"=>$ad_id);
	event_handler($event_array);

	header("Location: adv_advertisement_keyword_ad.php?ad_id={$ad_id}&$SLINE"); exit;
}

function add_ad_info($ad_name,$headline,$line_1,$line_2,$display_url,$destination_url,$max_cpc,$daily_budget,$monthly_budget,
		$status,$keywords_list)
{
 global $db_tables, $SLINE;
	//Add Ad info
	mysql_query("INSERT INTO ".$db_tables["ads"]." VALUES(NULL,'".$_SESSION["sess_userid"]."','$ad_name','$headline','$line_1',".
				"'$line_2','$display_url','$destination_url','$max_cpc','$daily_budget','$monthly_budget','$status')") or query_die(__FILE__,__LINE__,mysql_error());

	$ad_id = mysql_insert_id();

	//Add Keywords info
	for ($i=0; $i<count($keywords_list); $i++)
	{
		mysql_query("INSERT INTO ".$db_tables["keyword_ads"]." VALUES(NULL,'$ad_id','$status','$status','{$keywords_list[$i]["soptions"]}',".
			 "'{$keywords_list[$i]["keyword"]}')") or query_die(__FILE__,__LINE__,mysql_error());

		$keywords_list[$i]["keyword"] = data_addslashes($keywords_list[$i]["keyword"]);
	}

	//Send event
	$event_array = array("event"=>"insert", "source"=>"adv_advertisement_keyword_ad", "table"=>"keyword_ads", "ad_id"=>$ad_id);
	event_handler($event_array);

	header("Location: adv_advertisement_keyword_ad.php?ad_id={$ad_id}&$SLINE"); exit;
}

function get_cur_values(&$ad_name,&$headline,&$line_1,&$line_2,&$display_url,&$destination_url,&$max_cpc,&$daily_budget,
		&$monthly_budget,&$status,&$keyword)
{
	$ad_name				= html_chars(get_post_value("ad_name",""));
	$headline				= html_chars(get_post_value("headline",""));
	$line_1					= html_chars(get_post_value("line_1",""));
	$line_2					= html_chars(get_post_value("line_2",""));
	$display_url		= html_chars(get_post_value("display_url",""));
	$destination_url= html_chars(get_post_value("destination_url",""));
	$max_cpc				= html_chars(get_post_value("max_cpc",""));
	$daily_budget		= html_chars(get_post_value("daily_budget",""));
	$monthly_budget	= html_chars(get_post_value("monthly_budget",""));
	$status					= html_chars(get_post_value("status","1"));
	$keyword				= get_post_value("keyword","");
}

function check_keywords_list(&$keyword,&$my_error)
{
 global $Error_messages,$is_k_stripslashes;
	//Strip slashes if it was done
	if (get_magic_quotes_gpc()) {
		$is_k_stripslashes = true;
		$keyword = stripslashes($keyword);
	}
	else $is_k_stripslashes = false;

	$keywords_tmp = preg_split ("/(\n|\n\r)+/", $keyword);
	$keywords_list = array();
	if (count($keywords_tmp) == 0) return;

	//remove dublicates
	$keywords = array();
	for ($i=0; $i<count($keywords_tmp); $i++)
	{
		if (!in_array(trim($keywords_tmp[$i]), $keywords)) $keywords[] = trim($keywords_tmp[$i]);
	}

	for ($i=0; $i<count($keywords); $i++)
	{
		$keyword_tmp = trim($keywords[$i]);
		if ($keyword_tmp == "") continue;
/*	soptions - опция поиска:
			1: broad - broad match (SQL: "like")
			2: exact - exact match (SQL: "=")
			3: phrase - phrase match (SQL: "=", но для фразы)
			4: negative - negative match (SQL: "<>") */
		$lt = substr($keyword_tmp, 0, 1); $gt = substr($keyword_tmp, -1);

		if ($lt == "-") { $keywords_list[] = array("soptions"=>4, "keyword"=>substr($keyword_tmp, 1)); continue; } //{4}

		if (($lt == "[") && ($gt == "]")) 
			{ $keywords_list[] = array("soptions"=>2, "keyword"=>substr($keyword_tmp, 1, strlen($keyword_tmp)-2)); continue; } //{2}

		if ( (($lt == '"') && ($gt == '"')) || (($lt == "'") && ($gt == "'")) )
			{ $keywords_list[] = array("soptions"=>3, "keyword"=>substr($keyword_tmp, 1, strlen($keyword_tmp)-2)); continue; } //{3}

		if ( ($lt <> '[') && ($lt <> '-') && ($lt <> '"') && ($gt <> ']') && ($gt <> '"') )
			{ $keywords_list[] = array("soptions"=>1, "keyword"=>$keyword_tmp); continue; } // {1}

		$my_error .= str_replace("{*Keyword*}", $keyword_tmp, $Error_messages["invalid_keyword_parsing"]);
	}
	if ( ($my_error == "") && (count($keywords_list) == 0) ) $my_error .= $Error_messages["no_keywords_list"];
 return $keywords_list;
}

function check_cur_values(&$my_error,&$ad_name,&$headline,&$line_1,&$line_2,&$display_url,&$destination_url,&$max_cpc,
		&$daily_budget,&$monthly_budget,$status,&$keyword,&$keywords_list)
{
 global $Error_messages,$text_info,$active_disable_array;
	//Check values on emptiness
	$vallist = array($ad_name,$headline,$line_1,$line_2,$display_url,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status,$keyword);
	$errlist = array($Error_messages["no_ad_name"],$Error_messages["no_headline"],$Error_messages["no_line_1"],
				$Error_messages["no_line_2"],$Error_messages["no_display_url"],$Error_messages["no_destination_url"],
				$Error_messages["no_max_cpc"],$Error_messages["no_daily_budget"],$Error_messages["no_monthly_budget"],
				$Error_messages["no_ad_status"],$Error_messages["no_keywords_list"]);
	isblank($vallist,$errlist,$my_error); //Check values on emptiness (function)
	//Check array values
	$vallist = array($status);
	$errlist = array($Error_messages["invalid_ad_status"]);
	$check_array = array($active_disable_array);
	is_not_array($vallist,$errlist,$check_array,$my_error); //Check values on a correctness (function)

	if ($display_url != "") {
		$display_url_ = $display_url; $display_url = check_url($display_url); 
		if ($display_url === false) {
			$my_error .= $Error_messages["invalid_display_url"]; $display_url = $display_url_;
		}
	}
	if ($destination_url != "") {
		$destination_url_ = $destination_url; $destination_url = check_url($destination_url);
		if ($display_url === false) {
			$my_error .= $Error_messages["invalid_destination_url"]; $destination_url = $destination_url_;
		}
	}
	
	if (($max_cpc !="") && !check_float($max_cpc)) $my_error .= $Error_messages["invalid_max_cpc"];
	if (($daily_budget != "") && !check_float($daily_budget)) $my_error .= $Error_messages["invalid_daily_budget"];
	if (($monthly_budget != "") && !check_float($monthly_budget)) $my_error .= $Error_messages["invalid_monthly_budget"];
	if (($max_cpc < $_SESSION["globsettings"]["min_adv_cost_per_click"]))
			$my_error .= str_replace("{*Amount*}",$_SESSION["globsettings"]["min_adv_cost_per_click"],$Error_messages["small_max_cpc"]);

	if (($headline != "") && (strlen($headline) > $_SESSION["globsettings"]["max_adv_headline_length"])) $my_error .= $Error_messages["invalid_headline_length"];
	if (($line_1 != "") && (strlen($line_1) > $_SESSION["globsettings"]["max_adv_line1_length"])) $my_error .= $Error_messages["invalid_line_1_length"];
	if (($line_2 != "") && (strlen($line_2) > $_SESSION["globsettings"]["max_adv_line2_length"])) $my_error .= $Error_messages["invalid_line_2_length"];

	$keywords_list = check_keywords_list($keyword,$my_error);
}

function slash_cur_values(&$ad_name,&$headline,&$line_1,&$line_2,&$display_url,&$destination_url,&$max_cpc,&$daily_budget,
		&$monthly_budget,&$status,&$keywords_list)
{
 global $is_k_stripslashes;
	$ad_name				= data_addslashes($ad_name);
	$headline				= data_addslashes($headline);
	$line_1					= data_addslashes($line_1);
	$line_2					= data_addslashes($line_2);
	$display_url		= data_addslashes($display_url);
	$destination_url= data_addslashes($destination_url);
	$max_cpc				= data_addslashes($max_cpc);
	$daily_budget		= data_addslashes($daily_budget);
	$monthly_budget	= data_addslashes($monthly_budget);
	$status					= data_addslashes($status);
	for ($i=0; $i<count($keywords_list); $i++)
	{
		if ($is_k_stripslashes) $keywords_list[$i]["keyword"] = addslashes($keywords_list[$i]["keyword"]);
		$keywords_list[$i]["keyword"] = html_chars(data_addslashes($keywords_list[$i]["keyword"]));
	}
}

function try_add()
{
 global $Error_messages,$text_info,$db_tables;
	$my_error = "";
	get_cur_values($ad_name,$headline,$line_1,$line_2,$display_url,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status,$keyword);
	check_cur_values($my_error,$ad_name,$headline,$line_1,$line_2,$display_url,$destination_url,$max_cpc,$daily_budget,$monthly_budget,
		$status,$keyword,$keywords_list);

	//If no errors - save
	if ($my_error == "") {
		slash_cur_values($ad_name,$headline,$line_1,$line_2,$display_url,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status,$keywords_list);
		//Update data
		add_ad_info($ad_name,$headline,$line_1,$line_2,$display_url,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status,$keywords_list);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		create_values("",$ad_name,$headline,$line_1,$line_2,$display_url,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status,$keyword,1);
		create_page_buttons("add",$text_info["btn_add"]);
	}
}

function try_save($ad_id)
{
 global $smarty,$Error_messages,$text_info,$db_tables,$active_disable_array;
	$my_error = "";
	get_cur_values($ad_name,$headline,$line_1,$line_2,$display_url,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status,$keyword);
	check_cur_values($my_error,$ad_name,$headline,$line_1,$line_2,$display_url,$destination_url,$max_cpc,$daily_budget,$monthly_budget,
		$status,$keyword,$keywords_list);

	//If no errors - save
	if ($my_error == "") {
		slash_cur_values($ad_name,$headline,$line_1,$line_2,$display_url,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status,$keywords_list);
		//Update data
		update_ad_info($ad_id,$ad_name,$headline,$line_1,$line_2,$display_url,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status,$keywords_list);
	}
	else { //else - try again
		smarty_create_message("error","abort.gif",$my_error);
		//Create form and buttons
		create_values($ad_id,$ad_name,$headline,$line_1,$line_2,$display_url,$destination_url,$max_cpc,$daily_budget,$monthly_budget,$status,$keyword,1);
		create_page_buttons("save",$text_info["btn_save"]);
	}
}
?>