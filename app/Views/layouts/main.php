<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= esc($title ?? 'Inconel Building') ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- AdminLTE 3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img src="<?= base_url('assets/img/logo.png') ?>" alt="Inconel Building" height="60" class="animation__shake">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark navbar-inconel">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="<?= site_url('dashboard') ?>" class="nav-link">Inicio</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Notifications -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                </a>
            </li>

            <!-- User Menu -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <div class="user-avatar-circle">
                        <?= strtoupper(substr(session()->get('user_nombre') ?? 'U', 0, 1)) ?>
                    </div>
                    <span class="d-none d-md-inline"><?= esc(session()->get('user_nombre') ?? '') ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <li class="user-header bg-inconel">
                        <div class="user-avatar-circle-lg">
                            <?= strtoupper(substr(session()->get('user_nombre') ?? 'U', 0, 1)) ?>
                        </div>
                        <p>
                            <?= esc(session()->get('user_nombre') ?? '') ?>
                            <small><?= esc(ucfirst(session()->get('user_role') ?? '')) ?></small>
                        </p>
                    </li>
                    <li class="user-footer">
                        <a href="<?= site_url('logout') ?>" class="btn btn-default btn-flat float-right">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-inconel">
        <!-- Brand Logo -->
        <a href="<?= site_url('dashboard') ?>" class="brand-link text-center d-block">
            <span class="brand-text">
                <i class="fas fa-building mr-2"></i>
                <strong>Inconel Building</strong>
            </span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <div class="user-avatar-sidebar">
                        <?= strtoupper(substr(session()->get('user_nombre') ?? 'U', 0, 1)) ?>
                    </div>
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?= esc(session()->get('user_nombre') ?? '') ?></a>
                    <span class="badge badge-role-<?= session()->get('user_role') ?>">
                        <?= ucfirst(session()->get('user_role') ?? '') ?>
                    </span>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">

                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="<?= site_url('dashboard') ?>"
                           class="nav-link <?= (uri_string() === 'dashboard') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- Viviendas -->
                    <li class="nav-item">
                        <a href="<?= site_url('viviendas') ?>"
                           class="nav-link <?= (strpos(uri_string(), 'viviendas') !== false) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Viviendas</p>
                        </a>
                    </li>

                    <!-- Reportes -->
                    <li class="nav-item <?= (strpos(uri_string(), 'reportes') !== false) ? 'menu-open' : '' ?>">
                        <a href="#" class="nav-link <?= (strpos(uri_string(), 'reportes') !== false) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                Reportes
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= site_url('reportes/garantias') ?>"
                                   class="nav-link <?= (uri_string() === 'reportes/garantias') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Estado Garantías</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= site_url('reportes/por-tecnico') ?>"
                                   class="nav-link <?= (uri_string() === 'reportes/por-tecnico') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Por Técnico</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= site_url('reportes/vencimientos') ?>"
                                   class="nav-link <?= (uri_string() === 'reportes/vencimientos') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Vencimientos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= site_url('reportes/resumen') ?>"
                                   class="nav-link <?= (uri_string() === 'reportes/resumen') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Resumen General</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <?php if (session()->get('user_role') === 'admin'): ?>
                    <!-- Admin: Usuarios -->
                    <li class="nav-header">ADMINISTRACIÓN</li>
                    <li class="nav-item">
                        <a href="<?= site_url('usuarios') ?>"
                           class="nav-link <?= (strpos(uri_string(), 'usuarios') !== false) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Usuarios</p>
                        </a>
                    </li>
                    <?php endif; ?>

                    <!-- Logout -->
                    <li class="nav-item mt-3">
                        <a href="<?= site_url('logout') ?>" class="nav-link nav-link-logout"
                           onclick="return confirm('¿Cerrar sesión?')">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Cerrar Sesión</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><?= esc($pageTitle ?? '') ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <?php if (isset($breadcrumbs)): ?>
                                <?php foreach ($breadcrumbs as $bc): ?>
                                    <?php if ($bc['active'] ?? false): ?>
                                        <li class="breadcrumb-item active"><?= esc($bc['label']) ?></li>
                                    <?php else: ?>
                                        <li class="breadcrumb-item">
                                            <a href="<?= esc($bc['url']) ?>"><?= esc($bc['label']) ?></a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Alert messages -->
                <?php if (session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php endif; ?>

                <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; <?= date('Y') ?> <a href="#">Inconel Building</a>.</strong>
        Todos los derechos reservados.
        <div class="float-right d-none d-sm-inline-block">
            <b>Versión</b> 1.0.0
        </div>
    </footer>

</div><!-- ./wrapper -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap 5 Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom JS -->
<script src="<?= base_url('assets/js/app.js') ?>"></script>

<?= $this->renderSection('scripts') ?>

</body>
</html>
