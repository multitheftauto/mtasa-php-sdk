<?
$useCurl = false;

class Element {
	var $id;

	function Element($id)
	{
		$this->id = $id;
	}

	function toString()
	{
		return "^E^" . $this->id;
	}
}

class Resource {
	var $name;

	function Resource($name)
	{
		$this->name = $name;
	}

	function toString()
	{
		return "^R^" . $this->name;
	}
}

function callFunction ($server, $resource, $function) {
	$val = array();
	for ($i = 3;$i < func_num_args();$i++) {
		$val[$i-3] = func_get_arg($i);
    }
	$val = convertFromObjects($val);
	$json_output = json_encode($val);

	$URL= $server . "/" . $resource . "/call/" . $function ;
	$result = do_post_request($URL, $json_output);
	return convertToObjects(json_decode ( $result, true ));
}

function convertToObjects ( $item )
{
	if ( is_array($item) )
	{
		foreach ($item as &$value) 
		{
			$value = convertToObjects($value);
		}
	}
	else if ( is_string($item) )
	{	
		if ( substr($item, 0, 3) == "^E^" )
		{
			$item = new Element(substr($item,3));
		}
		elseif ( substr($item, 0, 3) == "^R^" )
		{
			$item = new Resource(substr($item,3));
		}
	}
	return $item;
}

function convertFromObjects ( $item )
{
	if ( is_array($item) )
	{
		foreach ($item as &$value) 
		{
			$value = convertFromObjects($value);
		}
	}
	elseif ( is_object($item) )
	{	
		if ( get_class($item) == "Element" || get_class($item) == "Resource" )
		{
			$item = $item->toString();
		}
	}
	return $item;
}

function do_post_request($url, $data)
{
	if ( $useCurl )
	{
		$ch = curl_init();   
		curl_setopt($ch, CURLOPT_URL,"http://$url"); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec ($ch);    
		curl_close ($ch); 
		return $result;
	}
	else
	{
		 $params = array('http' => array(
					  'method' => 'POST',
					  'content' => $data
				   ));

		 $ctx = stream_context_create($params);
		 $fp = @fopen("http://$url", 'rb', false, $ctx);
		 if (!$fp) {
			throw new Exception("Problem with $url, $php_errormsg");
		 }
		 $response = @stream_get_contents($fp);
		 if ($response === false) {
			throw new Exception("Problem reading data from $url, $php_errormsg");
		 }
		 return $response;
	}
}
?>