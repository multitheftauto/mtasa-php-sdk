<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        ElementFactory.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Factory;

use MultiTheftAuto\Sdk\Model\Element;
use MultiTheftAuto\Sdk\Model\Resource as MtaResource;

class ElementFactory
{
    /**
     * @return Element|MtaResource|string
     */
    public static function fromServer(string $value)
    {
        $valuePrefix = substr($value, 0, 3);

        switch ($valuePrefix) {
            case Element::SERVER_PREFIX: {
                return Element::fromServer($value);
            }
            case MtaResource::SERVER_PREFIX: {
                return MtaResource::fromServer($value);
            }
        }

        return $value;
    }
}
