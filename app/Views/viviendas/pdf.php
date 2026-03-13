<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #1a3a5c; padding-bottom: 8px; margin-bottom: 15px; }
        .header h1 { color: #1a3a5c; font-size: 16px; margin: 0; }
        .header h2 { color: #555; font-size: 11px; font-weight: normal; margin: 3px 0 0; }
        .date { color: #888; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #1a3a5c; color: white; padding: 5px 6px; text-align: left; font-size: 9px; }
        td { padding: 4px 6px; border-bottom: 1px solid #eee; font-size: 9px; }
        tr:nth-child(even) { background: #f9f9f9; }
        .badge { padding: 1px 6px; border-radius: 8px; font-size: 8px; font-weight: bold; }
        .success { background: #28a745; color: white; }
        .danger  { background: #dc3545; color: white; }
        .warning { background: #ffc107; color: #333; }
        .footer { margin-top: 15px; text-align: center; color: #888; font-size: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>INCONEL BUILDING</h1>
        <h2>Listado de Viviendas Registradas</h2>
        <div class="date">Generado el: <?= esc($fecha) ?> | Total: <?= count($viviendas) ?> registros</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Dirección</th>
                <th>F. Instalación</th>
                <th>Serie Handler</th>
                <th>Serie Condenser</th>
                <th>F. Venta</th>
                <th>Técnico</th>
                <th>G. Labor</th>
                <th>G. Equip.</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($viviendas as $i => $v):
                $fv    = new DateTime($v['fecha_venta']);
                $hoy   = new DateTime();
                $labor = (clone $fv)->modify('+1 year');
                $equip = (clone $fv)->modify('+10 years');
                $diasL = (int)$hoy->diff($labor)->format('%r%a');
                $diasE = (int)$hoy->diff($equip)->format('%r%a');
                $stL   = $diasL > 30 ? 'Activa' : ($diasL > 0 ? 'Por Vencer' : 'Vencida');
                $stE   = $diasE > 90 ? 'Activa' : ($diasE > 0 ? 'Por Vencer' : 'Vencida');
                $clsL  = $stL === 'Activa' ? 'success' : ($stL === 'Vencida' ? 'danger' : 'warning');
                $clsE  = $stE === 'Activa' ? 'success' : ($stE === 'Vencida' ? 'danger' : 'warning');
            ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= esc(substr($v['direccion'], 0, 40)) ?></td>
                <td><?= date('d/m/Y', strtotime($v['fecha_instalacion_ac'])) ?></td>
                <td><?= esc($v['serie_handler']) ?></td>
                <td><?= esc($v['serie_condenser']) ?></td>
                <td><?= date('d/m/Y', strtotime($v['fecha_venta'])) ?></td>
                <td><?= esc($v['tecnico_nombre_completo']) ?></td>
                <td><span class="badge <?= $clsL ?>"><?= $stL ?></span></td>
                <td><span class="badge <?= $clsE ?>"><?= $stE ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        INCONEL BUILDING &mdash; Sistema de Gestión de Viviendas y Garantías &mdash; <?= date('Y') ?>
    </div>
</body>
</html>
