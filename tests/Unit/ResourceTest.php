<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        ResourceTest.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Model;

use Exception;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{
    public function testItReturnsResourceName(): void
    {
        $resource = new Resource('someName');
        $this->assertEquals('someName', $resource->getName());
    }

    public function testItReturnsResourceNameForServer(): void
    {
        $resource = new Resource('someName');
        $json = json_encode([$resource]);

        $this->assertJsonStringEqualsJsonString('["^R^someName"]', $json);
    }

    public function testItThrowsExceptionIfMtaInstanceNotPassed(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Resource someName can not be called because Mta manager is not defined');

        $resource = new Resource('someName');
        $resource->call('someFunction');
    }
}
