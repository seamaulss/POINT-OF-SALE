<?php
require_once '../../includes/auth.php';
if ($user['role'] !== 'admin') exit;
require_once '../../config/database.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM detail_transaksi WHERE barang_id = ?");
$stmt->execute([$id]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    die("Tidak bisa menghapus: barang sudah pernah digunakan dalam transaksi!");
}

$stmt = $pdo->prepare("DELETE FROM barang WHERE id = ?");
$stmt->execute([$id]);

header('Location: index.php');
exit;
?>