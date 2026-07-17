-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2026 at 05:23 PM
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
-- Database: `phserved_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `action_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`log_id`, `user_id`, `action`, `action_date`) VALUES
(1, 1, 'Logged in to the Seller System', '2026-07-16 15:40:20'),
(2, 1, 'Logged out of the Seller System', '2026-07-16 15:44:14'),
(3, 1, 'Logged in to the Seller System', '2026-07-16 15:53:52'),
(4, 1, 'Logged in to the Seller System', '2026-07-16 16:14:44'),
(5, 2, 'Logged in to the Seller System', '2026-07-16 18:25:42'),
(6, 3, 'Logged in to the Seller System', '2026-07-16 18:35:56'),
(7, 3, 'Added user: Sample Sample as Buyer', '2026-07-16 18:37:12'),
(8, 3, 'Updated user: Sample Sample', '2026-07-16 18:38:00'),
(9, 3, 'Updated user: Sample Sample', '2026-07-16 18:38:22'),
(10, 3, 'Logged in to the Seller System', '2026-07-16 18:40:00'),
(11, 3, 'Updated product: 16GB DDR4 Memory', '2026-07-16 18:40:51'),
(12, 3, 'Added product: Sample', '2026-07-16 18:42:52'),
(13, 1, 'Logged in to the Seller System', '2026-07-16 19:45:09'),
(14, 1, 'Logged in to the Seller System', '2026-07-16 20:00:18'),
(15, 1, 'Logged out of the Seller System', '2026-07-16 20:57:48'),
(16, 3, 'Logged in to the Seller System', '2026-07-16 20:58:13'),
(17, 3, 'Logged out of the Seller System', '2026-07-16 21:12:34'),
(18, 4, 'Logged in to the Seller System', '2026-07-16 21:22:58'),
(19, 4, 'Logged out of the Seller System', '2026-07-16 21:27:57'),
(20, 1, 'Updated product: USB Gaming Controller', '2026-07-16 22:32:49'),
(21, 1, 'Logged out of the Seller System', '2026-07-16 22:41:18'),
(22, 1, 'Logged in to the Seller System', '2026-07-16 22:44:47'),
(23, 1, 'Logged in to the Seller System', '2026-07-16 22:59:25'),
(24, 1, 'Added user: sample sample as Buyer', '2026-07-16 23:46:18'),
(25, 1, 'Deleted 1 Buyer account(s)', '2026-07-16 23:48:59'),
(26, 1, 'Logged out of the Seller System', '2026-07-16 23:50:34'),
(27, 1, 'Logged out of the Seller System', '2026-07-17 00:31:39'),
(28, 1, 'Logged out of the Seller System', '2026-07-17 01:10:24'),
(29, 1, 'Logged in to the Seller System', '2026-07-17 01:28:54'),
(30, 1, 'Added user: Sample Sample as Buyer', '2026-07-17 01:29:35'),
(31, 1, 'Updated user: Sample Sample', '2026-07-17 01:30:17'),
(32, 1, 'Deleted 1 Buyer account(s)', '2026-07-17 01:30:36'),
(33, 1, 'Logged out of the Seller System', '2026-07-17 01:32:05'),
(34, 3, 'Logged in to the Seller System', '2026-07-17 01:33:10'),
(35, 3, 'Logged out of the Seller System', '2026-07-17 01:33:33'),
(36, 2, 'Logged out of the Seller System', '2026-07-17 01:47:32'),
(37, 4, 'Logged out of the Seller System', '2026-07-17 09:04:25'),
(38, 1, 'Logged in to the Seller System', '2026-07-17 12:09:58'),
(39, 1, 'Logged out of the Seller System', '2026-07-17 13:17:18'),
(40, 1, 'Logged out of the Seller System', '2026-07-17 14:02:04'),
(41, 1, 'Logged in to the Seller System', '2026-07-17 14:02:29'),
(42, 1, 'Deleted 1 Buyer account(s)', '2026-07-17 14:02:42'),
(43, 1, 'Logged in to the Seller System', '2026-07-17 14:33:23'),
(44, 1, 'Logged in to the Seller System', '2026-07-17 14:57:52'),
(45, 1, 'Added product: NVIDIA RTX 4060 Ti Graphics Card', '2026-07-17 14:59:12'),
(46, 1, 'Logged out of the Seller System', '2026-07-17 14:59:58'),
(47, 4, 'Logged in to the Seller System', '2026-07-17 15:00:17'),
(48, 4, 'Deleted 1 Buyer account(s)', '2026-07-17 15:00:54'),
(49, 3, 'Logged in to the Seller System', '2026-07-17 15:12:07'),
(50, 1, 'Logged in to the Seller System', '2026-07-17 15:30:14'),
(51, 1, 'Updated product: NVIDIA RTX 4060 Ti Graphics Card', '2026-07-17 15:30:31'),
(52, 1, 'Updated user: PHServed', '2026-07-17 15:33:15'),
(53, 1, 'Deleted 1 Buyer account(s)', '2026-07-17 15:33:47');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `date_added` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_item_id`, `user_id`, `product_id`, `quantity`, `date_added`) VALUES
(26, 2, 6, 2, '2026-07-17 15:09:47');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Input Devices'),
(3, 'Output Devices'),
(2, 'Processing Devices');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shipping_address` varchar(500) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` varchar(30) NOT NULL DEFAULT 'Paid',
  `order_status` varchar(30) NOT NULL DEFAULT 'Completed',
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `shipping_address`, `contact_number`, `payment_method`, `payment_status`, `order_status`, `total_amount`, `order_date`) VALUES
(1, 1, '123 Ayala Avenue, Barangay Poblacion, Makati City, Metro Manila 1210', '+639171234501', 'GCash / E-Wallet', 'Paid', 'Completed', 27609.00, '2026-07-16 17:20:16'),
(2, 1, '123 Ayala Avenue, Barangay Poblacion, Makati City, Metro Manila 1210', '+639171234501', 'Cash on Delivery', 'Paid', 'Completed', 1019.00, '2026-07-16 18:22:34'),
(3, 1, '123 Ayala Avenue, Barangay Poblacion, Makati City, Metro Manila 1210', '+639171234501', 'Cash on Delivery', 'Paid', 'Completed', 3518.00, '2026-07-16 19:46:59'),
(4, 1, '123 Ayala Avenue, Barangay Poblacion, Makati City, Metro Manila 1210', '+639171234501', 'Cash on Delivery', 'Paid', 'Completed', 1019.00, '2026-07-16 19:52:28'),
(5, 1, '123 Ayala Avenue, Barangay Poblacion, Makati City, Metro Manila 1210', '+639171234501', 'Cash on Delivery', 'Paid', 'Completed', 8516.00, '2026-07-16 22:12:06'),
(6, 1, '123 Ayala Avenue, Barangay Poblacion, Makati City, Metro Manila 1210', '+639171234501', 'GCash / E-Wallet', 'Paid', 'Completed', 4918.00, '2026-07-16 23:55:50'),
(7, 1, '123 Ayala Avenue, Barangay Poblacion, Makati City, Metro Manila 1210', '+639171234501', 'GCash / E-Wallet', 'Paid', 'Completed', 15416.00, '2026-07-17 01:27:01'),
(10, 2, '45 Quezon Avenue, Barangay South Triangle, Quezon City, Metro Manila 1103', '+639171234502', 'GCash / E-Wallet', 'Paid', 'Completed', 53120.00, '2026-07-17 15:08:18'),
(12, 1, '123 Ayala Avenue, Barangay Poblacion, Makati City, Metro Manila 1210', '+639171234501', 'GCash / E-Wallet', 'Paid', 'Completed', 7119.00, '2026-07-17 22:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `product_name`, `quantity`, `unit_price`, `subtotal`) VALUES
(1, 1, 1, 'Mechanical Gaming Keyboard', 11, 2499.00, 27489.00),
(2, 2, 2, 'Wireless Optical Mouse', 1, 899.00, 899.00),
(3, 3, 2, 'Wireless Optical Mouse', 1, 899.00, 899.00),
(4, 3, 1, 'Mechanical Gaming Keyboard', 1, 2499.00, 2499.00),
(5, 4, 2, 'Wireless Optical Mouse', 1, 899.00, 899.00),
(6, 5, 2, 'Wireless Optical Mouse', 1, 899.00, 899.00),
(7, 5, 5, '16GB DDR4 Memory', 3, 2499.00, 7497.00),
(8, 6, 6, '650W Power Supply', 1, 3499.00, 3499.00),
(9, 6, 3, 'USB Gaming Controller', 1, 1299.00, 1299.00),
(10, 7, 3, 'USB Gaming Controller', 1, 1299.00, 1299.00),
(11, 7, 7, '24-inch LED Monitor', 1, 6999.00, 6999.00),
(12, 7, 6, '650W Power Supply', 2, 3499.00, 6998.00),
(15, 10, 11, 'NVIDIA RTX 4060 Ti Graphics Card', 2, 26500.00, 53000.00),
(17, 12, 7, '24-inch LED Monitor', 1, 6999.00, 6999.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `product_status` varchar(30) NOT NULL DEFAULT 'Available',
  `description` text NOT NULL,
  `date_added` datetime DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `product_image`, `price`, `stock_quantity`, `product_status`, `description`, `date_added`, `date_updated`) VALUES
(1, 1, 'Mechanical Gaming Keyboard', 'Mechanical_Gaming_Keyboard.png', 2499.00, 0, 'Out of Stock', 'A mechanical keyboard designed for gaming and everyday computer use.', '2026-07-16 15:05:12', '2026-07-16 19:46:59'),
(2, 1, 'Wireless Optical Mouse', 'Wireless_Optical_Mouse.png', 899.00, 0, 'Out of Stock', 'A wireless mouse with an adjustable sensitivity setting.', '2026-07-16 15:05:12', '2026-07-16 22:12:06'),
(3, 1, 'USB Gaming Controller', 'USB_Gaming_Controller.png', 1299.00, 0, 'Out of Stock', 'A wired controller compatible with desktop computer games.', '2026-07-16 15:05:12', '2026-07-17 01:27:01'),
(4, 2, 'AMD Ryzen 5 Processor', 'AMD_Ryzen_5_Processor.png', 8999.00, 8, 'Available', 'A six-core processor suitable for gaming and productivity.', '2026-07-16 15:05:12', '2026-07-17 14:49:46'),
(5, 2, '16GB DDR4 Memory', '16GB_DDR4_Memory.png', 2499.00, 2, 'Low Stock', 'A 16GB DDR4 desktop memory module.', '2026-07-16 15:05:12', '2026-07-16 22:12:06'),
(6, 2, '650W Power Supply', '650W_Power_Supply.png', 3499.00, 5, 'Low Stock', 'A 650-watt power supply for desktop computer systems.', '2026-07-16 15:05:12', '2026-07-17 01:27:01'),
(7, 3, '24-inch LED Monitor', '24-inch_LED_Monitor.png', 6999.00, 6, 'Available', 'A full HD LED monitor for work, school, and gaming.', '2026-07-16 15:05:12', '2026-07-17 22:04:54'),
(8, 3, 'USB Computer Speakers', 'USB_Computer_Speakers.png', 999.00, 3, 'Low Stock', 'Compact USB-powered speakers for desktop computers.', '2026-07-16 15:05:12', '2026-07-16 15:05:12'),
(9, 3, 'HDMI Display Projector', 'HDMI_Display_Projector.png', 12999.00, 0, 'Out of Stock', 'A digital projector with HDMI input for computer display output.', '2026-07-16 15:05:12', '2026-07-16 15:05:12'),
(11, 2, 'NVIDIA RTX 4060 Ti Graphics Card', '1784273431_NVIDIA_RTX_4060_Ti_Graphics_Card.png', 26500.00, 8, 'Available', 'Advanced Ada Lovelace architecture GPU designed for high-frame-rate gaming and processing.', '2026-07-17 14:59:12', '2026-07-17 15:31:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `complete_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `street` varchar(150) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'Buyer',
  `account_status` varchar(20) NOT NULL DEFAULT 'Active',
  `is_confirmed` tinyint(4) NOT NULL DEFAULT 0,
  `confirm_token` varchar(255) DEFAULT NULL,
  `date_created` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `complete_name`, `email`, `password`, `street`, `barangay`, `city`, `province`, `zip_code`, `contact_number`, `role`, `account_status`, `is_confirmed`, `confirm_token`, `date_created`) VALUES
(1, 'Hannah Elisha A. Delos Santos', 'hadelossantos@fit.edu.ph', '$2y$10$4wpvbTCwoazj0Acln88YyeMNEvfOPKsFqMEvZU9rOiXxkFsDhpGnm', '123 Ayala Avenue', 'Poblacion', 'Makati City', 'Metro Manila', '1210', '+639171234501', 'Admin', 'Active', 1, NULL, '2026-07-16 15:05:12'),
(2, 'Jen Raina R. Teodoro', 'jrteodoro@fit.edu.ph', '$2y$10$jmYgSArkozXSoYTdjgcgB.sQ5xFlF3gW1iB9RBHJ6WiACj55KNOqK', '45 Quezon Avenue', 'South Triangle', 'Quezon City', 'Metro Manila', '1103', '+639171234502', 'Admin', 'Active', 1, NULL, '2026-07-16 15:05:12'),
(3, 'Joy Anne Ciaris B. Nuqui', 'jbnuqui@fit.edu.ph', '$2y$10$sew8dsu2PY7pXi6ngc2/2uM8LD1wVYlHYNvNFr8RsWn3JFKvy7Rym', '78 MacArthur Highway', 'Tikay', 'Malolos City', 'Bulacan', '3000', '+639171234503', 'Admin', 'Active', 1, NULL, '2026-07-16 15:05:12'),
(4, 'Ledson John B. Ejanda', 'lbejanda@fit.edu.ph', '$2y$10$IiJBheQLKDQGzML5SsHuGuNvyJov9UbchX1IC7jHKiDJocgGm1eBq', '90 Taft Avenue', 'Ermita', 'Manila', 'Metro Manila', '1000', '+639171234504', 'Admin', 'Active', 1, NULL, '2026-07-16 15:05:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
