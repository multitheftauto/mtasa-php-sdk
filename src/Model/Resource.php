<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        Resource.php
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Model;

class Resource
{
    var $name;
    private $server;

    function __construct($name, $server = null)
    {
        $this->name = $name;
        $this->server = $server;
    }

    function toString()
    {
        return "^R^" . $this->name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function call ( $function )
    {
        if (!$this->server) {
            throw new Exception(sprintf('Resource %s can not be called because server is not defined', $this->name));
        }

        $val = array();

        for ( $i = 1; $i < func_num_args(); $i++ )
        {
            $val[$i-1] = func_get_arg($i);
        }
        return $this->server->callFunction ( $this->name, $function, $val );
    }
}
