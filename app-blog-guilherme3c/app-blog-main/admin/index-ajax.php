<?php
require_once '../_conn/conect.php';
session_start();
// Se já estiver logado, pula a tela de login
// if (isset($_SESSION['email'])) {
//     header('Location: home.php');
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso ao Sistema</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/bootstrap/css/bootstrap.min.css">
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
        
    </style>
</head>

<body>

    <div class="container d-flex justify-content-center">
        <div class="card card-login shadow-lg">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold mt-2">Painel Admin</h2>
                </div>

                <div id="msgAlerta" class="alert alert-danger d-none align-items-center mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <div id="msgTexto"></div>
                </div>

                <form id="formLogin">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
                        <label for="email"><i class="bi bi-envelope me-2"></i>E-mail</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="pass" name="pass" placeholder="Senha">
                        <label for="pass"><i class="bi bi-key me-2"></i>Senha</label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label small text-muted" for="remember">Lembrar-me</label>
                        </div>
                        <a href="#" class="text-decoration-none small fw-medium">Esqueceu a senha?</a>
                    </div>

                    <button type="submit" id="btnEntrar" class="btn btn-primary w-100 btn-login shadow-sm">
                        <span>Acessar Sistema</span>
                    </button>

                    <!-- <a href="#" id="sum"> Add + 1 </a>
                    <h2 id="msgTitulo"> </h2>
                    <a href="#" id="subtraction"> Remove 1 </a> -->
                    <!-- <script>      
                        let contador = 0;
                        document.getElementById('sum').addEventListener('click', function(e) {
                            e.preventDefault();
                            contador++;
                            document.getElementById('msgTitulo').innerText = contador;
                        })
                        // remover o texto do msgTitulo
                        document.getElementById('subtraction').addEventListener('click', function(e) {
                            e.preventDefault();
                            contador--;
                            if (contador < 0) {
                                contador = 0;
                            }
                            document.getElementById('msgTitulo').innerText = contador;
                        })
                        
                    </script> -->



                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('formLogin').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = document.getElementById('btnEntrar');
            const alerta = document.getElementById('msgAlerta');
            const texto = document.getElementById('msgTexto');

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Carregando...';
            alerta.classList.add('d-none');

            const dados = new FormData(this); // FormData para enviar os dados do formulário
            // alert(dados.get('pass') + ' - ' + dados.get('email'));

            fetch('logar-ajax.php', {
                    method: 'POST',
                    body: dados
                })
                .then(res => res.json())
                .then(retorno => {
                    if (retorno.status === 'success') {
                        window.location.href = retorno.redirect;
                    } else {
                        alerta.classList.remove('d-none');
                        alerta.classList.add('d-flex');
                        texto.innerText = retorno.message;
                        btn.disabled = false;
                        btn.innerHTML = 'Acessar Sistema';
                    }
                })
                .catch(erro => {
                    console.error('Erro:', erro);
                    alert('Erro na comunicação com o servidor.');
                    btn.disabled = false;
                });
        });
    </script>
</body>

</html>