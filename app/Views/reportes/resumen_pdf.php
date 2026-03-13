<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; font-size: 10px; }
.header { text-align: center; border-bottom: 2px solid #1a3a5c; padding-bottom: 8px; margin-bottom: 12px; }
h1 { color: #1a3a5c; font-size: 16px; }
h3 { color: #1a3a5c; font-size: 12px; margin: 15px 0 5px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
th { background: #1a3a5c; color: white; padding: 5px; }
td { padding: 4px 6px; border-bottom: 1px solid #eee; }
tr:nth-child(even) { background: #f9f9f9; }
</style>
</head>
<body>
<div class="header">
    <h1>INCONEL BUILDING — Resumen General</h1>
    <div style="color:#888;font-size:9px">Generado: <?= esc($fecha) ?></div>
</div>

<h3>Estadísticas de Garantías</h3>
<table>
    <thead><tr><th>Indicador</th><th>Cantidad</th></tr></thead>
    <tbody>
        <tr><td>Total Viviendas</td><td><strong><?= $stats['total_viviendas'] ?? 0 ?></strong></td></tr>
        <tr><td>Garantía Labor Activa</td><td><?= $stats['garantia_labor_activa'] ?? 0 ?></td></tr>
        <tr><td>Garantía Labor Por Vencer</td><td><?= $stats['garantia_labor_por_vencer'] ?? 0 ?></td></tr>
        <tr><td>Garantía Labor Vencida</td><td><?= $stats['garantia_labor_vencida'] ?? 0 ?></td></tr>
        <tr><td>Garantía Equip. Activa</td><td><?= $stats['garantia_equip_activa'] ?? 0 ?></td></tr>
        <tr><td>Garantía Equip. Por Vencer</td><td><?= $stats['garantia_equip_por_vencer'] ?? 0 ?></td></tr>
        <tr><td>Garantía Equip. Vencida</td><td><?= $stats['garantia_equip_vencida'] ?? 0 ?></td></tr>
    </tbody>
</table>

<h3>Por Técnico</h3>
<table>
    <thead>
        <tr>
            <th>Técnico</th><th>Total</th>
            <th>Labor Activa</th><th>Labor Vencida</th>
            <th>Equip. Activa</th><th>Equip. Vencida</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tecnicos as $t): ?>
        <tr>
            <td><?= esc($t['tecnico']) ?></td>
            <td><?= $t['total'] ?></td>
            <td><?= $t['labor_activa'] ?></td>
            <td><?= $t['labor_vencida'] ?></td>
            <td><?= $t['equip_activa'] ?></td>
            <td><?= $t['equip_vencida'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
