<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "database/koneksi.php";

// Arahkan ke folder login/ (otomatis membuka login/index.php)
echo "<script>window.location='login/';</script>";
exit();
?>