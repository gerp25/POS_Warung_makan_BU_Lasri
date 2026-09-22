-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Sep 2026 pada 10.09
-- Versi server: 11.8.1-MariaDB
-- Versi PHP: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `resto`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bahan_baku`
--

CREATE TABLE `bahan_baku` (
  `id` bigint(20) NOT NULL,
  `nama_bahan` varchar(150) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `satuan` varchar(20) NOT NULL,
  `harga_satuan` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bahan_baku`
--

INSERT INTO `bahan_baku` (`id`, `nama_bahan`, `stok`, `satuan`, `harga_satuan`, `created_at`, `updated_at`) VALUES
(1, 'Beras', 100, 'kg', '16500.00', '2026-08-31 02:54:32', '2026-09-22 04:33:50'),
(2, 'Daging Ayam', 10, 'kg', '43000.00', '2026-08-31 02:54:32', '2026-09-22 03:39:01'),
(3, 'Telur Ayam', 100, 'butir', '2000.00', '2026-08-31 02:54:32', '2026-09-22 04:21:46'),
(4, 'Ikan Tongkol', 5000, 'gram', '40.00', '2026-08-31 02:54:32', '2026-09-22 04:34:34'),
(5, 'Tempe', 2000, 'gram', '12.00', '2026-08-31 02:54:32', '2026-09-22 04:36:31'),
(6, 'Tahu', 1000, 'gram', '12.00', '2026-08-31 02:54:32', '2026-09-22 04:36:38'),
(7, 'Kentang', 10, 'kg', '16000.00', '2026-08-31 02:54:32', '2026-09-22 04:04:09'),
(8, 'Terong', 1000, 'gram', '12.00', '2026-08-31 02:54:32', '2026-09-22 04:36:26'),
(9, 'Nangka Muda (Gudeg)', 2000, 'gram', '12.50', '2026-08-31 02:54:32', '2026-09-22 04:36:15'),
(10, 'Mie Basah/Kering', 5000, 'gram', '18.50', '2026-08-31 02:54:32', '2026-09-22 04:35:51'),
(11, 'Bawang Merah', 1000, 'gram', '37.50', '2026-08-31 04:55:22', '2026-09-22 04:30:44'),
(12, 'Bawang Putih', 1000, 'gram', '39.50', '2026-08-31 04:55:22', '2026-09-22 04:31:07'),
(13, 'Cabe Merah Besar', 1000, 'gram', '58.00', '2026-08-31 04:55:22', '2026-09-22 04:31:44'),
(14, 'Cabe Rawit Merah', 1000, 'gram', '95.00', '2026-08-31 04:55:22', '2026-09-22 04:31:57'),
(15, 'Kemiri', 5000, 'gram', '44.00', '2026-08-31 04:55:22', '2026-09-22 04:35:11'),
(16, 'Lengkuas', 2000, 'gram', '12.00', '2026-08-31 04:55:22', '2026-09-22 04:35:19'),
(17, 'Jahe ', 1000, 'gram', '25.00', '2026-08-31 04:55:22', '2026-09-22 04:34:43'),
(18, 'Serai', 3000, 'gram', '12.00', '2026-08-31 04:55:22', '2026-09-22 04:36:43'),
(19, 'Daun Salam', 5, 'seikat', '9000.00', '2026-08-31 04:55:22', '2026-09-22 03:41:04'),
(20, 'Minyak Goreng', 50, 'liter', '140000.00', '2026-08-31 04:55:22', '2026-09-22 04:08:15'),
(21, 'Garam', 1000, 'gram', '23.00', '2026-08-31 04:55:22', '2026-09-22 04:34:07'),
(22, 'Gula Merah', 1000, 'gram', '25.00', '2026-08-31 04:55:22', '2026-09-22 04:34:22'),
(23, 'Santan Kelapa', 5000, 'gram', '35.00', '2026-08-31 04:55:22', '2026-09-22 04:36:51'),
(24, 'Kecap Manis', 5, 'botol', '32000.00', '2026-08-31 04:55:22', '2026-09-22 04:35:04'),
(28, 'Mie Bihun', 1000, 'gram', '37.15', '2026-09-22 06:48:06', '2026-09-22 06:48:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_menu`
--

CREATE TABLE `kategori_menu` (
  `id` bigint(20) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori_menu`
--

INSERT INTO `kategori_menu` (`id`, `nama_kategori`, `deskripsi`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'Nasi Olahan / Paket', 'Menu utama berbasis nasi paket', NULL, '2026-08-31 02:54:31', '2026-08-31 02:54:31'),
(2, 'Lauk & Sayur', 'Pilihan lauk dan sayuran ramesan', NULL, '2026-08-31 02:54:31', '2026-08-31 02:54:31'),
(3, 'Pesanan Khusus', 'Pesanan khusus jumlah besar (Nasbox, Tumpeng, dll)', NULL, '2026-08-31 02:54:31', '2026-08-31 02:54:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id` bigint(20) NOT NULL,
  `kategori_id` bigint(20) NOT NULL,
  `nama_menu` varchar(150) NOT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('tersedia','habis') DEFAULT 'tersedia',
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id`, `kategori_id`, `nama_menu`, `harga`, `deskripsi`, `foto`, `status`, `gambar`, `created_at`, `updated_at`) VALUES
(30, 1, 'Nasi Rames', '7000.00', 'Nasi putih hangat disajikan dengan aneka lauk gurih khas rumahan. Dilengkapi setengah butir telur bacem, tongkol balado kemangi, bihun goreng, soun tumis, dan orek tempe manis gurih. Porsi kenyang dan pas untuk makan siang!', NULL, 'tersedia', '1790047319_6ab1f457075c2.png', '2026-09-22 03:21:59', '2026-09-22 07:06:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int(11) NOT NULL,
  `no_faktur` varchar(50) NOT NULL,
  `tgl_pembelian` date NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `total_harga` decimal(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembelian`
--

INSERT INTO `pembelian` (`id_pembelian`, `no_faktur`, `tgl_pembelian`, `id_supplier`, `total_harga`) VALUES
(1, 'TRSUP-20260922144542', '2026-09-22', 1, '1650000.00'),
(2, 'TRSUP-20260922144542', '2026-09-22', 1, '1650000.00'),
(3, 'TRSUP-20260922144542', '2026-09-22', 1, '1650000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelian_detail`
--

CREATE TABLE `pembelian_detail` (
  `id_detail` int(11) NOT NULL,
  `id_pembelian` int(11) NOT NULL,
  `id_bahan` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembelian_detail`
--

INSERT INTO `pembelian_detail` (`id_detail`, `id_pembelian`, `id_bahan`, `jumlah`, `harga_satuan`, `subtotal`) VALUES
(1, 3, 1, 100, '16500.00', '1650000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `resep_detail`
--

CREATE TABLE `resep_detail` (
  `id` bigint(20) NOT NULL,
  `menu_id` bigint(20) NOT NULL,
  `bahan_baku_id` bigint(20) NOT NULL,
  `jumlah_butuh` decimal(10,2) NOT NULL,
  `satuan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `resep_detail`
--

INSERT INTO `resep_detail` (`id`, `menu_id`, `bahan_baku_id`, `jumlah_butuh`, `satuan`) VALUES
(26, 30, 1, '1.00', 'kg'),
(27, 30, 3, '10.00', 'butir'),
(28, 30, 4, '200.00', 'gram'),
(29, 30, 28, '700.00', 'gram'),
(30, 30, 5, '250.00', 'gram'),
(31, 30, 13, '10.00', 'gram'),
(32, 30, 19, '0.50', 'seikat'),
(33, 30, 21, '5.00', 'gram');

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `alamat`, `no_telp`) VALUES
(1, 'Pasar', 'Pasar', '088899993333');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_user`
--

CREATE TABLE `tbl_user` (
  `username` varchar(13) NOT NULL,
  `sandi` varchar(100) NOT NULL,
  `peran` enum('K','A') NOT NULL,
  `pin` char(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_user`
--

INSERT INTO `tbl_user` (`username`, `sandi`, `peran`, `pin`) VALUES
('admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'A', '111111'),
('kasir', '8691e4fc53b99da544ce86e22acba62d13352eff', 'K', '222222');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bahan_baku`
--
ALTER TABLE `bahan_baku`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori_menu`
--
ALTER TABLE `kategori_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu_kategori` (`kategori_id`);

--
-- Indeks untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indeks untuk tabel `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pembelian` (`id_pembelian`);

--
-- Indeks untuk tabel `resep_detail`
--
ALTER TABLE `resep_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_resep_menu` (`menu_id`),
  ADD KEY `fk_resep_bahan` (`bahan_baku_id`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indeks untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bahan_baku`
--
ALTER TABLE `bahan_baku`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `kategori_menu`
--
ALTER TABLE `kategori_menu`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `resep_detail`
--
ALTER TABLE `resep_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_menu` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  ADD CONSTRAINT `pembelian_detail_ibfk_1` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `resep_detail`
--
ALTER TABLE `resep_detail`
  ADD CONSTRAINT `fk_resep_bahan` FOREIGN KEY (`bahan_baku_id`) REFERENCES `bahan_baku` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_resep_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
