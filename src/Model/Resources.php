<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        Resources.php
 *  VERSION:     1.0.0
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Model;

class Resources
{
    /**
     * @var Resource[]
     */
    protected $resources = [];

    public function findByName(string $name): ?Resource
    {
        $found = array_filter($this->resources, function(Resource $resource) use ($name) {
            return $resource->getName() == $name;
        });

        $resource = current($found);
        return $resource? $resource : null;
    }

    public function add(Resource $resource): void
    {
        $this->resources[] = $resource;
    }
}
