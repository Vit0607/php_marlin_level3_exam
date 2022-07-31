-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 31 2022 г., 16:22
-- Версия сервера: 8.0.24
-- Версия PHP: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `php_marlin_app3`
--

-- --------------------------------------------------------

--
-- Структура таблицы `info_links`
--

CREATE TABLE `info_links` (
  `add_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `online_status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `job_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telegram` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `info_links`
--

INSERT INTO `info_links` (`add_id`, `user_id`, `online_status`, `avatar`, `job_title`, `phone`, `address`, `telegram`, `instagram`, `vk`) VALUES
(1, 1, 'online', '62e66c0fe00a0.png', 'Admin', '+7 930 8044381', '15 Charist St, Detroit, MI, 48212, USA', 'https://telegram.org/', 'https://instagram.com/', 'https://vk.com/id399462970'),
(2, 2, 'away', '62e66bd28a976.png', 'IT Director', '+7 930 8044382', 'London', 'https://telegram.org/', 'https://instagram.com/', 'https://vk.com/id399462970');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `email` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `verified` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `resettable` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `roles_mask` int UNSIGNED NOT NULL DEFAULT '0',
  `registered` int UNSIGNED NOT NULL,
  `last_login` int UNSIGNED DEFAULT NULL,
  `force_logout` mediumint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `status`, `verified`, `resettable`, `roles_mask`, `registered`, `last_login`, `force_logout`) VALUES
(1, 'rahim@marlindev.ru', '', 'Rahim', 1, 1, 1, 1, 1657551647, 1659184966, 0),
(2, 'rahim3@marlindev.ru', '$2y$10$ACh3YLOwDZgu8pJuGedn8eCuDZ.tiyrT3K2xoiYONsIVSGttrypz6', 'Rahim10', 2, 1, 1, 1, 1658316208, 1659271013, 0),
(150, '777@mail.ru', '$2y$10$BvkGT.enhImXW3.JpgdtrOHLT19aQTr.SdNJnHDGCO0.K5260O9g2', NULL, 0, 1, 1, 1, 1659272615, 1659272622, 0),
(149, '123@tutu.ru', '$2y$10$/AffTvbSARp4iJo6o.75HePRdcUhEmz.Z1rLa3/DYOMjlzDW4zHeq', NULL, 1, 1, 1, 512, 1659271890, 1659271900, 0),
(151, '999@dev.ru', '$2y$10$hqB9vRJpEznakvTs.jDJwOwIe5d/gjJ20rw.4evJWxYmfTyfg2.bC', NULL, 0, 1, 1, 0, 1659273243, 1659273249, 0),
(152, '555@dev.ru', '$2y$10$Bvqsj7s.0cTbngE23Z15Qu6wzgw3Lg46V3j0nlke7whQn.UqXIRRa', NULL, 0, 1, 1, 0, 1659273336, NULL, 0),
(153, '444@dev.ru', '$2y$10$JMjJWu.5jnMz.yp/z5knren/DykPMnLjKwlEcxuf7YQ/7ziy2NfHm', NULL, 0, 1, 1, 0, 1659273362, 1659273367, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users_confirmations`
--

CREATE TABLE `users_confirmations` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `email` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `selector` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `expires` int UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users_confirmations`
--

INSERT INTO `users_confirmations` (`id`, `user_id`, `email`, `selector`, `token`, `expires`) VALUES
(2, 2, 'rahim2@marlindev.ru', 'YuW8jLjK3kBcHGTh', '$2y$10$K8FnKaXHqavYbFECxP8BPuYo9k7xlpmnbIuCOZ8f7mjXaoAtwv32K', 1658402608),
(3, 3, 'test@test2.ru', 'G-DBq3rMnwHzUoOn', '$2y$10$bMMTXHdIYt9vQvbJkuwUseoWoGVgiBj39AjR2Wki6WHhVHA1YrSnm', 1658402681),
(4, 4, '321@mail.ru', 'bG1mpmfcpUIdxGKp', '$2y$10$y5kqLrwp7mPLID9p3GLW6eAITw6ij8vb9ItAgcgPDKbc.b75BUDti', 1658494427),
(5, 5, '7532@mail.ru', 'j2XYWVsySfO6pCLF', '$2y$10$9RsecyWNrK6O.gQ6GlcFre3Gji4IiOOQAOYsyThY6zMXzB2lxfnH6', 1658494787),
(6, 6, '951@mail.ru', 'yopx8qq7RwNdWwK_', '$2y$10$sJEoUF35I9cL/e2j3wCjN.SW3V2FDIrXBewHDXQbAtSM4bNE40xWO', 1658494893),
(7, 7, '753@test.ru', 'yyjL_tVduKE3g2Hv', '$2y$10$TQp1g/7h/WCacb8aO3tO4O8l4jBjpXkCR.9F5XAu2e/C.59pMjJaC', 1658554239),
(8, 8, '357@test.ru', 'gmmsClKsdMm40JWZ', '$2y$10$PAqpt4k0QyH1vrpb5YOK1.Feo9rRFLnVu/c0k0z/nPR7WKAUE4tJa', 1658555228),
(9, 9, '987@test.ru', '0Ln1lhxZLKOUhhn3', '$2y$10$VJw.md/vuQBFT1BN3gMPEeABRMOJAtfdXrlawTrMVvdlpv8h0bi5C', 1658570879),
(10, 10, 'test123@gmail.com', 'Hz64n6ldTWPF67fc', '$2y$10$aQmwaJfEklI/MN7TWxg8WORL0PE6yS9JVu4obFb2lvLrqE.wdSgQC', 1658582543),
(11, 11, '123@tutu.ru', 'R79D391jFLNmcLqf', '$2y$10$ae8vRzSjkhPw98Gebje/0OAj0LPDcWmp7wOaL74pzYboFEz8HTWSe', 1658665276),
(12, 12, '321@tutu.ru', 'VOCtkCgset8dt49w', '$2y$10$6bPO.rtko3WZaztDHVrvMeHM2c/nDz1RcaH6ZrcZHxK4lE7hnWhVq', 1658672592),
(13, 13, '777@mail.ru', '0X7WN30hMBWAFHe-', '$2y$10$Mzvn4F1B7rEaJ04RU8Z9Ou227M8v2EQfERNt.l0hQihv0yNIs4YTi', 1658730677),
(14, 14, '321@tup.ru', 'OkedPXLVDzlrxRt3', '$2y$10$sOtQNWT9rf/eWtaHUX2ACeA4RHTg/ZRwpVffRXxLzLIP3byLgT8oi', 1658754534),
(15, 15, '444@dev.ru', 'WKIjNMSfriKP3X0n', '$2y$10$BgXkYJsTgDY/zcq3iaTPs.xtv8zFKYYg31Xf.3mL3oedakEsAjzXi', 1658757430),
(16, 16, '321@dev.ru', 'Eez5j1NlABvCFc7J', '$2y$10$mETZ4LyQx7kClY.Eb9CK1.AqhuIfU9sOdtIVwVXRksc2.1g60v/pu', 1658757570),
(17, 17, '543@dev.ru', 'oXA4KKN-ukpa_yxG', '$2y$10$w4viRboOxsQaX/gjz5zkOeKoPZMz9MxpZbx6sdFFhvd0CQjSV8BwW', 1658757698),
(18, 18, '111@dev.ru', 'vPCYdtDZF3N6TtuF', '$2y$10$vgfUWzxHkf63hgTLzTtiRu6RwzXgZI6PR8dkDu4RZgqH12r.oO7yq', 1658758068),
(19, 19, '555@dev.ru', 'cBn11O2Udwk2UKv8', '$2y$10$lBaxCuaSXr84bkF9e5q4tefZ9vqs7NN7J.sY3b6VQzzSozPHQ5hiq', 1658758336);

-- --------------------------------------------------------

--
-- Структура таблицы `users_remembered`
--

CREATE TABLE `users_remembered` (
  `id` bigint UNSIGNED NOT NULL,
  `user` int UNSIGNED NOT NULL,
  `selector` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `expires` int UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_resets`
--

CREATE TABLE `users_resets` (
  `id` bigint UNSIGNED NOT NULL,
  `user` int UNSIGNED NOT NULL,
  `selector` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `expires` int UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_throttling`
--

CREATE TABLE `users_throttling` (
  `bucket` varchar(44) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `tokens` float UNSIGNED NOT NULL,
  `replenished_at` int UNSIGNED NOT NULL,
  `expires_at` int UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users_throttling`
--

INSERT INTO `users_throttling` (`bucket`, `tokens`, `replenished_at`, `expires_at`) VALUES
('QduM75nGblH2CDKFyk0QeukPOwuEVDAUFE54ITnHM38', 0.750012, 1658670350, 1659210350),
('PZ3qJtO_NLbJfRIP-8b4ME4WA3xxc6n9nbCORSffyQ0', 0.0209854, 1658668134, 1659100134),
('HIJQJPUQ2qyyTt0Q7_AuZA0pXm27czJnqpJsoA5IFec', 49, 1658586192, 1658658192),
('HATvH93y3K2wHD2M-caoNIMI856MVSVj0HQPPnReIKI', 28.1158, 1657552187, 1657624187),
('O6NVfiZjat9VFcxT7ReFxLL03jgwXySBKdowACvNEdQ', 28.1158, 1657552187, 1657624187),
('QG-7mhO6KMAQVd27hY19XX1a63Bor0g5I5PFXNbxqNI', 29, 1658586192, 1658658192),
('py90jzaAqDRiDAr0XWq5MjGK_KelKoHOrEMJ_uKYlZE', 29, 1658586192, 1658658192);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `info_links`
--
ALTER TABLE `info_links`
  ADD PRIMARY KEY (`add_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `users_confirmations`
--
ALTER TABLE `users_confirmations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `email_expires` (`email`,`expires`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users_remembered`
--
ALTER TABLE `users_remembered`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `user` (`user`);

--
-- Индексы таблицы `users_resets`
--
ALTER TABLE `users_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `user_expires` (`user`,`expires`);

--
-- Индексы таблицы `users_throttling`
--
ALTER TABLE `users_throttling`
  ADD PRIMARY KEY (`bucket`),
  ADD KEY `expires_at` (`expires_at`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `info_links`
--
ALTER TABLE `info_links`
  MODIFY `add_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT для таблицы `users_confirmations`
--
ALTER TABLE `users_confirmations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `users_remembered`
--
ALTER TABLE `users_remembered`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_resets`
--
ALTER TABLE `users_resets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
