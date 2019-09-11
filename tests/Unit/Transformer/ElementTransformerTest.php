<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        TranslatorTest.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Transformer;

use MultiTheftAuto\Sdk\Model\Element;
use MultiTheftAuto\Sdk\Model\Resource;
use PHPUnit\Framework\TestCase;

class ElementTransformerTest extends TestCase
{
    public function testItConvertsToObjects(): void
    {
        $input = '["^R^someResource","someString","^E^someElementId"]';
        $input = ElementTransformer::fromServer($input);

        $this->assertInstanceOf(Resource::class, $input[0]);
        $this->assertIsString($input[1]);
        $this->assertInstanceOf(Element::class, $input[2]);
    }

    public function testItConvertsObjects2(): void
    {
        $input = '[["^R^someResource","^R^someResource2"],"someString","^E^someElementId"]';
        $input = ElementTransformer::fromServer($input);

        $this->assertContainsOnlyInstancesOf(Resource::class, $input[0]);
        $this->assertIsString($input[1]);
        $this->assertInstanceOf(Element::class, $input[2]);
    }

    public function testItConvertsToServerFormat(): void
    {
        $array = [new Resource('someResource'), 'String', [new Element('someElement')], new Element('someElement2')];
        $output = ElementTransformer::toServer($array);
        $this->assertEquals('["^R^someResource","String",["^E^someElement"],"^E^someElement2"]', $output);
    }

    public function testItReturnsNullIfDataIsEmpty(): void
    {
        $input = '';
        $fromInput = ElementTransformer::fromServer($input);

        $this->assertNull($fromInput);
    }
}
