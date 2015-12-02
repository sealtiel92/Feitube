-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 02-12-2015 a las 04:33:35
-- Versión del servidor: 5.6.25
-- Versión de PHP: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `feitube`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentario`
--

CREATE TABLE IF NOT EXISTS `comentario` (
  `id_com` int(11) NOT NULL,
  `comentario` text CHARACTER SET utf8 COLLATE utf8_spanish_ci,
  `fecha` date DEFAULT NULL,
  `video_id_video` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `comentario`
--

INSERT INTO `comentario` (`id_com`, `comentario`, `fecha`, `video_id_video`) VALUES
(34, 'comentario', '2015-12-02', 9),
(35, 'sldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasf', '2015-12-02', 7),
(36, 'sldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasf', '2015-12-02', 7),
(37, 'sldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga{dpj gasfjsldga', '2015-12-02', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'members', 'General User');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `like`
--

CREATE TABLE IF NOT EXISTS `like` (
  `id_like` int(11) NOT NULL,
  `id_video` int(11) unsigned NOT NULL,
  `id_users` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `like`
--

INSERT INTO `like` (`id_like`, `id_video`, `id_users`) VALUES
(21, 7, 9),
(20, 8, 9),
(22, 9, 9),
(23, 33, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) unsigned NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `birthday` date NOT NULL,
  `phone` varchar(20) NOT NULL,
  `sex` char(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `birthday`, `phone`, `sex`) VALUES
(1, '127.0.0.1', 'administrator', '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36', '', 'admin@admin.com', '', NULL, 1268889823, '1268889823', 1, 0, 0, 'ADMIN', '0', '0000-00-00', '', ''),
(2, '192.168.0.14', '', '$2y$08$kS/YG.hzoS.HdVo.3S4cve3sO.tQ.eF3nid/Pj7K383hrAya2mCmS', NULL, 'sealtiel92@hotmail.com', NULL, NULL, NULL, NULL, 1448520881, 1449027008, 1, 'sealtiel', 'huerta', '1992-11-06', '2281727313', 'M'),
(3, '192.168.43.180', '', '$2y$08$dRl7SW68JsFb/Y0aBYbikO.EMDKkkSGqgRQ.UfCBZ1OsPVXDEBRzS', NULL, 'mtcm2711@gmail.com', NULL, NULL, NULL, NULL, 1448567486, 1448567581, 1, 'Mayte', 'Cadena', '2015-11-27', '22281402314', 'F'),
(4, '192.168.43.99', '', '$2y$08$BWfiyHAnrAHdwFfy0wz.p.VhzXomzM5dOnm.ImIKEQ1vI6kQdYinK', NULL, 'sealtiel888@gmail.com', NULL, NULL, NULL, NULL, 1448567970, 1449018954, 1, 'sealtiel', 'huerta', '2015-01-02', '2281727311', 'M'),
(5, '192.168.43.134', '', '$2y$08$EkDTmKaCdxp87m/tKpphuO2cuXqMMH9Ok34mWldodrGCx4094C.MG', NULL, 'prueba@hotmail.com', NULL, NULL, NULL, NULL, 1448568031, 1448568044, 1, 'prueba', 'huerta', '2016-01-01', '2281727313424', 'M'),
(6, '172.72.63.167', '', '$2y$08$TdIMIRF7RomMwmTJypOSkek9LYx3eHxc.OH5Jd8aYL34bCNxzja2u', NULL, 'lazaro9318@outlook.com', NULL, NULL, NULL, NULL, 1448918316, NULL, 1, 'Lázaro', 'Hernández Cruz', '1111-11-11', '2288366371', 'M'),
(7, '192.168.43.175', '', '$2y$08$RwmOdNULSAyA/3wmiBpYAeL92Gvq/jWQkNr1ETQWF8rXSKtMlo4/.', NULL, 'lazaro@outlook.com', NULL, NULL, NULL, NULL, 1448920061, 1448920087, 1, 'Lázaro ', 'Hernández Cruz', '1111-11-11', '2288366371', 'M'),
(8, '192.168.43.162', '', '$2y$08$Y3jImqNVZ8xQoDBEMCU8n.3SFI2ELRWAxleM/rgSkfnbZ.3RDMpm.', NULL, 'elrevo@gmail.com', NULL, NULL, NULL, NULL, 1448929806, 1448929854, 1, '''', '''', '1979-10-10', '123112321', 'M'),
(9, '192.168.0.14', '', '$2y$08$rRBbZsQ/Vjtn/jste3kKauILQT6CQhi8pZMHYD1s0AY11HJF0BfE.', NULL, 'planb_rey@hotmail.com', NULL, NULL, NULL, NULL, 1449005803, 1449015526, 1, 'sealtiel', '', '2015-01-02', '235262624', 'M');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(4, 3, 2),
(5, 4, 2),
(6, 5, 2),
(7, 6, 2),
(8, 7, 2),
(9, 8, 2),
(10, 9, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `video`
--

CREATE TABLE IF NOT EXISTS `video` (
  `id_video` int(11) unsigned NOT NULL,
  `name_video` varchar(30) NOT NULL,
  `date_up` date NOT NULL,
  `desc` text,
  `ruta` text,
  `categoria` varchar(45) DEFAULT NULL,
  `users_id` int(11) unsigned NOT NULL,
  `ruta_img` text NOT NULL,
  `visitas` int(11) NOT NULL DEFAULT '0',
  `likes` int(11) NOT NULL DEFAULT '0',
  `not_like` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `video`
--

INSERT INTO `video` (`id_video`, `name_video`, `date_up`, `desc`, `ruta`, `categoria`, `users_id`, `ruta_img`, `visitas`, `likes`, `not_like`) VALUES
(7, 'sealtiel92@hotmail.com - mtv', '2015-11-30', 'bird', '/videos/sealtiel92@hotmail.com/MTV.mp4', 'comico', 2, '/videos/sealtiel92@hotmail.com/MTV.mp4.png', 17, 1, 0),
(8, 'lazaro@outlook.com - jiojljkl', '2015-11-30', 'fgkgykgyuj', '/videos/lazaro@outlook.com/90935931.mp4', 'gkkg', 7, '/videos/lazaro@outlook.com/90935931.mp4.png', 6, 1, 0),
(9, 'elrevo@gmail.com -  comentario', '2015-12-01', ' comentario de prueba  comentario de prueba comentario de prueba  co', '/videos/elrevo@gmail.com/880hola.m4v', ' comentario de prueba  comentario de prueba c', 8, '/videos/elrevo@gmail.com/880hola.m4v.png', 6, 0, 1),
(33, 'sealtiel92@hotmail.com - ', '2015-12-02', '', '/videos/sealtiel92@hotmail.com/MTV.mp4', '', 2, '/videos/sealtiel92@hotmail.com/MTV.mp4.png', 3, 1, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`id_com`,`video_id_video`),
  ADD KEY `fk_comentario_video1_idx` (`video_id_video`);

--
-- Indices de la tabla `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`id_like`,`id_video`,`id_users`),
  ADD KEY `fk_like_video1_idx` (`id_video`),
  ADD KEY `fk_like_users1_idx` (`id_users`);

--
-- Indices de la tabla `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- Indices de la tabla `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id_video`,`users_id`),
  ADD KEY `fk_video_users1_idx` (`users_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentario`
--
ALTER TABLE `comentario`
  MODIFY `id_com` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT de la tabla `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `like`
--
ALTER TABLE `like`
  MODIFY `id_like` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `video`
--
ALTER TABLE `video`
  MODIFY `id_video` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD CONSTRAINT `fk_comentario_video1` FOREIGN KEY (`video_id_video`) REFERENCES `video` (`id_video`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `fk_like_users1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_like_video1` FOREIGN KEY (`id_video`) REFERENCES `video` (`id_video`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `fk_video_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
