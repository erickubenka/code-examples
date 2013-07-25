<?php
include __DIR__.'/../vendor/autoload.php'; // composer autoload

$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
    'debug' => true,
    // 'cacheDir' => __DIR__.'/../tests/cache',
    // 'excludePaths' => [__DIR__.'/../vendor'],
    'includePaths' => [__DIR__.'/../src']
]);
?>