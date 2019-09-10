<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        MessageException.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Exception;

use Exception;

abstract class MessageException extends Exception
{
    protected const EXCEPTION_MESSAGE = '';

    public function __construct()
    {
        parent::__construct(static::EXCEPTION_MESSAGE);
    }
}
