<?php

$header = <<<EOF
PSR-15 Dispatcher by @ajgarlag

Copyright (C) Antonio J. García Lagar <aj@garcialagar.es>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

return \PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@Symfony' => true,
            'array_syntax' => array('syntax' => 'short'),
            'header_comment' => array('header' => $header),
            'ordered_imports' => true,
            'phpdoc_order' => true,
            'psr4' => true,
            'strict_comparison' => true,
            'strict_param' => true,
        ]
    )
    ->setFinder(
        \PhpCsFixer\Finder::create()
            ->in(__DIR__.'/src')
            ->in(__DIR__.'/spec')
    )
;
