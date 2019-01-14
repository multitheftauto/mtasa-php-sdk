<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        mta.php
 *  VERSION:     1.0.0
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk;

use Exception;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use InvalidArgumentException;
use MultiTheftAuto\Sdk\Authentication\Credential;
use MultiTheftAuto\Sdk\Model\Element;
use MultiTheftAuto\Sdk\Model\Resource;
use MultiTheftAuto\Sdk\Model\Resources;
use MultiTheftAuto\Sdk\Model\Server;

class Mta
{
    /**
     * @var Server
     */
    protected $server;

    /**
     * @var Credential
     */
    protected $credential;

    /**
     * @var Resources
     */
    protected $resources;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var MessageFactory
     */
    protected $requestFactory;

    public function __construct(Server $server, Credential $credential, HttpClient $httpClient = null, MessageFactory $requestFactory = null)
    {
        $this->server = $server;
        $this->credential = $credential;
        $this->resources = new Resources();
        $this->httpClient = $httpClient?? HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory?? MessageFactoryDiscovery::find();
    }

    public function getResource(string $resourceName)
    {
        $resource = $this->resources->findByName($resourceName);

        if (!$resource) {
            $resource = new Resource($resourceName, $this);
            $this->resources->add($resource);
        }

        return $resource;
    }

    public static function getInput()
    {
        $input = file_get_contents('php://input');
        if (!$input) {
            return false;
        }

        $inputArray = json_decode($input, true);
        return Mta::convertToObjects($inputArray)?? false;
    }

    public static function doReturn(...$arguments)
    {
        $arguments = Mta::convertFromObjects($arguments);
        echo json_encode($arguments);
    }

    public function callFunction(string $resourceName, string $function, array $arguments = null)
    {
        $json_output = $arguments? Mta::convertFromObjects(json_encode($arguments)) : '';
        $path = sprintf('/%s/call/%s', $resourceName, $function);
        $result = $this->do_post_request($path, $json_output);
        $out = Mta::convertToObjects(json_decode($result, true));

        return $out?? false;
    }

    public static function convertToObjects($item)
    {
        if (is_array($item)) {
            foreach ($item as &$value) {
                $value = Mta::convertToObjects($value);
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
                $value = Mta::convertFromObjects($value);
            }
        }

        return (string) $item;
    }

    public function do_post_request($path, $json_data)
    {
        $request = $this->requestFactory->createRequest(
            'POST',
            sprintf('%s/%s', $this->server->getBaseUri(), $path),
            [
                'Content-type' => 'text/json; charset=UTF8',
                'auth' => [$this->credential->getUser(), $this->credential->getPassword()],
            ],
            $json_data
        );

        $response = $this->httpClient->sendRequest($request);
        $statusCode = $response->getStatusCode();

        if ($statusCode == 200) {
            return (string) $response->getBody();
        }

        switch ($statusCode) {
            case 401:
                throw new Exception('Access Denied. This server requires authentication. Please ensure that a valid username and password combination is provided.');
                break;

            case 404:
                throw new Exception('There was a problem with the request. Ensure that the resource exists and that the name is spelled correctly.');
                break;
        }

        return $response;
    }
}
