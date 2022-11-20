<?php

namespace Kyrax324\Queriplex;

use Illuminate\Support\ServiceProvider;
use Kyrax324\Queriplex\Commands\MakeQueriplexCommand;
use Kyrax324\Queriplex\Commands\PublishStubCommand;

class QueriplexServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
        $this->registerPublishing();
    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeQueriplexCommand::class,
                PublishStubCommand::class,
            ]);
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/stubs/queriplex.stub' => base_path('/stubs/queriplex.stub'),
            ], ['queriplex-stub']);
        }
    }
}
