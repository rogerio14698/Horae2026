<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== PREPARAR USUARIO DE PRUEBA ===\n";

// Encontrar un usuario para dar de baja temporalmente
$user = App\User::where('baja', 0)->where('role_id', 2)->first();

if ($user) {
    echo "Dando de baja temporalmente a: {$user->name} (ID: {$user->id})\n";
    $user->baja = 1;
    $user->save();
    echo "✅ Usuario dado de baja para pruebas\n";
    echo "Ahora ve a /eunomia/fichajes?estado=inactivos para probar la reactivación\n";
} else {
    echo "❌ No se encontró usuario disponible para pruebas\n";
}
?>