<?php
//Error types
$app_errors_handler_types = array(2=>"Warning", 8=>"Notice", 256=>"User Error", 512=>"User Warning", 1024=>"User Notice");
//Ignor these errors
$app_errors_handler_ignor_list[] = array("file"=>"Smarty_Compiler.class.php", "msg_part"=>"strftime()");
$app_errors_handler_ignor_list[] = array("file"=>"core.write_file.php", "msg_part"=>"unlink");
$app_errors_handler_ignor_list[] = array("file"=>"email_message.php", "msg_part"=>"strftime()");
$app_errors_handler_ignor_list[] = array("file"=>"email_message.php", "msg_part"=>"mail()");
$app_errors_handler_ignor_list[] = array("file"=>"email_message.php", "msg_part"=>'email_message_class::$localhost');
$app_errors_handler_ignor_list[] = array("file"=>"class.get_crypt.php", "msg_part"=>"date()");
$app_errors_handler_ignor_list[] = array("file"=>"manage_templates_func.php", "msg_part"=>"chmod()");
?>