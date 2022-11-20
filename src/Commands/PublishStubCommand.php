<?php

namespace Kyrax324\Queriplex\Commands;

use Illuminate\Console\Command;

class PublishStubCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queriplex:publish-stub';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish queriplex stub';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' => 'queriplex-stub',
        ]);
    }
}
