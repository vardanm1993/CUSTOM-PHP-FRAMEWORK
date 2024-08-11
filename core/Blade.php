<?php

namespace Core;

use Core\Exceptions\NotFoundTemplate;

class Blade
{
    public static array $layouts = [];
    public static array $sections = [];
    public static array $sectionStack = [];

    public function __construct(public  array $patterns)
    {

    }

    public  function parseSyntax(string $content): string
    {

        foreach ($this->patterns as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }

        return $content;
    }

    public static function startSection(string $name): void
    {
        self::$sectionStack[] = $name;
        ob_start();
    }

    public static function endSection(): void
    {
        $last = array_pop(self::$sectionStack);
        self::$sections[$last] = ob_get_clean();
    }

    public static function yieldSection(string $name): string
    {
        return self::$sections[$name] ?? '';
    }

    public static function extend(string $layout): void
    {
        self::$layouts[] = $layout;
    }

    /**
     * @throws NotFoundTemplate
     */
    public function parseToBlade(string $bladePath , array $data): string
    {

        extract($data);

        ob_start();

        include $bladePath;

        $content = ob_get_clean();

        $parsedContent = $this->parseSyntax($content);

        ob_start();

        eval('?>' . $parsedContent);

        $parsedContent = ob_get_clean();

        if (!empty(self::$layouts)) {
            $layout = array_pop(self::$layouts);

            return View::render($layout, $data);
        }

        return $parsedContent;
    }

}