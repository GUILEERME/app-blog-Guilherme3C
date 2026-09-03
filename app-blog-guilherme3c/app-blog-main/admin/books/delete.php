<?php
require_once '../../setup/connect.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        header("Location: index.php?deleted=true");
    } else {
        header("Location: index.php?error=true");
    }
} else {
    header("Location: index.php");
}
exit;