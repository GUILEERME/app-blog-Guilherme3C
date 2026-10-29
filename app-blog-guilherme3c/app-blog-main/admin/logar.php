
<?php
session_start();

if (empty($_POST)) {
    die('Acesso Restrito');
}

include_once '../setup/connect.php';

$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($post['email'], $post['pass'])) {
    $post['email'] = trim(strtolower($post['email']));
    $post['pass'] = trim($post['pass']);

    if (empty($post['email']) || empty($post['pass'])) {
        echo 'Preencha todos os campos!';
        exit;
    }

    try {
        $senha = md5($post['pass']);

        $sql = "
            SELECT id, name, email, id_level_users
            FROM users
            WHERE email = :email AND pass = :pass
            LIMIT 1
        ";

        $sth = $pdo->prepare($sql);
        $sth->bindParam(':email', $post['email']);
        $sth->bindParam(':pass', $senha);
        $sth->execute();

        $usuario = $sth->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            session_regenerate_id(true);

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['name'] = $usuario['name'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['id_level_users'] =
                (int) $usuario['id_level_users'];

            header('Location: home.php');
            exit;
        }

        unset(
            $_SESSION['id'],
            $_SESSION['name'],
            $_SESSION['email'],
            $_SESSION['id_level_users']
        );

        header('Location: index.php?error=true');
        exit;

    } catch (PDOException $e) {
        error_log($e->getMessage());
        http_response_code(500);
        exit('Ocorreu um erro ao processar o login.');
    }
}

header('Location: index.php');
exit;
