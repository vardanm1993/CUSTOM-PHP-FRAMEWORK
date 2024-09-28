<?php

namespace Core\Console\Commands\Model;

use Core\Console\Command;
use Core\Console\Traits\CustomMake;

class MakeModelCommand extends Command
{
    use CustomMake;

    protected string $signature = 'make:model {name}';
    protected string $description = 'Create a new model file';

    public function handle(): void
    {
        $this->make('model', dirname(__DIR__,4).'/app/Http/Models/');
    }

    private function getContent(string $className): string
    {
        return <<<EOT
<?php

namespace App\Http\Models;

use Core\Model;

class {$className} extends Model
{
}
EOT;
    }

}