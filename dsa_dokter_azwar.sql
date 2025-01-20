-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 20, 2025 at 12:49 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dsa_dokter_azwar`
--

-- --------------------------------------------------------

--
-- Table structure for table `ds_kunci_tb`
--

CREATE TABLE `ds_kunci_tb` (
  `id_kunci` varchar(50) NOT NULL,
  `panjang_kunci` int NOT NULL,
  `kode_kunci` varchar(255) NOT NULL,
  `kunci_privat` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `kunci_publik` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ds_kunci_tb`
--

INSERT INTO `ds_kunci_tb` (`id_kunci`, `panjang_kunci`, `kode_kunci`, `kunci_privat`, `kunci_publik`, `created_at`, `updated_at`) VALUES
('KY-e2c1a1-LAN', 2048, '001 - A', '\r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\rEfo2Qg6q3tYPp6gRELtmElr79EQEwF6O3cHieQ==\r\r', '2025-01-20 12:38:43', '2025-01-20 12:38:43');

-- --------------------------------------------------------

--
-- Table structure for table `ds_laporan_pasien_tb`
--

CREATE TABLE `ds_laporan_pasien_tb` (
  `id_laporan_pasien` int NOT NULL,
  `id_pasien` varchar(50) NOT NULL,
  `keluhan` text NOT NULL,
  `saran` text NOT NULL,
  `tanda_tangan` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ds_laporan_pasien_tb`
--

INSERT INTO `ds_laporan_pasien_tb` (`id_laporan_pasien`, `id_pasien`, `keluhan`, `saran`, `tanda_tangan`, `created_at`, `updated_at`) VALUES
(2, 'PSN-2cc2f383RZDPKS', 'Sakit Maag', 'Minum Promag', 'ttd_PRW-58571b87OGFBCJ_KY-e2c1a1-LAN.sig', '2025-01-20 12:45:06', '2025-01-20 12:45:06');

-- --------------------------------------------------------

--
-- Table structure for table `ds_pengguna_tb`
--

CREATE TABLE `ds_pengguna_tb` (
  `id_pengguna` varchar(50) NOT NULL,
  `nama_lengkap` longtext,
  `alamat` longtext,
  `foto_profil` text,
  `hak_akses` text,
  `nip` longtext,
  `username` varchar(255) DEFAULT NULL,
  `password` text,
  `tanda_tangan` text,
  `id_kunci` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ds_pengguna_tb`
--

INSERT INTO `ds_pengguna_tb` (`id_pengguna`, `nama_lengkap`, `alamat`, `foto_profil`, `hak_akses`, `nip`, `username`, `password`, `tanda_tangan`, `id_kunci`, `created_at`, `updated_at`) VALUES
('PRW-58571b87OGFBCJ', 'khalis', 'Barru', '678e44dfe792a.jpg', 'Perawat', 'PRW-2001968AGO', 'khalis', '$2y$10$y/H1aeEhgadaufSwmzu1ke3PRV7LeKjO4gBr08Jf5gnTNHj/KzouG', 'ttd_PRW-58571b87OGFBCJ_KY-e2c1a1-LAN.sig', 'KY-e2c1a1-LAN', '2025-01-20 12:43:12', '2025-01-20 12:43:12'),
('PSN-2cc2f383RZDPKS', 'Nur Afifah', 'Enrekang', NULL, 'Pasien', NULL, 'Silfa', '$2y$10$.YVI/0F2bmhL5Bbn6Iw.K.d7v/AePbGF7OMF13bbPeFo8Z3ju5n6q', NULL, NULL, '2025-01-20 12:44:13', '2025-01-20 12:44:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ds_kunci_tb`
--
ALTER TABLE `ds_kunci_tb`
  ADD PRIMARY KEY (`id_kunci`);

--
-- Indexes for table `ds_laporan_pasien_tb`
--
ALTER TABLE `ds_laporan_pasien_tb`
  ADD PRIMARY KEY (`id_laporan_pasien`),
  ADD KEY `id_pasien` (`id_pasien`);

--
-- Indexes for table `ds_pengguna_tb`
--
ALTER TABLE `ds_pengguna_tb`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_kunci` (`id_kunci`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ds_laporan_pasien_tb`
--
ALTER TABLE `ds_laporan_pasien_tb`
  MODIFY `id_laporan_pasien` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ds_laporan_pasien_tb`
--
ALTER TABLE `ds_laporan_pasien_tb`
  ADD CONSTRAINT `ds_laporan_pasien_tb_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `ds_pengguna_tb` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ds_pengguna_tb`
--
ALTER TABLE `ds_pengguna_tb`
  ADD CONSTRAINT `ds_pengguna_tb_ibfk_1` FOREIGN KEY (`id_kunci`) REFERENCES `ds_kunci_tb` (`id_kunci`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
