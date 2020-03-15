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

use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use MultiTheftAuto\Sdk\Model\Authentication;
use MultiTheftAuto\Sdk\Model\Resource as MtaResource;
use MultiTheftAuto\Sdk\Model\Resources;
use MultiTheftAuto\Sdk\Model\Server;
use MultiTheftAuto\Sdk\Service\MtaService;
use MultiTheftAuto\Sdk\Transformer\ElementTransformer;
use MultiTheftAuto\Sdk\Util\Input;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Mta
{
    /**
     * @var MtaService
     */
    protected $mtaService;

    /**
     * @var Resources
     */
    protected $resources;

    public function __construct(
        Server $server,
        Authentication $auth,
        ClientInterface $httpClient = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null
    ) {
        $this->mtaService = new MtaService(
            $server,
            $auth,
            $httpClient ?? HttpClientDiscovery::find(),
            $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory(),
            $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory()
        );

        $this->resources = new Resources();
    }

    public function getService(): MtaService
    {
        return $this->mtaService;
    }

    public function getResource(string $resourceName): MtaResource
    {
        $resource = $this->resources->findByName($resourceName);

        if (empty($resource)) {
            $resource = new MtaResource($resourceName, $this->mtaService);
            $this->resources->add($resource);
        }

        return $resource;
    }

    public function getResourcesInstance(): Resources
    {
        return $this->resources;
    }

    /**
     * @codeCoverageIgnore
     * @return array|mixed[]|null
     */
    public static function getInput(): ?array
    {
        return ElementTransformer::fromServer(Input::get()) ?? null;
    }

    /**
     * @param mixed ...$arguments
     */
    public static function doReturn(...$arguments): void
    {
        echo ElementTransformer::toServer($arguments);
    }
}
