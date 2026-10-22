<?php
// Define a URL para links, imagens e scripts (Navegador)
$base_url = "http://localhost/app-blog-guilherme3c/app-blog-main/";  
if (!defined('BASE_URL')) define('BASE_URL', $base_url);

// Define o Caminho Físico (Servidor) para evitar erros de include
// __DIR__ aponta para a pasta _conn. O realpath + .. sobe para a raiz 'app-blog'
if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR);
}

//Configurações do banco de dados
$host = "localhost";     // Endereço do servidor do banco de dados
$dbname = "app-blog-guilherme3c";   // Nome do banco de dados
$user = "root";   // Usuário do banco de dados
$password = ""; // Senha do banco de dados

try {
    // Cria uma nova instância da classe PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Em caso de erro, exibe a mensagem
    echo "Falha na conexão: " . $e->getMessage();
}


?>
