-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 17-01-2015 a las 20:22:21
-- Versión del servidor: 5.5.38-MariaDB-cll-lve
-- Versión de PHP: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `gimmeahi_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banned_ips`
--

CREATE TABLE IF NOT EXISTS `banned_ips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `ban_time` int(11) NOT NULL,
  `expire_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blog_categories`
--

CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blog_post`
--

CREATE TABLE IF NOT EXISTS `blog_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `message` longtext NOT NULL,
  `cat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `linked_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `action_time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `comments`
--

INSERT INTO `comments` (`id`, `linked_id`, `type`, `action_time`, `user_id`, `message`, `approved`) VALUES
(1, 16, 2, 1419303539, 1, 'eeeee', 1),
(2, 16, 2, 1419304687, 2, 'Ã±alalpo', 1),
(3, 15, 2, 1419348626, 2, 'aprobacion requerida', 1),
(4, 15, 2, 1419348780, 2, 'aprorr', 1),
(5, 15, 2, 1419348894, 2, 'eeee', 1),
(6, 15, 2, 1419349014, 2, 'fdff', 1),
(7, 15, 2, 1419352393, 2, 'dÃ±clfÃ±', 0),
(8, 16, 2, 1419352484, 2, 'tfgf', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `daily_stats`
--

CREATE TABLE IF NOT EXISTS `daily_stats` (
  `id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `global_points` int(11) NOT NULL,
  `global_coins` int(11) NOT NULL,
  `online_people` int(11) NOT NULL,
  `global_hits` int(11) NOT NULL,
  `new_visitors` int(11) NOT NULL,
  `new_users` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fav`
--

CREATE TABLE IF NOT EXISTS `fav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `linked_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_time` int(11) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `id` bigint(7) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(7) NOT NULL DEFAULT '0',
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `time_created` int(11) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hits`
--

CREATE TABLE IF NOT EXISTS `hits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` text NOT NULL,
  `time` int(11) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_activity`
--

CREATE TABLE IF NOT EXISTS `log_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitor_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `url` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `private_messages`
--

CREATE TABLE IF NOT EXISTS `private_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profile_messages`
--

CREATE TABLE IF NOT EXISTS `profile_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `time_created` int(11) NOT NULL,
  `approved` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ranks`
--

CREATE TABLE IF NOT EXISTS `ranks` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `show_name` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rdl_cats`
--

CREATE TABLE IF NOT EXISTS `rdl_cats` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rdl_items`
--

CREATE TABLE IF NOT EXISTS `rdl_items` (
  `id` bigint(7) NOT NULL AUTO_INCREMENT,
  `name` text,
  `desc` text,
  `cat` int(11) NOT NULL,
  `url` text,
  `thumb` text,
  `points` int(11) NOT NULL DEFAULT '0',
  `downloads` int(11) NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relation_table`
--

CREATE TABLE IF NOT EXISTS `relation_table` (
  `user_id` int(11) NOT NULL,
  `second_id` int(11) NOT NULL,
  `relation_type` int(11) NOT NULL,
  `action_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `relation_table`
--

INSERT INTO `relation_table` (`user_id`, `second_id`, `relation_type`, `action_time`) VALUES
(2, 1, 0, 0),
(2, 3, 3, 1415493754),
(1, 2, 0, 1415533746),
(3, 2, 1, 1415533793),
(1, 3, 1, 1419604785);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requests`
--

CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request` text NOT NULL,
  `time` int(11) NOT NULL,
  `ip` text NOT NULL,
  `usercode` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `share`
--

CREATE TABLE IF NOT EXISTS `share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `linked_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_time` int(11) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `synth-surv_users`
--

CREATE TABLE IF NOT EXISTS `synth-surv_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `linked_id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `ip` text NOT NULL,
  `email` text NOT NULL,
  `reg_time` int(11) NOT NULL,
  `last_connection` int(11) NOT NULL,
  `session_id` text NOT NULL,
  `unique_id` text NOT NULL,
  `client_version` text NOT NULL,
  `gameplay_time` int(11) NOT NULL,
  `is_premium` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket`
--

CREATE TABLE IF NOT EXISTS `ticket` (
  `id` bigint(7) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(7) NOT NULL DEFAULT '0',
  `ticket_type` int(11) NOT NULL,
  `title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `creation_time` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(7) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `ip` text NOT NULL,
  `user_agent` text NOT NULL,
  `reg_time` int(11) NOT NULL,
  `started_conn_time` int(11) NOT NULL,
  `last_activity` int(11) NOT NULL,
  `online_time` int(11) NOT NULL,
  `visitor_id` int(11) NOT NULL,
  `real_name` text NOT NULL,
  `email` text NOT NULL,
  `code` text NOT NULL,
  `activation` longtext NOT NULL,
  `prem_days` int(11) NOT NULL,
  `ref_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `coins` bigint(20) NOT NULL,
  `exp` bigint(20) NOT NULL,
  `lvl` bigint(20) NOT NULL,
  `avatar` text NOT NULL,
  `banner` text NOT NULL,
  `banner_bcolor` text NOT NULL,
  `banner_mosaic` int(11) NOT NULL DEFAULT '1',
  `gender` tinyint(4) NOT NULL DEFAULT '0',
  `birthdate` text NOT NULL,
  `location` text NOT NULL,
  `skype` text NOT NULL,
  `website_title` text NOT NULL,
  `website_url` text NOT NULL,
  `personal_text` text NOT NULL,
  `approve_pmsgs` tinyint(4) NOT NULL,
  `rank_id` tinyint(4) NOT NULL,
  `status_id` tinyint(4) NOT NULL,
  `visit_array` longtext NOT NULL,
  `rem_visits` int(11) NOT NULL,
  `dyn_mult` int(11) NOT NULL DEFAULT '0',
  `discount` int(11) NOT NULL,
  `daily_points` longtext NOT NULL,
  `daily_coins` longtext NOT NULL,
  `new_mp` int(11) NOT NULL,
  `claimed_dbonus` int(11) NOT NULL,
  `p_hits` int(11) NOT NULL,
  `ban_time` int(11) NOT NULL,
  `ban_duration` int(11) NOT NULL,
  `ban_reason` text NOT NULL,
  `rank` text NOT NULL,
  `rank_duration` int(11) NOT NULL,
  `last_visitors` longtext NOT NULL,
  `show_real_name` int(11) NOT NULL DEFAULT '-1',
  `show_location` int(11) NOT NULL DEFAULT '-1',
  `show_gender` int(11) NOT NULL DEFAULT '-1',
  `show_age` int(11) NOT NULL DEFAULT '-1',
  `show_skype` int(11) NOT NULL DEFAULT '-1',
  `show_mail` int(11) NOT NULL DEFAULT '-1',
  `show_banner` int(11) NOT NULL DEFAULT '-1',
  `show_moods` int(11) NOT NULL DEFAULT '-1',
  `show_avatar` int(11) NOT NULL DEFAULT '-1',
  `show_last_act` int(11) NOT NULL DEFAULT '-1',
  `show_visitors` int(11) NOT NULL DEFAULT '-1',
  `show_friends` int(11) NOT NULL DEFAULT '-1',
  `show_follow` int(11) NOT NULL DEFAULT '-1',
  `show_badges` int(11) NOT NULL DEFAULT '-1',
  `allow_approve_mood_reply` tinyint(1) NOT NULL DEFAULT '0',
  `allow_profile_msgs` tinyint(1) NOT NULL,
  `allow_approve_profile_messages` tinyint(1) NOT NULL,
  `allow_friend_req` tinyint(1) NOT NULL DEFAULT '0',
  `allow_newsletters` tinyint(1) NOT NULL,
  `allow_hide_online` tinyint(1) NOT NULL DEFAULT '0',
  `allow_private_msgs` tinyint(1) NOT NULL,
  `allow_warn_onleave` tinyint(1) NOT NULL DEFAULT '1',
  `timezone` smallint(6) NOT NULL,
  `date_format` tinyint(4) NOT NULL DEFAULT '-1',
  `detect_dst` tinyint(4) NOT NULL DEFAULT '1',
  `lang` text NOT NULL,
  KEY `id` (`id`),
  FULLTEXT KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `ip`, `user_agent`, `reg_time`, `started_conn_time`, `last_activity`, `online_time`, `visitor_id`, `real_name`, `email`, `code`, `activation`, `prem_days`, `ref_id`, `points`, `coins`, `exp`, `lvl`, `avatar`, `banner`, `banner_bcolor`, `banner_mosaic`, `gender`, `birthdate`, `location`, `skype`, `website_title`, `website_url`, `personal_text`, `approve_pmsgs`, `rank_id`, `status_id`, `visit_array`, `rem_visits`, `dyn_mult`, `discount`, `daily_points`, `daily_coins`, `new_mp`, `claimed_dbonus`, `p_hits`, `ban_time`, `ban_duration`, `ban_reason`, `rank`, `rank_duration`, `last_visitors`, `show_real_name`, `show_location`, `show_gender`, `show_age`, `show_skype`, `show_mail`, `show_banner`, `show_moods`, `show_avatar`, `show_last_act`, `show_visitors`, `show_friends`, `show_follow`, `show_badges`, `allow_approve_mood_reply`, `allow_profile_msgs`, `allow_approve_profile_messages`, `allow_friend_req`, `allow_newsletters`, `allow_hide_online`, `allow_private_msgs`, `allow_warn_onleave`, `timezone`, `date_format`, `detect_dst`, `lang`) VALUES
(1, 'Ikillnukes', '3d0771e2546a1c422fe1b33fc25da81c', '79.146.192.147', '', 1413211405, 1419782782, 1419794131, 2523, 0, 'XSS16', '', 'D0BFE250-AA57-D97E-9002-4D58D0E5200D', '5f0815f352eff837bf81114d6582d329', 0, 0, 0, 0, 0, 0, 'http://coad.net/Blog/Images/AnnRegulAvatar.jpg', '', 'FFFFFF', 1, 0, '26-12-2008', '', '', '', '', '', 0, 0, 0, '', 0, 1, 0, '', '', 0, 0, 0, 0, 0, '', '', 0, '', 4, 0, 0, 0, 0, 0, 0, 4, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, ''),
(2, 'RandomGuy', '81dc9bdb52d04dc20036dbd8313ed055', '2.138.97.246', '', 1413991738, 1419352380, 1419352490, 415, 0, '', '', 'B7D8C76F-ABED-4EB4-97E5-CB8570D28D26', '47f283bb08e09299fc55f012d5a3b64c', 0, 0, 0, 0, 0, 0, 'http://hablemosdemisterio.com/wp-content/uploads/2011/02/MUERTE.jpg', 'https://images.blogthings.com/thecolorfulpatterntest/pattern-1.png', 'FF5C5C', 1, 1, '18-7-1998', '', '', '', '', '', 0, 0, 0, '', 0, 1, 0, '', '', 0, 0, 0, 0, 0, '', '', 0, 'a:2:{i:3;a:3:{s:2:"id";s:1:"3";s:4:"time";i:1419427398;s:5:"times";i:1;}i:1;a:3:{s:2:"id";s:1:"1";s:4:"time";i:1419724558;s:5:"times";i:72;}}', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 4, 4, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 1, ''),
(3, 'RandomGuy2', '202cb962ac59075b964b07152d234b70', '2.138.70.23', '', 1415483293, 1419426777, 1419427442, 0, 0, '', 'alvaro.rg.98@gmail.com', '7F269F47-C04B-94F8-D3D3-C6F6E47CA24F', '', 0, 0, 0, 0, 0, 0, '', '', '', 1, 0, '', '', '', '', '', '', 0, 0, 0, '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, '', '', 0, 'a:1:{i:1;a:3:{s:2:"id";s:1:"1";s:4:"time";i:1419604805;s:5:"times";i:2;}}', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, -1, 0, 0, 0, 0, 1, ''),
(4, 'koko', '37f525e2b6fc3cb4abd882f708ab80eb', '46.249.70.103', '', 1419096976, 0, 1419098401, 0, 0, 'Juanjoi', '', '12A6B3A5-E44A-B94F-BF80-98B9D68C05E2', 'e43a20aa699da354fb7713940353883a', 0, -2147483648, 0, 0, 0, 0, '', '', 'FFFFFF', 1, 0, '1-4-2014', '', '', '', '', '', 0, 0, 0, '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, '', '', 0, '', -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 0, 0, 0, 0, 0, -1, 0, 0, 0, -1, 1, ''),
(15, 'iiii', '807e493ae27918547e83f4e9e80a80d0', '2.138.65.119', '', 1421544027, 0, 1421544027, 0, 0, '', 'iiii@iiii.com', '5233D2C7-08EE-C163-8783-A3BFA1A945FD', '', 0, 0, 0, 0, 0, 0, '', '', '', 1, 0, '', '', '', '', '', '', 0, 0, 0, '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, '', '', 0, '', -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 0, 0, 0, 0, 0, 0, 0, 1, 0, -1, 1, ''),
(14, 'eeeo', 'ff2f24f8b6d253bb5a8bc55728ca7372', '2.138.65.119', '', 1421543951, 0, 1421543951, 0, 0, '', 'rrr@gmail.com', '5233D2C7-08EE-C163-8783-A3BFA1A945FD', '', 0, 0, 0, 0, 0, 0, '', '', '', 1, 0, '', '', '', '', '', '', 0, 0, 0, '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, '', '', 0, '', -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 0, 0, 0, 0, 0, 0, 0, 1, 0, -1, 1, ''),
(13, 'eoeoeo', 'ebe1b49e3c01a7ed012ed737235fcc3b', '2.138.65.119', '', 1421543862, 0, 1421543862, 0, 0, '', 'eeee@gmail.com', '5233D2C7-08EE-C163-8783-A3BFA1A945FD', '', 0, 0, 0, 0, 0, 0, '', '', '', 1, 0, '', '', '', '', '', '', 0, 0, 0, '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, '', '', 0, '', -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 0, 0, 0, 0, 0, 0, 0, 1, 0, -1, 1, ''),
(12, 'test', '0b4e7a0e5fe84ad35fb5f95b9ceeac79', '2.138.65.119', '', 1421543819, 0, 1421543819, 0, 0, '', 'aaa@gmail.com', '5233D2C7-08EE-C163-8783-A3BFA1A945FD', '', 0, 0, 0, 0, 0, 0, '', '', '', 1, 0, '', '', '', '', '', '', 0, 0, 0, '', 0, 0, 0, '', '', 0, 0, 0, 0, 0, '', '', 0, '', -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 0, 0, 0, 0, 0, 0, 0, 1, 0, -1, 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_moods`
--

CREATE TABLE IF NOT EXISTS `user_moods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `creation_time` int(11) NOT NULL,
  `message` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Volcado de datos para la tabla `user_moods`
--

INSERT INTO `user_moods` (`id`, `user_id`, `creation_time`, `message`) VALUES
(1, 1, 1419210530, 'eoeoeoe'),
(2, 1, 1419217356, 'a\r\na\r\na\r\na\r\na\r\na\r\naa\r\na\r\na\r\na\r\na\r\na\r\na\r\na\r\na\r\na\r\na\r\na\r\na\r\naa\r\na\r\na\r\na\r\na\r\na\r\na\r\na\r\naa\r\n'),
(3, 1, 1419217404, 'a<br />\r\na<br />\r\na<br />\r\na<br />\r\na<br />\r\na<br />\r\na<br />\r\na<br />\r\na<br />\r\na<br />\r\na<br />\r\na<br />\r\n<br />\r\naa<br />\r\n<br />\r\n'),
(4, 1, 1419275884, 'flood'),
(5, 1, 1419277157, 'eeee'),
(6, 1, 1419277291, 'eee'),
(7, 1, 1419277348, 'e'),
(8, 1, 1419277439, 'eeee'),
(9, 1, 1419277580, 'eee'),
(10, 1, 1419277621, 'ee'),
(11, 1, 1419277810, 'ee'),
(12, 1, 1419277883, 'ee'),
(13, 1, 1419277930, 'ee'),
(14, 1, 1419278037, 'eee'),
(15, 1, 1419278227, 'eee'),
(16, 1, 1419278279, 'ee'),
(17, 1, 1419558520, 'ttttt'),
(18, 1, 1419558819, 'gggg'),
(19, 1, 1419558889, 'ddd'),
(20, 1, 1419558969, 'lll'),
(21, 1, 1419559144, 'llll');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `visitors`
--

CREATE TABLE IF NOT EXISTS `visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` text NOT NULL,
  `user_agent` text NOT NULL,
  `reg_time` int(11) NOT NULL,
  `last_activity` int(11) NOT NULL,
  `refer_id` int(11) NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '1',
  `points` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=135 ;

--
-- Volcado de datos para la tabla `visitors`
--

INSERT INTO `visitors` (`id`, `ip`, `user_agent`, `reg_time`, `last_activity`, `refer_id`, `hits`, `points`) VALUES
(1, '79.146.192.147', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36', 1413397221, 1413679872, 0, 78, 0),
(13, '66.249.69.22', 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 1414080676, 1417163404, 0, 6, 0),
(3, '66.249.69.9', 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 1413451625, 1416874406, 0, 13, 0),
(4, '208.53.133.34', 'Mozilla/5.0 (X11; Linux i686; rv:12.0) Gecko/20100101 Firefox/12.0', 1413459968, 1418892479, 0, 14, 0),
(5, '192.99.150.7', 'Mozilla/5.0 (X11; Linux i686; rv:12.0) Gecko/20100101 Firefox/12.0', 1413462021, 1413462106, 0, 1, 0),
(6, '107.189.165.245', 'Mozilla/5.0 (X11; Linux i686; rv:12.0) Gecko/20100101 Firefox/12.0', 1413462322, 1413462322, 0, 1, 0),
(7, '83.45.85.166', 'Mozilla/5.0 (Linux; U; Android 2.3.6; es-es; GT-S5570I Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1', 1413474859, 1414062650, 0, 3, 0),
(8, '100.43.85.1', 'Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)', 1413551588, 1420815622, 0, 22, 0),
(9, '66.249.69.38', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 1413606897, 1415792328, 0, 6, 0),
(10, '89.140.87.147', 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.101 Safari/537.36', 1413657733, 1413666193, 0, 1, 0),
(11, '2.138.108.91', 'Mozilla/5.0 (Linux; U; Android 2.3.6; es-es; GT-S5570I Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1', 1413718242, 1413718283, 0, 1, 0),
(12, '2.138.97.246', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36', 1413901750, 1414099096, 0, 157, 0),
(14, '2.138.70.23', 'Mozilla/5.0 (Linux; U; Android 2.3.6; es-es; GT-S5570I Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1', 1414129451, 1417716285, 0, 559, 0),
(15, '188.78.24.106', 'Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.12', 1414168861, 1414169779, 0, 1801, 0),
(16, '91.207.7.254', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.102 Safari/537.36', 1415203325, 1415203327, 0, 6, 0),
(17, '188.165.15.192', 'Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)', 1415616666, 1415621272, 0, 2, 0),
(18, '188.165.15.211', 'Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)', 1415626137, 1415626137, 0, 1, 0),
(19, '188.165.15.22', 'Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)', 1415632444, 1415632444, 0, 1, 0),
(20, '188.165.15.176', 'Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)', 1415730844, 1415730844, 0, 1, 0),
(21, '188.165.15.27', 'Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)', 1415814472, 1415814472, 0, 1, 0),
(22, '66.249.75.86', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 1417299093, 1421495928, 0, 16, 0),
(23, '66.249.75.54', 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 1417379004, 1421538338, 0, 9, 0),
(24, '107.182.120.206', 'Mozilla/5.0 (Windows NT 6.1; rv:17.0) Gecko/20100101 Firefox/17.0', 1417888702, 1417888705, 0, 2, 0),
(25, '66.249.75.70', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 1417934390, 1421501608, 0, 12, 0),
(26, '188.165.15.196', 'Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)', 1417947733, 1419397969, 0, 2, 0),
(27, '188.165.15.152', 'Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)', 1417950263, 1417950263, 0, 1, 0),
(28, '188.165.15.117', 'Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)', 1418007243, 1418007243, 0, 1, 0),
(29, '204.44.83.235', 'Mozilla/5.0 (Windows NT 6.1; rv:5.0) Gecko/20100101 Firefox/5.02', 1418183197, 1418183198, 0, 2, 0),
(30, '192.171.233.212', 'Mozilla/5.0 (Windows NT 6.1; rv:5.0) Gecko/20100101 Firefox/5.02', 1418271819, 1418271819, 0, 2, 0),
(31, '188.165.15.230', 'Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)', 1418300334, 1418300334, 0, 1, 0),
(32, '85.89.188.247', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31', 1418353269, 1418353269, 0, 2, 0),
(33, '23.94.79.30', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36', 1418519000, 1418519002, 0, 2, 0),
(34, '81.144.138.34', 'Wotbox/2.01 (+http://www.wotbox.com/bot/)', 1418586600, 1418586632, 0, 6, 0),
(35, '2.138.65.119', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1418840566, 1421532619, 0, 1136, 0),
(36, '217.127.156.218', 'Mozilla/5.0 (X11; Linux i686; rv:12.0) Gecko/20100101 Firefox/12.0', 1418902684, 1418902684, 0, 1, 0),
(37, '66.249.65.183', 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 1418957809, 1418957809, 0, 1, 0),
(38, '37.15.118.87', 'Mozilla/5.0 (X11; U; Unix; en-US) AppleWebKit/537.15 (KHTML, like Gecko) Chrome/24.0.1295.0 Safari/537.15 Surf/0.6', 1418996427, 1419096173, 0, 4, 0),
(39, '46.249.70.103', 'Mozilla/5.0 (X11; Linux x86_64; rv:34.0) Gecko/20100101 Firefox/34.0 Iceweasel/34.0', 1419000303, 1419108043, 0, 52, 0),
(40, '84.122.189.65', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:36.0) Gecko/20100101 Firefox/36.0', 1419082058, 1419082058, 0, 1, 0),
(41, '71.51.110.66', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.101 Safari/537.36', 1419082300, 1421532533, 0, 2, 0),
(42, '188.78.22.63', 'Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.12', 1419089252, 1419102905, 0, 14, 0),
(43, '181.114.124.172', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419098035, 1419098045, 0, 2, 0),
(44, '90.163.187.193', 'Mozilla/5.0 (Windows NT 6.1; rv:34.0) Gecko/20100101 Firefox/34.0', 1419098037, 1419098037, 0, 1, 0),
(45, '186.80.234.245', 'Mozilla/5.0 (X11; Linux i686; rv:7.0.1) Gecko/20100101 Arch Linux Firefox/34.0.5', 1419098047, 1419098047, 0, 1, 0),
(46, '83.44.140.242', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 1419098486, 1419098486, 0, 2, 0),
(47, '62.99.26.121', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 OPR/26.0.1656.60', 1419098690, 1419098690, 0, 1, 0),
(48, '85.54.213.57', 'Mozilla/5.0 (iPad; CPU OS 8_1_2 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) CriOS/39.0.2171.50 Mobile/12B440 Safari/600.1.4', 1419098842, 1419098844, 0, 2, 0),
(49, '186.118.50.107', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.104 Safari/537.36', 1419099105, 1419099105, 0, 1, 0),
(50, '187.210.247.17', 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_1_2 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12B440 Safari/600.1.4', 1419099111, 1419099118, 0, 2, 0),
(51, '66.102.6.161', 'Mozilla/5.0 (en-us) AppleWebKit/534.14 (KHTML, like Gecko; Google Wireless Transcoder) Chrome/9.0.597 Safari/534.14', 1419099127, 1419099127, 0, 1, 0),
(52, '190.205.105.79', 'Mozilla/5.0 (Linux; Android 4.1.1; HTC One S Build/JRO03C) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.93 Mobile Safari/537.36', 1419099279, 1419130275, 0, 7, 0),
(53, '181.208.175.143', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419099365, 1419099365, 0, 1, 0),
(54, '185.28.168.194', 'Mozilla/5.0 (Linux; U; Android 4.2.2; es-es; InFocus M310 Build/JDQ39) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30', 1419099411, 1419099411, 0, 1, 0),
(55, '88.0.9.129', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.103 Safari/537.36', 1419099421, 1419100148, 0, 3, 0),
(56, '66.102.6.179', 'Mozilla/5.0 (en-us) AppleWebKit/534.14 (KHTML, like Gecko; Google Wireless Transcoder) Chrome/9.0.597 Safari/534.14', 1419099443, 1419099443, 0, 1, 0),
(57, '107.167.107.97', 'Opera/9.80 (Android; Opera Mini/7.5.32195/35.6368; U; es) Presto/2.8.119 Version/11.10', 1419099562, 1419099562, 0, 1, 0),
(58, '189.236.116.164', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419099780, 1419099780, 0, 1, 0),
(59, '85.52.133.203', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 1419099970, 1419099973, 0, 2, 0),
(60, '79.108.57.160', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:34.0) Gecko/20100101 Firefox/34.0', 1419100078, 1419100078, 0, 1, 0),
(61, '95.39.243.200', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419100207, 1419100208, 0, 2, 0),
(62, '88.8.94.149', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419100383, 1419100383, 0, 1, 0),
(63, '186.83.41.231', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419100527, 1419100527, 0, 1, 0),
(64, '217.216.87.226', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0; NP06)', 1419100667, 1419101022, 0, 2, 0),
(65, '187.193.154.86', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419100867, 1419100867, 0, 1, 0),
(66, '201.190.18.168', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419100993, 1419100993, 0, 1, 0),
(67, '81.202.240.20', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0', 1419101019, 1419101145, 0, 3, 0),
(68, '190.124.163.3', 'Mozilla/5.0 (Android; Mobile; rv:18.0) Gecko/18.0 Firefox/18.0', 1419101120, 1419102966, 0, 3, 0),
(69, '181.166.170.128', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419101153, 1419101153, 0, 1, 0),
(70, '83.57.208.83', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:34.0) Gecko/20100101 Firefox/34.0', 1419101421, 1419101421, 0, 1, 0),
(71, '77.12.83.40', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419101436, 1419101436, 0, 1, 0),
(72, '87.222.168.235', 'Mozilla/5.0 (Linux; Android 5.0; Nexus 7 Build/LRX21P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.93 Safari/537.36', 1419101698, 1419104321, 0, 3, 0),
(73, '200.86.50.22', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 OPR/26.0.1656.60', 1419101725, 1419101725, 0, 1, 0),
(74, '201.235.33.149', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419102074, 1419102074, 0, 1, 0),
(75, '188.84.180.125', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419102288, 1419102289, 0, 2, 0),
(76, '179.7.85.134', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419102393, 1419102478, 0, 2, 0),
(77, '186.94.95.168', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419102568, 1419102571, 0, 2, 0),
(78, '189.214.125.188', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419102632, 1419102632, 0, 1, 0),
(79, '201.193.11.138', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36', 1419102726, 1419102726, 0, 1, 0),
(80, '94.228.11.163', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.1.25 (KHTML, like Gecko) Version/8.0 Safari/600.1.25', 1419102754, 1419102754, 0, 1, 0),
(81, '189.228.219.136', 'Mozilla/5.0 (Windows NT 6.0; rv:34.0) Gecko/20100101 Firefox/34.0', 1419102857, 1419104352, 0, 4, 0),
(82, '190.240.71.199', 'Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419103084, 1419103084, 0, 1, 0),
(83, '70.45.54.96', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:34.0) Gecko/20100101 Firefox/34.0', 1419103340, 1419103345, 0, 3, 0),
(84, '207.46.13.94', 'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)', 1419103499, 1419103499, 0, 1, 0),
(85, '190.72.53.129', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419103575, 1419103575, 0, 1, 0),
(86, '190.160.224.214', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 1419103639, 1419103641, 0, 2, 0),
(87, '77.27.174.118', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419103766, 1419103766, 0, 1, 0),
(88, '186.122.47.166', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36', 1419103819, 1419103883, 0, 2, 0),
(89, '181.143.2.162', 'Mozilla/5.0 (Linux; Android 4.2.2; ALCATEL ONE TOUCH 7040A Build/JDQ39) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.93 Mobile Safari/537.36', 1419104080, 1419104080, 0, 1, 0),
(90, '185.13.106.89', 'Mozilla/5.0 (Linux; Android 4.4.2; LG-D682 Build/KOT49I.D68220b) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.59 Mobile Safari/537.36', 1419104091, 1419104091, 0, 1, 0),
(91, '95.121.85.184', 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko', 1419104099, 1419104099, 0, 1, 0),
(92, '85.50.5.141', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 Safari/537.36', 1419104371, 1419104371, 0, 1, 0),
(93, '83.55.55.169', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36', 1419104374, 1419104374, 0, 1, 0),
(94, '200.83.175.26', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 1419104460, 1419104460, 0, 2, 0),
(95, '83.52.18.157', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419104557, 1419104557, 0, 1, 0),
(96, '190.233.170.91', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419104902, 1419104902, 0, 1, 0),
(97, '79.108.10.116', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419105015, 1419105016, 0, 2, 0),
(98, '62.81.177.250', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419105066, 1419105066, 0, 1, 0),
(99, '87.217.160.163', 'Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.17', 1419105076, 1419105175, 0, 3, 0),
(100, '90.175.164.215', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36', 1419105162, 1419105162, 0, 1, 0),
(101, '190.238.209.13', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419105185, 1419105288, 0, 4, 0),
(102, '79.156.224.28', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419105194, 1419105194, 0, 1, 0),
(103, '190.117.236.144', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419105418, 1419105418, 0, 1, 0),
(104, '66.102.6.170', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko; Google Web Preview) Chrome/27.0.1453 Safari/537.36', 1419105677, 1419105677, 0, 1, 0),
(105, '190.238.69.156', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419106036, 1419106036, 0, 1, 0),
(106, '90.166.223.30', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 1419106046, 1419106046, 0, 1, 0),
(107, '201.153.189.242', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:34.0) Gecko/20100101 Firefox/34.0', 1419106073, 1419106073, 0, 1, 0),
(108, '85.136.107.42', 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko', 1419106145, 1419106145, 0, 1, 0),
(109, '190.63.130.242', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 1419106209, 1419106211, 0, 2, 0),
(110, '137.135.176.175', 'Mozilla/5.0 (Windows Phone 8.1; ARM; Trident/7.0; Touch; rv:11.0; IEMobile/11.0; NOKIA; Lumia 520; Vodafone ES) like Gecko', 1419106791, 1419106791, 0, 1, 0),
(111, '81.202.129.181', 'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419106799, 1419106799, 0, 1, 0),
(112, '189.202.216.137', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 1419106820, 1419106823, 0, 2, 0),
(113, '2.136.246.197', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 1419106873, 1419106873, 0, 1, 0),
(114, '181.132.221.75', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 1419106980, 1419106980, 0, 1, 0),
(115, '84.123.94.153', 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko', 1419107236, 1419107236, 0, 1, 0),
(116, '181.136.142.58', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36', 1419107255, 1419107304, 0, 3, 0),
(117, '190.108.194.197', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36 PSafe Internet', 1419107327, 1419107327, 0, 1, 0),
(118, '189.217.69.13', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419107496, 1419107496, 0, 1, 0),
(119, '186.7.104.225', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419107524, 1419107526, 0, 2, 0),
(120, '190.106.222.68', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419107670, 1419107670, 0, 1, 0),
(121, '62.57.251.164', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419107722, 1419107722, 0, 1, 0),
(122, '179.7.120.63', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419108051, 1419108052, 0, 2, 0),
(123, '80.31.120.120', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 1419228052, 1419228052, 0, 1, 0),
(124, '79.151.117.187', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 1419406301, 1419537555, 0, 12, 0),
(125, '79.146.111.178', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 1420015310, 1420015310, 0, 1, 0),
(126, '195.154.187.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.110 Safari/537.36', 1420074647, 1420459356, 0, 12, 0),
(127, '83.33.91.110', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 1420241150, 1420241621, 0, 7, 0),
(128, '188.165.15.132', 'Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)', 1420340168, 1420340168, 0, 1, 0),
(129, '188.165.15.130', 'Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)', 1420982684, 1420982684, 0, 1, 0),
(130, '188.165.15.235', 'Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)', 1421007978, 1421007978, 0, 1, 0),
(131, '188.165.15.160', 'Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)', 1421331807, 1421331807, 0, 1, 0),
(132, '109.169.55.112', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36', 1421526264, 1421530467, 0, 2, 0),
(133, '109.169.76.102', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36', 1421528140, 1421528140, 0, 1, 0),
(134, '95.154.194.110', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36', 1421528460, 1421528460, 0, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vote`
--

CREATE TABLE IF NOT EXISTS `vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `linked_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_time` int(11) NOT NULL,
  `vote` tinyint(1) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `vote`
--

INSERT INTO `vote` (`id`, `type`, `linked_id`, `user_id`, `action_time`, `vote`) VALUES
(4, 4, 20, 1, 1419793549, 1),
(5, 4, 19, 1, 1419793562, 1),
(6, 4, 18, 1, 1419793600, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
