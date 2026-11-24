-- ============================================================
-- DATABASE SCHEMA: WarungKu
-- POS & Inventaris Warung UMKM
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `tbltransactiondetail`;
DROP TABLE IF EXISTS `tbltransaction`;
DROP TABLE IF EXISTS `tblrestock`;
DROP TABLE IF EXISTS `tblproduct`;
DROP TABLE IF EXISTS `tblcategory`;
DROP TABLE IF EXISTS `tbluser`;
DROP TABLE IF EXISTS `tblstore`;
SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------
-- 1. Table structure for table `tblstore`
-- ------------------------------------------------------------
CREATE TABLE `tblstore` (
  `StoreID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreName` varchar(100) NOT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`StoreID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tblstore`
INSERT INTO `tblstore` (`StoreID`, `StoreName`, `Address`, `CreatedAt`) VALUES
(1, 'Warung Berkah', 'Jl. Merdeka No.10', '2026-11-16 16:10:27');

-- ------------------------------------------------------------
-- 2. Table structure for table `tbluser`
-- ------------------------------------------------------------
CREATE TABLE `tbluser` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Fullname` varchar(100) NOT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `Role` enum('owner','kasir') NOT NULL DEFAULT 'kasir',
  `IsActive` tinyint(1) NOT NULL DEFAULT 1,
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DeletedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `uq_user_store_username` (`StoreID`, `Username`),
  KEY `fk_user_store` (`StoreID`),
  CONSTRAINT `fk_user_store` FOREIGN KEY (`StoreID`) REFERENCES `tblstore` (`StoreID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbluser`
INSERT INTO `tbluser` (`UserID`, `StoreID`, `Username`, `Email`, `Password`, `Fullname`, `PhoneNumber`, `Role`, `IsActive`, `CreatedAt`, `DeletedAt`) VALUES
(1, 1, 'Zhafran', 'zhafran@mail.com', '$2y$10$cCaR4usSXKoVjm.r0Lm.duMlP35mZ/xP8BHUu7sSLZcZ.k/ZXkCLe', 'Zhafran An-Nawawi', '081234567890', 'owner', 1, '2026-11-16 16:10:27', NULL),
(2, 1, 'Nawa', 'nawa@gmail.com', '$2y$10$t.XxO6I4NpyqWEFKzOpi8O24xB2VGEBVw/y9M1dNTbUQ7eXrBxNbS', 'Nawawi', '0812345678', 'kasir', 1, '2026-11-23 11:16:33', NULL);

-- ------------------------------------------------------------
-- 3. Table structure for table `tblcategory`
-- ------------------------------------------------------------
CREATE TABLE `tblcategory` (
  `CategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreID` int(11) NOT NULL,
  `CategoryName` varchar(50) NOT NULL,
  `IsActive` tinyint(1) NOT NULL DEFAULT 1,
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DeletedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`CategoryID`),
  KEY `idx_category_store` (`StoreID`, `DeletedAt`, `IsActive`),
  CONSTRAINT `fk_category_store` FOREIGN KEY (`StoreID`) REFERENCES `tblstore` (`StoreID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tblcategory`
INSERT INTO `tblcategory` (`CategoryID`, `StoreID`, `CategoryName`, `IsActive`, `CreatedAt`, `DeletedAt`) VALUES
(1, 1, 'Sembako', 1, '2026-11-16 16:10:27', NULL);

-- ------------------------------------------------------------
-- 4. Table structure for table `tblproduct`
-- ------------------------------------------------------------
CREATE TABLE `tblproduct` (
  `ProductID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreID` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `ProductName` varchar(100) NOT NULL,
  `Unit` varchar(20) NOT NULL DEFAULT 'pcs',
  `PurchasePrice` decimal(12,2) NOT NULL DEFAULT 0.00,
  `SellingPrice` decimal(12,2) NOT NULL DEFAULT 0.00,
  `Stock` int(11) NOT NULL DEFAULT 0,
  `MinimumStock` int(11) NOT NULL DEFAULT 0,
  `Location` varchar(50) DEFAULT NULL,
  `IsActive` tinyint(1) NOT NULL DEFAULT 1,
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DeletedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`ProductID`),
  KEY `idx_product_store` (`StoreID`, `DeletedAt`, `IsActive`),
  KEY `idx_product_category` (`CategoryID`),
  CONSTRAINT `fk_product_store` FOREIGN KEY (`StoreID`) REFERENCES `tblstore` (`StoreID`) ON DELETE CASCADE,
  CONSTRAINT `fk_product_category` FOREIGN KEY (`CategoryID`) REFERENCES `tblcategory` (`CategoryID`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tblproduct`
INSERT INTO `tblproduct` (`ProductID`, `StoreID`, `CategoryID`, `ProductName`, `Unit`, `PurchasePrice`, `SellingPrice`, `Stock`, `MinimumStock`, `Location`, `IsActive`, `CreatedAt`, `DeletedAt`) VALUES
(1, 1, 1, 'Beras', 'karung', 20000.00, 200000.00, 4, 5, 'Rak A1, Etalase Depan', 1, '2026-11-23 00:00:00', NULL);

-- ------------------------------------------------------------
-- 5. Table structure for table `tblrestock`
-- ------------------------------------------------------------
CREATE TABLE `tblrestock` (
  `RestockID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `PurchasePrice` decimal(12,2) NOT NULL,
  `RestockDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Note` text DEFAULT NULL,
  PRIMARY KEY (`RestockID`),
  KEY `idx_restock_store` (`StoreID`),
  KEY `idx_restock_product` (`ProductID`),
  KEY `idx_restock_user` (`UserID`),
  CONSTRAINT `fk_restock_store` FOREIGN KEY (`StoreID`) REFERENCES `tblstore` (`StoreID`) ON DELETE CASCADE,
  CONSTRAINT `fk_restock_product` FOREIGN KEY (`ProductID`) REFERENCES `tblproduct` (`ProductID`) ON DELETE CASCADE,
  CONSTRAINT `fk_restock_user` FOREIGN KEY (`UserID`) REFERENCES `tbluser` (`UserID`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 6. Table structure for table `tbltransaction`
-- ------------------------------------------------------------
CREATE TABLE `tbltransaction` (
  `TransactionID` int(11) NOT NULL AUTO_INCREMENT,
  `InvoiceNumber` varchar(30) NOT NULL,
  `StoreID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TransactionDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `TotalAmount` decimal(12,2) NOT NULL,
  `AmountPaid` decimal(12,2) NOT NULL,
  `ChangeAmount` decimal(12,2) NOT NULL,
  `PaymentMethod` enum('cash','qris') NOT NULL DEFAULT 'cash',
  PRIMARY KEY (`TransactionID`),
  UNIQUE KEY `uq_transaction_invoice` (`InvoiceNumber`),
  KEY `idx_trx_store_date` (`StoreID`, `TransactionDate`),
  KEY `idx_trx_user` (`UserID`),
  CONSTRAINT `fk_trx_store` FOREIGN KEY (`StoreID`) REFERENCES `tblstore` (`StoreID`) ON DELETE CASCADE,
  CONSTRAINT `fk_trx_user` FOREIGN KEY (`UserID`) REFERENCES `tbluser` (`UserID`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 7. Table structure for table `tbltransactiondetail`
-- ------------------------------------------------------------
CREATE TABLE `tbltransactiondetail` (
  `TransactionDetailID` int(11) NOT NULL AUTO_INCREMENT,
  `TransactionID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `SellingPriceAtTransaction` decimal(12,2) NOT NULL,
  `PurchasePriceAtTransaction` decimal(12,2) NOT NULL,
  `Subtotal` decimal(12,2) NOT NULL,
  PRIMARY KEY (`TransactionDetailID`),
  KEY `idx_trxd_trx` (`TransactionID`),
  KEY `idx_trxd_product` (`ProductID`),
  CONSTRAINT `fk_trxd_trx` FOREIGN KEY (`TransactionID`) REFERENCES `tbltransaction` (`TransactionID`) ON DELETE CASCADE,
  CONSTRAINT `fk_trxd_product` FOREIGN KEY (`ProductID`) REFERENCES `tblproduct` (`ProductID`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
