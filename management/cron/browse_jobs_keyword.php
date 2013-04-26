<?
$adminsite_script_dir = dirname(__FILE__)."/../";
include_once $adminsite_script_dir."consts.php"; //Include main consts.php file (from frontend area)
require_once $adminsite_script_dir."app_errors_handler.php";
require_once $adminsite_script_dir."app_cache_functions.php";
require_once $adminsite_script_dir."language.php";
require_once $adminsite_script_dir."connect.inc";
require_once $adminsite_script_dir."include/functions/functions_main.php";

// Строит дерево слов, которые искали: Browse Jobs - Keyword.
define("sect_cnt", $browse_keyword_sect_cnt);  //First page section count (10)
define("sub_sect_cnt", $browse_keyword_sub_sect_cnt);  //First page section count (5)
define("more_sub_sect_cnt", $browse_keyword_more_sub_sect_cnt);  //All pages (except first) section count (200)

function clear_temp_tables()
{
 global $db_tables;
	mysql_query("DELETE FROM ".$db_tables["browse_keyword_temp"]) or query_die(__FILE__,__LINE__,mysql_error());
	mysql_query("DELETE FROM ".$db_tables["browse_keyword_most_popular_temp"]) or query_die(__FILE__,__LINE__,mysql_error());
}

function build_browse_keyword_list()
{
 global $db_tables,$text_info,$browse_keywords_info,$K;
	/* PART 1 */
	/* * * Select keywords count * * */
	$qr_res = mysql_query("SELECT count(DISTINCT keyword) as num FROM ".$db_tables["stats_search_success_keywords"]) or query_die(__FILE__,__LINE__,mysql_error());
	$myrow = mysql_fetch_array($qr_res);
	$total_count = $myrow["num"];
	if ($total_count == 0) { none_critical_notice(__FILE__,__LINE__,'Total keywords count == 0'); return; }

	if ($total_count < (sect_cnt*sub_sect_cnt)) { none_critical_notice(__FILE__,__LINE__,'We still have small amount of keywords'); return; }

/*
  -- section start
 | -- sub-section
 | -- sub-section
 | -- ...
  --
*/
	$sect_count = floor($total_count/sect_cnt); //How many keywords we have in each section
	$sub_sect_count = floor($sect_count/sub_sect_cnt); //How many keywords we have in each sub-section

	/* * * Get section keywords * * */
	$sql = array();
	for ($i=0; $i<sect_cnt; $i++)
	{
		$sect_start = ($i*$sect_count);
		//Last Sub-Section end
		if ($i>0)	$sql[] = "(SELECT DISTINCT keyword FROM ".$db_tables["stats_search_success_keywords"]." ORDER BY keyword LIMIT ".($sect_start-1).",1)";
		//Section start
		$sql[] = "(SELECT DISTINCT keyword FROM ".$db_tables["stats_search_success_keywords"]." ORDER BY keyword LIMIT ".$sect_start.",1)";
		for ($j=1; $j<sub_sect_cnt; $j++)
		{
			$sub_sect_start = $sect_start+($j*$sub_sect_count);
			//Sub-Section end
			$sql[] = "(SELECT DISTINCT keyword FROM ".$db_tables["stats_search_success_keywords"]." ORDER BY keyword LIMIT ".($sub_sect_start-1).",1)";
			//Sub-Section start
			$sql[] = "(SELECT DISTINCT keyword FROM ".$db_tables["stats_search_success_keywords"]." ORDER BY keyword LIMIT ".$sub_sect_start.",1)";
		}
	}
	$sql[] = "(SELECT DISTINCT keyword FROM ".$db_tables["stats_search_success_keywords"]." ORDER BY keyword DESC LIMIT 0,1)";
	$i = 0; $K = $S = -1; $sk = ""; $ssk = -1;
	$qr_res = mysql_query(implode("UNION",$sql)." ORDER BY keyword") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		if (($i % sub_sect_cnt) == 0) {
			$K = $S;
			$browse_keywords[++$K] = array();
			$browse_keywords_info[$K] = array("type"=>0,"level"=>0,"keyword"=>substr(data_addslashes($myrow["keyword"]),0,2));
			if ($ssk > -1) $browse_keywords_info[$ssk]["keyword"] = $browse_keywords_info[$ssk]["keyword"]." - ".substr($browse_keywords_info[$S]["end"],0,2);
			$ssk = $K;
			$S = $K;
		}
		$browse_keywords[$K][++$S] = array();
		$browse_keywords_info[$S] = array("type"=>1,"level"=>1,"start"=>data_addslashes($myrow["keyword"]));
		$myrow = mysql_fetch_array($qr_res);
		$browse_keywords_info[$S]["end"] = $sk = data_addslashes($myrow["keyword"]);
		$i++;
	}
	if ($ssk > -1) $browse_keywords_info[$ssk]["keyword"] = $browse_keywords_info[$ssk]["keyword"]." - ".substr($browse_keywords_info[$S]["end"],0,2);

	/* PART 2 */
	if ($S > $K) $K = $S;
	foreach ($browse_keywords as $k=>$v)
	{
		//INSERT
		mysql_query("INSERT INTO ".$db_tables["browse_keyword_temp"]." VALUES(NULL,{$browse_keywords_info[$k]["type"]},{$browse_keywords_info[$k]["level"]},0,'','{$browse_keywords_info[$k]["keyword"]}')") or query_die(__FILE__,__LINE__,mysql_error());
		build_sub_browse_keyword_list($v,mysql_insert_id(),'');
	}
}

function build_sub_browse_keyword_list($v,$parent,$path)
{
 global $db_tables,$text_info,$browse_keywords_info,$K;
	foreach ($v as $k=>$v1)
	{
		//INSERT RANGE
		$ins_path = ($path == "") ? $parent : $path.';'.$parent;
		mysql_query("INSERT INTO ".$db_tables["browse_keyword_temp"]." VALUES(NULL,{$browse_keywords_info[$k]["type"]},{$browse_keywords_info[$k]["level"]},$parent,'$ins_path','{$browse_keywords_info[$k]["start"]}...{$browse_keywords_info[$k]["end"]}')") or query_die(__FILE__,__LINE__,mysql_error());
		$parent_new = mysql_insert_id();
		$level = $browse_keywords_info[$k]["level"];
		//INSERT MOST POPULAR
		insert_most_popular_keyword($k,$parent_new);

		/* * * Select subsection keywords count * * */
		$k_str = "keyword>='{$browse_keywords_info[$k]["start"]}' and keyword<='{$browse_keywords_info[$k]["end"]}'";
		$cnt_sql_query = "SELECT count(DISTINCT keyword) as num FROM ".$db_tables["stats_search_success_keywords"]." WHERE $k_str ORDER BY keyword";
		$qr_res = mysql_query($cnt_sql_query) or query_die(__FILE__,__LINE__,mysql_error());
		$myrow = mysql_fetch_array($qr_res);
		$sub_sect_count = $myrow["num"];
		if ($sub_sect_count == 0) { none_critical_notice(__FILE__,__LINE__,'Total sub-section keywords count == 0. k='.$k.'. Query: '.$cnt_sql_query); continue; }

		if (($sub_sect_count > more_sub_sect_cnt) && (floor($sub_sect_count/more_sub_sect_cnt) > 1)) {
			/* * * Select subsection sections count * * */
			$sub_sub_sect_count = floor($sub_sect_count/more_sub_sect_cnt); //How many keywords we have in each sub-sub-section
			$sub_sub_sect_start = 0;
			$k_str = "keyword>='{$browse_keywords_info[$k]["start"]}' and keyword<='{$browse_keywords_info[$k]["end"]}'";
			//Sub-Section start(first sub-sub-section)
			$sql = array();
			$sql[] = "(SELECT DISTINCT keyword FROM ".$db_tables["stats_search_success_keywords"]." WHERE $k_str ORDER BY keyword LIMIT 0,1)";
			for ($i=1; $i<more_sub_sect_cnt; $i++)
			{
				$sub_sub_sect_start = $i*$sub_sub_sect_count;
				//Sub-Section end
				$sql[] = "(SELECT DISTINCT keyword FROM ".$db_tables["stats_search_success_keywords"]." WHERE $k_str ORDER BY keyword LIMIT ".($sub_sub_sect_start-1).",1)";
				//Sub-Section start
				$sql[] = "(SELECT DISTINCT keyword FROM ".$db_tables["stats_search_success_keywords"]." WHERE $k_str ORDER BY keyword LIMIT ".$sub_sub_sect_start.",1)";
			}
			//Sub-Section end(last sub-sub-section)
			$sql[] = "(SELECT DISTINCT keyword FROM ".$db_tables["stats_search_success_keywords"]." WHERE $k_str ORDER BY keyword DESC LIMIT 0,1)";

			/* * * Select subsection sections count * * */
			$qr_res = mysql_query(implode("UNION",$sql)." ORDER BY keyword") or query_die(__FILE__,__LINE__,mysql_error());
			$browse_keywords = array();
			while ($myrow = mysql_fetch_array($qr_res))
			{
				$browse_keywords[++$K] = array();
				$browse_keywords_info[$K] = array("type"=>1,"level"=>($level+1),"start"=>data_addslashes($myrow["keyword"]),"end"=>"");
				$myrow = mysql_fetch_array($qr_res);
				$browse_keywords_info[$K]["end"] = data_addslashes($myrow["keyword"]);
 			}

			build_sub_browse_keyword_list($browse_keywords,$parent_new,$ins_path);
		}
		else {
			$qr_res = mysql_query("SELECT DISTINCT keyword FROM ".$db_tables["stats_search_success_keywords"]." WHERE $k_str ORDER BY keyword") or query_die(__FILE__,__LINE__,mysql_error());
			while ($myrow = mysql_fetch_array($qr_res))
			{
				$browse_keywords[++$K] = array();
				$browse_keywords_info[$K] = array("type"=>2,"level"=>($level+1),"link"=>data_addslashes($myrow["keyword"]));
				mysql_query("INSERT INTO ".$db_tables["browse_keyword_temp"]." VALUES(NULL,{$browse_keywords_info[$K]["type"]},{$browse_keywords_info[$K]["level"]},$parent_new,'$ins_path','{$browse_keywords_info[$K]["link"]}')") or query_die(__FILE__,__LINE__,mysql_error());
			}
		}
	}
}

function insert_browse_keyword_list(&$sql)
{
 global $db_tables;
	if (strlen($sql) > 0) $sql = substr($sql, 0, -1);
	else return;
	mysql_query("INSERT INTO ".$db_tables["browse_keyword"]." VALUES ".$sql) or query_die(__FILE__,__LINE__,mysql_error());
}

function write_browse_keyword_list()
{
 global $db_tables,$text_info;
	mysql_query("LOCK TABLES ".$db_tables["browse_keyword"]." WRITE, ".$db_tables["browse_keyword_temp"]." WRITE") or query_die(__FILE__,__LINE__,mysql_error());
	mysql_query("DELETE FROM ".$db_tables["browse_keyword"]) or query_die(__FILE__,__LINE__,mysql_error());
	$qr_res = mysql_query("SELECT * FROM ".$db_tables["browse_keyword_temp"]." ORDER BY level") or query_die(__FILE__,__LINE__,mysql_error());
	$level = 0; $sql = "";
	while ($myrow = mysql_fetch_array($qr_res))
	{
		if ($level <> $myrow["level"]) {
			insert_browse_keyword_list($sql);
			$sql = ""; $level = $myrow["level"];
		}
		$sql .= "({$myrow["kid"]},{$myrow["type"]},{$myrow["level"]},{$myrow["parent"]},'{$myrow["path"]}','{$myrow["link"]}'),";
	}
	if ($sql != "") insert_browse_keyword_list($sql);
	set_not_actual_frontend_table_cache_data("browse_jobs","browse_keyword",array(""),"as_array");
	mysql_query("UNLOCK TABLES") or query_die(__FILE__,__LINE__,mysql_error());
}

//Insert most popular keyword for current section
function insert_most_popular_keyword($k,$kid)
{
 global $db_tables,$browse_keywords_info;
	$k_str = "keyword>='{$browse_keywords_info[$k]["start"]}' and keyword<='{$browse_keywords_info[$k]["end"]}'";
	$sql = "SELECT keyword, count(keyword) as num FROM ".$db_tables["stats_search_success_keywords"]." ".
				"WHERE $k_str GROUP BY keyword HAVING num>1 ORDER BY num DESC LIMIT 10";
	$qr_res = mysql_query($sql) or query_die(__FILE__,__LINE__,mysql_error());
	$sql = "";
	while ($myrow = mysql_fetch_array($qr_res))
	{
		if ($myrow["keyword"] == "") continue;
		$sql .= "('$kid','{$myrow["keyword"]}'),";
	}
	if (strlen($sql) > 0) {
		$sql = substr($sql, 0, -1);
		mysql_query("INSERT INTO ".$db_tables["browse_keyword_most_popular_temp"]." VALUES $sql") or query_die(__FILE__,__LINE__,mysql_error());
	}
}

function write_browse_most_popular_list()
{
 global $db_tables;
	$qr_res = mysql_query("SELECT count(*) as num FROM ".$db_tables["browse_keyword_most_popular_temp"]) or query_die(__FILE__,__LINE__,mysql_error());	
	if (mysql_num_rows($qr_res) > 0) {
		$myrow = mysql_fetch_array($qr_res);
		$num = $myrow["num"];	$i = 0; $S = 500;
		while ($i < $num)
		{
			$sql = "";
			$qr_res = mysql_query("SELECT * FROM ".$db_tables["browse_keyword_most_popular_temp"]." LIMIT $i,$S") or query_die(__FILE__,__LINE__,mysql_error());
			while ($myrow = mysql_fetch_array($qr_res))
			{
				$sql .= "('{$myrow["kid"]}','{$myrow["keyword"]}'),";
			}
			if (strlen($sql) > 0) {
				$sql = substr($sql, 0, -1);
				mysql_query("INSERT INTO ".$db_tables["browse_keyword_most_popular"]." VALUES $sql") or query_die(__FILE__,__LINE__,mysql_error());
			}
			$i += $S;
		}
		set_not_actual_frontend_table_cache_data("browse_jobs","browse_keyword",array(""),"as_array");
	}
}

doconnect();

clear_temp_tables();
build_browse_keyword_list(); //Build list
write_browse_keyword_list(); //Write keywords to table
write_browse_most_popular_list(); //Write most popular to table
clear_temp_tables();
?>