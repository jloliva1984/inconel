<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; font-size: 9px; color: #333; }
.header { text-align: center; border-bottom: 2px solid #1a3a5c; padding-bottom: 8px; margin-bottom: 12px; }
.header h1 { color: #1a3a5c; font-size: 15px; margin: 0; }
.header h2 { font-size: 11px; font-weight: normal; margin: 3px 0; }
.sub { color: #888; font-size: 8px; }
table { width: 100%; border-collapse: collapse; }
th { background: #1a3a5c; color: white; padding: 4px 5px; font-size: 8px; }
td { padding: 3px 5px; border-bottom: 1px solid #eee; font-size: 8px; }
tr:nth-child(even) { background: #f9f9f9; }
.s { padding: 1px 5px; border-radius: 8px; font-size: 7px; font-weight: bold; }
.success { background: #28a745; color: white; }
.danger  { background: #dc3545; color: white; }
.warning { background: #ffc107; color: #333; }
.footer { margin-top: 12px; text-align: center; color: #888; font-size: 8px; }
</style>
</head>
<body>
<div class="header">
    <h1>INCONEL BUILDING</h1>
    <h2>Reporte de Estado de Garantías</h2>
    <div class="sub">
        Generado: <?= esc($fecha) ?> | Total: <?= count($viviendas) ?> registros<br>
        Garantía M. Obra: 1 año | Garantía Equipamiento: 10 años
    </div>
</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Dirección</th>
            <th>F. Venta</th>
            <th>Técnico</th>
            <th>Venc. M.O.</th>
            <th>G. Labor</th>
            <th>Venc. Equip.</th>
            <th>G. Equip.</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($viviendas as $i => $v):
            $clsL = $v['garantia_mano_obra'] === 'activa' ? 'success' : ($v['garantia_mano_obra'] === 'vencida' ? 'danger' : 'warning');
            $clsE = $v['garantia_equipamiento'] === 'activa' ? 'success' : ($v['garantia_equipamiento'] === 'vencida' ? 'danger' : 'warning');
            $txtL = $v['garantia_mano_obra'] === 'activa' ? 'Activa' : ($v['garantia_mano_obra'] === 'vencida' ? 'Vencida' : 'Por Vencer');
            $txtE = $v['garantia_equipamiento'] === 'activa' ? 'Activa' : ($v['garantia_equipamiento'] === 'vencida' ? 'Vencida' : 'Por Vencer');
        ?>
        <tr>
            <td><?= $i+1 ?></td>
            <td><?= esc(substr($v['direccion'], 0, 35)) ?></td>
            <td><?= date('m/d/Y', strtotime($v['fecha_venta'])) ?></td>
            <td><?= esc($v['tecnico']) ?></td>
            <td><?= date('m/d/Y', strtotime($v['vencimiento_mano_obra'])) ?></td>
            <td><span class="s <?= $clsL ?>"><?= $txtL ?></span></td>
            <td><?= date('m/d/Y', strtotime($v['vencimiento_equipamiento'])) ?></td>
            <td><span class="s <?= $clsE ?>"><?= $txtE ?></span></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="footer">INCONEL BUILDING — Sistema de Garantías — <?= date('Y') ?></div>
</body>
</html>
