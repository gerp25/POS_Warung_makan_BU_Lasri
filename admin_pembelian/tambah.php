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

if (isset($_POST['simpan_transaksi'])) {
    $no_faktur     =$_POST['no_faktur'];
    $tgl_pembelian =$_POST['tgl_pembelian'];
    $id_supplier   =$_POST['id_supplier'];

    $id_bahan_arr    =$_POST['id_bahan'];
    $jumlah_arr      =$_POST['jumlah'];
    $satuan_arr      =$_POST['satuan'];
    $harga_satuan_arr=$_POST['harga_satuan'];

    // Hitung Total Harga Keseluruhan Transaksi
    $grand_total = 0;
    for ($i = 0; $i < count($id_bahan_arr); $i++) {$grand_total += ($jumlah_arr[$i] * $harga_satuan_arr[$i]);
    }

    // menambah Header Pembelian
    $q_header = "INSERT INTO pembelian (no_faktur, tgl_pembelian, id_supplier, total_harga) VALUES ('$no_faktur', '$tgl_pembelian', '$id_supplier', '$grand_total')";
    if (mysqli_query($con,$q_header)) {
        $id_pembelian = mysqli_insert_id($con);

        // Insert Setiap Item Detail Pembelian
        for ($i = 0; $i < count($id_bahan_arr); $i++) {
            $id_bahan     = $id_bahan_arr[$i];
            $jumlah       = $jumlah_arr[$i];
            $harga_satuan = $harga_satuan_arr[$i];
            $subtotal     = $jumlah * $harga_satuan;

            if (!empty($id_bahan)) {
                $query_cek_detail = "INSERT INTO pembelian_detail (id_pembelian, id_bahan, jumlah, harga_satuan, subtotal) 
                                    VALUES ('$id_pembelian', '$id_bahan', '$jumlah', '$harga_satuan', '$subtotal')";
                mysqli_query($con, $query_cek_detail);
            }
        }

        header("Location: index.php");
        exit();
    }
}

// Ambil data bahan baku untuk template opsi select
$query_bahan = mysqli_query($con, "SELECT * FROM bahan_baku ORDER BY nama_bahan ASC");
$options_bahan = "";
while ($bahan_item = mysqli_fetch_array($query_bahan)) {
    $options_bahan .= "<option value='".$bahan_item['id']."'>".$bahan_item['nama_bahan']."</option>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Input Transaksi Pembelian - Bakul Sega Bu Lastri</title>
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
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="../home_admin/index.php">BAKUL SEGA BU LASTRI - ADMIN</a>
            </div>
        </div>
    </nav>

    <section>
        <?php include '../sidebaradmin.php'; ?>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>INPUT TRANSAKSI PEMBELIAN BARANG</h2>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>FORM TRANSAKSI PEMBELIAN</h2>
                        </div>
                        <div class="body">
                            <form action="tambah.php" method="POST">
                                <div class="row clearfix">
                                    <div class="col-md-4">
                                        <label for="no_faktur">No. Faktur</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="no_faktur" name="no_faktur" class="form-control" value="TRSUP-<?= date('YmdHis'); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="tgl_pembelian">Tanggal Pembelian</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="date" id="tgl_pembelian" name="tgl_pembelian" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="id_supplier">Supplier</label>
                                        <div class="form-group">
                                            <select name="id_supplier" id="id_supplier" class="form-control show-tick" required>
                                                <option value="">-- Pilih Supplier --</option>
                                                <?php
                                                $supplier = mysqli_query($con, "SELECT * FROM supplier ORDER BY nama_supplier ASC");
                                                while ($s = mysqli_fetch_array($supplier)) {
                                                    echo "<option value='".$s['id_supplier']."'>".$s['nama_supplier']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row clearfix">
                                    <div class="col-xs-6">
                                        <h2 class="card-inside-title" style="margin-top:0;">PILIH BAHAN BAKU</h2>
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <button type="button" class="btn btn-success waves-effect" onclick="tambahBarisBahan()">
                                            <i class="material-icons">add</i> <span>Tambah Bahan Baku</span>
                                        </button>
                                    </div>
                                </div>

                                <div id="container-bahan">
                                    <!-- Baris Pertama Input Bahan Baku -->
                                    <div class="row clearfix baris-bahan">
                                        <div class="col-md-3 col-sm-12">
                                            <label>Nama Bahan Baku</label>
                                            <div class="form-group">
                                                <select name="id_bahan[]" class="form-control show-tick" required>
                                                    <option value="">-- Pilih Bahan Baku --</option>
                                                    <?= $options_bahan; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-12">
                                            <label>Jumlah</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="number" name="jumlah[]" class="form-control input-jumlah" min="1" step="any" required placeholder="Jumlah" oninput="hitungSubtotal(this)">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-12">
                                            <label>Satuan</label>
                                            <div class="form-group">
                                                <select name="satuan[]" class="form-control show-tick" required>
                                                    <option value="">-- Pilih Satuan --</option>
                                                    <option value="Kilogram (kg)">Kilogram (kg)</option>
                                                    <option value="Gram (gram)">Gram (gram)</option>
                                                    <option value="Liter (liter)">Liter (liter)</option>
                                                    <option value="Mililiter (ml)">Mililiter (ml)</option>
                                                    <option value="Pcs (pcs)">Pcs (pcs)</option>
                                                    <option value="Bungkus">Bungkus</option>
                                                    <option value="Ikat">Ikat</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-12">
                                            <label>Harga Satuan (Rp)</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="number" name="harga_satuan[]" class="form-control input-harga" min="0" required placeholder="Harga Satuan" oninput="hitungSubtotal(this)">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-10">
                                            <label>Subtotal (Rp)</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control input-subtotal" readonly placeholder="0" style="background-color: #eee;">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-1 col-sm-2 text-center">
                                            <label>&nbsp;</label>
                                            <div class="form-group">
                                                <button type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="hapusBarisBahan(this)" style="display:none;">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" name="simpan_transaksi" class="btn btn-primary m-t-15 waves-effect">Simpan Transaksi</button>
                                <a href="index.php" class="btn btn-default m-t-15 waves-effect">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
        // Template HTML untuk baris bahan baku baru
        var optionsBahanHTML = `<?= $options_bahan; ?>`;

        function tambahBarisBahan() {
            var templateHtml = `
            <div class="row clearfix baris-bahan">
                <div class="col-md-3 col-sm-12">
                    <div class="form-group">
                        <select name="id_bahan[]" class="form-control show-tick" required>
                            <option value="">-- Pilih Bahan Baku --</option>
                            ${optionsBahanHTML}
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="number" name="jumlah[]" class="form-control input-jumlah" min="1" step="any" required placeholder="Jumlah" oninput="hitungSubtotal(this)">
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12">
                    <div class="form-group">
                        <select name="satuan[]" class="form-control show-tick" required>
                            <option value="">-- Pilih Satuan --</option>
                            <option value="Kilogram (kg)">Kilogram (kg)</option>
                            <option value="Gram (gram)">Gram (gram)</option>
                            <option value="Liter (liter)">Liter (liter)</option>
                            <option value="Mililiter (ml)">Mililiter (ml)</option>
                            <option value="Pcs (pcs)">Pcs (pcs)</option>
                            <option value="Bungkus">Bungkus</option>
                            <option value="Ikat">Ikat</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="number" name="harga_satuan[]" class="form-control input-harga" min="0" required placeholder="Harga Satuan" oninput="hitungSubtotal(this)">
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-10">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control input-subtotal" readonly placeholder="0" style="background-color: #eee;">
                        </div>
                    </div>
                </div>
                <div class="col-md-1 col-sm-2 text-center">
                    <div class="form-group">
                        <button type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="hapusBarisBahan(this)">
                            <i class="material-icons">delete</i>
                        </button>
                    </div>
                </div>
            </div>`;

            $('#container-bahan').append(templateHtml);
            
            // Inisialisasi ulang plugin Bootstrap Select untuk elemen dropdown baru
            if ($.fn.selectpicker) {
                $('.show-tick').selectpicker('refresh');
            }
            cekTombolHapus();
        }

        function hapusBarisBahan(btn) {
            $(btn).closest('.baris-bahan').remove();
            cekTombolHapus();
        }

        function cekTombolHapus() {
            var totalBaris = $('.baris-bahan').length;
            if (totalBaris === 1) {
                $('.baris-bahan').find('.btn-danger').hide();
            } else {
                $('.baris-bahan').find('.btn-danger').show();
            }
        }

        function hitungSubtotal(elem) {
            var baris = $(elem).closest('.baris-bahan');
            var jumlah = parseFloat(baris.find('.input-jumlah').value) || parseFloat(baris.find('.input-jumlah').val()) || 0;
            var harga = parseFloat(baris.find('.input-harga').value) || parseFloat(baris.find('.input-harga').val()) || 0;
            var subtotal = jumlah * harga;

            baris.find('.input-subtotal').val(subtotal.toLocaleString('id-ID'));
        }
    </script>
</body>

</html>