<?php include_once '../_inc/_header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Novo Usuário</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="../home.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Usuários</a></li>
                    <li class="breadcrumb-item active">Cadastro</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="index.php" class="btn btn-outline-secondary px-3">
                <i class="fas fa-times me-2"></i>Cancelar
            </a>
            <button type="submit" form="formCadastro" class="btn btn-primary px-4">
                <i class="fas fa-check me-2"></i>Salvar Registro
            </button>
        </div>
    </div>

    <div class="card card-full">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark">Cadastro</h6>
        </div>
        <?php if (isset($_GET['errornull'])): ?>
            <div class="alert alert-danger w-80 m-3" role="alert">
                Por favor, preencha todos os campos obrigatórios *.
            </div>

        <?php endif; ?>
        <?php if (isset($_GET['errorhash'])): ?>
            <div class="alert alert-danger w-80 m-3" role="alert">
                As senhas não coincidem. Por favor, tente novamente.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['erroremail'])): ?>
            <div class="alert alert-danger w-80 m-3" role="alert">
                O e-mail informado já está cadastrado. Por favor, utilize outro e-mail.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['errorinsert'])): ?>
            <div class="alert alert-danger w-80 m-3" role="alert">
                Ocorreu um erro ao salvar o usuário. Por favor, tente novamente.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['inserted'])): ?>
            <div class="alert alert-success w-80 m-3" role="alert">
                Usuário cadastrado com sucesso!
            </div>
        <?php endif; ?>


        <div class="card-body p-4">
            <form action="insert.php" method="POST" id="formCadastro" enctype="multipart/form-data">

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase text-required">*Nome Completo</label>
                        <input type="text" name="name" class="form-control form-control-flat" placeholder="Digite o nome">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase text-required">*E-mail Principal</label>
                        <input type="email" name="email" class="form-control form-control-flat" placeholder="exemplo@blog.com">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase text-required">*Nível de Permissão</label>

                        <select name="id_level_users" class="form-select form-control-flat">
                            <?php
                            // Select de dados da tabela level_users
                            $stmt = $pdo->prepare("Select *from level_users");
                            $stmt->execute();
                            foreach ($stmt as $row) {
                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                            }
                            ?>
                        </select>

                    </div>
                </div>



                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase text-required">*Senha de Acesso</label>
                        <input type="password" name="pass" class="form-control form-control-flat" placeholder="••••••••">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase text-required">*Confirmar Senha</label>
                        <input type="password" name="pass_confirm" class="form-control form-control-flat" placeholder="••••••••">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase text-required">Status da Conta</label>
                        <div class="d-flex align-items-center h-100 mt-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="statusSwitch" name="status" checked>
                                <label class="form-check-label ms-2" for="statusSwitch">Usuário Ativo</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4 border-bottom pb-3">
                    <div class="col-md-12">
                        <label class="form-label fw-bold small text-muted text-uppercase">Foto de Perfil</label>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="width: 70px; height: 70px;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" name="image" class="form-control form-control-flat" accept="image/png, image/jpeg">
                                <div class="form-text small">Use arquivos JPG ou PNG de até 2MB.</div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card-footer bg-light py-3 d-flex justify-content-end gap-2">
                    <span class="text-muted small align-self-center me-auto ms-2">Campos marcados com * são obrigatórios</span>
                    <input type="submit" form="formCadastro" class="btn btn-primary px-5 shadow-sm" value="Salvar Novo Usuário">    
                </div>
            </form>
        </div>

    </div>
</div>

<?php include_once '../_inc/_footer.php'; ?>