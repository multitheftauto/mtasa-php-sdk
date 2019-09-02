<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        Credential.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Model;

use PHPUnit\Framework\TestCase;

class AuthenticationTest extends TestCase
{
    public function testItCreatesValidCredential(): void
    {
        $credential = new Authentication('someUser', 'somePassword');
        $this->assertEquals('someUser', $credential->getUser());
        $this->assertEquals('somePassword', $credential->getPassword());
    }
}
