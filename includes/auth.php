<?php


session_start();


$root = dirname(__DIR__); 

require_once $root . '/config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . $root . '/login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header('Location: ' . $root . '/login.php');
    exit;
}
?>