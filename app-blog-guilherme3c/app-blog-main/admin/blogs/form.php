<?php 
require_once '../../setup/connect.php';
include_once '../_inc/_header.php'; 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Novo Blog</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="../home.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Blogs</a></li>
                    <li class="breadcrumb-item active">Cadastro</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="index.php" class="btn btn-outline-secondary px-3">
                <i class="fas fa-times me-2"></i>Cancelar
            </a>
            <button type="submit" form="formCadastro" class="btn btn-primary px-4">
                <i class="fas fa-check me-2"></i>Salvar Blog
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

        <?php if (isset($_GET['errorinsert'])): ?>
            <div class="alert alert-danger w-80 m-3" role="alert">
                Ocorreu um erro ao salvar o blog. Por favor, tente novamente.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['inserted'])): ?>
            <div class="alert alert-success w-80 m-3" role="alert">
                Blog cadastrado com sucesso!
            </div>
        <?php endif; ?>

        <div class="card-body p-4">
            <form action="insert.php" method="post" id="formCadastro" enctype="multipart/form-data">

                <!-- Título, Subtítulo e Categoria -->
                <div class="row g-4 mb-4">
                    <div class="col-md-5">
                        <label class="form-label fw-bold small text-muted text-uppercase text-required">*Título do Blog</label>
                        <input type="text" name="title" class="form-control form-control-flat" placeholder="Digite o título principal">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Subtítulo</label>
                        <input type="text" name="subtitle" class="form-control form-control-flat" placeholder="Breve resumo ou subtítulo">
                    </div>
                    
                </div>


                <div class="row g-4 mb-4">
                    <div class="col-md-9">
                        <label class="form-label fw-bold small text-muted text-uppercase text-required">*Conteúdo do Blog</label>
                        <textarea name="description" class="form-control form-control-flat" rows="6" placeholder="Escreva aqui o texto do blog..."></textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Status da Publicação</label>
                        <div class="d-flex align-items-center h-100 mt-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="statusSwitch" name="status" value="1" checked>
                                <label class="form-check-label ms-2" for="statusSwitch">Blog Ativo</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Imagem de Destaque -->
                <div class="row g-4 mb-4 border-bottom pb-3">
                    <div class="col-md-12">
                        <label class="form-label fw-bold small text-muted text-uppercase">Imagem de Destaque</label>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="width: 70px; height: 70px;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" name="image" class="form-control form-control-flat" accept="image/png, image/jpeg, image/webp">
                                <div class="form-text small">Use arquivos JPG, PNG ou WEBP de até 2MB.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light py-3 d-flex justify-content-end gap-2">
                    <span class="text-muted small align-self-center me-auto ms-2">Campos marcados com * são obrigatórios</span>
                    <button type="submit" form="formCadastro" class="btn btn-primary px-5 shadow-sm">Salvar </button>    
                </div>
            </form>
        </div>

    </div>
</div>

<?php include_once '../_inc/_footer.php'; ?>