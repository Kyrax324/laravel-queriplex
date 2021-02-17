<?php

namespace Kyrax324\Queriplex\Facades;

use Illuminate\Support\Facades\Facade;

class Queriplex extends Facade
{
    protected static function getFacadeAccessor()
    {	
        return 'queriplex';
    }
}