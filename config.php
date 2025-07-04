<?php
// Evita redefinição de sessão se já estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_name('PHPSESSID');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '.petflow.pro',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'None'
    ]);

    if (isset($_GET['session']) && preg_match('/^[a-zA-Z0-9,-]{22,}$/', $_GET['session'])) {
        session_id($_GET['session']);
    }

    if (!isset($_COOKIE['PHPSESSID']) && !isset($_GET['session'])) {
        session_id(bin2hex(random_bytes(16)));
    }

    session_start();
}

// Banco de dados
$host = 'localhost';
$db   = 'u167672856_petflow';
$user = 'u167672856_petflow';
$pass = '=E+KN0ngv1rH';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    exit('Erro na conexão com o banco de dados: ' . $e->getMessage());
}
