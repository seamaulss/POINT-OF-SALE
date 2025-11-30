<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    die("Anda harus login untuk melihat struk.");
}

$root = dirname(__DIR__, 2);
require_once $root . '/config/database.php';


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$currentUser = $stmt->fetch();

if (!$currentUser) {
    die("User tidak valid.");
}


$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    die("ID transaksi tidak valid.");
}


if ($currentUser['role'] === 'admin') {

    $stmt = $pdo->prepare("
        SELECT t.*, u.username 
        FROM transaksi t 
        JOIN users u ON t.user_id = u.id 
        WHERE t.id = ?
    ");
    $stmt->execute([$id]);
} else {

    $stmt = $pdo->prepare("
        SELECT t.*, u.username 
        FROM transaksi t 
        JOIN users u ON t.user_id = u.id 
        WHERE t.id = ? AND t.user_id = ?
    ");
    $stmt->execute([$id, $_SESSION['user_id']]);
}

$transaksi = $stmt->fetch();

if (!$transaksi) {
    die("Transaksi tidak ditemukan!");
}

$stmt = $pdo->prepare("
    SELECT dt.*, b.nama 
    FROM detail_transaksi dt 
    JOIN barang b ON dt.barang_id = b.id 
    WHERE dt.transaksi_id = ?
");
$stmt->execute([$id]);
$details = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <meta charset="UTF-8">
    <style>
        body { 
            font-family: 'Courier New', monospace; 
            max-width: 400px; 
            margin: 0 auto; 
            padding: 15px;
            background: white;
            font-size: 14px;
        }
        .center { text-align: center; }
        .border { border-top: 1px dashed #000; margin: 8px 0; }
        .text-right { text-align: right; }
        .mb { margin-bottom: 6px; }
    </style>
</head>
<body>
    <div class="center">
        <h3>TOKO MAULANA</h3>
        <p>Jl. Tampomas</p>
        <p>----------</p>
        <p><strong>STRUK PEMBAYARAN</strong></p>
    </div>

    <p class="mb">No: #<?= $transaksi['id'] ?></p>
    <p class="mb">Tgl: <?= date('d-m-Y H:i', strtotime($transaksi['tanggal'])) ?></p>
    <p class="mb">Kasir: <?= htmlspecialchars($transaksi['username']) ?></p>

    <div class="border"></div>

    <?php foreach ($details as $d): ?>
        <div class="mb">
            <span><?= str_pad(substr($d['nama'], 0, 18), 18) ?></span>
            <span class="text-right"><?= $d['jumlah'] ?>x @<?= number_format($d['harga_satuan'], 0, ',', '.') ?></span>
        </div>
        <div class="text-right mb">Rp<?= number_format($d['subtotal'], 0, ',', '.') ?></div>
    <?php endforeach; ?>

    <div class="border"></div>
    <p class="center mb"><strong>TOTAL: Rp<?= number_format($transaksi['total'], 0, ',', '.') ?></strong></p>
    <div class="border"></div>
    <p class="center">Terima kasih!</p>

    <div class="center" style="margin-top: 20px;">
        <?php if ($currentUser['role'] === 'admin'): ?>
            <a href="<?= $_SERVER['HTTP_REFERER'] ?? '/posmaul/admin/transaksi/index.php' ?>" class="btn btn-sm">&larr; Kembali</a>
        <?php else: ?>
            <a href="../dashboard.php" class="btn btn-sm">&larr; Kembali</a>
        <?php endif; ?>
        <button onclick="window.print()" style="margin-left: 10px;">🖨️ Cetak</button>
    </div>
</body>
</html>