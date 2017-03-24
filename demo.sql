-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2017 年 03 月 24 日 05:54
-- 服务器版本: 5.5.53
-- PHP 版本: 5.4.45

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `demo`
--

-- --------------------------------------------------------

--
-- 表的结构 `think_access`
--

CREATE TABLE IF NOT EXISTS `think_access` (
  `ac_id` int(100) NOT NULL AUTO_INCREMENT COMMENT '权限id',
  `ac_name` varchar(50) NOT NULL DEFAULT '' COMMENT '权限标题',
  `ac_url` varchar(1000) NOT NULL DEFAULT '' COMMENT '权限urls',
  `ac_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `ac_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '是否有效',
  PRIMARY KEY (`ac_id`),
  UNIQUE KEY `access_id` (`ac_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='权限表' AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `think_access`
--

INSERT INTO `think_access` (`ac_id`, `ac_name`, `ac_url`, `ac_time`, `ac_update`, `status`) VALUES
(5, '权限管理', '["home\\/action\\/doact","home\\/action\\/showact","home\\/action\\/delact"]', '2017-03-22 19:13:13', '2017-03-24 03:53:14', 1),
(6, '默认首页', '["home\\/","home\\/admin\\/","home\\/admin\\/index"]', '2017-03-22 19:32:36', '0000-00-00 00:00:00', 1),
(8, '角色管理', '["home\\/rolers\\/showrole","home\\/rolers\\/dorole","home\\/rolers\\/bindact","home\\/rolers\\/delrole",""]', '2017-03-24 03:54:51', '0000-00-00 00:00:00', 1),
(7, '用户管理', '["home\\/users\\/showuser","home\\/users\\/upuser","home\\/users\\/userinfo","home\\/users\\/deluser"]', '2017-03-22 21:39:30', '2017-03-24 04:50:46', 1),
(9, '个人管理', '["home\\/admin\\/upuser"]', '2017-03-24 04:43:42', '0000-00-00 00:00:00', 1),
(10, '测试页面1', '["home\\/test\\/test1"]', '2017-03-23 17:19:50', '0000-00-00 00:00:00', 1),
(11, '测试页面2', '["home\\/test\\/test2"]', '2017-03-23 17:20:24', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- 表的结构 `think_roleacc`
--

CREATE TABLE IF NOT EXISTS `think_roleacc` (
  `roleac_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `roleac_roleid` int(100) NOT NULL COMMENT '角色id',
  `roleac_ac_id` int(100) NOT NULL COMMENT '权限ID',
  `update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`roleac_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='角色权限对应关系表' AUTO_INCREMENT=25 ;

--
-- 转存表中的数据 `think_roleacc`
--

INSERT INTO `think_roleacc` (`roleac_id`, `roleac_roleid`, `roleac_ac_id`, `update`) VALUES
(8, 1, 7, '2017-03-22 21:42:03'),
(12, 2, 6, '2017-03-24 04:44:38'),
(11, 1, 9, '2017-03-24 04:44:19'),
(7, 1, 6, '2017-03-22 21:41:56'),
(10, 4, 6, '2017-03-22 21:42:23'),
(15, 4, 8, '2017-03-24 04:44:55'),
(13, 2, 7, '2017-03-24 04:44:38'),
(14, 2, 9, '2017-03-24 04:44:38'),
(16, 4, 7, '2017-03-24 04:44:55'),
(17, 4, 9, '2017-03-24 04:44:55'),
(18, 6, 5, '2017-03-24 04:45:06'),
(19, 6, 6, '2017-03-24 04:45:06'),
(20, 6, 8, '2017-03-24 04:45:06'),
(21, 6, 7, '2017-03-24 04:45:06'),
(22, 6, 9, '2017-03-24 04:45:06'),
(23, 6, 10, '2017-03-23 17:24:51'),
(24, 6, 11, '2017-03-23 17:24:51');

-- --------------------------------------------------------

--
-- 表的结构 `think_roles`
--

CREATE TABLE IF NOT EXISTS `think_roles` (
  `roles_id` int(20) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `roles_name` varchar(20) NOT NULL COMMENT '角色名',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '角色权限表',
  `roles_creatime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `roles_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '角色更新时间',
  PRIMARY KEY (`roles_id`),
  UNIQUE KEY `roles_id` (`roles_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `think_roles`
--

INSERT INTO `think_roles` (`roles_id`, `roles_name`, `status`, `roles_creatime`, `roles_update`) VALUES
(1, '销售主管', 1, '0000-00-00 00:00:00', '2017-03-23 04:38:46'),
(2, '销售经理', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, '财务', 1, '2017-03-21 21:25:53', '2017-03-21 21:45:36'),
(5, '经理', 0, '2017-03-21 21:46:08', '2017-03-21 22:01:01'),
(6, '经理', 1, '2017-03-23 03:10:01', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `think_user`
--

CREATE TABLE IF NOT EXISTS `think_user` (
  `user_id` int(20) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `user_name` varchar(30) NOT NULL COMMENT '用户名',
  `user_password` varchar(50) NOT NULL COMMENT '用户密码',
  `user_email` varchar(50) NOT NULL COMMENT '用户邮箱',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '此条数据是否有效',
  `user_creatdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `user_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `user_type` int(5) NOT NULL DEFAULT '1' COMMENT '用户类型0为',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `think_user`
--

INSERT INTO `think_user` (`user_id`, `user_name`, `user_password`, `user_email`, `status`, `user_creatdate`, `user_update`, `user_type`) VALUES
(1, 'yaorong', 'e10adc3949ba59abbe56e057f20f883e', '123456@qq.com', 1, '2017-03-20 16:00:00', '2017-03-23 04:29:10', 0),
(2, 'mushangcao', 'e10adc3949ba59abbe56e057f20f883e', '123456@qq.com', 1, '0000-00-00 00:00:00', '2017-03-24 04:50:07', 1),
(4, 'mushangcao1', 'e10adc3949ba59abbe56e057f20f883e', '123456@qq.com', 1, '0000-00-00 00:00:00', '2017-03-23 04:29:20', 1);

-- --------------------------------------------------------

--
-- 表的结构 `think_userole`
--

CREATE TABLE IF NOT EXISTS `think_userole` (
  `userid` int(20) NOT NULL COMMENT '用户ID字段',
  `id` int(100) NOT NULL AUTO_INCREMENT COMMENT '用户角色表ID',
  `rolesid` int(20) NOT NULL COMMENT '角色id字段',
  `updatetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户角色关系表' AUTO_INCREMENT=48 ;

--
-- 转存表中的数据 `think_userole`
--

INSERT INTO `think_userole` (`userid`, `id`, `rolesid`, `updatetime`) VALUES
(1, 43, 6, '2017-03-23 03:26:16'),
(4, 45, 2, '2017-03-23 04:29:20'),
(1, 44, 2, '2017-03-23 04:29:10'),
(1, 40, 1, '2017-03-23 03:26:16'),
(2, 47, 6, '2017-03-24 04:45:22'),
(4, 46, 4, '2017-03-23 04:29:20');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
