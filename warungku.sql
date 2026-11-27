-- ============================================================
-- DATABASE SCHEMA & DATA DUMMY: WarungKu
-- POS & Inventaris Warung UMKM (VARCHAR Prefix IDs)
-- ============================================================

SET NAMES utf8mb4;
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
  `StoreID` varchar(25) NOT NULL,
  `StoreName` varchar(100) NOT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`StoreID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tblstore` (`StoreID`, `StoreName`, `Address`, `CreatedAt`) VALUES
('STR-001', 'Warung Berkah', 'Jl. Merdeka No.10', DATE_SUB(NOW(), INTERVAL 14 DAY));

-- ------------------------------------------------------------
-- 2. Table structure for table `tbluser`
-- ------------------------------------------------------------
CREATE TABLE `tbluser` (
  `UserID` varchar(25) NOT NULL,
  `StoreID` varchar(25) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Fullname` varchar(100) NOT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `Role` enum('owner','kasir') NOT NULL DEFAULT 'kasir',
  `IsActive` tinyint(1) NOT NULL DEFAULT 0,
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DeletedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `uq_user_store_username` (`StoreID`, `Username`),
  KEY `fk_user_store` (`StoreID`),
  CONSTRAINT `fk_user_store` FOREIGN KEY (`StoreID`) REFERENCES `tblstore` (`StoreID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Akun dummy:
-- Owner : Zhafran (password asli)
-- Kasir : Nawa (password asli), siti (password: 123456), rizky (login pertama pakai No. HP: 085678901234)
INSERT INTO `tbluser` (`UserID`, `StoreID`, `Username`, `Email`, `Password`, `Fullname`, `PhoneNumber`, `Role`, `IsActive`, `CreatedAt`, `DeletedAt`) VALUES
('USR-001', 'STR-001', 'Zhafran', 'zhafran@mail.com', '$2y$10$cCaR4usSXKoVjm.r0Lm.duMlP35mZ/xP8BHUu7sSLZcZ.k/ZXkCLe', 'Zhafran An-Nawawi', '081234567890', 'owner', 0, DATE_SUB(NOW(), INTERVAL 14 DAY), NULL),
('USR-002', 'STR-001', 'Nawa', 'nawa@gmail.com', '$2y$10$t.XxO6I4NpyqWEFKzOpi8O24xB2VGEBVw/y9M1dNTbUQ7eXrBxNbS', 'Nawawi', '0812345678', 'kasir', 0, DATE_SUB(NOW(), INTERVAL 10 DAY), NULL),
('USR-003', 'STR-001', 'siti', 'siti@warungberkah.id', '$2y$10$RjGd01ron5x0W80QOx5kOu6L8R0bdyMactmoR9E6smM8GJwN76Gia', 'Siti Aminah', '081345678901', 'kasir', 0, DATE_SUB(NOW(), INTERVAL 7 DAY), NULL),
('USR-004', 'STR-001', 'rizky', 'rizky@warungberkah.id', '085678901234', 'Rizky Pratama', '085678901234', 'kasir', 0, DATE_SUB(NOW(), INTERVAL 3 DAY), NULL);

-- ------------------------------------------------------------
-- 3. Table structure for table `tblcategory`
-- ------------------------------------------------------------
CREATE TABLE `tblcategory` (
  `CategoryID` varchar(25) NOT NULL,
  `StoreID` varchar(25) NOT NULL,
  `CategoryName` varchar(50) NOT NULL,
  `IsActive` tinyint(1) NOT NULL DEFAULT 1,
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DeletedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`CategoryID`),
  KEY `idx_category_store` (`StoreID`, `DeletedAt`, `IsActive`),
  CONSTRAINT `fk_category_store` FOREIGN KEY (`StoreID`) REFERENCES `tblstore` (`StoreID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tblcategory` (`CategoryID`, `StoreID`, `CategoryName`, `IsActive`, `CreatedAt`, `DeletedAt`) VALUES
('CAT-001', 'STR-001', 'Sembako', 1, DATE_SUB(NOW(), INTERVAL 14 DAY), NULL),
('CAT-002', 'STR-001', 'Minuman', 1, DATE_SUB(NOW(), INTERVAL 14 DAY), NULL),
('CAT-003', 'STR-001', 'Makanan Ringan', 1, DATE_SUB(NOW(), INTERVAL 14 DAY), NULL),
('CAT-004', 'STR-001', 'Kebutuhan Rumah', 1, DATE_SUB(NOW(), INTERVAL 12 DAY), NULL),
('CAT-005', 'STR-001', 'Bumbu Dapur', 1, DATE_SUB(NOW(), INTERVAL 12 DAY), NULL);

-- ------------------------------------------------------------
-- 4. Table structure for table `tblproduct`
-- ------------------------------------------------------------
CREATE TABLE `tblproduct` (
  `ProductID` varchar(25) NOT NULL,
  `StoreID` varchar(25) NOT NULL,
  `CategoryID` varchar(25) NOT NULL,
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

-- 12 produk dummy (3 stok menipis & 1 stok habis untuk demo notifikasi + pagination)
INSERT INTO `tblproduct` (`ProductID`, `StoreID`, `CategoryID`, `ProductName`, `Unit`, `PurchasePrice`, `SellingPrice`, `Stock`, `MinimumStock`, `Location`, `IsActive`, `CreatedAt`, `DeletedAt`) VALUES
('PRD-001', 'STR-001', 'CAT-001', 'Beras Ramos 5kg', 'sak', 62000.00, 70000.00, 4, 5, 'Rak A-01', 1, DATE_SUB(NOW(), INTERVAL 14 DAY), NULL),
('PRD-002', 'STR-001', 'CAT-001', 'Minyak Goreng 1L', 'pouch', 15000.00, 17500.00, 28, 10, 'Rak A-02', 1, DATE_SUB(NOW(), INTERVAL 14 DAY), NULL),
('PRD-003', 'STR-001', 'CAT-001', 'Gula Pasir 1kg', 'kg', 15500.00, 18000.00, 22, 8, 'Rak A-03', 1, DATE_SUB(NOW(), INTERVAL 14 DAY), NULL),
('PRD-004', 'STR-001', 'CAT-001', 'Telur Ayam 1kg', 'kg', 26000.00, 29000.00, 3, 5, 'Rak A-04', 1, DATE_SUB(NOW(), INTERVAL 12 DAY), NULL),
('PRD-005', 'STR-001', 'CAT-002', 'Air Mineral 600ml', 'botol', 2500.00, 4000.00, 48, 15, 'Kulkas 1', 1, DATE_SUB(NOW(), INTERVAL 12 DAY), NULL),
('PRD-006', 'STR-001', 'CAT-002', 'Teh Botol 350ml', 'botol', 3500.00, 5000.00, 36, 12, 'Kulkas 1', 1, DATE_SUB(NOW(), INTERVAL 12 DAY), NULL),
('PRD-007', 'STR-001', 'CAT-002', 'Kopi Sachet Renteng', 'pcs', 1500.00, 2500.00, 60, 20, 'Etalase 1', 1, DATE_SUB(NOW(), INTERVAL 10 DAY), NULL),
('PRD-008', 'STR-001', 'CAT-003', 'Mie Instan Goreng', 'bks', 2800.00, 3500.00, 55, 15, 'Rak B-01', 1, DATE_SUB(NOW(), INTERVAL 10 DAY), NULL),
('PRD-009', 'STR-001', 'CAT-003', 'Keripik Singkong', 'bks', 7000.00, 10000.00, 2, 5, 'Rak B-02', 1, DATE_SUB(NOW(), INTERVAL 10 DAY), NULL),
('PRD-010', 'STR-001', 'CAT-004', 'Sabun Cuci Piring', 'pouch', 9500.00, 12000.00, 18, 6, 'Rak C-01', 1, DATE_SUB(NOW(), INTERVAL 9 DAY), NULL),
('PRD-011', 'STR-001', 'CAT-004', 'Deterjen Bubuk 800g', 'bks', 17000.00, 20000.00, 0, 5, 'Rak C-02', 1, DATE_SUB(NOW(), INTERVAL 9 DAY), NULL),
('PRD-012', 'STR-001', 'CAT-005', 'Kecap Manis 275ml', 'botol', 12000.00, 15000.00, 16, 5, 'Rak D-01', 1, DATE_SUB(NOW(), INTERVAL 9 DAY), NULL);

-- ------------------------------------------------------------
-- 5. Table structure for table `tblrestock`
-- ------------------------------------------------------------
CREATE TABLE `tblrestock` (
  `RestockID` varchar(25) NOT NULL,
  `StoreID` varchar(25) NOT NULL,
  `ProductID` varchar(25) NOT NULL,
  `UserID` varchar(25) NOT NULL,
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

INSERT INTO `tblrestock` (`RestockID`, `StoreID`, `ProductID`, `UserID`, `Quantity`, `PurchasePrice`, `RestockDate`, `Note`) VALUES
('RCK-20260001', 'STR-001', 'PRD-002', 'USR-001', 20, 15000.00, DATE_SUB(NOW(), INTERVAL 2 HOUR), 'Kulakan Agen Sembako Makmur'),
('RCK-20260002', 'STR-001', 'PRD-005', 'USR-001', 24, 2500.00, DATE_SUB(NOW(), INTERVAL 4 HOUR), 'Stok kulkas 1 dus'),
('RCK-20260003', 'STR-001', 'PRD-008', 'USR-001', 40, 2800.00, DATE_SUB(NOW(), INTERVAL 1 DAY), 'Restock 1 kardus mie goreng'),
('RCK-20260004', 'STR-001', 'PRD-003', 'USR-001', 15, 15500.00, DATE_SUB(NOW(), INTERVAL 2 DAY), 'Pengiriman distributor gula'),
('RCK-20260005', 'STR-001', 'PRD-006', 'USR-001', 24, 3500.00, DATE_SUB(NOW(), INTERVAL 3 DAY), 'Stok minuman dingin'),
('RCK-20260006', 'STR-001', 'PRD-012', 'USR-001', 12, 12000.00, DATE_SUB(NOW(), INTERVAL 4 DAY), 'Belanja pasar grosir');

-- ------------------------------------------------------------
-- 6. Table structure for table `tbltransaction`
-- ------------------------------------------------------------
CREATE TABLE `tbltransaction` (
  `TransactionID` varchar(25) NOT NULL,
  `StoreID` varchar(25) NOT NULL,
  `UserID` varchar(25) NOT NULL,
  `TransactionDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `TotalAmount` decimal(12,2) NOT NULL,
  `AmountPaid` decimal(12,2) NOT NULL,
  `ChangeAmount` decimal(12,2) NOT NULL,
  `PaymentMethod` enum('cash','qris') NOT NULL DEFAULT 'cash',
  PRIMARY KEY (`TransactionID`),
  KEY `idx_trx_store_date` (`StoreID`, `TransactionDate`),
  KEY `idx_trx_user` (`UserID`),
  CONSTRAINT `fk_trx_store` FOREIGN KEY (`StoreID`) REFERENCES `tblstore` (`StoreID`) ON DELETE CASCADE,
  CONSTRAINT `fk_trx_user` FOREIGN KEY (`UserID`) REFERENCES `tbluser` (`UserID`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12 transaksi dummy (5 hari ini + 7 hari-hari terakhir, campuran cash & qris)
INSERT INTO `tbltransaction` (`TransactionID`, `StoreID`, `UserID`, `TransactionDate`, `TotalAmount`, `AmountPaid`, `ChangeAmount`, `PaymentMethod`) VALUES
('TRX-20260001', 'STR-001', 'USR-002', DATE_SUB(NOW(), INTERVAL 15 MINUTE), 105000.00, 110000.00, 5000.00, 'cash'),
('TRX-20260002', 'STR-001', 'USR-003', DATE_SUB(NOW(), INTERVAL 40 MINUTE), 26000.00, 26000.00, 0.00, 'qris'),
('TRX-20260003', 'STR-001', 'USR-002', DATE_SUB(NOW(), INTERVAL 1 HOUR), 65000.00, 70000.00, 5000.00, 'cash'),
('TRX-20260004', 'STR-001', 'USR-001', DATE_SUB(NOW(), INTERVAL 2 HOUR), 45000.00, 45000.00, 0.00, 'qris'),
('TRX-20260005', 'STR-001', 'USR-003', DATE_SUB(NOW(), INTERVAL 3 HOUR), 24500.00, 25000.00, 500.00, 'cash'),
('TRX-20260006', 'STR-001', 'USR-002', DATE_SUB(NOW(), INTERVAL 1 DAY), 87500.00, 100000.00, 12500.00, 'cash'),
('TRX-20260007', 'STR-001', 'USR-003', DATE_SUB(NOW(), INTERVAL 1 DAY), 65000.00, 65000.00, 0.00, 'qris'),
('TRX-20260008', 'STR-001', 'USR-002', DATE_SUB(NOW(), INTERVAL 2 DAY), 76000.00, 80000.00, 4000.00, 'cash'),
('TRX-20260009', 'STR-001', 'USR-001', DATE_SUB(NOW(), INTERVAL 2 DAY), 44000.00, 44000.00, 0.00, 'qris'),
('TRX-20260010', 'STR-001', 'USR-003', DATE_SUB(NOW(), INTERVAL 3 DAY), 64000.00, 100000.00, 36000.00, 'cash'),
('TRX-20260011', 'STR-001', 'USR-002', DATE_SUB(NOW(), INTERVAL 3 DAY), 50000.00, 50000.00, 0.00, 'qris'),
('TRX-20260012', 'STR-001', 'USR-002', DATE_SUB(NOW(), INTERVAL 4 DAY), 170000.00, 200000.00, 30000.00, 'cash');

-- ------------------------------------------------------------
-- 7. Table structure for table `tbltransactiondetail`
-- ------------------------------------------------------------
CREATE TABLE `tbltransactiondetail` (
  `TransactionDetailID` varchar(25) NOT NULL,
  `TransactionID` varchar(25) NOT NULL,
  `ProductID` varchar(25) NOT NULL,
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

INSERT INTO `tbltransactiondetail` (`TransactionDetailID`, `TransactionID`, `ProductID`, `Quantity`, `SellingPriceAtTransaction`, `PurchasePriceAtTransaction`, `Subtotal`) VALUES
('TRD-001', 'TRX-20260001', 'PRD-001', 1, 70000.00, 62000.00, 70000.00),
('TRD-002', 'TRX-20260001', 'PRD-002', 2, 17500.00, 15000.00, 35000.00),
('TRD-003', 'TRX-20260002', 'PRD-005', 3, 4000.00, 2500.00, 12000.00),
('TRD-004', 'TRX-20260002', 'PRD-008', 4, 3500.00, 2800.00, 14000.00),
('TRD-005', 'TRX-20260003', 'PRD-003', 2, 18000.00, 15500.00, 36000.00),
('TRD-006', 'TRX-20260003', 'PRD-004', 1, 29000.00, 26000.00, 29000.00),
('TRD-007', 'TRX-20260004', 'PRD-006', 2, 5000.00, 3500.00, 10000.00),
('TRD-008', 'TRX-20260004', 'PRD-009', 2, 10000.00, 7000.00, 20000.00),
('TRD-009', 'TRX-20260004', 'PRD-012', 1, 15000.00, 12000.00, 15000.00),
('TRD-010', 'TRX-20260005', 'PRD-007', 5, 2500.00, 1500.00, 12500.00),
('TRD-011', 'TRX-20260005', 'PRD-010', 1, 12000.00, 9500.00, 12000.00),
('TRD-012', 'TRX-20260006', 'PRD-001', 1, 70000.00, 62000.00, 70000.00),
('TRD-013', 'TRX-20260006', 'PRD-008', 5, 3500.00, 2800.00, 17500.00),
('TRD-014', 'TRX-20260007', 'PRD-002', 2, 17500.00, 15000.00, 35000.00),
('TRD-015', 'TRX-20260007', 'PRD-012', 2, 15000.00, 12000.00, 30000.00),
('TRD-016', 'TRX-20260008', 'PRD-004', 2, 29000.00, 26000.00, 58000.00),
('TRD-017', 'TRX-20260008', 'PRD-003', 1, 18000.00, 15500.00, 18000.00),
('TRD-018', 'TRX-20260009', 'PRD-005', 6, 4000.00, 2500.00, 24000.00),
('TRD-019', 'TRX-20260009', 'PRD-006', 4, 5000.00, 3500.00, 20000.00),
('TRD-020', 'TRX-20260010', 'PRD-011', 2, 20000.00, 17000.00, 40000.00),
('TRD-021', 'TRX-20260010', 'PRD-010', 2, 12000.00, 9500.00, 24000.00),
('TRD-022', 'TRX-20260011', 'PRD-008', 10, 3500.00, 2800.00, 35000.00),
('TRD-023', 'TRX-20260011', 'PRD-007', 6, 2500.00, 1500.00, 15000.00),
('TRD-024', 'TRX-20260012', 'PRD-001', 2, 70000.00, 62000.00, 140000.00),
('TRD-025', 'TRX-20260012', 'PRD-009', 3, 10000.00, 7000.00, 30000.00);
