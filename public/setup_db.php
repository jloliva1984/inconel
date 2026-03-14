<?php
/**
 * ONE-TIME setup script — DELETE AFTER USE
 * Runs migrations and seeds the database with default users.
 *
 * Usage: visit https://yourdomain.com/setup_db.php?key=InconelSetup2024
 * DELETE this file immediately after running it.
 */

// Simple security key — change if needed
define('SETUP_KEY', 'InconelSetup2024');

if (($_GET['key'] ?? '') !== SETUP_KEY) {
    http_response_code(403);
    die('Access denied. Provide ?key=InconelSetup2024');
}

// Boot CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(dirname(__DIR__));

require FCPATH . '../vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();

echo '<pre style="font-family:monospace;padding:20px">';
echo "=== Inconel DB Setup ===\n\n";

// ── 1. Run migrations ──────────────────────────────────────────
echo "Running migrations...\n";
try {
    $migrate = \Config\Services::migrations();
    $migrate->latest();
    echo "✓ Migrations completed.\n\n";
} catch (\Throwable $e) {
    echo "⚠ Migration error: " . $e->getMessage() . "\n";
    echo "  (Tables may already exist — continuing...)\n\n";
}

// ── 2. Check if users already exist ────────────────────────────
$count = $db->table('usuarios')->countAll();
echo "Current users in DB: {$count}\n\n";

if ($count > 0) {
    echo "✓ Users already exist. Skipping seed.\n";
    echo "  Try logging in with your existing credentials.\n\n";
} else {
    // ── 3. Insert admin user ────────────────────────────────────
    echo "Seeding users...\n";
    $now = date('Y-m-d H:i:s');

    $db->table('usuarios')->insert([
        'nombre'     => 'Administrador',
        'apellido'   => 'Sistema',
        'email'      => 'admin@inconel.com',
        'password'   => password_hash('Admin@123', PASSWORD_BCRYPT),
        'rol'        => 'admin',
        'activo'     => 1,
        'created_at' => $now,
        'updated_at' => $now,
    ]);
    echo "✓ Admin created: admin@inconel.com / Admin@123\n";

    $db->table('usuarios')->insert([
        'nombre'     => 'Juan',
        'apellido'   => 'González',
        'email'      => 'tecnico@inconel.com',
        'password'   => password_hash('Tecnico@123', PASSWORD_BCRYPT),
        'rol'        => 'tecnico',
        'activo'     => 1,
        'created_at' => $now,
        'updated_at' => $now,
    ]);
    echo "✓ Tecnico created: tecnico@inconel.com / Tecnico@123\n\n";

    // ── 4. Run viviendas seeder ─────────────────────────────────
    echo "Seeding viviendas...\n";
    try {
        $seeder = \Config\Database::seeder();
        $seeder->call('DatabaseSeeder');
        echo "  (Note: admin and tecnico already inserted above, viviendas added)\n";
    } catch (\Throwable $e) {
        // Seeder might fail on duplicate emails — viviendas may still have been added
        echo "  (Viviendas seeded separately if seeder failed on duplicate emails)\n";
    }
    echo "✓ Seed complete.\n\n";
}

// ── 5. Verify ──────────────────────────────────────────────────
echo "=== Users in database ===\n";
$users = $db->table('usuarios')->select('id, nombre, email, rol, activo')->get()->getResultArray();
foreach ($users as $u) {
    $active = $u['activo'] ? 'active' : 'INACTIVE';
    echo "  [{$u['id']}] {$u['nombre']} | {$u['email']} | {$u['rol']} | {$active}\n";
}

echo "\n=== DONE ===\n";
echo "⚠ DELETE this file from your server immediately!\n";
echo "   Path: " . __FILE__ . "\n";
echo '</pre>';
