<?php 
include_once '_inc/_header.php'; 

// Conexão PDO com o banco 'app-blog'
try {
    // $pdo = new PDO("mysql:host=localhost;dbname=app-blog;charset=utf8mb4", "root", "");
    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Métricas da Dashboard
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() ?: 0;
    $totalBlogs = $pdo->query("SELECT COUNT(*) FROM blogs")->fetchColumn() ?: 0;
    $blogsAtivos = $pdo->query("SELECT COUNT(*) FROM blogs WHERE status = 1")->fetchColumn() ?: 0;

    // 2. Buscar Usuários Recentes + Nível de Permissão (JOIN com level_users)
    $stmtUsers = $pdo->query("
        SELECT u.id, u.name, u.email, u.status, u.image, l.name AS level_name 
        FROM users u
        LEFT JOIN level_users l ON u.id_level_users = l.id
        ORDER BY u.id DESC LIMIT 5
    ");
    $recentUsers = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

    // 3. Buscar Blogs Recentes
    $stmtBlogs = $pdo->query("
        SELECT id, title, subtitle, status, image 
        FROM blogs 
        ORDER BY id DESC LIMIT 5
    ");
    $recentBlogs = $stmtBlogs->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

$nomeAdmin = $_SESSION['usuario_nome'] ?? 'Admin';
?>

<div class="container-fluid px-4 mt-4">
    <!-- Header da Dashboard -->
    <div class="row mb-4 align-items-end">
        <div class="col">
            <h6 class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Visão Geral</h6>
            <h2 class="fw-bold mb-0">Olá, <?= htmlspecialchars($nomeAdmin); ?></h2>
        </div>
    </div>

    <!-- Cards de Métricas Principais -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary p-3 rounded-3 me-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-1">Total de Usuários</h6>
                        <h3 class="fw-bold mb-0"><?= number_format($totalUsers, 0, ',', '.'); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success-subtle text-success p-3 rounded-3 me-3">
                        <i class="fas fa-blog fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-1">Total de Blogs</h6>
                        <h3 class="fw-bold mb-0"><?= number_format($totalBlogs, 0, ',', '.'); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info-subtle text-info p-3 rounded-3 me-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-1">Blogs Ativos</h6>
                        <h3 class="fw-bold mb-0"><?= number_format($blogsAtivos, 0, ',', '.'); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Coluna Esquerda: Tabela de Usuários -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Usuários Recentes</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr class="text-muted" style="font-size: 0.85rem;">
                                    <th>USUÁRIO</th>
                                    <th>NÍVEL</th>
                                    <th>STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentUsers)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">Nenhum usuário cadastrado.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentUsers as $user): ?>
                                        <?php 
                                            $names = explode(' ', trim($user['name']));
                                            $initials = strtoupper(substr($names[0], 0, 1) . (isset($names[1]) ? substr($names[1], 0, 1) : ''));
                                            $isAtivo = ($user['status'] == 1);
                                        ?>
                                        <tr class="border-bottom">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($user['image']) && file_exists('uploads/' . $user['image'])): ?>
                                                        <img src="uploads/<?= htmlspecialchars($user['image']); ?>" class="rounded-circle me-3" style="width: 35px; height: 35px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-primary-subtle text-primary rounded-circle p-2 me-3 fw-bold" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                                            <?= $initials; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="fw-bold mb-0"><?= htmlspecialchars($user['name']); ?></div>
                                                        <div class="text-muted small"><?= htmlspecialchars($user['email']); ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    <?= htmlspecialchars($user['level_name'] ?? 'Padrão'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill <?= $isAtivo ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'; ?>">
                                                    <?= $isAtivo ? 'Ativo' : 'Inativo'; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Direita: Blogs Recentes -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Últimos Blogs</h5>
                    
                    <?php if (empty($recentBlogs)): ?>
                        <p class="text-muted small">Nenhum blog cadastrado ainda.</p>
                    <?php else: ?>
                        <?php foreach ($recentBlogs as $blog): ?>
                            <?php $isBlogAtivo = ($blog['status'] == 1); ?>
                            <div class="d-flex align-items-start mb-3 pb-2 border-bottom">
                                <div class="me-3 mt-1">
                                    <i class="fas fa-file-alt text-primary"></i>
                                </div>
                                <div class="w-100">
                                    <div class="small fw-bold text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($blog['title']); ?>">
                                        <?= htmlspecialchars($blog['title']); ?>
                                    </div>
                                    <div class="text-muted extra-small d-flex justify-content-between align-items-center mt-1" style="font-size: 0.75rem;">
                                        <span class="text-truncate" style="max-width: 140px;"><?= htmlspecialchars($blog['subtitle']); ?></span>
                                        <span class="badge <?= $isBlogAtivo ? 'bg-success' : 'bg-secondary'; ?>" style="font-size: 0.65rem;">
                                            <?= $isBlogAtivo ? 'Ativo' : 'Rascunho'; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '_inc/_footer.php'; ?>