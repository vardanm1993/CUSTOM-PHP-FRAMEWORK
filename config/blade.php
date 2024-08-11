<?php

return [
    // Escaped output with blade syntax {{ ... }}
    '/{{\s*(.*?)\s*}}/' => '<?php echo htmlspecialchars($1 ?? "", ENT_QUOTES, \'UTF-8\'); ?>',

    // Unescaped output with blade syntax {!! ... !!}
    '/{!!\s*(.*?)\s*!!}/' => '<?php echo $1; ?>',

    // Function calls
    '/{{\s*([a-zA-Z_][a-zA-Z0-9_]*)\((.*?)\)\s*}}/' => '<?php echo $1($2); ?>',

    // PHP opening and closing tags for blade syntax
    '/@php/' => '<?php ',
    '/@endphp/' => ' ?>',

    // HTTP method input field
    '/@method\(\s*(.*?)\s*\)/' => '<input type="hidden" name="_method" value="$1">',

    // CSRF token input field
    '/@csrf/' => '<input type="hidden" name="_token" value="<?php echo \Core\CSRF::token(); ?>">',

    // Conditional statements
    '/@if\(\s*(.*?)\s*\)/' => '<?php if ($1): ?>',
    '/@elseif\(\s*(.*?)\s*\)/' => '<?php elseif ($1): ?>',
    '/@else/' => '<?php else: ?>',
    '/@endif/' => '<?php endif; ?>',

    // Loop statements
    '/@foreach\(\s*(.*?)\s*\)/' => '<?php foreach ($1 as $2): ?>',
    '/@endforeach/' => '<?php endforeach; ?>',
    '/@for\(\s*(.*?)\s*\)/' => '<?php for ($1): ?>',
    '/@endfor/' => '<?php endfor; ?>',
    '/@while\(\s*(.*?)\s*\)/' => '<?php while ($1): ?>',
    '/@endwhile/' => '<?php endwhile; ?>',

    // Variable checks
    '/@isset\(\s*(.*?)\s*\)/' => '<?php if (isset($1)): ?>',
    '/@endisset/' => '<?php endif; ?>',
    '/@empty\(\s*(.*?)\s*\)/' => '<?php if (empty($1)): ?>',
    '/@endempty/' => '<?php endif; ?>',

    // Include other templates
    '/@include\(\s*(.*?)\s*\)/' => '<?php echo \Core\View::render($1, get_defined_vars()); ?>',

    // Extending layouts
    '/@extends\(\s*(.*?)\s*\)/' => '<?php \Core\Blade::extend($1); ?>',

    // Section handling
    '/@section\(\s*(.*?)\s*\)/' => '<?php \Core\Blade::startSection($1); ?>',
    '/@endsection/' => '<?php \Core\Blade::endSection(); ?>',

    // Yielding sections
    '/@yield\(\s*(.*?)\s*\)/' => '<?php echo \Core\Blade::yieldSection($1); ?>'
];