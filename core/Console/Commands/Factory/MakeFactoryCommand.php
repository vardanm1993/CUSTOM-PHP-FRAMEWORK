<?php

namespace Core\Console\Commands\Factory;

use Core\Console\Command;
use Core\Console\Traits\CustomMake;

class MakeFactoryCommand extends Command
{
    use CustomMake;

    protected string $signature = 'make:factory {name}';
    protected string $description = 'Create a new factory file';

    public function handle(): void
    {
        $this->make('factory', dirname(__DIR__, 4) . '/database/factories/');
    }

    private function getContent(string $className): string
    {
        return <<<EOT
<?php

namespace Factories;

use Core\Factory;

class {$className} extends Factory
{
    public function definition(): array
    {
        return [
            // Define factory fields here
        ];
    }
}
EOT;
    }
}