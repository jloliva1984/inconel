<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; font-size: 10px; }
.header { text-align: center; border-bottom: 2px solid #1a3a5c; padding-bottom: 8px; margin-bottom: 12px; }
h1 { color: #1a3a5c; font-size: 16px; }
table { width: 100%; border-collapse: collapse; }
th { background: #1a3a5c; color: white; padding: 5px; font-size: 9px; }
td { padding: 4px 5px; border-bottom: 1px solid #eee; text-align: center; font-size: 9px; }
td:nth-child(2) { text-align: left; }
tr:nth-child(even) { background: #f9f9f9; }
</style>
</head>
<body>
<div class="header">
    <h1>INCONEL BUILDING — Reporte por Técnico</h1>
    <div style="color:#888;font-size:9px">Generado: <?= esc($fecha) ?></div>
</div>
<table>
    <thead>
        <tr>
            <th>#</th><th>Técnico</th><th>Total</th>
            <th>Labor Activa</th><th>Labor Vencida</th>
            <th>Equip. Activa</th><th>Equip. Vencida</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($viviendas as $i => $v): ?>
        <tr>
            <td><?= $i+1 ?></td>
            <td><?= esc($v['tecnico']) ?></td>
            <td><strong><?= $v['total'] ?></strong></td>
            <td><?= $v['labor_activa'] ?></td>
            <td><?= $v['labor_vencida'] ?></td>
            <td><?= $v['equip_activa'] ?></td>
            <td><?= $v['equip_vencida'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
