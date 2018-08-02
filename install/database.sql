-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- โฮสต์: localhost
-- เวอร์ชั่นของเซิร์ฟเวอร์: 5.1.73-log
-- รุ่นของ PHP: 5.4.45

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


-- --------------------------------------------------------

--
-- โครงสร้างตาราง `{prefix}_category`
--

CREATE TABLE `{prefix}_category` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `topic` varchar(128) CHARACTER SET utf8 NOT NULL,
  `color` varchar(16) CHARACTER SET utf8 NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- dump ตาราง `{prefix}_category`
--

INSERT INTO `{prefix}_category` (`id`, `type`, `category_id`, `topic`, `color`, `published`) VALUES
(1, 'repairstatus', 1, 'แจ้งซ่อม', '#660000', 1),
(2, 'repairstatus', 2, 'กำลังดำเนินการ', '#120eeb', 1),
(3, 'repairstatus', 3, 'รออะไหล่', '#d940ff', 1),
(4, 'repairstatus', 4, 'ซ่อมสำเร็จ', '#06d628', 1),
(5, 'repairstatus', 5, 'ซ่อมไม่สำเร็จ', '#FF0000', 1),
(6, 'repairstatus', 6, 'ยกเลิกการซ่อม', '#FF6F00', 1),
(7, 'repairstatus', 7, 'ส่งมอบเรียบร้อย', '#000000', 1),
(8, 'model_id', 2, 'Asus', '', 0),
(9, 'type_id', 3, 'โปรเจ็คเตอร์', '', 0),
(10, 'type_id', 2, 'เครื่องพิมพ์', '', 0),
(11, 'model_id', 3, 'Cannon', '', 0),
(12, 'category_id', 1, 'เครื่องใช้ไฟฟ้า', '', 0),
(13, 'category_id', 2, 'วัสดุสำนักงาน', '', 0),
(14, 'model_id', 1, 'Apple', '', 0),
(15, 'type_id', 1, 'เครื่องคอมพิวเตอร์', '', 0),
(16, 'model_id', 4, 'ACER', '', 0),
(17, 'type_id', 4, 'จอมอนิเตอร์', '', 0);

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `{prefix}_inventory`
--

CREATE TABLE `{prefix}_inventory` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `equipment` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `serial` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL,
  `type_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `{prefix}_inventory`
--

INSERT INTO `{prefix}_inventory` (`id`, `equipment`, `serial`, `create_date`, `type_id`, `model_id`, `category_id`) VALUES
(1, 'จอมอนิเตอร์ ACER S220HQLEBD', '1108-365D', '0000-00-00 00:00:00', 4, 4, 1),
(2, 'ASUS A550JX', '0000-0001', '0000-00-00 00:00:00', 1, 2, 1);

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `{prefix}_repair`
--

CREATE TABLE `{prefix}_repair` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `job_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `job_description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL,
  `appointment_date` date NOT NULL,
  `appraiser` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `job_id` (`job_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `{prefix}_repair_status`
--

CREATE TABLE `{prefix}_repair_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `repair_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `member_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `{prefix}_user`
--

CREATE TABLE `{prefix}_user` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `permission` text COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `sex` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_card` varchar(13) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expire_date` date NOT NULL,
  `address` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provinceID` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visited` int(11) UNSIGNED DEFAULT '0',
  `lastvisited` int(11) DEFAULT NULL,
  `session_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `fb` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- dump ตาราง `{prefix}_user`
--

INSERT INTO `{prefix}_user` (`id`, `username`, `salt`, `password`, `status`, `permission`, `name`, `sex`, `id_card`, `expire_date`, `address`, `phone`, `provinceID`, `zipcode`, `visited`, `lastvisited`, `session_id`, `ip`, `create_date`) VALUES
(1, 'admin@localhost', 'admin@localhost', 'b620e8b83d7fcf7278148d21b088511917762014', 1, ',can_config,can_repair,', 'แอดมิน', 'm', '', '1899-11-30', '1 หมู่ 1 ตำบล ลาดหญ้า อำเภอ เมือง', '08080808', '102', '71190', 86, 1499143102, '72f5jv4qn9gsgjs86f13n9gn00', '110.171.14.23', '0000-00-00 00:00:00'),
(2, 'demo2@localhost', 'demo2@localhost', 'db75cdf3d5e77181ec3ed6072b56a8870eb6822d', 2, ',can_repair,', 'ช่างซ่อม 2', 'f', '', '0000-00-00', '', '0123456789', '101', '', 0, 0, NULL, NULL, '2017-07-02 08:11:21'),
(3, 'demo@localhost', 'demo@localhost', 'db75cdf3d5e77181ec3ed6072b56a8870eb6822d', 2, ',can_repair,', 'ช่างซ่อม 1', 'f', '', '0000-00-00', '', '0123456788', '101', '', 21, 1499124076, 'nhmshndjgk9f09qc6390s1nnd4', '110.171.14.23', '2017-07-02 08:10:30');
