<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/koneksi.php';

$authority = @$_SESSION['peran'];
$is_2fa_passed = @$_SESSION['is_2fa_passed'];

if (($authority != 'A' && $authority != 'Admin') || $is_2fa_passed !== true) {
    echo '<script>alert("Akses ditolak. Silakan login kembali.");</script>';
    echo '<script>window.location.href ="../logout.php";</script>';
    exit();
}

// Cek parameter id tersedia di URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_pembelian = mysqli_real_escape_string($con, $_GET['id']);

    // buat hapus detail transaksi dulu
    $query_detail = "DELETE FROM pembelian_detail WHERE id_pembelian = '$id_pembelian'";
    mysqli_query($con, $query_detail);

    // buat ngehapus data dari tabel utamanya
    $query_pembelian = "DELETE FROM pembelian WHERE id_pembelian = '$id_pembelian'";
    
    if (mysqli_query($con, $query_pembelian)) {
        echo '<script>alert("Data transaksi pembelian berhasil dihapus.");</script>';
        echo '<script>window.location.href = "index.php";</script>';
    } else {
        echo '<script>alert("Gagal menghapus data transaksi: ' . mysqli_error($con) . '");</script>';
        echo '<script>window.location.href = "index.php";</script>';
    }
} else {
    echo '<script>window.location.href = "index.php";</script>';
}
exit();
?>