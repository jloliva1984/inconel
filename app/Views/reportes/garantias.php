<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$pageTitle   = 'Reporte de Garantías';
$breadcrumbs = [
    ['label' => 'Inicio', 'url' => site_url('dashboard')],
    ['label' => 'Reportes', 'url' => site_url('reportes')],
    ['label' => 'Garantías', 'url' => '#', 'active' => true],
];
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-shield-alt mr-2"></i> Estado de Garantías
        </h3>
        <div class="card-tools d-flex gap-2">
            <a href="<?= site_url('reportes/garantias-imprimir') ?>" target="_blank" class="btn btn-secondary btn-sm">
                <i class="fas fa-print mr-1"></i> Imprimir
            </a>
            <a href="<?= site_url('reportes/garantias-excel') ?>" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel mr-1"></i> Excel
            </a>
            <a href="<?= site_url('reportes/garantias-pdf') ?>" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>
        </div>
    </div>
    <div class="card-body">

        <!-- Warranty legend -->
        <div class="row mb-3">
            <div class="col-auto">
                <span class="badge bg-success me-1">Activa</span> Garantía vigente
            </div>
            <div class="col-auto">
                <span class="badge bg-warning text-dark me-1">Por Vencer</span> ≤30 días (labor) / ≤90 días (equip.)
            </div>
            <div class="col-auto">
                <span class="badge bg-danger me-1">Vencida</span> Garantía expirada
            </div>
        </div>

        <table id="tableGarantias" class="table table-striped table-bordered table-hover dt-responsive nowrap w-100">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Dirección</th>
                    <th>Fecha Venta</th>
                    <th>Técnico</th>
                    <th>Venc. M.O.</th>
                    <th class="text-center">G. Labor</th>
                    <th>Venc. Equip.</th>
                    <th class="text-center">G. Equip.</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function() {
    $('#tableGarantias').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '<?= site_url('reportes/garantias-datatable') ?>',
            type: 'GET',
        },
        columns: [
            { data: 'id', className: 'text-center', width: '50px' },
            {
                data: 'direccion',
                render: function(data) {
                    return data.length > 45 ? data.substring(0,45) + '...' : data;
                }
            },
            {
                data: 'fecha_venta',
                render: function(data) {
                    const d = new Date(data + 'T00:00:00');
                    return d.toLocaleDateString('es-ES');
                }
            },
            { data: 'tecnico' },
            {
                data: 'vencimiento_mano_obra',
                render: function(data) {
                    const d = new Date(data + 'T00:00:00');
                    return d.toLocaleDateString('es-ES');
                }
            },
            {
                data: 'garantia_mano_obra',
                className: 'text-center',
                render: function(data) { return warrantyBadge(data); }
            },
            {
                data: 'vencimiento_equipamiento',
                render: function(data) {
                    const d = new Date(data + 'T00:00:00');
                    return d.toLocaleDateString('es-ES');
                }
            },
            {
                data: 'garantia_equipamiento',
                className: 'text-center',
                render: function(data) { return warrantyBadge(data); }
            },
        ],
        language: datatableES,
        pageLength: 25,
        lengthMenu: [[10,25,50,100,-1], [10,25,50,100,'Todos']],
        order: [[2, 'asc']],
    });

    function warrantyBadge(status) {
        const map = {
            'activa':     '<span class="badge bg-success">Activa</span>',
            'vencida':    '<span class="badge bg-danger">Vencida</span>',
            'por_vencer': '<span class="badge bg-warning text-dark">Por Vencer</span>',
        };
        return map[status] || status;
    }
});
</script>
<?= $this->endSection() ?>
