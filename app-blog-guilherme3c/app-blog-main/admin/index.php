<?php
require_once '../setup/connect.php';
// Verifica se há erro na URL para exibir o alerta
$error = isset($_GET['error']) ? true : false;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso ao Sistema</title>
    <link rel="stylesheet" href="<?= $base_url ?>public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-login {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 12px;
        }

        .btn-login {
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
        }

        .form-floating>.form-control:focus~label {
            color: #007bff;
        }
    </style>
</head>

<body>



    <div class="container d-flex justify-content-center">
        <div class="card card-login shadow-lg">
            <div class="card-body p-5">
                <?php   echo MD5('admin123'); ?>
                <div class="text-center mb-4">
                    <!-- <i class="bi bi-shield-lock-fill text-secondary" style="font-size: 2rem;"></i> -->
                    <h2 class="fw-bold mt-2">Painel Admin</h2>
                    <p class="text-muted small">Insira suas credenciais abaixo</p>
                </div>

                <?php if ($error): ?>

                    <div class="alert alert-danger border-0 d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        <div>Acesso negado. Tente novamente.</div>
                    </div>

                <?php endif; ?>

                <form action="logar.php" method="POST">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="nome@exemplo.com" required>
                        <label for="email"><i class="bi bi-envelope me-2"></i>E-mail</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="pass" name="pass" placeholder="Senha" required>
                        <label for="pass"><i class="bi bi-key me-2"></i>Senha</label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label small text-muted" for="remember">Lembrar-me</label>
                        </div>
                        <a href="recuperar.php" class="text-decoration-none small fw-medium">Esqueceu a senha?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-login shadow-sm">
                        Acessar Sistema <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </form>

            </div>
            <div class="card-footer bg-white border-0 text-center pb-4">
                <span class="text-muted small">&copy; <?= date('Y') ?> Sua Empresa</span>
            </div>
        </div>
    </div>

</body>

</html>