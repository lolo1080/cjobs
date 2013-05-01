<?php
//Returnb string from all cahce values (key=val)
function get_cache_params_str($params)
{
 global $cache_filename_string;
	foreach ($params as $k=>$v)
	{
		if (is_array($v)) get_cache_params_str($v);
		else $cache_filename_string .= $k.'='.$v;
	}
}

//Return cache file name
function get_cache_filename($cache_params_array)
{
 global $cache_filename_string;
	$cache_filename_string = "";
	get_cache_params_str($cache_params_array);
	return md5($cache_filename_string);
}

//Return path with params cache values
function get_params_list_dir($cache_params_array)
{
	$params_list	= "";
	for ($i=0; $i<count($cache_params_array["params_list"]); $i++)
	{
		$params_list .= $cache_params_array["params_list"][$i].'/';
	}
	return $params_list;
}

//Prepare some cache values
function prepare_dir_values($cache_params_array,&$fname,&$cache_dir_innerpath)
{
 global $cache_info;
 	
	//Create cache file name
	$fname = get_cache_filename($cache_params_array);
	
	//Create table cache dir
	$cache_dir_innerpath = $cache_params_array["cache_group"].'/'.$cache_params_array["userid"].'/'.
			$cache_params_array["section"].'/'.$cache_params_array["table_name"].'/'.get_params_list_dir($cache_params_array);
}

function cache_stats_notice_generate($file,$line,$msg,&$result)
{
	none_critical_notice($file,$line,$msg);
	$result = false;
}

function set_correct_MySQL_path($cache_data_filename)
{
 global $os_devider;
	$devider = get_os_devider();
	if ($devider == $os_devider["windows"]) {
		$cache_data_filename = str_replace($os_devider["unix"], $os_devider["windows"], $cache_data_filename);
		$cache_data_filename = str_replace($os_devider["windows"], $os_devider["windows"].$os_devider["windows"], $cache_data_filename);
	}
 return $cache_data_filename;
}


//- - - - - - - - - -  - //
//* * Read cache data * *//
//Structure: cahce/<cache_group>/<userid>/<section>/<table_name>/(<params_list>)*/md5(all $cache_params_array keys and values)
function read_mydata_cache(&$cache_params_array,&$cache_data_array)
{
 global $cache_info;
	if (!isset($_SESSION["globsettings"]["use_frontend_cache"]))
	{
		if (!isset($cache_info["use_cache_default"]) || !$cache_info["use_cache_default"]) return false;
	}
	elseif (!$_SESSION["globsettings"]["use_frontend_cache"]) return false;

	//Prepare dir values
	$cache_data_array = array(); 
	prepare_dir_values($cache_params_array,$fname,$cache_dir_innerpath);

	//Get cache files name
	$cache_data_filename = $cache_info["cache_dir"].$cache_dir_innerpath.$fname;
	
	if (file_exists($cache_data_filename)) {
		//Check actual time
		if (time()-filemtime($cache_data_filename) > $cache_params_array["actual_time"]) return false;
		//Read cache data
		switch ($cache_params_array["store_type"]) {
			case "as_table":
				$cache_data_filename = set_correct_MySQL_path($cache_data_filename);
				$result = true;
				mysql_query("LOAD DATA INFILE '$cache_data_filename' INTO TABLE {$cache_params_array["table_name"]}") or 
					try_load_by_records($cache_params_array,$cache_data_filename,$result);	//cache_stats_notice_generate(__FILE__,__LINE__,'Can not load data from chache file to table '.$cache_params_array["table_name"].' DB Error message: '.mysql_error(),$result);
				return $result;
			break;
			case "as_array":
				$f = @fopen($cache_data_filename,"rb");
				if (!@$f) {	none_critical_notice(__FILE__,__LINE__,'Can not open cache data file '.$cache_data_filename.' for read'); return false; }
				if (filesize($cache_data_filename) == 0) return false;
				$cache_data_array = unserialize(fread($f, filesize($cache_data_filename)));
				fclose($f);
				return true;				
			break;
		}
	}
 $cache_data_array = array();
 return false;	
}

//If we cannot LOAD data from table using LOAD DATA INFILE 'file' INTO TABLE 'table' try load by records
function try_load_by_records($cache_params_array,$cache_data_filename,&$result)
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

		mysql_query("INSERT INTO {$cache_params_array["table_name"]} VALUES(".implode(',',$values).")") or cache_stats_notice_generate(__FILE__,__LINE__,'Can not load data from chache file to table '.$cache_params_array["table_name"].' DB Error message: '.mysql_error(),$result);
	}
	@fclose($f);
 $result = true;
}

//Force dir creation by subpath in current path
function create_dir_force($path, $subpath)
{
	$dirs = array();
	$dirs = explode("/", $subpath);
	$i = 0;
	foreach ($dirs as $element) {
		$path .= $element . "/";
			if (!is_dir($path)) {
				if (!mkdir($path)) {
					none_critical_notice(__FILE__,__LINE__,'Cannot create user cache force dir. Path: '.$path);
					return false;
				}
				else @chmod($path,0777);
			}  
		$i++;
  }
}


//- - - - - - - - - - -- - //
//* * Write cache data * * //
function write_mydata_cache(&$cache_params_array,&$cache_data_array)
{
 global $cache_info;
 
	if (!isset($_SESSION["globsettings"]["use_frontend_cache"]))
	{
		if (!isset($cache_info["use_cache_default"]) || !$cache_info["use_cache_default"]) return false;
	}
	elseif (!$_SESSION["globsettings"]["use_frontend_cache"]) return false;
	
	//Prepare dir values
	prepare_dir_values($cache_params_array,$fname,$cache_dir_innerpath);
	
	//Create table cache dir
	$cache_dir_fullpath = $cache_info["cache_dir"].$cache_dir_innerpath;
	if (!is_dir($cache_dir_fullpath)) create_dir_force($cache_info["cache_dir"], $cache_dir_innerpath);
	if (!is_dir($cache_dir_fullpath)) { none_critical_notice(__FILE__,__LINE__,'Cannot create user cache dir. '.$cache_dir_fullpath); return false; }

	//Get cache file names
	$cache_data_filename = $cache_dir_fullpath.$fname;
	
	//Check actual time
	$cache_data_filename = str_replace("//", "/", $cache_data_filename);
	if (file_exists($cache_data_filename)) {
		//Check actual time
		if (time()-filemtime($cache_data_filename) < $cache_params_array["actual_time"]) return;
	}

	//Save cache data
	switch ($cache_params_array["store_type"]) {
		case "as_table":
			$cache_data_filename = set_correct_MySQL_path($cache_data_filename);
			if (file_exists($cache_data_filename)) @unlink($cache_data_filename);
			mysql_query("SELECT * FROM {$cache_params_array["table_name"]} INTO OUTFILE '$cache_data_filename'") or 
					try_save_by_records($cache_params_array,$cache_data_filename,$result); //cache_stats_notice_generate(__FILE__,__LINE__,'Can not save data from table '.$cache_params_array["table_name"].' to cache file '.$cache_data_filename.' DB Error message: '.mysql_error(),$result);
		break;
		case "as_array":
			$f = @fopen($cache_data_filename,"wb");
			if (!@$f) {	none_critical_notice(__FILE__,__LINE__,'Can not open cache data file '.$cache_data_filename.' for read'); return; }
			fwrite($f, serialize($cache_data_array));
			fclose($f);
			@chmod($cache_data_filename,0777);
		break;
	}
}

//If we cannot SAVE data from table using SELECT * FROM table INTO OUTFILE 'file' try save by records
function try_save_by_records($cache_params_array,$cache_data_filename,&$result)
{
	//Save data by records
	$f = @fopen($cache_data_filename,"w");
	if (!@$f) {	none_critical_notice(__FILE__,__LINE__,'Can not open cache data file '.$cache_data_filename.' for write'); return; }

	$qr_res = mysql_query("SELECT * FROM {$cache_params_array["table_name"]}") or cache_stats_notice_generate(__FILE__,__LINE__,'Can not save data from table '.$cache_params_array["table_name"].' to cache file '.$cache_data_filename.' DB Error message: '.mysql_error(),$result);
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


//- - - - - - - - - - - - -//
//* * Remove cache data * *//
function remove_mydata_cache($cache_params_array)
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

	delete_dir($cache_dir_fullpath);

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

	//Set small timeout
	for ($i=0; $i<50; $i++)
	{
		$a = $i + ($i-1);
	}
 return;
}

//Delete dir
function delete_dir($file) {
	if (file_exists($file)) {
		@chmod($file,0777);
		if (is_dir($file)) {
			$handle = opendir($file); 
			while($filename = readdir($handle))
			{
				if ($filename != "." && $filename != "..") {
					delete_dir($file."/".$filename);
				}
			}
			@closedir($handle);
			@rmdir($file);
		} 
		else {
			if (file_exists($file))	@unlink($file);
		}
	}
}

function set_not_actual_mydata_cache($cache_params_array)
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

	set_not_actual_for_dir($cache_dir_fullpath);

 return;
}

function set_not_actual_for_dir($file) {
	if (file_exists($file)) {
//		@chown($file, "nobody");
		if (($file[strlen($file)-1] == '/') || ($file[strlen($file)-1] == '\\')) $file = substr($file, 0, -1);
		chmod($file,0777);
		if (is_dir($file)) {
			$handle = opendir($file); 
			while($filename = readdir($handle))
			{
				if ($filename != "." && $filename != "..") {
					set_not_actual_for_dir($file."/".$filename);
				}
			}
			@closedir($handle);
		} 
		else {
			if (file_exists($file))	{
				touch($file,time()-24*3600);
			}
		}
	}
}

//- - - - - - - - - - - - - - - - - - - - - - //
//* * Remove FRONT-END currnet table cache * *//
function remove_frontend_table_cache_data($section,$table_name,$params_list,$store_type)
{
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> $section, //"search_template_values",
		"table_name"	=> $table_name, //"template_values",
		"params_list"	=> $params_list, //array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> "", //$sql,
		"actual_time"	=> 1, //Время актуальности в милисек.
		"store_type"	=> $store_type //"as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);
	remove_mydata_cache($cache_params_array);
	//Set small timeout
	for ($i=0; $i<80; $i++)
	{
		$a = $i + ($i-1);
	}
}

//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -//
//* * Set not actual time for FRONT-END currnet table cache * *//
function set_not_actual_frontend_table_cache_data($section,$table_name,$params_list,$store_type)
{
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> $section, //"search_template_values",
		"table_name"	=> $table_name, //"template_values",
		"params_list"	=> $params_list, //array(), //Они учавствуют в построении пути (как папки) //"params_list"	=> array("1","b")
		"query"				=> "", //$sql,
		"actual_time"	=> 1, //Время актуальности в милисек.
		"store_type"	=> $store_type //"as_array" //Тип чтения: 1)"as_table" - в таблицу (имя "table_name") 2)"as_array" - в массив 	$cache_data_array
	);
	set_not_actual_mydata_cache($cache_params_array);
}
?>