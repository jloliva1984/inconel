<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$isEdit      = $usuario !== null;
$pageTitle   = $isEdit ? 'Editar Usuario' : 'Crear Usuario';
$breadcrumbs = [
    ['label' => 'Inicio', 'url' => site_url('dashboard')],
    ['label' => 'Usuarios', 'url' => site_url('usuarios')],
    ['label' => $pageTitle, 'url' => '#', 'active' => true],
];
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-<?= $isEdit ? 'edit' : 'user-plus' ?> mr-2"></i>
                    <?= $pageTitle ?>
                </h3>
            </div>
            <div class="card-body">

                <!-- Validation errors -->
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Nombre <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre" class="form-control"
                                       value="<?= old('nombre', $usuario['nombre'] ?? '') ?>"
                                       placeholder="Nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Apellido <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="apellido" class="form-control"
                                       value="<?= old('apellido', $usuario['apellido'] ?? '') ?>"
                                       placeholder="Apellido" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Correo Electrónico <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" class="form-control"
                               value="<?= old('email', $usuario['email'] ?? '') ?>"
                               placeholder="correo@ejemplo.com" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Contraseña <?= $isEdit ? '' : '<span class="text-danger">*</span>' ?>
                                </label>
                                <input type="password" name="password" class="form-control"
                                       placeholder="<?= $isEdit ? 'Dejar en blanco para no cambiar' : 'Mínimo 6 caracteres' ?>"
                                       <?= $isEdit ? '' : 'required' ?> minlength="6">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Confirmar Contraseña <?= $isEdit ? '' : '<span class="text-danger">*</span>' ?>
                                </label>
                                <input type="password" name="password_confirm" class="form-control"
                                       placeholder="Repetir contraseña"
                                       <?= $isEdit ? '' : 'required' ?>>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Rol <span class="text-danger">*</span>
                                </label>
                                <select name="rol" class="form-control form-select" required>
                                    <option value="">-- Seleccionar --</option>
                                    <option value="admin" <?= old('rol', $usuario['rol'] ?? '') === 'admin' ? 'selected' : '' ?>>
                                        Administrador
                                    </option>
                                    <option value="tecnico" <?= old('rol', $usuario['rol'] ?? '') === 'tecnico' ? 'selected' : '' ?>>
                                        Técnico
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Estado</label>
                                <select name="activo" class="form-control form-select">
                                    <option value="1" <?= old('activo', $usuario['activo'] ?? 1) == 1 ? 'selected' : '' ?>>
                                        Activo
                                    </option>
                                    <option value="0" <?= old('activo', $usuario['activo'] ?? 1) == 0 ? 'selected' : '' ?>>
                                        Inactivo
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            <?= $isEdit ? 'Actualizar' : 'Guardar' ?>
                        </button>
                        <a href="<?= site_url('usuarios') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
