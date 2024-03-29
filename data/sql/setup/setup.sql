CREATE TABLE `album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `body` text NULL,
  `public_flag` tinyint(2) NOT NULL DEFAULT '0',
  `cover_album_image_id` int(11) DEFAULT NULL,
  `foreign_table` varchar(20) NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_at_idx` (`created_at`),
  KEY `member_id_created_at_idx` (`member_id`,`created_at`),
  KEY `public_flag_craeted_at_idx` (`public_flag`,`created_at`),
  KEY `member_id_foreign_table_idx` (`member_id`,`foreign_table`),
  KEY `member_id_idx` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `album_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `album_id` int(11) NOT NULL,
  `file_id` varchar(255) NOT NULL,
  `name` text NULL,
  `public_flag` tinyint(2) NOT NULL DEFAULT '0',
  `shot_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `album_id_public_flag_created_at` (`album_id`,`public_flag`,`created_at`),
  KEY `album_id_public_flag_shot_at` (`album_id`,`public_flag`,`shot_at`),
  KEY `album_id_idx` (`album_id`),
  KEY `file_id_idx` (`file_id`),
  CONSTRAINT `album_image_album_id_album_id` FOREIGN KEY (`album_id`) REFERENCES `album` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `album_image_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `album_image_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `body` text NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `album_image_id_created_at` (`album_image_id`,`created_at`),
  KEY `album_image_id_idx` (`album_image_id`),
  CONSTRAINT `album_image_comment_album_image_id_album_image_id` FOREIGN KEY (`album_image_id`) REFERENCES `album_image` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `post_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `ua` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `file` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Serial number',
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'File name',
  `path` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'File path',
  `type` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Type of this file',
  `filesize` int(11) NOT NULL DEFAULT '0' COMMENT 'File size',
  `original_filename` text COLLATE utf8_unicode_ci COMMENT 'Original filename',
  `member_id` int(11) DEFAULT NULL,
  `exif` text NULL,
  `shot_at` datetime NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE_idx` (`name`),
  KEY `member_id_idx` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Saves informations of files uploaded';


CREATE TABLE `file_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Serial number',
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'File name',
  `path` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'File path',
  `type` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Type of this file',
  `filesize` int(11) NOT NULL DEFAULT '0' COMMENT 'File size',
  `original_filename` text COLLATE utf8_unicode_ci COMMENT 'Original filename',
  `member_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `exif` text DEFAULT NULL,
  `shot_at` datetime NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE_idx` (`name`),
  KEY `name_member_id_idx` (`name`,`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Saves informations of temporary files uploaded';


CREATE TABLE `file_tmp_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Serial number',
  `file_tmp_id` INT NOT NULL COMMENT 'file_tmp id',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT 'Configuration name',
  `value` text NOT NULL COMMENT 'Configuration value',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  INDEX file_tmp_id_idx (file_tmp_id),
  UNIQUE KEY `file_tmp_id_name_UNIQUE_idx` (`file_tmp_id`, `name`),
  CONSTRAINT `file_tmp_config_file_tmp_id_file_tmp_id` FOREIGN KEY (`file_tmp_id`) REFERENCES `file_tmp` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Saves configurations of each temporary files';


CREATE TABLE `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `last_login` datetime DEFAULT NULL,
  `login_hash` varchar(255) DEFAULT NULL,
  `file_id` varchar(255) DEFAULT NULL,
  `filesize_total` int(11) NOT NULL DEFAULT '0' COMMENT 'Total file size',
  `register_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: normal, 1:facebook, 2:twitter, 3:google',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `member_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id_UNIQUE_idx` (`member_id`),
  UNIQUE KEY `email_UNIQUE_idx` (`email`),
  CONSTRAINT `member_auth_member_id_member_id` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `member_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Serial number',
  `member_id` int(11) NOT NULL COMMENT 'Member id',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT 'Configuration name',
  `value` text NOT NULL COMMENT 'Configuration value',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id_idx` (`member_id`),
  UNIQUE KEY `member_id_name_UNIQUE_idx` (`member_id`, `name`),
  CONSTRAINT `member_config_member_id_member_id` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `member_email_pre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id_UNIQUE_idx` (`member_id`),
  UNIQUE KEY `token_UNIQUE_idx` (`token`),
  KEY `email_idx` (`email`),
  CONSTRAINT `member_email_pre_member_id_member_id` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `member_oauth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `oauth_provider_id` tinyint(2) NOT NULL,
  `uid` varchar(50) NOT NULL,
  `token` varchar(255) NOT NULL,
  `secret` varchar(255) NULL,
  `expires` int(11) NULL,
  `service_name` varchar(255) NULL,
  `service_url` varchar(255) NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `oauth_provider_id_uid_idx` (`oauth_provider_id`,`uid`),
  KEY `oauth_provider_id_uid_member_idx` (`oauth_provider_id`,`uid`,`member_id`),
  CONSTRAINT `member_oauth_member_id_member_id` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE CASCADE,
  CONSTRAINT `oauth_provider_id_oauth_provider_id` FOREIGN KEY (`oauth_provider_id`) REFERENCES `oauth_provider` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `oauth_provider` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE_idx` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `member_password_pre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id_UNIQUE_idx` (`member_id`),
  UNIQUE KEY `token_UNIQUE_idx` (`token`),
  CONSTRAINT `member_password_pre_member_id_member_id` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `member_pre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_UNIQUE_idx` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `member_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Serial number',
  `member_id` int(11) NOT NULL COMMENT 'Member id',
  `profile_id` int(11) NOT NULL COMMENT 'Profile id',
  `profile_option_id` int(11) DEFAULT NULL COMMENT 'Profile option id',
  `value` text NOT NULL COMMENT 'Text content for this profile item',
  `public_flag` tinyint(4) DEFAULT NULL COMMENT 'Public flag',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id_profile_id_UNIQUE_idx` (`member_id`,`profile_id`),
  KEY `member_id_idx` (`member_id`),
  KEY `profile_id_idx` (`profile_id`),
  KEY `profile_option_id_idx` (`profile_option_id`),
  CONSTRAINT `member_profile_member_id_member_id` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE CASCADE,
  CONSTRAINT `member_profile_profile_id_profile_id` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE,
  CONSTRAINT `member_profile_profile_option_id_profile_option_id` FOREIGN KEY (`profile_option_id`) REFERENCES `profile_option` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Saves informations of every member''''s profile';

CREATE TABLE `migration` (
  `name` varchar(50) NOT NULL,
  `type` varchar(25) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `migration` VALUES ('default','app',4);


CREATE TABLE `note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `body` text NOT NULL,
  `public_flag` tinyint(2) NOT NULL DEFAULT '0',
  `is_published` tinyint(2) NOT NULL DEFAULT '0',
  `published_at` datetime NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `published_at_idx` (`published_at`),
  KEY `member_id_is_published_published_at_public_flag_idx` (`member_id`,`is_published`,`published_at`,`public_flag`),
  KEY `member_id_created_at_idx` (`member_id`,`created_at`),
  KEY `public_flag_craeted_at_idx` (`public_flag`,`created_at`),
  KEY `is_published_published_at_public_flag_idx` (`is_published`,`published_at`,`public_flag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `note_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `note_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `body` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `note_id_created_at` (`note_id`,`created_at`),
  KEY `note_id_idx` (`note_id`),
  KEY `member_id_idx` (`member_id`),
  CONSTRAINT `note_comment_note_id_note_id` FOREIGN KEY (`note_id`) REFERENCES `note` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `note_album_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `note_id` int(11) NOT NULL,
  `album_image_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `note_id_idx` (`note_id`),
  CONSTRAINT `note_album_image_note_id_note_id` FOREIGN KEY (`note_id`) REFERENCES `note` (`id`) ON DELETE CASCADE,
  CONSTRAINT `note_album_image_album_image_id_album_image_id` FOREIGN KEY (`album_image_id`) REFERENCES `album_image` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `body` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Serial number',
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Identified profile name (ASCII)',
  `caption` text NOT NULL,
  `information` text NULL,
  `placeholder` text NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'This is a required',
  `is_unique` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Cannot select duplicate item',
  `is_edit_public_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Settable public flag',
  `default_public_flag` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Default of public flag',
  `is_disp_regist` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Shows when registeration',
  `is_disp_config` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Shows when edit',
  `is_disp_search` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Shows when searching',
  `form_type` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Form type to input/select',
  `value_type` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Type of input value',
  `value_regexp` text COLLATE utf8_unicode_ci COMMENT 'Regular expression',
  `value_min` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Minimum value',
  `value_max` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Maximum value',
  `sort_order` int(11) DEFAULT NULL COMMENT 'Order to sort',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE_idx` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Saves input/select items for the member profile';

CREATE TABLE `profile_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Serial number',
  `label` text NOT NULL COMMENT 'Choice',
  `profile_id` int(11) NOT NULL COMMENT 'Profile id',
  `sort_order` int(11) DEFAULT NULL COMMENT 'Order to sort',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `profile_id_idx` (`profile_id`),
  CONSTRAINT `profile_option_profile_id_profile_id` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Saves options of profile items';

CREATE TABLE `timeline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NULL,
  `member_id_to` int(11) NULL,
  `group_id` int(11) NULL,
  `page_id` int(11) NULL,
  `type` tinyint(2) NOT NULL DEFAULT '0',
  `body` text NULL,
  `foreign_table` varchar(20) NULL COMMENT 'Reference table name',
  `foreign_id` int(11) NULL COMMENT 'The id of reference table',
  `source` varchar(64) NULL COMMENT 'The source caption',
  `source_uri` text NULL COMMENT 'The source URI',
  `public_flag` tinyint(2) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `sort_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `timeline_member_id_member_id` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE CASCADE,
  KEY `foreign_table_foreign_id_type_created_at_idx` (`foreign_table`,`foreign_id`,`type`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `timeline_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timeline_id` int(11) NOT NULL,
  `member_id` int(11) NULL,
  `member_id_to` int(11) NULL,
  `group_id` int(11) NULL,
  `page_id` int(11) NULL,
  `is_follow` tinyint(1) NOT NULL DEFAULT '0',
  `public_flag` tinyint(2) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `sort_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `timeline_id_idx` (`timeline_id`),
  UNIQUE KEY `timeline_id_is_follow_UNIQUE_idx` (`timeline_id`,`is_follow`),
  KEY `public_flag_sort_datetime_idx` (`public_flag`,`sort_datetime`),
  CONSTRAINT `timeline_cache_timeline_id_timeline_id` FOREIGN KEY (`timeline_id`) REFERENCES `timeline` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `timeline_child_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timeline_id` int(11) NOT NULL,
  `foreign_table` varchar(20) NULL COMMENT 'Reference table name',
  `foreign_id` int(11) NULL COMMENT 'The id of reference table',
  PRIMARY KEY (`id`),
  KEY `timeline_id_idx` (`timeline_id`),
  KEY `foreign_table_foreign_id_timeline_id_idx` (`foreign_table`,`foreign_id`,`timeline_id`),
  CONSTRAINT `timeline_child_data_timeline_id_timeline_id` FOREIGN KEY (`timeline_id`) REFERENCES `timeline` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `timeline_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timeline_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `body` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `timeline_id_created_at` (`timeline_id`,`created_at`),
  CONSTRAINT `timeline_comment_timeline_id_timeline_id` FOREIGN KEY (`timeline_id`) REFERENCES `timeline` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `member_follow_timeline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `timeline_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id_timeline_id_UNIQUE_idx` (`member_id`,`timeline_id`),
  CONSTRAINT `member_follow_timeline_member_id_timeline_id` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE CASCADE,
  CONSTRAINT `member_follow_timeline_timeline_id_timeline_id` FOREIGN KEY (`timeline_id`) REFERENCES `timeline` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `group` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `last_login` int(11) NOT NULL,
  `login_hash` varchar(255) NOT NULL,
  `profile_fields` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `users` VALUES (1,'admin','RTRQWLQkWXL5w3v651COdjbiK6j5/Trs1El8UYDL5q8=',100,'admin@example.jp','','','a:0:{}','0000-00-00 00:00:00','0000-00-00 00:00:00');
