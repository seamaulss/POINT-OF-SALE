<?php
$root = $_SERVER['DOCUMENT_ROOT'] . '/posmaul'; 
require_once $root . '/includes/auth.php';
if ($user['role'] !== 'admin') { header('Location: ../../user/dashboard.php'); exit; }
require_once '../../config/database.php';

$stmt = $pdo->query("SELECT * FROM barang ORDER BY nama");
$barang = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Barang - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Daftar Barang</h2>
    <a href="tambah.php" class="btn btn-primary mb-3">Tambah Barang</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($barang as $b): ?>
            <tr>
                <td><?= $b['id'] ?></td>
                <td><?= htmlspecialchars($b['nama']) ?></td>
                <td>Rp <?= number_format($b['harga'], 0, ',', '.') ?></td>
                <td><?= $b['stok'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="hapus.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="../dashboard.php" class="btn btn-secondary">Kembali</a>
</div>
</body>
</html>