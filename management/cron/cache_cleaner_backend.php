<?
// Проверяет кеш. Если актуальность истекла - удаляет кеш файлы.
define("clear_actual_time_sec", 2*3600); //2 hours

$adminsite_script_dir = dirname(__FILE__)."/../";
include_once $adminsite_script_dir."consts.php"; //Include main consts.php file (from frontend area)
require_once $adminsite_script_dir."app_errors_handler.php";
require_once $adminsite_script_dir."app_cache_functions.php";
require_once $adminsite_script_dir."language.php";
require_once $adminsite_script_dir."connect.inc";
require_once $adminsite_script_dir."include/functions/functions_main.php";

function start_clear_not_actual_cache($cache_params_array)
{
 global $cache_info, $os_devider;
	//Create cache sub dir (stats_admin | stats_adv | stats_pub)
	$user_subdir = get_stats_cache_subdir_by_usertype($cache_params_array["user"]);
	if ($user_subdir == "") return;
	//Add path to table
	$user_tablebdir = get_stats_cache_table_dir($cache_params_array);
	//Create table cache dir
	$cache_dir_fullpath = $cache_info["cache_dir"].$user_subdir.$user_tablebdir;

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
			closedir($handle);
			//@rmdir($file);
		} 
		else {
			$pos = strpos($file, ".dat");
			if ($pos !== false) {
				$cfile = substr($file, 0, $pos);
				$f = @fopen($file, "r");
				if ($f) {
					$actual_time = @fgets($f);
					@fclose($f);
					if (abs(time()-$actual_time) > clear_actual_time_sec) {
						//echo $file."<br>\n";
						//echo $cfile."<br>\n";
						@unlink($file);
						@unlink($cfile);
					}
				}				
			}
		}
	}
}

//Remove all not actual cache files... (cron function)
function clear_not_actual_cache()
{
	for ($i=0; $i<3; $i++)
	{
		$cache_params_array = array(
			"user"					=> $i,
			"userid"				=> "",
			"stats_query"		=> "",
			"stats_type"		=> "",
			"stats_type_a"	=> "",
			"stats_type_b"	=> "",
			"params_list"		=> array(),
			"table_name"		=> ""
		);
		start_clear_not_actual_cache($cache_params_array);
	}
}

clear_not_actual_cache();
?>