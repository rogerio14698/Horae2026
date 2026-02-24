<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== RECUPERACIÓN DE USUARIOS ===\n";

// Mostrar usuarios dados de baja
$usersBaja = App\User::where('baja', 1)->get();
echo "Usuarios dados de baja:\n";
foreach($usersBaja as $user) {
    $fichajes = App\Fichaje::where('user_id', $user->id)->count();
    echo "  ID: {$user->id} - {$user->name} - Fichajes: {$fichajes}\n";
}

echo "\n¿Recuperar todos? (y/n): ";
$handle = fopen("php://stdin", "r");
$choice = trim(fgets($handle));

if(strtolower($choice) === 'y') {
    App\User::where('baja', 1)->update(['baja' => 0]);
    echo "\n✅ TODOS LOS USUARIOS RECUPERADOS\n";
    echo "Ahora aparecerán en la lista de usuarios activos\n";
} else {
    echo "\n❌ Operación cancelada\n";
}

fclose($handle);
?>