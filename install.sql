CREATE TABLE IF NOT EXISTS `default_directories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
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
  `althtml` mediumtext COLLATE utf8_bin,
  `metatitle` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `metadescription` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `metakeywords` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `directory` tinyint(1) NOT NULL DEFAULT '1',
  `live` tinyint(1) NOT NULL DEFAULT '0',
  `sidebar` tinyint(1) NOT NULL DEFAULT '0',
  `js` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `filename` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `ext` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `permission` varchar(50) COLLATE utf8_bin DEFAULT 'none',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


INSERT INTO `default_views` (`id`, `pageheading`, `pagesubheading`, `navheading`, `url`, `html`, `althtml`, `metatitle`, `metadescription`, `metakeywords`, `directory`, `live`, `sidebar`, `js`, `filename`, `ext`, `permission`) VALUES
	(1, 'Error', 'Page Not Found', 'Error', 'error', '', NULL, 'Error', '', '', 1, 1, 0, '', '', '', 'none'),
	(2, 'Lectric', 'Lectric', 'Lectric', 'index', '<h1>Welcome to Lectric</h1>\r\n<h4>Congrats, dude! If you\'re seeing this, it means the installation was <em>almost</em> a success.</h4>', '', 'Lectric Framework', 'Homepage of the Lectric Framework default view directory', '', 1, 1, 0, 'js_home.php', 'favicon.png', '', 'none');

	CREATE TABLE IF NOT EXISTS `lec-admin_directories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `live` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


INSERT INTO `lec-admin_directories` (`id`, `name`, `live`) VALUES
	(1, 'root', 1);

	CREATE TABLE IF NOT EXISTS `lec-admin_objects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `short_desc` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `icon` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `tab` tinyint(4) DEFAULT NULL,
  `sort_order` tinyint(4) DEFAULT '0',
  `permission` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `s_word` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `table` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `table_fields` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `deletion_tables` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `deletions` TINYINT(4) NULL DEFAULT NULL,
  `duplications` TINYINT(4) NULL DEFAULT NULL,
  `add_new` TINYINT(4) NULL DEFAULT NULL,
  `nodelete` varchar(250) COLLATE utf8_bin NOT NULL DEFAULT '[]',
  `edit_fields` text COLLATE utf8_bin DEFAULT NULL,
  `search` tinyint(4) DEFAULT NULL,
  `search_inj` varchar(1000) COLLATE utf8_bin DEFAULT NULL,
  `img_fields` varchar(250) COLLATE utf8_bin NOT NULL DEFAULT '[]',
  `img_directory` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  `thumb_directory` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  `extra_functions` varchar(1000) COLLATE utf8_bin DEFAULT NULL,
  `include_file_after` VARCHAR(1000) NULL DEFAULT NULL,
  `include_file_before` VARCHAR(1000) NULL DEFAULT NULL,
  `include_file_list` VARCHAR(1000) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


INSERT INTO `lec-admin_objects` (`id`, `name`, `short_desc`, `icon`, `tab`, `permission`, `s_word`, `table`, `table_fields`, `deletion_tables`, `nodelete`, `edit_fields`, `search`, `search_inj`, `img_fields`, `img_directory`, `thumb_directory`, `extra_functions`) VALUES
	(1, 'Permissions', 'Add, Edit or Delete Permissions', 'fa-lock', 2, '1', 'Permission', 'lec-admin_user_permission_types', '`identifier`,`description`', '[{"table":"lec-admin_user_permissions","field":"permission"}]', '[]', '[\r\n	{\r\n		"name":"Identifier",\r\n		"field":"identifier",\r\n		"placeholder":"",\r\n		"form_type":"text",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"AN_NO_SPACES",\r\n		"mandatory":"yes",\r\n		"edit_type":"text",\r\n		"class_inj":"",\r\n		"help_text":""\r\n	},\r\n	{\r\n		"name":"Description",\r\n		"field":"description",\r\n		"placeholder":"",\r\n		"form_type":"text",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"no",\r\n		"edit_type":"text",\r\n		"class_inj":"",\r\n		"help_text":""\r\n	}\r\n]', 0, '', '', '', '', ''),
	(8, 'Webpages', 'Add, Edit or Delete Webpages', 'fa-file', 1, '1', 'Webpage', 'default_views', '`pageheading`,`url`', '', '[]', '[\r\n	{\r\n		"name":"Page Heading",\r\n		"field":"pageheading",\r\n		"placeholder":"Page Heading",\r\n		"form_type":"text",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"yes",\r\n		"edit_type":"text",\r\n		"class_inj":"",\r\n		"help_text":""\r\n	},\r\n	{\r\n		"name":"Page Sub Heading",\r\n		"field":"pagesubheading",\r\n		"placeholder":"Page Sub Heading",\r\n		"form_type":"text",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"",\r\n		"edit_type":"text",\r\n		"class_inj":"",\r\n		"help_text":""\r\n	},\r\n	{\r\n		"name":" Navigation Heading",\r\n		"field":"navheading",\r\n		"placeholder":"Navigation Heading",\r\n		"form_type":"text",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"",\r\n		"edit_type":"text",\r\n		"class_inj":"",\r\n		"help_text":""\r\n	},\r\n	{\r\n		"name":"Page URL",\r\n		"field":"url",\r\n		"placeholder":"page-url",\r\n		"form_type":"text",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"yes",\r\n		"edit_type":"text",\r\n		"class_inj":"",\r\n		"help_text":""\r\n	},\r\n	{\r\n		"name":"HTML",\r\n		"field":"html",\r\n		"placeholder":"",\r\n		"form_type":"textarea",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"",\r\n		"edit_type":"html",\r\n		"class_inj":"editor",\r\n		"help_text":""\r\n	},\r\n	{\r\n		"name":"Alternate HTML",\r\n		"field":"althtml",\r\n		"placeholder":"",\r\n		"form_type":"textarea",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"",\r\n		"edit_type":"html",\r\n		"class_inj":"editor",\r\n		"help_text":""\r\n	},\r\n	{\r\n		"name":"Meta Title",\r\n		"field":"metatitle",\r\n		"placeholder":"Meta Title",\r\n		"form_type":"text",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"",\r\n		"edit_type":"text",\r\n		"class_inj":"",\r\n		"help_text":""\r\n	},\r\n	{\r\n		"name":"Meta Description",\r\n		"field":"metadescription",\r\n		"placeholder":"Meta Description",\r\n		"form_type":"text",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"",\r\n		"edit_type":"text",\r\n		"class_inj":"",\r\n		"help_text":""\r\n	},\r\n	{\r\n		"name":"Meta Keywords",\r\n		"field":"metakeywords",\r\n		"placeholder":"Meta Keywords",\r\n		"form_type":"text",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"",\r\n		"edit_type":"text",\r\n		"class_inj":"tags",\r\n		"help_text":""\r\n	},\r\n	{\r\n		"name":"Directory",\r\n		"field":"directory",\r\n		"placeholder":"",\r\n		"form_type":"select",\r\n		"select_table":"default_directories",\r\n		"select_field":"name",\r\n		"validation":"",\r\n		"mandatory":"",\r\n		"edit_type":"text",\r\n		"class_inj":"",\r\n		"help_text":""\r\n	},\r\n	{\r\n		"name":"Live?",\r\n		"field":"live",\r\n		"placeholder":"",\r\n		"form_type":"select_yesno",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"",\r\n		"edit_type":"text",\r\n		"class_inj":"",\r\n		"help_text":""\r\n	},\r\n	{\r\n		"name":"Image",\r\n		"field":"filename",\r\n		"placeholder":"",\r\n		"form_type":"image",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"",\r\n		"edit_type":"image",\r\n		"class_inj":"",\r\n		"help_text":""\r\n	}\r\n]', 0, '', '["filename"]', '/view/default/img/', '/view/default/img/thumbs/', ''),
	(9, 'Directories', 'Add, Edit or Delete Directories', 'fa-folder', 1, '1', 'Directory', 'default_directories', '`name`,`live`', '', '[1]', '[\r\n	{\r\n		"name":"Name",\r\n		"field":"name",\r\n		"placeholder":"Directory Name",\r\n		"form_type":"text",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"yes",\r\n		"edit_type":"text",\r\n		"class_inj":"",\r\n		"help_text":""\r\n	},\r\n	{\r\n		"name":"Live?",\r\n		"field":"live",\r\n		"placeholder":"",\r\n		"form_type":"select_yesno",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"yes",\r\n		"edit_type":"text",\r\n		"class_inj":"",\r\n		"help_text":""\r\n	}\r\n]', 0, '', '', '', '', '');

INSERT INTO `lec-admin_objects` (`name`, `short_desc`, `icon`, `tab`, `permission`, `s_word`, `table`, `table_fields`, `deletion_tables`, `nodelete`, `edit_fields`, `search`, `search_inj`, `img_fields`, `img_directory`, `thumb_directory`, `extra_functions`) VALUES ('Admin Users', 'Add, Edit or Delete Admin Users', 'fa-user', 2, '1', 'User', 'lec-admin_users', '`name`', NULL, '[]', '[\r\n	{\r\n		"name":"Name",\r\n		"field":"name",\r\n		"placeholder":"Name",\r\n		"form_type":"text",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"yes",\r\n		"edit_type":"text",\r\n		"class_inj":"filter_name",\r\n		"help_text":""\r\n	},\r\n	{\r\n		"name":"Username (Email)",\r\n		"field":"username",\r\n		"placeholder":"Email Address",\r\n		"form_type":"text",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"yes",\r\n		"edit_type":"text",\r\n		"class_inj":"",\r\n		"help_text":""\r\n	},\r\n	{\r\n		"name":"Password",\r\n		"field":"password",\r\n		"placeholder":"Password",\r\n		"form_type":"password",\r\n		"select_table":"",\r\n		"select_field":"",\r\n		"validation":"",\r\n		"mandatory":"no",\r\n		"edit_type":"password",\r\n		"class_inj":"",\r\n		"help_text":""\r\n	}\r\n]', 0, NULL, '[]', NULL, NULL, "admin_permissions.phgp");


	CREATE TABLE IF NOT EXISTS `lec-admin_tabs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sortorder` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


INSERT INTO `lec-admin_tabs` (`id`, `sortorder`, `name`) VALUES
	(1, 1, '<i class="fa fa-file-text fa-fw"></i>  Content'),
	(2, 1, '<i class="fa fa-lock fa-fw"></i>  Administration');

	CREATE TABLE IF NOT EXISTS `lec-admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `username` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `salt` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `unique` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `unique_count` TINYINT NULL DEFAULT '0',
  `folders` varchar(1000) COLLATE utf8_bin DEFAULT NULL,
  `last_logged_in` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `read_only` int(1) unsigned NOT NULL DEFAULT '0',
  `admin` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


INSERT INTO `lec-admin_users` (`id`, `name`, `username`, `password`, `salt`, `unique`, `folders`, `last_logged_in`, `created`, `read_only`, `admin`) VALUES
	(1, 'Admin', 'admin@login.com', '$2y$10$UzowCV55vpBrICMO3rNy6eMd7OkGvd3AmRX6Pf1qPAXm2HxSDpAUW', 'e66f114119', '$2y$10$dGuhj0Ab8Fmx9FZ4K.ZCSuCf2MKGbOV9abJlisza2TJIJJbTiNws2', '', '2017-08-11 17:33:31', '0000-00-00 00:00:00', 0, 1);

	CREATE TABLE IF NOT EXISTS `lec-admin_user_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned NOT NULL DEFAULT '0',
  `permission` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


INSERT INTO `lec-admin_user_permissions` (`id`, `user`, `permission`) VALUES
	(1, 1, 1);

	CREATE TABLE IF NOT EXISTS `lec-admin_user_permission_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `description` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`identifier`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


INSERT INTO `lec-admin_user_permission_types` (`id`, `identifier`, `description`) VALUES
	(1, 'access_admin', '');

CREATE TABLE IF NOT EXISTS `lec-admin_views` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pageheading` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `pagesubheading` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `navheading` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `url` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `html` mediumtext COLLATE utf8_bin,
  `althtml` mediumtext COLLATE utf8_bin,
  `metatitle` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `metadescription` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `metakeywords` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `directory` tinyint(1) NOT NULL DEFAULT '1',
  `live` tinyint(1) NOT NULL DEFAULT '0',
  `sidebar` tinyint(1) NOT NULL DEFAULT '0',
  `js` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `filename` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `ext` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `permission` varchar(50) COLLATE utf8_bin DEFAULT 'none',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


INSERT INTO `lec-admin_views` (`id`, `pageheading`, `pagesubheading`, `navheading`, `url`, `html`, `althtml`, `metatitle`, `metadescription`, `metakeywords`, `directory`, `live`, `sidebar`, `js`, `filename`, `ext`, `permission`) VALUES
	(1, 'Error', 'Page Not Found', 'Error', 'error', '', NULL, 'Error', '', '', 1, 1, 0, '', '', '', 'none'),
	(2, 'Dashboard', 'Dashboard', 'Dashboard', 'index', '', NULL, 'Administration Dashboard', 'Dashboard for the Lectric Admin Interface', '', 1, 1, 0, '', '', '', 'access_admin'),
	(3, 'Lectric Admin Login', 'Lectric Admin Login', 'Lectric Admin Login', 'login', NULL, NULL, 'Lectric Admin Login', 'Login to Administration', NULL, 1, 1, 0, '', '', '', 'none'),
	(4, 'Object', 'Object', 'Object', 'object', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 'js_objects.php', '', '', 'access_admin');
