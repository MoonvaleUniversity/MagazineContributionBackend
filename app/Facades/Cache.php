<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Cache extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'mv_cache';
    }
}
