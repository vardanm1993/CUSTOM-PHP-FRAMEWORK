<?php

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        return BASE_PATH . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('ensure_leading_slash')) {
    function ensure_leading_slash(string $url): string
    {
        if ($url[0] !== '/') {
            $url = '/' . $url;
        }
        return $url;
    }
}


if (!function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param mixed ...$args
     * @return void
     */
    function dd(...$args)
    {
        echo '<style>
            body {
                background-color: #f6f7f8;
                color: #c5c8c6;
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            .dd-output {
                background-color: #1d1f21;
                color: #d9e6dd;
                padding: 20px;
                margin: 20px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            }
            pre {
                background-color: #0f0f10;
                color: #0aef58;
                padding: 10px;
                border-radius: 5px;
                overflow: auto;
            }
        </style>';

        foreach ($args as $arg) {
            echo '<div class="dd-output"><pre>';
            echo htmlspecialchars(print_r($arg, true));
            echo '</pre></div>';
        }

        die(1);
    }
}

if (!function_exists('dump')) {
    /**
     * Dump the passed variables without ending the script.
     *
     * @param mixed ...$args
     * @return void
     */
    function dump(...$args)
    {
        echo '<style>
            body {
                background-color: #f6f7f8;
                color: #333;
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            .dump-output {
                background-color: #fff;
                color: #333;
                padding: 20px;
                margin: 20px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            pre {
                background-color: #f9f9f9;
                color: #333;
                padding: 10px;
                border-radius: 5px;
                overflow: auto;
            }
        </style>';

        foreach ($args as $arg) {
            echo '<div class="dump-output"><pre>';
            var_dump($arg);
            echo '</pre></div>';
        }
    }
}

