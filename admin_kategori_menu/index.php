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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Daftar Menu - Bakul Sega Bu Lastri</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <link href="../AdminBSB/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../AdminBSB/plugins/node-waves/waves.css" rel="stylesheet" />
    <link href="../AdminBSB/plugins/animate-css/animate.css" rel="stylesheet" />
    <link href="../AdminBSB/css/style.css" rel="stylesheet">
    <link href="../AdminBSB/css/themes/all-themes.css" rel="stylesheet" />

    <style>
        .menu-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }
        
        .menu-img-container {
            width: 85% !important;
            aspect-ratio: 4 / 3 !important;
            position: relative !important;
            overflow: hidden !important;
            background-color: #f5f5f5;
            display: block;
            margin: 15px auto 0 auto;
            border-radius: 12px;
        }

        .menu-img-container img {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }

        .menu-body {
            padding: 15px;
            text-align: center;
        }

        .menu-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .menu-price {
            font-size: 14px;
            font-weight: bold;
            color: #fb8c00;
            margin-bottom: 8px;
        }

        .menu-desc {
            font-size: 13px;
            color: #666;
            height: 20px;
            overflow: hidden;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        /* Penyesuaian Header agar Tombol Sejajar */
        .block-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .block-header-flex h2 {
            margin: 0;
        }

        /* Styling Search Bar */
        .search-container {
            position: relative;
            min-width: 280px;
        }

        .search-container input {
            padding-left: 38px;
            border-radius: 20px;
            border: 1px solid #ccc;
            box-shadow: none !important;
        }

        .search-container .material-icons {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 20px;
        }
    </style>
</head>

<body class="theme-red">
    <div class="overlay"></div>

    <?php include '../navbar.php'; ?>
    <section>
        <?php include '../sidebaradmin.php'; ?>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Header dengan Search Bar -->
            <div class="block-header block-header-flex">
                <h2>DAFTAR MENU MASAKAN</h2>
                
                <!-- Input Search Bar -->
                <div class="search-container">
                    <i class="material-icons">search</i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari menu masakan...">
                </div>
            </div>

            <div class="row clearfix" id="menuList">
                <?php
                // Mengambil data langsung dari tabel menu
                $query_menu = mysqli_query($con, "SELECT * FROM menu ORDER BY nama_menu ASC");
                
                if ($query_menu && mysqli_num_rows($query_menu) > 0) {
                    while ($menu = mysqli_fetch_assoc($query_menu)) {
                        $gambar_menu = !empty($menu['gambar']) ? '../image/' . $menu['gambar'] : '../image/sayur_dan_lauk.png';
                        $harga = isset($menu['harga']) ? 'Rp ' . number_format($menu['harga'], 0, ',', '.') : '-';
                ?>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 menu-item">
                    <div class="menu-card">
                        <div class="menu-img-container">
                            <img src="<?= $gambar_menu; ?>" alt="<?= htmlspecialchars($menu['nama_menu']); ?>">
                        </div>
                        <div class="menu-body">
                            <div class="menu-title"><?= htmlspecialchars($menu['nama_menu']); ?></div>
                            <div class="menu-price"><?= $harga; ?></div>
                            <div class="menu-desc"><?= htmlspecialchars($menu['deskripsi'] ?? ''); ?></div>
                            <a href="detail_menu.php?id=<?= $menu['id']; ?>" class="btn btn-danger btn-block waves-effect">
                                <i class="material-icons" style="font-size: 16px; vertical-align: middle;">visibility</i> 
                                <span style="vertical-align: middle;">LIHAT DETAIL</span>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo '<div class="col-md-12 text-center"><p>Belum ada menu masakan yang tersedia.</p></div>';
                }
                ?>
                
                <!-- Pesan ketika hasil pencarian tidak ditemukan -->
                <div id="noResults" class="col-md-12 text-center" style="display: none;">
                    <p class="text-muted">Menu yang anda cari tidak ditemukan.</p>
                </div>
            </div>

            <!-- MEMANGGIL FOOTER -->
            <?php include '../footer.php'; ?>
        </div>
    </section>

    

    <script src="../AdminBSB/plugins/jquery/jquery.min.js"></script>
    <script src="../AdminBSB/plugins/bootstrap/js/bootstrap.js"></script>
    <script src="../AdminBSB/plugins/node-waves/waves.js"></script>
    <script src="../AdminBSB/js/admin.js"></script>

    <!-- Script Filter Pencarian Real-time -->
    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase().trim();
                var visibleCount = 0;

                $('.menu-item').each(function() {
                    var title = $(this).find('.menu-title').text().toLowerCase();
                    var desc = $(this).find('.menu-desc').text().toLowerCase();

                    if (title.indexOf(value) > -1 || desc.indexOf(value) > -1) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                if (visibleCount === 0) {
                    $('#noResults').show();
                } else {
                    $('#noResults').hide();
                }
            });
        });
    </script>
</body>
</html>