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
    <title>Data Bahan Baku - Resto Project</title>

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
        <?php include '../sidebaradmin.php'; ?>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>MANAJEMEN DATA BAHAN BAKU</h2>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                DAFTAR BAHAN BAKU
                                <small>Kelola persediaan dan harga satuan bahan baku masakan di sini</small>
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li>
                                    <a href="tambah.php" class="btn bg-blue waves-effect">
                                        <i class="material-icons">add_box</i>
                                        <span>TAMBAH BAHAN</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="body table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">No.</th>
                                        <th width="30%">Nama Bahan</th>
                                        <th width="15%" class="text-center">Stok</th>
                                        <th width="15%" class="text-center">Satuan</th>
                                        <th width="15%">Harga Satuan</th>
                                        <th width="20%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query_bahan = mysqli_query($con, "SELECT * FROM bahan_baku ORDER BY nama_bahan ASC");
                                    $no = 1;

                                    while ($data = mysqli_fetch_array($query_bahan)) {
                                        $id_row = $no;
                                        $id_primary = $data['id'];
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no; ?></td>
                                        <td><strong><?= htmlspecialchars($data['nama_bahan']); ?></strong></td>
                                        <td class="text-center"><?= $data['stok'];?></td>
                                        <td class="text-center"><?= htmlspecialchars($data['satuan']); ?></td>
                                        <td>Rp <?= number_format($data['harga_satuan'], 0, ',', '.'); ?></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-success waves-effect" data-toggle="modal" data-target="#modalDetail<?= $id_row; ?>" title="Detail Bahan"><i class="material-icons">visibility</i></button>
                                            <a href="edit.php?id=<?= urlencode($id_primary); ?>" class="btn btn-sm btn-info waves-effect" title="Edit"><i class="material-icons">edit</i></a>
                                            <a href="hapus.php?id=<?= urlencode($id_primary); ?>" class="btn btn-sm btn-danger waves-effect" title="Hapus" onclick="return confirm('Yakin ingin menghapus bahan <?= htmlspecialchars($data['nama_bahan']); ?>?');"><i class="material-icons">delete</i></a>
                                        </td>
                                    </tr>

                                    <!-- MODAL DETAIL BAHAN BAKU -->
                                    <div class="modal fade" id="modalDetail<?= $id_row; ?>" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-green">
                                                    <h4 class="modal-title">Detail Bahan: <?= htmlspecialchars($data['nama_bahan']); ?></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Nama Bahan:</strong> <?= htmlspecialchars($data['nama_bahan']); ?></p>
                                                    <p><strong>Jumlah Stok Tersedia:</strong> <?= number_format($data['stok'], 2, ',', '.'); ?> <?= htmlspecialchars($data['satuan']); ?></p>
                                                    <p><strong>Harga per Satuan:</strong> Rp <?= number_format($data['harga_satuan'], 0, ',', '.'); ?> / <?= htmlspecialchars($data['satuan']); ?></p>
                                                    <p><strong>Terakhir Diperbarui:</strong> <?= $data['updated_at'] ?? $data['created_at']; ?></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">TUTUP</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END MODAL -->

                                    <?php 
                                        $no++;
                                    } 
                                    if(mysqli_num_rows($query_bahan) == 0){
                                        echo '<tr><td colspan="6" class="text-center">Belum ada data bahan baku.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
</body>
</html>