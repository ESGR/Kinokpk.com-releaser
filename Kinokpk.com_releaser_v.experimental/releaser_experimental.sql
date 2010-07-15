/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
-- phpMyAdmin SQL Dump
-- version 2.6.1
-- http://www.phpmyadmin.net
-- 
-- ����: localhost
-- ����� ��������: ��� 14 2010 �., 12:52
-- ������ �������: 5.0.45
-- ������ PHP: 5.2.4
-- 
-- ��: `releaser320`
-- 

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `presents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `presenter` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(100) DEFAULT NULL,
  `msg` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;
-- 
-- ��������� ������� `addedrequests`
-- 

CREATE TABLE `addedrequests` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `requestid` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pollid` (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `addedrequests`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `bannedemails`
-- 

CREATE TABLE `bannedemails` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` int(10) NOT NULL,
  `addedby` int(10) unsigned NOT NULL default '0',
  `comment` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `bannedemails`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `bans`
-- 

CREATE TABLE `bans` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `mask` varchar(60) NOT NULL,
  `desc` varchar(255) default NULL,
  `user` int(10) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `bans`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `bookmarks`
-- 

CREATE TABLE `bookmarks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `torrentid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `bookmarks`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `cache_stats`
-- 

CREATE TABLE `cache_stats` (
  `cache_name` varchar(255) NOT NULL,
  `cache_value` text,
  PRIMARY KEY  (`cache_name`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- 
-- ���� ������ ������� `cache_stats`
-- 

INSERT INTO `cache_stats` VALUES ('adminemail', 'admin@localhost');
INSERT INTO `cache_stats` VALUES ('allow_invite_signup', '1');
INSERT INTO `cache_stats` VALUES ('announce_interval', '30');
INSERT INTO `cache_stats` VALUES ('announce_packed', '1');
INSERT INTO `cache_stats` VALUES ('as_check_messages', '1');
INSERT INTO `cache_stats` VALUES ('as_timeout', '15');
INSERT INTO `cache_stats` VALUES ('autoclean_interval', '900');
INSERT INTO `cache_stats` VALUES ('avatar_max_height', '100');
INSERT INTO `cache_stats` VALUES ('avatar_max_width', '100');
INSERT INTO `cache_stats` VALUES ('debug_mode', '1');
INSERT INTO `cache_stats` VALUES ('defaultbaseurl', 'http://releaser320.com');
INSERT INTO `cache_stats` VALUES ('default_emailnotifs', 'unread,torrents,friends');
INSERT INTO `cache_stats` VALUES ('default_language', 'ru');
INSERT INTO `cache_stats` VALUES ('default_notifs', 'unread,torrents,comments,pollcomments,newscomments,usercomments,reqcomments,rgcomments,pages,pagecomments,friends');
INSERT INTO `cache_stats` VALUES ('default_theme', 'kinokpk');
INSERT INTO `cache_stats` VALUES ('defuserclass', '3');
INSERT INTO `cache_stats` VALUES ('deny_signup', '0');
INSERT INTO `cache_stats` VALUES ('description', 'Kinokpk.com releaser new installation');
INSERT INTO `cache_stats` VALUES ('emo_dir', 'default');
INSERT INTO `cache_stats` VALUES ('exporttype', 'wiki');
INSERT INTO `cache_stats` VALUES ('forumname', 'Integrated forum');
INSERT INTO `cache_stats` VALUES ('forumurl', 'http://forum.pdaprime.ru');
INSERT INTO `cache_stats` VALUES ('forum_bin_id', '20');
INSERT INTO `cache_stats` VALUES ('ipb_cookie_prefix', '');
INSERT INTO `cache_stats` VALUES ('ipb_password_priority', '0');
INSERT INTO `cache_stats` VALUES ('keywords', 'kinokpk, kinokpk.com, releaser, 3.20, ZonD80');
INSERT INTO `cache_stats` VALUES ('maxusers', '0');
INSERT INTO `cache_stats` VALUES ('max_dead_torrent_time', '744');
INSERT INTO `cache_stats` VALUES ('max_images', '4');
INSERT INTO `cache_stats` VALUES ('max_torrent_size', '1000000');
INSERT INTO `cache_stats` VALUES ('not_found_export_id', '66');
INSERT INTO `cache_stats` VALUES ('pm_max', '100');
INSERT INTO `cache_stats` VALUES ('pron_cats', '0');
INSERT INTO `cache_stats` VALUES ('register_timezone', '3');
INSERT INTO `cache_stats` VALUES ('re_privatekey', 'none');
INSERT INTO `cache_stats` VALUES ('re_publickey', 'none');
INSERT INTO `cache_stats` VALUES ('signup_timeout', '3');
INSERT INTO `cache_stats` VALUES ('siteemail', 'webmaster@localhost');
INSERT INTO `cache_stats` VALUES ('sitename', 'Kinokpk.com releaser new installation');
INSERT INTO `cache_stats` VALUES ('siteonline', 'a:4:{s:5:"onoff";i:1;s:6:"reason";s:4:"test";s:5:"class";i:6;s:10:"class_name";s:21:"������ ��� ����������";}');
INSERT INTO `cache_stats` VALUES ('smtptype', 'advanced');
INSERT INTO `cache_stats` VALUES ('torrentsperpage', '25');
INSERT INTO `cache_stats` VALUES ('ttl_days', '28');
INSERT INTO `cache_stats` VALUES ('use_blocks', '1');
INSERT INTO `cache_stats` VALUES ('use_captcha', '1');
INSERT INTO `cache_stats` VALUES ('use_dc', '1');
INSERT INTO `cache_stats` VALUES ('use_email_act', '0');
INSERT INTO `cache_stats` VALUES ('use_gzip', '0');
INSERT INTO `cache_stats` VALUES ('use_integration', '0');
INSERT INTO `cache_stats` VALUES ('use_ipbans', '1');
INSERT INTO `cache_stats` VALUES ('use_kinopoisk_trailers', '1');
INSERT INTO `cache_stats` VALUES ('use_lang', '1');
INSERT INTO `cache_stats` VALUES ('use_sessions', '1');
INSERT INTO `cache_stats` VALUES ('use_ttl', '0');
INSERT INTO `cache_stats` VALUES ('use_wait', '0');
INSERT INTO `cache_stats` VALUES ('yourcopy', '� {datenow} Your Copyright');

-- --------------------------------------------------------

-- 
-- ��������� ������� `categories`
-- 

CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sort` int(10) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `seo_name` varchar(80) default NULL,
  `image` varchar(255) NOT NULL,
  `parent_id` int(10) NOT NULL default '0',
  `forum_id` smallint(5) NOT NULL default '0',
  `disable_export` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `categories`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `censoredtorrents`
-- 

CREATE TABLE `censoredtorrents` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `censoredtorrents`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `comments`
-- 

CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `torrent` int(10) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(10) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  `post_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `torrent` (`torrent`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `comments`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `countries`
-- 

CREATE TABLE `countries` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  `flagpic` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=106 ;

-- 
-- ���� ������ ������� `countries`
-- 

INSERT INTO `countries` VALUES (1, '������', 'sweden.gif');
INSERT INTO `countries` VALUES (2, '���', 'usa.gif');
INSERT INTO `countries` VALUES (3, '������', 'russia.gif');
INSERT INTO `countries` VALUES (4, '���������', 'finland.gif');
INSERT INTO `countries` VALUES (5, '������', 'canada.gif');
INSERT INTO `countries` VALUES (6, '�������', 'france.gif');
INSERT INTO `countries` VALUES (7, '��������', 'germany.gif');
INSERT INTO `countries` VALUES (8, '�����', 'china.gif');
INSERT INTO `countries` VALUES (9, '������', 'italy.gif');
INSERT INTO `countries` VALUES (10, 'Denmark', 'denmark.gif');
INSERT INTO `countries` VALUES (11, '��������', 'norway.gif');
INSERT INTO `countries` VALUES (12, '������', 'uk.gif');
INSERT INTO `countries` VALUES (13, '��������', 'ireland.gif');
INSERT INTO `countries` VALUES (14, '������', 'poland.gif');
INSERT INTO `countries` VALUES (15, '����������', 'netherlands.gif');
INSERT INTO `countries` VALUES (16, '�������', 'belgium.gif');
INSERT INTO `countries` VALUES (17, '������', 'japan.gif');
INSERT INTO `countries` VALUES (18, '��������', 'brazil.gif');
INSERT INTO `countries` VALUES (19, '���������', 'argentina.gif');
INSERT INTO `countries` VALUES (20, '���������', 'australia.gif');
INSERT INTO `countries` VALUES (21, '����� ��������', 'newzealand.gif');
INSERT INTO `countries` VALUES (22, '�������', 'spain.gif');
INSERT INTO `countries` VALUES (23, '����������', 'portugal.gif');
INSERT INTO `countries` VALUES (24, '�������', 'mexico.gif');
INSERT INTO `countries` VALUES (25, '��������', 'singapore.gif');
INSERT INTO `countries` VALUES (26, '�����', 'india.gif');
INSERT INTO `countries` VALUES (27, '�������', 'albania.gif');
INSERT INTO `countries` VALUES (28, '����� ������', 'southafrica.gif');
INSERT INTO `countries` VALUES (29, '����� �����', 'southkorea.gif');
INSERT INTO `countries` VALUES (30, '������', 'jamaica.gif');
INSERT INTO `countries` VALUES (31, '����������', 'luxembourg.gif');
INSERT INTO `countries` VALUES (32, '���� ����', 'hongkong.gif');
INSERT INTO `countries` VALUES (33, 'Belize', 'belize.gif');
INSERT INTO `countries` VALUES (34, '�����', 'algeria.gif');
INSERT INTO `countries` VALUES (35, '������', 'angola.gif');
INSERT INTO `countries` VALUES (36, '�������', 'austria.gif');
INSERT INTO `countries` VALUES (37, '���������', 'yugoslavia.gif');
INSERT INTO `countries` VALUES (38, '����� �����', 'westernsamoa.gif');
INSERT INTO `countries` VALUES (39, '��������', 'malaysia.gif');
INSERT INTO `countries` VALUES (40, '������������� ����������', 'dominicanrep.gif');
INSERT INTO `countries` VALUES (41, '������', 'greece.gif');
INSERT INTO `countries` VALUES (42, '���������', 'guatemala.gif');
INSERT INTO `countries` VALUES (43, '�������', 'israel.gif');
INSERT INTO `countries` VALUES (44, '��������', 'pakistan.gif');
INSERT INTO `countries` VALUES (45, '�����', 'czechrep.gif');
INSERT INTO `countries` VALUES (46, '������', 'serbia.gif');
INSERT INTO `countries` VALUES (47, '����������� �������', 'seychelles.gif');
INSERT INTO `countries` VALUES (48, '�������', 'taiwan.gif');
INSERT INTO `countries` VALUES (49, '������ ����', 'puertorico.gif');
INSERT INTO `countries` VALUES (50, '����', 'chile.gif');
INSERT INTO `countries` VALUES (51, '����', 'cuba.gif');
INSERT INTO `countries` VALUES (52, '�����', 'congo.gif');
INSERT INTO `countries` VALUES (53, '����������', 'afghanistan.gif');
INSERT INTO `countries` VALUES (54, '������', 'turkey.gif');
INSERT INTO `countries` VALUES (55, '����������', 'uzbekistan.gif');
INSERT INTO `countries` VALUES (56, '���������', 'switzerland.gif');
INSERT INTO `countries` VALUES (57, '��������', 'kiribati.gif');
INSERT INTO `countries` VALUES (58, '���������', 'philippines.gif');
INSERT INTO `countries` VALUES (59, 'Burkina Faso', 'burkinafaso.gif');
INSERT INTO `countries` VALUES (60, '�������', 'nigeria.gif');
INSERT INTO `countries` VALUES (61, '��������', 'iceland.gif');
INSERT INTO `countries` VALUES (62, '�����', 'nauru.gif');
INSERT INTO `countries` VALUES (63, '��������', 'slovenia.gif');
INSERT INTO `countries` VALUES (64, '������������', 'turkmenistan.gif');
INSERT INTO `countries` VALUES (65, '������', 'bosniaherzegovina.gif');
INSERT INTO `countries` VALUES (66, '������', 'andorra.gif');
INSERT INTO `countries` VALUES (67, '�����', 'lithuania.gif');
INSERT INTO `countries` VALUES (68, '���������', 'macedonia.gif');
INSERT INTO `countries` VALUES (69, '������������� �������', 'nethantilles.gif');
INSERT INTO `countries` VALUES (70, '�������', 'ukraine.gif');
INSERT INTO `countries` VALUES (71, '���������', 'venezuela.gif');
INSERT INTO `countries` VALUES (72, '�������', 'hungary.gif');
INSERT INTO `countries` VALUES (73, '�������', 'romania.gif');
INSERT INTO `countries` VALUES (74, '�������', 'vanuatu.gif');
INSERT INTO `countries` VALUES (75, '�������', 'vietnam.gif');
INSERT INTO `countries` VALUES (76, 'Trinidad & Tobago', 'trinidadandtobago.gif');
INSERT INTO `countries` VALUES (77, '��������', 'honduras.gif');
INSERT INTO `countries` VALUES (78, '���������', 'kyrgyzstan.gif');
INSERT INTO `countries` VALUES (79, '�������', 'ecuador.gif');
INSERT INTO `countries` VALUES (80, '������', 'bahamas.gif');
INSERT INTO `countries` VALUES (81, '����', 'peru.gif');
INSERT INTO `countries` VALUES (82, '��������', 'cambodia.gif');
INSERT INTO `countries` VALUES (83, '��������', 'barbados.gif');
INSERT INTO `countries` VALUES (84, '���������', 'bangladesh.gif');
INSERT INTO `countries` VALUES (85, '����', 'laos.gif');
INSERT INTO `countries` VALUES (86, '�������', 'uruguay.gif');
INSERT INTO `countries` VALUES (87, 'Antigua Barbuda', 'antiguabarbuda.gif');
INSERT INTO `countries` VALUES (88, '��������', 'paraguay.gif');
INSERT INTO `countries` VALUES (89, '�������', 'thailand.gif');
INSERT INTO `countries` VALUES (90, '����', 'ussr.gif');
INSERT INTO `countries` VALUES (91, 'Senegal', 'senegal.gif');
INSERT INTO `countries` VALUES (92, '����', 'togo.gif');
INSERT INTO `countries` VALUES (93, '�������� �����', 'northkorea.gif');
INSERT INTO `countries` VALUES (94, '��������', 'croatia.gif');
INSERT INTO `countries` VALUES (95, '�������', 'estonia.gif');
INSERT INTO `countries` VALUES (96, '��������', 'colombia.gif');
INSERT INTO `countries` VALUES (97, '�������', 'lebanon.gif');
INSERT INTO `countries` VALUES (98, '������', 'latvia.gif');
INSERT INTO `countries` VALUES (99, '����� ����', 'costarica.gif');
INSERT INTO `countries` VALUES (100, '�����', 'egypt.gif');
INSERT INTO `countries` VALUES (101, '��������', 'bulgaria.gif');
INSERT INTO `countries` VALUES (102, '���� �� ������', 'jollyroger.gif');
INSERT INTO `countries` VALUES (103, '���������', 'kazahstan.png');
INSERT INTO `countries` VALUES (104, '�������', 'moldova.gif');
INSERT INTO `countries` VALUES (105, '��������', '');

-- --------------------------------------------------------

-- 
-- ��������� ������� `cron`
-- 

CREATE TABLE `cron` (
  `cron_name` varchar(255) NOT NULL,
  `cron_value` int(10) NOT NULL default '0',
  PRIMARY KEY  (`cron_name`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- 
-- ���� ������ ������� `cron`
-- 

INSERT INTO `cron` VALUES ('announce_interval', 15);
INSERT INTO `cron` VALUES ('autoclean_interval', 900);
INSERT INTO `cron` VALUES ('delete_votes', 1440);
INSERT INTO `cron` VALUES ('in_cleanup', 0);
INSERT INTO `cron` VALUES ('in_remotecheck', 0);
INSERT INTO `cron` VALUES ('last_cleanup', 0);
INSERT INTO `cron` VALUES ('last_remotecheck', 0);
INSERT INTO `cron` VALUES ('max_dead_torrent_time', 744);
INSERT INTO `cron` VALUES ('num_checked', 0);
INSERT INTO `cron` VALUES ('num_cleaned', 0);
INSERT INTO `cron` VALUES ('pm_delete_sys_days', 15);
INSERT INTO `cron` VALUES ('pm_delete_user_days', 30);
INSERT INTO `cron` VALUES ('promote_rating', 50);
INSERT INTO `cron` VALUES ('rating_checktime', 180);
INSERT INTO `cron` VALUES ('rating_discounttorrent', 1);
INSERT INTO `cron` VALUES ('rating_dislimit', -200);
INSERT INTO `cron` VALUES ('rating_downlimit', -10);
INSERT INTO `cron` VALUES ('rating_enabled', 1);
INSERT INTO `cron` VALUES ('rating_freetime', 7);
INSERT INTO `cron` VALUES ('rating_max', 300);
INSERT INTO `cron` VALUES ('rating_perdownload', 1);
INSERT INTO `cron` VALUES ('rating_perinvite', 5);
INSERT INTO `cron` VALUES ('rating_perleech', 1);
INSERT INTO `cron` VALUES ('rating_perrelease', 5);
INSERT INTO `cron` VALUES ('rating_perrequest', 10);
INSERT INTO `cron` VALUES ('rating_perseed', 1);
INSERT INTO `cron` VALUES ('remotecheck_disabled', 0);
INSERT INTO `cron` VALUES ('remotecheck_interval', 2);
INSERT INTO `cron` VALUES ('remotepeers_cleantime', 10800);
INSERT INTO `cron` VALUES ('remote_lastchecked', 0);
INSERT INTO `cron` VALUES ('remote_trackers', 50);
INSERT INTO `cron` VALUES ('signup_timeout', 5);
INSERT INTO `cron` VALUES ('ttl_days', 100);

-- --------------------------------------------------------

-- 
-- ��������� ������� `cron_emails`
-- 

CREATE TABLE `cron_emails` (
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- 
-- ���� ������ ������� `cron_emails`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `dchubs`
-- 

CREATE TABLE `dchubs` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `sort` int(3) NOT NULL default '0',
  `announce_url` varchar(500) NOT NULL,
  `mask` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `dchubs`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `files`
-- 

CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrent` int(10) unsigned NOT NULL default '0',
  `filename` varchar(255) NOT NULL,
  `size` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `torrent` (`torrent`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `files`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `friends`
-- 

CREATE TABLE `friends` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `friendid` int(10) unsigned NOT NULL default '0',
  `confirmed` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userid` (`userid`,`friendid`),
  UNIQUE KEY `friendid` (`friendid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `friends`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `invites`
-- 

CREATE TABLE `invites` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `inviter` int(10) unsigned NOT NULL default '0',
  `inviteid` int(10) NOT NULL default '0',
  `invite` varchar(32) NOT NULL,
  `time_invited` int(10) NOT NULL,
  `confirmed` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `invites`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `messages`
-- 

CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sender` int(10) unsigned NOT NULL default '0',
  `receiver` int(10) unsigned NOT NULL default '0',
  `added` int(10) default NULL,
  `subject` varchar(255) NOT NULL,
  `msg` text,
  `unread` tinyint(1) NOT NULL default '1',
  `poster` int(10) unsigned NOT NULL default '0',
  `location` tinyint(1) NOT NULL default '1',
  `saved` tinyint(1) NOT NULL default '0',
  `archived` tinyint(1) NOT NULL default '0',
  `archived_receiver` tinyint(1) NOT NULL default '0',
  `spamid` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `receiver` (`receiver`),
  KEY `sender` (`sender`),
  KEY `poster` (`poster`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `messages`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `news`
-- 

CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `added` int(10) NOT NULL,
  `body` text NOT NULL,
  `subject` varchar(300) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `added` (`added`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `news`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `newscomments`
-- 

CREATE TABLE `newscomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `news` int(10) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(10) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `news` (`news`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `newscomments`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `notifs`
-- 

CREATE TABLE `notifs` (
  `id` int(11) NOT NULL auto_increment,
  `checkid` int(11) NOT NULL default '0',
  `type` varchar(100) NOT NULL,
  `userid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `checkid` (`checkid`,`type`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `notifs`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `orbital_blocks`
-- 

CREATE TABLE `orbital_blocks` (
  `bid` int(10) NOT NULL auto_increment,
  `bkey` varchar(15) NOT NULL,
  `title` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `bposition` char(1) NOT NULL,
  `weight` int(10) NOT NULL default '1',
  `active` int(1) NOT NULL default '1',
  `time` int(10) NOT NULL default '0',
  `blockfile` varchar(255) NOT NULL,
  `view` int(1) NOT NULL default '0',
  `expire` int(10) NOT NULL default '0',
  `action` char(1) NOT NULL,
  `which` varchar(255) NOT NULL,
  PRIMARY KEY  (`bid`),
  KEY `title` (`title`),
  KEY `weight` (`weight`),
  KEY `active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=16 ;

-- 
-- ���� ������ ������� `orbital_blocks`
-- 

INSERT INTO `orbital_blocks` VALUES (1, '', '�������!', '', 'c', 2, 1, 0, 'block-indextorrents.php', 0, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (2, '', '�������', '', 'r', 2, 1, 0, 'block-news.php', 0, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (3, '', 'Serverload', '', 'c', 3, 1, 0, 'block-server_load.php', 2, 0, 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (4, '', '����� ����������', '', 'r', 1, 1, 0, 'block-login.php', 1, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (5, '', '������', '', 'd', 3, 1, 0, 'block-polls.php', 1, 0, 'd', 'ihome,userdetails,');
INSERT INTO `orbital_blocks` VALUES (6, '', '� �������', 'about project', 'r', 5, 1, 0, '', 0, 0, 'd', 'faq,rules,signup,');
INSERT INTO `orbital_blocks` VALUES (7, '', '������������ ������', '', 'r', 3, 1, 0, 'block-online.php', 1, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (8, '', '����������', '<h2><span style="color: #ffcc00;">����� �����, ��� ��� �������, ���������� �� ����������:</span></h2>', 'd', 1, 1, 0, 'block-stats.php', 0, 0, 'd', 'signup,');
INSERT INTO `orbital_blocks` VALUES (9, '', '����������', '', 'd', 2, 1, 0, 'block-stats.php', 0, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (10, '', '����', '', 'r', 4, 1, 0, 'block-cloud.php', 0, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (11, '', 'helpseed', '', 'd', 4, 0, 0, 'block-helpseed.php', 2, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (12, '', '����������� ������', '', 'r', 7, 1, 0, 'block-cen.php', 1, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (13, '', '����� �������', 'nothing here', 'c', 1, 0, 0, '', 0, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (14, '', '��������� �����������', '', 'r', 6, 1, 0, 'block-req.php', 1, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (15, '', 'DISCLAIMER', 'disclaimer', 'd', 5, 1, 0, '', 0, 0, 'd', 'ihome,');

-- --------------------------------------------------------

-- 
-- ��������� ������� `pagecomments`
-- 

CREATE TABLE `pagecomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `page` int(10) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(10) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  `post_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `torrent` (`page`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `pagecomments`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `pages`
-- 

CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `category` varchar(100) NOT NULL,
  `owner` int(10) NOT NULL default '0',
  `added` int(10) NOT NULL,
  `name` varchar(300) NOT NULL,
  `class` int(2) NOT NULL default '0',
  `tags` varchar(500) NOT NULL,
  `content` text NOT NULL,
  `comments` int(5) unsigned NOT NULL default '0',
  `indexed` tinyint(1) NOT NULL default '0',
  `sticky` tinyint(1) NOT NULL default '0',
  `ratingsum` int(5) NOT NULL default '0',
  `denycomments` tinyint(1) unsigned NOT NULL default '0',
  `views` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `pages`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `pagescategories`
-- 

CREATE TABLE `pagescategories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sort` int(10) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `parent_id` int(10) NOT NULL default '0',
  `class` int(2) NOT NULL default '0',
  `class_edit` int(2) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `pagescategories`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `peers`
-- 

CREATE TABLE `peers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrent` int(10) unsigned NOT NULL default '0',
  `peer_id` varchar(40) NOT NULL,
  `ip` varchar(64) NOT NULL,
  `port` smallint(5) unsigned NOT NULL default '0',
  `seeder` tinyint(1) NOT NULL default '0',
  `started` int(10) NOT NULL,
  `last_action` int(10) NOT NULL,
  `userid` int(10) unsigned NOT NULL default '0',
  `finishedat` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `torrent_peer_id` (`torrent`,`peer_id`),
  KEY `torrent` (`torrent`),
  KEY `torrent_seeder` (`torrent`,`seeder`),
  KEY `last_action` (`last_action`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `peers`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `pollcomments`
-- 

CREATE TABLE `pollcomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `poll` int(10) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(10) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `poll` (`poll`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `pollcomments`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `polls`
-- 

CREATE TABLE `polls` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `question` varchar(255) NOT NULL,
  `start` int(10) NOT NULL,
  `exp` int(10) default NULL,
  `public` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `polls`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `polls_structure`
-- 

CREATE TABLE `polls_structure` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pollid` int(10) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `polls_structure`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `polls_votes`
-- 

CREATE TABLE `polls_votes` (
  `vid` int(10) unsigned NOT NULL auto_increment,
  `sid` int(10) NOT NULL default '0',
  `user` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  PRIMARY KEY  (`vid`),
  UNIQUE KEY `sid` (`sid`,`user`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `polls_votes`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `ratings`
-- 

CREATE TABLE `ratings` (
  `id` int(6) NOT NULL auto_increment,
  `rid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `type` varchar(30) NOT NULL,
  `added` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `rid` (`rid`,`userid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `ratings`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `relgroups`
-- 

CREATE TABLE `relgroups` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `added` int(10) NOT NULL default '0',
  `spec` varchar(500) NOT NULL,
  `descr` text NOT NULL,
  `image` varchar(300) NOT NULL,
  `owners` varchar(100) NOT NULL,
  `members` varchar(255) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `private` tinyint(1) NOT NULL default '0',
  `only_invites` tinyint(1) unsigned NOT NULL default '0',
  `amount` int(3) NOT NULL default '0',
  `page_pay` varchar(300) NOT NULL,
  `subscribe_length` int(2) NOT NULL default '31',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `relgroups`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `reltemplates`
-- 

CREATE TABLE `reltemplates` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `reltemplates`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `reports`
-- 

CREATE TABLE `reports` (
  `id` int(11) NOT NULL auto_increment,
  `reportid` int(10) NOT NULL default '0',
  `userid` int(10) NOT NULL default '0',
  `type` varchar(100) NOT NULL,
  `motive` varchar(255) NOT NULL,
  `added` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `reportid` (`reportid`,`userid`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=3 ;

-- 
-- ���� ������ ������� `reports`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `reqcomments`
-- 

CREATE TABLE `reqcomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `request` int(10) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(10) NOT NULL,
  `ratingsum` int(5) NOT NULL default '9',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `news` (`request`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `reqcomments`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `requests`
-- 

CREATE TABLE `requests` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `request` varchar(225) default NULL,
  `descr` text NOT NULL,
  `added` int(10) NOT NULL,
  `hits` int(10) unsigned NOT NULL default '0',
  `filled` varchar(200) NOT NULL,
  `comments` int(10) unsigned NOT NULL default '0',
  `cat` int(10) unsigned NOT NULL default '0',
  `filledby` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `requests`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `retrackers`
-- 

CREATE TABLE `retrackers` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `sort` int(3) NOT NULL default '0',
  `announce_url` varchar(500) NOT NULL,
  `mask` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `retrackers`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `rg_invites`
-- 

CREATE TABLE `rg_invites` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `inviter` int(10) unsigned NOT NULL default '0',
  `rgid` int(5) NOT NULL default '0',
  `invite` varchar(32) NOT NULL,
  `time_invited` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `rg_invites`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `rg_subscribes`
-- 

CREATE TABLE `rg_subscribes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL,
  `rgid` int(5) unsigned NOT NULL,
  `valid_until` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userid` (`userid`,`rgid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `rg_subscribes`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `rgcomments`
-- 

CREATE TABLE `rgcomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `relgroup` int(5) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(11) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `news` (`relgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `rgcomments`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `rgnews`
-- 

CREATE TABLE `rgnews` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `relgroup` int(5) NOT NULL default '0',
  `added` int(10) NOT NULL,
  `body` text NOT NULL,
  `subject` varchar(300) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `added` (`added`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `rgnews`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `rgnewscomments`
-- 

CREATE TABLE `rgnewscomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `rgnews` int(10) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(11) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `news` (`rgnews`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `rgnewscomments`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `sessions`
-- 

CREATE TABLE `sessions` (
  `sid` varchar(32) NOT NULL,
  `uid` int(10) NOT NULL default '0',
  `username` varchar(40) NOT NULL,
  `class` tinyint(4) NOT NULL default '0',
  `ip` varchar(40) NOT NULL,
  `time` bigint(30) NOT NULL default '0',
  `url` varchar(150) NOT NULL,
  `useragent` text,
  PRIMARY KEY  (`sid`),
  KEY `time` (`time`),
  KEY `uid` (`uid`),
  KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- 
-- ���� ������ ������� `sessions`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `sitelog`
-- 

CREATE TABLE `sitelog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` int(10) default NULL,
  `userid` int(10) NOT NULL default '0',
  `txt` text,
  `type` varchar(80) NOT NULL default 'tracker',
  PRIMARY KEY  (`id`),
  KEY `added` (`added`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `sitelog`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `snatched`
-- 

CREATE TABLE `snatched` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) default '0',
  `torrent` int(10) unsigned NOT NULL default '0',
  `startedat` int(10) NOT NULL,
  `completedat` int(10) NOT NULL,
  `finished` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `snatch` (`torrent`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `snatched`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `stamps`
-- 

CREATE TABLE `stamps` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sort` int(10) NOT NULL default '0',
  `class` tinyint(3) NOT NULL default '0',
  `image` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `stamps`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `stylesheets`
-- 

CREATE TABLE `stylesheets` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uri` varchar(255) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uri` (`uri`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

-- 
-- ���� ������ ������� `stylesheets`
-- 

INSERT INTO `stylesheets` VALUES (1, 'kinokpk', 'Kinokpk.com releaser 3');

-- --------------------------------------------------------

-- 
-- ��������� ������� `torrents`
-- 

CREATE TABLE `torrents` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `info_hash` varbinary(40) NOT NULL default '',
  `tiger_hash` varbinary(38) default NULL,
  `name` varchar(255) NOT NULL,
  `descr` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  `images` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `size` bigint(20) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL default '0',
  `ismulti` tinyint(1) NOT NULL default '0',
  `numfiles` int(10) unsigned NOT NULL default '1',
  `comments` int(10) unsigned NOT NULL default '0',
  `views` int(10) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `times_completed` int(10) unsigned NOT NULL default '0',
  `last_action` int(10) NOT NULL default '0',
  `last_reseed` int(10) NOT NULL default '0',
  `visible` tinyint(1) NOT NULL default '1',
  `banned` tinyint(1) NOT NULL default '0',
  `owner` int(10) unsigned NOT NULL default '0',
  `orig_owner` int(10) unsigned NOT NULL default '0',
  `ratingsum` int(10) NOT NULL default '0',
  `free` tinyint(1) NOT NULL default '0',
  `sticky` tinyint(1) NOT NULL default '0',
  `moderated` tinyint(1) NOT NULL default '0',
  `modcomm` text NOT NULL,
  `moderatedby` int(10) unsigned default '0',
  `freefor` text NOT NULL,
  `relgroup` int(5) NOT NULL,
  `topic_id` int(10) NOT NULL default '0',
  `online` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `info_hash` (`info_hash`),
  KEY `owner` (`owner`),
  KEY `visible` (`visible`),
  KEY `category_visible` (`category`,`visible`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `torrents`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `trackers`
-- 

CREATE TABLE `trackers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrent` int(10) unsigned NOT NULL,
  `tracker` varchar(255) NOT NULL default 'localhost',
  `seeders` int(5) unsigned NOT NULL default '0',
  `leechers` int(5) unsigned NOT NULL default '0',
  `lastchecked` int(10) unsigned NOT NULL default '0',
  `state` varchar(300) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `torrent` (`torrent`,`tracker`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `trackers`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `usercomments`
-- 

CREATE TABLE `usercomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(11) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `news` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `usercomments`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `users`
-- 

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(40) NOT NULL,
  `old_password` varchar(40) NOT NULL,
  `passhash` varchar(32) NOT NULL,
  `secret` varchar(20) NOT NULL,
  `email` varchar(80) NOT NULL,
  `confirmed` tinyint(1) NOT NULL default '0',
  `added` int(10) NOT NULL default '0',
  `last_login` int(10) NOT NULL default '0',
  `last_access` int(10) NOT NULL default '0',
  `editsecret` varchar(20) NOT NULL,

  `privacy` enum('strong','normal','highest') NOT NULL default 'normal',

  `stylesheet` int(10) default '1',
  `info` text,
  `ratingsum` int(8) NOT NULL default '0',
  `acceptpms` enum('yes','friends','no') NOT NULL default 'yes',
  `pron` tinyint(1) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  `class` tinyint(3) unsigned NOT NULL default '0',
  `supportfor` text,
  `avatar` varchar(100) NOT NULL,
  `icq` varchar(255) NOT NULL,
  `msn` varchar(255) NOT NULL,
  `aim` varchar(255) NOT NULL,
  `yahoo` varchar(255) NOT NULL,
  `skype` varchar(255) NOT NULL,
  `mirc` varchar(255) NOT NULL,
  `website` varchar(50) NOT NULL,
  `title` varchar(30) NOT NULL,
  `country` int(10) unsigned NOT NULL default '0',
  `notifs` varchar(1000) NOT NULL,
  `emailnotifs` varchar(1000) NOT NULL,
  `modcomment` text,
  `enabled` tinyint(1) NOT NULL default '1',
  `dis_reason` text NOT NULL,
  `timezone` int(2) NOT NULL default '0',
  `avatars` tinyint(1) NOT NULL default '1',
  `extra_ef` tinyint(1) NOT NULL default '1',
  `donor` tinyint(1) default '0',
  `warned` tinyint(1) default '0',
  `warneduntil` int(10) NOT NULL,
  `deletepms` tinyint(1) default '0',
  `savepms` tinyint(1) NOT NULL default '1',
  `gender` smallint(1) NOT NULL default '0',
  `birthday` date default '0000-00-00',
  `passkey` varchar(32) NOT NULL,
  `language` varchar(255) default NULL,
  `invites` int(10) NOT NULL default '0',
  `invitedby` int(10) NOT NULL default '0',
  `invitedroot` int(10) NOT NULL default '0',
  `passkey_ip` varchar(15) NOT NULL,
  `num_warned` int(2) NOT NULL default '0',
  `status` varchar(255) NOT NULL,
  `last_downloaded` int(10) NOT NULL default '0',
  `last_checked` int(10) NOT NULL default '0',
  `last_announced` int(10) NOT NULL default '0',
  `discount` int(5) NOT NULL default '0',
  `viptill` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `status_added` (`confirmed`,`added`),
  KEY `ip` (`ip`),
  KEY `country` (`country`),
  KEY `last_access` (`last_access`),
  KEY `enabled` (`enabled`),
  KEY `warned` (`warned`),
  KEY `user` (`id`,`confirmed`,`enabled`),
  KEY `passkey` (`passkey`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `users`
-- 

INSERT INTO `cache_stats` (
=======
`cache_name` ,
`cache_value`
)
VALUES (
'low_comment_hide', '-3'
);

INSERT INTO `cache_stats` (`cache_name`, `cache_value`) VALUES
('debug_language', '0');
=======
);

CREATE TABLE IF NOT EXISTS `languages` (
  `lkey` varchar(255) NOT NULL,
  `ltranslate` varchar(2) NOT NULL,
  `lvalue` text NOT NULL,
  UNIQUE KEY `key` (`lkey`,`ltranslate`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;