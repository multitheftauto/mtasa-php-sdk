<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        FunctionNotFoundExceptionTest.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Exception;

use Exception;
use PHPUnit\Framework\TestCase;

class FunctionNotFoundExceptionTest extends TestCase
{
    public function testItThrowsExceptionWithMessage(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Attempted function call was not found');

        throw new FunctionNotFoundException();
    }
}
