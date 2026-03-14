<?php
/**
 * ONE-TIME diagnostic script — DELETE AFTER USE
 * Visit: https://yourdomain.com/check_user.php?key=InconelCheck2024
 */

if (($_GET['key'] ?? '') !== 'InconelCheck2024') {
    http_response_code(403);
    die('Access denied.');
}

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(dirname(__DIR__));
require FCPATH . '../vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();

echo '<pre style="font-family:monospace;padding:20px">';
echo "=== Diagnóstico de login ===\n\n";

// Get raw user data (bypassing soft deletes)
$user = $db->table('usuarios')->where('email', 'admin@inconel.com')->get()->getRowArray();

if (! $user) {
    echo "✗ NO existe ningún usuario con email admin@inconel.com\n";
    echo "</pre>";
    exit;
}

echo "--- Estado del usuario ---\n";
echo "activo:     {$user['activo']} " . ($user['activo'] == 1 ? "✓" : "✗ INACTIVO — esto impide el login") . "\n";
echo "deleted_at: " . ($user['deleted_at'] ?? 'NULL') . " " . ($user['deleted_at'] ? "✗ SOFT-DELETED — esto impide el login" : "✓") . "\n";
echo "email:      [{$user['email']}] (¿espacios extra?)\n\n";

echo "--- Verificación de contraseña ---\n";
$passwords = ['Admin@123', 'admin@123', 'Admin123', 'admin123', '123456', 'password'];
foreach ($passwords as $p) {
    $ok = password_verify($p, $user['password']);
    if ($ok) {
        echo "✓ Contraseña correcta: '{$p}'\n";
        break;
    }
}
if (! isset($ok) || ! $ok) {
    echo "✗ Ninguna contraseña común coincide. El hash en BD:\n";
    echo "  " . $user['password'] . "\n";
}

echo "\n--- Fix automático ---\n";
// Fix activo and deleted_at
$db->table('usuarios')->where('email', 'admin@inconel.com')->update([
    'activo'     => 1,
    'deleted_at' => null,
    'updated_at' => date('Y-m-d H:i:s'),
]);
echo "✓ activo=1 y deleted_at=NULL aplicados.\n";

// Reset password to known value
$newHash = password_hash('Admin@123', PASSWORD_BCRYPT);
$db->table('usuarios')->where('email', 'admin@inconel.com')->update([
    'password'   => $newHash,
    'updated_at' => date('Y-m-d H:i:s'),
]);
echo "✓ Contraseña reseteada a: Admin@123\n\n";

echo "--- Sesiones ---\n";
$sessionPath = ini_get('session.save_path') ?: sys_get_temp_dir();
echo "session.save_path: {$sessionPath}\n";
echo "Writable: " . (is_writable($sessionPath) ? "✓ SÍ" : "✗ NO — problema de sesiones") . "\n\n";

echo "=== Intenta login ahora con: admin@inconel.com / Admin@123 ===\n";
echo "\n⚠ Elimina este archivo del servidor después.\n";
echo '</pre>';
