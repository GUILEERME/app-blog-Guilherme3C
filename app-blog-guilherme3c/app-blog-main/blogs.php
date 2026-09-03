<?php 
require_once 'setup/connect.php'; 

// Filtro opcional por busca simples via GET
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

try {
    if (!empty($search)) {
        $stmt = $pdo->prepare("SELECT * FROM blogs WHERE status = 1 AND (title LIKE :search OR description LIKE :search) ORDER BY id DESC");
        $stmt->bindValue(':search', '%' . $search . '%');
    } else {
        $stmt = $pdo->prepare("SELECT * FROM blogs WHERE status = 1 ORDER BY id DESC");
    }
    $stmt->execute();
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $blogs = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Meta Tags SEO -->
    <title>Todos os Artigos | DevBlog</title>
    <meta name="description" content="Explore nosso acervo completo de artigos, tutoriais e publicações sobre desenvolvimento de software.">
    <meta name="robots" content="index, follow">

    <!-- Bootstrap 5 & Ícones Nativos -->
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #f1f5f9;
        }

        /* Banner de Cabeçalho Integrado ao Estilo */
        .page-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1e1b4b 100%);
        }

        /* Animação e Hover dos Cards */
        .card-blog {
            border: 1px solid #e2e8f0;
            transition: all 0.25s ease-in-out;
        }
        .card-blog:hover {
            transform: translateY(-4px);
            border-color: #0d6efd !important;
            box-shadow: 0 12px 24px rgba(13, 110, 253, 0.12) !important;
        }

        /* Imagem Proporcional */
        .blog-img {
            height: 220px;
            object-fit: cover;
            width: 100%;
        }

        .newsletter-section {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }
    </style>
</head>

<body class="text-dark d-flex flex-column min-vh-100">

    <?php include '_inc/_header.php'; ?>

    <main id="main-content">
        
        <!-- CABEÇALHO DA PÁGINA -->
        <section class="page-header py-5 text-white border-bottom border-primary mb-5 shadow-sm">
            <div class="container py-3">
                <div class="row align-items-center justify-content-between gy-4">
                    <div class="col-lg-7">
                        <span class="badge bg-primary text-uppercase px-3 py-2 rounded-pill font-monospace mb-3">
                            <i class="bi bi-collection me-1"></i> Acervo de Conteúdo
                        </span>
                        <h1 class="display-6 fw-bold text-white mb-2">
                            Todos os Artigos & Publicações
                        </h1>
                        <p class="lead text-light opacity-75 mb-0 fs-6">
                            Navegue por todas as publicações técnicas sobre programação, arquitetura e engenharia de software.
                        </p>
                    </div>

                    <!-- Barra de Busca Rápida na Página -->
                    <div class="col-lg-4">
                        <form action="blogs.php" method="GET" role="search" class="bg-white bg-opacity-10 p-2 rounded-4 border border-white border-opacity-10 backdrop-blur">
                            <div class="input-group">
                                <input type="search" name="q" class="form-control bg-white border-0 ps-3" placeholder="Filtrar publicações..." value="<?= htmlspecialchars($search) ?>" aria-label="Filtrar">
                                <button class="btn btn-primary fw-semibold px-3" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- CONTAINER DOS ARTIGOS -->
        <section class="container mb-5">
            
            <!-- Barra de Status e Contagem -->
            <div class="d-flex flex-wrap align-items-center justify-content-between pb-3 mb-4 border-bottom border-2 border-primary-subtle gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h2 class="h5 fw-bold text-dark m-0">
                        <i class="bi bi-grid-fill text-primary me-2"></i>Artigos Disponíveis
                    </h2>
                    <?php if (!empty($search)): ?>
                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill">
                            Busca: "<?= htmlspecialchars($search) ?>"
                            <a href="blogs.php" class="text-secondary ms-1"><i class="bi bi-x-circle-fill"></i></a>
                        </span>
                    <?php endif; ?>
                </div>

                <span class="badge bg-primary rounded-pill px-3 py-2">
                    <?= count($blogs) ?> <?= count($blogs) === 1 ? 'artigo encontrado' : 'artigos encontrados' ?>
                </span>
            </div>

            <!-- GRID DE BLOGS -->
            <?php if (!empty($blogs)): ?>
                <div class="row g-4">
                    <?php foreach ($blogs as $blog): ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <article class="card card-blog h-100 bg-white shadow-sm rounded-4 overflow-hidden">
                                
                                <!-- VALIDAÇÃO DA IMAGEM DO BLOG -->
                                <?php 
                                    $imagePath = 'uploads/images/blogs/' . $blog['image'];
                                    if (!empty($blog['image']) && file_exists($imagePath)): 
                                ?>
                                    <a href="blog.php?blog=<?= urlencode($blog['slug']) ?>" class="d-block overflow-hidden">
                                        <img src="<?= htmlspecialchars($imagePath) ?>" 
                                             class="card-img-top blog-img" 
                                             alt="<?= htmlspecialchars($blog['title']) ?>">
                                    </a>
                                <?php endif; ?>

                                <div class="card-body d-flex flex-column p-4">
                                    
                                    <h3 class="card-title h6 fw-bold mb-2">
                                        <a href="blog.php?blog=<?= urlencode($blog['slug']) ?>" class="text-decoration-none text-dark hover-primary">
                                            <?= htmlspecialchars($blog['title']) ?>
                                        </a>
                                    </h3>

                                    <?php if (!empty($blog['subtitle'])): ?>
                                        <p class="card-subtitle mb-2 text-primary small fw-semibold">
                                            <?= htmlspecialchars($blog['subtitle']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <p class="card-text text-secondary small flex-grow-1 mb-3">
                                        <?= htmlspecialchars(mb_strimwidth($blog['description'], 0, 120, '...')) ?>
                                    </p>

                                    <div class="pt-3 mt-auto border-top d-flex align-items-center justify-content-between">
                                        <a href="blog.php?blog=<?= urlencode($blog['slug']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                            Ler artigo <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>

                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Estado Vazio -->
                <div class="card border-0 bg-white shadow-sm p-5 text-center rounded-4 my-5" role="alert">
                    <i class="bi bi-search fs-1 text-primary d-block mb-3"></i>
                    <h3 class="h5 fw-bold text-dark mb-1">Nenhum artigo encontrado</h3>
                    <p class="text-secondary small mb-3">Não encontramos resultados para a sua busca ou não há posts cadastrados.</p>
                    <div>
                        <a href="blogs.php" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold">
                            Ver todos os artigos
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </section>


    </main>

    <?php include '_inc/_footer.php'; ?>

    <script src="public/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>