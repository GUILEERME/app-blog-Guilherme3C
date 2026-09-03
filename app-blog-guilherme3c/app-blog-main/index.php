<?php 
require_once 'setup/connect.php'; 

// Buscar todos os posts ativos
try {
    $stmt = $pdo->prepare("SELECT * FROM blogs WHERE status = 1 ORDER BY id DESC");
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
    <title>DevBlog | Tecnologia & Engenharia de Software</title>
    <meta name="description" content="Artigos, tutoriais e soluções práticas sobre desenvolvimento de sistemas e programação.">
    <meta name="robots" content="index, follow">
    <meta name="author" content="DevBlog">

    <!-- Bootstrap 5 & Ícones Nativos -->
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Fundo suave com leve tom frio */
        body {
            background-color: #f1f5f9;
        }

        /* Banner Hero com degradê corporativo atraente */
        .hero-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1e1b4b 100%);
        }

        /* Card com borda interativa no hover */
        .card-blog {
            border: 1px solid #e2e8f0;
            transition: all 0.25s ease-in-out;
        }
        .card-blog:hover {
            transform: translateY(-4px);
            border-color: #0d6efd !important;
            box-shadow: 0 12px 24px rgba(13, 110, 253, 0.12) !important;
        }

        /* Formatação de imagem recortada proporcionalmente */
        .blog-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        /* Seção Newsletter em tom escuro de contraste */
        .newsletter-section {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }
    </style>
</head>

<body class="text-dark d-flex flex-column min-vh-100">

    <?php include '_inc/_header.php'; ?>

    <main id="main-content">
        
        <!-- BANNER HERO DE DESTAQUE -->
        <section class="py-5 hero-banner text-white border-bottom border-primary mb-4 shadow-sm">
            <div class="container py-2">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <span class="badge bg-primary text-uppercase px-3 py-2 rounded-pill font-monospace mb-3">
                            <i class="bi bi-code-slash me-1"></i> Dev & System Architecture
                        </span>
                        <h1 class="display-5 fw-bold mb-3 text-white">
                            Desenvolvimento de Sistemas & Algoritmos
                        </h1>
                        <p class="lead text-light opacity-75 mb-4 col-lg-11">
                            Artigos práticos, padrões de projeto, bancos de dados e engenharia de software para impulsionar suas habilidades.
                        </p>
                        <a href="#artigos" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm fw-semibold">
                            Explorar Artigos <i class="bi bi-arrow-down-short fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ÁREA PRINCIPAL DE ARTIGOS + SIDEBAR -->
        <section class="container mb-5" id="artigos">
            <div class="row g-4">
                
                <!-- Feed Principal -->
                <div class="col-lg-8">
                    
                    <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom border-2 border-primary-subtle">
                        <h2 class="h5 fw-bold text-dark m-0 d-flex align-items-center gap-2">
                            <i class="bi bi-journals text-primary"></i> Últimas Publicações
                        </h2>
                        <span class="badge bg-primary rounded-pill px-3 py-2"><?= count($blogs) ?> publicações</span>
                    </div>

                    <?php if (!empty($blogs)): ?>
                        <div class="row g-4">
                            <?php foreach ($blogs as $blog): ?>
                                <div class="col-12 col-md-6">
                                    <article class="card card-blog h-100 bg-white shadow-sm rounded-4 overflow-hidden">
                                        
                                        <!-- VALIDAÇÃO E EXIBIÇÃO DA IMAGEM DO BLOG -->
                                        <?php 
                                            // Concatena o diretório relativo com o nome do arquivo no banco
                                            $imagePath = 'uploads/images/blogs/' . $blog['image'];
                                            
                                            // Exibe a imagem APENAS se o campo não for vazio E o arquivo existir fisicamente
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
                                                <?= htmlspecialchars(mb_strimwidth($blog['description'], 0, 110, '...')) ?>
                                            </p>

                                            <div class="pt-3 mt-auto border-top d-flex align-items-center justify-content-between">
                                                <a href="blog.php?blog=<?= urlencode($blog['slug']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                                    Ler post <i class="bi bi-arrow-right ms-1"></i>
                                                </a>
                                            </div>

                                        </div>
                                    </article>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="card border-0 bg-white shadow-sm p-5 text-center rounded-4" role="alert">
                            <i class="bi bi-inbox fs-1 text-primary d-block mb-3"></i>
                            <h3 class="h5 fw-bold text-dark mb-1">Nenhum artigo encontrado</h3>
                            <p class="mb-0 text-secondary small">Ainda não há publicações ativas no banco de dados.</p>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- Barra Lateral (Sidebar) -->
                <aside class="col-lg-4" aria-label="Barra Lateral">
                    <div class="sticky-top" style="top: 2rem;">
                        
                        <!-- Widget de Pesquisa -->
                        <div class="card border-0 bg-white shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <h3 class="h6 fw-bold text-dark mb-3">
                                    <i class="bi bi-search text-primary me-2"></i>Buscar Artigos
                                </h3>
                                <form action="busca.php" method="GET" role="search">
                                    <div class="input-group">
                                        <input type="search" name="q" class="form-control bg-light border-0" placeholder="Ex: PHP, SQL, APIs..." aria-label="Buscar" required>
                                        <button class="btn btn-primary px-3" type="submit">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Widget de Categorias / Tags -->
                        <div class="card border-0 bg-white shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <h3 class="h6 fw-bold text-dark mb-3">
                                    <i class="bi bi-tags text-primary me-2"></i>Tópicos Populares
                                </h3>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="#" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-3 py-2 rounded-pill">Backend PHP</a>
                                    <a href="#" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-3 py-2 rounded-pill">Bancos de Dados</a>
                                    <a href="#" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-3 py-2 rounded-pill">Arquitetura MVC</a>
                                    <a href="#" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-3 py-2 rounded-pill">APIs RESTful</a>
                                    <a href="#" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-3 py-2 rounded-pill">Clean Code</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </aside>

            </div>
        </section>

        <!-- SEÇÃO NEWSLETTER DA COMUNIDADE -->
        <section class="py-5 newsletter-section text-white border-top mt-auto">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-6">
                        <div class="badge bg-primary rounded-circle p-3 mb-3">
                            <i class="bi bi-envelope-paper fs-3 text-white"></i>
                        </div>
                        <h2 class="fw-bold h4 text-white mb-2">Comunidade DevBlog</h2>
                        <p class="text-light opacity-75 small mb-4">Receba conteúdos semanais sobre arquitetura de sistemas e novas postagens.</p>
                        <form class="row g-2 justify-content-center" action="newsletter.php" method="POST">
                            <div class="col-12 col-sm-8">
                                <input type="email" name="email" class="form-control border-0" placeholder="seu.email@exemplo.com" required>
                            </div>
                            <div class="col-12 col-sm-auto">
                                <button type="submit" class="btn btn-primary fw-bold px-4 w-100">Inscrever-se</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <?php include '_inc/_footer.php'; ?>

    <script src="public/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>