<?php
require_once '../includes/auth.php';
if ($user['role'] !== 'user') { header('Location: ../admin/dashboard.php'); exit; }
require_once '../config/database.php';

$stmt = $pdo->prepare("SELECT * FROM transaksi WHERE user_id = ? ORDER BY tanggal DESC");
$stmt->execute([$_SESSION['user_id']]);
$riwayat = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>POSmaul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>POS - Maul</h2>
    <p>Halo, <strong><?= htmlspecialchars($user['username']) ?></strong></p>
    
    <a href="transaksi/index.php" class="btn btn-success mb-3">Buat Transaksi Baru</a>
    <a href="../logout.php" class="btn btn-danger float-end">Logout</a>

    <h4 class="mt-4">Riwayat Transaksi Anda</h4>
    <?php if (empty($riwayat)): ?>
        <p class="text-muted">Belum ada transaksi.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($riwayat as $t): ?>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h6>#<?= $t['id'] ?> | <?= date('d-m-Y H:i', strtotime($t['tanggal'])) ?></h6>
                        <p>Total: Rp <?= number_format($t['total'], 0, ',', '.') ?></p>
                        <a href="transaksi/struk.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-primary">Lihat Struk</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>