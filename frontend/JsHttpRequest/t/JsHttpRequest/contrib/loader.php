<?php
//
// ��������! �� ����������� ���������� � ������� �� ������ ���� ��������
// �� ������ �������. � ��������� ������ ������� header(), ������������
// �����������, �� ��������� (��. ������������), � ��������� ������.
//

// Turn on all errors.
error_reporting(E_ALL);
ini_set('log_errors', true);
// �������� ������.
session_start();
// ���������� ���������� ���������.
require_once "../../../lib/JsHttpRequest/JsHttpRequest.php";
// ������� ������� ������ ����������.
// ��������� ��������� �������� (�����������!).
$JsHttpRequest =& new JsHttpRequest("windows-1251");
// �������� ������.
$q = @$_REQUEST['q'];
// ��������� ��������� ����� � ���� PHP-�������!
$_RESULT = array(
  "q"      => JsHttpRequest::php2js($q),
  "md5"    => md5(is_array($q)? serialize($q) : $q),
  "hello"  => isset($_SESSION['hello'])? $_SESSION['hello'] : null,
  "upload" => print_r($_FILES, 1),
);
if ($q == "session-set") {
    $_SESSION['test'] = "test_value";
} 
// ������������ ���������� ���������.
if (@strpos($q, 'error') !== false) {
  callUndefinedFunction();
}
if (@strpos($q, 'notice') !== false) {
  echo $undefinedVariable;
}
if (@strpos($q, 'object') !== false) {
  $obj = (object)array('a' => 1, 'b' => 2);
  $_RESULT['obj'] = $obj;
}
if (@strpos($q, 'obj_cyr') !== false) {
  class C { var $a, $b; }
  $obj = new C();
  $obj->a = 'english';
  $obj->b = '��������';
  $_RESULT['obj'] = $obj;
}
if (@strpos($q, 'memory_limit') !== false) {
  while (1) $buf[] = str_repeat('a', 10000);
}
if (@$_REQUEST['dt']) {
  sleep($_REQUEST['dt']);
}
// Do NOT write Content-type here: IE ommits it for ActiveX!
?>
<?php if (!$JsHttpRequest->ID) {?>Zero loading ID: yes<?php echo "\n"; }?>
QUERY_STRING: <?php =$_SERVER['QUERY_STRING'] . "\n"?>
Request method: <?php =$_SERVER['REQUEST_METHOD'] . "\n"?>
Loader used: <?php =$JsHttpRequest->LOADER . "\n"?>
Uploaded file size: <?php =@$_FILES['file']['size'] . "\n"?>
_GET: <?php =print_r($_GET, 1)?>
_POST: <?php =print_r($_POST, 1)?>
_FILES: <?php =preg_replace('/(\[(name|size|tmp_name|type)\].*?)(\S+)$/m', '$1***', print_r($_FILES, 1))?>
<?php if ($q == "session-get") {?>_SESSION[test]: <?=@$_SESSION['test']?><?}?>
