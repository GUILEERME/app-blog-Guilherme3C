-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/08/2026 às 16:42
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `app-blog`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `subtitle`, `description`, `image`, `status`, `slug`) VALUES
(1, 'O Futuro do Desenvolvimento Web com Bootstrap', 'Aprenda as melhores práticas de criação de interfaces responsivas', 'O Bootstrap continua sendo uma das ferramentas mais populares para criação de layouts web. Com suporte avançado a Flexbox e CSS Grid, é possível construir páginas modernas rapidamente.', 'post1.jpg', 1, 'o-futuro-do-desenvolvimento-web-com-bootstrap'),
(2, 'Guia Definitivo de UI/UX para Blogs Modernos', 'Dicas essenciais de tipografia e espaçamento para retenção de leitores', 'A experiência do usuário em blogs vai muito além da estética. Tipografia adequada, contraste de cores e espaçamento correto influenciam diretamente o tempo de leitura.', 'post2.jpg', 1, 'guia-definitivo-de-ui-ux-para-blogs-modernos'),
(3, 'Como Estruturar Projetos PHP sem Frameworks', 'Organize seu código com boas práticas e padrão de arquitetura limpo', 'Entenda como criar conexões seguras com PDO, organizar rotas simples e reaproveitar headers e footers em projetos PHP puros de forma eficiente.', '', 0, 'como-estruturar-projetos-php-sem-frameworks'),
(5, 'teste', 'teste', 'tes', 'ti-1.avif', 1, 'teste');

-- --------------------------------------------------------

--
-- Estrutura para tabela `level_users`
--

CREATE TABLE `level_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `level_users`
--

INSERT INTO `level_users` (`id`, `name`, `level`) VALUES
(1, 'admin', 20),
(2, 'super-admin', 50),
(3, 'Editor', 15);

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `id_level_users` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `pass`, `slug`, `image`, `status`, `id_level_users`) VALUES
(38, 'nelci', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', 'nelci', 'testimonial-3.jpg', 1, 1),
(44, 'Nelci Mariano Pinto Júnior', 'nelci.juninho@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'nelci-mariano-pinto-j-nior', 'user.png', 1, 1),
(45, 'Nelci Mariano Pinto Júnior', 'nelci.juninho2@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'nelci-mariano-pinto-j-nior-1', 'user-1.png', 1, 1),
(46, 'Nelci Mariano Pinto Júnior', 'nelci.juninho3@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'nelci-mariano-pinto-j-nior-2', 'user-2.png', 1, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `level_users`
--
ALTER TABLE `level_users`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `level_users`
--
ALTER TABLE `level_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
