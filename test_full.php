<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Test Full ===\n";

try {
    echo "1. Autoload...\n";
    require __DIR__.'/vendor/autoload.php';
    
    echo "2. Bootstrap...\n";
    $app = require_once __DIR__.'/bootstrap/app.php';
    
    echo "3. Kernel...\n";
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    echo "4. All Commands...\n";
    $commands = $kernel->all();
    echo "   Total commands: " . count($commands) . "\n";
    
    echo "5. Creating Input/Output...\n";
    $input = new Symfony\Component\Console\Input\ArgvInput(['artisan', '--version']);
    
    // Probar con diferentes tipos de output
    echo "6. Probando StreamOutput...\n";
    $output = new Symfony\Component\Console\Output\StreamOutput(fopen('php://stdout', 'w'));
    $output->writeln("Test output working!");
    
    echo "7. Ejecutando handle...\n";
    $status = $kernel->handle($input, $output);
    
    echo "8. Status: $status\n";
    
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
