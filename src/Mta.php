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

use GuzzleHttp\Psr7\Stream;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication\BasicAuth;
use MultiTheftAuto\Sdk\Model\Authentication;
use MultiTheftAuto\Sdk\Model\Resource as MtaResource;
use MultiTheftAuto\Sdk\Model\Resources;
use MultiTheftAuto\Sdk\Model\Server;
use MultiTheftAuto\Sdk\Response\HandleResponse;
use MultiTheftAuto\Sdk\Response\HttpStatusVerification;
use MultiTheftAuto\Sdk\Utils\ElementTransformer;
use MultiTheftAuto\Sdk\Utils\Input;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Mta
{
    /**
     * @var Server
     */
    protected $server;

    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @var Resources
     */
    protected $resources;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var RequestFactoryInterface
     */
    protected $requestFactory;

    /**
     * @var StreamFactoryInterface
     */
    protected $streamFactory;

    public function __construct(
        Server $server,
        Authentication $auth,
        HttpClient $httpClient = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null
    ) {
        $this->server = $server;
        $this->auth = $auth;
        $this->httpClient = $httpClient ?? HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();

        $this->resources = new Resources();
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

    /**
     * @codeCoverageIgnore
     */
    public static function getInput(): ?array
    {
        return ElementTransformer::fromServer(Input::get()) ?? null;
    }

    public static function doReturn(...$arguments): void
    {
        echo ElementTransformer::toServer($arguments);
    }

    public function callFunction(string $resourceName, string $functionName, array $arguments = null): ?array
    {
        $requestData = $arguments ? ElementTransformer::toServer($arguments) : '';
        $path = sprintf('%s/call/%s', $resourceName, $functionName);

        $responseBody = $this->executeRequest($path, $requestData);
        $convertedResponse = ElementTransformer::fromServer($responseBody);

        return $convertedResponse ?? null;
    }

    public function __get(string $name): MtaResource
    {
        return $this->getResource($name);
    }

    protected function executeRequest(string $path, string $body): string
    {
        $request = $this->requestFactory->createRequest(
            'POST',
            sprintf('%s/%s', $this->server->getBaseUri(), $path)
        );

        $streamBody = $this->streamFactory->createStream($body);
        $request->withBody($streamBody);

        $auth = new BasicAuth($this->auth->getUser(), $this->auth->getPassword());
        $request = $auth->authenticate($request);

        $response = $this->httpClient->sendRequest($request);
        HttpStatusVerification::validateStatus($response);

        return HandleResponse::getBody($response);
    }
}
