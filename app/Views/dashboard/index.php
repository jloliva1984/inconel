<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$pageTitle   = 'Dashboard';
$breadcrumbs = [
    ['label' => 'Inicio', 'url' => site_url('dashboard'), 'active' => true],
];
?>

<!-- Stats Cards -->
<div class="row">
    <!-- Total Viviendas -->
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $stats['total_viviendas'] ?? 0 ?></h3>
                <p>Total Viviendas</p>
            </div>
            <div class="icon"><i class="fas fa-home"></i></div>
            <a href="<?= site_url('viviendas') ?>" class="small-box-footer">
                Ver más <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Garantía Labor Activa -->
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $stats['garantia_labor_activa'] ?? 0 ?></h3>
                <p>G. Mano Obra Activa</p>
            </div>
            <div class="icon"><i class="fas fa-tools"></i></div>
            <a href="<?= site_url('reportes/garantias') ?>" class="small-box-footer">
                Ver reporte <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Garantía Labor Por Vencer -->
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $stats['garantia_labor_por_vencer'] ?? 0 ?></h3>
                <p>G. Labor Por Vencer (30d)</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            <a href="<?= site_url('reportes/vencimientos') ?>" class="small-box-footer">
                Ver reporte <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Garantías Vencidas -->
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $stats['garantia_labor_vencida'] ?? 0 ?></h3>
                <p>G. Mano Obra Vencida</p>
            </div>
            <div class="icon"><i class="fas fa-times-circle"></i></div>
            <a href="<?= site_url('reportes/garantias') ?>" class="small-box-footer">
                Ver reporte <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Second row stats -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="small-box" style="background:#17a2b8;color:#fff">
            <div class="inner">
                <h3><?= $stats['garantia_equip_activa'] ?? 0 ?></h3>
                <p>G. Equipamiento Activa</p>
            </div>
            <div class="icon"><i class="fas fa-snowflake"></i></div>
            <a href="<?= site_url('reportes/garantias') ?>" class="small-box-footer">
                Ver reporte <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="small-box" style="background:#ffc107;color:#fff">
            <div class="inner">
                <h3><?= $stats['garantia_equip_por_vencer'] ?? 0 ?></h3>
                <p>G. Equip. Por Vencer (90d)</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <a href="<?= site_url('reportes/vencimientos') ?>" class="small-box-footer">
                Ver reporte <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="small-box" style="background:#dc3545;color:#fff">
            <div class="inner">
                <h3><?= $stats['garantia_equip_vencida'] ?? 0 ?></h3>
                <p>G. Equipamiento Vencida</p>
            </div>
            <div class="icon"><i class="fas fa-ban"></i></div>
            <a href="<?= site_url('reportes/garantias') ?>" class="small-box-footer">
                Ver reporte <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <?php if (session()->get('user_role') === 'admin'): ?>
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3><?= $totalUsuarios ?></h3>
                <p>Usuarios Activos</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="<?= site_url('usuarios') ?>" class="small-box-footer">
                Gestionar <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Tables Row -->
<div class="row">
    <!-- Últimas Viviendas -->
    <div class="col-lg-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-home mr-2"></i> Últimas Viviendas Registradas
                </h3>
                <div class="card-tools">
                    <a href="<?= site_url('viviendas') ?>" class="btn btn-sm btn-primary">
                        Ver todas <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Dirección</th>
                                <th>Fecha Venta</th>
                                <th>Técnico</th>
                                <th>G. Labor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recientes)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    No hay viviendas registradas aún.
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($recientes as $v): ?>
                            <tr>
                                <td>
                                    <a href="<?= site_url('viviendas/ver/' . $v['id']) ?>">
                                        <?= esc(substr($v['direccion'], 0, 45)) ?><?= strlen($v['direccion']) > 45 ? '...' : '' ?>
                                    </a>
                                </td>
                                <td><?= (! empty($v['fecha_venta']) && $v['fecha_venta'] !== '0000-00-00') ? date('m/d/Y', strtotime($v['fecha_venta'])) : '<span class="text-muted">—</span>' ?></td>
                                <td><?= esc($v['tecnico']) ?></td>
                                <td><?= warrantyBadge($v['garantia_mano_obra']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Garantías por Vencer -->
    <div class="col-lg-4">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle mr-2 text-warning"></i> Por Vencer (30 días)
                </h3>
            </div>
            <div class="card-body p-0">
                <?php if (empty($porVencer)): ?>
                <div class="text-center text-muted py-4">
                    <i class="fas fa-check-circle fa-2x text-success mb-2 d-block"></i>
                    No hay garantías próximas a vencer.
                </div>
                <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($porVencer as $v): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-start px-3 py-2">
                        <div>
                            <a href="<?= site_url('viviendas/ver/' . $v['id']) ?>" class="d-block fw-semibold" style="font-size:13px">
                                <?= esc(substr($v['direccion'], 0, 35)) ?>...
                            </a>
                            <small class="text-muted">
                                Vence: <?= date('m/d/Y', strtotime($v['vencimiento_labor'])) ?>
                            </small>
                        </div>
                        <span class="badge bg-warning text-dark">
                            <?= $v['dias_restantes'] ?>d
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bolt mr-2"></i> Accesos Rápidos</h3>
            </div>
            <div class="card-body">
                <a href="<?= site_url('viviendas/crear') ?>" class="btn btn-success btn-block mb-2">
                    <i class="fas fa-plus mr-2"></i> Nueva Vivienda
                </a>
                <a href="<?= site_url('reportes/garantias') ?>" class="btn btn-info btn-block mb-2">
                    <i class="fas fa-shield-alt mr-2"></i> Ver Garantías
                </a>
                <a href="<?= site_url('reportes/resumen') ?>" class="btn btn-primary btn-block">
                    <i class="fas fa-chart-pie mr-2"></i> Resumen General
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function() {
    // Auto-close alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});

function warrantyBadgeJS(status) {
    const map = {
        'activa':     '<span class="badge bg-success">Activa</span>',
        'vencida':    '<span class="badge bg-danger">Vencida</span>',
        'por_vencer': '<span class="badge bg-warning text-dark">Por Vencer</span>',
    };
    return map[status] || status;
}
</script>
<?= $this->endSection() ?>

<?php
function warrantyBadge(string $status): string
{
    return match ($status) {
        'activa'     => '<span class="badge bg-success"><i class="fas fa-check-circle mr-1"></i>Activa</span>',
        'vencida'    => '<span class="badge bg-danger"><i class="fas fa-times-circle mr-1"></i>Vencida</span>',
        'por_vencer' => '<span class="badge bg-warning text-dark"><i class="fas fa-exclamation-circle mr-1"></i>Por Vencer</span>',
        default      => '<span class="badge bg-secondary">' . $status . '</span>',
    };
}
?>
