<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$pageTitle   = 'Centro de Reportes';
$breadcrumbs = [
    ['label' => 'Inicio', 'url' => site_url('dashboard')],
    ['label' => 'Reportes', 'url' => '#', 'active' => true],
];
?>

<!-- Summary stats -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="callout callout-info">
            <h5><i class="fas fa-info-circle mr-2"></i>Resumen del Sistema</h5>
            <div class="row text-center">
                <div class="col-md-3 col-6 border-right">
                    <h3 class="text-primary"><?= $stats['total_viviendas'] ?? 0 ?></h3>
                    <p class="mb-0">Total Viviendas</p>
                </div>
                <div class="col-md-3 col-6 border-right">
                    <h3 class="text-success"><?= $stats['garantia_labor_activa'] ?? 0 ?></h3>
                    <p class="mb-0">G. Labor Activa</p>
                </div>
                <div class="col-md-3 col-6 border-right">
                    <h3 class="text-warning"><?= $stats['garantia_labor_por_vencer'] ?? 0 ?></h3>
                    <p class="mb-0">G. Labor Por Vencer</p>
                </div>
                <div class="col-md-3 col-6">
                    <h3 class="text-danger"><?= $stats['garantia_labor_vencida'] ?? 0 ?></h3>
                    <p class="mb-0">G. Labor Vencida</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Cards -->
<div class="row">
    <!-- Garantías -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-outline card-primary h-100">
            <div class="card-header text-center bg-primary text-white">
                <i class="fas fa-shield-alt fa-2x mb-2 d-block"></i>
                <h5 class="mb-0">Estado de Garantías</h5>
            </div>
            <div class="card-body text-center">
                <p class="text-muted">Reporte completo del estado de todas las garantías de mano de obra y equipamiento.</p>
            </div>
            <div class="card-footer">
                <a href="<?= site_url('reportes/garantias') ?>" class="btn btn-primary btn-block">
                    <i class="fas fa-eye mr-2"></i> Ver Reporte
                </a>
            </div>
        </div>
    </div>

    <!-- Por Técnico -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-outline card-warning h-100">
            <div class="card-header text-center bg-warning text-dark">
                <i class="fas fa-user-hard-hat fa-2x mb-2 d-block"></i>
                <h5 class="mb-0">Por Técnico</h5>
            </div>
            <div class="card-body text-center">
                <p class="text-muted">Estadísticas de viviendas registradas y estado de garantías por cada técnico.</p>
            </div>
            <div class="card-footer">
                <a href="<?= site_url('reportes/por-tecnico') ?>" class="btn btn-warning btn-block">
                    <i class="fas fa-eye mr-2"></i> Ver Reporte
                </a>
            </div>
        </div>
    </div>

    <!-- Vencimientos -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-outline card-danger h-100">
            <div class="card-header text-center bg-danger text-white">
                <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>
                <h5 class="mb-0">Vencimientos</h5>
            </div>
            <div class="card-body text-center">
                <p class="text-muted">Análisis de vencimientos próximos y garantías que ya han expirado.</p>
            </div>
            <div class="card-footer">
                <a href="<?= site_url('reportes/vencimientos') ?>" class="btn btn-danger btn-block">
                    <i class="fas fa-eye mr-2"></i> Ver Reporte
                </a>
            </div>
        </div>
    </div>

    <!-- Resumen General -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-outline card-success h-100">
            <div class="card-header text-center bg-success text-white">
                <i class="fas fa-chart-pie fa-2x mb-2 d-block"></i>
                <h5 class="mb-0">Resumen General</h5>
            </div>
            <div class="card-body text-center">
                <p class="text-muted">Vista consolidada de todas las estadísticas del sistema.</p>
            </div>
            <div class="card-footer">
                <a href="<?= site_url('reportes/resumen') ?>" class="btn btn-success btn-block">
                    <i class="fas fa-eye mr-2"></i> Ver Reporte
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Warranty legend -->
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h5 class="card-title"><i class="fas fa-info-circle mr-2"></i>Leyenda de Garantías</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-success fs-6 me-3">ACTIVA</span>
                            <span>Garantía vigente y sin riesgo inmediato.</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-warning text-dark fs-6 me-3">POR VENCER</span>
                            <span>Mano de obra: vence en ≤30 días. Equip.: ≤90 días.</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-danger fs-6 me-3">VENCIDA</span>
                            <span>La garantía ha expirado.</span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <i class="fas fa-tools text-primary mr-2"></i>
                        <strong>Garantía de Mano de Obra:</strong> 1 año desde la fecha de venta.
                    </div>
                    <div class="col-md-6">
                        <i class="fas fa-snowflake text-info mr-2"></i>
                        <strong>Garantía de Equipamiento:</strong> 10 años desde la fecha de venta.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
