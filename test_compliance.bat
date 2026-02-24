@echo off
echo === VERIFICACION DE COMPLIANCE ===
echo.
cd /d C:\xampp72\htdocs\Horae_Raquel
php -r "require 'vendor/autoload.php'; $app = require_once 'bootstrap/app.php'; $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); $kernel->bootstrap(); echo 'Usuarios activos: ' . App\User::where('baja', 0)->count() . PHP_EOL;"
php -r "require 'vendor/autoload.php'; $app = require_once 'bootstrap/app.php'; $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); $kernel->bootstrap(); echo 'Usuarios dados de baja: ' . App\User::where('baja', 1)->count() . PHP_EOL;"
php -r "require 'vendor/autoload.php'; $app = require_once 'bootstrap/app.php'; $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); $kernel->bootstrap(); echo 'Total fichajes: ' . App\Fichaje::count() . PHP_EOL;"
echo.
echo === PRUEBAS A REALIZAR ===
echo 1. Ve a /eunomia/users
echo 2. Da de baja a un usuario
echo 3. Ejecuta este script de nuevo
echo 4. Ve a /eunomia/fichajes y verifica que los fichajes del usuario siguen ahi
pause