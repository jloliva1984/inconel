<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$pageTitle   = 'Reporte por Técnico';
$breadcrumbs = [
    ['label' => 'Inicio', 'url' => site_url('dashboard')],
    ['label' => 'Reportes', 'url' => site_url('reportes')],
    ['label' => 'Por Técnico', 'url' => '#', 'active' => true],
];
?>

<div class="card card-outline card-warning">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user-hard-hat mr-2"></i> Viviendas por Técnico
        </h3>
        <div class="card-tools d-flex gap-2">
            <a href="<?= site_url('reportes/por-tecnico-excel') ?>" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel mr-1"></i> Excel
            </a>
            <a href="<?= site_url('reportes/por-tecnico-pdf') ?>" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <table id="tableTecnico" class="table table-striped table-bordered table-hover dt-responsive w-100">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Técnico</th>
                    <th class="text-center">Total Viviendas</th>
                    <th class="text-center">G. Labor Activa</th>
                    <th class="text-center">G. Labor Vencida</th>
                    <th class="text-center">G. Equip. Activa</th>
                    <th class="text-center">G. Equip. Vencida</th>
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
    $('#tableTecnico').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '<?= site_url('reportes/por-tecnico-datatable') ?>',
            type: 'GET',
            error: function(xhr) {
                if (xhr.status === 401) { window.location.href = '<?= site_url('login') ?>'; }
            }
        },
        columns: [
            { data: 'tecnico_id', className: 'text-center', width: '50px' },
            { data: 'tecnico' },
            { data: 'total', className: 'text-center', render: function(d) {
                return '<span class="badge bg-primary fs-6">' + d + '</span>';
            }},
            { data: 'labor_activa', className: 'text-center', render: function(d) {
                return '<span class="badge bg-success">' + d + '</span>';
            }},
            { data: 'labor_vencida', className: 'text-center', render: function(d) {
                return d > 0 ? '<span class="badge bg-danger">' + d + '</span>' : '<span class="badge bg-light text-dark">0</span>';
            }},
            { data: 'equip_activa', className: 'text-center', render: function(d) {
                return '<span class="badge bg-success">' + d + '</span>';
            }},
            { data: 'equip_vencida', className: 'text-center', render: function(d) {
                return d > 0 ? '<span class="badge bg-danger">' + d + '</span>' : '<span class="badge bg-light text-dark">0</span>';
            }},
        ],
        language: datatableES,
        pageLength: 25,
        order: [[2, 'desc']],
    });
});
</script>
<?= $this->endSection() ?>
