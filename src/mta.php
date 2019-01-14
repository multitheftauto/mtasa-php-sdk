<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        mta.php
 *  VERSION:     0.5
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk;

use Exception;
use InvalidArgumentException;
use MultiTheftAuto\Sdk\Model\Element;
use MultiTheftAuto\Sdk\Model\Resource;
use MultiTheftAuto\Sdk\Authentication\Credential;
use MultiTheftAuto\Sdk\Model\Server;

class mta
{
    protected $useCurl = false;
    protected $sockTimeout = 6; // seconds

    protected $server;
    protected $credential;

    protected $resources = [];

    public function __construct(Server $server, Credential $credential)
    {
        $this->server = $server;
        $this->credential = $credential;
    }

    public function getResource(string $resourceName)
    {
        foreach ($this->resources as $resource) {
            if ($resource->getName() == $resourceName) {
                return $resource;
            }
        }

        $resource = new Resource($resourceName, $this);
        $this->resources[] = $resource;

        return $resource;
    }

    public static function getInput()
    {
        $input = file_get_contents('php://input');
        if (!$input) {
            return false;
        }

        $inputArray = json_decode($input, true);
        return mta::convertToObjects($inputArray)?? false;
    }

    public static function doReturn(...$arguments)
    {
        $arguments = mta::convertFromObjects($arguments);
        echo json_encode($arguments);
    }

    public function callFunction(string $resourceName, string $function, array $arguments = null)
    {
        $json_output = $arguments? mta::convertFromObjects(json_encode($arguments)) : '';
        $path = sprintf('/%s/call/%s', $resourceName, $function);
        $result = $this->do_post_request($path, $json_output);
        $out = mta::convertToObjects(json_decode($result, true));

        return $out?? false;
    }

    public static function convertToObjects($item)
    {
        if (is_array($item)) {
            foreach ($item as &$value) {
                $value = mta::convertToObjects($value);
            }
        } elseif (is_string($item)) {
            if (substr($item, 0, 3) == '^E^') {
                $item = new Element(substr($item, 3));
            } elseif (substr($item, 0, 3) == '^R^') {
                $item = new Resource(substr($item, 3));
            }
        } else {
            throw new InvalidArgumentException('Bad argument at convertToObjects');
        }

        return $item;
    }

    public static function convertFromObjects($item)
    {
        if (is_array($item)) {
            foreach ($item as &$value) {
                $value = mta::convertFromObjects($value);
            }
        }

        return (string) $item;
    }

    public function do_post_request($path, $json_data)
    {
        if ($this->useCurl) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "{$this->server->getBaseUri()}{$path}");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            $result = curl_exec($ch);
            curl_close($ch);

            return $result;
        }

        if (!$fp = @fsockopen($this->server->getHost(), $this->server->getPort(), $errno, $errstr, $this->sockTimeout)) {
            throw new Exception("Could not connect to {$this->server->getHost()}:{$this->server->getPort()}");
        }

        $out = "POST {$path} HTTP/1.0\r\n";
        $out .= "Host: {$this->server->getBaseUri()}\r\n";

        if ($this->credential->getUser() && $this->credential->getPassword()) {
            $out .= 'Authorization: Basic ' . base64_encode("{$this->credential->getUser()}:{$this->credential->getPassword()}") . "\r\n";
        }

        $out .= 'Content-Length: ' . strlen($json_data) . "\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n\r\n";
        $out .= $json_data . "\r\n\r\n";

        if (!fputs($fp, $out)) {
            throw new Exception("Unable to send request to {$this->server->getBaseUri()}");
        }

        @stream_set_timeout($fp, $this->sockTimeout);
        $status = @socket_get_status($fp);

        $response = '';

        while (!feof($fp) && !$status['timed_out']) {
            $response .= fgets($fp, 128);
            $status = socket_get_status($fp);
        }

        fclose($fp);

        $tmp = explode("\r\n\r\n", $response, 2);
        $headers = $tmp[0];
        $response = trim($tmp[1]);

        preg_match("/HTTP\/1.(?:0|1)\s*([0-9]{3})/", $headers, $matches);
        $statusCode = (int) ($matches[1]);

        if ($statusCode != 200) {
            switch ($statusCode) {
               case 401:
                   throw new Exception('Access Denied. This server requires authentication. Please ensure that a valid username and password combination is provided.');
                   break;

               case 404:
                   throw new Exception('There was a problem with the request. Ensure that the resource exists and that the name is spelled correctly.');
                   break;
           }
        }

        if (preg_match('/^error/i', $response)) {
            $errorResponse = preg_replace("/^error:?\s*/i", '', $response) ?? 'Error';
            $errorResponse = ucwords($errorResponse);
            throw new Exception($errorResponse);
        }

        return $response;
    }
}
