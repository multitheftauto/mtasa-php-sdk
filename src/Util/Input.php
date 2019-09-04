<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        Input.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Util;

abstract class Input
{
    /**
     * @codeCoverageIgnore
     */
    public static function get(): ?string
    {
        $input = file_get_contents('php://input');
        return $input? $input : null;
    }
}
