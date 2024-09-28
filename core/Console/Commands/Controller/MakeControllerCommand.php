<?php

namespace Core\Console\Commands\Controller;

use Core\Console\Command;
use Core\Console\Traits\CustomMake;

class MakeControllerCommand extends Command
{
    use CustomMake;

    protected string $signature = 'make:controller {name}';
    protected string $description = 'Create a new controller file';

    public function handle(): void
    {
        $this->make('controller', dirname(__DIR__,4).'/app/Http/Controllers/');
    }

    private function getContent(string $className): string
    {
        return <<<EOT
<?php

namespace App\Http\Controllers;

use Core\Request;

class {$className}
{
    public function __construct(protected Request \$request)
    {
    }

    public function index()
    {
        // Code for listing resources
    }

    public function create()
    {
        // Code for showing form to create a resource
    }

    public function store()
    {
        // Code for storing a new resource
    }

    public function show(\$id)
    {
        // Code for displaying a single resource
    }

    public function edit(\$id)
    {
        // Code for showing form to edit a resource
    }

    public function update(\$id)
    {
        // Code for updating a resource
    }

    public function destroy(\$id)
    {
        // Code for deleting a resource
    }
}
EOT;
    }

}