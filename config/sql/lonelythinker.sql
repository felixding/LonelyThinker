-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 17, 2009 at 07:12 上午
-- Server version: 5.1.37
-- PHP Version: 5.3.0

--
-- Database: `lonelythinker_0414`
--

-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_bayesian_wordlists`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_bayesian_wordlists` (
  `token` varchar(255) NOT NULL,
  `count` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`token`)
);

-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_blacklists`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_blacklists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('on','off') NOT NULL DEFAULT 'on',
  `field` enum('title','body','email','website','ip','antispam') NOT NULL,
  `pattern` text NOT NULL,
  `logic` enum('allow','deny') NOT NULL DEFAULT 'deny',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
);


-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_comments`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_comments` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `post_id` int(5) NOT NULL DEFAULT '0',
  `status` enum('published','trash','spam') NOT NULL DEFAULT 'published',
  `name` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `website` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `agent` varchar(255) NOT NULL DEFAULT '',
  `subscription` varchar(32) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_events`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `priority` int(1) NOT NULL DEFAULT '9',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
);


-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_groups`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `level` int(11) NOT NULL DEFAULT '0',
  `redirect` varchar(50) NOT NULL DEFAULT '',
  `perm_type` enum('allow','deny') NOT NULL DEFAULT 'allow',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `lonelythinker_groups`
--

INSERT INTO `lonelythinker_groups` (`id`, `name`, `level`, `redirect`, `perm_type`, `created`, `modified`) VALUES
(1, 'administrators', 100, '', 'allow', '2007-08-06 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_groups_permissions`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_groups_permissions` (
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `permission_id` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `group_id` (`group_id`,`permission_id`)
);

--
-- Dumping data for table `lonelythinker_groups_permissions`
--

INSERT INTO `lonelythinker_groups_permissions` (`group_id`, `permission_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_links`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link_category_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_link_categories`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_link_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_permissions`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

--
-- Dumping data for table `lonelythinker_permissions`
--

INSERT INTO `lonelythinker_permissions` (`id`, `name`, `created`, `modified`) VALUES
(1, '*', '2007-08-06 15:41:31', '2007-08-06 15:41:31');

-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_posts`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_posts` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `title` varchar(100) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  `body` longtext NOT NULL,
  `comment` enum('on','off') NOT NULL DEFAULT 'on',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 ;

--
-- Dumping data for table `lonelythinker_posts`
--

INSERT INTO `lonelythinker_posts` (`id`, `status`, `title`, `slug`, `body`, `comment`, `created`, `modified`) VALUES
(1, 'published', 'Hello World!', 'hello-world', '<p>This is a test post for demostration, edit it at will.</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 'on', '2009-04-14 17:04:00', '2009-04-14 17:04:00');

-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_posts_tags`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_posts_tags` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `post_id` int(5) NOT NULL DEFAULT '0',
  `tag_id` int(5) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 ;

--
-- Dumping data for table `lonelythinker_posts_tags`
--

INSERT INTO `lonelythinker_posts_tags` (`id`, `post_id`, `tag_id`, `created`, `modified`) VALUES
(1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_related_posts`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_related_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `related_post_id` int(11) NOT NULL,
  `similarity` float NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_sensors`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_sensors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('on','off') NOT NULL DEFAULT 'on',
  `name` varchar(255) NOT NULL,
  `trigger` varchar(255) NOT NULL,
  `trigger_option` text NOT NULL,
  `action` varchar(255) NOT NULL,
  `action_option` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
);


-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_settings`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('system','user') NOT NULL DEFAULT 'user',
  `key` varchar(48) NOT NULL,
  `value` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=11 ;

--
-- Dumping data for table `lonelythinker_settings`
--

INSERT INTO `lonelythinker_settings` (`id`, `type`, `key`, `value`, `created`, `modified`) VALUES
(1, 'user', 'siteUrl', 'http://lonelythinker.org', '2008-12-18 11:05:02', '2008-12-18 11:05:02'),
(2, 'user', 'siteName', 'LonelyThinker', '2009-01-03 14:48:55', '2009-11-15 21:26:36'),
(3, 'user', 'siteSlogan', 'The missing blog platform for geeks', '2009-01-03 14:49:10', '2009-11-15 21:26:36'),
(5, 'user', 'mailer', 'mailer@domain', '2009-03-06 15:31:38', '2009-11-15 21:26:36'),
(6, 'user', 'bulletin', 'off', '2009-03-06 15:31:38', '2009-05-08 22:52:03'),
(4, 'system', 'bayesRating', '0.7', '2009-01-19 21:03:31', '2009-01-19 21:03:31'),
(7, 'system', 'version', '0.5', '2009-03-06 15:31:38', '2009-03-06 15:31:38'),
(8, 'user', 'twitterUsername', '', '2009-05-08 21:55:23', '2009-11-15 21:49:48'),
(9, 'user', 'lastfmUsername', '', '2009-05-08 22:57:31', '2009-11-15 21:49:54'),
(10, 'user', 'lastfmAPIKey', '', '2009-05-08 22:48:08', '2009-11-15 21:50:22');

-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_tags`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_tags` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 ;

--
-- Dumping data for table `lonelythinker_tags`
--

INSERT INTO `lonelythinker_tags` (`id`, `title`, `slug`, `description`, `created`, `modified`) VALUES
(1, 'By talk', 'by-talk', 'This is a test tag, edit it at will.', '2009-04-14 17:02:50', '2009-04-14 17:02:50');

-- --------------------------------------------------------

--
-- Table structure for table `lonelythinker_users`
--

CREATE TABLE IF NOT EXISTS `lonelythinker_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `passwd` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `last_visit` datetime NOT NULL,
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`,`username`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 ;

--
-- Dumping data for table `lonelythinker_users`
--

INSERT INTO `lonelythinker_users` (`id`, `username`, `passwd`, `name`, `email`, `last_visit`, `group_id`, `active`, `created`, `modified`) VALUES
(1, 'root', 'a4a3ab7de14efad76303e413b1ea6d88', 'The ultimate root12', 'adminitrator@domain.com', '2009-11-16 10:37:32', 1, 1, '2007-08-06 15:38:59', '2007-08-06 15:38:59');
