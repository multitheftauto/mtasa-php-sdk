<?php

use MultiTheftAuto\Sdk\Mta;
use MultiTheftAuto\Sdk\Authentication\CredentialTest;
use MultiTheftAuto\Sdk\Model\Server;

// =============================
// (Config) HTTP Username / Password
// =============================

$http_username = '';
$http_password = '';

// =============================
// No need to edit below this line
// =============================

$host     = $_POST['host'];
$port     = (int) ($_POST['port']);
$resource = $_POST['resource'];
$function = $_POST['func'];
$val      = $_POST['val'];

try {
	if ( !$http_username && !$http_password ) {
		throw new Exception('Invalid credentials');
	}

	$server = new Server($host, $port);
	$credential = new CredentialTest($http_username, $http_password);
    $mta = new Mta($server, $credential);

	$val = explode(",", $val);
	$json_data = json_encode($val);
	echo $mta->getResource($resource)->call($function, $json_data);

} catch( Exception $e ) {
	@header('HTTP/1.0 500 Internal Server Error');
	echo $e->getMessage();
}
