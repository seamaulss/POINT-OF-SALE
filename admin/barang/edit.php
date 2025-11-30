<?php
require_once '../../includes/auth.php';
if ($user['role'] !== 'admin') { header('Location: ../../user/dashboard.php'); exit; }
require_once '../../config/database.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->execute([$id]);
$barang = $stmt->fetch();

if (!$barang) {
    die("Barang tidak ditemukan!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $stmt = $pdo->prepare("UPDATE barang SET nama = ?, harga = ?, stok = ? WHERE id = ?");
    $stmt->execute([$nama, $harga, $stok, $id]);
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Edit Barang</h3>
    <form method="POST">
        <div class="mb-3">
            <label>Nama Barang</label>
            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($barang['nama']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="<?= $barang['harga'] ?>" required min="0">
        </div>
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= $barang['stok'] ?>" required min="0">
        </div>
        <button type="submit" class="btn btn-warning">Update</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>