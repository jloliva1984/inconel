<?php
// TEMPORARY DIAGNOSTIC - delete after use
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'inconel_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

echo '<h3>Columnas en tabla viviendas:</h3><pre>';
$res = $conn->query("SHOW COLUMNS FROM viviendas");
while ($row = $res->fetch_assoc()) {
    echo $row['Field'] . ' (' . $row['Type'] . ')' . PHP_EOL;
}
echo '</pre>';

echo '<h3>Test query datatable:</h3><pre>';
$sql = "SELECT v.id, v.direccion, v.fecha_instalacion_ac, v.fecha_arranque_ac, v.serie_handler,
        v.serie_condenser, v.fecha_venta,
        CONCAT(u.nombre, ' ', u.apellido) AS tecnico,
        v.created_at,
        CASE WHEN DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) < CURDATE() THEN 'vencida' ELSE 'activa' END AS garantia_mano_obra,
        CASE WHEN DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) < CURDATE() THEN 'vencida' ELSE 'activa' END AS garantia_equipamiento
        FROM viviendas v
        JOIN usuarios u ON u.id = v.tecnico_id
        WHERE v.deleted_at IS NULL
        LIMIT 1";
$res = $conn->query($sql);
if ($conn->error) {
    echo 'ERROR: ' . $conn->error;
} else {
    echo 'OK - filas: ' . $res->num_rows;
}
echo '</pre>';
$conn->close();
echo '<p><b>Elimina este archivo cuando termines.</b></p>';
