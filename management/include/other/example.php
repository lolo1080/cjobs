<?
include('rsa.class.php');
$RSA = new RSA();

/* Example */
echo"<i>Keys:</i><br />";
$keys = $RSA->generate_keys ('059045484156', '249095927223', 3);
print_r($keys);

$message="TEST";
$encoded = $RSA->encrypt ($message, $keys[1], $keys[0], 5);
$decoded = $RSA->decrypt ($encoded, $keys[2], $keys[0]);

echo "<b>Message:</b> $message<br />\n";
echo "<b>Encoded:</b> $encoded<br />\n";
echo "<b>Decoded:</b> $decoded<br />\n";
echo "Success: ".(($decoded == $message) ? "True" : "False")."<hr />\n";
?>