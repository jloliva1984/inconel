<?php
// TEMPORARY - delete after running
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'inconel_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$res = $conn->query("SHOW COLUMNS FROM viviendas LIKE 'fecha_arranque_ac'");
if ($res->num_rows > 0) {
    echo '<p style="color:green">✓ La columna <b>fecha_arranque_ac</b> ya existe.</p>';
} else {
    $conn->query("ALTER TABLE viviendas ADD COLUMN fecha_arranque_ac DATE NULL AFTER fecha_instalacion_ac");
    if ($conn->error) {
        echo '<p style="color:red">Error: ' . $conn->error . '</p>';
    } else {
        echo '<p style="color:green">✓ Columna <b>fecha_arranque_ac</b> agregada correctamente.</p>';
    }
}

$conn->close();
echo '<p><b>Elimina este archivo cuando termines.</b></p>';
