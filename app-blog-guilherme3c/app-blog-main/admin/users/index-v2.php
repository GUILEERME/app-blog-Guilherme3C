<?php
require_once '../../setup/connect.php';

// --- 1. CONFIGURAÇÃO DE BUSCA E PAGINAÇÃO ---
$busca = filter_input(INPUT_GET, 'busca', FILTER_UNSAFE_RAW) ?? ''; // ?? Se busca for nula, atribui string vazia
$limite = 10; 
$pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) ?? 1; // Se pagina for nula ou inválida, atribui 1
if ($pagina_atual < 1) $pagina_atual = 1;

$inicio = ($pagina_atual - 1) * $limite;
$params = [];
$where = "";

// Se houver busca, filtramos por nome ou email
if (!empty($busca)) {
    $where = " WHERE U.name LIKE :busca OR U.email LIKE :busca ";
    $params[':busca'] = "%$busca%";
}

try {
    // Conta o total de registros (com filtro de busca)
    $sqlTotal = "SELECT COUNT(id) as total FROM users AS U" . $where;
    $stmtTotal = $pdo->prepare($sqlTotal);
    foreach ($params as $key => $val) $stmtTotal->bindValue($key, $val);
    $stmtTotal->execute();
    $totalRegistros = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPaginas = ceil($totalRegistros / $limite);

    // Consulta os usuários da página atual
    $sql = "SELECT U.*, L.name AS level_name 
            FROM users AS U 
            INNER JOIN level_users AS L ON U.id_level_users = L.id 
            $where
            ORDER BY U.name ASC 
            LIMIT :limite OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $val) $stmt->bindValue($key, $val);
    $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$inicio, PDO::PARAM_INT);
    $stmt->execute();
    
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $totalUsersNaPagina = count($users);
} catch (PDOException $e) {
    die("Erro na consulta: " . $e->getMessage());
}

include_once '../_inc/_header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Gerenciar Usuários</h4>
            <p class="text-muted small mb-0">Total de <?= $totalRegistros; ?> usuários encontrados.</p>
        </div>
        <a href="form.php" class="btn btn-primary shadow-sm px-4">
            <i class="fas fa-plus me-2"></i>Adicionar Usuário
        </a>
    </div>

    <div class="card card-full border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">Lista de Usuários</h6>
            
            <form method="GET" class="input-group input-group-sm" style="width: 280px;">
                <input type="text" name="busca" class="form-control bg-light" 
                       placeholder="Buscar por nome ou e-mail..." 
                       value="<?= htmlspecialchars($busca) ?>">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
                <?php if(!empty($busca)): ?>
                    <a href="index.php" class="btn btn-outline-secondary" title="Limpar busca">
                        <i class="fas fa-times"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success w-80 m-3" role="alert">Usuário Excluído com sucesso!</div>
        <?php endif; ?>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th class="ps-4" style="width: 30%">NOME / E-MAIL</th>
                            <th style="width: 20%">NÍVEL</th>
                            <th style="width: 20%">SLUG</th>
                            <th style="width: 15%">STATUS</th>
                            <th class="text-end pe-4" style="width: 15%">AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($totalUsersNaPagina > 0): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <?php
                                            $cores = [
                                                'a' => 'bg-primary', 'b' => 'bg-secondary', 'c' => 'bg-success',
                                                'd' => 'bg-danger', 'e' => 'bg-warning', 'f' => 'bg-info',
                                                'g' => 'bg-dark', 'h' => 'bg-primary', 'i' => 'bg-secondary',
                                                'j' => 'bg-success', 'k' => 'bg-danger', 'l' => 'bg-warning',
                                                'm' => 'bg-info', 'n' => 'bg-dark', 'o' => 'bg-primary',
                                                'p' => 'bg-secondary', 'q' => 'bg-success', 'r' => 'bg-danger',
                                                's' => 'bg-warning', 't' => 'bg-info', 'u' => 'bg-dark',
                                                'v' => 'bg-primary', 'w' => 'bg-secondary', 'x' => 'bg-success',
                                                'y' => 'bg-danger', 'z' => 'bg-warning'
                                            ];
                                            $primeiraLetra = strtolower($user['name'][0]);
                                            $corFundo = $cores[$primeiraLetra] ?? 'bg-secondary';
                                            
                                            if (!empty($user['photo']) && file_exists('../../uploads/' . $user['photo'])) {
                                                $imgUrl = '../../uploads/' . $user['photo'];
                                                echo "<img src='$imgUrl' class='rounded-circle me-2' style='width: 40px; height: 40px; object-fit: cover;'>";
                                            } else {
                                                $iniciais = strtoupper(substr($user['name'], 0, 2));
                                                echo "<span class='d-inline-block me-2 rounded-circle text-white text-center $corFundo' style='width: 40px; height: 40px; line-height: 40px; font-size: 14px;'>$iniciais</span>";
                                            }
                                            ?>
                                            <div>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($user['name']); ?></div>
                                                <div class="text-muted small"><?= htmlspecialchars($user['email']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 text-capitalize">
                                            <?= htmlspecialchars($user['level_name']); ?>
                                        </span>
                                    </td>
                                    <td><div class="text-muted small"><?= htmlspecialchars($user['slug']); ?></div></td>
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
                                    <i class="fas fa-search fa-2x mb-3 d-block"></i>
                                    Nenhum usuário encontrado para "<strong><?= htmlspecialchars($busca) ?></strong>".
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <p class="mb-0 small text-muted">Exibindo <?= $totalUsersNaPagina; ?> de <?= $totalRegistros; ?> registros</p>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?= ($pagina_atual <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?pagina=<?= $pagina_atual - 1; ?>&busca=<?= urlencode($busca) ?>">Anterior</a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li class="page-item <?= ($i == $pagina_atual) ? 'active' : ''; ?>">
                                <a class="page-link" href="?pagina=<?= $i; ?>&busca=<?= urlencode($busca) ?>"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($pagina_atual >= $totalPaginas) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?pagina=<?= $pagina_atual + 1; ?>&busca=<?= urlencode($busca) ?>">Próximo</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmarExclusao(id, nome) {
        Swal.fire({
            title: 'Tem certeza?',
            text: `Você deseja excluir o usuário: ${nome}?`,
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