-- =====================================================
-- E-commerce Store - Complete Updated Database
-- Professional products with working images
-- =====================================================

CREATE DATABASE IF NOT EXISTS ecommerce_store;
USE ecommerce_store;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin (password: admin123)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Admin', 'admin@store.com', '$2y$10$hyKIpnP85La1E5U9p8vOJeTn8ReQRMaXPd4PdKEIjOb/CoMRwUIbu', 'admin');

-- --------------------------------------------------------
-- Table: categories
-- --------------------------------------------------------
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Electronics', 'electronics', 'Latest smartphones, laptops, headphones, smartwatches and electronic gadgets.'),
(2, 'Clothing', 'clothing', 'Stylish and comfortable clothing for men and women. Casual, formal and seasonal fashion.'),
(3, 'Home & Kitchen', 'home-kitchen', 'Quality home appliances, kitchen tools and essentials for everyday living.'),
(4, 'Sports & Fitness', 'sports', 'Sports equipment, activewear and fitness gear to keep you active and healthy.');

-- --------------------------------------------------------
-- Table: products
-- --------------------------------------------------------
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `compare_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` 
(`category_id`, `name`, `slug`, `description`, `price`, `compare_price`, `stock`, `image`, `featured`, `status`) 
VALUES
-- Electronics
(1, 'Wireless Bluetooth Headphones', 'wireless-bluetooth-headphones', 'Premium wireless headphones with active noise cancellation, crystal-clear sound, and up to 30 hours of battery life. Comfortable over-ear design perfect for music, calls and travel.', 2499.00, 3499.00, 50, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 1, 'active'),
(1, 'Smart Watch Pro', 'smart-watch-pro', 'Advanced fitness smartwatch with heart rate monitor, blood oxygen tracking, sleep analysis, GPS and water resistance up to 50m. Compatible with Android and iOS.', 4599.00, 5999.00, 30, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&h=500&fit=crop', 1, 'active'),
(1, 'Portable Bluetooth Speaker', 'portable-bluetooth-speaker', 'Compact waterproof Bluetooth speaker with powerful 360° sound, 12-hour battery life and built-in microphone for hands-free calls. Perfect for outdoor and home use.', 1299.00, 1799.00, 75, 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=500&h=500&fit=crop', 1, 'active'),
(1, 'Wireless Earbuds Pro', 'wireless-earbuds-pro', 'True wireless earbuds with deep bass, noise isolation, touch controls and 24-hour total playtime with charging case. Secure fit for sports and daily use.', 1899.00, 2499.00, 60, 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=500&h=500&fit=crop', 0, 'active'),
(1, 'Wireless Charging Pad', 'wireless-charging-pad', 'Fast 15W wireless charging pad compatible with all Qi-enabled smartphones. Sleek design with LED indicator and overcharge protection.', 899.00, 1299.00, 90, 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=500&h=500&fit=crop', 0, 'active'),
(1, 'Gaming Laptop Pro 15', 'gaming-laptop-pro-15', 'High-performance gaming laptop with Intel Core i7, 16GB RAM, 512GB SSD, RTX 4060 graphics and 15.6-inch Full HD display. Perfect for gaming and content creation.', 45999.00, 52999.00, 15, 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=500&h=500&fit=crop', 1, 'active'),
(1, 'iPhone 15 Style Smartphone', 'iphone-15-style-smartphone', 'Premium smartphone with 6.7-inch AMOLED display, 128GB storage, triple camera system and all-day battery life. Fast performance and elegant design.', 34999.00, 39999.00, 25, 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=500&h=500&fit=crop', 1, 'active'),
(1, '4K Action Camera', '4k-action-camera', 'Waterproof 4K action camera with image stabilization, wide-angle lens and dual screens. Ideal for adventure, sports and vlogging.', 5499.00, 6999.00, 40, 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=500&h=500&fit=crop', 1, 'active'),
(1, 'Mechanical Gaming Keyboard', 'mechanical-gaming-keyboard', 'RGB mechanical keyboard with blue switches, anti-ghosting and customizable lighting. Durable build for serious gamers.', 2499.00, 3199.00, 55, 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=500&h=500&fit=crop', 0, 'active'),
(1, 'Tablet 10 Inch', 'tablet-10-inch', '10-inch Android tablet with 64GB storage, dual cameras and long battery life. Perfect for entertainment, study and work.', 8999.00, 10999.00, 35, 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500&h=500&fit=crop', 1, 'active'),
(1, 'USB-C Hub Multiport', 'usb-c-hub-multiport', '7-in-1 USB-C hub with HDMI, USB 3.0, SD card reader and power delivery. Compatible with laptops and tablets.', 1299.00, 1699.00, 70, 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=500&h=500&fit=crop', 0, 'active'),
(1, 'Wireless Mouse Ergonomic', 'wireless-mouse-ergonomic', 'Ergonomic wireless mouse with silent clicks, adjustable DPI and long battery life. Comfortable for daily office use.', 699.00, 999.00, 95, 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=500&h=500&fit=crop', 0, 'active'),
(1, 'Power Bank 20000mAh', 'power-bank-20000mah', 'High capacity 20000mAh power bank with fast charging and dual USB ports. Charge multiple devices on the go.', 1499.00, 1999.00, 60, 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=500&h=500&fit=crop', 1, 'active'),
(1, 'Webcam Full HD', 'webcam-full-hd', 'Full HD 1080p webcam with built-in microphone and auto light correction. Ideal for video calls and streaming.', 1899.00, 2499.00, 45, 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=500&h=500&fit=crop', 0, 'active'),

-- Clothing
(2, 'Men Casual T-Shirt', 'men-casual-tshirt', 'Soft 100% cotton crew-neck t-shirt for men. Breathable, lightweight and available in multiple colors. Perfect for everyday casual wear.', 599.00, 899.00, 120, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=500&fit=crop', 1, 'active'),
(2, 'Women Summer Dress', 'women-summer-dress', 'Elegant and lightweight summer dress for women. Flowy fabric, comfortable fit and stylish design ideal for casual outings, beach and daily wear.', 1299.00, 1799.00, 45, 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=500&h=500&fit=crop', 1, 'active'),
(2, 'Men Slim Fit Jeans', 'men-slim-fit-jeans', 'Classic slim-fit denim jeans for men. Stretchable fabric, modern cut and durable stitching. Comfortable for all-day wear.', 1499.00, 1999.00, 80, 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=500&h=500&fit=crop', 0, 'active'),
(2, 'Women Casual Blouse', 'women-casual-blouse', 'Stylish and soft blouse for women. Lightweight fabric, elegant design and perfect for office or casual occasions.', 999.00, 1399.00, 55, 'https://images.unsplash.com/photo-1564257631407-4deb1f99d992?w=500&h=500&fit=crop', 0, 'active'),
(2, 'Men Formal Shirt', 'men-formal-shirt', 'Classic slim-fit formal shirt for men. Premium cotton blend, wrinkle-resistant and perfect for office and formal occasions.', 1299.00, 1699.00, 70, 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=500&h=500&fit=crop', 1, 'active'),
(2, 'Women Elegant Handbag', 'women-elegant-handbag', 'Stylish leather-look handbag with multiple compartments. Spacious, elegant and perfect for daily use or special occasions.', 1899.00, 2499.00, 45, 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=500&h=500&fit=crop', 1, 'active'),
(2, 'Unisex Hoodie Premium', 'unisex-hoodie-premium', 'Soft fleece unisex hoodie with kangaroo pocket and adjustable drawstring. Comfortable for casual wear and cold weather.', 1499.00, 1999.00, 85, 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=500&h=500&fit=crop', 0, 'active'),
(2, 'Women High Waist Jeans', 'women-high-waist-jeans', 'Trendy high-waist skinny jeans for women. Stretch denim, flattering fit and available in classic blue wash.', 1599.00, 2099.00, 60, 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=500&h=500&fit=crop', 0, 'active'),
(2, 'Men Sports Cap', 'men-sports-cap', 'Adjustable sports cap with breathable fabric and embroidered logo. Perfect for outdoor activities and casual style.', 499.00, 699.00, 100, 'https://images.unsplash.com/photo-1521369909029-2afed882baee?w=500&h=500&fit=crop', 0, 'active'),
(2, 'Men Leather Jacket', 'men-leather-jacket', 'Classic faux leather jacket for men. Stylish, durable and perfect for casual and semi-formal looks.', 3499.00, 4499.00, 30, 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&h=500&fit=crop', 1, 'active'),
(2, 'Women Sneakers White', 'women-sneakers-white', 'Comfortable white sneakers for women. Lightweight, breathable and stylish for everyday wear.', 1799.00, 2299.00, 55, 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=500&h=500&fit=crop', 1, 'active'),
(2, 'Men Polo Shirt', 'men-polo-shirt', 'Classic cotton polo shirt for men. Soft fabric, elegant design and available in multiple colors.', 899.00, 1199.00, 80, 'https://images.unsplash.com/photo-1586790170083-2f9ceadc732d?w=500&h=500&fit=crop', 0, 'active'),
(2, 'Women Winter Coat', 'women-winter-coat', 'Warm and stylish winter coat for women. Soft lining, modern cut and perfect for cold weather.', 3999.00, 4999.00, 25, 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=500&h=500&fit=crop', 0, 'active'),
(2, 'Unisex Backpack', 'unisex-backpack', 'Durable unisex backpack with laptop compartment and multiple pockets. Ideal for school, work and travel.', 1599.00, 2099.00, 50, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500&h=500&fit=crop', 1, 'active'),

-- Home & Kitchen
(3, 'Non-Stick Frying Pan', 'non-stick-frying-pan', 'Premium 28cm non-stick frying pan with heat-resistant handle. Easy to clean, even heat distribution and suitable for all stovetops including induction.', 899.00, 1299.00, 60, 'https://images.unsplash.com/photo-1556911220-bff31c812dba?w=500&h=500&fit=crop', 1, 'active'),
(3, 'Electric Kettle 1.7L', 'electric-kettle-1-7l', 'Fast-boiling stainless steel electric kettle with 1.7 liter capacity, auto shut-off and boil-dry protection. Safe and energy efficient.', 1199.00, 1599.00, 40, 'https://images.unsplash.com/photo-1570222094114-d054a817e56b?w=500&h=500&fit=crop', 0, 'active'),
(3, 'Stainless Steel Cookware Set', 'stainless-steel-cookware-set', '5-piece stainless steel cookware set including pots and pans. Durable, dishwasher safe and suitable for all cooking styles.', 3499.00, 4499.00, 25, 'https://images.unsplash.com/photo-1556911220-bff31c812dba?w=500&h=500&fit=crop', 1, 'active'),
(3, 'Air Fryer 5L', 'air-fryer-5l', 'Large capacity 5-liter digital air fryer with 8 preset cooking modes. Healthy oil-free frying with easy cleanup.', 4999.00, 6499.00, 30, 'https://images.unsplash.com/photo-1574269909862-7e1d70bb8078?w=500&h=500&fit=crop', 1, 'active'),
(3, 'Blender & Smoothie Maker', 'blender-smoothie-maker', 'Powerful 1000W blender with multiple speed settings and pulse function. Perfect for smoothies, soups and food preparation.', 2799.00, 3499.00, 40, 'https://images.unsplash.com/photo-1570222094114-d054a817e56b?w=500&h=500&fit=crop', 0, 'active'),
(3, 'Ceramic Coffee Mug Set', 'ceramic-coffee-mug-set', 'Set of 4 elegant ceramic coffee mugs. Microwave and dishwasher safe. Beautiful design for everyday use or gifting.', 899.00, 1199.00, 75, 'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?w=500&h=500&fit=crop', 0, 'active'),
(3, 'LED Desk Lamp', 'led-desk-lamp', 'Modern LED desk lamp with adjustable brightness, color temperature and flexible neck. Eye-caring light for study and work.', 1299.00, 1699.00, 50, 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=500&h=500&fit=crop', 1, 'active'),
(3, 'Vacuum Cleaner Cordless', 'vacuum-cleaner-cordless', 'Lightweight cordless vacuum cleaner with powerful suction, HEPA filter and long battery life. Ideal for home cleaning.', 6999.00, 8499.00, 20, 'https://images.unsplash.com/photo-1563453392212-326f5e854473?w=500&h=500&fit=crop', 1, 'active'),
(3, 'Rice Cooker 1.8L', 'rice-cooker-1-8l', 'Electric rice cooker with 1.8 liter capacity, keep-warm function and non-stick inner pot. Easy and convenient cooking.', 2499.00, 3199.00, 40, 'https://images.unsplash.com/photo-1574269909862-7e1d70bb8078?w=500&h=500&fit=crop', 1, 'active'),
(3, 'Kitchen Knife Set', 'kitchen-knife-set', 'Professional 6-piece stainless steel kitchen knife set with wooden block. Sharp, durable and essential for cooking.', 2199.00, 2799.00, 35, 'https://images.unsplash.com/photo-1593618998160-e34014e67546?w=500&h=500&fit=crop', 0, 'active'),
(3, 'Toaster 2 Slice', 'toaster-2-slice', 'Modern 2-slice toaster with adjustable browning control and cancel function. Perfect for quick breakfasts.', 999.00, 1399.00, 55, 'https://images.unsplash.com/photo-1481391319762-47dff72954d9?w=500&h=500&fit=crop', 0, 'active'),
(3, 'Wall Clock Modern', 'wall-clock-modern', 'Sleek modern wall clock with silent quartz movement. Minimalist design that fits any room style.', 799.00, 1099.00, 70, 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=500&h=500&fit=crop', 0, 'active'),
(3, 'Bed Sheet Set Cotton', 'bed-sheet-set-cotton', 'Soft 100% cotton bed sheet set including fitted sheet, flat sheet and 2 pillowcases. Breathable and comfortable.', 1899.00, 2499.00, 45, 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=500&h=500&fit=crop', 1, 'active'),

-- Sports & Fitness
(4, 'Yoga Mat Premium', 'yoga-mat-premium', 'Extra thick 8mm anti-slip yoga mat with carrying strap. Eco-friendly material, excellent cushioning and grip for yoga, pilates and floor exercises.', 799.00, 999.00, 80, 'https://images.unsplash.com/photo-1545389336-cf090694435e?w=500&h=500&fit=crop', 1, 'active'),
(4, 'Adjustable Dumbbell Set', 'adjustable-dumbbell-set', 'Space-saving adjustable dumbbell set (2.5kg - 20kg per hand). Perfect for home gym strength training with secure locking system.', 2999.00, 3999.00, 35, 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=500&h=500&fit=crop', 1, 'active'),
(4, 'Sports Running Shoes', 'sports-running-shoes', 'Lightweight and breathable running shoes with cushioned sole and excellent grip. Ideal for jogging, gym and outdoor activities.', 2199.00, 2799.00, 50, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&h=500&fit=crop', 0, 'active'),
(4, 'Resistance Bands Set', 'resistance-bands-set', 'Set of 5 resistance bands with different levels. Includes door anchor and carrying bag. Perfect for home workouts.', 699.00, 999.00, 90, 'https://images.unsplash.com/photo-1598289431512-b97b0917affc?w=500&h=500&fit=crop', 0, 'active'),
(4, 'Jump Rope Speed', 'jump-rope-speed', 'Professional speed jump rope with adjustable length and ball bearings. Ideal for cardio, boxing and fitness training.', 399.00, 599.00, 110, 'https://images.unsplash.com/photo-1594737625785-a6cbdabd333c?w=500&h=500&fit=crop', 0, 'active'),
(4, 'Fitness Tracker Band', 'fitness-tracker-band', 'Smart fitness band with heart rate monitor, step counter, sleep tracking and waterproof design. 7-day battery life.', 1499.00, 1999.00, 65, 'https://images.unsplash.com/photo-1575311373937-040b8e1fd5b6?w=500&h=500&fit=crop', 1, 'active'),
(4, 'Yoga Block Pair', 'yoga-block-pair', 'High-density foam yoga blocks (pair). Lightweight, non-slip and perfect for improving flexibility and balance.', 599.00, 799.00, 80, 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=500&h=500&fit=crop', 0, 'active'),
(4, 'Protein Shaker Bottle', 'protein-shaker-bottle', 'Leak-proof 700ml protein shaker bottle with mixing ball. BPA-free and easy to clean. Perfect for gym and daily use.', 349.00, 499.00, 120, 'https://images.unsplash.com/photo-1593095948071-474c5cc2989d?w=500&h=500&fit=crop', 0, 'active'),
(4, 'Dumbbell Set 20kg', 'dumbbell-set-20kg', 'Pair of 10kg dumbbells (total 20kg) with comfortable grip. Ideal for strength training at home.', 2499.00, 3199.00, 40, 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=500&h=500&fit=crop', 1, 'active'),
(4, 'Yoga Pants Women', 'yoga-pants-women', 'High-waist stretch yoga pants for women. Soft, breathable fabric with excellent flexibility for workouts.', 999.00, 1399.00, 65, 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?w=500&h=500&fit=crop', 0, 'active'),
(4, 'Sports Water Bottle', 'sports-water-bottle', '1 Liter BPA-free sports water bottle with leak-proof lid. Perfect for gym, running and outdoor activities.', 449.00, 649.00, 100, 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=500&h=500&fit=crop', 0, 'active'),
(4, 'Foam Roller', 'foam-roller', 'High-density foam roller for muscle recovery and flexibility. Ideal after workouts and for physical therapy.', 899.00, 1199.00, 50, 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=500&h=500&fit=crop', 0, 'active'),
(4, 'Men Running Shorts', 'men-running-shorts', 'Lightweight breathable running shorts for men with inner lining and zip pocket. Comfortable for sports and gym.', 799.00, 1099.00, 75, 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=500&h=500&fit=crop', 0, 'active');

-- --------------------------------------------------------
-- Table: orders
-- --------------------------------------------------------
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_number` varchar(30) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'cash_on_delivery',
  `payment_status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  `shipping_name` varchar(100) NOT NULL,
  `shipping_phone` varchar(20) NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_city` varchar(100) NOT NULL,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: order_items
-- --------------------------------------------------------
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: addresses
-- --------------------------------------------------------
CREATE TABLE `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `city` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;