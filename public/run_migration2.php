<?php
// TEMPORARY - delete after running
$conn = new mysqli('127.0.0.1', 'root', '', 'inconel_db');
if ($conn->connect_error) { die('Error: ' . $conn->connect_error); }

$sqls = [
    "ALTER TABLE viviendas MODIFY COLUMN fecha_venta DATE NULL",
    "ALTER TABLE viviendas ADD COLUMN IF NOT EXISTS fecha_arranque_ac DATE NULL AFTER fecha_instalacion_ac",
    "UPDATE viviendas SET fecha_venta = NULL WHERE fecha_venta = '0000-00-00'",
    "UPDATE viviendas SET fecha_arranque_ac = NULL WHERE fecha_arranque_ac = '0000-00-00'",
];

foreach ($sqls as $sql) {
    $conn->query($sql);
    echo $conn->error ? '<p style="color:red">Error: ' . $conn->error . '</p>'
                      : '<p style="color:green">✓ ' . htmlspecialchars($sql) . '</p>';
}
$conn->close();
echo '<p><b>Elimina este archivo cuando termines.</b></p>';
