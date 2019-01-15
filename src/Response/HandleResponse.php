<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        HandleResponse.php
 *  VERSION:     1.0.0
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HandleResponse
{
    public static function getBody(ResponseInterface $response): string
    {
        $stream = $response->getBody();
        if ($stream instanceof StreamInterface) {
            $body = $stream->getContents();
        } else {
            $body = $stream;
        }
        return $body;
    }
}
