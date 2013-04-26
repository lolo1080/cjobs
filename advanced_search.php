<?
session_start();

require_once "consts.php";
require_once $admin_dir_path."app_errors_handler.php";
require_once "consts_smarty.php";
require_once "language.php";
require_once "template_vals.php";
require_once "search_functions.php";
require_once "index_functions.php";
require_once $admin_dir_path."connect.inc";
require_once $admin_dir_path."include/functions/functions_main.php";
require_once $frontend_script_dir."app_cache_functions.php";
require_once $frontend_script_dir."common_functions.php";

doconnect();

//Site title
$SiteTitle = $text_info["html_advanced_search"];

//Check settings
get_global_settings();

// * * Check cache * * //
$template_id = 7;
$sql = "SELECT * FROM ".$db_tables["template_values"]." WHERE template_id='$template_id'";
$cache_params_array = array(
	"user"				=> 3, //$_SESSION["sess_user"]
	"cache_group"	=> "smarty_frontend",
	"userid"			=> 0, //$_SESSION["sess_userid"]
	"section"			=> "search_template_values",
	"table_name"	=> "template_values",
	"params_list"	=> array(), //��� �砢����� � ����஥��� ��� (��� �����) //"params_list"	=> array("1","b")
	"query"				=> $sql,
	"actual_time"	=> 37*60, //�६� ���㠫쭮�� � ����ᥪ.
	"store_type"	=> "as_array" //��� �⥭��: 1)"as_table" - � ⠡���� (��� "table_name") 2)"as_array" - � ���ᨢ 	$cache_data_array
);
prepare_template_values($cache_params_array,$template_id);
$smarty->display('advanced_searchpage.tpl');
?>