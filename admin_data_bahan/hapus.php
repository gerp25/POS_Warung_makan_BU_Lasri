<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/koneksi.php';

// Validasi Keamanan Hak Akses & 2FA
$authority = @$_SESSION['peran'];
$is_2fa_passed = @$_SESSION['is_2fa_passed'];

if (($authority != 'A' && $authority != 'Admin') || $is_2fa_passed !== true) {
    echo '<script>alert("Akses ditolak. Silakan login kembali.");</script>';
    echo '<script>window.location.href ="../logout.php";</script>';
    exit();
}

// Ambil ID dari Parameter URL
$id = isset($_GET['id']) ? mysqli_real_escape_string($con, $_GET['id']) : '';

if (empty($id)) {
    echo '<script>alert("ID Bahan Baku tidak valid!"); window.location.href="index.php";</script>';
    exit();
}

// Cek Apakah Bahan Baku Masih Digunakan dalam Tabel Resep Detail
$cek_resep = mysqli_query($con, "SELECT id FROM resep_detail WHERE bahan_baku_id = '$id'");

if (mysqli_num_rows($cek_resep) > 0) {
    // Jika bahan masih terikat pada resep menu, gagalkan penghapusan demi integritas data
    echo '<script>alert("Gagal menghapus! Bahan baku ini sedang digunakan pada resep menu."); window.location.href="index.php";</script>';
    exit();
} else {
    // Jalankan Query Hapus Data
    $query_hapus = mysqli_query($con, "DELETE FROM bahan_baku WHERE id = '$id'");

    if ($query_hapus) {
        echo '<script>alert("Data bahan baku berhasil dihapus!"); window.location.href="index.php";</script>';
        exit();
    } else {
        echo '<script>alert("Gagal menghapus data: ' . mysqli_error($con) . '"); window.location.href="index.php";</script>';
        exit();
    }
}
?>