<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        Credential.php
 *  VERSION:     1.0.0
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Authentication;

use PHPUnit\Framework\TestCase;

class CredentialTest extends TestCase
{
    public function testItCreatesValidCredential(): void
    {
        $credential = new Credential('someUser', 'somePassword');
        $this->assertEquals('someUser', $credential->getUser());
        $this->assertEquals('somePassword', $credential->getPassword());
    }
}
