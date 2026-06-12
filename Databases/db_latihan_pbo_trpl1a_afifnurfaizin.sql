-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 12, 2026 at 03:10 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_latihan_pbo_trpl1a_afifnurfaizin`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_tiket`
--

CREATE TABLE `tabel_tiket` (
  `id_tiket` int NOT NULL,
  `nama_film` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `jadwal_film` datetime NOT NULL,
  `jadwal_tayang` datetime NOT NULL,
  `jumlah_kursi` int NOT NULL,
  `harga_dasar_tiket` decimal(12,2) NOT NULL,
  `jenis_audio` enum('Reguler','IMAX','Velvet') COLLATE utf8mb4_general_ci NOT NULL,
  `tipe_audio` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lokasi_baris` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kecamata_3d_id` int DEFAULT NULL,
  `efek_gerak_fitur_bantal_selimut_pack` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `layanan_butler` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_tiket`
--

INSERT INTO `tabel_tiket` (`id_tiket`, `nama_film`, `jadwal_film`, `jadwal_tayang`, `jumlah_kursi`, `harga_dasar_tiket`, `jenis_audio`, `tipe_audio`, `lokasi_baris`, `kecamata_3d_id`, `efek_gerak_fitur_bantal_selimut_pack`, `layanan_butler`) VALUES
(1, 'Avengers: Endgame', '2026-06-12 10:00:00', '2026-06-12 10:30:00', 50, 45000.00, 'Reguler', NULL, NULL, NULL, NULL, NULL),
(2, 'Spider-Man: No Way Home', '2026-06-12 13:00:00', '2026-06-12 13:15:00', 60, 45000.00, 'Reguler', NULL, NULL, NULL, NULL, NULL),
(3, 'The Batman', '2026-06-12 16:00:00', '2026-06-12 16:20:00', 55, 50000.00, 'Reguler', NULL, NULL, NULL, NULL, NULL),
(4, 'Dune: Part Two', '2026-06-13 10:00:00', '2026-06-13 10:15:00', 45, 50000.00, 'Reguler', NULL, NULL, NULL, NULL, NULL),
(5, 'Interstellar', '2026-06-13 14:00:00', '2026-06-13 14:30:00', 50, 45000.00, 'Reguler', NULL, NULL, NULL, NULL, NULL),
(6, 'Oppenheimer', '2026-06-13 19:00:00', '2026-06-13 19:15:00', 55, 50000.00, 'Reguler', NULL, NULL, NULL, NULL, NULL),
(7, 'Inside Out 2', '2026-06-14 10:00:00', '2026-06-14 10:10:00', 60, 40000.00, 'Reguler', NULL, NULL, NULL, NULL, NULL),
(8, 'Avatar: The Way of Water', '2026-06-12 11:00:00', '2026-06-12 11:30:00', 40, 85000.00, 'IMAX', 'Dolby Atmos', 'Baris A', 1, NULL, NULL),
(9, 'Godzilla x Kong', '2026-06-12 14:30:00', '2026-06-12 14:45:00', 35, 90000.00, 'IMAX', 'Dolby Atmos', 'Baris B', 2, NULL, NULL),
(10, 'Jurassic World Rebirth', '2026-06-12 18:00:00', '2026-06-12 18:20:00', 38, 85000.00, 'IMAX', 'Dolby Surround', 'Baris C', 3, NULL, NULL),
(11, 'Mission Impossible 8', '2026-06-13 11:00:00', '2026-06-13 11:15:00', 42, 90000.00, 'IMAX', 'Dolby Atmos', 'Baris A', 4, NULL, NULL),
(12, 'Transformers One', '2026-06-13 15:00:00', '2026-06-13 15:10:00', 36, 85000.00, 'IMAX', 'Dolby Surround', 'Baris B', 5, NULL, NULL),
(13, 'Deadpool & Wolverine', '2026-06-14 13:00:00', '2026-06-14 13:15:00', 40, 95000.00, 'IMAX', 'Dolby Atmos', 'Baris A', 6, NULL, NULL),
(14, 'Top Gun: Maverick', '2026-06-14 17:00:00', '2026-06-14 17:20:00', 38, 90000.00, 'IMAX', 'Dolby Surround', 'Baris C', 7, NULL, NULL),
(15, 'The Grand Budapest Hotel', '2026-06-12 19:00:00', '2026-06-12 19:30:00', 20, 150000.00, 'Velvet', 'Dolby Atmos', 'Baris VIP-1', 8, 'Efek gerak 4D, Bantal & Selimut Premium Pack', 'Butler Service Eksklusif'),
(16, 'La La Land', '2026-06-13 19:30:00', '2026-06-13 19:45:00', 18, 160000.00, 'Velvet', 'Dolby Surround', 'Baris VIP-2', 9, 'Efek gerak 4D, Bantal & Selimut Deluxe Pack', 'Butler Service VIP'),
(17, 'Inception', '2026-06-14 20:00:00', '2026-06-14 20:15:00', 22, 150000.00, 'Velvet', 'Dolby Atmos', 'Baris VIP-1', 10, 'Efek gerak 4D, Bantal & Selimut Premium Pack', 'Butler Service Eksklusif'),
(18, 'Parasite', '2026-06-15 19:00:00', '2026-06-15 19:10:00', 20, 155000.00, 'Velvet', 'Dolby Atmos', 'Baris VIP-3', 11, 'Efek gerak Haptic, Bantal & Selimut Luxury Pack', 'Butler Service Premium'),
(19, 'Everything Everywhere', '2026-06-15 21:00:00', '2026-06-15 21:20:00', 16, 170000.00, 'Velvet', 'Dolby Surround', 'Baris VIP-2', 12, 'Efek gerak 4D, Bantal & Selimut Deluxe Pack', 'Butler Service VIP'),
(20, 'John Wick: Chapter 4', '2026-06-16 20:00:00', '2026-06-16 20:30:00', 18, 165000.00, 'Velvet', 'Dolby Atmos', 'Baris VIP-1', 13, 'Efek gerak Haptic, Bantal & Selimut Premium Pack', 'Butler Service Eksklusif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_tiket`
--
ALTER TABLE `tabel_tiket`
  ADD PRIMARY KEY (`id_tiket`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_tiket`
--
ALTER TABLE `tabel_tiket`
  MODIFY `id_tiket` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
