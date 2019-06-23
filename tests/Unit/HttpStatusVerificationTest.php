<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        HttpStatusVerificationTest.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Response;

use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class HttpStatusVerificationTest extends TestCase
{
    public function testItThrowsExceptionForAccessDenied(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Access Denied. This server requires authentication. Please ensure that a valid username and password combination is provided.');

        $response = $this->prophesize(ResponseInterface::class);
        $response
            ->getBody()
            ->willReturn('');
        $response
            ->getStatusCode()
            ->willReturn(401);
        $response = $response->reveal();

        HttpStatusVerification::validateStatus($response);
    }

    public function testItThrowsExceptionForNotFoundPage(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('There was a problem with the request. Ensure that the resource exists and that the name is spelled correctly.');

        $response = $this->prophesize(ResponseInterface::class);
        $response
            ->getBody()
            ->willReturn('');
        $response
            ->getStatusCode()
            ->willReturn(404);
        $response = $response->reveal();

        HttpStatusVerification::validateStatus($response);
    }

    public function testItThrowsExceptionForNotReturningSuccessfulCode(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Something went wrong. HTTP Status Code: 201 | Body: someBody');

        $response = $this->prophesize(ResponseInterface::class);
        $response
            ->getStatusCode()
            ->willReturn(201);
        $response
            ->getBody()
            ->willReturn('someBody');
        $response = $response->reveal();

        HttpStatusVerification::validateStatus($response);
    }

    public function testItThrowsExceptionIfFunctionWasNotFound(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Attempted function call was not found');

        $response = $this->prophesize(ResponseInterface::class);
        $response
            ->getStatusCode()
            ->willReturn(200);
        $response
            ->getBody()
            ->willReturn('error: not found');
        $response = $response->reveal();

        HttpStatusVerification::validateStatus($response);
    }
}
