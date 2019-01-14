<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        Resources.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Model;

class Resources
{
    protected $resources = [];

    public function findByName(string $name): ?Resource
    {
        $found = array_filter($this->resources, function($resource) use ($name) {
            return $resource->getName() == $name;
        });

        if (empty($found)) {
            return null;
        }
        return $found[0];
    }

    public function add(Resource $resource): bool
    {
        return (bool) $resource[] = $resource;
    }
}
