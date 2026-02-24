<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "Laravel Version: " . app()->version() . "\n";
echo "Artisan funciona correctamente!\n";

$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);

echo "Exit status: $status\n";

$kernel->terminate($input, $status);

exit($status);
