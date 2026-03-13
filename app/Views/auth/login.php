<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login - Inconel Building') ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">

    <!-- Custom -->
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

    <style>
        body {
            background: linear-gradient(135deg, #1a3a5c 0%, #0d1f33 50%, #1a3a5c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Source Sans 3', sans-serif;
        }
        .login-box {
            width: 400px;
            max-width: 95vw;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-logo .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #c8a84b, #f0d060);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 36px;
            color: #1a3a5c;
            box-shadow: 0 8px 25px rgba(200,168,75,0.4);
        }
        .login-logo h1 {
            color: #fff;
            font-weight: 700;
            font-size: 28px;
            letter-spacing: 1px;
            margin: 0;
        }
        .login-logo p {
            color: rgba(255,255,255,0.7);
            font-size: 14px;
            margin-top: 5px;
        }
        .login-card {
            background: rgba(255,255,255,0.97);
            border-radius: 16px;
            padding: 35px 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            backdrop-filter: blur(10px);
        }
        .login-card h4 {
            color: #1a3a5c;
            font-weight: 600;
            margin-bottom: 25px;
            text-align: center;
            font-size: 18px;
        }
        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            height: auto;
            border: 1.5px solid #dde3ea;
            font-size: 15px;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: #1a3a5c;
            box-shadow: 0 0 0 3px rgba(26,58,92,0.12);
        }
        .input-group-text {
            background: #f4f7fb;
            border: 1.5px solid #dde3ea;
            color: #1a3a5c;
            border-radius: 8px 0 0 8px;
        }
        .btn-login {
            background: linear-gradient(135deg, #1a3a5c, #2a5fa0);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(26,58,92,0.3);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26,58,92,0.4);
            background: linear-gradient(135deg, #2a5fa0, #1a3a5c);
        }
        .footer-text {
            text-align: center;
            color: rgba(255,255,255,0.6);
            font-size: 12px;
            margin-top: 20px;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        .logo-icon { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body>

<div class="login-box">
    <!-- Logo -->
    <div class="login-logo">
        <div class="logo-icon">
            <i class="fas fa-building"></i>
        </div>
        <h1>Inconel Building</h1>
        <p>Sistema de Gestión de Viviendas y Garantías</p>
    </div>

    <!-- Login Card -->
    <div class="login-card">
        <h4><i class="fas fa-lock mr-2" style="color:#1a3a5c"></i>Iniciar Sesión</h4>

        <!-- Errors -->
        <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        <?php endif; ?>

        <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        <?php endif; ?>

        <?php if (isset($errors) && is_array($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form action="<?= site_url('login') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="color:#1a3a5c">
                    <i class="fas fa-envelope mr-1"></i> Correo Electrónico
                </label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control"
                           value="<?= old('email') ?>"
                           placeholder="correo@inconel.com" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="color:#1a3a5c">
                    <i class="fas fa-lock mr-1"></i> Contraseña
                </label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control"
                           placeholder="••••••••" required>
                    <button type="button" class="btn btn-outline-secondary"
                            onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-login btn-primary w-100 text-white">
                <i class="fas fa-sign-in-alt mr-2"></i> Ingresar
            </button>
        </form>

        <div class="text-center mt-3">
            <small style="color:#6c757d">
                <i class="fas fa-info-circle mr-1"></i>
                ¿Problemas para acceder? Contacte al administrador.
            </small>
        </div>
    </div>

    <div class="footer-text">
        &copy; <?= date('Y') ?> Inconel Building &mdash; Todos los derechos reservados
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>

<script>
function togglePassword() {
    const pwd  = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        pwd.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
</body>
</html>
