<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$pageTitle   = 'Detalle de Vivienda';
$breadcrumbs = [
    ['label' => 'Inicio', 'url' => site_url('dashboard')],
    ['label' => 'Viviendas', 'url' => site_url('viviendas')],
    ['label' => 'Detalle', 'url' => '#', 'active' => true],
];
?>

<div class="row">
    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-home mr-2"></i> Información de la Vivienda
                </h3>
                <div class="card-tools">
                    <a href="<?= site_url('viviendas/editar/' . $vivienda['id']) ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i> Editar
                    </a>
                    <a href="<?= site_url('viviendas') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-light" style="width:35%">
                            <i class="fas fa-map-marker-alt mr-2 text-primary"></i>Dirección
                        </th>
                        <td><?= esc($vivienda['direccion']) ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">
                            <i class="fas fa-snowflake mr-2 text-info"></i>Fecha Instalación A/C
                        </th>
                        <td><?= date('d/m/Y', strtotime($vivienda['fecha_instalacion_ac'])) ?></td>
                    </tr>
                    <?php if (! empty($vivienda['fecha_arranque_ac'])): ?>
                    <tr>
                        <th class="bg-light">
                            <i class="fas fa-bolt mr-2 text-success"></i>Fecha Arranque A/C
                        </th>
                        <td><?= date('d/m/Y', strtotime($vivienda['fecha_arranque_ac'])) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th class="bg-light">
                            <i class="fas fa-barcode mr-2"></i>N° Serie Handler
                        </th>
                        <td><code><?= esc($vivienda['serie_handler']) ?></code></td>
                    </tr>
                    <tr>
                        <th class="bg-light">
                            <i class="fas fa-barcode mr-2"></i>N° Serie Condenser
                        </th>
                        <td><code><?= esc($vivienda['serie_condenser']) ?></code></td>
                    </tr>
                    <?php if (! empty($vivienda['fecha_venta'])): ?>
                    <tr>
                        <th class="bg-light">
                            <i class="fas fa-calendar-check mr-2 text-success"></i>Fecha de Venta
                        </th>
                        <td><?= date('d/m/Y', strtotime($vivienda['fecha_venta'])) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th class="bg-light">
                            <i class="fas fa-user-hard-hat mr-2 text-warning"></i>Técnico
                        </th>
                        <td><?= esc($vivienda['tecnico_nombre_completo']) ?></td>
                    </tr>
                    <?php if ($vivienda['notas']): ?>
                    <tr>
                        <th class="bg-light">
                            <i class="fas fa-sticky-note mr-2"></i>Notas
                        </th>
                        <td><?= nl2br(esc($vivienda['notas'])) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th class="bg-light"><i class="fas fa-clock mr-2"></i>Registrado</th>
                        <td><?= date('d/m/Y H:i', strtotime($vivienda['created_at'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Warranty Card -->
    <div class="col-md-4">
        <?php if ($vivienda['garantia_labor'] === 'sin_fecha'): ?>
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-tools mr-2"></i> Garantías</h3>
            </div>
            <div class="card-body text-center text-muted">
                <i class="fas fa-calendar-times fa-2x mb-2"></i>
                <p>Sin fecha de venta registrada.<br>Las garantías se calculan una vez se ingrese la fecha de venta.</p>
            </div>
        </div>
        <?php else: ?>
        <?php
        $laborClass = match($vivienda['garantia_labor']) {
            'activa' => 'success', 'vencida' => 'danger', default => 'warning'
        };
        $laborText = match($vivienda['garantia_labor']) {
            'activa' => 'ACTIVA', 'vencida' => 'VENCIDA', default => 'POR VENCER'
        };
        $equipClass = match($vivienda['garantia_equipamiento']) {
            'activa' => 'success', 'vencida' => 'danger', default => 'warning'
        };
        $equipText = match($vivienda['garantia_equipamiento']) {
            'activa' => 'ACTIVA', 'vencida' => 'VENCIDA', default => 'POR VENCER'
        };
        ?>
        <div class="card card-outline card-<?= $laborClass ?>">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tools mr-2"></i> Garantía Mano de Obra
                </h3>
            </div>
            <div class="card-body text-center">
                <div class="badge bg-<?= $laborClass ?> fs-5 px-4 py-2 mb-3"><?= $laborText ?></div>
                <table class="table table-sm table-bordered mt-2">
                    <tr><th>Inicio</th><td><?= date('d/m/Y', strtotime($vivienda['fecha_venta'])) ?></td></tr>
                    <tr><th>Vencimiento</th><td><?= date('d/m/Y', strtotime($vivienda['vencimiento_labor'])) ?></td></tr>
                    <tr>
                        <th>Días</th>
                        <td>
                            <?php if ($vivienda['dias_labor'] > 0): ?>
                            <span class="text-<?= $laborClass ?>"><?= $vivienda['dias_labor'] ?> días restantes</span>
                            <?php else: ?>
                            <span class="text-danger">Vencida hace <?= abs($vivienda['dias_labor']) ?> días</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr><th>Período</th><td>1 año</td></tr>
                </table>
            </div>
        </div>

        <div class="card card-outline card-<?= $equipClass ?>">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-snowflake mr-2"></i> Garantía Equipamiento
                </h3>
            </div>
            <div class="card-body text-center">
                <div class="badge bg-<?= $equipClass ?> fs-5 px-4 py-2 mb-3"><?= $equipText ?></div>
                <table class="table table-sm table-bordered mt-2">
                    <tr><th>Inicio</th><td><?= date('d/m/Y', strtotime($vivienda['fecha_venta'])) ?></td></tr>
                    <tr><th>Vencimiento</th><td><?= date('d/m/Y', strtotime($vivienda['vencimiento_equipamiento'])) ?></td></tr>
                    <tr>
                        <th>Días</th>
                        <td>
                            <?php if ($vivienda['dias_equipamiento'] > 0): ?>
                            <span class="text-<?= $equipClass ?>"><?= $vivienda['dias_equipamiento'] ?> días restantes</span>
                            <?php else: ?>
                            <span class="text-danger">Vencida hace <?= abs($vivienda['dias_equipamiento']) ?> días</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr><th>Período</th><td>10 años</td></tr>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
