<?
include "class.xml2array.php";
include "php45fix.php";
$xml = new xml2array('feed.xml');
print_R($xml->getResult());
?>