<?php
// Reset admin password directly via PDO
// DELETE THIS FILE AFTER USE

$host = '';
$db   = '';
$user = '';
$pass = '';
$port = 3306;

// Load .env
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        [$key, $val] = explode('=', $line, 2);
        $key = trim($key);
        $val = trim($val, " \t\n\r\0\x0B\"'");
        switch ($key) {
            case 'database.default.hostname': $host = $val; break;
            case 'database.default.database': $db   = $val; break;
            case 'database.default.username': $user = $val; break;
            case 'database.default.password': $pass = $val; break;
            case 'database.default.port':     $port = (int)$val; break;
        }
    }
}

$newPassword = 'Admin@123';
$newHash     = password_hash($newPassword, PASSWORD_BCRYPT);

echo "<pre>";
echo "Configuracion BD:\n";
echo "  Host: $host\n";
echo "  DB:   $db\n";
echo "  User: $user\n\n";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, email, password FROM usuarios WHERE email = 'admin@inconel.com' LIMIT 1");
    $row  = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo "ERROR: Usuario admin@inconel.com NO encontrado\n";
        exit;
    }

    echo "Usuario encontrado:\n";
    echo "  ID:    {$row['id']}\n";
    echo "  Email: {$row['email']}\n";
    echo "  Hash actual: {$row['password']}\n\n";

    $isValidBcrypt = (substr($row['password'], 0, 4) === '$2y$' || substr($row['password'], 0, 4) === '$2a$');
    echo "El hash actual es bcrypt? " . ($isValidBcrypt ? "SI" : "NO (ese es el problema)") . "\n\n";

    $update = $pdo->prepare("UPDATE usuarios SET password = ?, updated_at = NOW() WHERE email = 'admin@inconel.com'");
    $update->execute([$newHash]);

    echo "Contrasena actualizada correctamente\n";
    echo "  Nueva contrasena: $newPassword\n";
    echo "  Nuevo hash:       $newHash\n\n";

    $stmt2 = $pdo->query("SELECT password FROM usuarios WHERE email = 'admin@inconel.com' LIMIT 1");
    $row2  = $stmt2->fetch(PDO::FETCH_ASSOC);
    $ok    = password_verify($newPassword, $row2['password']);
    echo "Verificacion con password_verify(): " . ($ok ? "OK - COINCIDE" : "ERROR - NO coincide") . "\n\n";

    if ($ok) {
        echo "===========================================\n";
        echo "Ahora puedes iniciar sesion con:\n";
        echo "  Email:      admin@inconel.com\n";
        echo "  Contrasena: $newPassword\n";
        echo "===========================================\n";
        echo "\nELIMINA ESTE ARCHIVO despues de usarlo!\n";
    }

} catch (PDOException $e) {
    echo "Error de BD: " . $e->getMessage() . "\n";
}
echo "</pre>";
