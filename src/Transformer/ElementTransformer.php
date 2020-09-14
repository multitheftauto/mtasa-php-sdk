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

use MultiTheftAuto\Sdk\Exception\NotCallableResourceException;
use MultiTheftAuto\Sdk\Factory\ElementFactory;

abstract class ElementTransformer
{
    /**
     * @return mixed[]|null
     */
    public static function fromServer(?string $dataFromServer): ?array
    {
        if (empty($dataFromServer)) {
            return null;
        }

        $data = json_decode($dataFromServer);

        if ($data === null) {
            throw new NotCallableResourceException();   
        }
        
        foreach ($data as &$value) {
            ElementTransformer::stringValuesToObjects($value);
        }

        return $data;
    }

    /**
     * @param mixed[] $inputData
     *
     */
    public static function toServer(array $inputData): string
    {
        return json_encode($inputData) ?: '';
    }

    /**
     * @param mixed[]|string $value
     */
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
