<?php
require_once '../../includes/auth.php';
if ($user['role'] !== 'admin') exit;
require_once '../../config/database.php';

$stmt = $pdo->query("
    SELECT t.*, u.username 
    FROM transaksi t 
    JOIN users u ON t.user_id = u.id 
    ORDER BY t.tanggal DESC
");
$transaksi_list = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Semua Transaksi</h2>
    <a href="../dashboard.php" class="btn btn-secondary mb-3">Kembali</a>
    
    <?php if (empty($transaksi_list)): ?>
        <div class="alert alert-info">Belum ada transaksi.</div>
    <?php else: ?>
        <?php foreach ($transaksi_list as $t): ?>
            <div class="card mb-3">
                <div class="card-header">
                    ID: #<?= $t['id'] ?> | Kasir: <?= htmlspecialchars($t['username']) ?> | Tanggal: <?= date('d-m-Y H:i', strtotime($t['tanggal'])) ?>
                </div>
                <div class="card-body">
                    <h5>Total: Rp <?= number_format($t['total'], 0, ',', '.') ?></h5>
                    <a href="../../user/transaksi/struk.php?id=<?= $t['id'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat Struk</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>