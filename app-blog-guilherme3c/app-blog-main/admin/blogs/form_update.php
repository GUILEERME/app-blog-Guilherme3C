<?php
require_once '../../setup/connect.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

try {
    // 1. Procurar os dados do post
    $stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$blog) {
        header("Location: index.php");
        exit;
    }

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}

include_once '../_inc/_header.php';
?>

<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h4 class="mb-4">Editar Post: <?= htmlspecialchars($blog['title']) ?></h4>

            <form action="update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $blog['id'] ?>">

                <div class="row">
                    <!-- Coluna da Imagem -->
                    <div class="col-md-4 text-center mb-4">
                        <label class="fw-bold d-block mb-3">Imagem do Post</label>
                        <div class="mb-3">
                            <?php if (!empty($blog['image']) && file_exists("../../uploads/images/blogs/" . $blog['image'])): ?>
                                <img src="../../uploads/images/blogs/<?= $blog['image'] ?>" class="img-fluid rounded border shadow-sm" style="max-height: 200px; width: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="height: 180px; width: 100%;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                        <small class="text-muted">JPG, PNG ou WEBP (Máx. 2MB)</small>
                    </div>

                    <!-- Coluna dos Dados do Post -->
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">Título</label>
                                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($blog['title']) ?>" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Subtítulo</label>
                                <input type="text" name="subtitle" class="form-control" value="<?= htmlspecialchars($blog['subtitle'] ?? '') ?>">
                            </div>

                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" value="1" <?= $blog['status'] == 1 ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-bold" for="statusSwitch">Post Ativo</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Conteúdo</label>
                                <textarea name="description" class="form-control" rows="6" required><?= htmlspecialchars($blog['description'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4">Atualizar Post</button>
                            <a href="index.php" class="btn btn-outline-secondary px-4">Voltar</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once '../_inc/_footer.php'; ?>