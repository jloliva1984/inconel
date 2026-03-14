<?php
/**
 * ONE-TIME password reset script — DELETE AFTER USE
 * Visit: https://yourdomain.com/reset_password.php?key=InconelReset2024
 */

if (($_GET['key'] ?? '') !== 'InconelReset2024') {
    http_response_code(403);
    die('Access denied.');
}

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(dirname(__DIR__));
require FCPATH . '../vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

$db  = \Config\Database::connect();
$now = date('Y-m-d H:i:s');

echo '<pre style="font-family:monospace;padding:20px">';

// Show current state of admin user
$user = $db->table('usuarios')->where('email', 'admin@inconel.com')->get()->getRowArray();

if (! $user) {
    echo "ERROR: No existe usuario con email admin@inconel.com\n";
    echo '</pre>';
    exit;
}

echo "=== Usuario encontrado ===\n";
echo "ID:     {$user['id']}\n";
echo "Nombre: {$user['nombre']}\n";
echo "Email:  {$user['email']}\n";
echo "Rol:    {$user['rol']}\n";
echo "Activo: {$user['activo']}\n";
echo "Password actual (primeros 20 chars): " . substr($user['password'], 0, 20) . "...\n";
echo "¿Es bcrypt válido? " . (str_starts_with($user['password'], '$2') ? "SÍ ✓" : "NO ✗ — estaba en texto plano o MD5") . "\n\n";

// Reset password
$newPassword = 'Admin@123';
$hash        = password_hash($newPassword, PASSWORD_BCRYPT);

$db->table('usuarios')
   ->where('email', 'admin@inconel.com')
   ->update([
       'password'   => $hash,
       'activo'     => 1,
       'updated_at' => $now,
   ]);

echo "=== Contraseña reseteada ===\n";
echo "Email:      admin@inconel.com\n";
echo "Contraseña: {$newPassword}\n";
echo "Hash nuevo: " . substr($hash, 0, 30) . "...\n\n";

// Verify
$verify = password_verify($newPassword, $hash);
echo "Verificación: " . ($verify ? "✓ CORRECTA" : "✗ ERROR") . "\n\n";

echo "⚠ Inicia sesión y ELIMINA este archivo del servidor.\n";
echo '</pre>';
