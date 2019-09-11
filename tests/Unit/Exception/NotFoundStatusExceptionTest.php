<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        NotFoundStatusExceptionTest.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Exception;

use Exception;
use PHPUnit\Framework\TestCase;

class NotFoundStatusExceptionTest extends TestCase
{
    public function testItThrowsExceptionWithMessage(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('There was a problem with the request. Ensure that the resource exists and that the name is spelled correctly.');

        throw new NotFoundStatusException();
    }
}
