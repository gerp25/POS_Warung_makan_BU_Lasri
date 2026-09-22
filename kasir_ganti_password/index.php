<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/koneksi.php';

$authority = @$_SESSION['peran'];
$is_2fa_passed = @$_SESSION['is_2fa_passed'];

if (($authority != 'K' && $authority != 'Kasir' && $authority != 'kasir') || $is_2fa_passed !== true) {
    echo '<script>alert ("Akses ditolak. Silakan login kembali.");</script>';
    echo '<script>window.location.href ="../logout.php";</script>';
    exit();
}

$user_id = $_SESSION['id_user'] ?? $_SESSION['user_id'] ?? null;
$pesan_sukses = "";
$pesan_error  = "";

// Proses Perubahan Password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_simpan'])) {
    $password_lama  = $_POST['password_lama'] ?? '';
    $password_baru  = $_POST['password_baru'] ?? '';
    $konfirmasi_pwd = $_POST['konfirmasi_password'] ?? '';

    if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_pwd)) {
        $pesan_error = "Semua kolom password wajib diisi!";
    } elseif ($password_baru !== $konfirmasi_pwd) {
        $pesan_error = "Konfirmasi password baru tidak cocok!";
    } else {
        // Ambil password lama dari database
        $stmt = $koneksi->prepare("SELECT password FROM users WHERE id = ? OR username = ? LIMIT 1");
        $username_session = $_SESSION['username'] ?? '';
        $stmt->bind_param("is", $user_id, $username_session);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Cek kecocokan password lama (dukungan password_verify atau md5)
            if (password_verify($password_lama, $row['password']) || md5($password_lama) === $row['password'] || $password_lama === $row['password']) {
                
                // Hash password baru dengan password_hash
                $password_hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);
                
                $update_stmt = $koneksi->prepare("UPDATE users SET password = ? WHERE id = ? OR username = ?");
                $update_stmt->bind_param("sis", $password_hash_baru, $user_id, $username_session);
                
                if ($update_stmt->execute()) {
                    $pesan_sukses = "Password berhasil diperbarui!";
                } else {
                    $pesan_error = "Gagal memperbarui password. Silakan coba lagi.";
                }
                $update_stmt->close();
            } else {
                $pesan_error = "Password saat ini salah!";
            }
        } else {
            $pesan_error = "Pengguna tidak ditemukan!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Ganti Password - BAKUL SEGA BU LASTRI</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <link href="../AdminBSB/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../AdminBSB/plugins/node-waves/waves.css" rel="stylesheet" />
    <link href="../AdminBSB/plugins/animate-css/animate.css" rel="stylesheet" />
    <link href="../AdminBSB/css/style.css" rel="stylesheet">
    <link href="../AdminBSB/css/themes/all-themes.css" rel="stylesheet" />
</head>

<body class="theme-red">
    <div class="overlay"></div>

    <?php include '../navbar.php'; ?>
    <section>
        <?php include '../sidebarkasir.php'; ?>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>PENGATURAN AKUN</h2>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>GANTI PASSWORD</h2>
                            <small>Perbarui kata sandi akun Anda untuk menjaga keamanan sistem</small>
                        </div>
                        <div class="body">

                            <?php if (!empty($pesan_sukses)): ?>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?= htmlspecialchars($pesan_sukses); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($pesan_error)): ?>
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?= htmlspecialchars($pesan_error); ?>
                                </div>
                            <?php endif; ?>

                            <form id="form_ganti_password" method="POST" action="">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="password_lama" required>
                                        <label class="form-label">Password Saat Ini</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="password_baru" required>
                                        <label class="form-label">Password Baru</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="konfirmasi_password" required>
                                        <label class="form-label">Konfirmasi Password Baru</label>
                                    </div>
                                </div>

                                <button type="submit" name="btn_simpan" class="btn btn-primary btn-lg waves-effect">
                                    <i class="material-icons">save</i>
                                    <span>SIMPAN PERUBAHAN</span>
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <?php include '../footer.php'; ?>

        </div>
    </section>

    <script src="../AdminBSB/plugins/jquery/jquery.min.js"></script>
    <script src="../AdminBSB/plugins/bootstrap/js/bootstrap.js"></script>
    <script src="../AdminBSB/plugins/node-waves/waves.js"></script>
    <script src="../AdminBSB/js/admin.js"></script>
</body>
</html>