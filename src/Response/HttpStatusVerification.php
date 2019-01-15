<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        HttpStatusVerification.php
 *  VERSION:     1.0.0
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Response;

use Exception;
use Psr\Http\Message\ResponseInterface;

class HttpStatusVerification
{
    public static function validateStatus(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();

        switch ($statusCode) {
            case 401:
                throw new Exception('Access Denied. This server requires authentication. Please ensure that a valid username and password combination is provided.');
                break;
            case 404:
                throw new Exception('There was a problem with the request. Ensure that the resource exists and that the name is spelled correctly.');
                break;
        }

        if ($statusCode != 200) {
            throw new Exception(sprintf('Something went wrong. HTTP Status Code: %s | Body: %s', $statusCode, $response->getBody()));
        }
    }
}
