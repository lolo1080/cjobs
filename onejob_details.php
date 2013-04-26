<?
session_start();

require_once "consts.php";
require_once $admin_dir_path."app_errors_handler.php";
require_once "consts_smarty.php";
require_once "language.php";
require_once "template_vals.php";
require_once "index_functions.php";
require_once "onejob_details_func.php";
require_once "search_functions_errpages.php";
require_once "search_functions.php";
require_once $admin_dir_path."connect.inc";
require_once $admin_dir_path."include/functions/functions_main.php";
require_once $frontend_script_dir."app_cache_functions.php";
require_once $frontend_script_dir."common_functions.php";

function check_no_data_found(&$job_data)
{
 global $Error_messages;
	if (count($job_data) == 0) {
		$job_search_params["search_type"]	= "simple";
		create_start_error_page($Error_messages["search_no_data_found"]);
	}
}

//Get data ids (common data and advertiser data)
$data_id = html_chars(get_get_value("data_id",""));					//Main result list

if ( ($data_id == "") || (($data_id != "") && !check_int($data_id)) ) {
	doconnect();
	//Check settings
	get_global_settings();
	$job_search_params["search_type"]	= "simple";
	create_start_error_page($Error_messages["search_no_data_id"]);
}

doconnect();

//Check IPFW
if (!check_visitor_ipfw_cache()) { header("Location: ipfw.php"); exit; }

//Check settings
get_global_settings();

$job_list = array();

//Select this job
$job_list = do_onejob_data_search($data_id);
//Check results count
check_no_data_found($job_list);

//Design job
do_onejob_design($job_list);

// * * Check cache * * //
$template_id = 29;
$sql = "SELECT * FROM ".$db_tables["template_values"]." WHERE template_id='$template_id'";
$cache_params_array = array(
	"user"				=> 3, //$_SESSION["sess_user"]
	"cache_group"	=> "smarty_frontend",
	"userid"			=> 0, //$_SESSION["sess_userid"]
	"section"			=> "onejob_template_values",
	"table_name"	=> "template_values",
	"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
	"query"				=> $sql,
	"actual_time"	=> 15*60, //Время актуальности в сек.
	"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
);
prepare_template_values($cache_params_array,$template_id);
$smarty->display('onejob.tpl');
?>