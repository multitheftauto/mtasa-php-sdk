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
use JsonSerializable;
use MultiTheftAuto\Sdk\Mta;

class Resource implements JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Mta|null
     */
    private $mta;

    public const SERVER_PREFIX = '^R^';

    public function __construct(string $name, Mta $mta = null)
    {
        $this->name = $name;
        $this->mta = $mta;
    }

    public static function fromServer(string $value): self
    {
        $name = substr($value, 3);
        return new static($name);
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

    public function __call($name, $arguments)
    {
        array_unshift($arguments, $name);
        return call_user_func_array([$this, 'call'], $arguments);
    }

    public function jsonSerialize(): string
    {
        return self::SERVER_PREFIX . $this->name;
    }
}
