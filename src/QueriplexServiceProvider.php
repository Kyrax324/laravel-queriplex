<?php

namespace Kyrax324\Queriplex;

use Illuminate\Support\ServiceProvider;

class QueriplexServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('queriplex', function($app) {
            return new Queriplex();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
