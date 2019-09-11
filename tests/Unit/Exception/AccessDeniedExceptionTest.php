<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        AccessDeniedExceptionTest.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Exception;

use Exception;
use PHPUnit\Framework\TestCase;

class AccessDeniedExceptionTest extends TestCase
{
    public function testItThrowsExceptionWithMessage(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Access Denied. This server requires authentication. Please ensure that a valid username and password combination is provided.');

        throw new AccessDeniedException();
    }
}
