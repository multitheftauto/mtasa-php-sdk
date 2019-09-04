<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        ResourceCall.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Model;

use MultiTheftAuto\Sdk\Model\Resource as MtaResource;

class ResourceCall
{
    /**
     * @var MtaResource
     */
    protected $resource;

    public function __construct(MtaResource $resource)
    {
        $this->resource = $resource;
    }

    public function __call(string $name, array $arguments)
    {
        array_unshift($arguments, $name);
        return call_user_func_array([$this->resource, 'call'], $arguments);
    }
}
