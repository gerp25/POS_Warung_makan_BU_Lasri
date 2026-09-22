<?php
// Mendapatkan nama file yang sedang diakses untuk mengatur menu mana yang sedang aktif (active class)
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside id="leftsidebar" class="sidebar">
    <!-- User Info -->
    <div class="user-info">
        <div class="image">
            <img src="../AdminBSB/images/user.png" width="48" height="48" alt="User" />
        </div>
        <div class="info-container">
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username'] ?? 'Kasir'); ?>
            </div>
            <div class="email">Kasir - Bakul Sega Bu Lastri</div>
            <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <li><a href="ganti_password.php"><i class="material-icons">lock</i>Ganti Password</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="../logout.php"><i class="material-icons">input</i>Sign Out</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- #User Info -->

    <!-- Menu Sidebar -->
    <div class="menu">
        <ul class="list">
            <li class="header">NAVIGASI UTAMA</li>
            
            <!-- Dashboard Kasir -->
            <li class="<?= ($current_page == 'index.php' || $current_page == 'dashboard.php') ? 'active' : ''; ?>">
                <a href="index.php">
                    <i class="material-icons">dashboard</i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Form Transaksi / POS Utama -->
            <li class="<?= ($current_page == 'transaksi.php' || $current_page == 'pos.php') ? 'active' : ''; ?>">
                <a href="transaksi.php">
                    <i class="material-icons">shopping_cart</i>
                    <span>Transaksi Penjualan</span>
                </a>
            </li>

            <!-- Riwayat Transaksi -->
            <li class="<?= ($current_page == 'riwayat_transaksi.php') ? 'active' : ''; ?>">
                <a href="riwayat_transaksi.php">
                    <i class="material-icons">receipt</i>
                    <span>Riwayat Transaksi</span>
                </a>
            </li>

            <!-- Daftar Menu & Stok -->
            <li class="<?= ($current_page == 'daftar_menu.php') ? 'active' : ''; ?>">
                <a href="daftar_menu.php">
                    <i class="material-icons">restaurant_menu</i>
                    <span>Daftar & Stok Menu</span>
                </a>
            </li>

            <!-- Rekap Shift Harian -->
            <li class="<?= ($current_page == 'rekap_shift.php') ? 'active' : ''; ?>">
                <a href="rekap_shift.php">
                    <i class="material-icons">today</i>
                    <span>Rekap Kas Harian</span>
                </a>
            </li>

            <!-- Ganti Password -->
            <li class="<?= (strpos($current_page, 'kasir_ganti_password') !== false) ? 'active' : ''; ?>">
                <a href="../kasir_ganti_password/index.php">
                    <i class="material-icons">lock</i>
                    <span>Ganti Password</span>
                </a>
            </li>

            <li>
                <a href="../logout.php">
                    <i class="material-icons">input</i>
                    <span>Keluar</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- #Menu Sidebar -->

    <!-- Footer Sidebar -->
    <div class="legal">
        <div class="copyright">
            &copy; <?= date('Y'); ?> <a href="javascript:void(0);">Bakul Sega Bu Lastri</a>.
        </div>
        <div class="version">
            <b>Version: </b> 1.0.0
        </div>
    </div>
    <!-- #Footer Sidebar -->
</aside>