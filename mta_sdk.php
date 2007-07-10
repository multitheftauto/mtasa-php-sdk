<?php
/**
************************************
* MTA PHP SDK
************************************
*
* @copyright	Copyright (C) 2007, Multi Theft Auto
* @author		JackC, eAi
* @link			http://www.mtasa.com
* @version		0.2
*/

class mta
{
	private $useCurl = false;
	private $sockTimeout = 6; // seconds
	
	public $http_username = '';
	public $http_password = '';
	
	public function callFunction( $host, $port, $resource, $function )
	{
		$val = array();
		
		for ( $i = 4; $i < func_num_args(); $i++ )
		{
			$val[$i-4] = func_get_arg($i);
	    }
	    
		$val = $this->convertFromObjects($val);
		$json_output = json_encode($val);
		$path = "/" . $resource . "/call/" . $function;
		$result = $this->do_post_request( $host, $port, $path, $json_output );
		$out = $this->convertToObjects( json_decode( $result, true ) );
		
		return (is_array($out)) ? $out : false;
	}
	
	public function convertToObjects( $item )
	{
		if ( is_array($item) )
		{
			foreach ( $item as &$value ) 
			{
				$value = $this->convertToObjects( $value );
			}
		}
		else if ( is_string($item) )
		{	
			if ( substr( $item, 0, 3 ) == "^E^" )
			{
				$item = new Element( substr( $item, 3 ) );
			}
			elseif ( substr( $item, 0, 3 ) == "^R^" )
			{
				$item = new Resource( substr( $item, 3 ) );
			}
		}
		
		return $item;
	}
	
	public function convertFromObjects( $item )
	{
		if ( is_array($item) )
		{
			foreach ( $item as &$value ) 
			{
				$value = $this->convertFromObjects($value);
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
	
	function do_post_request( $host, $port, $path, $json_data )
	{
		if ( $this->useCurl )
		{
			$ch = curl_init();   
			curl_setopt( $ch, CURLOPT_URL, "http://{$host}:{$port}{$path}" ); 
			curl_setopt( $ch, CURLOPT_POST, 1 );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data );
			$result = curl_exec($ch);    
			curl_close($ch); 
			return $result;
		}
		else
		{
			if ( !$fp = @fsockopen( $host, $port, $errno, $errstr, $this->sockTimeout ) )
			{
				throw new Exception( "Could not connect to {$host}:{$port}" );
			}

			$out = "POST {$path} HTTP/1.0\r\n";
			$out .= "Host: {$host}:{$port}\r\n";
			
			if ( $this->http_username && $this->http_password )
			{
				$out .= "Authorization: Basic " . base64_encode( "{$this->http_username}:{$this->http_password}" ) . "\r\n";
			}
			
			$out .= "Content-Length: " . strlen($json_data) . "\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n\r\n";
			//$out .= "Connection: close\r\n\r\n";
			$out .= $json_data . "\r\n\r\n";
			
			if ( !fputs( $fp, $out ) )
			{
				throw new Exception( "Unable to send request to {$host}:{$port}" );
			}
			
			@stream_set_timeout( $fp, $this->sockTimeout );
			$status = @socket_get_status($fp);
			
			$response = '';
			
			while ( !feof($fp) && !$status['timed_out'] )
			{
				$response .= fgets( $fp, 128 );
				$status = socket_get_status($fp);
			}
			
			fclose( $fp );
			
			$tmp = explode( "\r\n\r\n", $response, 2 );
			$headers = $tmp[0];
       		$response = trim($tmp[1]);
       		
       		preg_match( "/HTTP\/1.(?:0|1)\s*([0-9]{3})/", $headers, $matches );
       		$statusCode = intval($matches[1]);
       		
       		if ( $statusCode != 200 )
       		{
       			switch( $statusCode )
       			{
       				case 401:
       					throw new Exception( "Access Denied. This server requires authentication. Please ensure that a valid username and password combination is provided." );
       				break;
       				
       				case 404:
       					throw new Exception( "There was a problem with the request. Ensure that the resource exists and that the name is spelled correctly." );
       				break;
       			}
       		}
       		
       		if ( preg_match( "/^error/i", $response ) )
       		{
       			throw new Exception( ucwords( preg_replace("/^error:?\s*/i", "", $response ) ) );
       		}
			
			return $response;
		}
	}
}

class Element
{
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

class Resource
{
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
?>