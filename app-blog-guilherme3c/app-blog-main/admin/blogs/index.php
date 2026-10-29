<?php
require_once '../../setup/connect.php';

try {
    $sql = "
        SELECT
            blogs.*,
            categories_blog.name AS category_name
        FROM blogs
        LEFT JOIN categories_blog
            ON blogs.id_categories_blog = categories_blog.id
        ORDER BY blogs.id DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro na consulta: " . $e->getMessage());
}

include_once '../_inc/_header.php';
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Gerenciar Posts do Blog</h4>
            <p class="text-muted small mb-0">
                Total de <?= count($blogs) ?> posts cadastrados.
            </p>
        </div>

        <a href="form.php"
           class="btn btn-primary shadow-sm px-4">
            <i class="fas fa-plus me-2"></i>Adicionar
        </a>
    </div>

    <div class="card card-full border-0 shadow-sm">

        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success m-3">
                Post atualizado com sucesso!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success m-3">
                Blog excluído com sucesso!
            </div>
        <?php endif; ?>

        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th class="ps-4">TÍTULO / SUBTÍTULO</th>
                            <th>SLUG</th>
                            <th>CATEGORIA</th>
                            <th>STATUS</th>
                            <th class="text-end pe-4">AÇÕES</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if (count($blogs) > 0): ?>

                        <?php foreach ($blogs as $blog): ?>
                            <tr>

                                <td class="ps-4">
                                    <div class="d-flex align-items-center">

                                        <?php
                                        $caminhoImagem =
                                            '../../uploads/images/blogs/'
                                            . ($blog['image'] ?? '');

                                        if (
                                            !empty($blog['image']) &&
                                            is_file($caminhoImagem)
                                        ):
                                        ?>
                                            <img
                                                src="<?= htmlspecialchars($caminhoImagem) ?>"
                                                class="rounded me-3"
                                                style="width: 50px; height: 50px; object-fit: cover;"
                                                alt="Imagem do post">
                                        <?php else: ?>
                                            <div
                                                class="bg-light border rounded me-3 d-flex align-items-center justify-content-center text-muted"
                                                style="width: 50px; height: 50px;">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>

                                        <div>
                                            <div class="fw-bold text-dark">
                                                <?= htmlspecialchars($blog['title']) ?>
                                            </div>

                                            <div class="text-muted small text-truncate"
                                                 style="max-width: 250px;">
                                                <?= htmlspecialchars($blog['subtitle'] ?? '') ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="text-muted small text-truncate"
                                         style="max-width: 180px;">
                                        <?= htmlspecialchars($blog['slug'] ?? '') ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?= htmlspecialchars(
                                            $blog['category_name'] ?? 'Sem categoria'
                                        ) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="d-flex align-items-center">
                                        <span class="status-dot
                                            <?= (int) $blog['status'] === 1
                                                ? 'bg-success'
                                                : 'bg-danger' ?>">
                                        </span>

                                        <span class="small fw-bold
                                            <?= (int) $blog['status'] === 1
                                                ? 'text-success'
                                                : 'text-danger' ?>">

                                            <?= (int) $blog['status'] === 1
                                                ? 'Ativo'
                                                : 'Inativo' ?>
                                        </span>
                                    </span>
                                </td>

                                <td class="text-end pe-4">
                                    <div class="btn-group shadow-sm">

                                        <a
                                            href="form_update.php?id=<?= (int) $blog['id'] ?>"
                                            class="btn btn-white btn-sm border"
                                            title="Editar">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a>

                                        <a
                                            href="javascript:void(0)"
                                            class="btn btn-white btn-sm border"
                                            title="Excluir"
                                            onclick='confirmarExclusao(
                                                <?= (int) $blog['id'] ?>,
                                                <?= json_encode(
                                                    $blog['title'],
                                                    JSON_HEX_APOS |
                                                    JSON_HEX_QUOT |
                                                    JSON_HEX_TAG |
                                                    JSON_HEX_AMP
                                                ) ?>
                                            )'>
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </a>

                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="5"
                                class="text-center py-5 text-muted">
                                Nenhum post encontrado no banco de dados.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarExclusao(id, titulo) {
    Swal.fire({
        title: 'Tem certeza?',
        text: `Você deseja excluir o post: "${titulo}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `delete.php?id=${id}`;
        }
    });
}
</script>

<?php include_once '../_inc/_footer.php'; ?>
