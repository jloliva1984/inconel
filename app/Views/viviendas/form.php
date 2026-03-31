<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$isEdit      = $vivienda !== null;
$pageTitle   = $isEdit ? 'Editar Vivienda' : 'Registrar Vivienda';
$breadcrumbs = [
    ['label' => 'Inicio', 'url' => site_url('dashboard')],
    ['label' => 'Viviendas', 'url' => site_url('viviendas')],
    ['label' => $pageTitle, 'url' => '#', 'active' => true],
];
?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-<?= $isEdit ? 'edit' : 'home' ?> mr-2"></i>
                    <?= $pageTitle ?>
                </h3>
            </div>
            <div class="card-body">

                <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form action="<?= esc($action) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-map-marker-alt mr-1 text-primary"></i>
                            Dirección <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="direccion" class="form-control form-control-lg"
                               value="<?= old('direccion', $vivienda['direccion'] ?? '') ?>"
                               placeholder="Ej: 123 Main St, Miami, FL 33101" required>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-snowflake mr-1 text-info"></i>
                                    Fecha Instalación A/C <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="fecha_instalacion_ac" class="form-control"
                                       value="<?= old('fecha_instalacion_ac', $vivienda['fecha_instalacion_ac'] ?? '') ?>"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-bolt mr-1 text-success"></i>
                                    Fecha Arranque A/C
                                    <small class="text-muted">(opcional)</small>
                                </label>
                                <input type="date" name="fecha_arranque_ac" class="form-control"
                                       value="<?= old('fecha_arranque_ac', $vivienda['fecha_arranque_ac'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar-check mr-1 text-success"></i>
                                    Fecha de Venta
                                    <small class="text-muted">(inicia garantía)</small>
                                </label>
                                <input type="date" name="fecha_venta" class="form-control"
                                       value="<?= old('fecha_venta', $vivienda['fecha_venta'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-barcode mr-1 text-secondary"></i>
                                    N° Serie Handler <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="serie_handler" class="form-control"
                                       value="<?= old('serie_handler', $vivienda['serie_handler'] ?? '') ?>"
                                       placeholder="Ej: HDL-2024-001" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-barcode mr-1 text-secondary"></i>
                                    N° Serie Condenser <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="serie_condenser" class="form-control"
                                       value="<?= old('serie_condenser', $vivienda['serie_condenser'] ?? '') ?>"
                                       placeholder="Ej: CDS-2024-001" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-user-hard-hat mr-1 text-warning"></i>
                                    Técnico Responsable <span class="text-danger">*</span>
                                </label>
                                <select name="tecnico_id" class="form-control form-select" required>
                                    <option value="">-- Seleccionar Técnico --</option>
                                    <?php foreach ($tecnicos as $tec): ?>
                                    <option value="<?= $tec['id'] ?>"
                                        <?= old('tecnico_id', $vivienda['tecnico_id'] ?? $defaultTecnico ?? '') == $tec['id'] ? 'selected' : '' ?>>
                                        <?= esc($tec['nombre'] . ' ' . $tec['apellido']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-sticky-note mr-1 text-warning"></i> Notas
                        </label>
                        <textarea name="notas" class="form-control" rows="3"
                                  placeholder="Observaciones adicionales..."><?= old('notas', $vivienda['notas'] ?? '') ?></textarea>
                    </div>

                    <!-- Warranty Preview -->
                    <?php
                    $fechaVenta = old('fecha_venta', $vivienda['fecha_venta'] ?? null);
                    if ($fechaVenta):
                        $fv      = new DateTime($fechaVenta);
                        $labor   = (clone $fv)->modify('+1 year')->format('d/m/Y');
                        $equip   = (clone $fv)->modify('+10 years')->format('d/m/Y');
                    ?>
                    <div class="alert alert-info">
                        <strong><i class="fas fa-shield-alt mr-2"></i>Garantías calculadas:</strong>
                        <span class="ms-3">
                            <i class="fas fa-tools mr-1"></i>
                            Mano de obra: <strong><?= $labor ?></strong>
                        </span>
                        <span class="ms-3">
                            <i class="fas fa-snowflake mr-1"></i>
                            Equipamiento: <strong><?= $equip ?></strong>
                        </span>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-2"></i>
                            <?= $isEdit ? 'Actualizar' : 'Guardar' ?>
                        </button>
                        <a href="<?= site_url('viviendas') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function() {
    // Auto-calculate warranty dates when fecha_venta changes
    $('[name="fecha_venta"]').on('change', function() {
        const val = $(this).val();
        if (!val) return;
        const d     = new Date(val + 'T00:00:00');
        const labor = new Date(d); labor.setFullYear(labor.getFullYear() + 1);
        const equip = new Date(d); equip.setFullYear(equip.getFullYear() + 10);

        const fmt = (dt) => dt.toLocaleDateString('es-ES', {day:'2-digit',month:'2-digit',year:'numeric'});

        if ($('#warranty-info').length === 0) {
            $('[name="notas"]').closest('.mb-4').before(
                '<div id="warranty-info" class="alert alert-info">' +
                '<strong><i class="fas fa-shield-alt me-2"></i>Garantías calculadas:</strong>' +
                '<span class="ms-3"><i class="fas fa-tools me-1"></i>Mano de obra: <strong id="w-labor"></strong></span>' +
                '<span class="ms-3"><i class="fas fa-snowflake me-1"></i>Equipamiento: <strong id="w-equip"></strong></span>' +
                '</div>'
            );
        }
        $('#w-labor').text(fmt(labor));
        $('#w-equip').text(fmt(equip));
        $('#warranty-info').show();
    });
});
</script>
<?= $this->endSection() ?>
