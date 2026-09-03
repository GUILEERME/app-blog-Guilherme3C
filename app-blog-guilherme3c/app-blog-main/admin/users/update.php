<?php
require_once '../../setup/connect.php';
$post = filter_input_array(INPUT_POST);

// Validação do ID e dos campos obrigatórios do usuário
if (empty($post['id']) || empty($post['name']) || empty($post['email'])) {
    header('LOCATION: form_update.php?id=' . ($post['id'] ?? '') . '&errornull=True');
    exit;
}

$id = $post['id'];
unset($post['id']); // Remove o id para não entrar nos SETs dinâmicos

// Tratamento de senha (se não enviou senha nova, remove do $post para não sobrescrever a hash atual)
if (empty($post['password'])) {
    unset($post['password']);
} else {
    $post['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
}

// Ternária para o campo status
$post['status'] = isset($post['status']) ? 1 : 0;

// Slug - Gerado a partir do nome do usuário com verificação de unicidade ignorando o próprio id
$slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $post['name']));
$slug = trim($slugBase, '-');

$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE slug = :slug AND id != :id");
$stmt->execute([':slug' => $slug, ':id' => $id]);
$slugCount = $stmt->fetchColumn();

$counter = 1;
while ($slugCount > 0) {
    $slug = $slugBase . '-' . $counter;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE slug = :slug AND id != :id");
    $stmt->execute([':slug' => $slug, ':id' => $id]);
    $slugCount = $stmt->fetchColumn();
    $counter++;
}
$post['slug'] = $slug;

// Upload de imagem do usuário
$dir = "../../uploads/images/users/";
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    // Busca foto antiga para apagar
    $stmtOld = $pdo->prepare("SELECT image FROM users WHERE id = :id");
    $stmtOld->execute([':id' => $id]);
    $oldImg = $stmtOld->fetchColumn();

    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $baseName = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
    $newName = $baseName . "." . $ext;

    $counter = 1;
    while (file_exists($dir . $newName)) {
        $newName = $baseName . '-' . $counter . "." . $ext;
        $counter++;
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $dir . $newName)) {
        if (!empty($oldImg) && file_exists($dir . $oldImg)) {
            unlink($dir . $oldImg);
        }
        $post['image'] = $newName;
    }
} else {
    unset($post['image']); // Se não enviou imagem nova, mantém a atual no banco
}

// Atualização Dinâmica na tabela 'users'
$setFields = [];
foreach ($post as $key => $value) {
    $setFields[] = $key . " = :" . $key;
}

$sql = "UPDATE users SET " . implode(", ", $setFields) . " WHERE id = :id";
$stmt = $pdo->prepare($sql);

foreach ($post as $key => $value) {
    $stmt->bindValue(':' . $key, $value);
}
$stmt->bindValue(':id', $id);

$stmt->execute();

if ($stmt->rowCount() !== false) {
    header('LOCATION: index.php?updated=True');
    exit;
} else {
    header('LOCATION: form_update.php?id=' . $id . '&errorupdate=True');
    exit;
}