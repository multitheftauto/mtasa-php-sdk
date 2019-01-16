<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        TranslatorTest.php
 *  VERSION:     1.0.0
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Utils;

use MultiTheftAuto\Sdk\Model\Element;
use MultiTheftAuto\Sdk\Model\Resource;
use PHPUnit\Framework\TestCase;

class TranslatorTest extends TestCase
{
    public function testItConvertsToObjects(): void
    {
        $input = '["^R^someResource","someString","^E^someElementId"]';
        $input = Translator::fromServer($input);

        $this->assertInstanceOf(Resource::class, $input[0]);
        $this->assertIsString($input[1]);
        $this->assertInstanceOf(Element::class, $input[2]);
    }

    public function testItConvertsObjects2(): void
    {
        $input = '[["^R^someResource","^R^someResource2"],"someString","^E^someElementId"]';
        $input = Translator::fromServer($input);

        $this->assertContainsOnlyInstancesOf(Resource::class, $input[0]);
        $this->assertIsString($input[1]);
        $this->assertInstanceOf(Element::class, $input[2]);
    }

    public function testItConvertsToServerFormat(): void
    {
        $array = [new Resource('someResource'), 'String', [new Element('someElement')], new Element('someElement2')];
        $output = Translator::toServer($array);
        $this->assertEquals('["^R^someResource","String",["^E^someElement"],"^E^someElement2"]', $output);
    }
}
