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

        if (!file_exists($templatePath)) {
            throw new NotFoundTemplate("Template file not found: " . $template);
        }

        extract($data);

        ob_start();

        include $templatePath;

        return ob_get_clean();
    }

}