<?php
require_once '../../setup/connect.php';

$post = filter_input_array(INPUT_POST);

// Validação de Campos Obrigatórios
if (empty($post['name']) || empty($post['email']) || empty($post['pass']) || empty($post['pass_confirm'])) {
    header('LOCATION: form.php?errornull=True');
    exit;
}

if ($post['pass'] !== $post['pass_confirm']) {
    header('LOCATION: form.php?errorhash=True');
    exit;
}
// Remover o array de confirmação de senha para não ser inserido no banco
unset($post['pass_confirm']);
// Criptografar a senha usando password_hash com o algoritmo BCRYPT
$post['pass'] = md5($post['pass']);

// Verificação se já existe o e-mail cadastrado no banco de dados
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
$stmt->bindParam(':email', $post['email']);
$stmt->execute();
$emailCount = $stmt->fetchColumn();
if ($emailCount > 0) {
    header('LOCATION: form.php?erroremail=True');
    exit;
}

// Ternária, se existir o campo status, atribui 1, caso contrário, atribui 0
$post['status'] = isset($post['status']) ? 1 : 0;

// Slug - Verificação se o slug já existe no banco de dados, caso exista, adiciona um sufixo numérico para garantir a unicidade
$slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $post['name'])); // Gerar slug base a partir do nome
$slug = $slugBase;
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE slug = :slug");
$stmt->bindParam(':slug', $slug);
$stmt->execute();
$slugCount = $stmt->fetchColumn();
$counter = 1;
while ($slugCount > 0) {
    $slug = $slugBase . '-' . $counter;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE slug = :slug");
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
    $slugCount = $stmt->fetchColumn();
    $counter++;
}
$post['slug'] = $slug;

$dir = "../../uploads/images/users/";
// Criar a pasta caso ela não exista
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) { // UPLOAD_ERR_OK significa que o upload foi bem-sucedido
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION); // pathinfo é uma função do PHP que retorna informações sobre o caminho do arquivo, e PATHINFO_EXTENSION retorna a extensão do arquivo
    $baseName = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME); // PATHINFO_FILENAME retorna o nome do arquivo sem a extensão
    $newName = $baseName . "." . $ext; // Nome original do arquivo
    // Verificar se o arquivo já existe e adicionar sufixo numérico se necessário
    $counter = 1;
    while (file_exists($dir . $newName)) {
        $newName = $baseName . '-' . $counter . "." . $ext;
        $counter++;
    }
    if (move_uploaded_file($_FILES['image']['tmp_name'], $dir . $newName)) {
        $post['image'] = $newName;
    }
} else {
    unset($post['image']); // Remove o campo image do array $post se nenhum arquivo foi enviado
}

// if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
//     $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
//     $newName = md5(uniqid()) . "." . $ext; // Nome único e aleatório

//     if (move_uploaded_file($_FILES['image']['tmp_name'], $dir . $newName)) {
//         // Adicionamos o nome do arquivo ao array $post para o Insert Dinâmico
//         $post['image'] = $newName;
//     }
// }


// Modo 2 - Insert Dinâmico 
$table = 'users';
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
    header('LOCATION: form.php?inserted=True');
    exit;
} else {
    header('LOCATION: form.php?errorinsert=True');
    exit;
}

// Modo 1 - Insert com BindParam
// $stmt = $pdo->prepare("INSERT INTO users (name, email, pass, status) VALUES (:name, :email, :pass, :status)");
// $stmt->bindParam(':name', $post['name']);
// $stmt->bindParam(':email', $post['email']);
// $stmt->bindParam(':pass', $post['pass']);
// $stmt->bindParam(':status', $post['status']);
// $stmt->execute();
