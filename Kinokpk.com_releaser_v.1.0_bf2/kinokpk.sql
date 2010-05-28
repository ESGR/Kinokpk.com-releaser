-- phpMyAdmin SQL Dump
-- version 2.6.1
-- http://www.phpmyadmin.net
-- 
-- ����: localhost
-- ����� ��������: ��� 31 2008 �., 21:34
-- ������ �������: 5.0.18
-- ������ PHP: 5.1.6
-- 
-- ��: `kinokpk`
-- 

-- --------------------------------------------------------

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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `addedrequests`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `avps`
-- 

CREATE TABLE `avps` (
  `arg` varchar(20) NOT NULL default '',
  `value_s` text NOT NULL,
  `value_i` int(11) NOT NULL default '0',
  `value_u` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`arg`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- 
-- ���� ������ ������� `avps`
-- 

INSERT INTO `avps` VALUES ('lastcleantime', '', 0, 1220203945);

-- --------------------------------------------------------

-- 
-- ��������� ������� `bans`
-- 

CREATE TABLE `bans` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `addedby` int(10) unsigned NOT NULL default '0',
  `comment` varchar(255) NOT NULL default '',
  `first` int(11) default NULL,
  `last` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `first_last` (`first`,`last`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `bans`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `blocks`
-- 

CREATE TABLE `blocks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `blockid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userfriend` (`userid`,`blockid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `blocks`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `bonus`
-- 

CREATE TABLE `bonus` (
  `id` int(5) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `points` decimal(5,2) NOT NULL default '0.00',
  `description` text NOT NULL,
  `type` varchar(10) NOT NULL default 'traffic',
  `quanity` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `bonus`
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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `bookmarks`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `captcha`
-- 

CREATE TABLE `captcha` (
  `imagehash` varchar(32) NOT NULL default '',
  `imagestring` varchar(8) NOT NULL default '',
  `dateline` bigint(30) NOT NULL default '0',
  KEY `imagehash` (`imagehash`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- 
-- ���� ������ ������� `captcha`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `categories`
-- 

CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sort` int(10) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=2 ;

-- 
-- ���� ������ ������� `categories`
-- 

INSERT INTO `categories` VALUES (1, 10, '�������� �����', 'testcat.jpg');

-- --------------------------------------------------------

-- 
-- ��������� ������� `censoredtorrents`
-- 

CREATE TABLE `censoredtorrents` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `name` (`name`,`reason`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `censoredtorrents`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `checkcomm`
-- 

CREATE TABLE `checkcomm` (
  `id` int(11) NOT NULL auto_increment,
  `checkid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `offer` tinyint(4) NOT NULL default '0',
  `torrent` tinyint(4) NOT NULL default '0',
  `req` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=6 ;

-- 
-- ���� ������ ������� `checkcomm`
-- 

INSERT INTO `checkcomm` VALUES (1, 1, 1, 0, 1, 0);
INSERT INTO `checkcomm` VALUES (2, 1, 1, 0, 1, 0);
INSERT INTO `checkcomm` VALUES (3, 2, 1, 0, 1, 0);
INSERT INTO `checkcomm` VALUES (4, 3, 1, 0, 1, 0);
INSERT INTO `checkcomm` VALUES (5, 4, 1, 0, 1, 0);

-- --------------------------------------------------------

-- 
-- ��������� ������� `comments`
-- 

CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `torrent` int(10) unsigned NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `ori_text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` datetime NOT NULL default '0000-00-00 00:00:00',
  `request` varchar(11) NOT NULL default '0',
  `offer` varchar(11) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `post_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `torrent` (`torrent`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=103 ;

-- 
-- ���� ������ ������� `countries`
-- 

INSERT INTO `countries` VALUES (87, 'Antigua Barbuda', 'antiguabarbuda.gif');
INSERT INTO `countries` VALUES (33, 'Belize', 'belize.gif');
INSERT INTO `countries` VALUES (59, 'Burkina Faso', 'burkinafaso.gif');
INSERT INTO `countries` VALUES (10, 'Denmark', 'denmark.gif');
INSERT INTO `countries` VALUES (91, 'Senegal', 'senegal.gif');
INSERT INTO `countries` VALUES (76, 'Trinidad & Tobago', 'trinidadandtobago.gif');
INSERT INTO `countries` VALUES (20, '���������', 'australia.gif');
INSERT INTO `countries` VALUES (36, '�������', 'austria.gif');
INSERT INTO `countries` VALUES (27, '�������', 'albania.gif');
INSERT INTO `countries` VALUES (34, '�����', 'algeria.gif');
INSERT INTO `countries` VALUES (12, '������', 'uk.gif');
INSERT INTO `countries` VALUES (35, '������', 'angola.gif');
INSERT INTO `countries` VALUES (66, '������', 'andorra.gif');
INSERT INTO `countries` VALUES (19, '���������', 'argentina.gif');
INSERT INTO `countries` VALUES (53, '����������', 'afghanistan.gif');
INSERT INTO `countries` VALUES (80, '������', 'bahamas.gif');
INSERT INTO `countries` VALUES (83, '��������', 'barbados.gif');
INSERT INTO `countries` VALUES (16, '�������', 'belgium.gif');
INSERT INTO `countries` VALUES (84, '���������', 'bangladesh.gif');
INSERT INTO `countries` VALUES (101, '��������', 'bulgaria.gif');
INSERT INTO `countries` VALUES (65, '������', 'bosniaherzegovina.gif');
INSERT INTO `countries` VALUES (18, '��������', 'brazil.gif');
INSERT INTO `countries` VALUES (74, '�������', 'vanuatu.gif');
INSERT INTO `countries` VALUES (72, '�������', 'hungary.gif');
INSERT INTO `countries` VALUES (71, '���������', 'venezuela.gif');
INSERT INTO `countries` VALUES (75, '�������', 'vietnam.gif');
INSERT INTO `countries` VALUES (7, '��������', 'germany.gif');
INSERT INTO `countries` VALUES (77, '��������', 'honduras.gif');
INSERT INTO `countries` VALUES (32, '���� ����', 'hongkong.gif');
INSERT INTO `countries` VALUES (41, '������', 'greece.gif');
INSERT INTO `countries` VALUES (42, '���������', 'guatemala.gif');
INSERT INTO `countries` VALUES (40, '������������� ����������', 'dominicanrep.gif');
INSERT INTO `countries` VALUES (100, '�����', 'egypt.gif');
INSERT INTO `countries` VALUES (43, '�������', 'israel.gif');
INSERT INTO `countries` VALUES (26, '�����', 'india.gif');
INSERT INTO `countries` VALUES (13, '��������', 'ireland.gif');
INSERT INTO `countries` VALUES (61, '��������', 'iceland.gif');
INSERT INTO `countries` VALUES (102, '���� �� ������', 'jollyroger.gif');
INSERT INTO `countries` VALUES (22, '�������', 'spain.gif');
INSERT INTO `countries` VALUES (9, '������', 'italy.gif');
INSERT INTO `countries` VALUES (82, '��������', 'cambodia.gif');
INSERT INTO `countries` VALUES (5, '������', 'canada.gif');
INSERT INTO `countries` VALUES (78, '���������', 'kyrgyzstan.gif');
INSERT INTO `countries` VALUES (57, '��������', 'kiribati.gif');
INSERT INTO `countries` VALUES (8, '�����', 'china.gif');
INSERT INTO `countries` VALUES (52, '�����', 'congo.gif');
INSERT INTO `countries` VALUES (96, '��������', 'colombia.gif');
INSERT INTO `countries` VALUES (99, '����� ����', 'costarica.gif');
INSERT INTO `countries` VALUES (51, '����', 'cuba.gif');
INSERT INTO `countries` VALUES (85, '����', 'laos.gif');
INSERT INTO `countries` VALUES (98, '������', 'latvia.gif');
INSERT INTO `countries` VALUES (97, '�������', 'lebanon.gif');
INSERT INTO `countries` VALUES (67, '�����', 'lithuania.gif');
INSERT INTO `countries` VALUES (31, '����������', 'luxembourg.gif');
INSERT INTO `countries` VALUES (68, '���������', 'macedonia.gif');
INSERT INTO `countries` VALUES (39, '��������', 'malaysia.gif');
INSERT INTO `countries` VALUES (24, '�������', 'mexico.gif');
INSERT INTO `countries` VALUES (62, '�����', 'nauru.gif');
INSERT INTO `countries` VALUES (60, '�������', 'nigeria.gif');
INSERT INTO `countries` VALUES (69, '������������� �������', 'nethantilles.gif');
INSERT INTO `countries` VALUES (15, '����������', 'netherlands.gif');
INSERT INTO `countries` VALUES (21, '����� ��������', 'newzealand.gif');
INSERT INTO `countries` VALUES (11, '��������', 'norway.gif');
INSERT INTO `countries` VALUES (44, '��������', 'pakistan.gif');
INSERT INTO `countries` VALUES (88, '��������', 'paraguay.gif');
INSERT INTO `countries` VALUES (81, '����', 'peru.gif');
INSERT INTO `countries` VALUES (14, '������', 'poland.gif');
INSERT INTO `countries` VALUES (23, '����������', 'portugal.gif');
INSERT INTO `countries` VALUES (49, '������ ����', 'puertorico.gif');
INSERT INTO `countries` VALUES (3, '������', 'russia.gif');
INSERT INTO `countries` VALUES (73, '�������', 'romania.gif');
INSERT INTO `countries` VALUES (93, '�������� �����', 'northkorea.gif');
INSERT INTO `countries` VALUES (47, '����������� �������', 'seychelles.gif');
INSERT INTO `countries` VALUES (46, '������', 'serbia.gif');
INSERT INTO `countries` VALUES (25, '��������', 'singapore.gif');
INSERT INTO `countries` VALUES (63, '��������', 'slovenia.gif');
INSERT INTO `countries` VALUES (90, '����', 'ussr.gif');
INSERT INTO `countries` VALUES (2, '���', 'usa.gif');
INSERT INTO `countries` VALUES (48, '�������', 'taiwan.gif');
INSERT INTO `countries` VALUES (89, '�������', 'thailand.gif');
INSERT INTO `countries` VALUES (92, '����', 'togo.gif');
INSERT INTO `countries` VALUES (64, '������������', 'turkmenistan.gif');
INSERT INTO `countries` VALUES (54, '������', 'turkey.gif');
INSERT INTO `countries` VALUES (55, '����������', 'uzbekistan.gif');
INSERT INTO `countries` VALUES (70, '�������', 'ukraine.gif');
INSERT INTO `countries` VALUES (86, '�������', 'uruguay.gif');
INSERT INTO `countries` VALUES (58, '���������', 'philippines.gif');
INSERT INTO `countries` VALUES (4, '���������', 'finland.gif');
INSERT INTO `countries` VALUES (6, '�������', 'france.gif');
INSERT INTO `countries` VALUES (94, '��������', 'croatia.gif');
INSERT INTO `countries` VALUES (45, '�����', 'czechrep.gif');
INSERT INTO `countries` VALUES (50, '����', 'chile.gif');
INSERT INTO `countries` VALUES (56, '���������', 'switzerland.gif');
INSERT INTO `countries` VALUES (1, '������', 'sweden.gif');
INSERT INTO `countries` VALUES (79, '�������', 'ecuador.gif');
INSERT INTO `countries` VALUES (95, '�������', 'estonia.gif');
INSERT INTO `countries` VALUES (37, '���������', 'yugoslavia.gif');
INSERT INTO `countries` VALUES (28, '����� ������', 'southafrica.gif');
INSERT INTO `countries` VALUES (29, '����� �����', 'southkorea.gif');
INSERT INTO `countries` VALUES (38, '����� �����', 'westernsamoa.gif');

-- --------------------------------------------------------

-- 
-- ��������� ������� `files`
-- 

CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrent` int(10) unsigned NOT NULL default '0',
  `filename` varchar(255) NOT NULL default '',
  `size` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `torrent` (`torrent`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

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
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userfriend` (`userid`,`friendid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

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
  `invite` varchar(32) NOT NULL default '',
  `time_invited` datetime NOT NULL default '0000-00-00 00:00:00',
  `confirmed` char(3) NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  KEY `inviter` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

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
  `added` datetime default NULL,
  `subject` varchar(255) NOT NULL default '',
  `msg` text,
  `unread` enum('yes','no') NOT NULL default 'yes',
  `poster` int(10) unsigned NOT NULL default '0',
  `location` tinyint(1) NOT NULL default '1',
  `saved` enum('no','yes') NOT NULL default 'no',
  `archived` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  KEY `receiver` (`receiver`),
  KEY `sender` (`sender`),
  KEY `poster` (`poster`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

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
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `body` text NOT NULL,
  `subject` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=2 ;

-- 
-- ���� ������ ������� `news`
-- 

INSERT INTO `news` VALUES (1, 1, '2008-08-27 19:42:40', '���� �� ������ ��� �������, ������ ��������� �������� Kinokpk.com ������ �������!\r\n\r\n��� ��� ������ �������� ����� \r\n� ����� �������� ��������� "�������� �����", ������� ������������� ���������� ������ ��������� IPB.\r\n\r\n�� ������ ������� �����, ����� ������� ������� ������� � ��������.', '��������� �������!');

-- --------------------------------------------------------

-- 
-- ��������� ������� `newscomments`
-- 

CREATE TABLE `newscomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `news` int(10) unsigned NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `ori_text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` datetime NOT NULL default '0000-00-00 00:00:00',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `news` (`news`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `newscomments`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `notconnectablepmlog`
-- 

CREATE TABLE `notconnectablepmlog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `notconnectablepmlog`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `offers`
-- 

CREATE TABLE `offers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `name` varchar(225) default NULL,
  `descr` text NOT NULL,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `category` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `votes` smallint(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `offers`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `offervotes`
-- 

CREATE TABLE `offervotes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `offerid` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `offervotes`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `orbital_blocks`
-- 

CREATE TABLE `orbital_blocks` (
  `bid` int(10) NOT NULL auto_increment,
  `bkey` varchar(15) NOT NULL default '',
  `title` varchar(60) NOT NULL default '',
  `content` text NOT NULL,
  `bposition` char(1) NOT NULL default '',
  `weight` int(10) NOT NULL default '1',
  `active` int(1) NOT NULL default '1',
  `time` varchar(14) NOT NULL default '0',
  `blockfile` varchar(255) NOT NULL default '',
  `view` int(1) NOT NULL default '0',
  `expire` varchar(14) NOT NULL default '0',
  `action` char(1) NOT NULL default '',
  `which` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`bid`),
  KEY `title` (`title`),
  KEY `weight` (`weight`),
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=23 ;

-- 
-- ���� ������ ������� `orbital_blocks`
-- 

INSERT INTO `orbital_blocks` VALUES (1, '', '�������������', '<table border="0"><tr>\r\n<td class="block"><a href="admincp.php">�������</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="online.php">�� �� ������?!</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="news.php">������������� �������</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="users.php">������ �������������</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="viewreport.php">������ �� ��������</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="staffmess.php">�������� ��</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="ipcheck.php">�������� �� IP</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="logout.php">�����</a></td>\r\n</tr>\r\n</table>', 'l', 3, 1, '', '', 2, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (8, '', '����������', '', 'd', 2, 1, '', 'block-stats.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (9, '', '������, ������� ����� ���������', '', 'c', 1, 1, '', 'block-helpseed.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (10, '', '����������� � ��������', '<p align="jsutify">������������� ������� ����� - ������������ ������� � ����������, ������� ������ � ���� ����� �������� � ������������ ��� ��������������, ��������� ��� ����� ����� ���������� �����. ������������ ������ �������� ����� - �� ���������� ��� �� �����, �� ���� �������� ������ � ���������, ����������� ���� ������� ��� ����� ����� � 1, � �� ������ ������ ������������ � ���������. � �� ��������, ��� �� ��� �� �������������! (�����)</p>', 'c', 2, 1, '', '', 0, '0', 'd', 'rules,');
INSERT INTO `orbital_blocks` VALUES (2, '', '�������', '', 'c', 3, 1, '', 'block-news.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (3, '', '������������', '', 'd', 1, 1, '', 'block-online.php', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (4, '', '�����', '', 'l', 4, 1, '', 'block-search.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (5, '', '�����', '', 'c', 4, 1, '', 'block-polls.php', 1, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (6, '', '����� ������', '', 'c', 5, 0, '', 'block-releases.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (7, '', '���� ��� �� ������ ��������?)', '', 'c', 6, 0, '', 'block-forum.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (11, '', '�������� �������', '', 'c', 7, 0, '', 'block-server_load.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (12, '', '�������� �� �������', '', 'c', 8, 1, '', 'block-indextorrents.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (13, '', '�������������', '<center><a href="javascript:void(0)" title="SMS.������� � ����� ��������� ������" onClick="javascript:window.open(''http://smskopilka.ru/?info&id=36066'', ''smskopilka'',''width=400,height=480,status=no,toolbar=no, menubar=no,scrollbars=yes,resizable=yes'');">\r\n<img src="http://img.smskopilka.ru/common/digits/target2/36/36066-101.gif" border="0" alt="SMS.�������"></a><br>\r\nWebMoney:<br>\r\nR153898361884\r\nZ113282224168<br><hr>\r\n������� �������!</center> ', 'l', 5, 1, '', '', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (14, '', '�������� ?', '<center>\r\n<a href="contact.php"><font color="red"><u>�������� ������!<br><br>\r\n<a target="_blank" href="http://dev.kinokpk.com">����� ��������� ��������</a></u></font></a>\r\n</center><br>\r\n<i>..� ������������<br>\r\n..� ������<br>\r\n..� ����������</i>', 'l', 6, 1, '', '', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (15, '', '��� ������', '<center>\r\n��� ������:<br />\r\n<a href="http://www.kinokpk.com"><img src="http://www.kinokpk.com/pic/banner_small.gif" width="88" height="31" alt="Kinokpk.com - ���� ��� �� ������! - ������ ��� ��� � �� ������"></a>\r\n<br />\r\n<textarea  readonly="readonly"><a href="http://www.kinokpk.com"><img src="http://www.kinokpk.com/pic/banner_small.gif" width="88" height="31" alt="Kinokpk.com - ���� ��� �� ������! - ������ ��� ��� � �� ������"></a>\r\n</textarea></center>\r\n', 'l', 7, 1, '', '', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (16, '', '������', '<center>\r\n<a href="http://www.razdolbai.com"><img alt="Razdolbai.com - ��� � ������������� � ����� 1580 � ����" src="http://www.razdolbai.com/img/banner_slim_forfriends.gif"></a>\r\n</center>', 'l', 8, 0, '', '', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (17, '', '�������', '', 'l', 2, 1, '', 'block-req.php', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (18, '', '������� ?', '<center>\r\n��� ��������</center>', 'l', 9, 1, '', '', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (19, '', '����', '', 'l', 1, 1, '', 'block-login.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (22, '', '����������� ������', '', 'd', 3, 1, '', 'block-cen.php', 0, '0', 'd', 'all');

-- --------------------------------------------------------

-- 
-- ��������� ������� `peers`
-- 

CREATE TABLE `peers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrent` int(10) unsigned NOT NULL default '0',
  `peer_id` varchar(20) NOT NULL default '',
  `ip` varchar(64) NOT NULL default '',
  `port` smallint(5) unsigned NOT NULL default '0',
  `uploaded` bigint(20) unsigned NOT NULL default '0',
  `downloaded` bigint(20) unsigned NOT NULL default '0',
  `uploadoffset` bigint(20) unsigned NOT NULL default '0',
  `downloadoffset` bigint(20) unsigned NOT NULL default '0',
  `to_go` bigint(20) unsigned NOT NULL default '0',
  `seeder` enum('yes','no') NOT NULL default 'no',
  `started` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_action` datetime NOT NULL default '0000-00-00 00:00:00',
  `prev_action` datetime NOT NULL default '0000-00-00 00:00:00',
  `connectable` enum('yes','no') NOT NULL default 'yes',
  `userid` int(10) unsigned NOT NULL default '0',
  `agent` varchar(60) NOT NULL default '',
  `finishedat` int(10) unsigned NOT NULL default '0',
  `passkey` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `torrent_peer_id` (`torrent`,`peer_id`),
  KEY `torrent` (`torrent`),
  KEY `torrent_seeder` (`torrent`,`seeder`),
  KEY `last_action` (`last_action`),
  KEY `connectable` (`connectable`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `peers`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `pollanswers`
-- 

CREATE TABLE `pollanswers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pollid` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `selection` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pollid` (`pollid`),
  KEY `selection` (`selection`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `pollanswers`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `pollcomments`
-- 

CREATE TABLE `pollcomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `poll` int(10) unsigned NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `ori_text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` datetime NOT NULL default '0000-00-00 00:00:00',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `poll` (`poll`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `pollcomments`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `polls`
-- 

CREATE TABLE `polls` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `question` varchar(255) NOT NULL default '',
  `option0` varchar(40) NOT NULL default '',
  `option1` varchar(40) NOT NULL default '',
  `option2` varchar(40) NOT NULL default '',
  `option3` varchar(40) NOT NULL default '',
  `option4` varchar(40) NOT NULL default '',
  `option5` varchar(40) NOT NULL default '',
  `option6` varchar(40) NOT NULL default '',
  `option7` varchar(40) NOT NULL default '',
  `option8` varchar(40) NOT NULL default '',
  `option9` varchar(40) NOT NULL default '',
  `option10` varchar(40) NOT NULL default '',
  `option11` varchar(40) NOT NULL default '',
  `option12` varchar(40) NOT NULL default '',
  `option13` varchar(40) NOT NULL default '',
  `option14` varchar(40) NOT NULL default '',
  `option15` varchar(40) NOT NULL default '',
  `option16` varchar(40) NOT NULL default '',
  `option17` varchar(40) NOT NULL default '',
  `option18` varchar(40) NOT NULL default '',
  `option19` varchar(40) NOT NULL default '',
  `sort` enum('yes','no') NOT NULL default 'yes',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `polls`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `ratings`
-- 

CREATE TABLE `ratings` (
  `id` int(6) NOT NULL auto_increment,
  `torrent` int(10) NOT NULL default '0',
  `user` int(6) NOT NULL default '0',
  `rating` int(1) NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `ratings`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `readtorrents`
-- 

CREATE TABLE `readtorrents` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `torrentid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `read` (`userid`,`torrentid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=4 ;

-- 
-- ���� ������ ������� `readtorrents`
-- 

INSERT INTO `readtorrents` VALUES (1, 1, 1);
INSERT INTO `readtorrents` VALUES (2, 1, 3);
INSERT INTO `readtorrents` VALUES (3, 1, 4);

-- --------------------------------------------------------

-- 
-- ��������� ������� `report`
-- 

CREATE TABLE `report` (
  `id` int(11) NOT NULL auto_increment,
  `torrentid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `motive` varchar(255) NOT NULL,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `report`
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
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `hits` int(10) unsigned NOT NULL default '0',
  `uploaded` enum('yes','no') NOT NULL default 'no',
  `filled` varchar(200) NOT NULL default '',
  `torrentid` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `cat` int(10) unsigned NOT NULL default '0',
  `filledby` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `requests`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `sessions`
-- 

CREATE TABLE `sessions` (
  `sid` varchar(32) NOT NULL default '',
  `uid` int(10) NOT NULL default '0',
  `username` varchar(40) NOT NULL default '',
  `class` tinyint(4) NOT NULL default '0',
  `ip` varchar(40) NOT NULL default '',
  `time` bigint(30) NOT NULL default '0',
  `url` varchar(150) NOT NULL default '',
  `useragent` text,
  PRIMARY KEY  (`sid`),
  KEY `time` (`time`),
  KEY `uid` (`uid`),
  KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- 
-- ���� ������ ������� `sessions`
-- 

INSERT INTO `sessions` VALUES ('3663ca5c2d93eab2a274e6056cbef946', -1, '', -1, '127.0.0.1', 1220203945, '/', 'Mozilla/5.0 (Windows; U; Windows NT 6.0; ru; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1');

-- --------------------------------------------------------

-- 
-- ��������� ������� `simpaty`
-- 

CREATE TABLE `simpaty` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `touserid` int(10) unsigned NOT NULL default '0',
  `fromuserid` int(10) unsigned NOT NULL default '0',
  `fromusername` varchar(40) NOT NULL default '',
  `bad` tinyint(1) unsigned NOT NULL default '0',
  `good` tinyint(1) unsigned NOT NULL default '0',
  `type` varchar(60) NOT NULL default '',
  `respect_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `touserid` (`touserid`),
  KEY `fromuserid` (`fromuserid`),
  KEY `fromusername` (`fromusername`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `simpaty`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `sitelog`
-- 

CREATE TABLE `sitelog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` datetime default NULL,
  `color` varchar(11) NOT NULL default 'transparent',
  `txt` text,
  `type` varchar(8) NOT NULL default 'tracker',
  PRIMARY KEY  (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=11 ;

-- 
-- ���� ������ ������� `sitelog`
-- 

INSERT INTO `sitelog` VALUES (1, '2008-08-27 19:29:53', '', '��������� 0 ������������� (5 � ����� ��������������)', 'admin');
INSERT INTO `sitelog` VALUES (2, '2008-08-27 19:44:58', '', '��������� 0 ������������� (5 � ����� ��������������)', 'admin');
INSERT INTO `sitelog` VALUES (3, '2008-08-27 19:47:41', '5DDB6E', '������� ����� 1 (test / test + HTTP + FTP + ed2k | �����) ��� ����� ������������� admin', 'torrent');
INSERT INTO `sitelog` VALUES (4, '2008-08-27 19:52:46', '5DDB6E', '������� ����� 1 (testfs / ������ + HTTP + FTP + ed2k | �����) ��� ����� ������������� admin', 'torrent');
INSERT INTO `sitelog` VALUES (5, '2008-08-27 19:59:23', '5DDB6E', '������� ����� 2 (esgfsf / dsfds + HTTP + FTP + ed2k | �����) ��� ����� ������������� admin', 'torrent');
INSERT INTO `sitelog` VALUES (6, '2008-08-27 20:01:32', '5DDB6E', '������� ����� 3 (esgfsf����� / dsfds + HTTP + FTP + ed2k | �����) ��� ����� ������������� admin', 'torrent');
INSERT INTO `sitelog` VALUES (7, '2008-08-27 20:02:46', '5DDB6E', '������� ����� 4 (esgfsf����� / dsfds�sfds + HTTP + FTP + ed2k | �����) ��� ����� ������������� admin', 'torrent');
INSERT INTO `sitelog` VALUES (8, '2008-08-27 20:04:39', '', '��������� 0 ������������� (5 � ����� ��������������)', 'admin');
INSERT INTO `sitelog` VALUES (9, '2008-08-31 18:37:13', '', '��������� 0 ������������� (5 � ����� ��������������)', 'admin');
INSERT INTO `sitelog` VALUES (10, '2008-08-31 21:32:25', '', '��������� 0 ������������� (5 � ����� ��������������)', 'admin');

-- --------------------------------------------------------

-- 
-- ��������� ������� `siteonline`
-- 

CREATE TABLE `siteonline` (
  `onoff` int(1) NOT NULL default '1',
  `reason` varchar(255) NOT NULL default '',
  `class` int(2) NOT NULL default '6',
  `class_name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`onoff`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- 
-- ���� ������ ������� `siteonline`
-- 

INSERT INTO `siteonline` VALUES (1, '������� ����������', 6, '���');

-- --------------------------------------------------------

-- 
-- ��������� ������� `snatched`
-- 

CREATE TABLE `snatched` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) default '0',
  `torrent` int(10) unsigned NOT NULL default '0',
  `port` smallint(5) unsigned NOT NULL default '0',
  `uploaded` bigint(20) unsigned NOT NULL default '0',
  `downloaded` bigint(20) unsigned NOT NULL default '0',
  `to_go` bigint(20) unsigned NOT NULL default '0',
  `seeder` enum('yes','no') NOT NULL default 'no',
  `last_action` datetime NOT NULL default '0000-00-00 00:00:00',
  `startdat` datetime NOT NULL default '0000-00-00 00:00:00',
  `completedat` datetime NOT NULL default '0000-00-00 00:00:00',
  `connectable` enum('yes','no') NOT NULL default 'yes',
  `finished` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  KEY `snatch` (`torrent`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `snatched`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `sources`
-- 

CREATE TABLE `sources` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=13 ;

-- 
-- ���� ������ ������� `sources`
-- 

INSERT INTO `sources` VALUES (1, 'CamRip (CAM)');
INSERT INTO `sources` VALUES (2, 'Telesync (TS)');
INSERT INTO `sources` VALUES (3, 'Screener (SCR)');
INSERT INTO `sources` VALUES (4, 'DVDScreener (SCR)');
INSERT INTO `sources` VALUES (5, 'Workprint (WP)');
INSERT INTO `sources` VALUES (6, 'Telecine (TC)');
INSERT INTO `sources` VALUES (7, 'VHSrip');
INSERT INTO `sources` VALUES (8, 'TVrip');
INSERT INTO `sources` VALUES (9, 'SATrip');
INSERT INTO `sources` VALUES (10, 'DVDRip');
INSERT INTO `sources` VALUES (11, 'HDDVD/BDrip');
INSERT INTO `sources` VALUES (12, 'HDTVrip');

-- --------------------------------------------------------

-- 
-- ��������� ������� `stamps`
-- 

CREATE TABLE `stamps` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sort` int(10) NOT NULL default '0',
  `class` tinyint(3) NOT NULL default '0',
  `image` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `image` (`image`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=2 ;

-- 
-- ���� ������ ������� `stamps`
-- 

INSERT INTO `stamps` VALUES (1, 1, 0, 'teststamp.jpg');

-- --------------------------------------------------------

-- 
-- ��������� ������� `stylesheets`
-- 

CREATE TABLE `stylesheets` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uri` varchar(255) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `stylesheets`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `thanks`
-- 

CREATE TABLE `thanks` (
  `id` int(11) NOT NULL auto_increment,
  `torrentid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `torrentid` (`torrentid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `thanks`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `torrents`
-- 

CREATE TABLE `torrents` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `info_hash` varbinary(40) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `save_as` varchar(255) NOT NULL default '',
  `search_text` text NOT NULL,
  `descr` text NOT NULL,
  `ori_descr` text NOT NULL,
  `image1` text NOT NULL,
  `image2` text NOT NULL,
  `category` int(10) unsigned NOT NULL default '0',
  `size` bigint(20) unsigned NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `type` enum('single','multi') NOT NULL default 'single',
  `numfiles` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `views` int(10) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `times_completed` int(10) unsigned NOT NULL default '0',
  `leechers` int(10) unsigned NOT NULL default '0',
  `seeders` int(10) unsigned NOT NULL default '0',
  `last_action` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_reseed` datetime NOT NULL default '0000-00-00 00:00:00',
  `visible` enum('yes','no') NOT NULL default 'yes',
  `banned` enum('yes','no') NOT NULL default 'no',
  `owner` int(10) unsigned NOT NULL default '0',
  `orig_owner` int(10) unsigned NOT NULL default '0',
  `numratings` int(10) unsigned NOT NULL default '0',
  `ratingsum` int(10) unsigned NOT NULL default '0',
  `free` enum('yes','no') default 'no',
  `sticky` enum('yes','no') NOT NULL default 'no',
  `moderated` enum('yes','no') NOT NULL default 'no',
  `moderatedby` int(10) unsigned default '0',
  `topic_id` int(10) NOT NULL default '0',
  `online` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `info_hash` (`info_hash`),
  KEY `owner` (`owner`),
  KEY `visible` (`visible`),
  KEY `category_visible` (`category`,`visible`),
  FULLTEXT KEY `ft_search` (`search_text`,`ori_descr`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `torrents`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `users`
-- 

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(40) NOT NULL default '',
  `old_password` varchar(40) NOT NULL default '',
  `passhash` varchar(32) NOT NULL default '',
  `secret` varchar(20) NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  `status` enum('pending','confirmed') NOT NULL default 'pending',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_login` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_access` datetime NOT NULL default '0000-00-00 00:00:00',
  `editsecret` varchar(20) NOT NULL default '',
  `privacy` enum('strong','normal','low') NOT NULL default 'normal',
  `stylesheet` int(10) default '3',
  `info` text,
  `acceptpms` enum('yes','friends','no') NOT NULL default 'yes',
  `ip` varchar(15) NOT NULL default '',
  `class` tinyint(3) unsigned NOT NULL default '0',
  `override_class` tinyint(3) unsigned NOT NULL default '255',
  `support` enum('no','yes') NOT NULL default 'no',
  `supportfor` text,
  `avatar` varchar(100) NOT NULL default '',
  `icq` varchar(255) NOT NULL default '',
  `msn` varchar(255) NOT NULL default '',
  `aim` varchar(255) NOT NULL default '',
  `yahoo` varchar(255) NOT NULL default '',
  `skype` varchar(255) NOT NULL default '',
  `mirc` varchar(255) NOT NULL default '',
  `website` varchar(50) NOT NULL default '',
  `uploaded` bigint(20) unsigned NOT NULL default '0',
  `downloaded` bigint(20) unsigned NOT NULL default '0',
  `bonus` decimal(5,2) NOT NULL default '0.00',
  `title` varchar(30) NOT NULL default '',
  `country` int(10) unsigned NOT NULL default '0',
  `notifs` varchar(100) NOT NULL default '',
  `modcomment` text,
  `enabled` enum('yes','no') NOT NULL default 'yes',
  `parked` enum('yes','no') NOT NULL default 'no',
  `avatars` enum('yes','no') NOT NULL default 'yes',
  `donor` enum('yes','no') NOT NULL default 'no',
  `simpaty` int(10) unsigned NOT NULL default '0',
  `warned` enum('yes','no') NOT NULL default 'no',
  `warneduntil` datetime NOT NULL default '0000-00-00 00:00:00',
  `torrentsperpage` int(3) unsigned NOT NULL default '0',
  `topicsperpage` int(3) unsigned NOT NULL default '0',
  `postsperpage` int(3) unsigned NOT NULL default '0',
  `deletepms` enum('yes','no') NOT NULL default 'yes',
  `savepms` enum('yes','no') NOT NULL default 'no',
  `gender` enum('1','2','3') NOT NULL default '1',
  `birthday` date default '0000-00-00',
  `passkey` varchar(32) NOT NULL default '',
  `language` varchar(255) NOT NULL default 'russian',
  `invites` int(10) NOT NULL default '0',
  `invitedby` int(10) NOT NULL default '0',
  `invitedroot` int(10) NOT NULL default '0',
  `passkey_ip` varchar(15) NOT NULL default '',
  `comment_type` enum('all','forum','tracker') NOT NULL default 'all',
  `num_warned` int(2) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `status_added` (`status`,`added`),
  KEY `ip` (`ip`),
  KEY `uploaded` (`uploaded`),
  KEY `downloaded` (`downloaded`),
  KEY `country` (`country`),
  KEY `last_access` (`last_access`),
  KEY `enabled` (`enabled`),
  KEY `warned` (`warned`),
  KEY `user` (`id`,`status`,`enabled`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `users`
-- 
ALTER TABLE `users` ADD `dis_reason` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL AFTER `enabled` ;

ALTER TABLE `users` ADD FULLTEXT (
`dis_reason`
);
