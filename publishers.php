<?
session_start();

require_once "consts.php";
require_once $admin_dir_path."app_errors_handler.php";
require_once "consts_smarty.php";
require_once "language.php";
require_once "template_vals.php";
require_once "index_functions.php";
require_once $admin_dir_path."connect.inc";
require_once $admin_dir_path."include/functions/functions_main.php";
require_once $frontend_script_dir."app_cache_functions.php";
require_once $frontend_script_dir."common_functions.php";
require_once $frontend_script_dir."common_statistic.php";

doconnect();

//Check IPFW
if (!check_visitor_ipfw_cache()) { header("Location: ipfw.php"); exit; }

//Check settings
get_global_settings();

//Collect visitor info
collect_visitor_info();

//Login value
$_SESSION["sess_curlogintype"] = "publisher";

//Site title
$SiteTitle = $text_info["html_publishers"];

// * * Check cache * * //
$template_id = 21;
$sql = "SELECT * FROM ".$db_tables["template_values"]." WHERE template_id='$template_id'";
$cache_params_array = array(
	"user"				=> 3, //$_SESSION["sess_user"]
	"cache_group"	=> "smarty_frontend",
	"userid"			=> 0, //$_SESSION["sess_userid"]
	"section"			=> "publishers_template_values",
	"table_name"	=> "template_values",
	"params_list"	=> array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
	"query"				=> $sql,
	"actual_time"	=> 36*60, //Время актуальности в сек.
	"store_type"	=> "as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
);
prepare_template_values($cache_params_array,$template_id);
$smarty->display('publishers.tpl');
?>