<?php

namespace Core;

use Core\Exceptions\NotFoundTemplate;

class View
{
    /**
     * @throws NotFoundTemplate
     */
    public static function render(string $template, array $data = []): string
    {
        $templatePath = base_path('resources/views/' . $template . '.php');

        $bladePath = base_path('resources/views/' . $template . '.blade.php');

        if (!file_exists($templatePath) && !file_exists($bladePath)) {
            throw new \RuntimeException("Template file not found: " . $template);
        }

        if (file_exists($bladePath)) {
            return App::resolve(Blade::class)?->parseToBlade($bladePath,$data);
        }

        extract($data);

        ob_start();

        include $templatePath;

        return ob_get_clean();
    }

}