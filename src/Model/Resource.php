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
use MultiTheftAuto\Sdk\Service\MtaService;

class Resource implements JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var MtaService|null
     */
    private $mtaService;

    /**
     * @var ResourceCall
     */
    public $call;

    public const SERVER_PREFIX = '^R^';
    protected const UNDEFINED_SERVICE_EXCEPTION = 'Resource %s can not be called because Mta service is not defined';

    final public function __construct(string $name, MtaService $mtaService = null)
    {
        $this->name = $name;
        $this->mtaService = $mtaService;
        $this->call = new ResourceCall($this);
    }

    public static function fromServer(string $value): self
    {
        $name = substr($value, 3);
        return new static($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param mixed  ...$arguments
     *
     * @throws \Http\Client\Exception
     * @return array|mixed[]|null
     */
    public function call(string $function, ...$arguments)
    {
        if (!$this->mtaService) {
            throw new Exception(sprintf(self::UNDEFINED_SERVICE_EXCEPTION, $this->name));
        }

        return $this->mtaService->callFunction($this->name, $function, $arguments);
    }

    public function jsonSerialize(): string
    {
        return self::SERVER_PREFIX . $this->name;
    }
}
