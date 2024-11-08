-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 12, 2024 at 01:25 PM
-- Server version: 5.7.33
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pengiriman`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` varchar(7) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `del_no` varchar(15) NOT NULL,
  `harga` int(100) DEFAULT NULL,
  `id_kategori` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama`, `satuan`, `del_no`, `harga`, `id_kategori`) VALUES
('BRG0001', 'BRAKE SHOE HONDA ASP UPDATE', 'SATUAN 1', 'null', 100000, 'KTG01'),
('BRG0002', 'BRAKE SHOE KHARISMA', 'SATUAN 1', 'Box', 100000, 'KTG02'),
('BRG0003', 'BRAKE SHOE SUPRA FED', 'SATUAN 1', 'Box', 100000, 'KTG01'),
('BRG0004', 'BRAKE SHOE YAMAHA ASP', 'SATUAN 1', 'Box', 100000, 'KTG01'),
('BRG0005', 'PAD SET HONDA BLADE - ASP', 'SATUAN 1', 'Box', 100000, 'KTG01'),
('BRG0006', 'PAD SET HONDA SUPRA X 125 - AS', 'SATUAN 1', 'BOX', 100000, 'KTG01'),
('BRG0007', 'PAD SET SUPRA FED', 'SATUAN 1', 'Box', 100000, 'KTG01'),
('BRG0008', 'PAD SET SUPRA X 125 - ASP', 'SATUAN 1', 'Box', 100000, 'KTG01'),
('BRG0009', 'MANTUL', 'SATUAN 1', '231', 250000, 'KTG01'),
('BRG0010', 'TESTING CREATE', 'SATUAN 1', 'null', 100000, 'KTG01'),
('BRG0011', 'TESTING CREATE TERBARU BARU', 'SATUAN 1', 'null', 1000000, 'KTG01');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pengiriman`
--

CREATE TABLE `detail_pengiriman` (
  `id_detail` int(4) NOT NULL,
  `id_pengiriman` varchar(100) NOT NULL,
  `id_barang` varchar(7) NOT NULL,
  `qty` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_pengiriman`
--

INSERT INTO `detail_pengiriman` (`id_detail`, `id_pengiriman`, `id_barang`, `qty`) VALUES
(207, 'KRM20240130010', 'BRG0004', 1),
(208, 'KRM20240130010', 'BRG0007', 1),
(209, 'KRM20240130010', 'BRG0002', 1),
(210, 'KRM20240130010', 'BRG0006', 1),
(211, 'KRM20240130010', 'BRG0003', 1),
(285, 'KRM20240130002', 'BRG0004', 222222),
(286, 'KRM20240130002', 'BRG0005', 1),
(287, 'KRM20240623012', 'BRG0010', 1),
(288, 'KRM20240623013', 'BRG0001', 1),
(289, 'KRM20240623014', 'BRG0004', 1),
(290, 'KRM20240623015', 'BRG0001', 1),
(291, 'KRM20240623016', 'BRG0002', 1),
(292, 'KRM20240623017', 'BRG0001', 1),
(293, 'KRM20240623018', 'BRG0005', 1),
(294, 'KRM20240130003', 'BRG0004', 1),
(295, 'KRM20240130003', 'BRG0006', 1),
(296, 'KRM20240130004', 'BRG0009', 1),
(297, 'KRM20240130007', 'BRG0001', 1),
(298, 'KRM20240130007', 'BRG0004', 1),
(299, 'KRM20240130007', 'BRG0008', 1),
(303, 'KRM20240130009', 'BRG0001', 1),
(304, 'KRM20240130009', 'BRG0004', 1),
(305, 'KRM20240130009', 'BRG0009', 1),
(306, 'KRM20240130009', 'BRG0007', 1),
(307, 'KRM20240130005', 'BRG0005', 1),
(308, 'KRM20240130005', 'BRG0006', 1),
(309, 'KRM20240130005', 'BRG0007', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_keberangkatan` varchar(14) NOT NULL,
  `tanggal` date NOT NULL,
  `id_mobil` varchar(10) NOT NULL,
  `id_kurir` varchar(5) NOT NULL,
  `id_rate` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id_keberangkatan`, `tanggal`, `id_mobil`, `id_kurir`, `id_rate`) VALUES
('JDL20240130001', '2024-01-30', 'MBL02', 'KRR03', 'RTE0001'),
('JDL20240130002', '2024-01-30', 'MBL01', 'KRR06', 'RTE0001'),
('JDL20240130003', '2024-01-30', 'MBL01', 'KRR07', 'RTE0002');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` varchar(5) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `keterangan` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama`, `keterangan`) VALUES
('KTG01', 'KATEGORI 1', 'KETERANGAN'),
('KTG02', 'KATEGORI 2', 'KATEGORI 2'),
('KTG03', 'KATEGORI 3', 'KATEGORI 3');

-- --------------------------------------------------------

--
-- Table structure for table `kurir`
--

CREATE TABLE `kurir` (
  `id_kurir` varchar(5) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `jenis_kelamin` varchar(10) NOT NULL,
  `telepon` varchar(15) NOT NULL,
  `alamat` varchar(150) DEFAULT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kurir`
--

INSERT INTO `kurir` (`id_kurir`, `nama`, `jenis_kelamin`, `telepon`, `alamat`, `password`) VALUES
('KRR01', 'EKO ', 'Laki-Laki', '081385195955', 'TANGERANG', 'ee9cc68e583241dcb548e4217d8c8eb9'),
('KRR02', 'ERIK', 'Laki-Laki', '081284959589', 'TANGERANG', '6faae43d506a230beedcdbff231b478e'),
('KRR03', 'TRIBUDI', 'Laki-Laki', '081219900381', 'TANGERANG', 'b4ae1f68447e3df8a1ce6c4dc3660c5b'),
('KRR04', 'SUMANTA', 'Laki-Laki', '081382630321', 'TANGERANG', 'af7ece06ca8c285657e6a8860e58c44f'),
('KRR05', 'UDRI', 'Laki-Laki', '081210426881', 'TANGERANG', 'a82ae164e11127090055c6c7fbb6a888'),
('KRR06', 'SAEPUL', 'Laki-Laki', '081314485383', 'TANGERANG', '1cdb001697052dcdf055da6b82124bc3'),
('KRR07', 'yanto', 'Laki-Laki', '081284213311', 'Gandul, 16512', '81dc9bdb52d04dc20036dbd8313ed055'),
('KRR08', 'SUJONO', 'Laki-Laki', '0812345678', 'Jonggol, West Java', '81dc9bdb52d04dc20036dbd8313ed055'),
('KRR09', 'ADIT', 'Laki-Laki', '13192038120', 'Banjarbaru', '407339954babda0c4ab38ceef81c0bda');

-- --------------------------------------------------------

--
-- Table structure for table `mobil`
--

CREATE TABLE `mobil` (
  `id_mobil` varchar(15) NOT NULL,
  `plat` varchar(30) NOT NULL,
  `jenis` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mobil`
--

INSERT INTO `mobil` (`id_mobil`, `plat`, `jenis`) VALUES
('MBL01', 'BF012', 'Truck'),
('MBL02', 'TY534', 'Pick-up');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` varchar(7) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `telepon` varchar(15) NOT NULL,
  `alamat` varchar(150) NOT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `telepon`, `alamat`, `email`) VALUES
('CST0001', 'ASTRA OTOPART', '021-4603550', 'jakarta', 'yudistirarivaldii1234@gmail.com'),
('CST0002', 'Idemitsu Lube Indonesia', '021-8911 4611', 'JL Permata Raya, Kawasan Industri KIIC, Lot BB/4A, Karawang, Jawa Barat,', 'yudistirarivaldii1234@gmail.com'),
('CST0003', 'Federal Karyatama', '021-4613583', 'Jl. Pulobuaran Raya, RW.9, Jatinegara, Cakung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13910', 'yudistirarivaldii1234@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `pengiriman`
--

CREATE TABLE `pengiriman` (
  `id_pengiriman` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `id_pelanggan` varchar(7) NOT NULL,
  `id_kurir` varchar(5) NOT NULL,
  `id_rate` varchar(50) DEFAULT NULL,
  `no_kendaraan` varchar(8) DEFAULT NULL,
  `keterangan` varchar(150) DEFAULT NULL,
  `penerima` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pengiriman`
--

INSERT INTO `pengiriman` (`id_pengiriman`, `tanggal`, `id_pelanggan`, `id_kurir`, `id_rate`, `no_kendaraan`, `keterangan`, `penerima`, `status`) VALUES
('KRM20240130002', '2024-01-30', 'CST0002', 'KRR01', 'RTE0002', NULL, '', 'MANTEPPPP', 2),
('KRM20240130003', '2024-01-30', 'CST0001', 'KRR05', 'RTE0001', NULL, '', 'awdawd', 2),
('KRM20240130004', '2024-01-30', 'CST0001', 'KRR04', 'RTE0001', NULL, '', 'mantep', 2),
('KRM20240130005', '2024-01-30', 'CST0003', 'KRR04', 'RTE0002', NULL, '', 'adwaw', 2),
('KRM20240130007', '2024-01-30', 'CST0003', 'KRR02', 'RTE0002', NULL, '', 'mantep', 2),
('KRM20240130009', '2024-01-30', 'CST0003', 'KRR01', 'RTE0002', NULL, '', NULL, 1),
('KRM20240130010', '2024-01-30', 'CST0001', 'KRR04', 'RTE0001', NULL, NULL, NULL, 1),
('KRM20240623012', '2024-06-23', 'CST0001', 'KRR04', 'RTE0002', NULL, '', NULL, 1),
('KRM20240623013', '2024-06-23', 'CST0002', 'KRR01', 'RTE0002', NULL, '', NULL, 1),
('KRM20240623014', '2024-06-23', 'CST0001', 'KRR05', 'RTE0002', NULL, '', NULL, 1),
('KRM20240623015', '2024-06-23', 'CST0001', 'KRR01', 'RTE0002', NULL, '', NULL, 1),
('KRM20240623016', '2024-06-23', 'CST0001', 'KRR04', 'RTE0001', NULL, '', NULL, 1),
('KRM20240623017', '2024-06-23', 'CST0002', 'KRR01', 'RTE0002', NULL, '', NULL, 1),
('KRM20240623018', '2024-06-23', 'CST0001', 'KRR05', 'RTE0002', NULL, '', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `performa`
--

CREATE TABLE `performa` (
  `id_performa` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `id_mobil` varchar(10) NOT NULL,
  `keterangan` varchar(10) DEFAULT NULL,
  `kondisi` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `performa`
--

INSERT INTO `performa` (`id_performa`, `tanggal`, `id_mobil`, `keterangan`, `kondisi`) VALUES
('PRFRM20240812001', '2024-08-12', 'MBL01', 'awdawd', '2');

-- --------------------------------------------------------

--
-- Table structure for table `rate`
--

CREATE TABLE `rate` (
  `id_rate` varchar(7) NOT NULL,
  `rate` varchar(30) NOT NULL,
  `dari` varchar(10) NOT NULL,
  `wilayah` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rate`
--

INSERT INTO `rate` (`id_rate`, `rate`, `dari`, `wilayah`) VALUES
('RTE0001', '1', '20:54', 'Bajarmasin'),
('RTE0002', '2', '15:30', 'Tagalog');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` varchar(5) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `level` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `level`) VALUES
('USR01', 'acs', '1a1dc91c907325c69271ddf0c944bc72', 1),
('USR02', 'koordinator', '1a1dc91c907325c69271ddf0c944bc72', 2),
('USR03', 'planner', '1a1dc91c907325c69271ddf0c944bc72', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `detail_pengiriman`
--
ALTER TABLE `detail_pengiriman`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `delete_constraint` (`id_pengiriman`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_keberangkatan`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `kurir`
--
ALTER TABLE `kurir`
  ADD PRIMARY KEY (`id_kurir`);

--
-- Indexes for table `mobil`
--
ALTER TABLE `mobil`
  ADD PRIMARY KEY (`id_mobil`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD PRIMARY KEY (`id_pengiriman`);

--
-- Indexes for table `performa`
--
ALTER TABLE `performa`
  ADD PRIMARY KEY (`id_performa`) USING BTREE;

--
-- Indexes for table `rate`
--
ALTER TABLE `rate`
  ADD PRIMARY KEY (`id_rate`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pengiriman`
--
ALTER TABLE `detail_pengiriman`
  MODIFY `id_detail` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=310;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pengiriman`
--
ALTER TABLE `detail_pengiriman`
  ADD CONSTRAINT `delete_constraint` FOREIGN KEY (`id_pengiriman`) REFERENCES `pengiriman` (`id_pengiriman`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
