<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$pageTitle   = 'Reporte de Vencimientos';
$breadcrumbs = [
    ['label' => 'Inicio', 'url' => site_url('dashboard')],
    ['label' => 'Reportes', 'url' => site_url('reportes')],
    ['label' => 'Vencimientos', 'url' => '#', 'active' => true],
];
?>

<div class="card card-outline card-danger">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calendar-times mr-2"></i> Análisis de Vencimientos de Garantías
        </h3>
        <div class="card-tools d-flex gap-2">
            <a href="<?= site_url('reportes/garantias-imprimir') ?>" target="_blank" class="btn btn-secondary btn-sm">
                <i class="fas fa-print mr-1"></i> Imprimir
            </a>
            <a href="<?= site_url('reportes/vencimientos-excel') ?>" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel mr-1"></i> Excel
            </a>
            <a href="<?= site_url('reportes/vencimientos-pdf') ?>" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Atención:</strong> Garantía de Mano de Obra: <strong>1 año</strong>.
            Garantía de Equipamiento: <strong>10 años</strong>.
            Se alerta cuando quedan ≤30 días para M.O. y ≤90 días para equipamiento.
        </div>

        <table id="tableVencimientos" class="table table-striped table-bordered table-hover dt-responsive nowrap w-100">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Dirección</th>
                    <th>Fecha Venta</th>
                    <th>Técnico</th>
                    <th>Venc. M.O.</th>
                    <th class="text-center">Estado M.O.</th>
                    <th>Venc. Equip.</th>
                    <th class="text-center">Estado Equip.</th>
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
    $('#tableVencimientos').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '<?= site_url('reportes/vencimientos-datatable') ?>',
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
                    return new Date(data + 'T00:00:00').toLocaleDateString('es-ES');
                }
            },
            { data: 'tecnico' },
            {
                data: 'vencimiento_mano_obra',
                render: function(data) {
                    return new Date(data + 'T00:00:00').toLocaleDateString('es-ES');
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
                    return new Date(data + 'T00:00:00').toLocaleDateString('es-ES');
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
        order: [[4, 'asc']],
        rowCallback: function(row, data) {
            if (data.garantia_mano_obra === 'vencida') {
                $(row).addClass('table-danger');
            } else if (data.garantia_mano_obra === 'por_vencer') {
                $(row).addClass('table-warning');
            }
        }
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
