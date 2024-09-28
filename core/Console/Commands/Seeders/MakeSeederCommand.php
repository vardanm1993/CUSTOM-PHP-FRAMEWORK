<?php

namespace Core\Console\Commands\Seeders;

use Core\Console\Command;
use Core\Console\Traits\HasFactoryOrSeeder;
use Core\Exceptions\ContainerException;
use ReflectionException;
use Seeders\DatabaseSeeder;

class MakeSeederCommand extends Command
{
    use HasFactoryOrSeeder;

    protected string $signature = 'make:seeder {name}';
    protected string $description = 'Create a new seeder file';

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function handle(): void
    {
        $this->make('seeder');
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