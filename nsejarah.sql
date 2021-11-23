-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2021 at 07:56 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nsejarah`
--
CREATE DATABASE IF NOT EXISTS `nsejarah` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `nsejarah`;

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE IF NOT EXISTS `guru` (
  `g_id` int(30) UNSIGNED NOT NULL AUTO_INCREMENT,
  `g_nokp` varchar(30) NOT NULL,
  `g_nama` varchar(255) NOT NULL,
  `g_katalaluan` varchar(255) NOT NULL,
  `g_jenis` enum('admin','guru') DEFAULT 'guru',
  PRIMARY KEY (`g_id`),
  UNIQUE KEY `g_nokp` (`g_nokp`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`g_id`, `g_nokp`, `g_nama`, `g_katalaluan`, `g_jenis`) VALUES
(1, '333333333333', 'Samad', '123', 'guru'),
(2, '444444444444', 'Aidil Admin', '123', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `jawapan`
--

CREATE TABLE IF NOT EXISTS `jawapan` (
  `j_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `j_soalan` int(10) UNSIGNED NOT NULL,
  `j_teks` text NOT NULL,
  PRIMARY KEY (`j_id`),
  KEY `j_soalan` (`j_soalan`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jawapan`
--

INSERT INTO `jawapan` (`j_id`, `j_soalan`, `j_teks`) VALUES
(1, 1, 'Professor Emeritus Dato\' Dr. Nik hassan Shuhaimi Nik Abdul Rahman'),
(2, 1, 'Abdul Hadi Haji Hassan'),
(3, 1, 'Alfred Russel Wallace'),
(4, 1, 'Sultan Takdir Alisjahbana'),
(5, 2, 'Kepulauan Melayu meliputi Tanah Melayu hingga Tenasserim dan kepulauan Nicobar, Filipina dan Kepulauan Solomon hingga Papua New Guinea'),
(6, 2, 'Dunia Melayu merupakan sebuah kawasa yang luas meliputi Malaysia, Indonesia, Brunei, Singapura, selatan Thailand dan Filipina.'),
(7, 2, 'Alam Melayu merupakan suatu lingkungan geografi yang luas meliputi Kepulauan Melayu hingga selatan Thailand'),
(8, 2, 'Alam Melayu meliputi kawasan dari Madagaskar ke Tanah Melayu, Papua New guinea, Australia, New Zealand, Kepulauan Pasifik dan sampai ke Taiwan'),
(9, 3, 'China'),
(10, 3, 'Funan'),
(11, 3, 'Angkor'),
(12, 3, 'Majapahit'),
(13, 4, 'Chu-Lien'),
(14, 4, 'Vyadhapura'),
(15, 4, 'Dapunta Hyang Seri'),
(16, 4, 'Jayavarman II'),
(17, 5, 'Chola'),
(18, 5, 'Dinasti Han'),
(19, 5, 'Empayar Parsi'),
(20, 5, 'Empayar Rom');

-- --------------------------------------------------------

--
-- Table structure for table `jawapan_murid`
--

CREATE TABLE IF NOT EXISTS `jawapan_murid` (
  `jm_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `jm_murid` int(10) UNSIGNED NOT NULL,
  `jm_soalan` int(10) UNSIGNED NOT NULL,
  `jm_jawapan` int(10) UNSIGNED DEFAULT NULL,
  `jm_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`jm_id`),
  KEY `jm_murid` (`jm_murid`),
  KEY `jm_soalan` (`jm_soalan`),
  KEY `jm_jawapan` (`jm_jawapan`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE IF NOT EXISTS `kelas` (
  `k_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `k_nama` varchar(255) NOT NULL,
  PRIMARY KEY (`k_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`k_id`, `k_nama`) VALUES
(1, 'Amanah'),
(2, 'Bestari'),
(3, 'Cemerlang'),
(4, 'Dinamik'),
(5, 'Harmoni');

-- --------------------------------------------------------

--
-- Table structure for table `kelas_tingkatan`
--

CREATE TABLE IF NOT EXISTS `kelas_tingkatan` (
  `kt_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kt_ting` tinyint(1) UNSIGNED NOT NULL,
  `kt_kelas` int(10) UNSIGNED NOT NULL,
  `kt_guru` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`kt_id`),
  KEY `kt_kelas` (`kt_kelas`),
  KEY `kt_guru` (`kt_guru`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kelas_tingkatan`
--

INSERT INTO `kelas_tingkatan` (`kt_id`, `kt_ting`, `kt_kelas`, `kt_guru`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 2),
(3, 1, 2, 1),
(4, 2, 2, 2),
(5, 1, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `kuiz`
--

CREATE TABLE IF NOT EXISTS `kuiz` (
  `kz_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kz_nama` varchar(255) NOT NULL,
  `kz_guru` int(10) UNSIGNED NOT NULL,
  `kz_ting` int(10) UNSIGNED DEFAULT NULL,
  `kz_tarikh` date NOT NULL,
  `kz_jenis` enum('kuiz','latihan') DEFAULT 'kuiz',
  `kz_masa` int(5) DEFAULT NULL,
  PRIMARY KEY (`kz_id`),
  KEY `kz_guru` (`kz_guru`),
  KEY `kz_ting` (`kz_ting`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kuiz`
--

INSERT INTO `kuiz` (`kz_id`, `kz_nama`, `kz_guru`, `kz_ting`, `kz_tarikh`, `kz_jenis`, `kz_masa`) VALUES
(1, 'Bab 1.1: Konsep Alam Melayu', 1, 1, '2025-04-02', 'latihan', NULL),
(2, 'Bab 1.3: Kerajaan Alam Melayu yang Masyhur', 1, 1, '2021-06-16', 'kuiz', 12),
(3, 'Bab 1.4: Kerajaan Luar Sezaman', 2, 2, '2021-11-15', 'latihan', NULL),
(6, 'Bab 2.1: Sistem pemerintahan', 2, 2, '2021-11-15', 'latihan', NULL),
(7, 'Bab 2.2: Kegiatan Ekonomi', 2, 2, '2021-11-15', 'latihan', NULL),
(8, 'Bab 3.1: Sistem dan Tulisan', 2, 2, '2021-11-15', 'latihan', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `murid`
--

CREATE TABLE IF NOT EXISTS `murid` (
  `m_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `m_nokp` varchar(30) NOT NULL,
  `m_nama` varchar(255) NOT NULL,
  `m_katalaluan` varchar(255) NOT NULL,
  `m_kelas` int(30) UNSIGNED NOT NULL,
  PRIMARY KEY (`m_id`,`m_nokp`),
  UNIQUE KEY `m_nokp` (`m_nokp`),
  KEY `m_kelas` (`m_kelas`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `murid`
--

INSERT INTO `murid` (`m_id`, `m_nokp`, `m_nama`, `m_katalaluan`, `m_kelas`) VALUES
(1, '111111111111', 'Aidil', '123', 1),
(2, '222222222222', 'Sudin', '123', 2),
(3, '333333333333', 'John', '123', 1);

-- --------------------------------------------------------

--
-- Table structure for table `skor_murid`
--

CREATE TABLE IF NOT EXISTS `skor_murid` (
  `sm_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sm_murid` int(10) UNSIGNED NOT NULL,
  `sm_kuiz` int(10) UNSIGNED NOT NULL,
  `sm_skor` double(10,2) NOT NULL,
  PRIMARY KEY (`sm_id`),
  KEY `sm_murid` (`sm_murid`),
  KEY `sm_kuiz` (`sm_kuiz`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `soalan`
--

CREATE TABLE IF NOT EXISTS `soalan` (
  `s_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `s_kuiz` int(10) UNSIGNED NOT NULL,
  `s_teks` text NOT NULL,
  `s_gambar` text DEFAULT NULL,
  PRIMARY KEY (`s_id`),
  KEY `s_kuiz` (`s_kuiz`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `soalan`
--

INSERT INTO `soalan` (`s_id`, `s_kuiz`, `s_teks`, `s_gambar`) VALUES
(1, 1, 'Apakah nama tokoh ini?', 'tokoh1.PNG'),
(2, 1, 'Apakah pendapat Alfred Russel Wallace terhadap kedudukan geografi Alam Melayu?', NULL),
(3, 2, 'Manakah antara berikut bukan kerajaan alam melayu yang masyhur', NULL),
(4, 2, 'Siapakah pengasas kerajaan Champa?', NULL),
(5, 3, 'Berikut merupakan kerajaan luar yang sezaman dengan kerajaan Funan, kecuali ', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `soalan_jawapan`
--

CREATE TABLE IF NOT EXISTS `soalan_jawapan` (
  `sj_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sj_soalan` int(10) UNSIGNED NOT NULL,
  `sj_jawapan` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`sj_id`),
  KEY `sj_soalan` (`sj_soalan`),
  KEY `sj_jawapan` (`sj_jawapan`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `soalan_jawapan`
--

INSERT INTO `soalan_jawapan` (`sj_id`, `sj_soalan`, `sj_jawapan`) VALUES
(1, 1, 1),
(2, 2, 5),
(3, 3, 9),
(4, 4, 13),
(5, 5, 17);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jawapan`
--
ALTER TABLE `jawapan`
  ADD CONSTRAINT `jawapan_ibfk_1` FOREIGN KEY (`j_soalan`) REFERENCES `soalan` (`s_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jawapan_murid`
--
ALTER TABLE `jawapan_murid`
  ADD CONSTRAINT `jawapan_murid_ibfk_1` FOREIGN KEY (`jm_murid`) REFERENCES `murid` (`m_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jawapan_murid_ibfk_2` FOREIGN KEY (`jm_soalan`) REFERENCES `soalan` (`s_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jawapan_murid_ibfk_3` FOREIGN KEY (`jm_jawapan`) REFERENCES `jawapan` (`j_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelas_tingkatan`
--
ALTER TABLE `kelas_tingkatan`
  ADD CONSTRAINT `kelas_tingkatan_ibfk_1` FOREIGN KEY (`kt_kelas`) REFERENCES `kelas` (`k_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kelas_tingkatan_ibfk_2` FOREIGN KEY (`kt_guru`) REFERENCES `guru` (`g_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kuiz`
--
ALTER TABLE `kuiz`
  ADD CONSTRAINT `kuiz_ibfk_1` FOREIGN KEY (`kz_guru`) REFERENCES `guru` (`g_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kuiz_ibfk_2` FOREIGN KEY (`kz_ting`) REFERENCES `kelas_tingkatan` (`kt_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `murid`
--
ALTER TABLE `murid`
  ADD CONSTRAINT `murid_ibfk_1` FOREIGN KEY (`m_kelas`) REFERENCES `kelas_tingkatan` (`kt_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `skor_murid`
--
ALTER TABLE `skor_murid`
  ADD CONSTRAINT `skor_murid_ibfk_1` FOREIGN KEY (`sm_murid`) REFERENCES `murid` (`m_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `skor_murid_ibfk_2` FOREIGN KEY (`sm_kuiz`) REFERENCES `kuiz` (`kz_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `soalan`
--
ALTER TABLE `soalan`
  ADD CONSTRAINT `soalan_ibfk_1` FOREIGN KEY (`s_kuiz`) REFERENCES `kuiz` (`kz_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `soalan_jawapan`
--
ALTER TABLE `soalan_jawapan`
  ADD CONSTRAINT `soalan_jawapan_ibfk_1` FOREIGN KEY (`sj_soalan`) REFERENCES `soalan` (`s_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `soalan_jawapan_ibfk_2` FOREIGN KEY (`sj_jawapan`) REFERENCES `jawapan` (`j_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
