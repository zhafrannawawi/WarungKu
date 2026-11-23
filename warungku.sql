-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2026 at 02:27 AM
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
-- Database: `warungku`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `CategoryID` int(11) NOT NULL,
  `StoreID` int(11) NOT NULL,
  `CategoryName` varchar(50) NOT NULL,
  `IsActive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct`
--

CREATE TABLE `tblproduct` (
  `ProductID` int(11) NOT NULL,
  `StoreID` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `ProductName` varchar(100) NOT NULL,
  `Unit` varchar(20) NOT NULL,
  `PurchasePrice` decimal(12,2) NOT NULL,
  `SellingPrice` decimal(12,2) NOT NULL,
  `Stock` int(11) NOT NULL DEFAULT 0,
  `MinimumStock` int(11) NOT NULL DEFAULT 0,
  `Location` varchar(50) DEFAULT NULL,
  `Note` text DEFAULT NULL,
  `EntryDate` date NOT NULL,
  `IsActive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblrestock`
--

CREATE TABLE `tblrestock` (
  `RestockID` int(11) NOT NULL,
  `StoreID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `PurchasePrice` decimal(12,2) NOT NULL,
  `RestockDate` datetime NOT NULL DEFAULT current_timestamp(),
  `Note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblstore`
--

CREATE TABLE `tblstore` (
  `StoreID` int(11) NOT NULL,
  `StoreName` varchar(100) NOT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblstore`
--

INSERT INTO `tblstore` (`StoreID`, `StoreName`, `Address`, `CreatedAt`) VALUES
(1, 'Warung Berkah', 'Jl. Merdeka No.10', '2026-11-16 16:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `tbltransaction`
--

CREATE TABLE `tbltransaction` (
  `TransactionID` int(11) NOT NULL,
  `StoreID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TransactionDate` datetime NOT NULL DEFAULT current_timestamp(),
  `TotalAmount` decimal(12,2) NOT NULL,
  `AmountPaid` decimal(12,2) NOT NULL,
  `ChangeAmount` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbltransactiondetail`
--

CREATE TABLE `tbltransactiondetail` (
  `TransactionDetailID` int(11) NOT NULL,
  `TransactionID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `SellingPriceAtTransaction` decimal(12,2) NOT NULL,
  `PurchasePriceAtTransaction` decimal(12,2) NOT NULL,
  `Subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `UserID` int(11) NOT NULL,
  `StoreID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Fullname` varchar(100) NOT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `Role` varchar(10) NOT NULL,
  `IsActive` tinyint(1) NOT NULL DEFAULT 1,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`UserID`, `StoreID`, `Username`, `Email`, `Password`, `Fullname`, `PhoneNumber`, `Role`, `IsActive`, `CreatedAt`) VALUES
(4, 1, 'Zhafran', 'zhafran@mail.com', '$2y$10$cCaR4usSXKoVjm.r0Lm.duMlP35mZ/xP8BHUu7sSLZcZ.k/ZXkCLe', 'Zhafran An-Nawawi', '081234567890', 'owner', 1, '2026-11-16 16:10:27'),
(5, 1, 'iqbaldez', '', NULL, 'Iqbal', '0812345678', 'kasir', 1, '2026-11-16 16:11:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`CategoryID`),
  ADD UNIQUE KEY `uq_category_store_name` (`StoreID`,`CategoryName`);

--
-- Indexes for table `tblproduct`
--
ALTER TABLE `tblproduct`
  ADD PRIMARY KEY (`ProductID`),
  ADD UNIQUE KEY `uq_product_store_name` (`StoreID`,`ProductName`),
  ADD KEY `fk_product_category` (`CategoryID`);

--
-- Indexes for table `tblrestock`
--
ALTER TABLE `tblrestock`
  ADD PRIMARY KEY (`RestockID`),
  ADD KEY `fk_restock_store` (`StoreID`),
  ADD KEY `fk_restock_product` (`ProductID`),
  ADD KEY `fk_restock_user` (`UserID`);

--
-- Indexes for table `tblstore`
--
ALTER TABLE `tblstore`
  ADD PRIMARY KEY (`StoreID`);

--
-- Indexes for table `tbltransaction`
--
ALTER TABLE `tbltransaction`
  ADD PRIMARY KEY (`TransactionID`),
  ADD KEY `fk_trx_store` (`StoreID`),
  ADD KEY `fk_trx_user` (`UserID`);

--
-- Indexes for table `tbltransactiondetail`
--
ALTER TABLE `tbltransactiondetail`
  ADD PRIMARY KEY (`TransactionDetailID`),
  ADD UNIQUE KEY `uq_trxd_trx_product` (`TransactionID`,`ProductID`),
  ADD KEY `fk_trxd_product` (`ProductID`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `uq_user_store_username` (`StoreID`,`Username`),
  ADD KEY `fk_user_store` (`StoreID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblproduct`
--
ALTER TABLE `tblproduct`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblrestock`
--
ALTER TABLE `tblrestock`
  MODIFY `RestockID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblstore`
--
ALTER TABLE `tblstore`
  MODIFY `StoreID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbltransaction`
--
ALTER TABLE `tbltransaction`
  MODIFY `TransactionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbltransactiondetail`
--
ALTER TABLE `tbltransactiondetail`
  MODIFY `TransactionDetailID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD CONSTRAINT `fk_category_store` FOREIGN KEY (`StoreID`) REFERENCES `tblstore` (`StoreID`);

--
-- Constraints for table `tblproduct`
--
ALTER TABLE `tblproduct`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`CategoryID`) REFERENCES `tblcategory` (`CategoryID`),
  ADD CONSTRAINT `fk_product_store` FOREIGN KEY (`StoreID`) REFERENCES `tblstore` (`StoreID`);

--
-- Constraints for table `tblrestock`
--
ALTER TABLE `tblrestock`
  ADD CONSTRAINT `fk_restock_product` FOREIGN KEY (`ProductID`) REFERENCES `tblproduct` (`ProductID`),
  ADD CONSTRAINT `fk_restock_store` FOREIGN KEY (`StoreID`) REFERENCES `tblstore` (`StoreID`),
  ADD CONSTRAINT `fk_restock_user` FOREIGN KEY (`UserID`) REFERENCES `tbluser` (`UserID`);

--
-- Constraints for table `tbltransaction`
--
ALTER TABLE `tbltransaction`
  ADD CONSTRAINT `fk_trx_store` FOREIGN KEY (`StoreID`) REFERENCES `tblstore` (`StoreID`),
  ADD CONSTRAINT `fk_trx_user` FOREIGN KEY (`UserID`) REFERENCES `tbluser` (`UserID`);

--
-- Constraints for table `tbltransactiondetail`
--
ALTER TABLE `tbltransactiondetail`
  ADD CONSTRAINT `fk_trxd_product` FOREIGN KEY (`ProductID`) REFERENCES `tblproduct` (`ProductID`),
  ADD CONSTRAINT `fk_trxd_trx` FOREIGN KEY (`TransactionID`) REFERENCES `tbltransaction` (`TransactionID`);

--
-- Constraints for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD CONSTRAINT `fk_user_store` FOREIGN KEY (`StoreID`) REFERENCES `tblstore` (`StoreID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
