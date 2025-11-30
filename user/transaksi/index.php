<?php
require_once '../../includes/auth.php';
if ($user['role'] !== 'user') exit;
require_once '../../config/database.php';

$stmt = $pdo->query("SELECT * FROM barang WHERE stok > 0 ORDER BY nama");
$barang = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Transaksi Baru</h3>

    <form id="formTransaksi" method="POST" action="proses.php">
        <table class="table">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barang as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['nama']) ?></td>
                    <td>Rp <?= number_format($b['harga'], 0, ',', '.') ?></td>
                    <td><?= $b['stok'] ?></td>
                    <td>
                        <input type="number" name="jumlah[<?= $b['id'] ?>]" min="0" max="<?= $b['stok'] ?>" class="form-control" value="0">
                        <input type="hidden" name="harga[<?= $b['id'] ?>]" value="<?= $b['harga'] ?>">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Proses Transaksi</button>
        <a href="../dashboard.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>