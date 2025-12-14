-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql306.byetcluster.com
-- Generation Time: Dec 11, 2025 at 06:21 PM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40650282_english_practice`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'fas fa-book'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `icon`) VALUES
(1, 'IELTS', 'International English Language Testing System', 'fas fa-globe-europe'),
(2, 'TOEIC', 'Test of English for International Communication', 'fas fa-briefcase'),
(3, 'TOEFL', 'Test of English as a Foreign Language', 'fas fa-university');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `reply` text DEFAULT NULL,
  `status` enum('new','read','replied') DEFAULT 'new',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration_minutes` float DEFAULT 60,
  `total_questions` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `type` enum('mixed','image','audio') DEFAULT 'mixed'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `category_id`, `title`, `description`, `duration_minutes`, `total_questions`, `image`, `created_at`, `type`) VALUES
(1, 1, 'IELTS Reading - Academic Test 1', 'Full academic reading simulation.', 60, 2, '', '2025-12-10 21:10:50', 'mixed'),
(2, 1, 'â', '', 60, 1, '', '2025-12-10 22:47:57', 'image'),
(3, 1, 'thi dang', '', 5, 1, '', '2025-12-10 22:49:50', 'audio');

-- --------------------------------------------------------

--
-- Table structure for table `lab_files`
--

CREATE TABLE `lab_files` (
  `id` int(11) NOT NULL,
  `lab_session` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `display_path` varchar(500) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(10) DEFAULT 'FILE',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `lab_files`
--

INSERT INTO `lab_files` (`id`, `lab_session`, `title`, `display_path`, `file_path`, `file_type`, `created_at`) VALUES
(262, 4, 'lab04', 'lab4_2.php', 'lab4/lab4_2.php', 'PHP', '2025-12-11 01:39:20'),
(263, 4, 'lab04', 'lab4_2a.php', 'lab4/lab4_2a.php', 'PHP', '2025-12-11 01:39:20'),
(264, 4, 'lab04', 'lab4_2b.php', 'lab4/lab4_2b.php', 'PHP', '2025-12-11 01:39:20'),
(265, 4, 'lab04', 'lab4_3.php', 'lab4/lab4_3.php', 'PHP', '2025-12-11 01:39:20'),
(266, 4, 'lab04', 'lab5_1.php', 'lab4/lab5_1.php', 'PHP', '2025-12-11 01:39:20'),
(267, 4, 'lab04', 'lab5_2.php', 'lab4/lab5_2.php', 'PHP', '2025-12-11 01:39:20'),
(268, 4, 'lab04', 'lab5_3.php', 'lab4/lab5_3.php', 'PHP', '2025-12-11 01:39:20'),
(269, 4, 'lab04', 'lap4_4.php', 'lab4/lap4_4.php', 'PHP', '2025-12-11 01:39:20'),
(251, 3, 'lab03', 'lab5_2.php', 'lab3/lab5_2.php', 'PHP', '2025-12-11 01:25:09'),
(250, 3, 'lab03', 'lab5_1.php', 'lab3/lab5_1.php', 'PHP', '2025-12-11 01:25:09'),
(249, 3, 'lab03', 'lab4_4.php', 'lab3/lab4_4.php', 'PHP', '2025-12-11 01:25:09'),
(248, 3, 'lab03', 'lab4_3.php', 'lab3/lab4_3.php', 'PHP', '2025-12-11 01:25:09'),
(247, 3, 'lab03', 'lab4_2.php', 'lab3/lab4_2.php', 'PHP', '2025-12-11 01:25:09'),
(246, 3, 'lab03', 'lab4_1.php', 'lab3/lab4_1.php', 'PHP', '2025-12-11 01:25:09'),
(245, 3, 'lab03', 'lab3_5.php', 'lab3/lab3_5.php', 'PHP', '2025-12-11 01:25:09'),
(244, 3, 'lab03', 'lab3_4.php', 'lab3/lab3_4.php', 'PHP', '2025-12-11 01:25:09'),
(243, 3, 'lab03', 'lab3_3.php', 'lab3/lab3_3.php', 'PHP', '2025-12-11 01:25:09'),
(242, 3, 'lab03', 'lab3_2.php', 'lab3/lab3_2.php', 'PHP', '2025-12-11 01:25:09'),
(241, 3, 'lab03', 'lab3_1.php', 'lab3/lab3_1.php', 'PHP', '2025-12-11 01:25:09'),
(222, 2, 'lab02', 'lab2_1a.html', 'lab2/lab2_1a.html', 'HTML', '2025-12-11 01:22:32'),
(223, 2, 'lab02', 'lab2_1b.php', 'lab2/lab2_1b.php', 'PHP', '2025-12-11 01:22:32'),
(224, 2, 'lab02', 'lab2_2.php', 'lab2/lab2_2.php', 'PHP', '2025-12-11 01:22:32'),
(225, 2, 'lab02', 'lab2_3.php', 'lab2/lab2_3.php', 'PHP', '2025-12-11 01:22:32'),
(226, 2, 'lab02', 'lab2_4.php', 'lab2/lab2_4.php', 'PHP', '2025-12-11 01:22:32'),
(227, 2, 'lab02', 'lab2_5.php', 'lab2/lab2_5.php', 'PHP', '2025-12-11 01:22:32'),
(228, 2, 'lab02', 'lab2_5a.php', 'lab2/lab2_5a.php', 'PHP', '2025-12-11 01:22:32'),
(229, 2, 'lab02', 'lab2_5b.php', 'lab2/lab2_5b.php', 'PHP', '2025-12-11 01:22:32'),
(230, 2, 'lab02', 'lab2_6.php', 'lab2/lab2_6.php', 'PHP', '2025-12-11 01:22:32'),
(231, 2, 'lab02', 'lab5_1.php', 'lab2/lab5_1.php', 'PHP', '2025-12-11 01:22:32'),
(232, 2, 'lab02', 'lab5_2.php', 'lab2/lab5_2.php', 'PHP', '2025-12-11 01:22:32'),
(233, 2, 'lab02', 'lab5_3.php', 'lab2/lab5_3.php', 'PHP', '2025-12-11 01:22:32'),
(234, 2, 'lab02', 'sosanh.php', 'lab2/sosanh.php', 'PHP', '2025-12-11 01:22:32'),
(235, 2, 'lab02', 'vd4_5.php', 'lab2/vd4_5.php', 'PHP', '2025-12-11 01:22:32'),
(236, 2, 'lab02', 'vd4_6.php', 'lab2/vd4_6.php', 'PHP', '2025-12-11 01:22:32'),
(237, 2, 'lab02', 'vd4_6b.php', 'lab2/vd4_6b.php', 'PHP', '2025-12-11 01:22:32'),
(238, 2, 'lab02', 'vd4_7.php', 'lab2/vd4_7.php', 'PHP', '2025-12-11 01:22:32'),
(239, 2, 'lab02', 'vd4_7b.php', 'lab2/vd4_7b.php', 'PHP', '2025-12-11 01:22:32'),
(240, 3, 'lab03', 'function.php', 'lab3/function.php', 'PHP', '2025-12-11 01:25:09'),
(261, 4, 'lab04', 'lab4_1.php', 'lab4/lab4_1.php', 'PHP', '2025-12-11 01:39:20'),
(314, 5, 'lab5', 'lab5_4/module/info/index.php', 'lab5/lab5_4/module/info/index.php', 'PHP', '2025-12-11 02:17:55'),
(313, 5, 'lab5', 'lab5_4/module/info/gioithieu.php', 'lab5/lab5_4/module/info/gioithieu.php', 'PHP', '2025-12-11 02:17:55'),
(312, 5, 'lab5', 'lab5_4/module/product/spmoi.php', 'lab5/lab5_4/module/product/spmoi.php', 'PHP', '2025-12-11 02:17:55'),
(311, 5, 'lab5', 'lab5_4/module/product/spbc.php', 'lab5/lab5_4/module/product/spbc.php', 'PHP', '2025-12-11 02:17:55'),
(310, 5, 'lab5', 'lab5_4/module/product/search.php', 'lab5/lab5_4/module/product/search.php', 'PHP', '2025-12-11 02:17:55'),
(309, 5, 'lab5', 'lab5_4/module/product/product_press.php', 'lab5/lab5_4/module/product/product_press.php', 'PHP', '2025-12-11 02:17:55'),
(308, 5, 'lab5', 'lab5_4/module/product/product_catalog.php', 'lab5/lab5_4/module/product/product_catalog.php', 'PHP', '2025-12-11 02:17:55'),
(307, 5, 'lab5', 'lab5_4/module/product/index.php', 'lab5/lab5_4/module/product/index.php', 'PHP', '2025-12-11 02:17:55'),
(306, 5, 'lab5', 'lab5_4/module/index.php', 'lab5/lab5_4/module/index.php', 'PHP', '2025-12-11 02:17:55'),
(305, 5, 'lab5', 'lab5_4/mod.php', 'lab5/lab5_4/mod.php', 'PHP', '2025-12-11 02:17:55'),
(304, 5, 'lab5', 'lab5_4/index.php', 'lab5/lab5_4/index.php', 'PHP', '2025-12-11 02:17:55'),
(303, 5, 'lab5', 'lab5_3/image/chan dung dai tuong copy.jpg', 'lab5/lab5_3/image/chan dung dai tuong copy.jpg', 'JPG', '2025-12-11 02:17:55'),
(302, 5, 'lab5', 'lab5_3/2.php', 'lab5/lab5_3/2.php', 'PHP', '2025-12-11 02:17:55'),
(301, 5, 'lab5', 'lab5_3/1.php', 'lab5/lab5_3/1.php', 'PHP', '2025-12-11 02:17:55'),
(300, 5, 'lab5', 'lab5_4_2.php', 'lab5/lab5_4_2.php', 'PHP', '2025-12-11 02:17:55'),
(299, 5, 'lab5', 'lab5_2.php', 'lab5/lab5_2.php', 'PHP', '2025-12-11 02:17:55'),
(298, 5, 'lab5', 'lab5_1.php', 'lab5/lab5_1.php', 'PHP', '2025-12-11 02:17:55'),
(315, 5, 'lab5', 'lab5_4/module/info/lienhe.php', 'lab5/lab5_4/module/info/lienhe.php', 'PHP', '2025-12-11 02:17:55'),
(316, 5, 'lab5', 'lab5_4/module/info/trangchu.php', 'lab5/lab5_4/module/info/trangchu.php', 'PHP', '2025-12-11 02:17:55'),
(317, 5, 'lab5', 'lab5_4/include/function.php', 'lab5/lab5_4/include/function.php', 'PHP', '2025-12-11 02:17:55'),
(318, 5, 'lab5', 'lab5_4/include/header.php', 'lab5/lab5_4/include/header.php', 'PHP', '2025-12-11 02:17:55'),
(319, 5, 'lab5', 'lab5_4/include/left.php', 'lab5/lab5_4/include/left.php', 'PHP', '2025-12-11 02:17:55'),
(320, 5, 'lab5', 'lab5_4/css/style.css', 'lab5/lab5_4/css/style.css', 'CSS', '2025-12-11 02:17:55'),
(321, 5, 'lab5', 'lab5_4/admin/index.php', 'lab5/lab5_4/admin/index.php', 'PHP', '2025-12-11 02:17:55'),
(322, 5, 'lab5', 'lab5_4/admin/css/style.css', 'lab5/lab5_4/admin/css/style.css', 'CSS', '2025-12-11 02:17:55'),
(323, 6, 'lab6', 'lab06_1.php', 'lab6/lab06_1.php', 'PHP', '2025-12-11 02:19:39'),
(324, 6, 'lab6', 'lab06_2.php', 'lab6/lab06_2.php', 'PHP', '2025-12-11 02:19:39'),
(325, 6, 'lab6', 'lab06_3.php', 'lab6/lab06_3.php', 'PHP', '2025-12-11 02:19:39'),
(326, 6, 'lab6', 'lab06_4.1.php', 'lab6/lab06_4.1.php', 'PHP', '2025-12-11 02:19:39'),
(327, 6, 'lab6', 'lab6_4.2.php', 'lab6/lab6_4.2.php', 'PHP', '2025-12-11 02:19:39'),
(328, 6, 'lab6', 'lab6_4.3c.php', 'lab6/lab6_4.3c.php', 'PHP', '2025-12-11 02:19:39'),
(329, 6, 'lab6', 'lab6_4.3d.php', 'lab6/lab6_4.3d.php', 'PHP', '2025-12-11 02:19:39'),
(330, 6, 'lab6', 'lab6_5.1a.php', 'lab6/lab6_5.1a.php', 'PHP', '2025-12-11 02:19:39'),
(331, 6, 'lab6', 'lab6_5.1b.php', 'lab6/lab6_5.1b.php', 'PHP', '2025-12-11 02:19:39'),
(332, 6, 'lab6', 'lab6_5.2a.php', 'lab6/lab6_5.2a.php', 'PHP', '2025-12-11 02:19:39'),
(333, 6, 'lab6', 'lab6_5.2b.php', 'lab6/lab6_5.2b.php', 'PHP', '2025-12-11 02:19:39'),
(334, 6, 'lab6', 'lab6_5_2c.php', 'lab6/lab6_5_2c.php', 'PHP', '2025-12-11 02:35:31'),
(335, 1, 'lab01', 'a.php', 'lab1/a.php', 'PHP', '2025-12-11 02:38:50'),
(336, 1, 'lab01', 'example01.php', 'lab1/example01.php', 'PHP', '2025-12-11 02:38:50'),
(337, 1, 'lab01', 'lab1_3.php', 'lab1/lab1_3.php', 'PHP', '2025-12-11 02:38:50'),
(338, 1, 'lab01', 'lab1_4.php', 'lab1/lab1_4.php', 'PHP', '2025-12-11 02:38:50'),
(339, 1, 'lab01', 'php.php', 'lab1/php.php', 'PHP', '2025-12-11 02:38:50'),
(340, 1, 'lab01', 'vi_du_04/example01.php', 'lab1/vi_du_04/example01.php', 'PHP', '2025-12-11 02:38:50'),
(341, 1, 'lab01', 'vi_du_04/Error/404.html', 'lab1/vi_du_04/Error/404.html', 'HTML', '2025-12-11 02:38:50'),
(373, 8, 'lab8', 'lab8_2_2.php', 'lab8/lab8_2_2.php', 'PHP', '2025-12-11 17:08:21'),
(374, 8, 'lab8', 'lab8_3_2.php', 'lab8/lab8_3_2.php', 'PHP', '2025-12-11 17:08:21'),
(375, 8, 'lab8', 'Book.php', 'lab8/Book.php', 'PHP', '2025-12-11 17:08:21'),
(376, 8, 'lab8', 'Db.php', 'lab8/Db.php', 'PHP', '2025-12-11 17:08:21'),
(377, 8, 'lab8', 'lab8_2_1.php', 'lab8/lab8_2_1.php', 'PHP', '2025-12-11 17:08:21'),
(378, 8, 'lab8', 'lab8_3.php', 'lab8/lab8_3.php', 'PHP', '2025-12-11 17:08:21'),
(379, 8, 'lab8', 'lab8_2.php', 'lab8/lab8_2.php', 'PHP', '2025-12-11 17:08:21'),
(380, 8, 'lab8', 'lab8_3_1.php', 'lab8/lab8_3_1.php', 'PHP', '2025-12-11 17:08:21'),
(381, 8, 'lab8', 'lab8_1.php', 'lab8/lab8_1.php', 'PHP', '2025-12-11 17:08:21'),
(382, 8, 'lab8', 'lab8_31.php', 'lab8/lab8_31.php', 'PHP', '2025-12-11 17:08:21'),
(383, 8, 'lab8', 'database/bookstore.png', 'lab8/database/bookstore.png', 'PNG', '2025-12-11 17:08:21'),
(384, 8, 'lab8', 'database/bookstore.sql', 'lab8/database/bookstore.sql', 'SQL', '2025-12-11 17:08:21'),
(385, 8, 'lab8', 'database/bookstore_vn.png', 'lab8/database/bookstore_vn.png', 'PNG', '2025-12-11 17:08:21'),
(386, 8, 'lab8', 'database/bookstore_vn.sql', 'lab8/database/bookstore_vn.sql', 'SQL', '2025-12-11 17:08:21'),
(387, 8, 'lab8', 'lab8_4/index.php', 'lab8/lab8_4/index.php', 'PHP', '2025-12-11 17:08:21'),
(388, 8, 'lab8', 'lab8_4/include/function.php', 'lab8/lab8_4/include/function.php', 'PHP', '2025-12-11 17:08:21'),
(389, 8, 'lab8', 'lab8_4/image/book/20131025144855-4.jpg', 'lab8/lab8_4/image/book/20131025144855-4.jpg', 'JPG', '2025-12-11 17:08:21'),
(390, 8, 'lab8', 'lab8_4/image/book/Anh banner 1-600x470.jpg', 'lab8/lab8_4/image/book/Anh banner 1-600x470.jpg', 'JPG', '2025-12-11 17:08:21'),
(391, 8, 'lab8', 'lab8_4/image/book/td01.jpg', 'lab8/lab8_4/image/book/td01.jpg', 'JPG', '2025-12-11 17:08:21'),
(392, 8, 'lab8', 'lab8_4/image/book/td02.gif', 'lab8/lab8_4/image/book/td02.gif', 'GIF', '2025-12-11 17:08:21'),
(393, 8, 'lab8', 'lab8_4/image/book/td03.jpg', 'lab8/lab8_4/image/book/td03.jpg', 'JPG', '2025-12-11 17:08:21'),
(394, 8, 'lab8', 'lab8_4/image/book/td04.jpg', 'lab8/lab8_4/image/book/td04.jpg', 'JPG', '2025-12-11 17:08:21'),
(395, 8, 'lab8', 'lab8_4/image/book/td05.jpg', 'lab8/lab8_4/image/book/td05.jpg', 'JPG', '2025-12-11 17:08:21'),
(396, 8, 'lab8', 'lab8_4/image/book/td06.jpg', 'lab8/lab8_4/image/book/td06.jpg', 'JPG', '2025-12-11 17:08:21'),
(397, 8, 'lab8', 'lab8_4/image/book/th01.gif', 'lab8/lab8_4/image/book/th01.gif', 'GIF', '2025-12-11 17:08:21'),
(398, 8, 'lab8', 'lab8_4/image/book/th02.jpg', 'lab8/lab8_4/image/book/th02.jpg', 'JPG', '2025-12-11 17:08:21'),
(399, 8, 'lab8', 'lab8_4/image/book/th03.jpg', 'lab8/lab8_4/image/book/th03.jpg', 'JPG', '2025-12-11 17:08:21'),
(400, 8, 'lab8', 'lab8_4/image/book/th04.jpg', 'lab8/lab8_4/image/book/th04.jpg', 'JPG', '2025-12-11 17:08:21'),
(401, 8, 'lab8', 'lab8_4/image/book/th05.jpg', 'lab8/lab8_4/image/book/th05.jpg', 'JPG', '2025-12-11 17:08:21'),
(402, 8, 'lab8', 'lab8_4/image/book/th06.jpg', 'lab8/lab8_4/image/book/th06.jpg', 'JPG', '2025-12-11 17:08:21'),
(403, 8, 'lab8', 'lab8_4/image/book/th07.jpg', 'lab8/lab8_4/image/book/th07.jpg', 'JPG', '2025-12-11 17:08:21'),
(404, 8, 'lab8', 'lab8_4/image/book/th08.jpg', 'lab8/lab8_4/image/book/th08.jpg', 'JPG', '2025-12-11 17:08:21'),
(405, 8, 'lab8', 'lab8_4/image/book/th09.jpg', 'lab8/lab8_4/image/book/th09.jpg', 'JPG', '2025-12-11 17:08:21'),
(406, 8, 'lab8', 'lab8_4/image/book/th10.jpg', 'lab8/lab8_4/image/book/th10.jpg', 'JPG', '2025-12-11 17:08:21'),
(407, 8, 'lab8', 'lab8_4/image/book/th11.jpg', 'lab8/lab8_4/image/book/th11.jpg', 'JPG', '2025-12-11 17:08:21'),
(408, 8, 'lab8', 'lab8_4/image/book/th12.jpg', 'lab8/lab8_4/image/book/th12.jpg', 'JPG', '2025-12-11 17:08:21'),
(409, 8, 'lab8', 'lab8_4/image/book/th13.gif', 'lab8/lab8_4/image/book/th13.gif', 'GIF', '2025-12-11 17:08:21'),
(410, 8, 'lab8', 'lab8_4/image/book/th14.jpg', 'lab8/lab8_4/image/book/th14.jpg', 'JPG', '2025-12-11 17:08:21'),
(411, 8, 'lab8', 'lab8_4/image/book/th15.jpg', 'lab8/lab8_4/image/book/th15.jpg', 'JPG', '2025-12-11 17:08:21'),
(412, 8, 'lab8', 'lab8_4/image/book/th16.jpg', 'lab8/lab8_4/image/book/th16.jpg', 'JPG', '2025-12-11 17:08:21'),
(413, 8, 'lab8', 'lab8_4/image/book/th17.jpg', 'lab8/lab8_4/image/book/th17.jpg', 'JPG', '2025-12-11 17:08:21'),
(414, 8, 'lab8', 'lab8_4/image/book/th18.jpg', 'lab8/lab8_4/image/book/th18.jpg', 'JPG', '2025-12-11 17:08:21'),
(415, 8, 'lab8', 'lab8_4/image/book/vh26.jpg', 'lab8/lab8_4/image/book/vh26.jpg', 'JPG', '2025-12-11 17:08:21'),
(416, 8, 'lab8', 'lab8_4/config/config.php', 'lab8/lab8_4/config/config.php', 'PHP', '2025-12-11 17:08:21'),
(417, 8, 'lab8', 'lab8_4/classes/Book.class.php', 'lab8/lab8_4/classes/Book.class.php', 'PHP', '2025-12-11 17:08:21'),
(418, 8, 'lab8', 'lab8_4/classes/Db.class.php', 'lab8/lab8_4/classes/Db.class.php', 'PHP', '2025-12-11 17:08:21'),
(419, 8, 'lab8', 'lab8_5/index.php', 'lab8/lab8_5/index.php', 'PHP', '2025-12-11 17:08:21'),
(420, 8, 'lab8', 'lab8_5/module/news/index.php', 'lab8/lab8_5/module/news/index.php', 'PHP', '2025-12-11 17:08:21'),
(421, 8, 'lab8', 'lab8_5/module/book/home.php', 'lab8/lab8_5/module/book/home.php', 'PHP', '2025-12-11 17:08:21'),
(422, 8, 'lab8', 'lab8_5/module/book/index.php', 'lab8/lab8_5/module/book/index.php', 'PHP', '2025-12-11 17:08:21'),
(423, 8, 'lab8', 'lab8_5/module/book/list.php', 'lab8/lab8_5/module/book/list.php', 'PHP', '2025-12-11 17:08:21'),
(424, 8, 'lab8', 'lab8_5/include/category.php', 'lab8/lab8_5/include/category.php', 'PHP', '2025-12-11 17:08:21'),
(425, 8, 'lab8', 'lab8_5/include/function.php', 'lab8/lab8_5/include/function.php', 'PHP', '2025-12-11 17:08:21'),
(426, 8, 'lab8', 'lab8_5/include/header.php', 'lab8/lab8_5/include/header.php', 'PHP', '2025-12-11 17:08:21'),
(427, 8, 'lab8', 'lab8_5/include/publisher.php', 'lab8/lab8_5/include/publisher.php', 'PHP', '2025-12-11 17:08:21'),
(428, 8, 'lab8', 'lab8_5/image/book/20131025144855-4.jpg', 'lab8/lab8_5/image/book/20131025144855-4.jpg', 'JPG', '2025-12-11 17:08:21'),
(429, 8, 'lab8', 'lab8_5/image/book/Anh banner 1-600x470.jpg', 'lab8/lab8_5/image/book/Anh banner 1-600x470.jpg', 'JPG', '2025-12-11 17:08:21'),
(430, 8, 'lab8', 'lab8_5/image/book/td01.jpg', 'lab8/lab8_5/image/book/td01.jpg', 'JPG', '2025-12-11 17:08:21'),
(431, 8, 'lab8', 'lab8_5/image/book/td02.gif', 'lab8/lab8_5/image/book/td02.gif', 'GIF', '2025-12-11 17:08:21'),
(432, 8, 'lab8', 'lab8_5/image/book/td03.jpg', 'lab8/lab8_5/image/book/td03.jpg', 'JPG', '2025-12-11 17:08:21'),
(433, 8, 'lab8', 'lab8_5/image/book/td04.jpg', 'lab8/lab8_5/image/book/td04.jpg', 'JPG', '2025-12-11 17:08:21'),
(434, 8, 'lab8', 'lab8_5/image/book/td05.jpg', 'lab8/lab8_5/image/book/td05.jpg', 'JPG', '2025-12-11 17:08:21'),
(435, 8, 'lab8', 'lab8_5/image/book/td06.jpg', 'lab8/lab8_5/image/book/td06.jpg', 'JPG', '2025-12-11 17:08:21'),
(436, 8, 'lab8', 'lab8_5/image/book/th01.gif', 'lab8/lab8_5/image/book/th01.gif', 'GIF', '2025-12-11 17:08:21'),
(437, 8, 'lab8', 'lab8_5/image/book/th02.jpg', 'lab8/lab8_5/image/book/th02.jpg', 'JPG', '2025-12-11 17:08:21'),
(438, 8, 'lab8', 'lab8_5/image/book/th03.jpg', 'lab8/lab8_5/image/book/th03.jpg', 'JPG', '2025-12-11 17:08:21'),
(439, 8, 'lab8', 'lab8_5/image/book/th04.jpg', 'lab8/lab8_5/image/book/th04.jpg', 'JPG', '2025-12-11 17:08:21'),
(440, 8, 'lab8', 'lab8_5/image/book/th05.jpg', 'lab8/lab8_5/image/book/th05.jpg', 'JPG', '2025-12-11 17:08:21'),
(441, 8, 'lab8', 'lab8_5/image/book/th06.jpg', 'lab8/lab8_5/image/book/th06.jpg', 'JPG', '2025-12-11 17:08:21'),
(442, 8, 'lab8', 'lab8_5/image/book/th07.jpg', 'lab8/lab8_5/image/book/th07.jpg', 'JPG', '2025-12-11 17:08:21'),
(443, 8, 'lab8', 'lab8_5/image/book/th08.jpg', 'lab8/lab8_5/image/book/th08.jpg', 'JPG', '2025-12-11 17:08:21'),
(444, 8, 'lab8', 'lab8_5/image/book/th09.jpg', 'lab8/lab8_5/image/book/th09.jpg', 'JPG', '2025-12-11 17:08:21'),
(445, 8, 'lab8', 'lab8_5/image/book/th10.jpg', 'lab8/lab8_5/image/book/th10.jpg', 'JPG', '2025-12-11 17:08:21'),
(446, 8, 'lab8', 'lab8_5/image/book/th11.jpg', 'lab8/lab8_5/image/book/th11.jpg', 'JPG', '2025-12-11 17:08:21'),
(447, 8, 'lab8', 'lab8_5/image/book/th12.jpg', 'lab8/lab8_5/image/book/th12.jpg', 'JPG', '2025-12-11 17:08:21'),
(448, 8, 'lab8', 'lab8_5/image/book/th13.gif', 'lab8/lab8_5/image/book/th13.gif', 'GIF', '2025-12-11 17:08:21'),
(449, 8, 'lab8', 'lab8_5/image/book/th14.jpg', 'lab8/lab8_5/image/book/th14.jpg', 'JPG', '2025-12-11 17:08:21'),
(450, 8, 'lab8', 'lab8_5/image/book/th15.jpg', 'lab8/lab8_5/image/book/th15.jpg', 'JPG', '2025-12-11 17:08:21'),
(451, 8, 'lab8', 'lab8_5/image/book/th16.jpg', 'lab8/lab8_5/image/book/th16.jpg', 'JPG', '2025-12-11 17:08:21'),
(452, 8, 'lab8', 'lab8_5/image/book/th17.jpg', 'lab8/lab8_5/image/book/th17.jpg', 'JPG', '2025-12-11 17:08:21'),
(453, 8, 'lab8', 'lab8_5/image/book/th18.jpg', 'lab8/lab8_5/image/book/th18.jpg', 'JPG', '2025-12-11 17:08:21'),
(454, 8, 'lab8', 'lab8_5/image/book/vh26.jpg', 'lab8/lab8_5/image/book/vh26.jpg', 'JPG', '2025-12-11 17:08:21'),
(455, 8, 'lab8', 'lab8_5/css/style.css', 'lab8/lab8_5/css/style.css', 'CSS', '2025-12-11 17:08:21'),
(456, 8, 'lab8', 'lab8_5/config/config.php', 'lab8/lab8_5/config/config.php', 'PHP', '2025-12-11 17:08:21'),
(457, 8, 'lab8', 'lab8_5/classes/Book.class.php', 'lab8/lab8_5/classes/Book.class.php', 'PHP', '2025-12-11 17:08:21'),
(458, 8, 'lab8', 'lab8_5/classes/Cart.class.php', 'lab8/lab8_5/classes/Cart.class.php', 'PHP', '2025-12-11 17:08:21'),
(459, 8, 'lab8', 'lab8_5/classes/Db.class.php', 'lab8/lab8_5/classes/Db.class.php', 'PHP', '2025-12-11 17:08:21'),
(460, 8, 'lab8', 'lab8_5/classes/News.class.php', 'lab8/lab8_5/classes/News.class.php', 'PHP', '2025-12-11 17:08:21'),
(461, 8, 'lab8', 'lab8_5/classes/User.class.php', 'lab8/lab8_5/classes/User.class.php', 'PHP', '2025-12-11 17:08:21'),
(462, 7, 'lab7', 'Lab07_So sánh các Storage Engines.png', 'lab7/Lab07_So sánh các Storage Engines.png', 'PNG', '2025-12-11 19:07:58'),
(463, 7, 'lab7', 'Lab07_So sánh MySQLi và PDO.png', 'lab7/Lab07_So sánh MySQLi và PDO.png', 'PNG', '2025-12-11 19:07:58'),
(464, 7, 'lab7', 'lab7.sql', 'lab7/lab7.sql', 'SQL', '2025-12-11 19:11:45');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `summary`, `content`, `image`, `created_at`) VALUES
(1, 'Chào mừng ra mắt English Mastery', 'Nền tảng học tiếng Anh mới.', 'Nội dung chi tiết...', '1765489454_693b3b2e57804.png', '2025-12-10 21:10:50');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_option` enum('A','B','C','D') NOT NULL,
  `explanation` text DEFAULT NULL,
  `media_type` enum('none','image','audio') DEFAULT 'none',
  `media_url` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `exam_id`, `content`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `explanation`, `media_type`, `media_url`) VALUES
(1, 1, 'The main purpose of the passage is to...', 'Describe a new theory', 'Criticize a methodology', 'Explain a historical event', 'Compare two approaches', 'A', 'The passage focuses on introducing Theory X.', 'none', NULL),
(2, 1, 'What is this animal?', 'pig', 'cow', 'horse', 'monkey', 'A', 'it\'s a pig', 'image', '1765403845_chu-heo-con-dep_111011597.jpeg'),
(3, 2, 'what inside the picture', 'girl', 'boy', 'steam', 'chat', 'C', 'it\'s a steam page', 'image', '1765406973_Screenshot 2025-10-12 000948.png'),
(11, 3, 'what is this sound belongs to?', 'horse', 'dog', 'cat', 'mouse', 'D', 'it\'s sound of mouse', 'audio', '1765487587_tieng_chuot_keu-www_tiengdong_com.mp3');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `total_correct` int(11) NOT NULL,
  `time_taken_seconds` int(11) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `user_id`, `exam_id`, `score`, `total_correct`, `time_taken_seconds`, `completed_at`) VALUES
(13, 6, 1, 20, 2, NULL, '2025-12-11 21:25:47'),
(14, 3, 3, 10, 1, NULL, '2025-12-11 21:39:20'),
(15, 3, 3, 0, 0, NULL, '2025-12-11 22:51:10');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `plain_password` varchar(255) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `avatar` varchar(255) DEFAULT 'default_avatar.png',
  `status` enum('active','blocked') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `plain_password`, `fullname`, `email`, `role`, `avatar`, `status`, `created_at`) VALUES
(10, 'user', '$2y$10$wOlYgUGmhENZE7988YmlXuEegZuFJljnvGBBfswLjS.q11mSKA5Pa', 'password', 'default student', NULL, 'user', 'default_avatar.png', 'active', '2025-12-11 23:19:54'),
(9, 'admin', '$2y$10$7mfH.f6zBFAF44.ix6t3p.F0ZrRjEM0WY0bN3hBf1ba5dYNba5ENu', '123123', 'administrator', NULL, 'admin', 'default_avatar.png', 'active', '2025-12-11 23:19:20'),
(3, 'thi2004', '$2y$10$VMvdONoBqLbKkaPgxuiDcOJTZKbu9tDZh0eSyf6A8fSbF2I0seGpK', '123123', 'Super admin', NULL, 'admin', 'default_avatar.png', 'active', '2025-12-10 22:43:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `lab_files`
--
ALTER TABLE `lab_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lab_files`
--
ALTER TABLE `lab_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=465;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;