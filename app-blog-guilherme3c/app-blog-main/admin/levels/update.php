<?php

require_once '../../setup/connect.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

$id_level_users = filter_input(
    INPUT_POST,
    'id_level_users',
    FILTER_VALIDATE_INT
);

$valor = filter_input(
    INPUT_POST,
    'valor',
    FILTER_VALIDATE_INT
);

if (!$id || !$id_level_users || $valor === false || $valor === null) {
    die("Dados inválidos.");
}

try {

    $sql = "UPDATE users
            SET 
                id_level_users = :id_level_users,
                valor = :valor
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_level_users' => $id_level_users,
        ':valor' => $valor,
        ':id' => $id
    ]);

    header("Location: index.php?updated=1");
    exit;

} catch (PDOException $e) {

    die("Erro ao atualizar usuário: " . $e->getMessage());

}