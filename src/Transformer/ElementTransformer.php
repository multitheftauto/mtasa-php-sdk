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

namespace MultiTheftAuto\Sdk\Transformer;

use MultiTheftAuto\Sdk\Factory\ElementFactory;

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
        return json_encode($inputData) ?: '';
    }

    private static function stringValuesToObjects(&$value): void
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
