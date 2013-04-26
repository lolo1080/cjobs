<?
session_start();

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "app_events_handler.php";
require_once "functions.php";
require_once "functions_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "include/functions/functions_main.php";
require_once "include/functions/common_functions.php";
require_once "include/other/table_mini.php";
require_once "feeds_html_func.php";

/*Global value: return result*/
$Result = array('status'=>'ok','html_rawdata'=>'');

function return_error_msg($msg)
{
	global $Result;
	$Result['status'] = 'error';
	$Result['errormsg'][] = $msg;
	echo json_encode($Result); exit;
}

function check_ajax_user_access($users_arr)
{
	if ( !isset($_SESSION["sess_user"]) || $_SESSION["sess_user"] == "" || !in_array($_SESSION["sess_user"], $users_arr) ) return_error_msg('No access');
}

/* * * * * * * */
/* PARSE DATA  */
/* * * * * * * */
function dump_my_html_tree($Rbase, $n, $node, $show_attr=true, $deep=0, $last=true) {
	global $ResListStr,$ResListCnt,$Rres,$text_info;
    $count = count($node->nodes);
    if ($count>0) {
        if($last)
            $ResListStr .= '<li class="expandable lastExpandable"><div class="hitarea expandable-hitarea lastExpandable-hitarea"></div>&lt;<span class="tag">'.htmlspecialchars($node->tag).'</span>';
        else
            $ResListStr .= '<li class="expandable"><div class="hitarea expandable-hitarea"></div>&lt;<span class="tag">'.htmlspecialchars($node->tag).'</span>';
    }
    else {
        $laststr = ($last===false) ? '' : ' class="last"';
        $ResListStr .= '<li'.$laststr.'>&lt;<span class="tag">'.htmlspecialchars($node->tag).'</span>';
    }

    if ($show_attr) {
        foreach($node->attr as $k=>$v) {
            $ResListStr .= ' '.htmlspecialchars($k).'="<span class="attr">'.htmlspecialchars($node->$k).'</span>"';
        }
    }
    //$ResListStr .= '&gt;'.'[<a href="javascript:get_field_data(this,\''.$ResListCnt.'\')">s</a>] ';
		//$Rres[$ResListCnt] = $Rbase.'['.htmlspecialchars($node->tag).']';
		$ResListStr .= '&gt;'.
				'[<a href="javascript:get_field_data(this,\'c\',\''.$Rbase.'['.htmlspecialchars($node->tag).']['.$n.']\')" title="'.$text_info["p_copy_content"].'">c</a>] '.
				'[<a href="javascript:get_field_data(this,\'t\',\''.$Rbase.'['.htmlspecialchars($node->tag).']['.$n.']\')" title="'.$text_info["p_copy_tag"].'">t</a>] ';
				//'[<a href="javascript:get_field_data(this,\'a\',\''.$Rbase.'['.htmlspecialchars($node->tag).']['.$n.']\')" title="'.$text_info["p_copy_all"].'">a</a>] ';
		$ResListCnt++;
    
    if ($node->tag==='text' || $node->tag==='comment') {
        $ResListStr .= htmlspecialchars($node->innertext);
        return;
    }

    if ($count>0) {
			$ResListStr .= "\n<ul style=\"display: none;\">\n";
			$Rbase .= '['.htmlspecialchars($node->tag).']['.$n.']';
		}
    $i=0;
    foreach($node->nodes as $c) {
        $last = (++$i==$count) ? true : false;
        dump_my_html_tree($Rbase, $i, $c, $show_attr, $deep+1, $last);
    }
    if ($count>0)
        $ResListStr .= "</ul>\n";

    //if ($count>0) echo '&lt;/<span class="attr">'.htmlspecialchars($node->tag).'</span>&gt;';
    $ResListStr .= "</li>\n";
}

function parse_html_content(&$my_error)
{
 global $script_dir,$Result,$RPkey,$RPres,$ResListStr,$ResListCnt,$Rres;
	require_once($script_dir.'/html_parser/simple_html_dom.php');

	$html = str_get_html($Result['xml_rawdata']);

	$lang = '';
	$l=$html->find('html', 0);
	if ($l!==null) $lang = $l->lang;
	if ($lang!='') $lang = 'lang="'.$lang.'"';

	$charset = $html->find('meta[http-equiv*=content-type]', 0);
	$target = array();
	$query = '*';
	$target = $html->find($query);

	$ResListStr = '<div id="sidetreecontrol" style="padding-top:10px;padding-bottom:0px;"><a href="?#">Collapse All</a> | <a href="?#">Expand All</a></div><br>'.
		'<ul class="treeview" id="html_tree" style="width:900px; height:300px; overflow: auto; border: 1px solid #666;">';

	$ResListCnt = 0;
	$Rbase = ''; $Rres = array();
	foreach($target as $e)
	{
		dump_my_html_tree($Rbase, 0, $e, true);
	}

	$Result['html_parsedfields'] = $ResListStr;
	//$Result['html_Rres'] = '<script type="text/javascript">var Rres = '.json_encode($Rres).'</script>';
	unset($ResListStr);
}



function check_return_status($nm)
{
	global $return_status, $Error_messages;
	if (!isset($return_status[$nm]) || $return_status[$nm] == "") return_error_msg($Error_messages["no_return_status_".$nm]);
}

function check_return_phpdata_before($nm)
{
	global $Error_messages,$return_status,$return_box,$return_phpcode_before,$return_phpcode_before,$return_phpcodetext_before;
	if (($return_status[$nm] == '1') && (!isset($return_box[$nm]) || $return_box[$nm] == '')) return_error_msg($Error_messages["no_return_box_".$nm]);
	elseif ($return_status[$nm] == '2') {
		if (!isset($return_phpcode_before[$nm]) || $return_phpcode_before[$nm] == "") return_error_msg($Error_messages["no_return_phpcode_before_".$nm]);
		if ($return_phpcode_before[$nm] == '1') {
			if (!isset($return_phpcodetext_before[$nm]) || $return_phpcodetext_before[$nm] == "") return_error_msg($Error_messages["no_return_phpcodetext_before_".$nm]);
		}
	}
}

function check_return_phpdata_after($nm)
{
	global $Error_messages,$return_status,$return_box,$return_phpcode_after,$return_phpcode_after,$return_phpcodetext_after;
	if ($return_status[$nm] == 2) {
		if (!isset($return_phpcode_after[$nm]) || $return_phpcode_after[$nm] == "") return_error_msg($Error_messages["no_return_phpcode_after_".$nm]);
		if ($return_phpcode_after[$nm] == 1) {
			if (!isset($return_phpcodetext_after[$nm]) || $return_phpcodetext_after[$nm] == "") return_error_msg($Error_messages["no_return_phpcodetext_after_".$nm]);
		}
	}
	else return_error_msg($Error_messages["no_return_box_".$nm]);
}

function PrepAVal(&$arr,$key)
{
	if (!isset($arr[$key])) return 0;
	elseif (strtolower($arr[$key]) == 'undefined') return 0;
	else return $arr[$key];
}


doconnect();

$Result['cmd'] = $_POST['cmd'];

try {
	check_access(array(0));

	switch ($_POST['cmd']) {
		case 'get':
			$my_error = '';
//!
			//Get data
			$url = get_post_value("url","");
			if ($url == '') return_error_msg($Error_messages["no_data_url"]);
			//Get url parts
			$url_parts = get_url_parts($my_error,$url);
			if ($my_error != "") return_error_msg($my_error);
			//Get content
			get_site_content($my_error,$url_parts);
			if ($my_error != "") return_error_msg($my_error);
//!
/*
	$f = fopen("cron/data_collection/helper/www.indeed.com.html2.txt","r");
	$content = fread($f, filesize("cron/data_collection/helper/www.indeed.com.html2.txt"));
	fclose($f);
	$Result['xml_rawdata'] = $content;
*/
//!

			//Parse content
			parse_html_content($my_error);
			if ($my_error != "") return_error_msg($my_error);
			//Return data
			$Result['html_rawdata'] = $Result['xml_rawdata'];
			unset($Result['xml_rawdata']);
			//echo json_encode($Result); exit;
			echo php2js($Result); exit;
		break;
		case 'save_configurtion':
			$my_error = '';
			//Get data
			$mode = get_post_value("mode","");
			$mode_data = get_post_value("mode_data","");
			$url = get_post_value("url","");
			$html_parse_regular_expression = "";
			$return_status = get_post_value2("return_status",array());
			addslashes_and_html_array_notrelated($return_status);
			$return_box = get_post_value2("return_box",array());
			addslashes_and_html_array_notrelated($return_box);
			$run_phpcode_before = get_post_value2("run_phpcode_before",array());
			addslashes_and_html_array_notrelated($run_phpcode_before);
			$phpcodetext_before = get_post_value2("phpcodetext_before",array());
			addslashes_and_html_array_notrelated($phpcodetext_before);
			$return_phpcode_before = get_post_value2("return_phpcode_before",array());
			addslashes_and_html_array_notrelated($return_phpcode_before);
			$return_phpcodetext_before = get_post_value2("return_phpcodetext_before",array());
			addslashes_and_html_array_notrelated($return_phpcodetext_before);
			$return_phpcode_after = get_post_value2("return_phpcode_after",array());
			addslashes_and_html_array_notrelated($return_phpcode_after);
			$return_phpcodetext_after = get_post_value2("return_phpcodetext_after",array());
			addslashes_and_html_array_notrelated($return_phpcodetext_after);
			$return_parsed_data = get_post_value2("return_parsed_data",array());
			addslashes_and_html_array_notrelated($return_parsed_data);
			//Type
			if (($mode == 'edit') && (($mode_data == '') || !check_int($mode_data))) return_error_msg($Error_messages["no_mode_data"]);
			//URL
			if ($url != "") {
				$url_ = $url; $url = check_url($url); 
				if ($url === false) return_error_msg($Error_messages["no_data_url"]);
			}
			//Return status
			check_return_status('title');
			check_return_status('company_name');
			check_return_status('locId');
			check_return_status('description');
			check_return_status('url');
			check_return_status('job_type');
			check_return_status('site_type');
			check_return_status('isstaffing_agencies');
			check_return_status('salary');
			check_return_status('nextpage');
			//Return boxes
			check_return_phpdata_before('title');
			check_return_phpdata_before('company_name');
			check_return_phpdata_before('locId');
			check_return_phpdata_before('description');
			check_return_phpdata_before('url');
			check_return_phpdata_before('job_type');
			check_return_phpdata_before('site_type');
			check_return_phpdata_before('isstaffing_agencies');
			check_return_phpdata_before('salary');
			check_return_phpdata_before('nextpage');
			//URL
			$name = get_post_value("name","");
			if ($name == "") return_error_msg($Error_messages["no_data_name"]);

			//Insert
			if ($mode == 'edit') {
			mysql_query("UPDATE ".$db_tables["html_feeds_configuration"]." SET config_name='".$name."',config_url='".$url."',".
//				"html_parse_regular_expression='".data_addslashes_notrelated($html_parse_regular_expression)."',".
				"html_parse_regular_expression='".addslashes($html_parse_regular_expression)."',".
				"title_status='".
				PrepAVal($return_status,'title')."',title_field='".
				PrepAVal($return_box,'title')."',title_run_phpcode_before='".
				PrepAVal($run_phpcode_before,'title')."',title_phpcode_before='".
				PrepAVal($phpcodetext_before,'title')."',title_phpcode='".
				PrepAVal($return_parsed_data,'title')."',title_run_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'title')."',title_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'title')."',company_name_status='".

				PrepAVal($return_status,'company_name')."',company_name_field='".
				PrepAVal($return_box,'company_name')."',company_name_run_phpcode_before='".
				PrepAVal($run_phpcode_before,'company_name')."',company_name_phpcode_before='".
				PrepAVal($phpcodetext_before,'company_name')."',company_name_phpcode='".
				PrepAVal($return_parsed_data,'company_name')."',company_name_run_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'company_name')."',company_name_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'company_name')."',locId_status='".

				PrepAVal($return_status,'locId')."',locId_field='".
				PrepAVal($return_box,'locId')."',locId_run_phpcode_before='".
				PrepAVal($run_phpcode_before,'locId')."',locId_phpcode_before='".
				PrepAVal($phpcodetext_before,'locId')."',locId_phpcode='".
				PrepAVal($return_parsed_data,'locId')."',locId_run_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'locId')."',locId_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'locId')."',description_status='".

				PrepAVal($return_status,'description')."',description_field='".
				PrepAVal($return_box,'description')."',description_run_phpcode_before='".
				PrepAVal($run_phpcode_before,'description')."',description_phpcode_before='".
				PrepAVal($phpcodetext_before,'description')."',description_phpcode='".
				PrepAVal($return_parsed_data,'description')."',description_run_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'description')."',description_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'description')."',url_status='".

				PrepAVal($return_status,'url')."',url_field='".
				PrepAVal($return_box,'url')."',url_run_phpcode_before='".
				PrepAVal($run_phpcode_before,'url')."',url_phpcode_before='".
				PrepAVal($phpcodetext_before,'url')."',url_phpcode='".
				PrepAVal($return_parsed_data,'url')."',url_run_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'url')."',url_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'url')."',job_type_status='".

				PrepAVal($return_status,'job_type')."',job_type_field='".
				PrepAVal($return_box,'job_type')."',job_type_run_phpcode_before='".
				PrepAVal($run_phpcode_before,'job_type')."',job_type_phpcode_before='".
				PrepAVal($phpcodetext_before,'job_type')."',job_type_phpcode='".
				PrepAVal($return_parsed_data,'job_type')."',job_type_run_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'job_type')."',job_type_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'job_type')."',site_type_status='".

				PrepAVal($return_status,'site_type')."',site_type_field='".
				PrepAVal($return_box,'site_type')."',site_type_run_phpcode_before='".
				PrepAVal($run_phpcode_before,'site_type')."',site_type_phpcode_before='".
				PrepAVal($phpcodetext_before,'site_type')."',site_type_phpcode='".
				PrepAVal($return_parsed_data,'site_type')."',site_type_run_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'site_type')."',site_type_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'site_type')."',isstaffing_agencies_status='".

				PrepAVal($return_status,'isstaffing_agencies')."',isstaffing_agencies_field='".
				PrepAVal($return_box,'isstaffing_agencies')."',isstaffing_agencies_run_phpcode_before='".
				PrepAVal($run_phpcode_before,'isstaffing_agencies')."',isstaffing_agencies_phpcode_before='".
				PrepAVal($phpcodetext_before,'isstaffing_agencies')."',isstaffing_agencies_phpcode='".
				PrepAVal($return_parsed_data,'isstaffing_agencies')."',isstaffing_agencies_run_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'isstaffing_agencies')."',isstaffing_agencies_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'isstaffing_agencies')."',salary_status='".

				PrepAVal($return_status,'salary')."',salary_field='".
				PrepAVal($return_box,'salary')."',salary_run_phpcode_before='".
				PrepAVal($run_phpcode_before,'salary')."',salary_phpcode_before='".
				PrepAVal($phpcodetext_before,'salary')."',salary_phpcode='".
				PrepAVal($return_parsed_data,'salary')."',salary_run_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'salary')."',salary_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'salary')."',nextpage_status='".

				PrepAVal($return_status,'nextpage')."',nextpage_field='".
				PrepAVal($return_box,'nextpage')."',nextpage_run_phpcode_before='".
				PrepAVal($run_phpcode_before,'nextpage')."',nextpage_phpcode_before='".
				PrepAVal($phpcodetext_before,'nextpage')."',nextpage_phpcode='".
				PrepAVal($return_parsed_data,'nextpage')."',nextpage_run_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'nextpage')."',nextpage_phpcode_after='".
				PrepAVal($return_phpcodetext_after,'nextpage')."' ".

				"WHERE config_id='".$mode_data."'") or query_die(__FILE__,__LINE__,mysql_error());
				$Result['config_name'] = $name;
				$Result['config_id'] = $mode_data;
			}
			//Edit
			elseif ($mode == 'add') {
			mysql_query("INSERT INTO ".$db_tables["html_feeds_configuration"]." VALUES(NULL,'".$name."','".$url."',".
//				"'".data_addslashes_notrelated($html_parse_regular_expression)."','".
				"'".addslashes($html_parse_regular_expression)."','".
				PrepAVal($return_status,'title')."','".
				PrepAVal($return_box,'title')."','".
				PrepAVal($run_phpcode_before,'title')."','".
				PrepAVal($phpcodetext_before,'title')."','".
				PrepAVal($return_parsed_data,'title')."','".
				PrepAVal($return_phpcodetext_after,'title')."','".
				PrepAVal($return_phpcodetext_after,'title')."','".

				PrepAVal($return_status,'company_name')."','".
				PrepAVal($return_box,'company_name')."','".
				PrepAVal($run_phpcode_before,'company_name')."','".
				PrepAVal($phpcodetext_before,'company_name')."','".
				PrepAVal($return_parsed_data,'company_name')."','".
				PrepAVal($return_phpcodetext_after,'company_name')."','".
				PrepAVal($return_phpcodetext_after,'company_name')."','".

				PrepAVal($return_status,'locId')."','".
				PrepAVal($return_box,'locId')."','".
				PrepAVal($run_phpcode_before,'locId')."','".
				PrepAVal($phpcodetext_before,'locId')."','".
				PrepAVal($return_parsed_data,'locId')."','".
				PrepAVal($return_phpcodetext_after,'locId')."','".
				PrepAVal($return_phpcodetext_after,'locId')."','".

				PrepAVal($return_status,'description')."','".
				PrepAVal($return_box,'description')."','".
				PrepAVal($run_phpcode_before,'description')."','".
				PrepAVal($phpcodetext_before,'description')."','".
				PrepAVal($return_parsed_data,'description')."','".
				PrepAVal($return_phpcodetext_after,'description')."','".
				PrepAVal($return_phpcodetext_after,'description')."','".

				PrepAVal($return_status,'url')."','".
				PrepAVal($return_box,'url')."','".
				PrepAVal($run_phpcode_before,'url')."','".
				PrepAVal($phpcodetext_before,'url')."','".
				PrepAVal($return_parsed_data,'url')."','".
				PrepAVal($return_phpcodetext_after,'url')."','".
				PrepAVal($return_phpcodetext_after,'url')."','".

				PrepAVal($return_status,'job_type')."','".
				PrepAVal($return_box,'job_type')."','".
				PrepAVal($run_phpcode_before,'job_type')."','".
				PrepAVal($phpcodetext_before,'job_type')."','".
				PrepAVal($return_parsed_data,'job_type')."','".
				PrepAVal($return_phpcodetext_after,'job_type')."','".
				PrepAVal($return_phpcodetext_after,'job_type')."','".

				PrepAVal($return_status,'site_type')."','".
				PrepAVal($return_box,'site_type')."','".
				PrepAVal($run_phpcode_before,'site_type')."','".
				PrepAVal($phpcodetext_before,'site_type')."','".
				PrepAVal($return_parsed_data,'site_type')."','".
				PrepAVal($return_phpcodetext_after,'site_type')."','".
				PrepAVal($return_phpcodetext_after,'site_type')."','".

				PrepAVal($return_status,'isstaffing_agencies')."','".
				PrepAVal($return_box,'isstaffing_agencies')."','".
				PrepAVal($run_phpcode_before,'isstaffing_agencies')."','".
				PrepAVal($phpcodetext_before,'isstaffing_agencies')."','".
				PrepAVal($return_parsed_data,'isstaffing_agencies')."','".
				PrepAVal($return_phpcodetext_after,'isstaffing_agencies')."','".
				PrepAVal($return_phpcodetext_after,'isstaffing_agencies')."','".

				PrepAVal($return_status,'salary')."','".
				PrepAVal($return_box,'salary')."','".
				PrepAVal($run_phpcode_before,'salary')."','".
				PrepAVal($phpcodetext_before,'salary')."','".
				PrepAVal($return_parsed_data,'salary')."','".
				PrepAVal($return_phpcodetext_after,'salary')."','".
				PrepAVal($return_phpcodetext_after,'salary')."','".

				PrepAVal($return_status,'nextpage')."','".
				PrepAVal($return_box,'nextpage')."','".
				PrepAVal($run_phpcode_before,'nextpage')."','".
				PrepAVal($phpcodetext_before,'nextpage')."','".
				PrepAVal($return_parsed_data,'nextpage')."','".
				PrepAVal($return_phpcodetext_after,'nextpage')."','".
				PrepAVal($return_phpcodetext_after,'nextpage')."',".

				"NOW(),'html2')") or query_die(__FILE__,__LINE__,mysql_error());

				//Return data
				$Result['cmd'] = 'add';
				$Result['config_name'] = $name;
				$Result['config_id'] = mysql_insert_id();
			}
			//echo json_encode($Result); exit;
			echo php2js($Result); exit;
		case 'edit':
			//Get data
			$config_id = get_post_value("config_id","");
			if (($config_id == "") || !check_int($config_id)) return_error_msg($Error_messages["no_config_data"]);
			//Get configuration by id 
			$Result['config_data'] = get_configuration_by_id($config_id);
			//Return data
			//echo json_encode($Result); exit;
			echo php2js($Result); exit;
		case 'delete':
			//Get data
			$config_id = get_post_value("config_id","");
			if (($config_id == "") || !check_int($config_id)) return_error_msg($Error_messages["no_config_data"]);
			//Delete
			$qr_res = mysql_query("DELETE FROM ".$db_tables["xml_feeds_configuration"]." WHERE config_id='$config_id'") or query_die(__FILE__,__LINE__,mysql_error());
			$Result['status'] = 'ok';
			//Return data
			//echo json_encode($Result); exit;
			echo php2js($Result); exit;
		break;

	}

} catch (Exception $e) {
	echo 'Error: '.$e->getMessage();
}
?>