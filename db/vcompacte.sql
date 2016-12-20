-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 20 2016 г., 17:55
-- Версия сервера: 5.7.13
-- Версия PHP: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `vcompacte`
--

-- --------------------------------------------------------

--
-- Структура таблицы `dialogs`
--

CREATE TABLE IF NOT EXISTS `dialogs` (
  `id` int(11) NOT NULL,
  `hash` varchar(40) NOT NULL,
  `id_author` int(11) NOT NULL,
  `id_receiver` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `dialogs`
--

INSERT INTO `dialogs` (`id`, `hash`, `id_author`, `id_receiver`, `status`) VALUES
(2, '2f0a473c0d8fb5bfdb655882ac3a6a5d', 1, 3, NULL),
(3, 'cd7f9d060ab548505ce6c15728f0f84b', 1, 2, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL,
  `dialog_id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `id_author` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `messages`
--

INSERT INTO `messages` (`id`, `dialog_id`, `msg`, `id_author`, `date`) VALUES
(4, 2, 'QQ, Adison!!!', 1, '2016-12-20 17:23:52'),
(5, 2, 'How are you?', 1, '2016-12-20 17:24:31'),
(6, 3, 'qq, Julia!!<br />\r\nHow are you? :D', 1, '2016-12-20 17:24:58'),
(7, 2, 'I''m fine!<br />\r\nWhat about you?', 3, '2016-12-20 17:52:26');

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id_session` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `sid` varchar(10) NOT NULL,
  `time_start` datetime NOT NULL,
  `time_last` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `sessions`
--

INSERT INTO `sessions` (`id_session`, `id_user`, `sid`, `time_start`, `time_last`) VALUES
(5, 3, 'oG4tEYP61K', '2016-12-20 17:51:48', '2016-12-20 17:51:48'),
(6, 3, 'kItJrVekZ0', '2016-12-20 17:51:48', '2016-12-20 17:52:33'),
(7, 1, 'C29EBBKYuN', '2016-12-20 17:52:40', '2016-12-20 17:53:14');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `login` varchar(55) NOT NULL,
  `password` varchar(55) NOT NULL,
  `img_profile` varchar(255) NOT NULL,
  `id_role` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_user`, `name`, `login`, `password`, `img_profile`, `id_role`) VALUES
(1, '123 Admin', '123', '202cb962ac59075b964b07152d234b70', '46615-eskizy_tatu_tigry_na_grudi_na_spine_na_bedre_na_pleche_56c0362033044.jpeg', 1),
(2, '1234 Julia', '1234', '81dc9bdb52d04dc20036dbd8313ed055', '20420-profile.jpg', 2),
(3, 'qqq Adison', 'qqq', 'b2ca678b4c936f905fb82f2733f5297f', '20420-profile.jpg', 3),
(4, 'qq1 Tomas', 'qq1', '53426eecbae3ef56b1c80769c81f4722', '37170-20420-profile.jpg', 3),
(5, 'qq2 Eric', 'qq2', '5c4b8110cef10e43ff58c7c458affd0c', '58585-profile.jpg', 3),
(6, 'qwe Mark', 'qwe', '76d80224611fc919a5d54f0ff9fba446', '20420-profile.jpg', 3),
(7, 'qwer Nastya', 'qwer', '962012d09b8170d912f0669f6d7d9d07', '20420-profile.jpg', 3),
(8, 'qwert Zheckson', 'qwert', 'a384b6463fc216a5f8ecb6670f86456a', '20420-profile.jpg', 3),
(9, 'qwerty Malber', 'qwerty', 'd8578edf8458ce06fbc5bb76a58c5ca4', '20420-profile.jpg', 3),
(10, '12345678 Konor', '12345678', '25d55ad283aa400af464c76d713c07ad', '20420-profile.jpg', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `wall`
--

CREATE TABLE IF NOT EXISTS `wall` (
  `id` int(11) NOT NULL,
  `id_user_to` int(11) NOT NULL,
  `id_user_from` int(11) NOT NULL,
  `msg` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `wall`
--

INSERT INTO `wall` (`id`, `id_user_to`, `id_user_from`, `msg`, `date`) VALUES
(2, 4, 1, 'Some message for wall user 4', '2016-11-20 14:00:00'),
(4, 1, 1, 'Некоторый текст от себя', '2016-11-20 21:32:04'),
(5, 2, 1, 'Гыыыы', '2016-11-20 21:32:18'),
(6, 1, 2, 'Message from user 1234', '2016-11-20 21:33:17'),
(7, 9, 9, 'ddd', '2016-11-22 16:50:18'),
(8, 3, 10, '444', '2016-11-22 18:09:55'),
(9, 10, 1, 'ааааа', '2016-11-23 16:28:37'),
(10, 1, 9, 'tte', '2016-11-23 17:49:47'),
(11, 7, 1, 'Hoow) Hello how are u?', '2016-11-23 18:54:00'),
(12, 7, 7, 'qq, im cool what''s about u?', '2016-11-23 19:16:16'),
(13, 7, 7, '<input type="submit" alert=(''Hack'') value="hello">', '2016-11-23 19:17:05'),
(14, 7, 7, 'fix this', '2016-11-23 19:20:28'),
(15, 1, 7, 'HTML SPECIAL CHARS', '2016-11-23 19:20:55'),
(16, 7, 7, '&lt;form method=&quot;post&quot; action=&quot;attacktarget?username=badfoo&amp;amp;password=badfoo&quot;&gt; &lt;input type=&quot;hidden&quot; name=&quot;username&quot; value=&quot;badfoo&quot; &gt; &lt;input type=&quot;hidden&quot; name=&quot;password&quot; value=&quot;badfoo&quot; &gt; &lt;/form&gt;', '2016-11-23 19:43:11'),
(17, 1, 1, '&lt;input type=''submit'' value=''hello''&gt;', '2016-11-24 12:39:47'),
(18, 1, 1, 'r', '2016-12-20 17:25:04'),
(19, 1, 1, '1', '2016-12-20 17:26:36');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `dialogs`
--
ALTER TABLE `dialogs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id_session`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- Индексы таблицы `wall`
--
ALTER TABLE `wall`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `dialogs`
--
ALTER TABLE `dialogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id_session` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `wall`
--
ALTER TABLE `wall`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
