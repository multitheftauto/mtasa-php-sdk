<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        NotCallableResourceException.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Exception;

class NotCallableResourceException extends MessageException
{
    protected const EXCEPTION_MESSAGE = 'There was a problem with the request. Ensure that the resource handling the call is running.';
}
