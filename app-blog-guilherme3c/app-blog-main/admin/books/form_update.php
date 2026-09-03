<?php
require_once '../../setup/connect.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

try {
    // 1. Procurar os dados do utilizador
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: index.php");
        exit;
    }

    // 2. Procurar níveis para o select
    $levels = $pdo->query("SELECT * FROM level_users ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}

include_once '../_inc/_header.php';
?>

<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h4 class="mb-4">Editar Perfil de <?= htmlspecialchars($user['name']) ?></h4>

            <form action="update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">

                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <label class="fw-bold d-block mb-3">Foto de Perfil</label>
                        <div class="mb-3">
                            <?php if (!empty($user['image']) && file_exists("../../uploads/images/users/" . $user['image'])): ?>
                                <img src="../../uploads/images/users/<?= $user['image'] ?>" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center border" style="width: 150px; height: 150px;">
                                    <i class="fas fa-user fa-4x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                        <small class="text-muted">JPG ou PNG (Máx. 2MB)</small>
                    </div>

                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Nome</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nível</label>
                                <select name="id_level_users" class="form-select">
                                    <?php foreach ($levels as $l): ?>
                                        <option value="<?= $l['id'] ?>" <?= $l['id'] == $user['id_level_users'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($l['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" <?= $user['status'] == 1 ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-bold" for="status">Usuário Ativo</label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4">Atualizar Registo</button>
                            <a href="index.php" class="btn btn-outline-secondary px-4">Voltar</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once '../_inc/_footer.php'; ?>