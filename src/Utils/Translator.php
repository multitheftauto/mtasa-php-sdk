<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        Conversion.php
 *  VERSION:     1.0.0
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Utils;

use MultiTheftAuto\Sdk\Model\Element;
use MultiTheftAuto\Sdk\Model\Resource;

class Translator
{
    public static function fromServer($item)
    {
        if (is_array($item)) {
            foreach ($item as &$value) {
                $value = Translator::fromServer($value);
            }
        } elseif (is_string($item)) {
            if (substr($item, 0, 3) == '^E^') {
                $item = new Element(substr($item, 3));
            } elseif (substr($item, 0, 3) == '^R^') {
                $item = new Resource(substr($item, 3));
            }
        } else {
            throw new InvalidArgumentException('Bad argument at convertToObjects');
        }

        return $item;
    }

    public static function toServer($item)
    {
        if (is_array($item)) {
            foreach ($item as &$value) {
                $value = Translator::toServer($value);
            }
        }

        return (string) $item;
    }
}
