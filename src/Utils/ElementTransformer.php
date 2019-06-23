<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        Conversion.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Utils;

use RuntimeException;

abstract class ElementTransformer
{
    public static function fromServer(?string $dataFromServer): ?array
    {
        if (!empty($dataFromServer)) {
            $dataFromServer = json_decode($dataFromServer, true);
            foreach ($dataFromServer as &$value) {
                ElementTransformer::stringValuesToObjects($value);
            }
        }

        return $dataFromServer;
    }

    public static function toServer(array $inputData): string
    {
        $output = json_encode($inputData);

        if (!$output) {
            throw new RuntimeException('There was an error trying to encode your request data');
        }

        return $output;
    }

    protected static function stringValuesToObjects(&$value): void
    {
        if (is_array($value)) {
            foreach ($value as &$subValue) {
                ElementTransformer::stringValuesToObjects($subValue);
            }
        } elseif (is_string($value)) {
            $value = ElementFactory::fromServer($value);
        }
    }
}
