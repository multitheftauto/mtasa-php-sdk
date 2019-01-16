<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        Resource.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Model;

use Exception;
use MultiTheftAuto\Sdk\Mta;

class Resource
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Mta|null
     */
    private $server;

    public function __construct(string $name, Mta $server = null)
    {
        $this->name = $name;
        $this->server = $server;
    }

    public function getName()
    {
        return $this->name;
    }

    public function call($function, ...$arguments)
    {
        if (!$this->server) {
            throw new Exception(sprintf('Resource %s can not be called because server is not defined', $this->name));
        }

        return $this->server->callFunction($this->name, $function, $arguments);
    }

    public function __toString()
    {
        return '^R^' . $this->name;
    }
}
