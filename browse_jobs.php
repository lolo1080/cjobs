<?
session_start();

require_once "consts.php";
require_once $admin_dir_path."app_errors_handler.php";
require_once "consts_smarty.php";
require_once "language.php";
require_once "template_vals.php";
require_once "index_functions.php";
require_once "browse_jobs_functions.php";
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

//Site title
$SiteTitle = $text_info["html_browse_jobs"];

//Check keyword section
$keyword_id = data_addslashes(html_chars(get_get_value("keyword_id","")));					//Keyword section

// * * Check cache * * //
$template_id = 25;
$sql = "SELECT * FROM ".$db_tables["template_values"]." WHERE template_id='$template_id'";
$cache_params_array = array(
	"user"				=> 3, //$_SESSION["sess_user"]
	"cache_group"	=> "smarty_frontend",
	"userid"			=> 0, //$_SESSION["sess_userid"]
	"section"			=> "browse_jobs_template_values",
	"table_name"	=> "template_values",
	"params_list"	=> array(), //��� �砢����� � ����஥��� ��� (��� �����) //"params_list"	=> array("1","b")
	"query"				=> $sql,
	"actual_time"	=> 36*60, //�६� ���㠫쭮�� � ᥪ.
	"store_type"	=> "as_array" //��� �⥭��: 1)"as_table" - � ⠡���� (��� "table_name") 2)"as_array" - � ���ᨢ 	$cache_data_array
);
prepare_template_values($cache_params_array,$template_id);
$smarty->display('browse_jobs.tpl');
?>