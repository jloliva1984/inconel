<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$pageTitle   = 'Gestión de Viviendas';
$breadcrumbs = [
    ['label' => 'Inicio', 'url' => site_url('dashboard')],
    ['label' => 'Viviendas', 'url' => '#', 'active' => true],
];
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-home mr-2"></i> Listado de Viviendas
        </h3>
        <div class="card-tools d-flex gap-2">
            <a href="<?= site_url('viviendas/crear') ?>" class="btn btn-success btn-sm">
                <i class="fas fa-plus mr-1"></i> Nueva Vivienda
            </a>
            <a href="<?= site_url('viviendas/imprimir') ?>" target="_blank" class="btn btn-secondary btn-sm">
                <i class="fas fa-print mr-1"></i> Imprimir
            </a>
            <a href="<?= site_url('viviendas/exportar-excel') ?>" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel mr-1"></i> Excel
            </a>
            <a href="<?= site_url('viviendas/exportar-pdf') ?>" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <table id="tableViviendas" class="table table-striped table-bordered table-hover dt-responsive nowrap w-100">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Dirección</th>
                    <th>Fecha Inst. A/C</th>
                    <th>Serie Handler</th>
                    <th>Serie Condenser</th>
                    <th>Fecha Venta</th>
                    <th>Técnico</th>
                    <th class="text-center">G. Labor</th>
                    <th class="text-center">G. Equip.</th>
                    <th class="text-center no-sort">Acciones</th>
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
    const table = $('#tableViviendas').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '<?= site_url('viviendas/datatable') ?>',
            type: 'GET',
            error: function(xhr) {
                if (xhr.status === 401) {
                    window.location.href = '<?= site_url('login') ?>';
                }
            }
        },
        columns: [
            { data: 'id', className: 'text-center', width: '50px' },
            {
                data: 'direccion',
                render: function(data, type, row) {
                    const truncated = data.length > 50 ? data.substring(0,50) + '...' : data;
                    return '<span title="' + data + '">' + truncated + '</span>';
                }
            },
            {
                data: 'fecha_instalacion_ac',
                render: function(data) {
                    if (!data) return '-';
                    const d = new Date(data + 'T00:00:00');
                    return d.toLocaleDateString('es-ES');
                }
            },
            { data: 'serie_handler' },
            { data: 'serie_condenser' },
            {
                data: 'fecha_venta',
                render: function(data) {
                    if (!data) return '-';
                    const d = new Date(data + 'T00:00:00');
                    return d.toLocaleDateString('es-ES');
                }
            },
            { data: 'tecnico' },
            {
                data: 'garantia_mano_obra',
                className: 'text-center',
                render: function(data) { return warrantyBadge(data); }
            },
            {
                data: 'garantia_equipamiento',
                className: 'text-center',
                render: function(data) { return warrantyBadge(data); }
            },
            { data: 'acciones', className: 'text-center', orderable: false, searchable: false },
        ],
        language: datatableES,
        dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        pageLength: 10,
        lengthMenu: [[5,10,25,50,100,-1], [5,10,25,50,100,'Todos']],
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [9] }
        ],
        drawCallback: function() {
            initDeleteButtons();
        }
    });

    function initDeleteButtons() {
        $('.btn-delete').off('click').on('click', function() {
            const url = $(this).data('url');
            confirmDelete(url, function() {
                table.ajax.reload(null, false);
            });
        });
    }
});

function warrantyBadge(status) {
    const map = {
        'activa':     '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Activa</span>',
        'vencida':    '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Vencida</span>',
        'por_vencer': '<span class="badge bg-warning text-dark"><i class="fas fa-exclamation-circle me-1"></i>Por Vencer</span>',
    };
    return map[status] || '<span class="badge bg-secondary">' + status + '</span>';
}
</script>
<?= $this->endSection() ?>
