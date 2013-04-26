<?
require_once $frontend_dir."lang/lang_settings_{$_SESSION["sess_lang"]}.php";

function get_distance_selectbox_data($distance)
{
 global $radius_array;
  $val = $sel = $capt = array();
	foreach ($radius_array as $k=>$v)
	{
		$val[] = $k;
		$sel[] = ($k == $distance) ? "selected" : "";
		$capt[] = $v["caption"];
	}
 return array("val"=>$val, "sel"=>$sel, "capt"=>$capt);
}

function get_deliver_selectbox_data($deliver)
{
 global $deliver_array;
  $val = $sel = $capt = array();
	foreach ($deliver_array as $k=>$v)
	{
		$val[] = $k;
		$sel[] = ($k == $deliver) ? "selected" : "";
		$capt[] = $v;
	}
 return array("val"=>$val, "sel"=>$sel, "capt"=>$capt);
}

function get_jobs_category_selectbox_data($jobs_category)
{
 global $db_tables, $text_info;
  $val = $sel = $capt = array();
	$data[] = array("cat_id"=>0, "cat_name"=>$text_info["p_all_categories"]);

	$qr_res = mysql_query("SELECT * FROM ".$db_tables["jobcategories"]." ORDER BY cat_name") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		$data[] = array("cat_id"=>$myrow["cat_id"], "cat_name"=>$myrow["cat_name"]);
	}

	foreach ($data as $k=>$v)
	{
		$val[] = $v["cat_id"];
		$sel[] = ($k == $jobs_category) ? "selected" : "";
		$capt[] = $v["cat_name"];
	}
 return array("val"=>$val, "sel"=>$sel, "capt"=>$capt);
}

function get_jobs_type_selectbox_data($jobs_type)
{
 global $jobs_type_array;
  $val = $sel = $capt = array();
	foreach ($jobs_type_array as $k=>$v)
	{
		$val[] = $k;
		$sel[] = ($k == $jobs_type) ? "selected" : "";
		$capt[] = $v["caption"];
	}
 return array("val"=>$val, "sel"=>$sel, "capt"=>$capt);
}

function get_jobs_from_selectbox_data($jobs_from)
{
 global $jobs_from_array;
  $val = $sel = $capt = array();
	foreach ($jobs_from_array as $k=>$v)
	{
		$val[] = $k;
		$sel[] = ($k == $jobs_from) ? "selected" : "";
		$capt[] = $v["caption"];
	}
 return array("val"=>$val, "sel"=>$sel, "capt"=>$capt);
}
?>
