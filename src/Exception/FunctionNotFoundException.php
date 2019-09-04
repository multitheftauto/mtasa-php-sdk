<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        NotFoundBodyException.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Exception;

use Exception;

class FunctionNotFoundException extends Exception
{
    protected const EXCEPTION_MESSAGE = 'Attempted function call was not found';

    public function __construct()
    {
        parent::__construct(self::EXCEPTION_MESSAGE);
    }
}
