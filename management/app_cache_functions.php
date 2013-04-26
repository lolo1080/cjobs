<?
require_once $frontend_dir."/app_cache_functions.php";

//Return path to table cache
function get_stats_cache_table_dir($cache_params_array)
{
	$params_list	= "";
	for ($i=0; $i<count($cache_params_array["params_list"]); $i++)
	{
		$params_list .= $cache_params_array["params_list"][$i].'/';
	}
	return $cache_params_array["userid"].'/'.$cache_params_array["stats_query"].'/'.$cache_params_array["table_name"].'/'.$params_list;
}

//Return cache file name
function get_stats_filename($cache_params_array)
{
	return md5($cache_params_array["stats_type"].$cache_params_array["stats_type_a"].$cache_params_array["stats_type_b"]);
}

//Force dir creation by subpath in current path
function create_dir_force_n($path, $subpath)
{
	$dirs = array();
	$dirs = explode("/", $subpath);
	$i = 0;
	foreach ($dirs as $element) {
		$path .= $element . "/";
			if (!is_dir($path) && $i != 0) {
				if (!mkdir($path)) {
					none_critical_notice(__FILE__,__LINE__,'Cannot create user cache force dir. Path: '.$path);
					return false;
				}
				else @chmod($path,0777);
			}  
		$i++;
  }
}

function get_stats_cache_subdir_by_usertype($usertype)
{
	switch ($usertype) {
		case "0": return 'stats_admin/';
		case "1": return 'stats_adv/';
		case "2": return 'stats_pub/';
	}
 return "";
}

function do_cache_stats_notice_generate($file,$line,$msg,&$result)
{
	none_critical_notice($file,$line,$msg);
	$result = false;
}

function set_correct_path_for_MySQL($cache_data_filename)
{
 global $os_devider;
	$devider = get_os_devider();
	if ($devider == $os_devider["windows"]) {
		$cache_data_filename = str_replace($os_devider["unix"], $os_devider["windows"], $cache_data_filename);
		$cache_data_filename = str_replace($os_devider["windows"], $os_devider["windows"].$os_devider["windows"], $cache_data_filename);
	}
 return $cache_data_filename;
}

function actual_stats_time_by_usertype($usertype)
{
	switch ($usertype) {
		case "0": return $_SESSION["globsettings"]["cache_actualtime_admin"];
		case "1": return $_SESSION["globsettings"]["cache_actualtime_adv"];
		case "2": return $_SESSION["globsettings"]["cache_actualtime_pub"];
	}
 return 0;
}

function prepare_cachedir_values($action,$cache_params_array,&$fname,&$user_subdir,&$user_tablebdir,&$cache_dir_fullpath)
{
 global $cache_info;
	//Create cache file name
	$fname = get_stats_filename($cache_params_array);

	//Create cache sub dir (stats_admin | stats_adv | stats_pub)
	$user_subdir = get_stats_cache_subdir_by_usertype($cache_params_array["user"]);
	if ($user_subdir == "") { none_critical_notice(__FILE__,__LINE__,$action.': Cannot define user cache dir. '.print_r($cache_params_array,true)); return false; }
	//Add path to table
	$user_tablebdir = get_stats_cache_table_dir($cache_params_array);

	//Create table cache dir
	$cache_dir_fullpath = $cache_info["cache_dir"].$user_subdir.$user_tablebdir;
}

//If we cannot SAVE data from table using SELECT * FROM table INTO OUTFILE 'file' try save by records
function try_save_data_by_records($cache_params_array,$cache_data_filename,&$result)
{
	//Save data by records
	$f = @fopen($cache_data_filename,"w");
	if (!@$f) {	none_critical_notice(__FILE__,__LINE__,'Can not open cache data file '.$cache_data_filename.' for write'); return; }

	$qr_res = mysql_query("SELECT * FROM {$cache_params_array["table_name"]}") or do_cache_stats_notice_generate(__FILE__,__LINE__,'Can not save data from table '.$cache_params_array["table_name"].' to cache file '.$cache_data_filename.' DB Error message: '.mysql_error(),$result);
	while ($myrow = mysql_fetch_row($qr_res))
	{
		$fstr = "";
		for ($i=0; $i<count($myrow); $i++)
		{
			if ($i == (count($myrow)-1) )	$fstr .= $myrow[$i];
			else $fstr .= $myrow[$i]."\t";
		}
		@fwrite($f, $fstr."\n");
	}
	@fclose($f);
	@chmod($cache_data_filename,0777);
}

//If we cannot LOAD data from table using LOAD DATA INFILE 'file' INTO TABLE 'table' try load by records
function try_load_data_by_records($cache_params_array,$cache_data_filename,&$result)
{
	//Load data by records
	$f = @fopen($cache_data_filename,"r");
	if (!@$f) {	none_critical_notice(__FILE__,__LINE__,'Can not open cache data file '.$cache_data_filename.' for write'); return; }
	while (!feof($f)) {
		@$values = explode("\t",fgets($f));

		if (!is_array($values) || (count($values) == 0) || !isset($values[0]) || ($values[0] == "")) continue;
		for ($i=0; $i<count($values); $i++)
		{
			$values[$i] = "'".trim($values[$i])."'";
		}

		mysql_query("INSERT INTO {$cache_params_array["table_name"]} VALUES(".implode(',',$values).")") or do_cache_stats_notice_generate(__FILE__,__LINE__,'Can not load data from chache file to table '.$cache_params_array["table_name"].' DB Error message: '.mysql_error(),$result);
	}
	@fclose($f);
 $result = true;
}


//- - - - - - - - - - - - - //
//* * Write stats cache * * //
function write_stats_cache($cache_params_array)
{
 global $cache_info;
	if (!$_SESSION["globsettings"]["use_stats_cache"]) return;

	//Prepare dir values
	prepare_cachedir_values("Write Cache",$cache_params_array,$fname,$user_subdir,$user_tablebdir,$cache_dir_fullpath);

	//Create table cache dir
	if (!is_dir($cache_dir_fullpath)) create_dir_force_n($cache_info["cache_dir"], $user_subdir.$user_tablebdir);
	if (!is_dir($cache_dir_fullpath)) { none_critical_notice(__FILE__,__LINE__,'Cannot create user cache dir. '.$cache_dir_fullpath); return false; }

	//Get cache files names (1 - cache data file(content) and   2 - cache info file with actual time(time))
	$cache_data_filename = $cache_dir_fullpath.$fname;
	$cache_info_filename = $cache_data_filename.'.dat';

	//Check actual time
	if (file_exists($cache_info_filename)) {
		//Read actual time
		$f = @fopen($cache_info_filename, "r");
		if (!@$f) {	none_critical_notice(__FILE__,__LINE__,'Can not open cache info file: '.$cache_info_filename); return false;	}
		$actual_time = @fgets($f);
		@fclose($f);

		//Check actual time
		if ($actual_time > time()) return;
	}

	//Save actual time
	$f = @fopen($cache_info_filename,"w");
	if (!@$f) {	none_critical_notice(__FILE__,__LINE__,'Can not open cache info file '.$cache_info_filename.' for write'); return; }
	else @fwrite($f, time() + actual_stats_time_by_usertype($cache_params_array["user"]));
	@fclose($f);
	@chmod($cache_info_filename,0777);

	//Save data
	$cache_data_filename = set_correct_path_for_MySQL($cache_data_filename);
	if (file_exists($cache_data_filename)) @unlink($cache_data_filename);
	mysql_query("SELECT * FROM {$cache_params_array["table_name"]} INTO OUTFILE '$cache_data_filename'") or 
			try_save_data_by_records($cache_params_array,$cache_data_filename,$result); //do_cache_stats_notice_generate(__FILE__,__LINE__,'Can not save data from table '.$cache_params_array["table_name"].' to cache file '.$cache_data_filename.' DB Error message: '.mysql_error(),$result);
}


//- - - - - - - - - - - - //
//* * Read stats cache * *//
function read_stats_cache($cache_params_array)
{
 global $cache_info;
	if (!$_SESSION["globsettings"]["use_stats_cache"]) return false;

	//Prepare dir values
	prepare_cachedir_values("Read Cache",$cache_params_array,$fname,$user_subdir,$user_tablebdir,$cache_dir_fullpath);

	//Get cache files names (1 - cache data file(content) and   2 - cache info file with actual time(time))
	$cache_data_filename = $cache_dir_fullpath.$fname;
	$cache_info_filename = $cache_data_filename.'.dat';

	if (file_exists($cache_info_filename)) {
		//Read actual time
		$f = @fopen($cache_info_filename, "r");
		if (!@$f) {	none_critical_notice(__FILE__,__LINE__,'Can not open cache info file: '.$cache_info_filename); return false;	}
		$actual_time = @fgets($f);
		@fclose($f);

		//Check actual time
		if ($actual_time < time()) return false;

		//Read cache data
		if (file_exists($cache_data_filename)) {
			$cache_data_filename = set_correct_path_for_MySQL($cache_data_filename);
			$result = true;
			mysql_query("LOAD DATA INFILE '$cache_data_filename' INTO TABLE {$cache_params_array["table_name"]}") or 
				try_load_data_by_records($cache_params_array,$cache_data_filename,$result);	//do_cache_stats_notice_generate(__FILE__,__LINE__,'Can not load data from chache file to table '.$cache_params_array["table_name"].' DB Error message: '.mysql_error(),$result);
			return $result;
		}
	}
 return false;	
}


//- - - - - - - - - - - - - //
//* * Remove stats cache * *//
function remove_stats_cache($cache_params_array)
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

	delete_this_dir($cache_dir_fullpath);

	if (is_dir($cache_dir_fullpath)) {
		$devider = get_os_devider();
		if ($devider == $os_devider["windows"]) {
			$cache_dir_fullpath = str_replace($os_devider["unix"], $os_devider["windows"], $cache_dir_fullpath);
			exec("rmdir /s /q $cache_dir_fullpath");
		}
		elseif ($devider == $os_devider["unix"]) {
			$cache_dir_fullpath = str_replace($os_devider["windows"], $os_devider["unix"], $cache_dir_fullpath);
			exec("rm -r $cache_dir_fullpath");
		}
	}
 return;
}

//Delete dir
function delete_this_dir($file) {
	if (file_exists($file)) {
		@chmod($file,0777);
		if (is_dir($file)) {
			$handle = opendir($file); 
			while($filename = readdir($handle))
			{
				if ($filename != "." && $filename != "..") {
					delete_this_dir($file."/".$filename);
				}
			}
			closedir($handle);
			@rmdir($file);
		} 
		else {
			@unlink($file);
		}
	}
}


//- - - - - - - - - - - - - - - - - - - - - -//
//* * Remove BACK-EDN currnet table cache * *//
function remove_table_stats_cache($stats_query,$params_list,$table_name)
{
	$cache_params_array = array(
		"user"					=> $_SESSION["sess_user"],
		"userid"				=> $_SESSION["sess_userid"],
		"stats_query"		=> $stats_query,
		"stats_type"		=> "",
		"stats_type_a"	=> "",
		"stats_type_b"	=> "",
		"params_list"		=> $params_list,
		"table_name"		=> $table_name
	);
	remove_stats_cache($cache_params_array);
	//Set small timeout
	for ($i=0; $i<80; $i++)
	{
		$a = $i + ($i-1);
	}
}

//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //
//* * Remove BACK-EDN currnet table cache from admin auser * *//
function remove_table_stats_cache_admin($stats_query,$params_list,$table_name)
{
	$admids = get_all_admin_ids();
	for ($a=0; $a<count($admids); $a++)
	{
		$cache_params_array = array(
			"user"					=> 0,
			"userid"				=> $admids[$a],
			"stats_query"		=> $stats_query,
			"stats_type"		=> "",
			"stats_type_a"	=> "",
			"stats_type_b"	=> "",
			"params_list"		=> $params_list,
			"table_name"		=> $table_name
		);
		remove_stats_cache($cache_params_array);
		//Set small timeout
		for ($i=0; $i<80; $i++)
		{
			$a = $i + ($i-1);
		}
	}
}
?>