<?php require_once '../includes/auth.php'; if ($user['role'] !== 'admin') { header('Location: ../user/dashboard.php'); exit; } ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Dashboard Admin</h2>
    <p>Selamat datang, <?= htmlspecialchars($user['username']) ?> (Admin)</p>
    <a href="barang/index.php" class="btn btn-success">Kelola Barang</a>
    <a href="transaksi/index.php" class="btn btn-info">Lihat Semua Transaksi</a>
    <a href="../logout.php" class="btn btn-danger float-end">Logout</a>
</div>
</body>
</html>