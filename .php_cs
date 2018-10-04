<?php
$finder = PhpCsFixer\Finder::create()
            ->files()
            ->in(__DIR__)
            ->exclude('vendor')
            ->notName("*.txt")
            ->name('*.php')
            ->ignoreDotFiles(true)
            ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true
    ])
    ->setFinder($finder);