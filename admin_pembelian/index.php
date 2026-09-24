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

// ini adalah proses update data dari modal edit
if (isset($_POST['update_transaksi'])) {
    $id_pembelian  = $_POST['id_pembelian'];
    $no_faktur     = $_POST['no_faktur'];
    $tgl_pembelian = $_POST['tgl_pembelian'];
    $id_supplier   = $_POST['id_supplier'];

    $query_update = "UPDATE pembelian SET 
                        no_faktur = '$no_faktur', 
                        tgl_pembelian = '$tgl_pembelian', 
                        id_supplier = '$id_supplier' 
                     WHERE id_pembelian = '$id_pembelian'";

    if (mysqli_query($con, $query_update)) {
        echo "<script>alert('Data transaksi berhasil diperbarui!'); window.location.href='index.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal memperbarui data: " . mysqli_error($con) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Data Pembelian - Bakul Sega Bu Lastri</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="../AdminBSB/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Waves Effect Css -->
    <link href="../AdminBSB/plugins/node-waves/waves.css" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="../AdminBSB/plugins/animate-css/animate.css" rel="stylesheet" />
    <!-- Bootstrap Select Css -->
    <link href="../AdminBSB/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <!-- Custom Css -->
    <link href="../AdminBSB/css/style.css" rel="stylesheet">
    <!-- AdminBSB Themes -->
    <link href="../AdminBSB/css/themes/all-themes.css" rel="stylesheet" />
</head>

<body class="theme-red">
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="../home_admin/index.php">BAKUL SEGA BU LASTRI - ADMIN</a>
            </div>
        </div>
    </nav>

    <!-- Left Sidebar -->
    <section>
        <?php include '../sidebaradmin.php'; ?>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>RIWAYAT PEMBELIAN BARANG</h2>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <a href="tambah.php" class="btn btn-primary waves-effect">+ Transaksi Pembelian Baru</a>
                        </div>
                        <div class="body table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;" class="text-center">No</th>
                                        <th>No. Faktur</th>
                                        <th>Tanggal</th>
                                        <th>Supplier</th>
                                        <th>Total Harga</th>
                                        <th style="width: 180px;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $query = "SELECT pembelian.*, supplier.nama_supplier FROM pembelian 
                                              JOIN supplier ON pembelian.id_supplier = supplier.id_supplier 
                                              ORDER BY pembelian.id_pembelian DESC";
                                    $data = mysqli_query($con, $query);

                                    // Simpan hasil query ke array untuk pemrosesan modal di luar tabel
                                    $transaksi_list = array();

                                    if ($data && mysqli_num_rows($data) > 0) {
                                        while ($d = mysqli_fetch_array($data)) {
                                            $transaksi_list[] = $d; // Simpan data untuk render modal
                                            $id_pembelian = $d['id_pembelian'];
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($d['no_faktur']); ?></td>
                                        <td><?= $d['tgl_pembelian']; ?></td>
                                        <td><?= htmlspecialchars($d['nama_supplier']); ?></td>
                                        <td>Rp <?= number_format($d['total_harga'], 0, ',', '.'); ?></td>
                                        <td class="text-center">
                                            <!-- Tombol Detail -->
                                            <button type="button" class="btn btn-info btn-xs waves-effect" data-toggle="modal" data-target="#modalDetail<?= $id_pembelian; ?>" title="Detail Transaksi">
                                                <i class="material-icons">visibility</i>
                                            </button>

                                            <!-- Tombol Edit -->
                                            <button type="button" 
                                                    class="btn btn-warning btn-xs waves-effect btn-edit" 
                                                    data-id="<?= $id_pembelian; ?>"
                                                    data-faktur="<?= htmlspecialchars($d['no_faktur']); ?>"
                                                    data-tgl="<?= $d['tgl_pembelian']; ?>"
                                                    data-supplier="<?= $d['id_supplier']; ?>"
                                                    title="Edit">
                                                <i class="material-icons">edit</i>
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <a href="hapus.php?id=<?= $id_pembelian; ?>" class="btn btn-danger btn-xs waves-effect" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');" title="Hapus">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center'>Belum ada transaksi pembelian.</td></tr>";
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

    <!-- CONTAINER UNTUK SEMUA MODAL DETAIL (DITARUH DI LUAR TABEL) -->
    <?php
    if (!empty($transaksi_list)) {
        foreach ($transaksi_list as $d) {
            $id_pembelian = $d['id_pembelian'];
    ?>
    <div class="modal fade" id="modalDetail<?= $id_pembelian; ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">DETAIL TRANSAKSI PEMBELIAN</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless" style="margin-bottom: 15px;">
                        <tr>
                            <th style="width: 150px;">No. Faktur</th>
                            <td style="width: 10px;">:</td>
                            <td><?= htmlspecialchars($d['no_faktur']); ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Pembelian</th>
                            <td>:</td>
                            <td><?= $d['tgl_pembelian']; ?></td>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <td>:</td>
                            <td><?= htmlspecialchars($d['nama_supplier']); ?></td>
                        </tr>
                    </table>

                    <h5 style="margin-bottom: 10px; font-weight: bold;">Rincian Bahan Baku:</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 40px;">No</th>
                                    <th>Nama Bahan Baku</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Satuan</th>
                                    <th class="text-right">Harga Satuan</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no_detail = 1;
                                $query_detail = mysqli_query($con, "SELECT pembelian_detail.*, bahan_baku.nama_bahan 
                                    FROM pembelian_detail 
                                    JOIN bahan_baku ON pembelian_detail.id_bahan = bahan_baku.id 
                                    WHERE pembelian_detail.id_pembelian = '$id_pembelian'");
                                if ($query_detail && mysqli_num_rows($query_detail) > 0) {
                                    while ($dt = mysqli_fetch_array($query_detail)) {
                                        $satuan = !empty($dt['satuan']) ? $dt['satuan'] : '-';
                                ?>
                                <tr>
                                    <td class="text-center"><?= $no_detail++; ?></td>
                                    <td><?= htmlspecialchars($dt['nama_bahan']); ?></td>
                                    <td class="text-center"><?= $dt['jumlah']; ?></td>
                                    <td class="text-center"><?= htmlspecialchars($satuan); ?></td>
                                    <td class="text-right">Rp <?= number_format($dt['harga_satuan'], 0, ',', '.'); ?></td>
                                    <td class="text-right">Rp <?= number_format($dt['subtotal'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>Tidak ada rincian bahan baku.</td></tr>";
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Total Transaksi:</th>
                                    <th class="text-right">Rp <?= number_format($d['total_harga'], 0, ',', '.'); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                   <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <?php 
        }
    } 
    ?>

    <!-- Modal Edit Transaksi Pembelian -->
    <div class="modal fade" id="modalEditPembelian" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">EDIT TRANSAKSI PEMBELIAN</h4>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id_pembelian" id="edit_id_pembelian">
                        
                        <div class="form-group">
                            <label for="edit_no_faktur">No. Faktur</label>
                            <div class="form-line">
                                <input type="text" id="edit_no_faktur" name="no_faktur" class="form-control" required readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_tgl_pembelian">Tanggal Pembelian</label>
                            <div class="form-line">
                                <input type="date" id="edit_tgl_pembelian" name="tgl_pembelian" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_id_supplier">Supplier</label>
                            <select name="id_supplier" id="edit_id_supplier" class="form-control show-tick" required>
                                <option value="">-- Pilih Supplier --</option>
                                <?php
                                $supplier_query = mysqli_query($con, "SELECT * FROM supplier ORDER BY nama_supplier ASC");
                                while ($s = mysqli_fetch_array($supplier_query)) {
                                    echo "<option value='".$s['id_supplier']."'>".$s['nama_supplier']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="update_transaksi" class="btn btn-primary waves-effect">Simpan Perubahan</button>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="../AdminBSB/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap Core Js -->
    <script src="../AdminBSB/plugins/bootstrap/js/bootstrap.js"></script>
    <!-- Select Plugin Js -->
    <script src="../AdminBSB/plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <!-- Slimscroll Plugin Js -->
    <script src="../AdminBSB/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="../AdminBSB/plugins/node-waves/waves.js"></script>
    <!-- Custom Js -->
    <script src="../AdminBSB/js/admin.js"></script>

    <script>
        $(document).ready(function() {
            // Ketika tombol Edit diklik
            $('.btn-edit').on('click', function() {
                var id = $(this).data('id');
                var faktur = $(this).data('faktur');
                var tgl = $(this).data('tgl');
                var supplier = $(this).data('supplier');

                $('#edit_id_pembelian').val(id);
                $('#edit_no_faktur').val(faktur);
                $('#edit_tgl_pembelian').val(tgl);
                $('#edit_id_supplier').val(supplier);

                if ($.fn.selectpicker) {
                    $('#edit_id_supplier').selectpicker('refresh');
                }

                $('#modalEditPembelian').modal('show');
            });
        });
    </script>
</body>

</html>