-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 01 2020 г., 20:45
-- Версия сервера: 10.3.13-MariaDB-log
-- Версия PHP: 7.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `ishop`
--
CREATE DATABASE IF NOT EXISTS `ishop` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ishop`;

-- --------------------------------------------------------

--
-- Структура таблицы `attribute_group`
--

CREATE TABLE `attribute_group` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `attribute_group`
--

INSERT INTO `attribute_group` (`id`, `title`) VALUES
(1, 'Механизм'),
(2, 'Стекло'),
(3, 'Ремешок'),
(4, 'Корпус'),
(5, 'Индикация'),
(7, 'Тестовая группа');

-- --------------------------------------------------------

--
-- Структура таблицы `attribute_product`
--

CREATE TABLE `attribute_product` (
  `attr_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `attribute_product`
--

INSERT INTO `attribute_product` (`attr_id`, `product_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 61),
(1, 62),
(1, 63),
(1, 82),
(2, 4),
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(5, 62),
(5, 63),
(5, 82),
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(8, 63),
(12, 1),
(12, 2),
(12, 3),
(12, 4),
(18, 1),
(18, 2),
(18, 4),
(19, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `attribute_value`
--

CREATE TABLE `attribute_value` (
  `id` int(10) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL,
  `attr_group_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `attribute_value`
--

INSERT INTO `attribute_value` (`id`, `value`, `attr_group_id`) VALUES
(1, 'Механика с автоподзаводом', 1),
(2, 'Механика с ручным заводом', 1),
(3, 'Кварцевый от батарейки', 1),
(4, 'Кварцевый от солнечного аккумулятора', 1),
(5, 'Сапфировое', 2),
(6, 'Минеральное', 2),
(7, 'Полимерное', 2),
(8, 'Стальной', 3),
(9, 'Кожаный', 3),
(10, 'Каучуковый', 3),
(11, 'Полимерный', 3),
(12, 'Нержавеющая сталь', 4),
(13, 'Титановый сплав', 4),
(14, 'Латунь', 4),
(15, 'Полимер', 4),
(16, 'Керамика', 4),
(17, 'Алюминий', 4),
(18, 'Аналоговые', 5),
(19, 'Цифровые', 5),
(21, 'Тестовый аттрибут', 7);

-- --------------------------------------------------------

--
-- Структура таблицы `brand`
--

CREATE TABLE `brand` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL DEFAULT 'brand_no_image.jpg',
  `keywords` varchar(256) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `brand`
--

INSERT INTO `brand` (`id`, `title`, `alias`, `img`, `keywords`, `description`) VALUES
(1, 'Casio', 'casio', 'abt-1.jpg', NULL, 'In sit amet sapien eros Integer dolore magna aliqua'),
(2, 'Citizen', 'citizen', 'abt-2.jpg', NULL, 'In sit amet sapien eros Integer dolore magna aliqua'),
(3, 'Royal London', 'royal-london', 'abt-3.jpg', NULL, 'In sit amet sapien eros Integer dolore magna aliqua'),
(4, 'Seiko', 'seiko', 'seiko.png', NULL, NULL),
(5, 'Diesel', 'diesel', 'diesel.png', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `parent_id` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `title`, `alias`, `parent_id`, `keywords`, `description`) VALUES
(1, 'Men', 'men', 0, 'Men', 'Men'),
(2, 'Women', 'women', 0, 'Women', 'Women'),
(3, 'Kids', 'kids', 0, 'Kids', 'Kids'),
(4, 'Электронные', 'elektronnye', 1, 'Электронные', 'Электронные'),
(5, 'Механические', 'mehanicheskie', 1, 'mehanicheskie', 'mehanicheskie'),
(6, 'Casio', 'casio', 4, 'Casio', 'Casio'),
(7, 'Citizen', 'citizen', 4, 'Citizen', 'Citizen'),
(8, 'Royal London', 'royal-london', 5, 'Royal London', 'Royal London'),
(9, 'Seiko', 'seiko', 5, 'Seiko', 'Seiko'),
(10, 'Epos', 'epos', 5, 'Epos', 'Epos'),
(11, 'Электронные', 'elektronnye-11', 2, 'Электронные', 'Электронные'),
(12, 'Механические', 'mehanicheskie-12', 2, 'Механические', 'Механические'),
(13, 'Adriatica', 'adriatica', 11, 'Adriatica', 'Adriatica'),
(14, 'Anne Klein', 'anne-klein', 12, 'Anne Klein', 'Anne Klein'),
(16, 'Тестовая категория', 'testovaya-kategoriya', 0, '', ''),
(17, 'Тестовая категория 2', 'testovaya-kategoriya-2', 16, '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
--

CREATE TABLE `comment` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `product_id` int(10) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `rate` int(10) NOT NULL DEFAULT 0,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Дамп данных таблицы `comment`
--

INSERT INTO `comment` (`id`, `parent_id`, `product_id`, `content`, `date`, `update_at`, `status`, `rate`, `user_id`) VALUES
(3, 0, 1, '<p>Lorem ipsum dolor sit amet. Blanditiis praesentium voluptatum\r\n				deleniti atque. Autem vel illum, qui blanditiis praesentium voluptatum deleniti atque corrupti.\r\n				Dolor repellendus cum soluta nobis. Corporis suscipit laboriosam, nisi ut enim. Debitis aut\r\n				perferendis doloribus asperiores repellat. sint, obcaecati cupiditate non numquam eius. Itaque\r\n				earum rerum facilis. Similique sunt in ea commodi. Dolor repellendus numquam eius modi. Quam\r\n				nihil molestiae consequatur, vel illum, qui ratione voluptatem accusantium doloremque.</p>\r\n', '2020-03-05 20:15:33', NULL, 1, 5, 1),
(4, 3, 1, '<p>Dolor sit, amet, consectetur, adipisci velit. Aperiam eaque ipsa, quae. Amet, consectetur, adipisci velit, sed quia consequuntur magni dolores. Ab illo inventore veritatis et quasi architecto. Quisquam est, omnis voluptas nulla. Obcaecati cupiditate non numquam eius modi tempora. Corporis suscipit laboriosam, nisi ut labore et aut reiciendis.</p>\r\n', '2020-03-05 20:40:13', NULL, 1, 3, 2),
(5, 4, 1, '<p><strong>Amet</strong>, consectetur, adipisci velit, sed ut labore et dolore. Maiores alias consequatur aut perferendis doloribus asperiores. Voluptas nulla vero eos. Minima veniam, quis nostrum exercitationem ullam corporis. Atque corrupti, quos dolores eos, qui blanditiis praesentium voluptatum deleniti atque corrupti. Quibusdam et harum quidem rerum necessitatibus saepe eveniet, ut enim ipsam. Magni dolores et dolorum fuga nostrum exercitationem ullam. Eligendi optio, cumque nihil molestiae consequatur.</p>\r\n', '2020-03-05 20:40:36', NULL, 1, -1, 3),
(6, 3, 1, '<p>Ex ea voluptate velit esse, quam nihil impedit, quo minus id quod. Totam rem aperiam eaque ipsa, quae ab illo. Minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid. Iste natus error sit voluptatem. Sunt, explicabo deleniti atque corrupti, quos dolores et expedita.</p>\r\n', '2020-03-05 20:40:43', NULL, 1, 1, 4),
(8, 0, 1, '<p>Eum iure reprehenderit, qui dolorem eum fugiat. Sint et expedita distinctio velit. Architecto beatae vitae dicta sunt, explicabo unde omnis. Qui aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto. Nemo enim ipsam voluptatem quia. Eos, qui ratione voluptatem sequi nesciunt, neque porro. A sapiente delectus, ut enim ipsam voluptatem, quia non recusandae architecto beatae.</p>\r\n', '2020-03-06 16:28:48', NULL, 1, 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `currency`
--

CREATE TABLE `currency` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `code` varchar(3) NOT NULL,
  `symbol_left` varchar(10) NOT NULL,
  `symbol_right` varchar(10) NOT NULL,
  `value` float(15,4) NOT NULL,
  `course` float(15,4) NOT NULL,
  `base` enum('0','1') NOT NULL,
  `update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `currency`
--

INSERT INTO `currency` (`id`, `title`, `code`, `symbol_left`, `symbol_right`, `value`, `course`, `base`, `update_at`) VALUES
(1, 'гривна', 'UAH', '', ' грн.', 0.0367, 27.2248, '0', '2020-03-06 17:54:37'),
(2, 'доллар', 'USD', '$ ', '', 0.0148, 67.5175, '0', '2020-03-06 17:54:37'),
(3, 'Евро', 'EUR', '€ ', '', 0.0132, 75.8424, '0', '2020-03-06 17:54:37'),
(4, 'Рубль', 'RUR', '', ' руб.', 1.0000, 1.0000, '1', '2020-02-22 18:36:55');

-- --------------------------------------------------------

--
-- Структура таблицы `gallery`
--

CREATE TABLE `gallery` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `gallery`
--

INSERT INTO `gallery` (`id`, `product_id`, `img`) VALUES
(1, 2, 's-1.jpg'),
(2, 2, 's-2.jpg'),
(3, 2, 's-3.jpg'),
(13, 61, '248defc5addc2a0a48661f9b8bdf5dcf.jpg'),
(14, 61, '15c51822b3732a379de1f839b0949b8f.jpg'),
(15, 61, 'c49eee753c2db814cb67c78c0e6d431b.jpg'),
(16, 62, '773e06c5a014aad26b013dc8198ce284.jpg'),
(17, 62, '56324195c6318c60adfff1506dae094e.jpg'),
(18, 63, '5c2d76205be0ecc5fed4cbef87a33c2b.jpg'),
(19, 63, '04633d94a379862e70a4cb442da276f5.jpg'),
(20, 63, 'b36571900efbaaf2a96b0f9350ba3d2a.jpg'),
(36, 82, 'cbfb5c5a525337d7a61010f7ed2a7f08.jpg'),
(39, 82, '2898ce1ba387291ae35dbb0047fa3118.jpg'),
(40, 82, '3c0b9faf215f1eb8b45b24af3dda1e19.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `modification`
--

CREATE TABLE `modification` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `modification`
--

INSERT INTO `modification` (`id`, `product_id`, `title`, `price`) VALUES
(5, 2, 'Silver', 80),
(6, 2, 'Red', 70),
(27, 1, 'Silver', 300),
(28, 1, 'Black', 300),
(29, 1, 'Dark Black', 305),
(30, 1, 'Red', 310);

-- --------------------------------------------------------

--
-- Структура таблицы `order`
--

CREATE TABLE `order` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NULL DEFAULT NULL,
  `currency` varchar(3) NOT NULL,
  `sum` float NOT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `order`
--

INSERT INTO `order` (`id`, `user_id`, `status`, `date`, `update_at`, `currency`, `sum`, `note`) VALUES
(6, 2, '0', '2019-10-11 17:59:21', NULL, 'EUR', 484, ''),
(9, 2, '0', '2019-10-11 18:03:07', NULL, 'EUR', 352, ''),
(10, 2, '0', '2019-10-11 18:06:57', NULL, 'USD', 400, ''),
(11, 2, '0', '2019-10-12 15:47:59', '2020-02-22 16:32:05', 'USD', 400, ''),
(12, 2, '0', '2019-10-12 15:52:22', '2020-02-22 16:32:29', 'USD', 150, 'Test'),
(13, 2, '1', '2019-10-12 15:54:03', '2020-02-22 16:32:42', 'EUR', 484, 'Test 2'),
(14, 1, '2', '2019-10-12 16:01:29', NULL, 'EUR', 352, '');

-- --------------------------------------------------------

--
-- Структура таблицы `order_product`
--

CREATE TABLE `order_product` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `order_product`
--

INSERT INTO `order_product` (`id`, `order_id`, `product_id`, `qty`, `title`, `price`) VALUES
(1, 6, 3, 1, 'Casio GA-1000-1AER', 352),
(2, 6, 2, 1, 'Casio MQ-24-7BUL', 61.6),
(3, 6, 2, 2, 'Casio MQ-24-7BUL (Silver)', 70.4),
(4, 9, 3, 1, 'Casio GA-1000-1AER', 352),
(5, 10, 3, 1, 'Casio GA-1000-1AER', 400),
(6, 11, 3, 1, 'Casio GA-1000-1AER', 400),
(7, 12, 2, 1, 'Casio MQ-24-7BUL', 70),
(8, 12, 2, 2, 'Casio MQ-24-7BUL (Silver)', 80),
(9, 13, 3, 1, 'Casio GA-1000-1AER', 352),
(10, 13, 2, 1, 'Casio MQ-24-7BUL', 61.6),
(11, 13, 2, 2, 'Casio MQ-24-7BUL (Silver)', 70.4),
(12, 14, 3, 1, 'Casio GA-1000-1AER', 352);

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE `product` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `brand_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `price` float NOT NULL DEFAULT 0,
  `old_price` float NOT NULL DEFAULT 0,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `img` varchar(255) NOT NULL DEFAULT 'no_image.jpg',
  `hit` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id`, `category_id`, `brand_id`, `title`, `alias`, `content`, `price`, `old_price`, `status`, `keywords`, `description`, `img`, `hit`) VALUES
(1, 6, 1, 'Casio MRP-700-1AVEF', 'casio-mrp-700-1avef', '', 300, 0, '1', '', '', 'p-1.png', '1'),
(2, 6, 1, 'Casio MQ-24-7BUL', 'casio-mq-24-7bul', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tristique, diam in consequat iaculis, est purus iaculis mauris, imperdiet facilisis ante ligula at nulla. Quisque volutpat nulla risus, id maximus ex aliquet ut. Suspendisse potenti. Nulla varius lectus id turpis dignissim porta. Quisque magna arcu, blandit quis felis vehicula, feugiat gravida diam. Nullam nec turpis ligula. Aliquam quis blandit elit, ac sodales nisl. Aliquam eget dolor eget elit malesuada aliquet. In varius lorem lorem, semper bibendum lectus lobortis ac.</p>\n\n                                            <p>Mauris placerat vitae lorem gravida viverra. Mauris in fringilla ex. Nulla facilisi. Etiam scelerisque tincidunt quam facilisis lobortis. In malesuada pulvinar neque a consectetur. Nunc aliquam gravida purus, non malesuada sem accumsan in. Morbi vel sodales libero.</p>', 70, 80, '1', '111', '222', 'p-2.png', '1'),
(3, 6, 1, 'Casio GA-1000-1AER', 'casio-ga-1000-1aer', '', 400, 0, '1', '', '', 'p-3.png', '1'),
(4, 6, 2, 'Citizen JP1010-00E', 'citizen-jp1010-00e', NULL, 400, 0, '1', NULL, NULL, 'p-4.png', '1'),
(5, 7, 2, 'Citizen BJ2111-08E', 'citizen-bj2111-08e', NULL, 500, 0, '1', NULL, NULL, 'p-5.png', '1'),
(6, 7, 2, 'Citizen AT0696-59E', 'citizen-at0696-59e', NULL, 350, 355, '1', NULL, NULL, 'p-6.png', '1'),
(7, 6, 3, 'Q&Q Q956J302Y', 'q-and-q-q956j302y', NULL, 20, 0, '1', NULL, NULL, 'p-7.png', '1'),
(8, 6, 4, 'Royal London 41040-01', 'royal-london-41040-01', NULL, 90, 0, '1', NULL, NULL, 'p-8.png', '1'),
(9, 6, 4, 'Royal London 20034-02', 'royal-london-20034-02', NULL, 110, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(10, 6, 4, 'Royal London 41156-02', 'royal-london-41156-02', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tristique, diam in consequat iaculis, est purus iaculis mauris, imperdiet facilisis ante ligula at nulla. Quisque volutpat nulla risus, id maximus ex aliquet ut. Suspendisse potenti. Nulla varius lectus id turpis dignissim porta. Quisque magna arcu, blandit quis felis vehicula, feugiat gravida diam. Nullam nec turpis ligula. Aliquam quis blandit elit, ac sodales nisl. Aliquam eget dolor eget elit malesuada aliquet. In varius lorem lorem, semper bibendum lectus lobortis ac.</p>\n\n                                            <p>Mauris placerat vitae lorem gravida viverra. Mauris in fringilla ex. Nulla facilisi. Etiam scelerisque tincidunt quam facilisis lobortis. In malesuada pulvinar neque a consectetur. Nunc aliquam gravida purus, non malesuada sem accumsan in. Morbi vel sodales libero.</p>', 100, 0, '1', NULL, NULL, 'no_image.jpg', '1'),
(11, 3, 2, 'Тестовый товар', 'testovyy-tovar', 'контент...', 10, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(12, 7, 2, 'Часы 1', 'chasy-1', NULL, 100, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(13, 7, 2, 'Часы 2', 'chasy-2', NULL, 105, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(14, 7, 2, 'Часы 3', 'chasy-3', NULL, 110, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(15, 7, 2, 'Часы 4', 'chasy-4', NULL, 115, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(16, 7, 2, 'Часы 5', 'chasy-5', NULL, 115, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(17, 7, 2, 'Часы 6', 'chasy-6', NULL, 120, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(20, 7, 2, 'Часы 7', 'chasy-7', NULL, 120, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(21, 7, 2, 'Часы 8', 'chasy-8', NULL, 120, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(22, 7, 2, 'Часы 9', 'chasy-9', NULL, 125, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(23, 7, 2, 'Часы 10', 'chasy-10', NULL, 125, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(24, 7, 2, 'Часы 11', 'chasy-11', NULL, 125, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(25, 7, 2, 'Часы 12', 'chasy-12', NULL, 125, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(26, 7, 2, 'Часы 13', 'chasy-13', NULL, 125, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(27, 7, 2, 'Часы 14', 'chasy-14', NULL, 125, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(28, 7, 2, 'Часы 15', 'chasy-15', NULL, 125, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(29, 7, 2, 'Часы 16', 'chasy-16', NULL, 125, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(30, 7, 2, 'Часы 17', 'chasy-17', NULL, 125, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(31, 7, 2, 'Часы 18', 'chasy-18', NULL, 125, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(32, 7, 2, 'Часы 19', 'chasy-19', NULL, 125, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(33, 7, 2, 'Часы 20', 'chasy-20', NULL, 125, 0, '1', NULL, NULL, 'no_image.jpg', '0'),
(61, 17, NULL, 'Новый товар 1', 'novyy-tovar-1', '', 100, 110, '1', '', '', '3fccdf2a4e086a11e68936c77d13380c.png', '0'),
(62, 17, NULL, 'Новый товар 2', 'novyy-tovar-2', '', 100, 110, '1', '', '', '1782efc38a5c811e1739be4c796f8d83.png', '0'),
(63, 17, NULL, 'Новый товар 3', 'novyy-tovar-3', '', 100, 110, '1', '', '', '92921b1b5498214d5b9574e20c74601a.png', '0'),
(82, 17, NULL, 'Новый товар 4', 'novyy-tovar-4', '', 100, 110, '1', '', '', 'dd93f97ca5b03b3a097867799423b3ac.png', '0');

-- --------------------------------------------------------

--
-- Структура таблицы `related_product`
--

CREATE TABLE `related_product` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `related_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `related_product`
--

INSERT INTO `related_product` (`product_id`, `related_id`) VALUES
(1, 2),
(1, 5),
(2, 5),
(2, 10),
(5, 1),
(5, 7),
(5, 8),
(61, 1),
(62, 2),
(62, 13),
(63, 1),
(63, 2),
(63, 3),
(82, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE `role` (
  `id` int(10) UNSIGNED NOT NULL,
  `role` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`id`, `role`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'no_image.jpg',
  `role` int(10) UNSIGNED NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `email`, `name`, `address`, `avatar`, `role`) VALUES
(1, 'admin', '$2y$10$.Vx2.uPlxCWsiobtFDlsHedJPb.OPVpjxbgh41LCS2/3hPGhpQ84q', 'admin@mail.ru', 'Admin', '1', 'avatar1.jpg', 1),
(2, 'user1', '$2y$10$7QvD9AiyE4SS0EVwsFyv3OvQqJpDfFs7Z6TF5cAI5Vb51KRIcEUcK', 'mishin.nikolay.d270893@yandex.ru', 'User1', '1', 'avatar2.jpg', 2),
(3, 'user2', '$2y$10$Xv6p.JDMkPuniMyMhPblSeAIVKQSeEeQs7MZrIbAA71O2KKfH.aTi', '2@1.ru', 'User2', '222', 'avatar3.jpg', 2),
(4, 'user3', '$2y$10$QkJYBUkwv7RJAbQVZDCIqOuzsOqFKKzr/2P.X5cmhqfuaxntK3lya', '3@3.ru', 'user3', '3', 'avatar4.jpg', 2);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `attribute_group`
--
ALTER TABLE `attribute_group`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `attribute_product`
--
ALTER TABLE `attribute_product`
  ADD PRIMARY KEY (`attr_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `attribute_value`
--
ALTER TABLE `attribute_value`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `value` (`value`),
  ADD KEY `attr_group_id` (`attr_group_id`);

--
-- Индексы таблицы `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alias` (`alias`);

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alias` (`alias`);

--
-- Индексы таблицы `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Индексы таблицы `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`);

--
-- Индексы таблицы `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `modification`
--
ALTER TABLE `modification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `currency` (`currency`);

--
-- Индексы таблицы `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alias` (`alias`),
  ADD KEY `category_id` (`category_id`,`brand_id`),
  ADD KEY `hit` (`hit`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Индексы таблицы `related_product`
--
ALTER TABLE `related_product`
  ADD PRIMARY KEY (`product_id`,`related_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `related_id` (`related_id`);

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role` (`role`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `attribute_group`
--
ALTER TABLE `attribute_group`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `attribute_value`
--
ALTER TABLE `attribute_value`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT для таблицы `modification`
--
ALTER TABLE `modification`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `order`
--
ALTER TABLE `order`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT для таблицы `order_product`
--
ALTER TABLE `order_product`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `attribute_product`
--
ALTER TABLE `attribute_product`
  ADD CONSTRAINT `attr_id` FOREIGN KEY (`attr_id`) REFERENCES `attribute_value` (`id`),
  ADD CONSTRAINT `attribute_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Ограничения внешнего ключа таблицы `attribute_value`
--
ALTER TABLE `attribute_value`
  ADD CONSTRAINT `attr_group_id` FOREIGN KEY (`attr_group_id`) REFERENCES `attribute_group` (`id`);

--
-- Ограничения внешнего ключа таблицы `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Ограничения внешнего ключа таблицы `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `gallery_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Ограничения внешнего ключа таблицы `modification`
--
ALTER TABLE `modification`
  ADD CONSTRAINT `modification_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Ограничения внешнего ключа таблицы `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `currency` FOREIGN KEY (`currency`) REFERENCES `currency` (`code`),
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ограничения внешнего ключа таблицы `order_product`
--
ALTER TABLE `order_product`
  ADD CONSTRAINT `order_id` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Ограничения внешнего ключа таблицы `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `brand_id` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`),
  ADD CONSTRAINT `category_id` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Ограничения внешнего ключа таблицы `related_product`
--
ALTER TABLE `related_product`
  ADD CONSTRAINT `related_id` FOREIGN KEY (`related_id`) REFERENCES `attribute_value` (`id`),
  ADD CONSTRAINT `related_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `role_id` FOREIGN KEY (`role`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
