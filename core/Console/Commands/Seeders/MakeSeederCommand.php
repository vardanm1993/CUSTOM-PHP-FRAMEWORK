<?php

namespace Core\Console\Commands\Seeders;

use Core\Console\Command;
use Core\Console\Traits\CustomMake;
use Core\Console\Traits\HasFactoryOrSeeder;
use Core\Exceptions\ContainerException;
use ReflectionException;

class MakeSeederCommand extends Command
{
    use CustomMake;

    protected string $signature = 'make:seeder {name}';
    protected string $description = 'Create a new seeder file';

    public function handle(): void
    {
        $this->make('seeder', dirname(__DIR__, 4) . '/database/seeders/');
    }

    private function getContent(string $className): string
    {
        return <<<EOT
<?php

namespace Seeders;

use Core\Seeder;

class {$className} extends Seeder
{
    public function run(): void
    {
        // Define seeding logic here
    }
}
EOT;
    }
}