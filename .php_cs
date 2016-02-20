<?php

return call_user_func(function () {

    $finder = \Symfony\Component\Finder\Finder::create()
        ->files()
        ->ignoreDotFiles(true)
        ->ignoreVCS(true)
        ->name('*.php')
        ->exclude('vendor')
        ->in(__DIR__);

    $fixers = array(
        '-concat_without_spaces',
        '-empty_return',
        '-phpdoc_params',
        '-phpdoc_params',
        '-phpdoc_short_description',
        '-unalign_double_arrow',
        '-unalign_equals',
        '-unary_operators_spaces',
        'align_double_arrow',
        'align_equals',
        'concat_with_spaces',
        'long_array_syntax',
        'multiline_spaces_before_semicolon',
        'ordered_use',
        'phpdoc_order',
    );

    return \Symfony\CS\Config\Config::create()
        ->level(\Symfony\CS\FixerInterface::SYMFONY_LEVEL)
        ->fixers($fixers)
        ->finder($finder);
});
