<?php
require_once "app_errors_handler_config.php";

function application_errors_log($errno, $errmsg, $file, $line) {
 global $log_info, $app_errors_handler_types;

	//���� ������ ������ ������������ � ������ ������������ ������, �� �����
	if (find_this_error_in_ignor_list($file, $errmsg)) return;

	if (!isset($app_errors_handler_types[$errno])) $err_type = 'Unknown error ('.$errno.')';
	else $err_type = $app_errors_handler_types[$errno];

  //��������� ����� ������ � ����
  $err_str = "***\n";
  $err_str .= date("d.m.Y H:i:s",time()).'||';
  $err_str .= $err_type.'||'; 
  $err_str .= $file.'||';     
  $err_str .= $line.'||'; 
  $err_str .= $errmsg."\n";


  //�������� �� ������������ ������
  if (is_file($log_info["application_errors"]) AND filesize($log_info["application_errors"])>=($log_info["application_maxsize"]*1024)) {
  	//��������� ���������, ���� ���������� ���_������,
  	//�� "��������" ������ ����� �� ���� ���� � ������� ������ ���
  	//���� ��� - ������ � ����� ������ ������� ����
  	if ($log_info["application_log_rotate"]===true) {
  	    $i=1;
  	    //������� ������ ���� � ��������
  	    while (is_file($log_info["application_errors"].'.'.$i)) { $i++; }
          $i--;
  	    //� ������� �� ��� �� ������� ����������� ����� �� 1
  	    while ($i>0) {
  		   @rename($log_info["application_errors"].'.'.$i,$log_info["application_errors"].'.'.(1+$i--));
  	    }
  	    @rename ($log_info["application_errors"],$log_info["application_errors"].'.1');
  	    @touch($log_info["application_errors"]);
  	}
  	elseif(is_file($log_info["application_errors"])) {
  	    //���� ����� ���� ������, �� ������ 
  	    //� �������� ������ ������ ����
  	    @unlink($log_info["application_errors"]);
  	    @touch($log_info["application_errors"]);
  	}
  }

  /*
  ��������� ���� �� ����� ����
  ���� ��� - ����� �� �� ��� �������
  ���� ���� - ����� �� �� ������ � ����
  */
  if(!is_file($log_info["application_errors"])) {
  	if (!@touch($log_info["application_errors"])) {
  	    trigger_error ('can\'t create log file');
  	}
  }
  elseif(!is_writable($log_info["application_errors"])) {
  	trigger_error ('can\'t write to log file');
  }

  //�������� �������� �� �������, ������� �� ����� ���.
  @error_log($err_str.dump_appvalues()."\n\n", 3, $log_info["application_errors"]);
	@chmod($log_info["application_errors"],0777);

	//��������� ��������� �� ������ �� �����
	if ($log_info["send_email_error_notification"]) send_error_notification($err_str);

	//����� ���������� ����������. �������� ������ � ��������
	if ($log_info["use_developer_error_mode"]) echo $err_str.'<br />';
}

function dump_appvalues()
{
 global $smarty;

	//������ ���������� �� ������� SERVER �-��� ���� ��������� � ���
	$log_server_values = array(
		'HTTP_HOST','HTTP_COOKIE','SERVER_NAME','SERVER_ADDR','SERVER_PORT','REMOTE_ADDR',
		'SCRIPT_FILENAME','REQUEST_METHOD','QUERY_STRING','REQUEST_URI','SCRIPT_NAME'
	);

	$dump_str = "";

	//��������� ���������� �� ������� SERVER � ���
	for($i=0; $i<count($log_server_values); $i++)
	{
		$dump_str .= 'GLOBALS["_SERVER"]["'.$log_server_values[$i].'"] => '.( isset($GLOBALS["_SERVER"][$log_server_values[$i]]) ? $GLOBALS["_SERVER"][$log_server_values[$i]] : " -- ")."\n";
	}

	//��������� ������� � ���
	if (isset($GLOBALS["_GET"])) $dump_str .= 'GLOBALS["_GET"]: '.print_r($GLOBALS["_GET"], true);
	if (isset($GLOBALS["_POST"])) $dump_str .= 'GLOBALS["_POST"]: '.print_r($GLOBALS["_POST"], true);
	if (isset($GLOBALS["_REQUEST"])) $dump_str .= 'GLOBALS["_REQUEST"]: '.print_r($GLOBALS["_REQUEST"], true);
	if (isset($GLOBALS["_SESSION"])) $dump_str .= 'GLOBALS["_SESSION"]: '.print_r($GLOBALS["_SESSION"], true);
	if ($smarty->_tpl_vars) $dump_str .= 'SMARTY->_tpl_vars: '.print_r($smarty->_tpl_vars, true);

 return $dump_str;
}

function send_error_notification($err_str)
{
 global $log_info, $bug_report_email;

  /*
  ��������� ���� �� ����� ����
  ���� ��� - ����� �� �� ��� �������
  ���� ���� - ����� �� �� ������ � ����
  */
  if(!is_file($log_info["last_email_error_notification_time_file"])) {
  	if (!@touch($log_info["last_email_error_notification_time_file"])) {
  	    trigger_error ('can\'t create email notification file');
  	}
  }
  elseif(!is_writable($log_info["last_email_error_notification_time_file"])) {
  	trigger_error ('can\'t write to email notification file');
  }

	//������ ��������� ����� �������� �����
	$last_timeout = array();
	$last_timeout = file($log_info["last_email_error_notification_time_file"]);

	if (!isset($last_timeout[0]) || !is_numeric($last_timeout[0]) || ( (int)(time() - $last_timeout[0]) > $log_info["email_error_notification_timeout"])) {
		@fwrite( fopen($log_info["last_email_error_notification_time_file"], "w"), time());
		@chmod($log_info["last_email_error_notification_time_file"],0777);
		@mail($bug_report_email, $log_info["email_error_notification_subject"], $err_str);
	}
}

function find_this_error_in_ignor_list(&$file, &$errmsg)
{
 global $app_errors_handler_ignor_list;
	for($i=0; $i<count($app_errors_handler_ignor_list); $i++)
	{
		$pos = strpos($file, $app_errors_handler_ignor_list[$i]["file"]);
		if ($pos !== false) {
			$pos = strpos($errmsg, $app_errors_handler_ignor_list[$i]["msg_part"]);
			if ($pos !== false) return true;
		}
	}
 return false;
}


if (isset($log_info["use_apperrors_log"]) && $log_info["use_apperrors_log"]) {
	//��������� ����� ������ � ����������� �����
	ini_set('display_errors',1);

	//�������� error_reporting �� ���������
	error_reporting(E_ALL);

	//�������� ������� ������ �� ���� �������
	set_error_handler('application_errors_log');
}
?>