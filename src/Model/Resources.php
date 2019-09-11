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

use MultiTheftAuto\Sdk\Model\Resource as MtaResource;

class Resources
{
    /**
     * @var MtaResource[]
     */
    protected $resources = [];

    public function findByName(string $name): ?MtaResource
    {
        $found = array_filter($this->resources, function(MtaResource $resource) use ($name) {
            return $resource->getName() == $name;
        });

        $resource = current($found);
        return $resource? $resource : null;
    }

    public function add(MtaResource $resource): void
    {
        $this->resources[] = $resource;
    }

    /**
     * @return MtaResource[]
     */
    public function all(): array
    {
        return $this->resources;
    }
}
