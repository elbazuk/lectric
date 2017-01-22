/* Run the queries below to install the default tables (x2) */

CREATE TABLE IF NOT EXISTS `default_directories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `live` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



INSERT INTO `default_directories` (`id`, `name`, `live`) VALUES
	(1, 'root', 1);



CREATE TABLE IF NOT EXISTS `default_views` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pageheading` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `pagesubheading` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `navheading` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `url` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `html` mediumtext COLLATE utf8_bin,
  `metatitle` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `metadescription` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `metakeywords` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `directory` tinyint(1) NOT NULL DEFAULT '1',
  `live` tinyint(1) NOT NULL DEFAULT '0',
  `sidebar` tinyint(1) NOT NULL DEFAULT '0',
  `js` varchar(255) COLLATE utf8_bin NOT NULL,
  `filename` varchar(250) COLLATE utf8_bin NOT NULL,
  `ext` varchar(50) COLLATE utf8_bin NOT NULL,
  `permission` varchar(50) COLLATE utf8_bin DEFAULT 'none',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


INSERT INTO `default_views` (`id`, `pageheading`, `pagesubheading`, `navheading`, `url`, `html`, `metatitle`, `metadescription`, `metakeywords`, `directory`, `live`, `sidebar`, `js`, `filename`, `ext`, `permission`) VALUES
	(1, 'Error', 'Page Not Found', 'Error', 'error', '', 'Error', '', '', 1, 1, 0, '', '', '', 'none'),
	(2, 'Lectric', 'Lectric', 'Lectric', 'index', '<h1>Welcome to Lectric</h1>\r\n<h4>Congrats, dude! If you\'re seeing this, it means the installation was <em>almost</em> a success.</h4> ', 'Lectric Framework', 'Homepage of the Lectric Framework default view directory', '', 1, 1, 0, 'js_home.php', '', '', 'none');
