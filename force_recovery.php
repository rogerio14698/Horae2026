<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== RECUPERANDO USUARIOS ===\n";

$recovered = App\User::where('baja', 1)->update(['baja' => 0]);
echo "✅ {$recovered} usuarios recuperados como activos\n";

echo "\nVerificación:\n";
echo "Usuarios activos ahora: " . App\User::where('baja', 0)->count() . "\n";
echo "Usuarios dados de baja: " . App\User::where('baja', 1)->count() . "\n";
?>