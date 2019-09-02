<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        Server.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Model;

use InvalidArgumentException;

class Server
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $httpPort;

    public function __construct(string $host, int $httpPort)
    {
        if (!filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            throw new InvalidArgumentException('Invalid IP');
        }

        $this->host = $host;
        $this->httpPort = $httpPort;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->httpPort;
    }

    public function getEndpoint(): string
    {
        return sprintf('%s:%s', $this->host, $this->httpPort);
    }
}
