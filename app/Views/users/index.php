<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$pageTitle   = 'Gestión de Usuarios';
$breadcrumbs = [
    ['label' => 'Inicio', 'url' => site_url('dashboard')],
    ['label' => 'Usuarios', 'url' => '#', 'active' => true],
];
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-users mr-2"></i> Listado de Usuarios
        </h3>
        <div class="card-tools">
            <a href="<?= site_url('usuarios/crear') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Nuevo Usuario
            </a>
        </div>
    </div>
    <div class="card-body">
        <table id="tableUsuarios" class="table table-striped table-bordered table-hover dt-responsive nowrap w-100">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Último Login</th>
                    <th>Registrado</th>
                    <th class="text-center">Acciones</th>
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
    const table = $('#tableUsuarios').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '<?= site_url('usuarios/datatable') ?>',
            type: 'GET',
        },
        columns: [
            { data: 'id', className: 'text-center', width: '50px' },
            { data: 'nombre' },
            { data: 'apellido' },
            { data: 'email' },
            {
                data: 'rol',
                className: 'text-center',
                render: function(data) {
                    return data === 'admin'
                        ? '<span class="badge bg-danger"><i class="fas fa-crown mr-1"></i>Admin</span>'
                        : '<span class="badge bg-primary"><i class="fas fa-wrench mr-1"></i>Técnico</span>';
                }
            },
            {
                data: 'activo',
                className: 'text-center',
                render: function(data) {
                    return data == 1
                        ? '<span class="badge bg-success"><i class="fas fa-check mr-1"></i>Activo</span>'
                        : '<span class="badge bg-secondary"><i class="fas fa-times mr-1"></i>Inactivo</span>';
                }
            },
            {
                data: 'ultimo_login',
                render: function(data) {
                    if (!data) return '<span class="text-muted">Nunca</span>';
                    const d = new Date(data);
                    return d.toLocaleString('es-ES');
                }
            },
            {
                data: 'created_at',
                render: function(data) {
                    const d = new Date(data);
                    return d.toLocaleDateString('es-ES');
                }
            },
            { data: 'acciones', className: 'text-center', orderable: false, searchable: false },
        ],
        language: datatableES,
        dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        pageLength: 10,
        lengthMenu: [[5,10,25,50,100], [5,10,25,50,100]],
        order: [[0, 'asc']],
        drawCallback: function() {
            initDeleteButtons();
        }
    });

    function initDeleteButtons() {
        $('.btn-delete').off('click').on('click', function() {
            const id  = $(this).data('id');
            const url = $(this).data('url');
            confirmDelete(url, function() {
                table.ajax.reload(null, false);
            });
        });
    }
});
</script>
<?= $this->endSection() ?>
