<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        ServerTest.php
 *  VERSION:     1.0.0
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Model;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
    public function testItCreatesValidServerInstance(): void
    {
        $server = new Server('127.0.0.1', 22005);
        $this->assertEquals('127.0.0.1', $server->getHost());
        $this->assertEquals(22005, $server->getPort());
        $this->assertEquals('http://127.0.0.1:22005', $server->getBaseUri());
    }

    public function testItThrowsErrorForInvalidIp(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid IP');

        new Server('999.123.123.123.123', 0);
    }

    public function testItThrowsErrorForInvalidIp2(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid IP');

        new Server('12.ds.as.12', 0);
    }
}
