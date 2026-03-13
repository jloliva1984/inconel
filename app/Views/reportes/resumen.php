<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$pageTitle   = 'Resumen General';
$breadcrumbs = [
    ['label' => 'Inicio', 'url' => site_url('dashboard')],
    ['label' => 'Reportes', 'url' => site_url('reportes')],
    ['label' => 'Resumen', 'url' => '#', 'active' => true],
];
?>

<div class="card card-outline card-success">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-chart-pie mr-2"></i> Resumen General del Sistema
        </h3>
        <div class="card-tools d-flex gap-2">
            <a href="<?= site_url('reportes/resumen-excel') ?>" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel mr-1"></i> Excel
            </a>
            <a href="<?= site_url('reportes/resumen-pdf') ?>" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>
        </div>
    </div>
    <div class="card-body">

        <div class="row">
            <!-- Stats -->
            <div class="col-md-6">
                <h5 class="mb-3"><i class="fas fa-home mr-2 text-primary"></i>Estadísticas Generales</h5>
                <table class="table table-bordered table-sm">
                    <tbody>
                        <tr class="table-primary">
                            <td><strong><i class="fas fa-home mr-2"></i>Total Viviendas</strong></td>
                            <td class="text-center fw-bold fs-5"><?= $stats['total_viviendas'] ?? 0 ?></td>
                        </tr>
                    </tbody>
                </table>

                <h6 class="mt-3 mb-2 text-tools">
                    <i class="fas fa-tools mr-2 text-warning"></i>Garantía Mano de Obra (1 año)
                </h6>
                <table class="table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <td><span class="badge bg-success me-2">Activa</span>Vigente</td>
                            <td class="text-center"><strong><?= $stats['garantia_labor_activa'] ?? 0 ?></strong></td>
                            <td>
                                <div class="progress" style="height:20px">
                                    <?php $pct = $stats['total_viviendas'] > 0 ? round(($stats['garantia_labor_activa'] / $stats['total_viviendas']) * 100) : 0; ?>
                                    <div class="progress-bar bg-success" style="width:<?= $pct ?>%"><?= $pct ?>%</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-warning text-dark me-2">Por Vencer</span>≤30 días</td>
                            <td class="text-center"><strong><?= $stats['garantia_labor_por_vencer'] ?? 0 ?></strong></td>
                            <td>
                                <div class="progress" style="height:20px">
                                    <?php $pct = $stats['total_viviendas'] > 0 ? round(($stats['garantia_labor_por_vencer'] / $stats['total_viviendas']) * 100) : 0; ?>
                                    <div class="progress-bar bg-warning" style="width:<?= $pct ?>%"><?= $pct ?>%</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-danger me-2">Vencida</span>Expirada</td>
                            <td class="text-center"><strong><?= $stats['garantia_labor_vencida'] ?? 0 ?></strong></td>
                            <td>
                                <div class="progress" style="height:20px">
                                    <?php $pct = $stats['total_viviendas'] > 0 ? round(($stats['garantia_labor_vencida'] / $stats['total_viviendas']) * 100) : 0; ?>
                                    <div class="progress-bar bg-danger" style="width:<?= $pct ?>%"><?= $pct ?>%</div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <h6 class="mt-3 mb-2">
                    <i class="fas fa-snowflake mr-2 text-info"></i>Garantía Equipamiento (10 años)
                </h6>
                <table class="table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <td><span class="badge bg-success me-2">Activa</span>Vigente</td>
                            <td class="text-center"><strong><?= $stats['garantia_equip_activa'] ?? 0 ?></strong></td>
                            <td>
                                <div class="progress" style="height:20px">
                                    <?php $pct = $stats['total_viviendas'] > 0 ? round(($stats['garantia_equip_activa'] / $stats['total_viviendas']) * 100) : 0; ?>
                                    <div class="progress-bar bg-success" style="width:<?= $pct ?>%"><?= $pct ?>%</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-warning text-dark me-2">Por Vencer</span>≤90 días</td>
                            <td class="text-center"><strong><?= $stats['garantia_equip_por_vencer'] ?? 0 ?></strong></td>
                            <td>
                                <div class="progress" style="height:20px">
                                    <?php $pct = $stats['total_viviendas'] > 0 ? round(($stats['garantia_equip_por_vencer'] / $stats['total_viviendas']) * 100) : 0; ?>
                                    <div class="progress-bar bg-warning" style="width:<?= $pct ?>%"><?= $pct ?>%</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-danger me-2">Vencida</span>Expirada</td>
                            <td class="text-center"><strong><?= $stats['garantia_equip_vencida'] ?? 0 ?></strong></td>
                            <td>
                                <div class="progress" style="height:20px">
                                    <?php $pct = $stats['total_viviendas'] > 0 ? round(($stats['garantia_equip_vencida'] / $stats['total_viviendas']) * 100) : 0; ?>
                                    <div class="progress-bar bg-danger" style="width:<?= $pct ?>%"><?= $pct ?>%</div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- By Technician -->
            <div class="col-md-6">
                <h5 class="mb-3"><i class="fas fa-users mr-2 text-warning"></i>Por Técnico</h5>
                <table class="table table-bordered table-sm table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Técnico</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Labor Activa</th>
                            <th class="text-center">Equip. Activa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tecnicos as $t): ?>
                        <tr>
                            <td><?= esc($t['tecnico']) ?></td>
                            <td class="text-center"><span class="badge bg-primary"><?= $t['total'] ?></span></td>
                            <td class="text-center"><span class="badge bg-success"><?= $t['labor_activa'] ?></span></td>
                            <td class="text-center"><span class="badge bg-info"><?= $t['equip_activa'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Quick links -->
                <div class="mt-4">
                    <h6><i class="fas fa-link mr-2"></i>Ver reportes detallados</h6>
                    <div class="d-flex flex-column gap-2">
                        <a href="<?= site_url('reportes/garantias') ?>" class="btn btn-primary">
                            <i class="fas fa-shield-alt mr-2"></i> Estado de Garantías
                        </a>
                        <a href="<?= site_url('reportes/por-tecnico') ?>" class="btn btn-warning">
                            <i class="fas fa-user-hard-hat mr-2"></i> Detalle por Técnico
                        </a>
                        <a href="<?= site_url('reportes/vencimientos') ?>" class="btn btn-danger">
                            <i class="fas fa-calendar-times mr-2"></i> Análisis de Vencimientos
                        </a>
                        <a href="<?= site_url('viviendas') ?>" class="btn btn-secondary">
                            <i class="fas fa-home mr-2"></i> Ver Todas las Viviendas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
