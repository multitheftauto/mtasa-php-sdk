<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        HttpStatusVerification.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Response;

use Exception;
use MultiTheftAuto\Sdk\Exception\AccessDeniedException;
use MultiTheftAuto\Sdk\Exception\FunctionNotFoundException;
use MultiTheftAuto\Sdk\Exception\NotFoundStatusException;
use Psr\Http\Message\ResponseInterface;

class HttpStatusValidator
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    protected const ERROR_NOT_FOUND_BODY = 'error: not found';
    protected const SOMETHING_WENT_WRONG = 'Something went wrong. HTTP Status Code: %s | Body: %s';

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @throws Exception
     */
    public function validate(): void
    {
        $statusCode = $this->response->getStatusCode();

        switch ($statusCode) {
            case 401:
                {
                    throw new AccessDeniedException();
                }
            case 404:
                {
                    throw new NotFoundStatusException();
                }
            case 200:
                {
                    if ($this->response->getBody() == self::ERROR_NOT_FOUND_BODY) {
                        throw new FunctionNotFoundException();
                    }
                    break;
                }
            default:
                {
                    throw new Exception(
                        sprintf(
                            self::SOMETHING_WENT_WRONG,
                            $statusCode,
                            $this->response->getBody()
                        )
                    );
                }
        }
    }
}
