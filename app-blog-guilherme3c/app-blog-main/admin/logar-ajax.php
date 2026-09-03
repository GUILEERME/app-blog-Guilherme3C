<?php
session_start();
header('Content-Type: application/json');

// Bloqueia acesso direto via URL
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido.']);
    exit;
}

require_once '../_conn/conect.php';

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$pass  = filter_input(INPUT_POST, 'pass', FILTER_DEFAULT);

if (!$email || !$pass) {
    echo json_encode(['status' => 'error', 'message' => 'Dados inválidos ou campos vazios.']);
    exit;
}

try {
    // Busca o usuário. Dica: no futuro, use password_verify aqui!
    $sql = "SELECT id, email, pass FROM users WHERE email = :email AND pass = :pass LIMIT 1";
    $sth = $pdo->prepare($sql);
    $sth->bindValue(':email', trim(strtolower($email)));
    $sth->bindValue(':pass', trim($pass));
    $sth->execute();

    $user = $sth->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email']   = $user['email'];
        
        echo json_encode([
            'status' => 'success', 
            'redirect' => 'home.php'
        ]);
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => 'E-mail ou senha incorretos.'
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Erro interno no banco de dados.'
    ]);
}