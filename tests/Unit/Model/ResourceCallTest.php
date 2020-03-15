<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        ResourceCallTest.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Model;

use PHPUnit\Framework\TestCase;

class ResourceCallTest extends TestCase
{
    public function testItMakeACallToResourceInstance()
    {
        $functionName = 'getSomething';
        $firstParameter = 'firstParameter';
        $secondParameter = 'secondParameter';

        $resource = $this->prophesize(Resource::class);
        $resource
            ->call($functionName, $firstParameter, $secondParameter)
            ->shouldBeCalled();

        $resourceCall = new ResourceCall($resource->reveal());
        $resourceCall->$functionName($firstParameter, $secondParameter);
    }
}
