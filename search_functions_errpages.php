<?
function create_start_error_page($message)
{
 global $db_tables, $smarty, $my_error, $Error_messages, $job_search_params;
	$my_error = $message;
	switch ($job_search_params["search_type"]) {
		case "simple": $template_id = 10; break;
		case "advanced": $template_id = 9; break;
		default: $job_search_params["search_type"] = "simple"; $template_id = 10; //critical_error(__FILE__,__LINE__,"Invalid job type.");
	}
	// * * Check cache * * //
	$sql = "SELECT * FROM ".$db_tables["template_values"]." WHERE template_id='$template_id'";
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_template_values",
		"table_name"	=> "template_values",
		"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 32*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);
	prepare_template_values($cache_params_array,$template_id);
	switch ($job_search_params["search_type"]) {
		case "simple": $smarty->display('simple_searchpage_error.tpl'); break;
		case "advanced": $smarty->display('advanced_searchpage_error.tpl'); break;
		default: critical_error(__FILE__,__LINE__,"Invalid job type.");
	}
	exit;
}

function create_empty_search_result_page(&$job_search_params)
{
 global $db_tables, $smarty, $my_error, $Error_messages;
	$job_alert = data_addslashes(html_chars(get_get_value("job_alert",""))); //Job alert search
	if (($job_alert != "") && check_int($job_alert)) $my_error = $Error_messages["search_empty_result_job_alert_".$job_alert];
	else $my_error = $Error_messages["search_empty_result"];
	$my_error = (isset($job_search_params["what"]) && ($job_search_params["what"] != ""))
		? str_replace("{*what*}", $Error_messages["search_empty_result_what"]."<b>{$job_search_params["what"]}</b>", $my_error)
		: str_replace("{*what*}", "", $my_error);
	$my_error = (isset($job_search_params["where"]) && ($job_search_params["where"] != ""))
		? str_replace("{*where*}", $Error_messages["search_empty_result_where"]."<b>{$job_search_params["where"]}</b>", $my_error)
		: str_replace("{*where*}", "", $my_error);
	// * * Check cache * * //
	$template_id = 16;
	$sql = "SELECT * FROM ".$db_tables["template_values"]." WHERE template_id='$template_id'";
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_template_values",
		"table_name"	=> "template_values",
		"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 33*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);
	prepare_template_values($cache_params_array,$template_id);
	$smarty->display('searchpage_empty.tpl');
	exit;
}

function create_empty_jobroll_page()
{
 global $db_tables, $smarty, $my_error, $Error_messages;
	// * * Check cache * * //
	$template_id = 17;
	$sql = "SELECT * FROM ".$db_tables["template_values"]." WHERE template_id='$template_id'";
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "search_template_values",
		"table_name"	=> "template_values",
		"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 35*60, //Время актуальности в сек.
		"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);
	prepare_template_values($cache_params_array,$template_id);
	$smarty->display('jobrollpage_empty.tpl');
	exit;
}
?>