<?php

declare(strict_types=1);

namespace MultiTheftAuto\Sdk;

use Http\Mock\Client;
use MultiTheftAuto\Sdk\Authentication\Credential;
use MultiTheftAuto\Sdk\Model\Element;
use MultiTheftAuto\Sdk\Model\Resource;
use MultiTheftAuto\Sdk\Model\Server;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class MtaTest extends TestCase
{
    public function testItReturnsResponse(): void
    {
        $client = new Client();
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn('["^R^someResource","someString","^E^someElementId"]');
        $response->method('getStatusCode')->willReturn(200);
        $client->addResponse($response);
        $server = new Server('127.0.0.1', 22005);
        $credential = new Credential('someUser', 'somePassword');

        $mta = new Mta($server, $credential, $client);
        $return = $mta->callFunction('someCallableResource', 'someCallableFunction');

        $this->assertInstanceOf(Resource::class, $return[0]);
        $this->assertEquals('someResource', $return[0]->getName());
        $this->assertEquals('someString', $return[1]);
        $this->assertInstanceOf(Element::class, $return[2]);
        $this->assertEquals('someElementId', $return[2]->getId());
    }

    public function testItPrintsSomeJson()
    {
        Mta::doReturn('someValue1', 'someValue2');
        $this->expectOutputString('["someValue1","someValue2"]');
    }
}
