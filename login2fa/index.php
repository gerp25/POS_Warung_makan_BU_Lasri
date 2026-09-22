<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/koneksi.php';

// Cek apakah ada sesi user yang sedang proses login
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

$error_msg = '';

if (isset($_POST['btn_verifikasi'])) {
    $pin_input = trim($_POST['pin']);
    $username = $_SESSION['username'];

    // Ambil PIN user dari database
    $stmt = mysqli_prepare($con, "SELECT pin, peran FROM tbl_user WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['pin'] === $pin_input) {
            // PIN Benar: set status 2FA
            $_SESSION['is_2fa_passed'] = true;

            // Arahkan ke halaman sesuai peran
            if ($row['peran'] == 'A') {
                header("Location: ../home_admin/index.php");
            } else {
                header("Location: ../home_kasir/index.php");
            }
            exit();
        } else {
            $error_msg = "PIN Keamanan yang Anda masukkan salah!";
        }
    } else {
        $error_msg = "Akun tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Verifikasi 2FA - BAKUL SEGA BU LASTRI</title>
    
    <!-- Favicon-->
    <link rel="icon" href="../favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="../AdminBSB/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="../AdminBSB/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="../AdminBSB/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="../AdminBSB/css/style.css" rel="stylesheet">
</head>

<body class="login-page bg-red">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);"><b>2-FACTOR</b> AUTH</a>
            <small>BAKUL SEGA BU LASTRI</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="form_2fa" method="POST" action="">
                    <div class="msg">Masukkan PIN Keamanan Anda</div>

                    <?php if (!empty($error_msg)): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?= $error_msg; ?>
                        </div>
                    <?php endif; ?>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="pin" placeholder="PIN Keamanan (6 Digit)" maxlength="6" pattern="[0-9]*" inputmode="numeric" required autofocus autocomplete="off">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <button class="btn btn-block bg-red waves-effect" type="submit" name="btn_verifikasi">
                                <i class="material-icons">verified_user</i> VERIFIKASI PIN
                            </button>
                        </div>
                    </div>

                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-12 align-center">
                            <a href="../logout.php">Batal & Keluar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="../AdminBSB/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../AdminBSB/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../AdminBSB/plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="../AdminBSB/plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="../AdminBSB/js/admin.js"></script>
</body>

</html>