<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        ResourceTest.php
 *  VERSION:     1.0.0
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
        $this->assertEquals('^R^someName', (string) $resource);
    }

    public function testItThrowsExceptionIfMtaInstanceNotPassed(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Resource someName can not be called because server is not defined');

        $resource = new Resource('someName');
        $resource->call('someFunction');
    }
}
