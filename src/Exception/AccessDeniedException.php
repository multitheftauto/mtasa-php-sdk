<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        AccessDeniedException.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Exception;

class AccessDeniedException extends MessageException
{
    protected const EXCEPTION_MESSAGE = 'Access Denied. This server requires authentication. Please ensure that a valid username and password combination is provided.';
}
