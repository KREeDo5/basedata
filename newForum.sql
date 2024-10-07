-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 07 2024 г., 22:35
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `forum`
--

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `id_parent_category` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `title`, `id_parent_category`) VALUES
(1, 'Оффтопик', NULL),
(2, 'Компьютеры', NULL),
(3, 'Девайсы', 2),
(4, 'Windows', 2),
(5, 'MacOS', 2),
(6, 'Linux', 2),
(7, 'Клавиатуры', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `message`
--

CREATE TABLE `message` (
  `id` int NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Текст сообщения',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_user` int NOT NULL COMMENT 'Автор сообщения',
  `id_thread` int NOT NULL COMMENT 'К какому треду относится сообщение',
  `visibility` enum('visible','hidden') NOT NULL DEFAULT 'visible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `message`
--

INSERT INTO `message` (`id`, `text`, `created_at`, `id_user`, `id_thread`, `visibility`) VALUES
(1, 'Переустанови винду', '2024-10-05 18:36:10', 1, 1, 'visible'),
(2, 'Решено, надо было активировать винду', '2024-10-05 18:36:39', 2, 1, 'visible'),
(3, ' у тебя 8 озу? или 16?\r\nкак вообще ноут сам по себе?', '2024-10-05 18:39:37', 1, 2, 'visible'),
(4, '8, все устраивает, все тянет вроде пока что', '2024-10-05 18:42:43', 2, 2, 'visible'),
(5, 'лучше зарядить до фула и использовать, потом опять заряжать', '2024-10-05 18:42:43', 1, 2, 'visible'),
(6, 'Нет', '2024-10-05 18:44:12', 1, 3, 'visible'),
(7, 'АВТООЧИЩЕНИЕ СТОИТ', '2024-10-05 18:44:12', 1, 3, 'hidden'),
(8, 'по умолчанию нет, в настройках можно задать время удаления', '2024-10-05 18:44:47', 1, 3, 'visible'),
(9, 'Я же говорил', '2024-10-07 16:18:03', 1, 1, 'visible'),
(10, 'Linux фигня', '2024-10-07 21:07:24', 4, 4, 'visible');

-- --------------------------------------------------------

--
-- Структура таблицы `message_image`
--

CREATE TABLE `message_image` (
  `id` int NOT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_message` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `message_image`
--

INSERT INTO `message_image` (`id`, `path`, `id_message`) VALUES
(1, 'var/library/message-images/215421581209.img', 1),
(2, 'var/library/message-images/215421581210.img', 1),
(3, 'var/library/message-images/215421581211.img', 2),
(4, 'var/library/message-images/21542158121244.img', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE `role` (
  `id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `rights` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Описание роли'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`id`, `title`, `rights`) VALUES
(1, 'Админ', 'Может \r\n- закрывать треды, \r\n- редактировать данные других \"Пользователей\", \r\n- удалять сообщения из тредов\r\n- удалять треды\r\n- добавлять категории\r\nТакже имеет права обычного пользователя.'),
(2, 'Агент поддержки', 'временная роль без прав'),
(3, 'Пользователь', 'Может создавать/закрывать свои треды, писать сообщения, подписываться/отписываться на/от пользователей, редактировать только свой профиль, пароль');

-- --------------------------------------------------------

--
-- Структура таблицы `thread`
--

CREATE TABLE `thread` (
  `id` int NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'заголовок треда',
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Содержание/описание треда (первое сообщение)',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('open','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'open' COMMENT 'закрыт тред или нет',
  `id_user` int NOT NULL COMMENT 'автор треда',
  `id_category` int NOT NULL COMMENT 'категория, к которой относится тред',
  `visibility` enum('visible','hidden') NOT NULL DEFAULT 'visible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `thread`
--

INSERT INTO `thread` (`id`, `title`, `text`, `created_at`, `status`, `id_user`, `id_category`, `visibility`) VALUES
(1, 'Как установить Английский язык Windows ?', 'Надо установить англ язык интерфейса в винде но есть ошибка ваша лицензия windows поддерживает только один язык интерфейса пробовал через редактор реестра менять значение Компьютер\\HKEY_LOCAL_MACHINE\\SYSTEM\\CurrentControlSet\\Control\\Nls\\Language менять значение InstallLanguage на айди английского языка, но при перезапуске оно возвращаеться в дефолтное', '2024-10-05 18:29:58', 'open', 2, 4, 'visible'),
(2, 'Как лучше пользоваться маком?', 'всегда держать на зарядке, или заряжать, а потом отключать и разряжать?\r\n(эир м1)', '2024-10-05 18:38:16', 'open', 2, 5, 'visible'),
(3, 'Очищается ли корзина на маке сама?', 'шапка', '2024-10-05 18:38:16', 'open', 2, 5, 'hidden'),
(4, 'Ваше мнение о Linux', 'Знатоки, расскажите что там по линуксу', '2024-10-07 20:59:33', 'closed', 5, 6, 'visible');

-- --------------------------------------------------------

--
-- Структура таблицы `thread_image`
--

CREATE TABLE `thread_image` (
  `id` int NOT NULL,
  `id_thread` int NOT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `thread_image`
--

INSERT INTO `thread_image` (`id`, `id_thread`, `path`) VALUES
(1, 3, 'var/library/thread-images/08.img'),
(2, 3, 'var/library/thread-images/09.img');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `id_role` int NOT NULL,
  `password` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `registration_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image_path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `id_role`, `password`, `login`, `name`, `registration_date`, `image_path`, `description`) VALUES
(1, 1, '12345', 'admin', 'Мать всея Руси', '2014-10-15 12:20:00', NULL, NULL),
(2, 2, 'Qwerty123', 'support1', 'Агент', '2024-10-05 18:21:15', NULL, NULL),
(3, 3, 'oqi3hrwoihrtoq', 'Gabe_Newell', 'Зарождающаяся звезда', '2024-10-05 18:56:27', 'var/library/user-images/valvegaben.png', 'Гейб Ло́ган Нью́элл, также известен под ником Ге́йбен — американский предприниматель и разработчик видеоигр. Один из основателей и генеральный директор компании Valve, занимающейся разработкой компьютерных игр и их цифровой дистрибуцией.\r\n\r\nНУ ИЛИ ПО-ПРОСТОМУ....\r\nГений, миллиардер, плейбой, филантроп '),
(4, 3, 'qB0JHO8_089', 'WoolfFromWallStreet', 'Маркелов', '2024-10-05 18:56:27', 'var/library/user-images/naberezhnaya-brugge.png', 'Посадили ни за что...\r\n'),
(5, 3, 'q1w2e3r4t5y6', 'kirill', 'Bachelor', '2024-10-07 19:49:04', NULL, 'I am COMING');

-- --------------------------------------------------------

--
-- Структура таблицы `user_has_subscribe`
--

CREATE TABLE `user_has_subscribe` (
  `id_user` int NOT NULL,
  `subscription` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `user_has_subscribe`
--

INSERT INTO `user_has_subscribe` (`id_user`, `subscription`) VALUES
(3, 4),
(3, 2),
(4, 1),
(5, 3);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_parent_category` (`id_parent_category`);

--
-- Индексы таблицы `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_thread` (`id_thread`),
  ADD KEY `id_user` (`id_user`);

--
-- Индексы таблицы `message_image`
--
ALTER TABLE `message_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_message` (`id_message`);

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `thread`
--
ALTER TABLE `thread`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_category` (`id_category`),
  ADD KEY `id_user` (`id_user`);

--
-- Индексы таблицы `thread_image`
--
ALTER TABLE `thread_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_thread` (`id_thread`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_role` (`id_role`);

--
-- Индексы таблицы `user_has_subscribe`
--
ALTER TABLE `user_has_subscribe`
  ADD KEY `subscription` (`subscription`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `message`
--
ALTER TABLE `message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `message_image`
--
ALTER TABLE `message_image`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `thread`
--
ALTER TABLE `thread`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `thread_image`
--
ALTER TABLE `thread_image`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`id_parent_category`) REFERENCES `category` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`id_thread`) REFERENCES `thread` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `message_image`
--
ALTER TABLE `message_image`
  ADD CONSTRAINT `message_image_ibfk_1` FOREIGN KEY (`id_message`) REFERENCES `message` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `thread`
--
ALTER TABLE `thread`
  ADD CONSTRAINT `thread_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `category` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `thread_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `thread_image`
--
ALTER TABLE `thread_image`
  ADD CONSTRAINT `thread_image_ibfk_1` FOREIGN KEY (`id_thread`) REFERENCES `thread` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `user_has_subscribe`
--
ALTER TABLE `user_has_subscribe`
  ADD CONSTRAINT `user_has_subscribe_ibfk_1` FOREIGN KEY (`subscription`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_has_subscribe_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
