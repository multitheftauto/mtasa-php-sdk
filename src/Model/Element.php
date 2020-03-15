<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        Element.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Model;

use JsonSerializable;

class Element implements JsonSerializable
{
    /**
     * @var string
     */
    protected $id;

    public const SERVER_PREFIX = '^E^';

    final public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromServer(string $value): self
    {
        $id = substr($value, 3);

        return new static($id);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function jsonSerialize(): string
    {
        return self::SERVER_PREFIX . $this->id;
    }
}
