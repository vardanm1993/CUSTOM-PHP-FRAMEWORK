<?php

namespace Core\Console\Commands;

use Core\Console\Command;
use Core\Console\Traits\MigrationCommandTrait;
use Core\Exceptions\ContainerException;
use ReflectionException;

class MakeMigrationCommand extends Command
{

    use MigrationCommandTrait;

    protected string $signature = 'make:migration {name} {--table=}';
    protected string $description = 'Create a new migration file';

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function handle()
    {
        $name = $this->argument('name');

        if (empty($name)) {
            echo "Migration name is required.\n";
            return;
        }

        $table = $this->option('table') ?? $this->getTableName($name);

        $timestamp = date('Y_m_d_His');
        $filename = $timestamp . '_' . $name . '.php';
        $path = dirname(__DIR__) . '/../../database/migrations/' . $filename;

        if ($this->migrationFileExists($name)) {
            echo "Migration file already exists: $filename\n";
            return;
        }

        $className = $this->getMigrationClassName($name, true);

        $content = $this->getContent($table, $className);

        file_put_contents($path, $content);
        chmod($path, 0777);

        echo "Migration created: $filename\n";
    }

    private function getTableName(string $name): string
    {
        return strtolower(
            str_replace('_table', '', str_replace('create_', '', $name))
        );
    }

    private function getContent(string $table, string $className): string
    {
        return <<<EOT
<?php

namespace Migrations;

use Core\Migration\Blueprint;
use Core\Migration\Migration;

class {$className} extends Migration
{
    /**
     * @throws \ReflectionException
     * @throws \Core\Exceptions\ContainerException
     */
    public function up(): void
    {
        \$this->schema->create('{$table}', function (Blueprint \$table) {
            \$table->id();
            // Add your columns here
            \$table->timestamps();
        });
    }

    /**
     * @throws \Core\Exceptions\ContainerException
     * @throws \ReflectionException
     */
    public function down(): void
    {
        \$this->schema->dropIfExists('{$table}');
    }
}
EOT;
    }

    private function migrationFileExists(string $name): bool
    {
        $files = glob(dirname(__DIR__) . '/../../database/migrations/*_' . $name . '.php');
        return !empty($files);
    }
}