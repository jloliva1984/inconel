<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Viviendas - Inconel Building</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #1a3a5c; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #1a3a5c; font-size: 20px; }
        .header h2 { color: #555; font-size: 14px; font-weight: normal; }
        .header .date { color: #888; font-size: 11px; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #1a3a5c; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f5f5f5; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .badge-success { background: #28a745; color: white; }
        .badge-danger { background: #dc3545; color: white; }
        .badge-warning { background: #ffc107; color: #333; }
        .footer { margin-top: 20px; text-align: center; color: #888; font-size: 10px; border-top: 1px solid #ddd; padding-top: 10px; }
        @media print { .no-print { display: none; } body { padding: 0; } }
    </style>
</head>
<body>
    <div class="no-print" style="text-align:center;margin-bottom:15px">
        <button onclick="window.print()" style="background:#1a3a5c;color:white;padding:8px 20px;border:none;border-radius:5px;cursor:pointer;font-size:13px">
            🖨️ Imprimir
        </button>
        <button onclick="window.close()" style="background:#6c757d;color:white;padding:8px 20px;border:none;border-radius:5px;cursor:pointer;font-size:13px;margin-left:10px">
            ✕ Cerrar
        </button>
    </div>

    <div class="header">
        <h1>🏢 INCONEL BUILDING</h1>
        <h2>Listado de Viviendas Registradas</h2>
        <div class="date">Generado el: <?= esc($fecha) ?> | Total: <?= count($viviendas) ?> registros</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Dirección</th>
                <th>Fecha Inst. A/C</th>
                <th>Serie Handler</th>
                <th>Serie Condenser</th>
                <th>Fecha Venta</th>
                <th>Técnico</th>
                <th>G. Labor</th>
                <th>G. Equip.</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($viviendas as $i => $v):
                $fv       = new DateTime($v['fecha_venta']);
                $hoy      = new DateTime();
                $labor    = (clone $fv)->modify('+1 year');
                $equip    = (clone $fv)->modify('+10 years');
                $diasL    = (int)$hoy->diff($labor)->format('%r%a');
                $diasE    = (int)$hoy->diff($equip)->format('%r%a');
                $stL      = $diasL > 30 ? 'Activa' : ($diasL > 0 ? 'Por Vencer' : 'Vencida');
                $stE      = $diasE > 90 ? 'Activa' : ($diasE > 0 ? 'Por Vencer' : 'Vencida');
                $classL   = $stL === 'Activa' ? 'success' : ($stL === 'Vencida' ? 'danger' : 'warning');
                $classE   = $stE === 'Activa' ? 'success' : ($stE === 'Vencida' ? 'danger' : 'warning');
            ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= esc($v['direccion']) ?></td>
                <td><?= date('d/m/Y', strtotime($v['fecha_instalacion_ac'])) ?></td>
                <td><?= esc($v['serie_handler']) ?></td>
                <td><?= esc($v['serie_condenser']) ?></td>
                <td><?= date('d/m/Y', strtotime($v['fecha_venta'])) ?></td>
                <td><?= esc($v['tecnico_nombre_completo']) ?></td>
                <td><span class="badge badge-<?= $classL ?>"><?= $stL ?></span></td>
                <td><span class="badge badge-<?= $classE ?>"><?= $stE ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        INCONEL BUILDING &mdash; Sistema de Gestión de Viviendas y Garantías &mdash; <?= date('Y') ?>
    </div>

    <script>
        // Auto-print after page loads
        // window.onload = () => window.print();
    </script>
</body>
</html>
