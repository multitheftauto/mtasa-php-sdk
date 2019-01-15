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
    public static function fromServer(string $dataFromServer): array
    {
        $dataFromServer = json_decode($dataFromServer, true);
        foreach ($dataFromServer as &$value) {
            Translator::stringValuesToObjects($value);
        }

        return $dataFromServer;
    }

    public static function toServer(array $inputData): string
    {
        foreach ($inputData as &$value) {
            Translator::objectValuesToString($value);
        }

        return (string) json_encode($inputData);
    }

    protected static function stringValuesToObjects(&$value): void
    {
        if (is_array($value)) {
            foreach ($value as &$subValue) {
                Translator::stringValuesToObjects($subValue);
            }
        } elseif (is_string($value)) {
            $valuePrefix = substr($value, 0, 3);
            if ($valuePrefix == '^E^') {
                $value = new Element(substr($value, 3));
            } elseif ($valuePrefix == '^R^') {
                $value = new Resource(substr($value, 3));
            }
        }
    }

    protected static function objectValuesToString(&$value): void
    {
        if (is_array($value)) {
            foreach ($value as &$subValue) {
                Translator::objectValuesToString($subValue);
            }
        } else {
            $value = (string) $value;
        }
    }
}
