<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        Resource.php
 *  VERSION:     0.5
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Model;

use Exception;

class Resource
{
    private $name;
    private $server;

    public function __construct($name, $server = null)
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
