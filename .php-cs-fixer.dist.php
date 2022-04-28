<?php

$header = <<<EOF
PSR-15 Dispatcher by @ajgarlag

Copyright (C) Antonio J. García Lagar <aj@garcialagar.es>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

return (new \PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@Symfony' => true,
            '@PSR12' => true,
            '@PHP74Migration' => true,
            'header_comment' => ['header' => $header],
        ]
    )
    ->setFinder(
        \PhpCsFixer\Finder::create()
            ->in(__DIR__.'/src')
            ->in(__DIR__.'/spec')
    )
;
