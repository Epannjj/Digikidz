-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2025 at 12:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `presensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `hasil_karya`
--

CREATE TABLE `hasil_karya` (
  `id` varchar(10) NOT NULL,
  `nama` varchar(60) NOT NULL,
  `program` varchar(10) NOT NULL,
  `pertemuan` varchar(12) NOT NULL,
  `materi` varchar(12) NOT NULL,
  `name_foto` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hasil_presensi`
--

CREATE TABLE `hasil_presensi` (
  `id` int(12) NOT NULL,
  `nama` varchar(60) NOT NULL,
  `program` varchar(10) NOT NULL,
  `level` int(1) NOT NULL,
  `pertemuan` int(2) NOT NULL,
  `materi` varchar(20) NOT NULL,
  `tanggal` datetime NOT NULL,
  `hasil_karya` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hasil_presensi`
--

INSERT INTO `hasil_presensi` (`id`, `nama`, `program`, `level`, `pertemuan`, `materi`, `tanggal`, `hasil_karya`) VALUES
(2, 'Budi', 'Coding', 1, 2, 'dasar 1', '2024-12-09 06:34:33', ''),
(3, 'Budi', 'Coding', 1, 3, 'materi tidak ditemuk', '2025-02-10 07:08:53', ''),
(8, 'Budi', 'Coding', 1, 5, 'materi tidak ditemuk', '2024-12-09 04:29:35', '');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` varchar(10) NOT NULL,
  `nama_siswa` varchar(60) NOT NULL,
  `program` varchar(20) NOT NULL,
  `level` int(2) NOT NULL,
  `pertemuan` int(2) NOT NULL,
  `teacher` varchar(60) NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `nama_siswa`, `program`, `level`, `pertemuan`, `teacher`, `tanggal`) VALUES
('1', 'nuel', 'Coding', 1, 1, 'asep', '2024-12-05 16:18:16'),
('2', 'budi', 'Art', 1, 1, 'guru', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `username` varchar(20) NOT NULL,
  `nama` varchar(60) NOT NULL,
  `password` varchar(20) NOT NULL,
  `jabatan` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`username`, `nama`, `password`, `jabatan`) VALUES
('admin', 'epan', 'admin', 'admin'),
('guru', 'rio', 'guru', 'guru'),
('nuel', 'nuel', 'aa', 'teacher');

-- --------------------------------------------------------

--
-- Table structure for table `materi`
--

CREATE TABLE `materi` (
  `id_materi` int(4) NOT NULL,
  `judul_materi` varchar(60) NOT NULL,
  `program` varchar(20) NOT NULL,
  `level` varchar(1) NOT NULL,
  `pertemuan` int(2) NOT NULL,
  `modul` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materi`
--

INSERT INTO `materi` (`id_materi`, `judul_materi`, `program`, `level`, `pertemuan`, `modul`) VALUES
(1, 'Dasar', 'Coding', '1', 1, 'File'),
(2, 'dasar 1', 'Coding', '1', 2, 'File'),
(3, 's', 'Art', '1', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(10) NOT NULL,
  `nama` varchar(60) NOT NULL,
  `program` varchar(10) NOT NULL,
  `level` int(1) NOT NULL,
  `password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama`, `program`, `level`, `password`) VALUES
(1, 'Budi', 'Coding', 1, ''),
(2, 'Rio', 'Robotik', 1, ''),
(3, 'evan', 'Coding', 1, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hasil_presensi`
--
ALTER TABLE `hasil_presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id_materi`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
