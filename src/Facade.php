<?php

namespace Kyrax324\Queriplex;

use Illuminate\Support\Facades\Facade as LaravelFacade;

class Facade extends LaravelFacade
{
    protected static function getFacadeAccessor()
    {	
        return 'queriplex';
    }
}