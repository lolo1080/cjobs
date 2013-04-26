<?
define("sitemap_records_cnt", 45000); //records count in sitemap file
define("sitemap_basename", "jobsmap"); //base site map file name: jobsmap1.xml, jobsmap2.xml, ...
define("index_sitemap_file", "indexsitemap.xml"); //index sitemap ile name

$adminsite_script_dir = dirname(__FILE__)."/../";
include_once $adminsite_script_dir."consts.php"; //Include main consts.php file (from frontend area)
require_once $adminsite_script_dir."app_errors_handler.php";
require_once $adminsite_script_dir."language.php";
require_once $adminsite_script_dir."connect.inc";
require_once $adminsite_script_dir."include/functions/functions_main.php";
require_once $adminsite_script_dir."functions.php";


doconnect();

function delete_sitemap_files($file)
{
	global $sitemaps_list;
	if (is_dir($file)) {
		$handle = opendir($file); 
		while($filename = readdir($handle))
		{
			if (($filename != ".") && ($filename != "..") && ($filename != index_sitemap_file) && preg_match("/".sitemap_basename."\d+.+/i", $filename)) {
				$f = fopen($file.'/'.$filename,'w');
				fclose($f);
				//unlink($file);
				sleep(1);
			}
		}
		closedir($handle);
	} 
}

function get_sitemaps_list($file)
{
	global $sitemaps_list;
	if (is_dir($file)) {
		$handle = opendir($file); 
		while($filename = readdir($handle))
		{
			if (($filename != ".") && ($filename != "..") && ($filename != index_sitemap_file) && preg_match("/".sitemap_basename."\d+.+/i", $filename)) {
				if (filesize($file.'/'.$filename) > 0) { $sitemaps_list[] = $filename; }
			}
		}
		closedir($handle);
	} 
}

//Delete old sitemap files
delete_sitemap_files(substr($sitemap_dir,0,-1));

//Check settings
get_global_settings();

//Create the list of sitemap files
$K = 0;
$data_from = 0;
$isres = true;
while ($isres) {
	$isres = false;
	$K++;

	$f = fopen($sitemap_dir.sitemap_basename.$K.'.xml','w');
	fwrite($f,'<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n");
	$qr_res = mysql_query("SELECT *,DATE_FORMAT(dateinsert,'%Y-%m-%d') as lastmod FROM ".$db_tables["data_list"]." ORDER BY dateinsert DESC LIMIT $data_from,".sitemap_records_cnt) or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$isres = true;
		fwrite($f,'<url><loc>'.$_SESSION["globsettings"]["site_url"].'onejob-details/?data-id='.$myrow['data_id'].'</loc><lastmod>'.$myrow['lastmod'].'</lastmod></url>'."\n");
	}
	fwrite($f,'</urlset>');
	fclose($f);

	sleep(1);
	//chmod($sitemap_dir.sitemap_basename.$K.'.xml',0777);

	if (!$isres) {
//		unlink($sitemap_dir.sitemap_basename.$K.'.xml');
		$f = fopen($sitemap_dir.sitemap_basename.$K.'.xml','w');
		fclose($f);
	}

	$data_from += sitemap_records_cnt;
}

//Get all sitemaps list
$sitemaps_list = array();
get_sitemaps_list(substr($sitemap_dir,0,-1));


//Create sitemap inxed
if (function_exists('date_default_timezone_set')) date_default_timezone_set($usersettings["timezone_identifier"]);
$f = fopen($sitemap_dir.index_sitemap_file,'w');
fwrite($f,'<?xml version="1.0" encoding="UTF-8"?>'."\n".'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n");
for($i=0; $i<count($sitemaps_list); $i++)
{
	fwrite($f,'<sitemap><loc>'.$_SESSION["globsettings"]["site_url"].$sitemap_url_part.$sitemaps_list[$i].'</loc><lastmod>'.date("Y-m-d",filemtime($sitemap_dir.$sitemaps_list[$i])).'</lastmod></sitemap>'."\n");
}
fwrite($f,'</sitemapindex>');
fclose($f);
?>