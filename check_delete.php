<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== VERIFICACIÓN CRÍTICA ===\n";
echo "Usuarios con baja=1: " . App\User::where('baja', 1)->count() . "\n";

$usersBaja = App\User::where('baja', 1)->get();
foreach($usersBaja as $user) {
    echo "Usuario de baja: ID={$user->id}, Nombre={$user->name}\n";
    $fichajes = App\Fichaje::where('user_id', $user->id)->count();
    echo "  - Fichajes del usuario: {$fichajes}\n";
}

echo "\nTotal fichajes en BD: " . App\Fichaje::count() . "\n";
?>