<?
include('mta.php');
$server = "localhost:890";

$root = getRootElement();
$type = getElementType($root );
echo "Root element type: $type<br/>";

$resource = getThisResource();
$resourceName = getResourceName($resource );
echo "This resource name: $resourceName";


function getElementType ( $element ) 
{
	global $server;
	$retn = callFunction($server, "echobot", "getElementType", $element );
	return $retn[0];
}

function getRootElement ( ) 
{
	global $server;
	$retn = callFunction($server, "echobot", "getRootElement" );
	return $retn[0];
}

function getThisResource (  ) 
{
	global $server;
	$retn = callFunction($server, "echobot", "getThisResource" );
	return $retn[0];
}

function getResourceName ( $resource ) 
{
	global $server;
	$retn = callFunction($server, "echobot", "getResourceName", $resource );
	return $retn[0];
}
?>