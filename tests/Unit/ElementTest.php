<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        ElementTest.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Model;

use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{
    public function testItReturnsElementId(): void
    {
        $element = new Element('someId');
        $this->assertEquals('someId', $element->getId());
    }

    public function testItReturnsElementIdWithPrefixForServer(): void
    {
        $element = new Element('someId');
        $json = json_encode([$element]);

        $this->assertJsonStringEqualsJsonString('["^E^someId"]', $json);
    }
}
