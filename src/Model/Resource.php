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
    private $mta;

    public function __construct(string $name, Mta $mta = null)
    {
        $this->name = $name;
        $this->mta = $mta;
    }

    public function getName()
    {
        return $this->name;
    }

    public function call($function, ...$arguments)
    {
        if (!$this->mta) {
            throw new Exception(sprintf('Resource %s can not be called because Mta manager is not defined', $this->name));
        }

        return $this->mta->callFunction($this->name, $function, $arguments);
    }

    public function __toString()
    {
        return '^R^' . $this->name;
    }
}
