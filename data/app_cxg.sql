-- phpMyAdmin SQL Dump
-- version 3.4.3.2
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2013 年 12 月 16 日 06:25
-- 服务器版本: 5.5.15
-- PHP 版本: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `app_cxg`
--

-- --------------------------------------------------------

--
-- 表的结构 `cxg_votelog`
--

CREATE TABLE IF NOT EXISTS `cxg_votelog` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `openId` varchar(40) NOT NULL,
  `workId` varchar(10) NOT NULL,
  `weixinName` text NOT NULL,
  `isLucky` int(2) NOT NULL DEFAULT '0',
  `luckyTime` datetime NOT NULL,
  `creatTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `cxg_works`
--

CREATE TABLE IF NOT EXISTS `cxg_works` (
  `id` int(3) DEFAULT NULL,
  `company` varchar(71) DEFAULT NULL,
  `workId` varchar(4) DEFAULT NULL,
  `voteCount` int(1) DEFAULT NULL,
  `isShow` int(2) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cxg_works`
--

INSERT INTO `cxg_works` (`id`, `company`, `workId`, `voteCount`, `isShow`) VALUES
(1, '团队1', 'E1', 262, 1),
(2, '团队2', 'D7', 233, 1),
(3, '团队3', 'E56', 236, 1),
(4, '团队4', 'E64', 225, 1),
(5, '团队5', 'E61', 339, 1),
(6, '团队6 ', 'E62', 224, 1),
(7, '团队7', 'E63', 212, 1),
(8, '团队8', 'E16', 388, 1),
(9, '团队9', 'D1', 243, 1),
(10, '团队10', 'D2', 227, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
