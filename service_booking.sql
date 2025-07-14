-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2025 at 11:53 AM
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
-- Database: `service_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `booking_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Rejected','Completed') DEFAULT 'Pending',
  `amount` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `service_id`, `user_id`, `name`, `email`, `phone`, `booking_date`, `created_at`, `status`, `amount`) VALUES
(1, 5, NULL, 'khalida', 'khalidaparveen@gmail.com', '9878877665', '2025-07-17', '2025-07-12 10:18:52', 'Pending', 0.00),
(4, 5, NULL, 'AZHAR KHAN', 'mdazharulkhan143000@gmail.com', '98765543321', '2025-08-01', '2025-07-12 10:22:07', 'Pending', 0.00),
(5, 3, NULL, 'azhar khan', 'mdazharulkhan143000@gmail.com', '98765543321', '2025-07-11', '2025-07-12 10:38:24', 'Pending', 0.00),
(6, 6, NULL, 'ajrul khan', 'sahilkhanali@gmail.com', '987887766', '2002-10-10', '2025-07-12 10:43:53', 'Pending', 0.00),
(7, 1, NULL, 'mohd azhar khan', 'sahilkhanali@gmail.com', '9878877665', '3200-02-12', '2025-07-12 11:07:47', 'Approved', 0.00),
(8, 4, NULL, 'Sahil Khan', 'sahilkhanali@gmail.com', '9878877665', '2002-02-20', '2025-07-13 10:09:39', 'Pending', 0.00),
(9, 12, NULL, 'sanju bhai', 'admin@example.com', '9878877665', '2022-02-20', '2025-07-13 10:31:42', 'Pending', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `image`, `price`, `category`) VALUES
(1, 'Home Cleaning', 'Professional cleaning service for your home.', 'clean.jpeg', 1499.00, 'Cleaning'),
(2, 'AC Repair', 'Expert AC repair and maintenance.', 'ac_repair.jpg', 799.00, 'Repair'),
(3, 'Plumbing Service', 'Fix leaks, pipe fittings and more.', 'plumbing.jpg', 2000.00, 'maintenance'),
(4, 'Sweet Paan ', 'A delightful blend of tradition and taste! Our Sweet Paan is made with fresh betel leaves, stuffed with a rich mixture of gulkand (rose petal preserve), candied fruits, coconut flakes, fennel seeds, and aromatic spices. It’s a perfect mouth freshener and a post-meal indulgence that bursts with flavor, sweetness, and a refreshing aroma.', 'sweet_paan.jpg', 500.00, 'Shop'),
(5, 'Electrician Service', 'Certified electricians at your doorstep.', 'electrician.jpg', 899.00, 'Electrical'),
(6, 'Car Wash', 'Doorstep car cleaning and waxing.', 'carwash.jpg', 599.00, 'Auto'),
(7, 'Carpenter Service', 'Woodwork and furniture fixing.', 'carpenter.jpg', 1499.00, 'Maintenance'),
(8, 'Sofa Cleaning', 'Deep cleaning for sofas and upholstery.', 'sofa_cleaning.jpg', 1099.00, 'Cleaning'),
(9, 'Water Tank Cleaning', 'Hygienic cleaning of water tanks.', 'watertank.jpg', 699.00, 'Sanitation'),
(10, 'Mobile Repair', 'Smartphone diagnostics and repair.', 'mobile_repair.jpg', 499.00, 'Repair'),
(11, 'Fridge Repair', 'Expert fridge and freezer repair.', 'fridge_repair.jpg', 899.00, 'Repair'),
(12, 'CCTV Installation', 'Secure your premises with CCTV.', 'cctv.jpg', 1999.00, 'Security'),
(13, 'Computer Repair', 'Laptop/PC repair and servicing.', 'computer_repair.jpg', 799.00, 'Repair'),
(14, 'Painting Service', 'Interior and exterior house painting.', 'painting.jpg', 2499.00, 'Renovation'),
(15, 'Gardening Service', 'Landscaping and garden maintenance.', 'gardening.jpg', 999.00, 'Outdoor'),
(16, 'Geyser Repair', 'Instant water heater repairs.', 'geyser.jpg', 699.00, 'Repair'),
(17, 'TV Installation', 'Wall-mount and setup of TVs.', 'tv_install.jpg', 599.00, 'Electronics'),
(18, 'Laundry Service', 'Washing, drying, and folding.', 'laundry.jpg', 399.00, 'Cleaning'),
(19, 'Chimney Cleaning', 'Deep kitchen chimney cleaning.', 'chimney.jpg', 799.00, 'Kitchen'),
(20, 'Bike Service', 'Two-wheeler full service at home.', 'bike_service.jpg', 499.00, 'Auto');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'azhar khan', 'azharkhan12@gmail.com', '$2y$10$bUpc4D3NtDmFt9b8umElB.uTFzU0CYmCWf/iK4/QXOI1qUbxjXXJ6', '2025-07-12 10:59:14'),
(2, 'azhar khan', 'azharkhan123@gmail.com', '$2y$10$4piifHy8gjPyzR7.OTpBmOdIbJOiwkFflKE..DXlHy3MS1oNjSvvK', '2025-07-12 10:59:33'),
(3, 'khalida', 'khalida@gmail.com', '$2y$10$jaNPg5fsbj.WCjpn/MTM4uvyjNFLt2w4OWjIPJyzQJ2LZi5KZmt.6', '2025-07-12 11:00:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
