-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2025 at 02:08 AM
-- Server version: 10.11.10-MariaDB
-- PHP Version: 7.2.34




--
-- Database: u867331264_a_cabana
--

-- --------------------------------------------------------

--
-- Table structure for table admin
--

CREATE TABLE admin (
  id SERIAL PRIMARY KEY,
  first_name varchar(255) NOT NULL,
  last_name varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  otp INTEGER NOT NULL

--
-- Dumping data for table admin
--

INSERT INTO admin (id, first_name, last_name, email, password, otp) VALUES
(1, 'admin', 'admin', 'playgod1478@gmail.com', '9218c32cc68d2e1a4b151ffc87bc6e7c', 651392);

-- --------------------------------------------------------

--
-- Table structure for table bookings
--

CREATE TABLE bookings (
  id SERIAL PRIMARY KEY,
  booking_id varchar(255) NOT NULL,
  customer INTEGER NOT NULL,
  slot INTEGER NOT NULL,
  passengers INTEGER NOT NULL,
  boats INTEGER NOT NULL,
  pre_setup enum('bbq','ice-bucket') NOT NULL,
  date date NOT NULL,
  checkin INTEGER NOT NULL,
  checkout INTEGER NOT NULL,
  discount decimal(11,0) NOT NULL,
  total decimal(11,0) NOT NULL,
  stripe_payment_intent_id varchar(255) NOT NULL,
  created timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  status enum('open','closed','cancelled','rescheduled') NOT NULL DEFAULT 'open'

--
-- Dumping data for table bookings
--

INSERT INTO bookings (id, booking_id, customer, slot, passengers, boats, pre_setup, date, checkin, checkout, discount, total, stripe_payment_intent_id, created, status) VALUES
(1, 'BKCB67BDB2A474634', 56, 2, 2, 0, 'ice-bucket', '2025-02-26', 0, 0, 2025, 3048, 'pi_3QwMveBQ2qqyjuoI2VXjvFuX', '2025-02-25 12:08:04.476776', 'open'),
(2, 'BKCB67BDB3CCD1222', 56, 2, 2, 0, 'ice-bucket', '2025-02-26', 0, 0, 2025, 3048, 'pi_3QwN0QBQ2qqyjuoI2kMQ1C6F', '2025-02-25 12:13:00.856663', 'open'),
(3, 'BKCB67BDB5BDBBA0E', 15, 2, 4, 0, 'ice-bucket', '2025-02-26', 1740557197, 0, 2025, 500, 'pi_3QwN8SBQ2qqyjuoI2eLvTS7Q', '2025-02-26 11:43:12.191770', 'cancelled'),
(4, 'BKCB67BDB60BA82C1', 39, 1, 14, 2, 'bbq', '2025-02-27', 1740558945, 1740558959, 2025, 1092, 'pi_3QwN9iBQ2qqyjuoI0FXoz7Uh', '2025-02-26 09:29:48.301896', 'closed'),
(5, 'BKCB67BDB78696831', 23, 1, 11, 2, 'ice-bucket', '2025-03-01', 0, 0, 2025, 1108, 'pi_3QwNFoBQ2qqyjuoI2ujlt1nc', '2025-02-26 09:30:26.629352', 'cancelled'),
(6, 'BKCB67BEAE7DB0B31', 23, 1, 14, 2, 'bbq', '2025-02-27', 1740556047, 1740564807, 2, 6488, 'pi_3QwdhXBQ2qqyjuoI0ASV0ano', '2025-02-26 10:13:27.473646', 'open'),
(7, 'BKCB67BEAF5A18986', 15, 1, 6, 1, 'ice-bucket', '2025-02-27', 1740562936, 1740565137, 12, 5292, 'pi_3Qwdl6BQ2qqyjuoI12tVUSRx', '2025-02-26 10:18:57.540592', 'open'),
(8, 'BKCB67BEB251EED7C', 12, 1, 14, 2, 'ice-bucket', '2025-02-27', 1740560255, 1740564796, 28, 5042, 'pi_3QwdxMBQ2qqyjuoI1mE1zi97', '2025-02-26 10:13:16.249481', 'open'),
(9, 'BKCB67BEB53615C82', 39, 1, 13, 2, 'ice-bucket', '2025-02-27', 1740562786, 1740564793, 26, 1364, 'pi_3Qwe9IBQ2qqyjuoI25bOSwMj', '2025-03-05 08:59:31.312030', 'cancelled'),
(10, 'BKCB67BEDA7CCB906', 12, 1, 15, 2, 'bbq', '2025-02-27', 1740563076, 1740570991, 30, 1300, 'pi_3QwgdDBQ2qqyjuoI2guvQVBl', '2025-02-26 11:56:31.712402', 'closed'),
(11, 'BKCB67BEDB2DD834B', 12, 1, 15, 2, 'bbq', '2025-02-27', 1740563120, 1740564787, 30, 1300, 'pi_3Qwgg3BQ2qqyjuoI0rK1DHFk', '2025-02-26 11:43:01.275742', 'cancelled'),
(12, 'BKCB67BEDBED98E5A', 12, 1, 15, 2, 'bbq', '2025-02-27', 1740564817, 1740565135, 30, 1300, 'pi_3Qwgj9BQ2qqyjuoI2cgC7rP9', '2025-02-26 11:01:14.409813', 'cancelled'),
(13, 'BKCB67BEDD10CCEF2', 39, 1, 11, 2, 'ice-bucket', '2025-02-27', 1740562708, 1740564784, 22, 288, 'pi_3QwgnqBQ2qqyjuoI00kAGbo0', '2025-02-26 11:38:23.573652', 'cancelled'),
(14, 'BKCB67BF07AF91C78', 60, 1, 17, 2, 'ice-bucket', '2025-02-27', 1740575482, 1740577313, 34, 1024, 'pi_3QwjdqBQ2qqyjuoI1HF0d2Wr', '2025-02-26 13:41:53.381427', 'closed'),
(15, 'BKCB67BF0CA1B639D', 23, 1, 11, 2, 'ice-bucket', '2025-02-28', 1740639181, 1740642608, 22, 226, 'pi_3QwjyFBQ2qqyjuoI1GCnhVu2', '2025-02-27 07:50:08.735037', 'closed');

-- --------------------------------------------------------

--
-- Table structure for table coupons
--

CREATE TABLE coupons (
  id SERIAL PRIMARY KEY,
  code varchar(50) NOT NULL,
  email varchar(255) NOT NULL,
  user_id INTEGER DEFAULT NULL,
  discount_type enum('fixed','percentage') NOT NULL,
  discount_value decimal(10,2) NOT NULL,
  valid_from date NOT NULL,
  valid_to date NOT NULL,
  usage_limit INTEGER NOT NULL DEFAULT 1,
  total_used INTEGER NOT NULL,
  limit_per_user INTEGER NOT NULL,
  type enum('general','personal') NOT NULL DEFAULT 'general',
  created_at timestamp NULL DEFAULT current_timestamp()

--
-- Dumping data for table coupons
--

INSERT INTO coupons (id, code, email, user_id, discount_type, discount_value, valid_from, valid_to, usage_limit, total_used, limit_per_user, type, created_at) VALUES
(3, 'coupon155', '', 0, 'fixed', 55.00, '2025-02-21', '2025-02-28', 55, 0, 0, 'general', '2025-02-21 13:40:47'),
(4, 'coupon1553', '', 0, 'fixed', 55.00, '2025-02-21', '2025-02-28', 55, 0, 0, 'general', '2025-02-21 13:41:26'),
(5, 'coupon1588', '', 0, 'fixed', 22.00, '2025-02-27', '2025-02-28', 55, 0, 0, 'general', '2025-02-24 06:56:04'),
(6, 'coupon15888', '', 0, 'fixed', 20.00, '2025-03-04', '2025-03-28', 55, 0, 0, 'general', '2025-02-25 04:40:22'),
(16, 'CABANA5', '', NULL, 'percentage', 5.00, '2025-02-26', '2025-02-28', 30, 0, 1, 'general', '2025-02-26 12:15:36'),
(17, 'CABANA25', '', NULL, 'percentage', 25.00, '2025-02-26', '2025-03-13', 25, 0, 0, 'general', '2025-02-26 12:16:01'),
(18, 'CABANA10', '', NULL, 'percentage', 5.00, '2025-02-26', '2025-02-28', 30, 0, 1, 'general', '2025-02-26 12:17:15'),
(19, 'CABANA11', '', NULL, 'percentage', 5.00, '2025-02-26', '2025-02-28', 30, 0, 1, 'general', '2025-02-26 12:17:57'),
(20, 'CABANA12', '', NULL, 'percentage', 5.00, '2025-02-26', '2025-02-28', 30, 0, 1, 'general', '2025-02-26 12:19:08'),
(21, 'CABANA13', '', NULL, 'percentage', 5.00, '2025-02-26', '2025-02-28', 30, 0, 1, 'general', '2025-02-26 12:19:31'),
(22, 'CABANA255', '', NULL, 'percentage', 25.00, '2025-02-26', '2025-03-27', 22, 0, 0, 'general', '2025-02-26 12:20:04'),
(23, 'FREE25', '', NULL, 'percentage', 25.00, '2025-02-26', '2025-03-27', 55, 2, 0, 'general', '2025-02-26 12:22:49'),
(24, 'CABANA111', '', NULL, 'percentage', 25.00, '2025-02-27', '2025-03-27', 22, 0, 0, 'general', '2025-02-27 06:46:35'),
(25, 'COUPON1585', '', NULL, 'fixed', 25.00, '2025-02-27', '2025-03-28', 55, 0, 0, 'general', '2025-02-27 06:50:29'),
(26, 'COUPON1555', '', NULL, 'fixed', 55.00, '2025-02-27', '2025-03-28', 22, 0, 0, 'general', '2025-02-27 06:50:51'),
(27, 'FREE256', '', NULL, 'percentage', 5.00, '2025-02-28', '2025-03-28', 55, 0, 0, 'general', '2025-02-27 06:51:31'),
(28, 'PCT15', '', NULL, 'percentage', 15.00, '2025-03-04', '2025-03-31', 1, 0, 0, 'general', '2025-03-04 18:10:25'),
(31, 'FREE252', '', NULL, 'fixed', 55.00, '2025-03-05', '2025-03-06', 22, 0, 2, 'general', '2025-03-05 10:05:29'),
(32, 'CABANA55', '', NULL, 'fixed', 15.00, '2025-03-05', '2025-03-29', 22, 0, 2, 'general', '2025-03-05 11:21:54');

-- --------------------------------------------------------

--
-- Table structure for table coupon_usage
--

CREATE TABLE coupon_usage (
  id SERIAL PRIMARY KEY,
  coupon_id INTEGER NOT NULL,
  email varchar(255) NOT NULL,
  user_id INTEGER DEFAULT NULL,
  phone varchar(255) NOT NULL,
  last_used timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()

--
-- Dumping data for table coupon_usage
--

INSERT INTO coupon_usage (id, coupon_id, email, user_id, phone, last_used) VALUES
(21, 23, 'playgod1478@gmail.com', NULL, '', '2025-02-26 12:23:11'),
(22, 23, 'new@gmail.com', NULL, '', '2025-02-26 12:44:17');

-- --------------------------------------------------------

--
-- Table structure for table menu
--

CREATE TABLE menu (
  id SERIAL PRIMARY KEY,
  name varchar(255) NOT NULL,
  info longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(info)),
  price decimal(10,2) NOT NULL,
  image varchar(255) NOT NULL,
  type varchar(255) NOT NULL,
  created timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)

--
-- Dumping data for table menu
--

INSERT INTO menu (id, name, info, price, image, type, created) VALUES
(21, 'Cabana Food 2', '[\"hfgfg\"]', 777.00, 'e4674677fd56e3f5fb3feefcb5f61c10.jpg', 'drinks', '2025-02-04 10:24:26.046344'),
(22, 'Cabana Food 15', '[\"sdsda\"]', 300.00, 'ae7787d73a149d4ae2bf3013ce1c7942.jpeg', 'drinks', '2025-02-16 12:15:07.074279'),
(24, 'fh', '[\"ghfg\"]', 350.00, 'fe87aefc2eccec9641cf3a297062b434.png', 'food', '2025-02-27 10:39:11.444127'),
(25, 'sas', '[\"dsadsa\"]', 450.00, '55b718821168bee85aa92eabfb04f7bc.png', 'food', '2025-02-14 07:49:08.702784'),
(26, 'test menu', '[\"this is a testing description\",\"ghjgh\"]', 300.00, '8c315ad0eb5d9486457a0310788139a3.png', 'food', '2025-02-17 17:41:27.643479'),
(27, 'asdasd', '[\"dsads\"]', 90.00, 'b52e42130e3000b8f676a768409a35f3.webp', 'drinks', '2025-02-18 09:50:41.547375'),
(28, 'Chef Hat', '[\"United Kingdom and its associated Crown Dependencies and British Overseas Territories and\"]', 999.00, '756a85b5223054b242bd496a75c91b44.png', 'others', '2025-02-23 22:42:32.563076'),
(29, 'ff', '[\"ff\",\"ffvf\"]', 3.54, '6776d3bd02d87ebb1a3309114153d248.png', 'food', '2025-02-24 07:48:54.581577');

-- --------------------------------------------------------

--
-- Table structure for table orders
--

CREATE TABLE orders (
  id SERIAL PRIMARY KEY,
  customer INTEGER NOT NULL,
  booking INTEGER NOT NULL,
  status enum('pending','completed','cancelled') NOT NULL,
  amount INTEGER NOT NULL

--
-- Dumping data for table orders
--

INSERT INTO orders (id, customer, booking, status, amount) VALUES
(1, 56, 1, 'pending', 2898),
(2, 56, 2, 'pending', 2898),
(3, 15, 3, 'pending', 350),
(4, 39, 4, 'pending', 700),
(5, 23, 5, 'pending', 800),
(6, 23, 6, 'pending', 6096),
(7, 15, 7, 'pending', 5124),
(8, 12, 8, 'pending', 4650),
(9, 39, 9, 'pending', 1000),
(10, 12, 10, 'pending', 900),
(11, 12, 11, 'pending', 900),
(12, 12, 12, 'pending', 900),
(13, 60, 14, 'pending', 900);

-- --------------------------------------------------------

--
-- Table structure for table order_items
--

CREATE TABLE order_items (
  id SERIAL PRIMARY KEY,
  order_id INTEGER NOT NULL,
  menu_id INTEGER NOT NULL,
  quantity INTEGER NOT NULL,
  price INTEGER NOT NULL,
  created timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)

--
-- Dumping data for table order_items
--

INSERT INTO order_items (id, order_id, menu_id, quantity, price, created) VALUES
(1, 2, 28, 2, 999, '2025-02-25 12:13:00.856962'),
(2, 2, 25, 2, 450, '2025-02-25 12:13:00.856962'),
(3, 3, 24, 1, 350, '2025-02-25 12:21:17.768901'),
(4, 4, 24, 2, 350, '2025-02-25 12:22:35.689454'),
(5, 5, 24, 1, 350, '2025-02-25 12:28:54.616857'),
(6, 5, 25, 1, 450, '2025-02-25 12:28:54.616857'),
(7, 6, 25, 2, 450, '2025-02-26 06:02:37.724299'),
(8, 6, 22, 4, 300, '2025-02-26 06:02:37.724299'),
(9, 6, 28, 4, 999, '2025-02-26 06:02:37.724299'),
(10, 7, 25, 3, 450, '2025-02-26 06:06:18.101163'),
(11, 7, 28, 3, 999, '2025-02-26 06:06:18.101163'),
(12, 7, 21, 1, 777, '2025-02-26 06:06:18.101163'),
(13, 8, 24, 9, 350, '2025-02-26 06:18:57.978642'),
(14, 8, 22, 5, 300, '2025-02-26 06:18:57.978642'),
(15, 9, 24, 2, 350, '2025-02-26 06:31:18.089915'),
(16, 9, 26, 1, 300, '2025-02-26 06:31:18.089915'),
(17, 10, 25, 2, 450, '2025-02-26 09:10:20.834285'),
(18, 11, 25, 2, 450, '2025-02-26 09:13:17.885879'),
(19, 12, 25, 2, 450, '2025-02-26 09:16:29.626641'),
(20, 13, 25, 2, 450, '2025-02-26 12:23:11.597669');

-- --------------------------------------------------------

--
-- Table structure for table payments
--

CREATE TABLE payments (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL,
  amount decimal(10,2) NOT NULL,
  currency varchar(10) DEFAULT 'USD',
  payment_status enum('pending','success','failed') DEFAULT 'pending',
  stripe_payment_intent_id varchar(255) NOT NULL,
  stripe_payment_method varchar(255) DEFAULT NULL,
  stripe_receipt_url text DEFAULT NULL,
  created_at timestamp NULL DEFAULT current_timestamp(),
  updated_at timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()

--
-- Dumping data for table payments
--

INSERT INTO payments (id, user_id, amount, currency, payment_status, stripe_payment_intent_id, stripe_payment_method, stripe_receipt_url, created_at, updated_at) VALUES
(1, 56, 3048.00, 'USD', 'pending', 'pi_3QwMveBQ2qqyjuoI2VXjvFuX', NULL, NULL, '2025-02-25 12:08:02', '2025-02-25 12:08:02'),
(2, 56, 3048.00, 'USD', 'pending', 'pi_3QwN0QBQ2qqyjuoI2kMQ1C6F', NULL, NULL, '2025-02-25 12:12:58', '2025-02-25 12:12:58'),
(3, 15, 500.00, 'USD', 'pending', 'pi_3QwN8SBQ2qqyjuoI2eLvTS7Q', NULL, NULL, '2025-02-25 12:21:16', '2025-02-25 12:21:16'),
(4, 39, 1092.00, 'USD', 'pending', 'pi_3QwN9iBQ2qqyjuoI0FXoz7Uh', NULL, NULL, '2025-02-25 12:22:34', '2025-02-25 12:22:34'),
(5, 23, 1108.00, 'USD', 'pending', 'pi_3QwNFoBQ2qqyjuoI2ujlt1nc', NULL, NULL, '2025-02-25 12:28:52', '2025-02-25 12:28:52'),
(6, 12, 3054.00, 'USD', 'pending', 'pi_3QwdUJBQ2qqyjuoI0o99814N', NULL, NULL, '2025-02-26 05:48:55', '2025-02-26 05:48:55'),
(7, 12, 3054.00, 'USD', 'pending', 'pi_3QwdUYBQ2qqyjuoI15K1Eo5L', NULL, NULL, '2025-02-26 05:49:10', '2025-02-26 05:49:10'),
(8, 23, 6488.00, 'USD', 'pending', 'pi_3QwdeuBQ2qqyjuoI0PlEw9KX', NULL, NULL, '2025-02-26 05:59:52', '2025-02-26 05:59:52'),
(9, 23, 6488.00, 'USD', 'pending', 'pi_3QwdfOBQ2qqyjuoI2fDkicVW', NULL, NULL, '2025-02-26 06:00:22', '2025-02-26 06:00:22'),
(10, 23, 6488.00, 'USD', 'pending', 'pi_3Qwdg4BQ2qqyjuoI2ZaNCcHI', NULL, NULL, '2025-02-26 06:01:05', '2025-02-26 06:01:05'),
(11, 23, 6488.00, 'USD', 'pending', 'pi_3QwdgZBQ2qqyjuoI2dRYJADq', NULL, NULL, '2025-02-26 06:01:36', '2025-02-26 06:01:36'),
(12, 23, 6488.00, 'USD', 'pending', 'pi_3QwdhXBQ2qqyjuoI0ASV0ano', NULL, NULL, '2025-02-26 06:02:35', '2025-02-26 06:02:35'),
(13, 15, 5292.00, 'USD', 'pending', 'pi_3Qwdl6BQ2qqyjuoI12tVUSRx', NULL, NULL, '2025-02-26 06:06:16', '2025-02-26 06:06:16'),
(14, 12, 5042.00, 'USD', 'pending', 'pi_3QwdxMBQ2qqyjuoI1mE1zi97', NULL, NULL, '2025-02-26 06:18:56', '2025-02-26 06:18:56'),
(15, 39, 1364.00, 'USD', '', 'pi_3Qwe9IBQ2qqyjuoI25bOSwMj', NULL, NULL, '2025-02-26 06:31:16', '2025-03-05 08:59:33'),
(16, 58, 504.00, 'USD', 'pending', 'pi_3Qwf8aBQ2qqyjuoI0iLud9wm', NULL, NULL, '2025-02-26 07:34:36', '2025-02-26 07:34:36'),
(17, 12, 1300.00, 'USD', 'pending', 'pi_3QwgdDBQ2qqyjuoI2guvQVBl', NULL, NULL, '2025-02-26 09:10:19', '2025-02-26 09:10:19'),
(18, 12, 1300.00, 'USD', 'pending', 'pi_3Qwgg3BQ2qqyjuoI0rK1DHFk', NULL, NULL, '2025-02-26 09:13:16', '2025-02-26 09:13:16'),
(19, 12, 1300.00, 'USD', '', 'pi_3Qwgj9BQ2qqyjuoI2cgC7rP9', NULL, NULL, '2025-02-26 09:16:27', '2025-02-26 12:24:59'),
(20, 39, 288.00, 'USD', 'pending', 'pi_3QwgnqBQ2qqyjuoI00kAGbo0', NULL, NULL, '2025-02-26 09:21:19', '2025-02-26 09:21:19'),
(21, 60, 1023.50, 'USD', 'pending', 'pi_3QwjdqBQ2qqyjuoI1HF0d2Wr', NULL, NULL, '2025-02-26 12:23:10', '2025-02-26 12:23:10'),
(22, 23, 225.50, 'USD', '', 'pi_3QwjyFBQ2qqyjuoI1GCnhVu2', NULL, NULL, '2025-02-26 12:44:16', '2025-02-26 13:26:08');

-- --------------------------------------------------------

--
-- Table structure for table pricing
--

CREATE TABLE pricing (
  id SERIAL PRIMARY KEY,
  date date NOT NULL,
  base_price_weekday decimal(10,2) NOT NULL,
  base_price_weekend decimal(10,2) NOT NULL,
  discount_per_passenger INTEGER NOT NULL,
  price_per_person decimal(10,2) NOT NULL,
  slots longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(slots)),
  status tinyint(1) NOT NULL DEFAULT 1

--
-- Dumping data for table pricing
--

INSERT INTO pricing (id, date, base_price_weekday, base_price_weekend, discount_per_passenger, price_per_person, slots, status) VALUES
(1, '2025-02-26', 150.00, 60.00, 2, 30.00, '[{\"id\":1,\"name\":\"Evening Slot\",\"start\":\"15:00:00.000000\",\"end\":\"17:30:00.000000\",\"duration\":\"2h 30m 0s\",\"boats\":\"5\"},{\"id\":2,\"name\":\"Night Slote\",\"start\":\"19:00:00.000000\",\"end\":\"21:30:00.000000\",\"duration\":\"2h 30m 0s\",\"boats\":\"55\"}]', 1),
(2, '2025-02-27', 150.00, 60.00, 2, 30.00, '[{\"id\":1,\"name\":\"Evening Slot\",\"start\":\"15:00:00.000000\",\"end\":\"17:30:00.000000\",\"duration\":\"2h 30m 0s\",\"boats\":\"55\"}]', 1),
(3, '2025-02-28', 150.00, 60.00, 2, 30.00, '[{\"id\":1,\"name\":\"Evening Slot\",\"start\":\"15:00:00.000000\",\"end\":\"16:30:00.000000\",\"duration\":\"1h 30m 0s\",\"boats\":\"55\"}]', 1),
(4, '2025-03-01', 150.00, 60.00, 2, 30.00, '[{\"id\":1,\"name\":\"Late Morning Slot\",\"start\":\"17:18:00.000000\",\"end\":\"19:19:00.000000\",\"duration\":\"2h 1m 0s\",\"boats\":\"2\"}]', 1),
(5, '2025-03-02', 150.00, 60.00, 2, 30.00, '[{\"id\":1,\"name\":\"Evening Slot\",\"start\":\"17:19:00.000000\",\"end\":\"18:19:00.000000\",\"duration\":\"1h 0m 0s\",\"boats\":\"2\"}]', 1),
(6, '2025-03-03', 150.00, 60.00, 2, 30.00, '[{\"id\":1,\"name\":\"Evening Slot\",\"start\":\"18:19:00.000000\",\"end\":\"19:19:00.000000\",\"duration\":\"1h 0m 0s\",\"boats\":\"5\"}]', 1),
(7, '2025-03-04', 150.00, 60.00, 2, 30.00, '[{\"id\":1,\"name\":\"Evening Slot\",\"start\":\"18:19:00.000000\",\"end\":\"20:19:00.000000\",\"duration\":\"2h 0m 0s\",\"boats\":\"2\"}]', 1),
(8, '2025-03-05', 155.00, 155.00, 3, 40.00, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Morning\\\",\\\"start\\\":\\\"08:00\\\",\\\"end\\\":\\\"10:00\\\",\\\"duration\\\":\\\"2h 0m 0s\\\",\\\"boats\\\":\\\"10\\\"},{\\\"id\\\":2,\\\"name\\\":\\\"Noon\\\",\\\"start\\\":\\\"12:00\\\",\\\"end\\\":\\\"14:00\\\",\\\"duration\\\":\\\"2h 0m 0s\\\",\\\"boats\\\":\\\"10\\\"},{\\\"id\\\":3,\\\"name\\\":\\\"Evening\\\",\\\"start\\\":\\\"19:00\\\",\\\"end\\\":\\\"21:00\\\",\\\"duration\\\":\\\"2h 0m 0s\\\",\\\"boats\\\":\\\"15\\\"}]\"', 1),
(9, '2025-03-06', 150.00, 150.00, 2, 35.00, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Morning\\\",\\\"start\\\":\\\"15:36\\\",\\\"end\\\":\\\"16:36\\\",\\\"duration\\\":\\\"1h 0m 0s\\\",\\\"boats\\\":\\\"2\\\"}]\"', 1),
(10, '2025-03-07', 150.00, 150.00, 2, 31.00, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Morning\\\",\\\"start\\\":\\\"08:00\\\",\\\"end\\\":\\\"10:00\\\",\\\"duration\\\":\\\"2h 0m 0s\\\",\\\"boats\\\":\\\"15\\\"},{\\\"id\\\":2,\\\"name\\\":\\\"Late Morning\\\",\\\"start\\\":\\\"10:00\\\",\\\"end\\\":\\\"12:00\\\",\\\"duration\\\":\\\"2h 0m 0s\\\",\\\"boats\\\":\\\"15\\\"},{\\\"id\\\":3,\\\"name\\\":\\\"Afternoon\\\",\\\"start\\\":\\\"12:00\\\",\\\"end\\\":\\\"14:00\\\",\\\"duration\\\":\\\"2h 0m 0s\\\",\\\"boats\\\":\\\"15\\\"}]\"', 1),
(11, '2025-03-08', 150.00, 150.00, 2, 30.00, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Morning Slot\\\",\\\"start\\\":\\\"10:20:00.000000\\\",\\\"end\\\":\\\"12:20:00.000000\\\",\\\"duration\\\":\\\"2h 0m 0s\\\",\\\"boats\\\":\\\"5\\\"}]\"', 1),
(12, '2025-03-09', 150.00, 60.00, 2, 30.00, '[{\"id\":1,\"name\":\"Late Morning Slot\",\"start\":\"18:21:00.000000\",\"end\":\"19:21:00.000000\",\"duration\":\"1h 0m 0s\",\"boats\":\"22\"}]', 1),
(13, '2025-03-10', 150.00, 60.00, 2, 30.00, '[{\"id\":1,\"name\":\"Evening Slot\",\"start\":\"18:21:00.000000\",\"end\":\"19:21:00.000000\",\"duration\":\"1h 0m 0s\",\"boats\":\"2\"}]', 1),
(14, '2025-03-11', 150.00, 60.00, 2, 30.00, '[{\"id\":1,\"name\":\"Morning Slot\",\"start\":\"17:21:00.000000\",\"end\":\"23:24:00.000000\",\"duration\":\"6h 3m 0s\",\"boats\":\"2\"}]', 1),
(15, '2025-03-12', 150.00, 60.00, 2, 30.00, '[{\"id\":1,\"name\":\"Evening Slot\",\"start\":\"17:21:00.000000\",\"end\":\"19:21:00.000000\",\"duration\":\"2h 0m 0s\",\"boats\":\"2\"}]', 1),
(16, '2025-03-13', 150.00, 150.00, 2, 30.00, '\"[{\\\"name\\\":\\\"Afternoon\\\",\\\"start\\\":\\\"12:00\\\",\\\"end\\\":\\\"14:00\\\",\\\"duration\\\":\\\"2h 0m 0s\\\",\\\"boats\\\":\\\"25\\\",\\\"id\\\":1}]\"', 1),
(17, '2025-03-14', 150.00, 150.00, 2, 30.00, '\"[]\"', 1),
(18, '2025-03-15', 150.00, 150.00, 2, 30.00, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"rs\\\",\\\"start\\\":\\\"14:00\\\",\\\"end\\\":\\\"16:00\\\",\\\"duration\\\":\\\"2h 0m 0s\\\",\\\"boats\\\":\\\"10\\\"}]\"', 1),
(19, '2025-03-16', 150.00, 150.00, 2, 30.00, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Morning Slot\\\",\\\"start\\\":\\\"15:30\\\",\\\"end\\\":\\\"16:30\\\",\\\"duration\\\":\\\"1h 0m 0s\\\",\\\"boats\\\":\\\"22\\\"}]\"', 1),
(20, '2025-03-17', 150.00, 60.00, 2, 30.00, '[]', 1),
(21, '2025-03-18', 150.00, 60.00, 2, 30.00, '[]', 1),
(22, '2025-03-19', 150.00, 60.00, 2, 30.00, '[]', 1),
(23, '2025-03-20', 150.00, 150.00, 2, 30.00, '\"[]\"', 1),
(24, '2025-03-21', 150.00, 60.00, 2, 30.00, '[]', 1),
(25, '2025-03-22', 150.00, 60.00, 2, 30.00, '[]', 1),
(26, '2025-03-23', 150.00, 60.00, 2, 30.00, '[]', 1),
(27, '2025-03-24', 150.00, 60.00, 2, 30.00, '[]', 1),
(28, '2025-03-25', 150.00, 60.00, 2, 30.00, '[]', 1),
(29, '2025-03-26', 160.00, 160.00, 2, 30.00, '\"[]\"', 1),
(30, '2025-03-27', 150.00, 60.00, 2, 30.00, '\"[]\"', 1),
(31, '2025-03-28', 150.00, 60.00, 2, 30.00, '[]', 1),
(32, '2025-03-29', 150.00, 60.00, 2, 30.00, '[]', 1),
(33, '2025-03-30', 150.00, 60.00, 2, 30.00, '[]', 1),
(34, '2025-03-31', 150.00, 60.00, 2, 30.00, '[]', 1),
(35, '2025-04-01', 150.00, 60.00, 2, 30.00, '[]', 1),
(36, '2025-04-02', 150.00, 60.00, 2, 30.00, '[]', 1),
(37, '2025-04-03', 150.00, 60.00, 2, 30.00, '[]', 1),
(38, '2025-04-04', 150.00, 60.00, 2, 30.00, '[]', 1),
(39, '2025-04-05', 150.00, 60.00, 2, 30.00, '[]', 1),
(40, '2025-04-06', 150.00, 60.00, 2, 30.00, '[]', 1),
(41, '2025-04-07', 150.00, 60.00, 2, 30.00, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Morning\\\",\\\"start\\\":\\\"07:00:00.000000\\\",\\\"end\\\":\\\"09:00:00.000000\\\",\\\"duration\\\":\\\"2h 0m 0s\\\",\\\"boats\\\":\\\"10\\\"},{\\\"name\\\":\\\"Late Morning\\\",\\\"start\\\":\\\"09:00:00.000000\\\",\\\"end\\\":\\\"11:00:00.000000\\\",\\\"duration\\\":\\\"2h 0m 0s\\\",\\\"boats\\\":\\\"20\\\"}]\"', 1),
(42, '2025-04-08', 150.00, 60.00, 2, 30.00, '[]', 1),
(43, '2025-04-09', 150.00, 60.00, 2, 30.00, '[]', 1),
(44, '2025-04-10', 150.00, 60.00, 2, 30.00, '[]', 1),
(45, '2025-04-11', 150.00, 60.00, 2, 30.00, '\"[{\\\"name\\\":\\\"Morning\\\",\\\"start\\\":\\\"08:00:00.000000\\\",\\\"end\\\":\\\"10:00:00.000000\\\",\\\"duration\\\":\\\"2h 0m 0s\\\",\\\"boats\\\":\\\"25\\\"}]\"', 1),
(46, '2025-04-30', 130.00, 130.00, 2, 35.00, '\"[]\"', 1),
(47, '2025-05-01', 130.00, 130.00, 2, 20.00, '\"[]\"', 1),
(48, '2025-05-02', 123.00, 123.00, 1, 12.00, '\"[]\"', 1),
(49, '2025-05-03', 150.00, 0.00, 1, 12.00, '[]', 1),
(50, '2025-05-04', 0.00, 0.00, 0, 0.00, '\"[]\"', 0),
(51, '2025-05-05', 0.00, 0.00, 0, 0.00, '[]', 0),
(52, '2025-05-06', 180.00, 0.00, 1, 15.00, '[]', 1),
(53, '2025-05-07', 150.00, 0.00, 1, 30.00, '[]', 1),
(55, '2025-05-08', 150.00, 0.00, 1, 35.00, '[]', 1),
(56, '2025-05-09', 150.00, 0.00, 7, 12.00, '[]', 1),
(58, '2025-05-10', 150.00, 0.00, 5, 35.00, '[]', 1),
(59, '2025-05-11', 150.00, 0.00, 5, 12.00, '[]', 1),
(60, '2025-05-12', 150.00, 0.00, 7, 35.00, '[]', 1),
(61, '2025-05-13', 0.00, 0.00, 0, 0.00, '[]', 0),
(62, '2025-05-14', 130.00, 130.00, 2, 35.00, '\"[]\"', 1),
(63, '2025-05-15', 150.00, 150.00, 1, 35.00, '\"[{\\\"id\\\":1,\\\"name\\\":\\\"Morning\\\",\\\"start\\\":\\\"08:30\\\",\\\"end\\\":\\\"10:30\\\",\\\"duration\\\":\\\"2h 0m 0s\\\",\\\"boats\\\":\\\"5\\\"}]\"', 1),
(64, '2025-04-14', 0.00, 0.00, 0, 0.00, '\"[]\"', 0),
(65, '2025-04-15', 0.00, 0.00, 0, 0.00, '[]', 0),
(66, '2025-04-20', 0.00, 0.00, 0, 0.00, '[]', 0),
(67, '2025-04-22', 0.00, 0.00, 0, 0.00, '[]', 0);

-- --------------------------------------------------------

--
-- Table structure for table queries
--

CREATE TABLE queries (
  id SERIAL PRIMARY KEY,
  name varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  phone varchar(255) NOT NULL,
  message text NOT NULL

--
-- Dumping data for table queries
--

INSERT INTO queries (id, name, email, phone, message) VALUES
(1, 'john doe', 'john@mail.com', '9876543210', 'This is a testing query'),
(2, 'Robin Kirar', 'robinkirar@mail.com', '9342582347', 'Some Query!'),
(3, 'Piyush', 'piyush@mail.com', '87536738434', 'Piyush Pityush'),
(4, 'Abbas', 'abbas@mail.com', '73984567345', 'This is my message!'),
(5, 'Tanish Sharma', 'tanish@digiflex.ai', '896795877345987', 'My message to the world!'),
(6, 'Tanish Sharma', 'tanish@digiflex.ai', '896795877345987', 'My message to the world!'),
(7, 'dfgf', 'pass@gmail.conm', '8484848484848', 'sdd'),
(8, 'ggh', 'pass@gmail.conm', '484848484848484', 'wewe');

-- --------------------------------------------------------

--
-- Table structure for table slots
--

CREATE TABLE slots (
  id SERIAL PRIMARY KEY,
  name varchar(255) NOT NULL,
  start time(6) NOT NULL,
  end time(6) NOT NULL,
  duration INTEGER NOT NULL,
  boats INTEGER NOT NULL,
  status tinyint(1) NOT NULL,
  createdAt timestamp(6) NOT NULL DEFAULT current_timestamp(6)

--
-- Dumping data for table slots
--

INSERT INTO slots (id, name, start, end, duration, boats, status, createdAt) VALUES
(5, 'late evening', '20:00:00.000000', '22:00:00.000000', 120, 1, 1, '2025-01-27 10:26:23.000000'),
(6, 'night', '22:00:00.000000', '00:00:00.000000', 120, 3, 1, '2025-01-27 10:26:23.000000'),
(8, 'Morning Slot', '09:00:00.000000', '12:00:00.000000', 120, 98, 1, '2025-01-31 09:14:50.356442'),
(9, 'Late Morning Slot', '00:00:00.000000', '03:00:00.000000', 3, 5, 1, '2025-02-04 09:53:56.431918'),
(10, 'Morning Slot', '10:00:00.000000', '12:00:00.000000', 2, 5, 1, '2025-02-04 10:34:24.365135'),
(11, 'Hellow', '10:00:00.000000', '13:05:00.000000', 3, 8, 1, '2025-02-04 10:36:32.909403');

-- --------------------------------------------------------

--
-- Table structure for table user
--

CREATE TABLE user (
  id SERIAL PRIMARY KEY,
  first_name varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL,
  last_name varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL,
  email varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL,
  phone varchar(30) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL,
  country varchar(30) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL

--
-- Dumping data for table user
--

INSERT INTO user (id, first_name, last_name, email, phone, country) VALUES
(1, 'abbas', 'newsurname', 'abbas@gmail.com', '8319790378', 'Australia'),
(2, 'John', 'Doe', 'john.doe@example.com', '+1234567890', 'USA'),
(3, 'Jane', 'Smith', 'jane.smith@example.com', '+9876543210', 'Canada'),
(4, 'Alice', 'Johnson', 'alice.johnson@example.com', '+1928374650', 'UK'),
(5, 'Bob', 'Brown', 'bob.brown@example.com', '+5647382910', 'Australia'),
(6, 'Charlie', 'Davis', 'charlie.davis@example.com', '+3456721890', 'India'),
(7, 'Eve', 'Martinez', 'eve.martinez@example.com', '+1112223333', 'Spain'),
(8, 'Frank', 'Garcia', 'frank.garcia@example.com', '+4445556666', 'Mexico'),
(9, 'Grace', 'Lee', 'grace.lee@example.com', '+7778889999', 'China'),
(10, 'Hannah', 'Kim', 'hannah.kim@example.com', '+1002003000', 'South Korea'),
(11, 'Ian', 'Wilson', 'ian.wilson@example.com', '+1233211234', 'Germany'),
(12, 'Robin', 'Kirar', 'robinkirar@mail.com', '6757868746', 'India'),
(13, 'Robin', 'Kirar', 'robinkirar@mail.com', '6757868746', 'India'),
(14, 'robin', 'Kirar', 'robinkirar@mail.com', '6757868746', 'Dominica'),
(15, 'Admin', 'User', 'admin@mail.com', '4398753486', 'Cyprus'),
(16, 'dfs', 'asdasd', 'ajeem@tripocio.com', '4398753486', 'Armenia'),
(17, 'Abbas', 'Bhaiya', 'abbas@mail.com', '3242353246', 'India'),
(18, 'Abbas', 'Bhaiya', 'abbas@mail.com', '3242353246', 'India'),
(19, 'Abbas', 'Bhaiya', 'abbas@mail.com', '3242353246', 'India'),
(20, 'Abbas', 'Bhaiya', 'abbas@mail.com', '3242353246', 'India'),
(21, 'Abbas', 'Bhaiya', 'abbas@mail.com', '3242353246', 'India'),
(22, 'Abbas', 'Bhaiya', 'abbas@mail.com', '3242353246', 'India'),
(23, 'robin', 'asd', 'new@gmail.com', '6757868746', 'Estonia'),
(24, 'robin', 'asd', 'new@gmail.com', '6757868746', 'Belarus'),
(25, 'robin', 'asd', 'new@gmail.com', '6757868746', 'Belarus'),
(26, 'robin', 'asd', 'new@gmail.com', '6757868746', 'Belarus'),
(27, 'robin', 'asd', 'new@gmail.com', '6757868746', 'Belarus'),
(28, 'robin', 'asdasd', 'vendoor1@amazingtours.com', '3242353246', 'Angola'),
(29, 'robin', 'asdasd', 'vendoor1@amazingtours.com', '3242353246', 'Angola'),
(30, 'dfs', 'asdasd', 'admin@mail.com', '3242353246', 'Bahrain'),
(31, 'dfs', 'asdasd', 'admin@mail.com', '3242353246', 'Bahrain'),
(32, 'dfs', 'asdasd', 'admin@mail.com', '3242353246', 'Bahrain'),
(33, 'dfs', 'asdasd', 'admin@mail.com', '3242353246', 'Bahrain'),
(34, 'dfs', 'asd', 'new@gmail.com', '9347534835', 'Australia'),
(35, 'dfs', 'asdasd', 'admin@mail.com', '4398753486', 'Bahrain'),
(36, 'Abbas', 'Kirar', 'new@gmail.com', '9347534835', 'Bangladesh'),
(37, 'robin', 'Kirar', 'vendoor1@amazingtours.com', '9347534835', 'Belarus'),
(38, 'robin', 'asdasd', 'vendoor1@amazingtours.com', '9347534835', 'Belgium'),
(39, 'wow', 'asd', 'wow@mail.com', '6757868746', 'Korea, North'),
(40, 'robin', 'asd', 'wow@mail.com', '6757868746', 'Belarus'),
(41, 'asdasdas', 'asdasdasd', 'asdasdasd@gmail.com', '7856783453465', 'Cuba'),
(42, 'asdasdas', 'asdasdasd', 'asdasdasd@gmail.com', '7856783453465', 'Cuba'),
(43, 'SOme one ', 'lkasdfjlaskd', 'ajeem@tripocio.com', '4398753486', 'Azerbaijan'),
(44, 'Robin', 'Kirar', 'robinkirar@mail.com', '8763524372', 'Latvia'),
(45, 'Robin', 'Kirar', 'rokukazama@gmail.com', '8567546456', 'Armenia'),
(46, 'johnny', 'bravo', 'johnnybravo@yahoo.com', '45345345', 'United Kingdom'),
(47, 'johnny', 'bravo', 'johnnybravo@yahoo.com', '45345345', 'United Kingdom'),
(48, 'johnny', 'bravo', 'johnnybravo@yahoo.com', '45345345', 'United Kingdom'),
(49, 'Robin', 'Kirar', 'robinkirar@mail.com', '897324823674', 'India'),
(50, 'gary', 'andrews', 'gary@gmail.com', '7896541230', 'Bahamas'),
(51, 'gary', 'andrews', 'gary@gmail.com', '7896541230', 'Bahamas'),
(52, 'james', 'brooke', 'james@gmail.com', '789561302', 'Afghanistan'),
(53, 'Someone', 'New Two', 'someonenew@mail.com', '5486599954', 'Azerbaijan'),
(54, 'jon', 'doe', 'jondoe@gmail.com', '7894561230', 'Afghanistan'),
(55, 'hh', 'hh', 'pass@gmail.conm', '888888888888888', 'India'),
(56, 'Piyush', 'Vyas', 'piyush@mail.com', '9843853445', 'India'),
(57, 'Some', 'New', 'somenew@mail.com', '856754456', 'Sri Lanka'),
(58, 'John', 'Due', 'abbasazhar229@gmail.com', '1234567890', 'Benin'),
(59, 'Asd', 'Dsa', 'asd@mail.com', '5486599954', 'Bahamas'),
(60, 'Jivan', 'Babu', 'playgod1478@gmail.com', '585858585858858', 'Belarus');

-- --------------------------------------------------------

--
-- Table structure for table user_payment_details
--

CREATE TABLE user_payment_details (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL,
  stripe_customer_id varchar(255) NOT NULL,
  stripe_payment_method_id varchar(255) NOT NULL,
  last4 varchar(4) NOT NULL,
  brand varchar(50) NOT NULL,
  exp_month INTEGER NOT NULL,
  exp_year INTEGER NOT NULL,
  created_at timestamp NULL DEFAULT current_timestamp(),
  updated_at timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()

--
-- Indexes for dumped tables
--

--
-- Indexes for table admin
--
ALTER TABLE admin
  ADD PRIMARY KEY (id);

--
-- Indexes for table bookings
--
ALTER TABLE bookings
  ADD PRIMARY KEY (id);

--
-- Indexes for table coupons
--
ALTER TABLE coupons
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY code (code),
  ADD KEY user_id (user_id);

--
-- Indexes for table coupon_usage
--
ALTER TABLE coupon_usage
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY coupon_id (coupon_id,user_id),
  ADD KEY user_id (user_id);

--
-- Indexes for table menu
--
ALTER TABLE menu
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY name (name);

--
-- Indexes for table orders
--
ALTER TABLE orders
  ADD PRIMARY KEY (id);

--
-- Indexes for table order_items
--
ALTER TABLE order_items
  ADD PRIMARY KEY (id);

--
-- Indexes for table payments
--
ALTER TABLE payments
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY stripe_payment_intent_id (stripe_payment_intent_id),
  ADD KEY user_id (user_id);

--
-- Indexes for table pricing
--
ALTER TABLE pricing
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY date (date);

--
-- Indexes for table queries
--
ALTER TABLE queries
  ADD PRIMARY KEY (id);

--
-- Indexes for table slots
--
ALTER TABLE slots
  ADD PRIMARY KEY (id);

--
-- Indexes for table user
--
ALTER TABLE user
  ADD PRIMARY KEY (id);

--
-- Indexes for table user_payment_details
--
ALTER TABLE user_payment_details
  ADD PRIMARY KEY (id),
  ADD KEY user_id (user_id);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table admin
--
ALTER TABLE admin
  MODIFY id SERIAL PRIMARY KEY AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table bookings
--
ALTER TABLE bookings
  MODIFY id SERIAL PRIMARY KEY AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table coupons
--
ALTER TABLE coupons
  MODIFY id SERIAL PRIMARY KEY AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table coupon_usage
--
ALTER TABLE coupon_usage
  MODIFY id SERIAL PRIMARY KEY AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table menu
--
ALTER TABLE menu
  MODIFY id SERIAL PRIMARY KEY AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table orders
--
ALTER TABLE orders
  MODIFY id SERIAL PRIMARY KEY AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table order_items
--
ALTER TABLE order_items
  MODIFY id SERIAL PRIMARY KEY AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table payments
--
ALTER TABLE payments
  MODIFY id SERIAL PRIMARY KEY AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table pricing
--
ALTER TABLE pricing
  MODIFY id SERIAL PRIMARY KEY AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table queries
--
ALTER TABLE queries
  MODIFY id SERIAL PRIMARY KEY AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table slots
--
ALTER TABLE slots
  MODIFY id SERIAL PRIMARY KEY AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table user
--
ALTER TABLE user
  MODIFY id SERIAL PRIMARY KEY AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table user_payment_details
--
ALTER TABLE user_payment_details
  MODIFY id SERIAL PRIMARY KEY AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table coupons
--
ALTER TABLE coupons
  ADD CONSTRAINT coupons_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE;

--
-- Constraints for table coupon_usage
--
ALTER TABLE coupon_usage
  ADD CONSTRAINT coupon_usage_ibfk_1 FOREIGN KEY (coupon_id) REFERENCES coupons (id) ON DELETE CASCADE,
  ADD CONSTRAINT coupon_usage_ibfk_2 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE;

--
-- Constraints for table payments
--
ALTER TABLE payments
  ADD CONSTRAINT payments_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE;

--
-- Constraints for table user_payment_details
--
ALTER TABLE user_payment_details
  ADD CONSTRAINT user_payment_details_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE;
COMMIT;

