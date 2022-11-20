<?php

namespace Kyrax324\Queriplex\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeQueriplexCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:queriplex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new queriplex class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Queriplex';

  	/**
     * Execute the console command.
     *
     * @return bool|null
	 */
    public function handle()
    {
        parent::handle();

        $this->makeFile();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
		return $this->resolveStubPath('/stubs/queriplex.stub');
    }

	/**
	* Resolve the fully-qualified path to the stub.
	*
	* @param  string  $stub
	* @return string
	*/
	protected function resolveStubPath($stub)
	{
		return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
					   ? $customPath
					   : __DIR__.$stub;
	}

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Queriplex';
    }

    /**
     * Create queriplex.
     *
     * @return void
     */
    protected function makeFile()
    {
        // Get the fully qualified class name (FQN)
        $class = $this->qualifyClass($this->getNameInput());

        // get the destination path, based on the default namespace
        $path = $this->getPath($class);

        $content = file_get_contents($path);
        $namespace = $this->getPath('');

        $replaceRules = [
            "{{namespace}}" => $namespace,
            "{{class}}" => $class,
        ];

        $stub = str_replace(
            array_keys($replaceRules),
            array_values($replaceRules),
            $content
        );

        file_put_contents($path, $stub);
    }
}