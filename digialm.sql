-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 02, 2024 at 04:41 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digialm`
--

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `category` varchar(10) NOT NULL,
  `gender` varchar(15) NOT NULL,
  `pwd` varchar(5) NOT NULL,
  `highest_qualification` varchar(100) DEFAULT NULL,
  `university` varchar(100) DEFAULT NULL,
  `year_of_passing` varchar(10) NOT NULL,
  `post` varchar(100) DEFAULT NULL,
  `eligibility` varchar(100) NOT NULL,
  `exam_center` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `signature_path` varchar(255) DEFAULT NULL,
  `is_submitted` varchar(5) NOT NULL,
  `mothers_name` varchar(255) NOT NULL,
  `fathers_name` varchar(255) NOT NULL,
  `nationality` varchar(100) NOT NULL,
  `is_ex_serviceman` enum('Yes','No') NOT NULL,
  `is_religious_minority` enum('Yes','No') NOT NULL,
  `is_central_govt_employee` enum('Yes','No') NOT NULL,
  `marital_status` enum('Single','Married','Divorced','Widowed') NOT NULL,
  `degree` varchar(255) NOT NULL,
  `institution_name` varchar(255) NOT NULL,
  `board` varchar(100) NOT NULL,
  `year_of_graduation` year(4) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `pin_code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`id`, `full_name`, `dob`, `mobile_number`, `email`, `category`, `gender`, `pwd`, `highest_qualification`, `university`, `year_of_passing`, `post`, `eligibility`, `exam_center`, `address`, `photo_path`, `signature_path`, `is_submitted`, `mothers_name`, `fathers_name`, `nationality`, `is_ex_serviceman`, `is_religious_minority`, `is_central_govt_employee`, `marital_status`, `degree`, `institution_name`, `board`, `year_of_graduation`, `city`, `state`, `country`, `pin_code`) VALUES
(1000138, 'Musheer Ahmad', '1996-08-13', '9759773601', 'musheer.fready@gmail.com', 'SC', 'Male', 'No', 'BSc', 'MJPRU Bareilly', '2017', 'Multi-Tasking Staff (MTS)', '(10+2) or its equivalent with PCM. Requires working knowledge in computer (MS word, MS Excel, etc.) ', 'Madurai', 'Ward No 11 garbi masjid Dhaura Tanda', 'uploads/66acb3bf93b5e-WhatsApp_Image.png', 'uploads/66acb3bf9491b-t sign.jpg', 'yes', 'Bilkees Begum', 'Manzoor Ahmad', '', 'No', 'Yes', 'No', 'Married', 'Equivalent of 10th', 'Faiz e Aam Public school', 'UP madarsa Board', 2010, 'Bareilly', 'Uttar Pradesh', 'Indian', '243202'),
(1000141, 'Musheer Ahmad', '1996-08-13', '9759720083', 'musheer7489@gmail.com', '', '', '', NULL, NULL, '', NULL, '', '', NULL, NULL, NULL, '', '', '', '', 'Yes', 'Yes', 'Yes', 'Single', '', '', '', 0000, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` enum('created','successful','failed') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `order_id`, `payment_id`, `amount`, `status`, `created_at`) VALUES
(12, 1000138, 'order_OfzrEJaHfPIgYM', 'pay_OfzrvpefD2W0D5', 5000, 'successful', '2024-08-02 10:24:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000142;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `applicants` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
