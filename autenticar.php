<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ⚠️ Sessão configurada corretamente antes de tudo
session_name('PHPSESSID');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '.petflow.pro',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'None'
]);

// ⚠️ Inclui config com os handlers ANTES do session_start()
require_once 'config.php';

// ⚠️ Agora sim: inicia a sessão
session_start();

// Coleta os dados
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$senha = $_POST['senha'] ?? '';

if (!$email || !$senha) {
    $_SESSION['login_error'] = "Informe e-mail e senha.";
    header("Location: index.php");
    exit;
}

// Busca usuário
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
$stmt->bindParam(':email', $email);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($senha, $usuario['senha'])) {
    // Armazena dados na sessão
    $_SESSION['user_id'] = $usuario['id'];
    $_SESSION['user_name'] = $usuario['nome_completo'];
    $_SESSION['user_email'] = $usuario['email'];

    // Estabelecimento
    $stmtEst = $pdo->prepare("SELECT * FROM usuarios_estabelecimentos WHERE id_usuario = :id_usuario LIMIT 1");
    $stmtEst->bindParam(':id_usuario', $usuario['id']);
    $stmtEst->execute();
    $estabelecimento = $stmtEst->fetch(PDO::FETCH_ASSOC);

    if (!$estabelecimento) {
        $_SESSION['login_error'] = "Usuário sem estabelecimento vinculado.";
        header("Location: index.php");
        exit;
    }

    $_SESSION['estabelecimento_id'] = $estabelecimento['id'];
    $_SESSION['estabelecimento_nome'] = $estabelecimento['nome_fantasia'];

    // Cookies compartilhados
    $domain = ".petflow.pro";
    $expire = time() + (86400 * 7); // 7 dias

    setcookie("user_id", $usuario['id'], $expire, "/", $domain, true, true);
    setcookie("user_name", $usuario['nome_completo'], $expire, "/", $domain, true, true);
    setcookie("user_email", $usuario['email'], $expire, "/", $domain, true, true);
    setcookie("estabelecimento_id", $estabelecimento['id'], $expire, "/", $domain, true, true);
    setcookie("estabelecimento_nome", $estabelecimento['nome_fantasia'], $expire, "/", $domain, true, true);

    // ⚠️ Fecha e grava sessão no banco
    session_write_close();

    // Redireciona com o ID da sessão
    header("Location: https://app.petflow.pro/index.php?session=" . urlencode(session_id()));
    exit;
} else {
    $_SESSION['login_error'] = "E-mail ou senha inválidos.";
    header("Location: index.php");
    exit;
}
