<?php
require_once '../../setup/connect.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    // 1. Busca o nome da imagem vinculada antes de deletar o registro
    $stmtSelect = $pdo->prepare("SELECT image FROM blogs WHERE id = :id");
    $stmtSelect->bindParam(':id', $id, PDO::PARAM_INT);
    $stmtSelect->execute();
    $blog = $stmtSelect->fetch(PDO::FETCH_ASSOC);

    // 2. Se o registro existir no banco, remove o arquivo físico de imagem (se houver)
    if ($blog) {
        if (!empty($blog['image'])) {
            $imagePath = "../../uploads/" . $blog['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath); // Exclui o arquivo do servidor
            }
        }

        // 3. Deleta o registro da tabela 'blogs'
        $stmtDelete = $pdo->prepare("DELETE FROM blogs WHERE id = :id");
        $stmtDelete->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmtDelete->execute()) {
            header("Location: index.php?deleted=true");
        } else {
            header("Location: index.php?error=true");
        }
    } else {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}
exit;