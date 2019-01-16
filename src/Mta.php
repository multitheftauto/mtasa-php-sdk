<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        mta.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication\BasicAuth;
use Http\Message\MessageFactory;
use MultiTheftAuto\Sdk\Authentication\Credential;
use MultiTheftAuto\Sdk\Model\Resource as MtaResource;
use MultiTheftAuto\Sdk\Model\Resources;
use MultiTheftAuto\Sdk\Model\Server;
use MultiTheftAuto\Sdk\Response\HandleResponse;
use MultiTheftAuto\Sdk\Response\HttpStatusVerification;
use MultiTheftAuto\Sdk\Utils\Input;
use MultiTheftAuto\Sdk\Utils\Translator;

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
    protected $messageFactory;

    public function __construct(
        Server $server,
        Credential $credential,
        HttpClient $httpClient = null,
        MessageFactory $messageFactory = null
    ) {
        $this->server = $server;
        $this->credential = $credential;
        $this->resources = new Resources();
        $this->httpClient = $httpClient?? HttpClientDiscovery::find();
        $this->messageFactory = $messageFactory?? MessageFactoryDiscovery::find();
    }

    public function getResource(string $resourceName): MtaResource
    {
        $resource = $this->resources->findByName($resourceName);

        if (empty($resource)) {
            $resource = new MtaResource($resourceName, $this);
            $this->resources->add($resource);
        }

        return $resource;
    }

    public static function getInput(): ?array
    {
        return Translator::fromServer(Input::get())?? null;
    }

    public static function doReturn(...$arguments): void
    {
        echo Translator::toServer($arguments);
    }

    public function callFunction(string $resourceName, string $function, array $arguments = null): ?array
    {
        $json_output = $arguments? Translator::toServer($arguments) : '';
        $path = sprintf('/%s/call/%s', $resourceName, $function);
        $result = $this->do_post_request($path, $json_output);
        $out = Translator::fromServer($result);

        return $out?? null;
    }

    protected function do_post_request($path, $json_data): string
    {
        $request = $this->messageFactory->createRequest('POST', sprintf('%s/%s', $this->server->getBaseUri(), $path), [], $json_data);
        $auth = new BasicAuth($this->credential->getUser(), $this->credential->getPassword());
        $auth->authenticate($request);

        $response = $this->httpClient->sendRequest($request);
        HttpStatusVerification::validateStatus($response);

        return HandleResponse::getBody($response);
    }
}
