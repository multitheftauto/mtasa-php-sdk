<?php

/*****************************************************************************
 *
 *  PROJECT:     MTASA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        Element.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Model;

class Element
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function toString()
    {
        return '^E^' . $this->id;
    }
}
