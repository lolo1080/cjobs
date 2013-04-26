<?
// Проверяет кеш. Если актуальность истекла - удаляет кеш файлы.
define("clear_actual_time_sec", 2*3600);	// 2 hours

$mainsite_script_dir = dirname(__FILE__)."/../../";
$adminsite_script_dir = dirname(__FILE__)."/../";
include_once $mainsite_script_dir."consts.php"; //Include main consts.php file (from frontend area)
require_once $admin_dir_path."app_errors_handler.php";
require_once $frontend_script_dir."app_cache_functions.php";
require_once $mainsite_script_dir."language.php";
require_once $adminsite_script_dir."connect.inc";
require_once $adminsite_script_dir."include/functions/functions_main.php";

function start_clear_not_actual_cache($cache_params_array)
{
 global $cache_info, $os_devider;
	//Prepare dir values
	prepare_dir_values($cache_params_array,$fname,$cache_dir_innerpath);

	//Create table cache dir
	$cache_dir_fullpath = $cache_info["cache_dir"].$cache_dir_innerpath;

	//Correct path
	while ( strpos($cache_dir_fullpath, '//') !== false )
	{
		$cache_dir_fullpath = str_replace('//', '/', $cache_dir_fullpath);
	}	

	if (!is_dir($cache_dir_fullpath)) return;

	do_clear_not_actual_cache($cache_dir_fullpath);

 return;
}

function do_clear_not_actual_cache($file) {
	if (file_exists($file)) {
		@chmod($file,0777);
		if (is_dir($file)) {
			$handle = opendir($file); 
			while($filename = readdir($handle))
			{
				if ($filename != "." && $filename != "..") {
					do_clear_not_actual_cache($file."/".$filename);
				}
			}
			@closedir($handle);
		} 
		else {
			if (file_exists($file))	{
					@unlink($file);
/*
				if ((time()-filemtime($file)) > clear_actual_time_sec) {
					//echo $file."<br>";
					@unlink($file);
				}
*/
			}
		}
	}
}

//Remove all not actual cache files... (cron function)
function clear_not_actual_cache()
{
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "", //"search_template_values",
		"table_name"	=> "", //"template_values",
		"params_list"	=> array(), //array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> "", //$sql,
		"actual_time"	=> 1, //Время актуальности в милисек.
		"store_type"	=> "" //"as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);
	start_clear_not_actual_cache($cache_params_array);
}

clear_not_actual_cache();
?>