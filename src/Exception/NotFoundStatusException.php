<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        NotFoundStatusException.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Exception;

use Exception;

class NotFoundStatusException extends Exception
{
    protected const EXCEPTION_MESSAGE = 'There was a problem with the request. Ensure that the resource exists and that the name is spelled correctly.';

    public function __construct()
    {
        parent::__construct(self::EXCEPTION_MESSAGE);
    }
}
