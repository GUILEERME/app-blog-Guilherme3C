<?php
require_once 'setup/connect.php';

// Captura e sanitiza o parâmetro 'blog' vindo da URL
$slug = filter_input(INPUT_GET, 'blog', FILTER_DEFAULT);

if (!$slug) {
    header("Location: index.php");
    exit;
}

// Busca o post ativo pelo slug
try {
    $stmt = $pdo->prepare("SELECT * FROM blogs WHERE slug = :slug AND status = 1");
    $stmt->execute([':slug' => $slug]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$blog) {
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($blog['title']) ?> - App Blog</title>
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <?php include '_inc/_header.php'; ?>

    <main class="container my-4">
        <div class="row g-5">
            
            <div class="col-md-12">
                <article class="bg-white p-4 p-md-5 rounded border shadow-sm mb-4">
                    
                    <header class="mb-4">
                        <h1 class="fw-bold display-5 mb-3"><?= htmlspecialchars($blog['title']) ?></h1>
                        
                        <?php if (!empty($blog['subtitle'])): ?>
                            <p class="lead text-muted mb-3"><?= htmlspecialchars($blog['subtitle']) ?></p>
                        <?php endif; ?>
                    </header>

                    <?php if (!empty($blog['image']) && file_exists("uploads/images/blogs/" . $blog['image'])): ?>
                        <div class="mb-4 text-center">
                            <img src="uploads/images/blogs/<?= $blog['image'] ?>" class="img-fluid rounded border shadow-sm" style="max-height: 450px; width: 100%; object-fit: cover;" alt="<?= htmlspecialchars($blog['title']) ?>">
                        </div>
                    <?php endif; ?>

                    <section class="lh-lg">
                        <div class="text-secondary">
                            <?= nl2br(htmlspecialchars($blog['description'])) ?>
                        </div>
                    </section>
                    
                    <div class="mt-5 border-top pt-3">
                        <a href="index.php" class="btn btn-outline-secondary btn-sm">
                            &larr; Voltar para a página inicial
                        </a>
                    </div>
                </article>

            </div>
        </div>
    </main>

    <?php include '_inc/_footer.php'; ?>

    <script src="public/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>