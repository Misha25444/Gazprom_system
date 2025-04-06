-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 07, 2025 at 12:01 AM
-- Server version: 8.0.30
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Base_material`
--

-- --------------------------------------------------------

--
-- Table structure for table `hashtags`
--

CREATE TABLE `hashtags` (
  `id` int NOT NULL,
  `name` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hashtags`
--

INSERT INTO `hashtags` (`id`, `name`) VALUES
(484, 'Тестовый_тег4'),
(485, 'Тестовый_тег3'),
(486, 'Тестовый_тег2'),
(487, 'Тестовый_тег'),
(488, 'ковш'),
(489, 'гидравлика'),
(490, 'строительство'),
(491, 'водоснабжение'),
(492, 'крепление'),
(493, 'грузоподъёмность'),
(494, 'анимация'),
(495, 'демонстрация'),
(496, 'производство'),
(497, 'тест'),
(498, 'нефть'),
(499, 'нефтяная_промышленность'),
(500, 'емкости'),
(501, 'Фильм'),
(502, 'новые'),
(503, 'теги'),
(504, 'для'),
(505, 'вас'),
(506, 'работа'),
(508, 'паспорт'),
(509, 'ящик'),
(510, 'Тестовый_материал_2'),
(511, 'Тестовый_материал_25'),
(513, 'резервуары'),
(514, 'Вертолет'),
(515, 'Сталь'),
(516, 'диплом'),
(533, 'приказ'),
(534, '1'),
(535, 'кран'),
(536, 'exr');

-- --------------------------------------------------------

--
-- Table structure for table `hashtagtomodels`
--

CREATE TABLE `hashtagtomodels` (
  `id` int NOT NULL,
  `hashtagfromheshtag` int DEFAULT NULL,
  `typeid` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hashtagtomodels`
--

INSERT INTO `hashtagtomodels` (`id`, `hashtagfromheshtag`, `typeid`) VALUES
(1216, 516, 579),
(1217, 516, 580),
(1218, 516, 581),
(1219, 516, 582),
(1220, 516, 583),
(1221, 516, 584),
(1222, 516, 585),
(1223, 516, 586),
(1224, 516, 587),
(1225, 516, 588),
(1226, 516, 589),
(1227, 516, 590),
(1228, 516, 591),
(1229, 516, 592),
(1230, 516, 593),
(1231, 533, 592),
(1232, 516, 594),
(1234, 535, 596),
(1235, 535, 597),
(1236, 536, 598),
(1237, 536, 599),
(1241, 516, 603),
(1242, 516, 604),
(1243, 534, 605),
(1244, 534, 606),
(1245, 534, 607),
(1246, 534, 608),
(1247, 534, 609),
(1248, 534, 610),
(1249, 534, 611),
(1250, 534, 612),
(1251, 534, 613),
(1252, 534, 614),
(1253, 534, 615),
(1254, 534, 616),
(1255, 534, 617),
(1256, 534, 618),
(1257, 534, 619),
(1258, 534, 620),
(1259, 534, 621);

-- --------------------------------------------------------

--
-- Table structure for table `hashtagtoproject`
--

CREATE TABLE `hashtagtoproject` (
  `id` int NOT NULL,
  `hashtagfromheshtag` int NOT NULL,
  `typeid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hashtagtoproject`
--

INSERT INTO `hashtagtoproject` (`id`, `hashtagfromheshtag`, `typeid`) VALUES
(2251, 516, 1542),
(2252, 534, 1543),
(2253, 535, 1544),
(2254, 536, 1545);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `message` varchar(191) DEFAULT NULL,
  `sender_role` varchar(191) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `admin_read` varchar(255) DEFAULT NULL,
  `user_read` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `message`, `sender_role`, `timestamp`, `admin_read`, `user_read`) VALUES
(106, 52, 'Ghgf', '49', '2025-04-06 01:21:57', '1', '1'),
(107, 52, 'uuuuuuuuuuuuuuuuuuuu', '49', '2025-04-06 01:56:06', '1', '1'),
(109, 75, 'Привет!', '74', '2025-04-06 20:54:41', '1', '1'),
(110, 74, 'Привет', '75', '2025-04-06 21:29:46', '1', '0');

-- --------------------------------------------------------

--
-- Table structure for table `models`
--

CREATE TABLE `models` (
  `id` int NOT NULL,
  `path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `format` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `preview` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `size` int DEFAULT NULL,
  `id_project` int DEFAULT NULL,
  `content_types` enum('audio','document','image','model','video','material','texture') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `models`
--

INSERT INTO `models` (`id`, `path`, `name`, `format`, `date`, `preview`, `size`, `id_project`, `content_types`) VALUES
(579, 'three.js-dev/examples/projects/1542/model1.fbx', 'model1.fbx', 'fbx', '2025-04-06 16:44:28', 'three.js-dev/examples/projects/1542/screenshot_0.png', 24560, 1542, 'model'),
(580, 'three.js-dev/examples/projects/1542/model2.gltf', 'model2.gltf', 'gltf', '2025-04-06 16:44:28', 'three.js-dev/examples/projects/1542/screenshot_1.png', 78768612, 1542, 'model'),
(581, 'three.js-dev/examples/projects/1542/model3.gltf', 'model3.gltf', 'gltf', '2025-04-06 16:44:28', 'three.js-dev/examples/projects/1542/screenshot_2.png', 2045960, 1542, 'model'),
(582, 'three.js-dev/examples/projects/1542/model4.obj', 'model4.obj', 'obj', '2025-04-06 16:44:28', 'three.js-dev/examples/projects/1542/screenshot_3.png', 9638870, 1542, 'model'),
(583, 'three.js-dev/examples/projects/1542/model5.glb', 'model5.glb', 'glb', '2025-04-06 16:44:28', 'three.js-dev/examples/projects/1542/screenshot_4.png', 2611280, 1542, 'model'),
(584, 'three.js-dev/examples/projects/1542/model6.glb', 'model6.glb', 'glb', '2025-04-06 16:44:28', 'three.js-dev/examples/projects/1542/screenshot_5.png', 12130244, 1542, 'model'),
(585, 'three.js-dev/examples/projects/1542/model7.stl', 'model7.stl', 'stl', '2025-04-06 16:44:28', 'three.js-dev/examples/projects/1542/screenshot_6.png', 2306984, 1542, 'model'),
(586, 'three.js-dev/examples/projects/1542/noon_grass_4k.hdr', 'noon_grass_4k.hdr', 'hdr', '2025-04-06 16:44:30', 'three.js-dev/examples/projects/1542/compressed_noon_grass_4k.jpg', 25306463, 1542, 'image'),
(587, 'three.js-dev/examples/projects/1542/grefer.jpg', 'grefer.jpg', 'jpg', '2025-04-06 16:44:30', 'three.js-dev/examples/projects/1542/compressed_grefer.jpg', 37585, 1542, 'image'),
(588, 'three.js-dev/examples/projects/1542/звук разлива нефти  (1).mp3', 'звук разлива нефти  (1).mp3', 'mp3', '2025-04-06 16:44:30', NULL, 9699665, 1542, 'audio'),
(589, 'three.js-dev/examples/projects/1542/Резервуар.mp4', 'Резервуар.mp4', 'mp4', '2025-04-06 16:44:30', NULL, 11259179, 1542, 'video'),
(590, 'projects/1542/textures_group_67f160132caa2', 'textures_group_67f160132caa2.zip', 'zip', '2025-04-06 16:44:30', NULL, 15147910, 1542, 'texture'),
(591, 'three.js-dev/examples/projects/1542/Пример №1.docx', 'Пример №1.docx', 'docx', '2025-04-06 16:44:30', NULL, 18063, 1542, 'document'),
(592, 'three.js-dev/examples/projects/1542/Резервуарные парки для хранения нефти и.pdf', 'Резервуарные парки для хранения нефти и.pdf', 'pdf', '2025-04-06 16:44:30', NULL, 97311, 1542, 'document'),
(593, 'projects/1542/textures_group_67f2af6eb6181.zip', 'Стена', NULL, '2025-04-06 16:44:30', 'three.js-dev/examples/projects/1542/sphere_0.png', 7402828, 1542, 'material'),
(594, 'three.js-dev/examples/projects/1542/6473_Пример №1.docx_[regforum].docx', 'Пример №1.docx_[regforum].docx', 'docx', '2025-04-06 16:52:30', NULL, 18063, 1542, 'document'),
(596, 'three.js-dev/examples/projects/1544/model4.obj', 'model4.obj', 'obj', '2025-04-06 18:11:17', 'three.js-dev/examples/projects/1544/screenshot_0.png', 9638870, 1544, 'model'),
(597, 'three.js-dev/examples/projects/1544/7722_noon_grass_4k.hdr', 'noon_grass_4k.hdr', 'hdr', '2025-04-06 20:19:21', 'three.js-dev/examples/projects/1544/7722_noon_grass_4k.jpg', 25306463, 1544, 'image'),
(598, 'three.js-dev/examples/projects/1545/noon_grass_4k.hdr', 'noon_grass_4k.hdr', 'hdr', '2025-04-06 20:20:45', 'three.js-dev/examples/projects/1545/compressed_noon_grass_4k.jpg', 25306463, 1545, 'image'),
(599, 'three.js-dev/examples/projects/1545/stierberg_sunrise_4k.exr', 'stierberg_sunrise_4k.exr', 'exr', '2025-04-06 20:20:48', 'three.js-dev/examples/projects/1545/compressed_stierberg_sunrise_4k.jpg', 81546479, 1545, 'image'),
(603, 'three.js-dev/examples/projects/1542/4852_medieval_red_brick_rough_2k.exr', 'medieval_red_brick_rough_2k.exr', 'exr', '2025-04-06 20:41:17', 'three.js-dev/examples/projects/1542/4852_medieval_red_brick_rough_2k.jpg', 1575152, 1542, 'image'),
(604, 'three.js-dev/examples/projects/1542/2291_secluded_beach_2k.exr', 'secluded_beach_2k.exr', 'exr', '2025-04-06 20:41:58', 'three.js-dev/examples/projects/1542/2291_secluded_beach_2k.jpg', 6035399, 1542, 'image'),
(605, 'three.js-dev/examples/projects/1543/7005_model1.fbx', 'model1.fbx', 'fbx', '2025-04-06 20:49:53', 'three.js-dev/examples/projects/1543/7005_screenshot_0.png', 24560, 1543, 'model'),
(606, 'three.js-dev/examples/projects/1543/5342_model2.gltf', 'model2.gltf', 'gltf', '2025-04-06 20:49:53', 'three.js-dev/examples/projects/1543/5342_screenshot_1.png', 78768612, 1543, 'model'),
(607, 'three.js-dev/examples/projects/1543/2736_model3.gltf', 'model3.gltf', 'gltf', '2025-04-06 20:49:53', 'three.js-dev/examples/projects/1543/2736_screenshot_2.png', 2045960, 1543, 'model'),
(608, 'three.js-dev/examples/projects/1543/1973_model4.obj', 'model4.obj', 'obj', '2025-04-06 20:49:53', 'three.js-dev/examples/projects/1543/1973_screenshot_3.png', 9638870, 1543, 'model'),
(609, 'three.js-dev/examples/projects/1543/2630_model5.glb', 'model5.glb', 'glb', '2025-04-06 20:49:53', 'three.js-dev/examples/projects/1543/2630_screenshot_4.png', 2611280, 1543, 'model'),
(610, 'three.js-dev/examples/projects/1543/2023_model6.glb', 'model6.glb', 'glb', '2025-04-06 20:49:53', 'three.js-dev/examples/projects/1543/2023_screenshot_5.png', 12130244, 1543, 'model'),
(611, 'three.js-dev/examples/projects/1543/4702_model7.stl', 'model7.stl', 'stl', '2025-04-06 20:49:53', 'three.js-dev/examples/projects/1543/4702_screenshot_6.png', 2306984, 1543, 'model'),
(612, 'three.js-dev/examples/projects/1543/3515_noon_grass_4k (2).hdr', 'noon_grass_4k (2).hdr', 'hdr', '2025-04-06 20:49:55', 'three.js-dev/examples/projects/1543/3515_noon_grass_4k (2).jpg', 25306463, 1543, 'image'),
(613, 'three.js-dev/examples/projects/1543/3404_secluded_beach_2k.exr', 'secluded_beach_2k.exr', 'exr', '2025-04-06 20:49:56', 'three.js-dev/examples/projects/1543/3404_secluded_beach_2k.jpg', 6035399, 1543, 'image'),
(614, 'three.js-dev/examples/projects/1543/8583_звук разлива нефти  (1).mp3', 'звук разлива нефти  (1).mp3', 'mp3', '2025-04-06 20:49:56', NULL, 9699665, 1543, 'audio'),
(615, 'three.js-dev/examples/projects/1543/7108_Резервуар.mp4', 'Резервуар.mp4', 'mp4', '2025-04-06 20:49:56', NULL, 11259179, 1543, 'video'),
(616, 'projects/1543/textures_group_67f16012f1a64', 'textures_group_67f16012f1a64.zip', 'zip', '2025-04-06 20:49:56', NULL, 7402828, 1543, 'texture'),
(617, 'three.js-dev/examples/projects/1543/5888_Резервуарные парки для хранения нефти и.pdf', 'Резервуарные парки для хранения нефти и.pdf', 'pdf', '2025-04-06 20:49:56', NULL, 97311, 1543, 'document'),
(618, 'three.js-dev/examples/projects/1543/7280_Пример №1.docx_[regforum].docx', 'Пример №1.docx_[regforum].docx', 'docx', '2025-04-06 20:49:56', NULL, 18063, 1543, 'document'),
(619, 'projects/1543/textures_group_67f2e8f4968e1.zip', 'Кирпичи', NULL, '2025-04-06 20:49:56', 'three.js-dev/examples/projects/1543/2935_sphere_0.png', 2779796, 1543, 'material'),
(620, 'projects/1543/textures_group_67f2e8f4a8bd2.zip', 'ткань', NULL, '2025-04-06 20:49:56', 'three.js-dev/examples/projects/1543/6685_sphere_1.png', 12267024, 1543, 'material'),
(621, 'three.js-dev/examples/projects/1543/1068_unnamed.jpg', 'unnamed.jpg', 'jpg', '2025-04-06 20:50:37', 'three.js-dev/examples/projects/1543/1068_unnamed.jpg', 40035, 1543, 'image');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int NOT NULL,
  `name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description_project` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `preview_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `User_id` int DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `size` int DEFAULT NULL,
  `archive_url` varchar(191) DEFAULT NULL,
  `format` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description_project`, `preview_url`, `User_id`, `date`, `size`, `archive_url`, `format`) VALUES
(1542, 'Проект в УПЦ', 'Данный проект был разработан в рамках преддипломной практики , включающий в себя 3д модели промышленных установок', 'three.js-dev/examples/projects/1542/greyferniy-zahvat-dlya-lesa-v-nalichii_foto_large.jpg', 49, '2025-04-06 16:44:28', 459599067, 'three.js-dev/examples/projects/1542/!Материалы ВКР (2).zip', 'zip'),
(1543, '11', '11', 'three.js-dev/examples/projects/1543/greyferniy-zahvat-dlya-lesa-v-nalichii_foto_large.jpg', 49, '2025-04-06 16:57:25', 236497365, 'three.js-dev/examples/projects/1543/!Материалы ВКР (2).zip', 'zip'),
(1544, 'тест ', 'Ба́шенный кра́н (англ. Tower crane, фр. Grue à tour) — поворотный кран стрелового типа со стрелой, закреплённой в верхней части вертикально расположенно.......', 'three.js-dev/examples/projects/1544/unnamed.jpg', 74, '2025-04-06 18:11:17', 53427182, 'three.js-dev/examples/projects/1544/!Материалы ВКР (2).zip', 'zip'),
(1545, 'test23', 'test23', 'three.js-dev/examples/projects/1545/greyferniy-zahvat-dlya-lesa-v-nalichii_foto_large.jpg', 74, '2025-04-06 20:20:43', 126324898, 'three.js-dev/examples/projects/1545/!Материалы ВКР (2).zip', 'zip');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `role` enum('Пользователь','Администратор') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Пользователь',
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `recovery_token` varchar(191) DEFAULT NULL,
  `fio` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `role`, `email`, `phone`, `recovery_token`, `fio`) VALUES
(74, 'Misha25', '$2y$10$Mev5qp3sxSDQutNPhyRv..8yjedUsk.2mPYYmbj1e9nx8/hYiE1SC', 'Администратор', 'Misha25@gmail.com', '+7 (904) 224-25-46', NULL, 'Нефедов Михаил Эдгарович'),
(75, 'Nikolay24', '$2y$10$iZvREVImTreo0IOGZGmIq.Q5KiyiVHAwh7thsJGAIXwoKUbjkx32G', 'Пользователь', 'nefedov24nik@gmail.com', '+7 (904) 231-25-55', NULL, 'Нефедов Николай Эдгарович ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hashtags`
--
ALTER TABLE `hashtags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hashtagtomodels`
--
ALTER TABLE `hashtagtomodels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hashtagfromheshtag` (`hashtagfromheshtag`),
  ADD KEY `typeid` (`typeid`);

--
-- Indexes for table `hashtagtoproject`
--
ALTER TABLE `hashtagtoproject`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Projects_id_2` (`typeid`),
  ADD KEY `HashTag_id_2` (`hashtagfromheshtag`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_foreignkey_messages_user` (`user_id`);

--
-- Indexes for table `models`
--
ALTER TABLE `models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `models_ibfk_1` (`id_project`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `User_id` (`User_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hashtags`
--
ALTER TABLE `hashtags`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=537;

--
-- AUTO_INCREMENT for table `hashtagtomodels`
--
ALTER TABLE `hashtagtomodels`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1261;

--
-- AUTO_INCREMENT for table `hashtagtoproject`
--
ALTER TABLE `hashtagtoproject`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2255;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `models`
--
ALTER TABLE `models`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=623;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1546;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hashtagtomodels`
--
ALTER TABLE `hashtagtomodels`
  ADD CONSTRAINT `hashtagtomodels_ibfk_1` FOREIGN KEY (`typeid`) REFERENCES `models` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `hashtagtomodels_ibfk_2` FOREIGN KEY (`hashtagfromheshtag`) REFERENCES `hashtags` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
