<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        MtaTest.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Stream;
use Http\Factory\Guzzle\RequestFactory;
use Http\Factory\Guzzle\StreamFactory;
use Http\Mock\Client;
use MultiTheftAuto\Sdk\Exception\AccessDeniedException;
use MultiTheftAuto\Sdk\Model\Authentication;
use MultiTheftAuto\Sdk\Model\Element;
use MultiTheftAuto\Sdk\Model\Resource;
use MultiTheftAuto\Sdk\Model\Server;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;

class MtaTest extends TestCase
{
    public function testItReturnsResponse(): void
    {
        $response = $this->prophesize(ResponseInterface::class);
        $response
            ->getBody()
            ->willReturn('["^R^someResource","someString","^E^someElementId", [1,2,3]]');
        $response
            ->getStatusCode()
            ->willReturn(200);
        $response = $response->reveal();

        $client = new Client();
        $client->addResponse($response);

        $server = new Server('127.0.0.1', 22005);
        $credential = new Authentication('someUser', 'somePassword');

        $mta = new Mta($server, $credential, $client);
        $return = $mta->getService()->callFunction('someCallableResource', 'someCallableFunction');

        $this->assertIsArray($return);
        $this->assertInstanceOf(Resource::class, $return[0]);
        $this->assertEquals('someResource', $return[0]->getName());
        $this->assertEquals('someString', $return[1]);
        $this->assertInstanceOf(Element::class, $return[2]);
        $this->assertEquals('someElementId', $return[2]->getId());
    }

    public function testItReturnsResponseUsingDirectCall(): void
    {
        $response = $this->prophesize(ResponseInterface::class);
        $response
            ->getBody()
            ->willReturn('["^R^someResource","someString","^E^someElementId"]');
        $response
            ->getStatusCode()
            ->willReturn(200);
        $response = $response->reveal();

        $client = new Client();
        $client->addResponse($response);

        $server = new Server('127.0.0.1', 22005);
        $credential = new Authentication('someUser', 'somePassword');

        $mta = new Mta($server, $credential, $client);
        $return = $mta->getResource('someCallableResource')->call->someCallableFunction();

        $this->assertIsArray($return);
        $this->assertInstanceOf(Resource::class, $return[0]);
        $this->assertEquals('someResource', $return[0]->getName());
        $this->assertEquals('someString', $return[1]);
        $this->assertInstanceOf(Element::class, $return[2]);
        $this->assertEquals('someElementId', $return[2]->getId());
    }

    public function testItAcceptsScalarParameter(): void
    {
        $response = $this->prophesize(ResponseInterface::class);
        $response
            ->getBody()
            ->willReturn('["^R^someResource","someString","^E^someElementId"]');
        $response
            ->getStatusCode()
            ->willReturn(200);
        $response = $response->reveal();

        $client = new Client();
        $client->addResponse($response);

        $server = new Server('127.0.0.1', 22005);
        $credential = new Authentication('someUser', 'somePassword');

        $mta = new Mta($server, $credential, $client);
        $return = $mta->getResource('someCallableResource')->call->someCallableFunction(1, "Test");

        $this->assertIsArray($return);
    }

    public function testItPrintsSomeJson(): void
    {
        Mta::doReturn('someValue1', 'someValue2');
        $this->expectOutputString('["someValue1","someValue2"]');
    }

    public function testItReturnResource()
    {
        $server = $this->prophesize(Server::class);
        $credential = $this->prophesize(Authentication::class);
        $client = new Client();
        $mta = new Mta($server->reveal(), $credential->reveal(), $client);

        $resourceName = 'someResource';
        $resource = $mta->getResource($resourceName);

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertEquals($resourceName, $resource->getName());
    }

    public function testItUsesRequestAndStreamFactoryParameter(): void
    {
        $request = new Request('POST', 'http://mtasa.com');
        $requestFactory = $this->prophesize(RequestFactory::class);
        $requestFactory
            ->createRequest(Argument::type('string'), Argument::type('string'))
            ->shouldBeCalled()
            ->willReturn($request);
        $requestFactory = $requestFactory->reveal();

        $stream = $this->prophesize(Stream::class)->reveal();
        $streamFactory = $this->prophesize(StreamFactory::class);
        $streamFactory
            ->createStream(Argument::type('string'))
            ->shouldBeCalled()
            ->willReturn($stream);
        $streamFactory = $streamFactory->reveal();

        $responseStream = (new StreamFactory())->createStream('["^R^someResource","someString","^E^someElementId"]');
        $response = $this->prophesize(ResponseInterface::class);
        $response
            ->getBody()
            ->willReturn($responseStream);
        $response
            ->getStatusCode()
            ->willReturn(200);
        $response = $response->reveal();

        $client = new Client();
        $client->addResponse($response);

        $server = new Server('127.0.0.1', 22005);
        $credential = new Authentication('someUser', 'somePassword');

        $mta = new Mta($server, $credential, $client, $requestFactory, $streamFactory);
        $mta->getService()->callFunction('someCallableResource', 'someCallableFunction');
    }

    public function testItThrowsExceptionIfResponseCodeIsAccessDenied(): void
    {
        $this->expectException(AccessDeniedException::class);

        $response = $this->prophesize(ResponseInterface::class);
        $response
            ->getStatusCode()
            ->willReturn(401);
        $response = $response->reveal();

        $client = new Client();
        $client->addResponse($response);

        $server = new Server('127.0.0.1', 22005);
        $credential = new Authentication('someUser', 'somePassword');

        $mta = new Mta($server, $credential, $client);
        $mta->getService()->callFunction('someCallableResource', 'someCallableFunction');
    }

    public function testItAddsAResource(): void
    {
        $client = new Client();
        $server = new Server('127.0.0.1', 22005);
        $credential = new Authentication('someUser', 'somePassword');
        $mta = new Mta($server, $credential, $client);

        $mta->getResource('someName');
        $this->assertCount(1, $mta->getResourcesInstance()->all());
    }
}
