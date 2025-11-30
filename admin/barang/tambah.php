<?php
require_once '../../includes/auth.php';
if ($user['role'] !== 'admin') exit;
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $stmt = $pdo->prepare("INSERT INTO barang (nama, harga, stok) VALUES (?, ?, ?)");
    $stmt->execute([$nama, $harga, $stok]);
    header('Location: index.php');
    exit;
}
?>

<form method="POST" class="container mt-4">
    <h3>Tambah Barang</h3>
    <div class="mb-3">
        <label>Nama Barang</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" required min="0">
    </div>
    <div class="mb-3">
        <label>Stok</label>
        <input type="number" name="stok" class="form-control" required min="0">
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="index.php" class="btn btn-secondary">Batal</a>
</form>