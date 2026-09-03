<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: index.php?error=true');
    exit();
}
$raiz = dirname(__DIR__, 2); // __DIR__ é uma forma de obter o diretório atual do arquivo, e dirname(__DIR__, 2) sobe dois níveis para chegar à raiz do projeto
include_once $raiz . '/setup/connect.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Painel</title>
    <link rel="stylesheet" href="<?= $base_url ?>public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>public/admin/css/admin.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <button class="btn btn-link text-white me-3" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <div class="ms-auto">
                <span class="text-white me-3">Olá, <?= $_SESSION['email'] ?></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Sair</a>
            </div>
        </div>
    </nav>

    <div class="wrapper">
        <nav id="sidebar" class="bg-light border-end">
            <div class="p-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>admin/home.php">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center"
                            href="<?= $base_url ?>admin/users/index.php">
                            <span><i class="fas fa-users me-2"></i> Usuários</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center"
                            href="<?= $base_url ?>admin/blogs/index.php">
                            <span><i class="fas fa-blog me-2"></i> Blogs</span>
                        </a>
                    </li>

                   
                </ul>
            </div>
        </nav>
        <main class="content-wrapper p-4">