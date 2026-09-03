<?php
require_once '../../setup/connect.php';

try {
    $sql = "SELECT U.*, L.name AS level_name 
            FROM users AS U 
            INNER JOIN level_users AS L ON U.id_level_users = L.id 
            ORDER BY U.name ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na consulta: " . $e->getMessage());
}

include_once '../_inc/_header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Gerenciar Usuários</h4>
            <p class="text-muted small mb-0">Total de <?= count($users); ?> usuários cadastrados.</p>
        </div>
        <a href="form.php" class="btn btn-primary shadow-sm px-4">
            <i class="fas fa-plus me-2"></i>Adicionar
        </a>
    </div>

    <div class="card card-full border-0 shadow-sm">
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success m-3" role="alert">Usuário excluído com sucesso!</div>
        <?php endif; ?>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th class="ps-4" style="width: 35%">NOME / E-MAIL</th>
                            <th style="width: 20%">NÍVEL</th>
                            <th style="width: 20%">SLUG</th>
                            <th style="width: 10%">STATUS</th>
                            <th class="text-end pe-4" style="width: 15%">AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($users) > 0): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($user['image']) && file_exists('../../uploads/images/users/' . $user['image'])): ?>
                                                <img src="../../uploads/images/users/<?= $user['image']; ?>" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light border rounded me-3 d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($user['name']); ?></div>
                                                <div class="text-muted small text-truncate" style="max-width: 300px;">
                                                    <?= htmlspecialchars($user['email']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 text-capitalize">
                                            <?= htmlspecialchars($user['level_name']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-muted small text-truncate" style="max-width: 200px;">
                                            <?= htmlspecialchars($user['slug'] ?? ''); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-flex align-items-center">
                                            <span class="status-dot <?= $user['status'] == 1 ? 'bg-success' : 'bg-danger'; ?>"></span>
                                            <span class="small fw-bold <?= $user['status'] == 1 ? 'text-success' : 'text-danger'; ?>">
                                                <?= $user['status'] == 1 ? 'Ativo' : 'Inativo'; ?>
                                            </span>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-sm">
                                            <a href="form_update.php?id=<?= $user['id']; ?>" class="btn btn-white btn-sm border" title="Editar">
                                                <i class="fas fa-edit text-primary"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-white btn-sm border" title="Excluir"
                                               onclick="confirmarExclusao(<?= $user['id']; ?>, '<?= addslashes(htmlspecialchars($user['name'])); ?>')">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    Nenhum usuário encontrado no banco de dados.
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
    function confirmarExclusao(id, nome) {
        Swal.fire({
            title: 'Tem certeza?',
            text: `Você deseja excluir o usuário: "${nome}"?`,
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
        })
    }
</script>

<?php include_once '../_inc/_footer.php'; ?>