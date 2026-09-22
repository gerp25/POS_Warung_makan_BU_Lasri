<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/koneksi.php';

$authority = @$_SESSION['peran'];
$is_2fa_passed = @$_SESSION['is_2fa_passed'];

if (($authority != 'A' && $authority != 'Admin') || $is_2fa_passed !== true) {
    echo '<script>alert ("Akses ditolak. Silakan login kembali.");</script>';
    echo '<script>window.location.href ="../logout.php";</script>';
    exit();
}

$username_param = isset($_GET['username']) ? $_GET['username'] : '';

if (empty($username_param)) {
    echo '<script>alert("Username tidak valid."); window.location.href="index.php";</script>';
    exit();
}

// Mencegah admin menghapus akunnya sendiri yang sedang aktif login
if ($username_param === @$_SESSION['username']) {
    echo '<script>alert("Anda tidak dapat menghapus akun yang sedang Anda gunakan saat ini!"); window.location.href="index.php";</script>';
    exit();
}

// Proses hapus menggunakan Prepared Statement
$stmt = mysqli_prepare($con, "DELETE FROM tbl_user WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username_param);

if (mysqli_stmt_execute($stmt)) {
    echo '<script>alert("Akun pengguna berhasil dihapus."); window.location.href="index.php";</script>';
} else {
    echo '<script>alert("Gagal menghapus pengguna: ' . mysqli_error($con) . '"); window.location.href="index.php";</script>';
}

mysqli_stmt_close($stmt);
mysqli_close($con);
exit();
?>