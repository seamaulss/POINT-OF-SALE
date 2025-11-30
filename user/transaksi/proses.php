<?php
require_once '../../includes/auth.php';
if ($user['role'] !== 'user') exit;
require_once '../../config/database.php';

$total = 0;
$items = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jumlah = $_POST['jumlah'] ?? [];
    $harga = $_POST['harga'] ?? [];

   
    foreach ($jumlah as $barang_id => $qty) {
        if ($qty > 0) {
            $barang_id = (int)$barang_id;
            $qty = (int)$qty;
            $hrg = (float)$harga[$barang_id];

          
            $stmt = $pdo->prepare("SELECT stok FROM barang WHERE id = ?");
            $stmt->execute([$barang_id]);
            $stok = $stmt->fetchColumn();

            if ($qty > $stok) {
                die("Stok tidak cukup untuk barang ID $barang_id");
            }

            $subtotal = $qty * $hrg;
            $total += $subtotal;
            $items[] = ['id' => $barang_id, 'qty' => $qty, 'harga' => $hrg, 'subtotal' => $subtotal];
        }
    }

    if (empty($items)) {
        die("Tidak ada barang yang dipilih!");
    }

  
    $pdo->beginTransaction();

    try {
       
        $stmt = $pdo->prepare("INSERT INTO transaksi (user_id, total) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $total]);
        $transaksi_id = $pdo->lastInsertId();

      
        foreach ($items as $item) {
          
            $stmt = $pdo->prepare("INSERT INTO detail_transaksi (transaksi_id, barang_id, jumlah, harga_satuan, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$transaksi_id, $item['id'], $item['qty'], $item['harga'], $item['subtotal']]);

         
            $stmt = $pdo->prepare("UPDATE barang SET stok = stok - ? WHERE id = ?");
            $stmt->execute([$item['qty'], $item['id']]);
        }

        $pdo->commit();
        header("Location: struk.php?id=$transaksi_id");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>