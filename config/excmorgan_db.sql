-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2025 at 12:09 AM
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
-- Database: `excmorgan_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-11-24 08:06:22', '2025-11-24 08:06:22'),
(2, 2, '2025-11-24 08:06:22', '2025-11-24 08:06:22'),
(3, 3, '2025-11-24 08:06:22', '2025-11-24 08:06:22'),
(4, 4, '2025-11-24 09:49:33', '2025-11-24 09:49:33'),
(5, 5, '2025-11-28 06:31:49', '2025-11-28 06:31:49');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(3, 2, 3, 1, '2025-11-24 08:06:22', '2025-11-24 08:06:22'),
(4, 3, 4, 1, '2025-11-24 08:06:22', '2025-11-24 08:06:22');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'M Faiz Abdillah', 'faizabdillah2828@gmail.com', 'percobaan', '2025-11-24 09:31:35'),
(6, 'Morgan', 'excmorgan@gmail.com', 'tes percobaan', '2025-11-24 09:45:16'),
(7, 'bb', 'bb@gbbdqj.com', 'n', '2025-11-28 06:32:58');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('Diproses','Dikirim','Selesai','Dibatalkan') DEFAULT 'Diproses',
  `shipping_address` text NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `shipping_address`, `payment_method`, `created_at`, `updated_at`) VALUES
(1, 2, 300000.00, 'Selesai', 'Jl. Contoh Alamat No. 123, Jakarta', 'Transfer Bank', '2025-11-24 08:06:22', '2025-11-28 06:36:41'),
(2, 3, 200000.00, 'Selesai', 'Jl. Alamat Kedua No. 456, Bandung', 'COD', '2025-11-24 08:06:22', '2025-11-28 06:36:22'),
(3, 4, 120000.00, 'Selesai', 'Banjarnegara, Jawa Tengah', 'E-Wallet', '2025-11-24 09:50:06', '2025-11-28 06:36:33'),
(4, 5, 180000.00, 'Selesai', 'm', 'COD', '2025-11-28 06:32:12', '2025-11-28 06:38:29'),
(5, 1, 570000.00, 'Selesai', 'm', 'COD', '2025-11-28 06:37:55', '2025-11-28 06:38:25');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_price`, `quantity`, `total_price`) VALUES
(1, 1, 1, 'Kaos Oversize Hitam', 120000.00, 2, 240000.00),
(2, 1, 2, 'Kemeja Putih Modern', 180000.00, 1, 180000.00),
(3, 2, 4, 'Jaket Hoodie Navy', 200000.00, 1, 200000.00),
(4, 3, 1, 'Kaos Oversize Hitam', 120000.00, 1, 120000.00),
(5, 4, 2, 'Kemeja Putih Modern', 180000.00, 1, 180000.00),
(6, 5, 1, 'Kaos Oversize Hitam', 120000.00, 2, 240000.00),
(7, 5, 2, 'Kemeja Putih Modern', 180000.00, 1, 180000.00),
(8, 5, 3, 'Cargo Pants Loose abu abu', 150000.00, 1, 150000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Kaos Oversize Hitam', 'Kaos fashion oversize dengan bahan katun lembut, nyaman dipakai sehari-hari', 120000.00, 'img/product_1763975013_69241f65031b0.jpg', 1, '2025-11-24 08:06:22', '2025-11-24 09:03:33'),
(2, 'Kemeja Putih Modern', 'Kemeja lengan panjang dengan desain modern, cocok untuk acara formal maupun casual', 180000.00, 'img/product_1763975132_69241fdc52419.jpg', 1, '2025-11-24 08:06:22', '2025-11-24 09:05:32'),
(3, 'Cargo Pants Loose abu abu', 'Celana cargo dengan bahan kain berkualitas, tampilan berani dan stylish', 150000.00, 'img/product_1763975463_69242127aab32.jpeg', 1, '2025-11-24 08:06:22', '2025-11-24 09:11:03'),
(4, 'Jaket Hoodie Navy', 'Hoodie hangat dengan desain unisex, cocok untuk gaya streetwear', 200000.00, 'img/product_1763975584_692421a00cfc9.jpg', 1, '2025-11-24 08:06:22', '2025-11-24 09:13:04'),
(5, 'Rok Midi Denim', 'Rok denim midi dengan potongan yang modis, cocok untuk berbagai suasana', 135000.00, 'img/product_1763975739_6924223bbbc1c.jpg', 1, '2025-11-24 08:06:22', '2025-11-24 09:15:39');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `key_name` varchar(50) NOT NULL,
  `value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `key_name`, `value`, `updated_at`) VALUES
(1, 'contact_email', 'faizabdillah2828@gmail.com', '2025-11-24 09:17:51'),
(2, 'contact_phone', '+6282224209906', '2025-11-24 09:17:51'),
(3, 'address', 'Banjarnegara, Jawa Tengah', '2025-11-24 09:17:51'),
(4, 'instagram', 'https://instagram.com/excmorgan', '2025-11-24 08:06:22'),
(5, 'tiktok', 'https://tiktok.com/@excmorgan', '2025-11-24 08:06:22'),
(6, 'contact_form_recipient', 'faizabdillah2828@gmail.com', '2025-11-24 09:17:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `is_admin`, `created_at`, `updated_at`) VALUES
(1, 'Admin Excmorgan', 'admin@excmorgan.com', '$2y$10$.qUIB8gl4QcSxKbfDoS.zuffhu3UdIYUF7wUXIOVul523d3b2crR.', 1, '2025-11-24 08:06:22', '2025-11-24 08:32:46'),
(2, 'John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, '2025-11-24 08:06:22', '2025-11-24 08:06:22'),
(3, 'Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, '2025-11-24 08:06:22', '2025-11-24 08:06:22'),
(4, 'M Faiz Abdillah', 'faizabdillah2828@gmail.com', '$2y$10$Bf5Ya7Iah44nfkJIuGWgEuNSnALU98SsU2yd/K1UhglVzzIy1mL7m', 0, '2025-11-24 09:29:50', '2025-11-24 09:29:50'),
(5, 'faizabdilah', 'bikinenjoy@gmail.com', '$2y$10$fdbK.THmmIGJ1OMOI/ErQOBKxgFkxaovE8OsBa/YHAo/vKXhf4dp2', 0, '2025-11-28 06:31:27', '2025-11-28 06:31:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_cart_items_cart_id` (`cart_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orders_user_id` (`user_id`),
  ADD KEY `idx_orders_status` (`status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_order_items_order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_products_active` (`is_active`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
