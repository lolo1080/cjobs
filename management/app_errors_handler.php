<?php
require_once "app_errors_handler_config.php";

function application_errors_log($errno, $errmsg, $file, $line) {
 global $log_info, $app_errors_handler_types;

	//если данная ошибка присутствует в списке игнорируемых ошибок, то выйти
	if (find_this_error_in_ignor_list($file, $errmsg)) return;

	if (!isset($app_errors_handler_types[$errno])) $err_type = 'Unknown error ('.$errno.')';
	else $err_type = $app_errors_handler_types[$errno];

  //формируем новую строку в логе
  $err_str = "***\n";
  $err_str .= date("d.m.Y H:i:s",time()).'||';
  $err_str .= $err_type.'||'; 
  $err_str .= $file.'||';     
  $err_str .= $line.'||'; 
  $err_str .= $errmsg."\n";


  //проверка на максимальный размер
  if (is_file($log_info["application_errors"]) AND filesize($log_info["application_errors"])>=($log_info["application_maxsize"]*1024)) {
  	//проверяем настройки, если установлен лог_ротэйт,
  	//то "сдвигаем" старые файлы на один вниз и создаем пустой лог
  	//если нет - чистим и пишем вместо старого лога
  	if ($log_info["application_log_rotate"]===true) {
  	    $i=1;
  	    //считаем старые логи в каталоге
  	    while (is_file($log_info["application_errors"].'.'.$i)) { $i++; }
          $i--;
  	    //у каждого из них по очереди увеличиваем номер на 1
  	    while ($i>0) {
  		   @rename($log_info["application_errors"].'.'.$i,$log_info["application_errors"].'.'.(1+$i--));
  	    }
  	    @rename ($log_info["application_errors"],$log_info["application_errors"].'.1');
  	    @touch($log_info["application_errors"]);
  	}
  	elseif(is_file($log_info["application_errors"])) {
  	    //если пишем логи сверху, то удалим 
  	    //и создадим заново пустой файл
  	    @unlink($log_info["application_errors"]);
  	    @touch($log_info["application_errors"]);
  	}
  }

  /*
  проверяем есть ли такой файл
  если нет - можем ли мы его создать
  если есть - можем ли мы писать в него
  */
  if(!is_file($log_info["application_errors"])) {
  	if (!@touch($log_info["application_errors"])) {
  	    trigger_error ('can\'t create log file');
  	}
  }
  elseif(!is_writable($log_info["application_errors"])) {
  	trigger_error ('can\'t write to log file');
  }

  //обратите внимание на функцию, которой мы пишем лог.
  @error_log($err_str.dump_appvalues()."\n\n", 3, $log_info["application_errors"]);
	@chmod($log_info["application_errors"],0777);

	//отправить сообщенеи об ошибке на почту
	if ($log_info["send_email_error_notification"]) send_error_notification($err_str);

	//режим разработки приложения. показать ошибку в браузере
	if ($log_info["use_developer_error_mode"]) echo $err_str.'<br />';
}

function dump_appvalues()
{
 global $smarty;

	//список переменных из массива SERVER к-рые надо поместить в лог
	$log_server_values = array(
		'HTTP_HOST','HTTP_COOKIE','SERVER_NAME','SERVER_ADDR','SERVER_PORT','REMOTE_ADDR',
		'SCRIPT_FILENAME','REQUEST_METHOD','QUERY_STRING','REQUEST_URI','SCRIPT_NAME'
	);

	$dump_str = "";

	//добавляем переменных из массива SERVER в лог
	for($i=0; $i<count($log_server_values); $i++)
	{
		$dump_str .= 'GLOBALS["_SERVER"]["'.$log_server_values[$i].'"] => '.( isset($GLOBALS["_SERVER"][$log_server_values[$i]]) ? $GLOBALS["_SERVER"][$log_server_values[$i]] : " -- ")."\n";
	}

	//добавляем массивы в лог
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
  проверяем есть ли такой файл
  если нет - можем ли мы его создать
  если есть - можем ли мы писать в него
  */
  if(!is_file($log_info["last_email_error_notification_time_file"])) {
  	if (!@touch($log_info["last_email_error_notification_time_file"])) {
  	    trigger_error ('can\'t create email notification file');
  	}
  }
  elseif(!is_writable($log_info["last_email_error_notification_time_file"])) {
  	trigger_error ('can\'t write to email notification file');
  }

	//читаем последнее время отправки почты
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
	//выключаем вывод ошибок в стандартный поток
	ini_set('display_errors',1);

	//повышаем error_reporting до максимума
	error_reporting(E_ALL);

	//заменяем хэндлер ошибок на нашу функцию
	set_error_handler('application_errors_log');
}
?>