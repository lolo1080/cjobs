<?
function get_global_settings()
{
 global $db_tables;
	// * * Check cache * * //
	$sql = "SELECT * FROM ".$db_tables["globsettings"];
	$cache_params_array = array(
		"user"				=> 3, //$_SESSION["sess_user"]
		"cache_group"	=> "smarty_frontend",
		"userid"			=> 0, //$_SESSION["sess_userid"]
		"section"			=> "homepage_template_primitives",
		"table_name"	=> "globsettings",
		"params_list"	=> array(""), //Ž­¨ ãç ¢áâ¢ãîâ ¢ ¯®áâà®¥­¨¨ ¯ãâ¨ (ª ª ¯ ¯ª¨) //"params_list"	=> array("1","b")
		"query"				=> $sql,
		"actual_time"	=> 20*60, //‚à¥¬ï  ªâã «ì­®áâ¨ ¢ á¥ª.
		"store_type"	=> "as_array" //’¨¯ çâ¥­¨ï: 1)"as_table" - ¢ â ¡«¨æã (¨¬ï "table_name") 2)"as_array" - ¢ ¬ áá¨¢ 	$cache_data_array
	);
	
	// if do not use cache OR cannot read cache OR cache is not actual -- get data from database and insert into table or array
	$data_array = array();
	if (!read_mydata_cache($cache_params_array,$data_array)) {
		//Get global settings list 		
		$qr_res = mysql_query($cache_params_array["query"]) or query_die(__FILE__,__LINE__,mysql_error());
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$data_array[$myrow["settings_name"]] = $myrow["settings_value"];
		}
		
	}
	
	if (!isset($_SESSION["globsettings"]["selected_country"])) $data_array["selected_country"] = "all";
	else $data_array["selected_country"] = $_SESSION["globsettings"]["selected_country"];
	
	$_SESSION["globsettings"] = $data_array;

	//�âã áâà®ªã ã¡à âì - ¥á«¨ ­ ¤® ¤«ï «î¡®© áâà ­ë
	if ($_SESSION["globsettings"]["selected_country"] == "all") $_SESSION["globsettings"]["selected_country"] = "us";

	// * * Write cache * * //
	//if use cache - save data
	write_mydata_cache($cache_params_array,$data_array);
}?>