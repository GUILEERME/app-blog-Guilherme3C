
<?php
require_once '../../setup/connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$title = trim($_POST['title'] ?? '');
$subtitle = trim($_POST['subtitle'] ?? '');
$description = trim($_POST['description'] ?? '');

$idCategoriesBlog = filter_input(
    INPUT_POST,
    'id_categories_blog',
    FILTER_VALIDATE_INT
);

$status = isset($_POST['status']) ? 1 : 0;

if (
    !$id ||
    $title === '' ||
    $description === '' ||
    !$idCategoriesBlog
) {
    die("Preencha os campos obrigatórios corretamente.");
}

try {
    // Verificar se o post existe
    $stmt = $pdo->prepare(
        "SELECT image FROM blogs WHERE id = :id"
    );
    $stmt->execute([':id' => $id]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$blog) {
        die("Post não encontrado.");
    }

    // Verificar se a categoria existe
    $stmt = $pdo->prepare(
        "SELECT id_categories_blog
         FROM categories_blog
         WHERE id_categories_blog = :id"
    );

    $stmt->execute([':id' => $idCategoriesBlog]);

    if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
        die("A categoria selecionada não existe.");
    }

    // Manter a imagem atual se nenhuma nova for enviada
    $image = $blog['image'] ?? null;

    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            die("Erro ao enviar a imagem.");
        }

        if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            die("A imagem deve ter no máximo 2 MB.");
        }

        $arquivoTemporario = $_FILES['image']['tmp_name'];

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($arquivoTemporario);

        $tiposPermitidos = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp'
        ];

        if (!isset($tiposPermitidos[$mime])) {
            die("Formato inválido. Envie JPG, PNG ou WEBP.");
        }

        $diretorio = __DIR__ . '/../../uploads/images/blogs/';

        if (
            !is_dir($diretorio) &&
            !mkdir($diretorio, 0755, true)
        ) {
            die("Não foi possível criar o diretório de imagens.");
        }

        if (!is_writable($diretorio)) {
            die("O diretório de imagens não permite gravação.");
        }

        $novoNome = bin2hex(random_bytes(16))
            . '.' . $tiposPermitidos[$mime];

        if (!move_uploaded_file(
            $arquivoTemporario,
            $diretorio . $novoNome
        )) {
            die("Não foi possível salvar a nova imagem.");
        }

        $image = $novoNome;
    }

    // Atualizar o post e sua categoria
    $sql = "
        UPDATE blogs
        SET
            title = :title,
            subtitle = :subtitle,
            description = :description,
            id_categories_blog = :id_categories_blog,
            status = :status,
            image = :image
        WHERE id = :id
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':title' => $title,
        ':subtitle' => $subtitle,
        ':description' => $description,
        ':id_categories_blog' => $idCategoriesBlog,
        ':status' => $status,
        ':image' => $image,
        ':id' => $id
    ]);

    header("Location: index.php?updated=1");
    exit;

} catch (PDOException $e) {
    error_log("Erro ao atualizar post: " . $e->getMessage());
    die("Ocorreu um erro ao atualizar o post.");
}
