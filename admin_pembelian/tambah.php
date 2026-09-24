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

    $id_bahan_arr     =$_POST['id_bahan'];
    $jumlah_arr       =$_POST['jumlah'];
    $satuan_arr       =$_POST['satuan'];
    $harga_satuan_arr =$_POST['harga_satuan'];

    $grand_total = 0;
    for ($i = 0; $i < count($id_bahan_arr); $i++) {$grand_total += ((float)$jumlah_arr[$i] * (float)$harga_satuan_arr[$i]);
    }

    $q_header = "INSERT INTO pembelian (no_faktur, tgl_pembelian, id_supplier, total_harga) VALUES ('$no_faktur', '$tgl_pembelian', '$id_supplier', '$grand_total')";
    if (mysqli_query($con,$q_header)) {
        $id_pembelian = mysqli_insert_id($con);

      for ($i = 0; $i < count($id_bahan_arr); $i++) {
            $id_bahan     = $id_bahan_arr[$i];
            $jumlah       = (float)$jumlah_arr[$i];
            $harga_satuan = (float)$harga_satuan_arr[$i];
            $subtotal     = $jumlah * $harga_satuan;

            if (!empty($id_bahan)) {
                // 1. Simpan detail pembelian
                $query_cek_detail = "INSERT INTO pembelian_detail (id_pembelian, id_bahan, jumlah, harga_satuan, subtotal) 
                                    VALUES ('$id_pembelian', '$id_bahan', '$jumlah', '$harga_satuan', '$subtotal')";
                mysqli_query($con, $query_cek_detail);

                // Update stok sekaligus perbarui harga_satuan terbaru
                $query_update_stok = "UPDATE bahan_baku SET stok = stok + $jumlah, harga_satuan = '$harga_satuan' WHERE id = '$id_bahan'";
                mysqli_query($con, $query_update_stok);
            }
        }

        header("Location: index.php");
        exit();
    }
}

// Ambil data bahan baku untuk opsi select (menambahkan data-satuan dan data-harga)
$query_bahan = mysqli_query($con, "SELECT * FROM bahan_baku ORDER BY nama_bahan ASC");
$options_bahan = "";
while ($bahan_item = mysqli_fetch_array($query_bahan)) {$satuan_val = htmlspecialchars($bahan_item['satuan'], ENT_QUOTES);$harga_val  = htmlspecialchars($bahan_item['harga_satuan'], ENT_QUOTES);$options_bahan .= "<option value='{$bahan_item['id']}' data-satuan='{$satuan_val}' data-harga='{$harga_val}'>{$bahan_item['nama_bahan']}</option>";
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
                            <form action="" method="POST">
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
                                        <button type="button" id="btn-tambah-bahan" class="btn btn-success waves-effect">
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
                                                <select name="id_bahan[]" class="form-control show-tick select-bahan" required>
                                                    <option value="">-- Pilih Bahan Baku --</option>
                                                    <?= $options_bahan; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-12">
                                            <label>Jumlah</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="number" name="jumlah[]" class="form-control input-jumlah" min="1" step="any" required placeholder="Jumlah">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-12">
                                            <label>Satuan</label>
                                            <div class="form-group">
                                                <select name="satuan[]" class="form-control show-tick select-satuan" required>
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
                                                    <input type="number" name="harga_satuan[]" class="form-control input-harga" min="0" required placeholder="Harga Satuan">
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
                                                <button type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float btn-hapus-baris" style="display:none;">
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
        // 1. Hitung Subtotal secara langsung saat input Jumlah atau Harga berubah
        $(document).on('input', '.input-jumlah, .input-harga', function() {
            var baris = $(this).closest('.baris-bahan');
            var jumlah = parseFloat(baris.find('.input-jumlah').val()) || 0;
            var harga = parseFloat(baris.find('.input-harga').val()) || 0;
            var subtotal = jumlah * harga;

            baris.find('.input-subtotal').val(subtotal.toLocaleString('id-ID'));
        });

        // Auto fill Satuan dan Harga Satuan ketika Bahan Baku dipilih
        $(document).on('change', 'select[name="id_bahan[]"]', function() {
            var selectedOption = $(this).find('option:selected');
            var baris = $(this).closest('.baris-bahan');
            
            var satuan = selectedOption.data('satuan');
            var harga = selectedOption.data('harga');

            if (satuan !== undefined && harga !== undefined) {
                // Isi nilai harga satuan
                baris.find('.input-harga').val(harga);

                var selectSatuan = baris.find('select[name="satuan[]"]');
                var searchSatuan = satuan.toString().trim().toLowerCase();

                // Cari opsi yang persis cocok
                var matchedVal = "";
                selectSatuan.find('option').each(function() {
                    var optVal  = $(this).val().toLowerCase();
                    var optText = $(this).text().toLowerCase();

                    // Cek jika persis sama dengan value atau text (misal "gram" dengan "gram (gram)")
                    if (optVal === searchSatuan || optText === searchSatuan || optText.startsWith(searchSatuan + " ") || optText.includes("(" + searchSatuan + ")")) {
                        matchedVal = $(this).val();
                        return false; // Hentikan loop jika sudah ketemu match pas
                    }
                });

                if (matchedVal) {
                    selectSatuan.val(matchedVal);
                } else {
                    selectSatuan.val("");
                }

                // Refresh Bootstrap Select agar UI terbarui
                if ($.fn.selectpicker) {
                    selectSatuan.selectpicker('refresh');
                }

                // Hitung ulang subtotal
                baris.find('.input-harga').trigger('input');
            }
        });

        // 3. Tambah Baris Bahan Baku
        $('#btn-tambah-bahan').click(function() {
            var optionsBahan = `<?= $options_bahan; ?>`;
            var html = `
            <div class="row clearfix baris-bahan">
                <div class="col-md-3 col-sm-12">
                    <div class="form-group">
                        <select name="id_bahan[]" class="form-control show-tick select-bahan" required>
                            <option value="">-- Pilih Bahan Baku --</option>
                            ` + optionsBahan + `
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="number" name="jumlah[]" class="form-control input-jumlah" min="1" step="any" required placeholder="Jumlah">
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12">
                    <div class="form-group">
                        <select name="satuan[]" class="form-control show-tick select-satuan" required>
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
                            <input type="number" name="harga_satuan[]" class="form-control input-harga" min="0" required placeholder="Harga Satuan">
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
                        <button type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float btn-hapus-baris">
                            <i class="material-icons">delete</i>
                        </button>
                    </div>
                </div>
            </div>`;

            $('#container-bahan').append(html);

            // Inisialisasi ulang Bootstrap Select
            if ($.fn.selectpicker) {
                $('.show-tick').selectpicker('refresh');
            }

            // Tampilkan tombol hapus jika baris lebih dari 1
            if ($('.baris-bahan').length > 1) {$('.baris-bahan').find('.btn-hapus-baris').show();
            }
        });

        // 4. Hapus Baris Bahan Baku
        $(document).on('click', '.btn-hapus-baris', function() {$(this).closest('.baris-bahan').remove();

            // Sembunyikan tombol hapus jika tinggal 1 baris
            if ($('.baris-bahan').length === 1) {$('.baris-bahan').find('.btn-hapus-baris').hide();
            }
        });
    </script>
</body>

</html>