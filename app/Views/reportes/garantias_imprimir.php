<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Garantías - Inconel Building</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #1a3a5c; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #1a3a5c; font-size: 20px; }
        .header h2 { color: #555; font-size: 14px; font-weight: normal; }
        .sub { color: #888; font-size: 11px; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #1a3a5c; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f5f5f5; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .badge-success { background: #28a745; color: white; }
        .badge-danger { background: #dc3545; color: white; }
        .badge-warning { background: #ffc107; color: #333; }
        .legend { margin: 15px 0; padding: 10px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; font-size: 10px; }
        .footer { margin-top: 20px; text-align: center; color: #888; font-size: 10px; border-top: 1px solid #ddd; padding-top: 10px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="text-align:center;margin-bottom:15px">
        <button onclick="window.print()" style="background:#1a3a5c;color:white;padding:8px 20px;border:none;border-radius:5px;cursor:pointer">
            🖨️ Imprimir
        </button>
        <button onclick="window.close()" style="background:#6c757d;color:white;padding:8px 20px;border:none;border-radius:5px;cursor:pointer;margin-left:10px">
            ✕ Cerrar
        </button>
    </div>

    <div class="header">
        <h1>🏢 INCONEL BUILDING</h1>
        <h2>Reporte de Estado de Garantías</h2>
        <div class="sub">
            Generado el: <?= esc($fecha) ?> | Total: <?= count($viviendas) ?> registros<br>
            Garantía de Mano de Obra: 1 año | Garantía de Equipamiento: 10 años
        </div>
    </div>

    <div class="legend">
        <strong>Leyenda:</strong>
        &nbsp;
        <span class="badge badge-success">Activa</span> Garantía vigente
        &nbsp;&nbsp;
        <span class="badge badge-warning">Por Vencer</span> M.O.: ≤30 días / Equip.: ≤90 días
        &nbsp;&nbsp;
        <span class="badge badge-danger">Vencida</span> Garantía expirada
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Dirección</th>
                <th>Fecha Venta</th>
                <th>Técnico</th>
                <th>Venc. M. Obra</th>
                <th>Estado M. Obra</th>
                <th>Venc. Equipamiento</th>
                <th>Estado Equip.</th>
                <th>Días M.O.</th>
                <th>Días Equip.</th>
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
                <td><?= $i + 1 ?></td>
                <td><?= esc(substr($v['direccion'], 0, 40)) ?></td>
                <td><?= date('m/d/Y', strtotime($v['fecha_venta'])) ?></td>
                <td><?= esc($v['tecnico']) ?></td>
                <td><?= date('m/d/Y', strtotime($v['vencimiento_mano_obra'])) ?></td>
                <td><span class="badge badge-<?= $clsL ?>"><?= $txtL ?></span></td>
                <td><?= date('m/d/Y', strtotime($v['vencimiento_equipamiento'])) ?></td>
                <td><span class="badge badge-<?= $clsE ?>"><?= $txtE ?></span></td>
                <td><?= $v['dias_garantia_labor'] ?></td>
                <td><?= $v['dias_garantia_equipamiento'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        INCONEL BUILDING &mdash; Sistema de Gestión de Viviendas y Garantías &mdash; <?= date('Y') ?>
    </div>
</body>
</html>
