<?php
// Essa validação é quando acessa a url diretamente: localhost/app-login/logar.php
if (empty($_POST)) {
    echo "Acesso Restrito";
    die();
}
// Essa validação é quando remove o required do html e clica no submite sem inserir e-mail
if (empty($_POST['email'])) :
    header("LOCATION: ../app-login/");
    die();
endif;
//Login e Senha Válido
$login_valido = 'professornelcimariano@gmail.com';
$password_valido = MD5('Senac#@#12');
//Recebe os dados do Formulário
$email = filter_input(INPUT_POST, 'email', FILTER_DEFAULT);
$pass = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

if (checkComplexPass($pass)) :

    if ($email == $login_valido && MD5($pass) == $password_valido) :
        // header("LOCATION: ../app-login/admin/home.php");
        echo "Maravilha! Está Correto </p>";
        echo "E-mail: " . $email;
        echo "<br>";
        echo "Senha: " . MD5($pass);
    else :
        echo 'E-mail ou Senha inválido';
    endif;
else :
    echo "Sua senha não atende os Requisitos de Complexidade";
endif;

// if(checkComplexPass($pass)) {
//     echo " <p> A senha é obedece os Requisitos </p>";
// } else {
//     echo " <p> A senha NÃO obedece </p>";
// }

function checkComplexPass($pass)
{
    // Verifica se a senha tem pelo menos 8 caracteres
    if (strlen($pass) < 8) {
        return false;
    }

    // Verifica se a senha contém pelo menos uma letra maiúscula
    if (!preg_match('/[A-Z]/', $pass)) {
        return false;
    }

    // Verifica se a senha contém pelo menos uma letra minúscula
    if (!preg_match('/[a-z]/', $pass)) {
        return false;
    }

    // Verifica se a senha contém pelo menos um número
    if (!preg_match('/[0-9]/', $pass)) {
        return false;
    }

    // Verifica se a senha contém pelo menos um caractere especial
    if (!preg_match('/[\W_]/', $pass)) {
        return false;
    }

    // A senha atende a todos os requisitos de complexidade
    return true;
}
