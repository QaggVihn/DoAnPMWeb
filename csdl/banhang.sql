-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th10 03, 2025 lúc 04:46 PM
-- Phiên bản máy phục vụ: 8.4.3
-- Phiên bản PHP: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `banhang`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Sách'),
(2, 'Điện Thoại'),
(3, 'Quần Áo'),
(4, 'Phụ Kiện'),
(5, 'Thức ăn'),
(6, 'Đồ gia dụng'),
(7, 'Mỹ phẩm');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('choxuly','hoanthanh','huy') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `total_price`, `status`, `created_at`) VALUES
(1, 2, 1, 2, 300000.00, 'choxuly', '2025-09-30 11:08:26'),
(2, 2, 3, 1, 200000.00, 'hoanthanh', '2025-09-30 11:08:26'),
(3, 3, 2, 1, 20000000.00, 'huy', '2025-09-30 11:08:26'),
(4, 1, 1, 1, 150000.00, 'choxuly', '2025-09-30 14:54:30'),
(5, 1, 2, 1, 20000000.00, 'choxuly', '2025-09-30 15:13:51'),
(6, 1, 4, 1, 500000.00, 'choxuly', '2025-09-30 15:13:51'),
(7, 1, 1, 1, 150000.00, 'choxuly', '2025-09-30 15:13:51'),
(8, 1, 3, 1, 200000.00, 'choxuly', '2025-09-30 15:14:11'),
(9, 1, 2, 1, 20000000.00, 'choxuly', '2025-09-30 15:14:38'),
(10, 1, 1, 1, 150000.00, 'choxuly', '2025-09-30 15:14:38'),
(11, 1, 5, 1, 25000000.00, 'choxuly', '2025-09-30 15:14:38'),
(12, 1, 1, 1, 150000.00, 'choxuly', '2025-10-02 10:14:45'),
(13, 1, 2, 1, 20000000.00, 'choxuly', '2025-10-02 10:14:45'),
(14, 1, 3, 1, 200000.00, 'choxuly', '2025-10-02 10:14:45'),
(15, 1, 7, 1, 15000.00, 'choxuly', '2025-10-03 16:11:53'),
(16, 1, 12, 1, 2520000.00, 'choxuly', '2025-10-03 16:45:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `discount` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `image`, `category_id`, `created_at`, `discount`) VALUES
(1, 'Sách Harry Potter', 'Bộ sách phép thuật nổi tiếng', 150000.00, 50, 'harrypotter.png', 1, '2025-09-30 11:08:26', 0),
(2, 'Điện thoại iPhone 13', 'Smartphone cao cấp', 20000000.00, 20, 'iphone13.jpg', 2, '2025-09-30 11:08:26', 0),
(3, 'Áo thun nam', 'Áo cotton thoải mái', 200000.00, 100, 'aonam.jpg', 3, '2025-09-30 11:08:26', 0),
(4, 'Tai nghe Bluetooth', 'Tai nghe không dây chất lượng cao', 500000.00, 30, 'tainghe.jpg', 4, '2025-09-30 11:08:26', 0),
(5, 'Laptop Dell XPS', 'Laptop mỏng nhẹ cho công việc', 25000000.00, 15, 'laptop.jpg', 2, '2025-09-30 11:08:26', 20),
(6, 'Sách Tư duy nhanh và chậm', 'Sách về tâm lý học và hành vi con người', 180000.00, 40, 'sachtuduy.jpg', 1, '2025-10-03 06:11:07', 0),
(7, 'Truyện tranh Conan tập 1', 'Tập đầu tiên của bộ truyện trinh thám nổi tiếng', 15000.00, 200, 'conan1.jpg', 1, '2025-10-03 06:11:07', 0),
(8, 'Điện thoại Samsung Galaxy S23', 'Flagship Android hiệu năng cao', 18500000.00, 18, 's23.jpg', 2, '2025-10-03 06:11:07', 0),
(9, 'Quần Jeans nữ', 'Quần bò co dãn, phong cách trẻ trung', 450000.00, 80, 'jeansnu.jpg', 3, '2025-10-03 06:11:07', 0),
(10, 'Sạc dự phòng 10000mAh', 'Sạc nhanh, dung lượng lớn, nhỏ gọn', 350000.00, 65, 'sacduphong.jpg', 4, '2025-10-03 06:11:07', 50),
(11, 'Mì gói Omachi Tôm chua cay', 'Thùng 30 gói mì ăn liền tiện lợi', 150000.00, 150, 'mianlien.jpg', 5, '2025-10-03 06:11:07', 0),
(12, 'Nồi chiên không dầu Philips', 'Giúp nấu ăn lành mạnh, giảm dầu mỡ', 2800000.00, 25, 'noichien.jpg', 6, '2025-10-03 06:11:07', 10),
(13, 'Máy xay sinh tố đa năng', 'Xay nhuyễn các loại thực phẩm', 750000.00, 35, 'mayxay.jpg', 6, '2025-10-03 06:11:07', 5),
(14, 'Kem chống nắng La Roche-Posay', 'Bảo vệ da khỏi tia UV hiệu quả', 480000.00, 55, 'kemchongnang.jpg', 7, '2025-10-03 06:11:07', 8),
(15, 'Son môi Black Rouge Air Fit Velvet Tint', 'Son kem lì, màu sắc thời trang', 190000.00, 90, 'sonmoi.jpg', 7, '2025-10-03 06:11:07', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('khachhang','quantri') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$Eanx0p0KBMaIFuAW8uSjkeqTmbmJK0xxznyA.F8mKplD07PSOiL0W', 'admin@example.com', 'quantri', '2025-09-30 11:08:26'),
(2, 'khach1', '$2y$10$3z4wY3z4wY3z4wY3z4wY3u3z4wY3z4wY3z4wY3z4wY3z4wY3z4wY3', 'khach1@example.com', 'khachhang', '2025-09-30 11:08:26'),
(3, 'khach2', '$2y$10$3z4wY3z4wY3z4wY3z4wY3u3z4wY3z4wY3z4wY3z4wY3z4wY3z4wY3', 'khach2@example.com', 'khachhang', '2025-09-30 11:08:26');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_category` (`category_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
