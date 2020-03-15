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

namespace MultiTheftAuto\Sdk\Service;

use Http\Message\Authentication\BasicAuth;
use MultiTheftAuto\Sdk\Model\Authentication;
use MultiTheftAuto\Sdk\Model\Server;
use MultiTheftAuto\Sdk\Response\HttpStatusValidator;
use MultiTheftAuto\Sdk\Transformer\ElementTransformer;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class MtaService
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
     * @var ClientInterface
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

    protected const HTTP_METHOD = 'POST';
    protected const MTA_RESOURCE_ENDPOINT = 'http://%s/%s/call/%s';

    public function __construct(
        Server $server,
        Authentication $auth,
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->server = $server;
        $this->auth = $auth;
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * @param mixed[]  $arguments
     *
     * @throws \Http\Client\Exception
     * @throws \Exception
     * @return mixed[]|null
     */
    public function callFunction(string $resourceName, string $functionName, array $arguments = []): ?array
    {
        $requestData = ElementTransformer::toServer($arguments);
        $streamBody = $this->streamFactory->createStream($requestData);

        $endpoint = $this->getEndpointToResourceFunction($resourceName, $functionName);
        $request = $this->requestFactory->createRequest(self::HTTP_METHOD, $endpoint);
        $request = $request->withBody($streamBody);

        $auth = new BasicAuth($this->auth->getUser(), $this->auth->getPassword());
        $request = $auth->authenticate($request);

        $response = $this->httpClient->sendRequest($request);

        $statusValidator = new HttpStatusValidator($response);
        $statusValidator->validate();

        $responseBody = (string) $response->getBody();

        return  ElementTransformer::fromServer($responseBody) ?? null;
    }

    private function getEndpointToResourceFunction(string $resourceName, string $functionName): string
    {
        return sprintf(
            self::MTA_RESOURCE_ENDPOINT,
            $this->server->getEndpoint(),
            $resourceName,
            $functionName
        );
    }
}
