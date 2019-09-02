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

    public const SERVER_PREFIX = '^R^';
    protected const UNDEFINED_SERVICE_EXCEPTION = 'Resource %s can not be called because Mta service is not defined';

    public function __construct(string $name, MtaService $mtaService = null)
    {
        $this->name = $name;
        $this->mtaService = $mtaService;
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

    /**
     * @throws \Http\Client\Exception
     * @throws Exception
     */
    public function call(string $function, array ...$arguments)
    {
        if (!$this->mtaService) {
            throw new Exception(sprintf(self::UNDEFINED_SERVICE_EXCEPTION, $this->name));
        }

        return $this->mtaService->callFunction($this->name, $function, $arguments);
    }

    public function __call(string $name, array $arguments)
    {
        array_unshift($arguments, $name);
        return call_user_func_array([$this, 'call'], $arguments);
    }

    public function jsonSerialize(): string
    {
        return self::SERVER_PREFIX . $this->name;
    }
}
