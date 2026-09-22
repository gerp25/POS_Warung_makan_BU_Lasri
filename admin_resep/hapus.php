<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/koneksi.php';

// Validasi Keamanan
$authority = @$_SESSION['peran'];
$is_2fa_passed = @$_SESSION['is_2fa_passed'];

if (($authority != 'A' && $authority != 'Admin') || $is_2fa_passed !== true) {
    echo '<script>alert ("Akses ditolak."); window.location.href ="../logout.php";</script>';
    exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);

    // Hapus terlebih dahulu data relasi di resep_detail (jika ada relasi foreign key)
    mysqli_query($con, "DELETE FROM resep_detail WHERE menu_id = '$id'");

    // Hapus data utama dari tabel menu
    $query = mysqli_query($con, "DELETE FROM menu WHERE id = '$id'");

    if ($query) {
        echo '<script>alert("Menu berhasil dihapus!"); window.location.href="index.php";</script>';
    } else {
        echo '<script>alert("Gagal menghapus menu: ' . mysqli_error($con) . '"); window.location.href="index.php";</script>';
    }
} else {
    header("Location: index.php");
}
exit();
?>