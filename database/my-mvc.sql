-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 22 2020 г., 17:15
-- Версия сервера: 5.6.43
-- Версия PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `my-mvc`
--

-- --------------------------------------------------------

--
-- Структура таблицы `avatars`
--

CREATE TABLE `avatars` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `avatars`
--

INSERT INTO `avatars` (`id`, `user_id`, `url`) VALUES
(27, 59, 'uploads/59/f2c36a3f4cdc04aeb8fb7705fc69489863408ebc.jpg'),
(28, 59, 'uploads/59/59cf33a0827dea8c8556c2b3a42c0b012dc7b1f3.jpg'),
(29, 60, 'uploads/60/8ec7eb35f5f58fb1f771afabe500d483a8621fa5.jpg'),
(30, 60, 'uploads/60/d46924cf24d08d658a0b361c9c8070f2f28a0f55.jpg'),
(31, 61, 'uploads/61/1182ba5f25904b6f1af3b06f27009ef4de0bea04.jpg'),
(32, 61, 'uploads/61/5a0d74f309d042c31fdcc7485372bb2acc3434f8.jpg'),
(33, 61, 'uploads/61/906c76e5e3fb138f4f2f605b3f8204588b5d0c36.jpg'),
(34, 69, 'uploads/69/594c5d7d805c0b092fe395379618fda4131eac12.jpg'),
(35, 69, 'uploads/69/8d0480fa95e05625404807ba1e713c061623b048.jpg'),
(36, 69, 'uploads/69/c10be44ea0e7be5ff22d61df97162d780d9c1f94.jpg'),
(37, 69, 'uploads/69/58b10592c0b5d3e544a6222ac4aa942e05cdf287.jpg'),
(38, 70, 'uploads/70/77ae889521d3de406e42be40495419cf888ea43a.jpg'),
(39, 71, 'uploads/71/a674bbd7af0eb78e0150cf302a17cdb2ecac77fd.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` tinyint(3) UNSIGNED NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `pass`, `age`, `desc`) VALUES
(59, 'Alexandr', 'ku@mail.ru', 'b99ab1cdd4b28287d4f4749da86e88ab1bedc800', 25, 'Любимое аниме fullmetal alchemist brothers'),
(60, 'Kasandra', 'myemail1@mail.ru', 'b99ab1cdd4b28287d4f4749da86e88ab1bedc800', 18, 'lindsey stirling underground'),
(61, 'Rudolf', 'mysuperemail1@gmail.com', 'b99ab1cdd4b28287d4f4749da86e88ab1bedc800', 17, 'I love dogs'),
(69, 'Genrih', 'earthjim@yandex.ru', 'b99ab1cdd4b28287d4f4749da86e88ab1bedc800', 32, 'My name\'s Genrih'),
(70, 'Timur', 'kukushka@mail.ruw', 'b99ab1cdd4b28287d4f4749da86e88ab1bedc800', 92, 'A love apples'),
(71, 'Malibu', 'curosaki@gmail.ru', 'b99ab1cdd4b28287d4f4749da86e88ab1bedc800', 10, 'My age\'s 10');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `avatars`
--
ALTER TABLE `avatars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UsersId-User_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `avatars`
--
ALTER TABLE `avatars`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `avatars`
--
ALTER TABLE `avatars`
  ADD CONSTRAINT `UsersId-User_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
