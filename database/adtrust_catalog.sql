-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jan 26, 2022 at 04:19 PM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


--
-- Database: `adtrust_catalog`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_activity`
--


DROP TABLE IF EXISTS `admin_activity`;
CREATE TABLE `admin_activity` (

  `id`                int(20) NOT NULL,
  `user_id`           int(11) NOT NULL,
  `admin_activity_id` bigint(20) UNSIGNED NOT NULL,
  `email_address`     varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_url`          varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address`        varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at`        timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP


) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE `brands` (

  `brand_id`          int(10) UNSIGNED NOT NULL,
  `brand`             varchar(255) DEFAULT NULL,
  `synopsis`          text,
  `logo`              varchar(255) DEFAULT NULL,
  `homepage`          varchar(255) DEFAULT NULL,
  `order_id`          int(10) DEFAULT '999999',
  `created_by`        int(5) NOT NULL DEFAULT '0',
  `created_at`        timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at`        timestamp NULL DEFAULT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

DROP TABLE IF EXISTS `offers`;
CREATE TABLE `offers` (

  `offer_id`          int(10) UNSIGNED NOT NULL,
  `brand_id`          int(10) NOT NULL,
  `offer`             varchar(255) DEFAULT NULL,
  `created_by`        int(10) NOT NULL DEFAULT '0',
  `created_at`        timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at`        timestamp NULL DEFAULT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `offer_urls`
--

DROP TABLE IF EXISTS `offer_urls`;
CREATE TABLE `offer_urls` (

  `offer_url_id`      int(10) UNSIGNED NOT NULL,
  `offer_id`          int(10) DEFAULT NULL,
  `offer_url`         varchar(255) DEFAULT NULL,
  `offer_url_type_id` int(10) DEFAULT '0',
  `created_by`        int(10) NOT NULL DEFAULT '0',
  `created_at`        timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at`        timestamp NULL DEFAULT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `offer_url_types`
--

DROP TABLE IF EXISTS `offer_url_types`;
CREATE TABLE `offer_url_types` (

  `offer_url_type_id` int(10) UNSIGNED NOT NULL,
  `offer_url_type`    varchar(255) DEFAULT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (


  `user_id`            int(10) UNSIGNED NOT NULL,
  `first_name`         varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name`          varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_address`      varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username`           varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password`           varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_login_at`      timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login_ip`      varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at`         timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP


) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email_address`, `username`, `password`, `last_login_at`, `last_login_ip`, `created_at`) VALUES
(1, 'A.R.', 'ZERIN', 'a.r.zerin@gmail.com', 'zerin', 'c33367701511b4f6020ec61ded352059', '2021-12-15 18:10:16', '::1', '2021-07-27 09:33:36'),
(2, 'John', 'Brahy', 'john@brahy.com', 'johnbrahy', '654321', '2021-12-11 13:49:02', '47.147.142.72', '2021-07-27 10:12:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

DROP TABLE IF EXISTS `user_permissions`;
CREATE TABLE `user_permissions` (

  `user_permission_id`                 int(20) NOT NULL,
  `user_id`                            int(10) UNSIGNED NOT NULL,
  `adding_placements`                  tinyint(1) NOT NULL DEFAULT '0',
  `updating_placements`                tinyint(1) DEFAULT '0',
  `sorting_placements`                 tinyint(1) NOT NULL DEFAULT '0',
  `archiving_placements`               tinyint(1) NOT NULL DEFAULT '0',
  `adding_placement_groups`            tinyint(1) NOT NULL DEFAULT '0',
  `updating_placement_groups`          tinyint(1) DEFAULT '0',
  `deleting_placements`                tinyint(1) NOT NULL DEFAULT '0',
  `deleting_placement_groups`          tinyint(1) NOT NULL DEFAULT '0',
  `managing_users`                     tinyint(1) NOT NULL DEFAULT '0',
  `managing_ssh_keys`                  tinyint(1) NOT NULL DEFAULT '0',
  `vertical_enabled`                   tinyint(1) DEFAULT '0',
  `reporting_enabled`                  tinyint(1) DEFAULT '0',
  `updating_admin_from_github`         tinyint(1) NOT NULL DEFAULT '0',
  `updating_app_from_github`           tinyint(1) NOT NULL DEFAULT '0',
  `updating_web_templates_from_github` tinyint(1) NOT NULL DEFAULT '0',
  `created_at`                         timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`                         timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP


) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_activity`
--
ALTER TABLE `admin_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offer_id`);

--
-- Indexes for table `offer_urls`
--
ALTER TABLE `offer_urls`
  ADD PRIMARY KEY (`offer_url_id`);

--
-- Indexes for table `offer_url_types`
--
ALTER TABLE `offer_url_types`
  ADD PRIMARY KEY (`offer_url_type_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`user_permission_id`);


--
-- AUTO_INCREMENT for table `admin_activity`
--
ALTER TABLE `admin_activity`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `offer_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offer_urls`
--
ALTER TABLE `offer_urls`
  MODIFY `offer_url_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offer_url_types`
--
ALTER TABLE `offer_url_types`
  MODIFY `offer_url_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `user_permission_id` int(20) NOT NULL AUTO_INCREMENT;
COMMIT;
