<?php
// Mulai session jika diperlukan
session_start();

// Jika sudah login otomatis arahkan ke dashboard (opsional)
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit;
}

// Jika belum login arahkan ke login.php
header('Location: login.php');
exit;
