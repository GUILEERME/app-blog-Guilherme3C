<?php
require_once '../../setup/connect.php';
$post = filter_input_array(INPUT_POST);

if (empty($post['title']) || empty($post['description'])) {
    header('LOCATION: form.php?errornull=True');
    exit;
}

// Ternária, se existir o campo status, atribui 1, caso contrário, atribui 0
$post['status'] = isset($post['status']) ? 1 : 0;

// Slug - Gerado a partir do título do blog com verificação de unicidade na tabela 'blogs'
$slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $post['title']));
$slug = trim($slugBase, '-');

$stmt = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE slug = :slug");
$stmt->bindParam(':slug', $slug);
$stmt->execute();
$slugCount = $stmt->fetchColumn();

$counter = 1;
while ($slugCount > 0) {
    $slug = $slugBase . '-' . $counter;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE slug = :slug");
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
    $slugCount = $stmt->fetchColumn();
    $counter++;
}
$post['slug'] = $slug;
$dir = "../../uploads/images/blogs/";
// Criar a pasta caso não exista
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $baseName = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
    $newName = $baseName . "." . $ext;
    // Evitar sobreposição de arquivos com o mesmo nome
    $counter = 1;
    while (file_exists($dir . $newName)) {
        $newName = $baseName . '-' . $counter . "." . $ext;
        $counter++;
    }
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $dir . $newName)) {
        $post['image'] = $newName;
    }
} else {
    unset($post['image']); // Remove o campo do array se nenhum arquivo for enviado
}

// Inserção Dinâmica na tabela 'blogs'
$table = 'blogs';
$fields = [];
$placeholders = [];

foreach ($post as $key => $value) {
    $fields[] = $key;
    $placeholders[] = ':' . $key;
}

$sql = "INSERT INTO " . $table . " (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $placeholders) . ")";
$stmt = $pdo->prepare($sql);

foreach ($post as $key => $value) {
    $stmt->bindValue(':' . $key, $value);
}

$stmt->execute();

if ($stmt->rowCount()) {
    header('LOCATION: index.php?inserted=True');
    exit;
} else {
    header('LOCATION: form.php?errorinsert=True');
    exit;
}