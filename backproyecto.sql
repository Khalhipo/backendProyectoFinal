-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-03-2021 a las 13:22:55
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `backproyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `amigos`
--

CREATE TABLE `amigos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) UNSIGNED NOT NULL,
  `id_amigo` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `amigos`
--

INSERT INTO `amigos` (`id`, `id_usuario`, `id_amigo`) VALUES
(63, 6, 4),
(64, 4, 6),
(65, 6, 5),
(66, 5, 6),
(67, 6, 7),
(68, 7, 6),
(69, 6, 8),
(70, 8, 6),
(73, 3, 4),
(74, 4, 3),
(75, 4, 5),
(76, 5, 4),
(79, 4, 7),
(80, 7, 4),
(83, 3, 5),
(84, 5, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicios`
--

CREATE TABLE `ejercicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ejercicios`
--

INSERT INTO `ejercicios` (`id`, `nombre`, `categoria`, `descripcion`) VALUES
(1, 'press banca', 'pecho', ''),
(2, 'press inclinado', 'pecho', ''),
(3, 'pull up', 'espalda', ''),
(4, 'seal row', 'espalda', ''),
(5, 'squat', 'pierna', ''),
(6, 'peso muerto', 'pierna', ''),
(7, 'press militar', 'hombro', ''),
(8, 'elevaciones laterales', 'hombro', '');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `ejerciciosmostrar`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `ejerciciosmostrar` (
`id` int(11)
,`nombre` varchar(255)
,`categoria` varchar(255)
,`id_entrenamiento` int(11)
,`id_ejercicio` int(11)
,`series` int(11)
,`repeticiones` int(11)
,`peso` float
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenamientos`
--

CREATE TABLE `entrenamientos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `comentario` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `entrenamientos`
--

INSERT INTO `entrenamientos` (`id`, `id_usuario`, `fecha`, `comentario`) VALUES
(7, 3, '2021-03-08', 'Este entrenamiento fue muy bien'),
(8, 3, '2021-03-09', 'El día de pierna pica mucho');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etto_ejercicios`
--

CREATE TABLE `etto_ejercicios` (
  `id` int(11) NOT NULL,
  `id_entrenamiento` int(11) NOT NULL,
  `id_ejercicio` int(11) NOT NULL,
  `series` int(11) NOT NULL,
  `repeticiones` int(11) NOT NULL,
  `peso` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `etto_ejercicios`
--

INSERT INTO `etto_ejercicios` (`id`, `id_entrenamiento`, `id_ejercicio`, `series`, `repeticiones`, `peso`) VALUES
(1, 7, 1, 4, 8, 64),
(2, 7, 3, 7, 5, 55),
(3, 7, 5, 5, 5, 78),
(4, 7, 7, 4, 8, 12),
(5, 8, 3, 5, 8, 4),
(6, 8, 2, 7, 8, 50),
(7, 8, 6, 8, 5, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(11) UNSIGNED NOT NULL,
  `idRemitente` int(11) UNSIGNED NOT NULL,
  `idDestinatario` int(11) UNSIGNED NOT NULL,
  `mensaje` varchar(255) NOT NULL,
  `leido` tinyint(1) NOT NULL DEFAULT 0,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `idRemitente`, `idDestinatario`, `mensaje`, `leido`, `fecha`) VALUES
(24, 6, 8, 'Hola, amigo', 0, '2021-02-27 17:29:29'),
(33, 4, 6, 'Hola amiguitos!', 0, '2021-02-28 18:05:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pesos`
--

CREATE TABLE `pesos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `peso` float NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pesos`
--

INSERT INTO `pesos` (`id`, `id_usuario`, `peso`, `fecha`) VALUES
(5, 3, 75, '2021-03-08'),
(6, 3, 78, '2021-03-09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `nombre` varchar(32) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(40) NOT NULL,
  `sexo` varchar(40) DEFAULT NULL,
  `altura` int(10) UNSIGNED DEFAULT NULL,
  `peso` int(10) UNSIGNED DEFAULT NULL,
  `imgSrc` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `password`, `email`, `sexo`, `altura`, `peso`, `imgSrc`) VALUES
(3, 'Juan Ángel', '$2y$10$rdABFbaf1aQzPaIlIwG.0u6de3HqvkOl2gw99qZpVggeme639liDa', 'juan@juan.es', 'hombre', 172, 80, 'http://localhost/backendphp/images/p-3-1614194749.jpg'),
(4, 'María', '$2y$10$L2MfoIId1Hb32T0i4qTkH.RJiWom/ysfLs7/f7Cnwwuu5SaMJ53lC', 'maria@maria.es', 'mujer', 172, 55, 'http://localhost/backendphp/images/p-4-1614278690.jpg'),
(5, 'mario', '$2y$10$2nmT2fCxneiIlnydmcMhHu7QOymBQ8bcVUIl.n.FIUEchBsLJlSku', 'mario@mario.es', 'hombre', 158, 55, ''),
(6, 'Pablo', '$2y$10$ZtzJEymJ6OXhVui.oMio/O3APJ29s97KFXL.K7ynle5QI/M4c0lxe', 'pablo@pablo.es', 'hombre', 180, 80, 'http://localhost/backendphp/images/p-6-1614269743.jpg'),
(7, 'Laura', '$2y$10$sqQfkbvWpkQXTALthsY3P.ell.TH10jYNcMblvOQvFRRwrjxDE/vm', 'laura@laura.es', 'mujer', 175, 55, 'http://localhost/backendphp/images/p-7-1614441818.jpg'),
(8, 'Jose', '$2y$10$e3gXlBQmqXqSpKOZiqYdHebK8uBGDmY3VrUVEDALAiu6zeVzRXA5G', 'jose@jose.es', 'hombre', 185, 85, 'http://localhost/backendphp/images/p-8-1614441951.jpg'),
(9, 'jejeje', '$2y$10$d9ywKWRvQwjsy9wTa9GV.enX1DA2A3clK5m20kd0BhPQp..HtQ9gS', 'a@a.a', 'hombre', 44, 55, '');

-- --------------------------------------------------------

--
-- Estructura para la vista `ejerciciosmostrar`
--
DROP TABLE IF EXISTS `ejerciciosmostrar`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ejerciciosmostrar`  AS  select `etto_ejercicios`.`id` AS `id`,`ejercicios`.`nombre` AS `nombre`,`ejercicios`.`categoria` AS `categoria`,`etto_ejercicios`.`id_entrenamiento` AS `id_entrenamiento`,`etto_ejercicios`.`id_ejercicio` AS `id_ejercicio`,`etto_ejercicios`.`series` AS `series`,`etto_ejercicios`.`repeticiones` AS `repeticiones`,`etto_ejercicios`.`peso` AS `peso` from (`etto_ejercicios` join `ejercicios`) where `etto_ejercicios`.`id_ejercicio` = `ejercicios`.`id` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `amigos`
--
ALTER TABLE `amigos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_amigo` (`id_amigo`),
  ADD KEY `amigo_usuario` (`id_usuario`);

--
-- Indices de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `entrenamientos`
--
ALTER TABLE `entrenamientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_etto` (`id_usuario`);

--
-- Indices de la tabla `etto_ejercicios`
--
ALTER TABLE `etto_ejercicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etto_ejercicio_etto` (`id_entrenamiento`),
  ADD KEY `etto_ejercicio_ejercicio` (`id_ejercicio`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_remitente` (`idRemitente`),
  ADD KEY `id_destinatario` (`idDestinatario`);

--
-- Indices de la tabla `pesos`
--
ALTER TABLE `pesos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_peso` (`id_usuario`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `amigos`
--
ALTER TABLE `amigos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `entrenamientos`
--
ALTER TABLE `entrenamientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `etto_ejercicios`
--
ALTER TABLE `etto_ejercicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `pesos`
--
ALTER TABLE `pesos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `amigos`
--
ALTER TABLE `amigos`
  ADD CONSTRAINT `amigo_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_amigo` FOREIGN KEY (`id_amigo`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `entrenamientos`
--
ALTER TABLE `entrenamientos`
  ADD CONSTRAINT `usuario_etto` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `etto_ejercicios`
--
ALTER TABLE `etto_ejercicios`
  ADD CONSTRAINT `etto_ejercicio_ejercicio` FOREIGN KEY (`id_ejercicio`) REFERENCES `ejercicios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `etto_ejercicio_etto` FOREIGN KEY (`id_entrenamiento`) REFERENCES `entrenamientos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `id_destinatario` FOREIGN KEY (`idDestinatario`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_remitente` FOREIGN KEY (`idRemitente`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pesos`
--
ALTER TABLE `pesos`
  ADD CONSTRAINT `usuario_peso` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
