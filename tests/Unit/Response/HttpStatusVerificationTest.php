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
use Http\Factory\Guzzle\StreamFactory;
use MultiTheftAuto\Sdk\Exception\AccessDeniedException;
use MultiTheftAuto\Sdk\Exception\FunctionNotFoundException;
use MultiTheftAuto\Sdk\Exception\NotFoundStatusException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class HttpStatusVerificationTest extends TestCase
{
    public function testItThrowsExceptionForAccessDenied(): void
    {
        $this->expectException(AccessDeniedException::class);

        $response = $this->prophesize(ResponseInterface::class);
        $response
            ->getBody()
            ->willReturn('');
        $response
            ->getStatusCode()
            ->willReturn(401);
        $response = $response->reveal();

        $validator = new HttpStatusValidator($response);
        $validator->validate();
    }

    public function testItThrowsExceptionForNotFoundPage(): void
    {
        $this->expectException(NotFoundStatusException::class);
        $response = $this->prophesize(ResponseInterface::class);
        $response
            ->getBody()
            ->willReturn('');
        $response
            ->getStatusCode()
            ->willReturn(404);
        $response = $response->reveal();

        $validator = new HttpStatusValidator($response);
        $validator->validate();
    }

    public function testItThrowsExceptionForNotReturningSuccessfulCode(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Something went wrong. HTTP Status Code: 201 | Body: someBody');

        $stream = (new StreamFactory())->createStream('someBody');

        $response = $this->prophesize(ResponseInterface::class);
        $response
            ->getStatusCode()
            ->willReturn(201);
        $response
            ->getBody()
            ->willReturn($stream);
        $response = $response->reveal();

        $validator = new HttpStatusValidator($response);
        $validator->validate();
    }

    public function testItThrowsExceptionIfFunctionWasNotFound(): void
    {
        $this->expectException(FunctionNotFoundException::class);

        $stream = (new StreamFactory())->createStream('error: not found');

        $response = $this->prophesize(ResponseInterface::class);
        $response
            ->getStatusCode()
            ->willReturn(200);
        $response
            ->getBody()
            ->willReturn($stream);
        $response = $response->reveal();

        $validator = new HttpStatusValidator($response);
        $validator->validate();
    }
}
