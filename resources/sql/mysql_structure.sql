SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `adminID` int(11) NOT NULL AUTO_INCREMENT,
  `admingroupsID` int(11) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `username` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `pwd` varchar(128) DEFAULT NULL,
  `misc_data` varchar(255) DEFAULT NULL,
  `table_access` varchar(64) DEFAULT NULL,
  `row_access` varchar(64) DEFAULT NULL,
  `lastUpdated` timestamp DEFAULT CURRENT_TIMESTAMP,
  `firstCreated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`adminID`),
  UNIQUE KEY `Email` (`email`) USING BTREE,
  UNIQUE KEY `username` (`username`) USING BTREE,
  KEY `admin_ibfk_1` (`admingroupsID`) USING BTREE,
  CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`admingroupsID`) REFERENCES `admingroups` (`admingroupsID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for admin_notifications
-- ----------------------------
DROP TABLE IF EXISTS `admin_notifications`;
CREATE TABLE `admin_notifications` (
  `adminID` int(11) NOT NULL,
  `notificationsID` int(11) NOT NULL,
  `lastUpdated` timestamp DEFAULT CURRENT_TIMESTAMP,
  `firstCreated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`adminID`,`notificationsID`),
  KEY `adminID` (`adminID`) USING BTREE,
  KEY `notificationsID` (`notificationsID`) USING BTREE,
  CONSTRAINT `admin_notifications_ibfk_1` FOREIGN KEY (`notificationsID`) REFERENCES `notifications` (`notificationsID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `admin_notifications_ibfk_2` FOREIGN KEY (`adminID`) REFERENCES `admin` (`adminID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for admingroups
-- ----------------------------
DROP TABLE IF EXISTS `admingroups`;
CREATE TABLE `admingroups` (
  `admingroupsID` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(32) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `privilege_level` int(11) DEFAULT NULL,
  PRIMARY KEY (`admingroupsID`),
  UNIQUE KEY `Alias` (`alias`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for articlecategories
-- ----------------------------
DROP TABLE IF EXISTS `articlecategories`;
CREATE TABLE `articlecategories` (
  `articlecategoriesID` int(11) NOT NULL AUTO_INCREMENT,
  `l` int(11) DEFAULT NULL,
  `r` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `Alias` varchar(64) NOT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `customSort` int(11) DEFAULT NULL,
  `lastUpdated` timestamp DEFAULT CURRENT_TIMESTAMP,
  `firstCreated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`articlecategoriesID`),
  UNIQUE KEY `Alias` (`Alias`) USING BTREE,
  KEY `l` (`l`) USING BTREE,
  KEY `r` (`r`) USING BTREE,
  KEY `level` (`level`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for articles
-- ----------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `articlesID` int(11) NOT NULL AUTO_INCREMENT,
  `Alias` varchar(255) DEFAULT NULL,
  `articlecategoriesID` int(11) DEFAULT NULL,
  `Headline` varchar(255) DEFAULT NULL,
  `Subline` varchar(255) DEFAULT NULL,
  `Teaser` varchar(500) DEFAULT NULL,
  `Content` text,
  `Article_Date` date DEFAULT NULL,
  `Display_from` date DEFAULT NULL,
  `Display_until` date DEFAULT NULL,
  `published` tinyint(255) DEFAULT NULL,
  `customFlags` bigint(20) DEFAULT NULL,
  `customSort` int(11) DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `publishedBy` int(255) DEFAULT NULL,
  `lastUpdated` timestamp DEFAULT CURRENT_TIMESTAMP,
  `firstCreated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`articlesID`),
  UNIQUE KEY `Alias` (`Alias`) USING BTREE,
  KEY `articlecategoriesID` (`articlecategoriesID`) USING BTREE,
  KEY `updatedBy` (`updatedBy`) USING BTREE,
  KEY `createdBy` (`createdBy`) USING BTREE,
  KEY `publishedBy` (`publishedBy`),
  CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`articlecategoriesID`) REFERENCES `articlecategories` (`articlecategoriesID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`updatedBy`) REFERENCES `admin` (`adminID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `articles_ibfk_3` FOREIGN KEY (`createdBy`) REFERENCES `admin` (`adminID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `articles_ibfk_4` FOREIGN KEY (`publishedBy`) REFERENCES `admin` (`adminID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for articles_files
-- ----------------------------
DROP TABLE IF EXISTS `articles_files`;
CREATE TABLE `articles_files` (
  `articlesID` int(11) NOT NULL,
  `filesID` int(11) NOT NULL,
  `customSort` int(11) DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `lastUpdated` timestamp DEFAULT CURRENT_TIMESTAMP,
  `firstCreated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`articlesID`,`filesID`),
  KEY `filesID` (`filesID`),
  KEY `updatedBy` (`updatedBy`),
  KEY `createdBy` (`createdBy`),
  CONSTRAINT `articles_files_ibfk_1` FOREIGN KEY (`articlesID`) REFERENCES `articles` (`articlesID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `articles_files_ibfk_2` FOREIGN KEY (`filesID`) REFERENCES `files` (`filesID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `articles_files_ibfk_3` FOREIGN KEY (`updatedBy`) REFERENCES `admin` (`adminID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `articles_files_ibfk_4` FOREIGN KEY (`createdBy`) REFERENCES `admin` (`adminID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for files
-- ----------------------------
DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `filesID` int(11) NOT NULL AUTO_INCREMENT,
  `foldersID` int(11) DEFAULT NULL,
  `Title` varchar(128) DEFAULT NULL,
  `Subtitle` varchar(255) DEFAULT NULL,
  `Description` mediumtext,
  `File` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Mimetype` varchar(128) DEFAULT NULL,
  `Checksum` varchar(64) DEFAULT NULL,
  `Obscured_Filename` varchar(64) DEFAULT NULL,
  `customSort` int(11) DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `lastUpdated` timestamp DEFAULT CURRENT_TIMESTAMP,
  `firstCreated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`filesID`),
  UNIQUE KEY `foldersID` (`foldersID`,`File`),
  KEY `fk_download_categories` (`foldersID`) USING BTREE,
  CONSTRAINT `files_ibfk_1` FOREIGN KEY (`foldersID`) REFERENCES `folders` (`foldersID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for folders
-- ----------------------------
DROP TABLE IF EXISTS `folders`;
CREATE TABLE `folders` (
  `foldersID` int(11) NOT NULL AUTO_INCREMENT,
  `l` int(11) DEFAULT NULL,
  `r` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `Static` tinyint(4) DEFAULT NULL,
  `Alias` varchar(255) DEFAULT NULL,
  `Title` varchar(128) DEFAULT NULL,
  `Description` mediumtext,
  `Path` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Access` enum('RW','R') NOT NULL DEFAULT 'R',
  `Obscure_Files` tinyint(4) DEFAULT NULL,
  `customSort` int(11) DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `lastUpdated` timestamp DEFAULT CURRENT_TIMESTAMP,
  `firstCreated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`foldersID`),
  UNIQUE KEY `Alias` (`Alias`) USING BTREE,
  KEY `level` (`level`) USING BTREE,
  KEY `l` (`l`) USING BTREE,
  KEY `r` (`r`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `notificationsID` int(11) NOT NULL AUTO_INCREMENT,
  `admingroupsID` int(11) DEFAULT NULL,
  `Alias` varchar(32) NOT NULL DEFAULT '',
  `Not_Displayed` tinyint(4) DEFAULT NULL,
  `Description` varchar(64) DEFAULT NULL,
  `Subject` varchar(255) NOT NULL DEFAULT '',
  `Message` text,
  `Signature` varchar(255) DEFAULT NULL,
  `Attachment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`notificationsID`),
  UNIQUE KEY `Code` (`Alias`) USING BTREE,
  KEY `notifications_ibfk_1` (`admingroupsID`) USING BTREE,
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`admingroupsID`) REFERENCES `admingroups` (`admingroupsID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pages
-- ----------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `pagesID` int(11) NOT NULL AUTO_INCREMENT,
  `Alias` varchar(32) DEFAULT NULL,
  `Title` varchar(128) DEFAULT NULL,
  `Keywords` varchar(512) DEFAULT NULL,
  `Template` varchar(32) DEFAULT NULL,
  `Locked` tinyint(4) DEFAULT NULL,
  `No_Nice_Edit` tinyint(4) DEFAULT NULL,
  `lastUpdated` timestamp DEFAULT CURRENT_TIMESTAMP,
  `firstCreated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pagesID`),
  UNIQUE KEY `Alias` (`Alias`) USING BTREE,
  UNIQUE KEY `Template` (`Template`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for revisions
-- ----------------------------
DROP TABLE IF EXISTS `revisions`;
CREATE TABLE `revisions` (
  `revisionsID` int(11) NOT NULL AUTO_INCREMENT,
  `authorID` int(11) DEFAULT NULL,
  `pagesID` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `Title` varchar(128) DEFAULT NULL,
  `Keywords` varchar(512) DEFAULT NULL,
  `Description` varchar(512) DEFAULT NULL,
  `Markup` text,
  `Rawtext` text,
  `Locale` varchar(2) DEFAULT NULL,
  `templateUpdated` datetime DEFAULT NULL,
  `lastUpdated` timestamp DEFAULT CURRENT_TIMESTAMP,
  `firstCreated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`revisionsID`),
  UNIQUE KEY `pagesID` (`pagesID`,`active`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bruteforce_attempts
-- ----------------------------
DROP TABLE IF EXISTS `bruteforce_attempts`;
CREATE TABLE `bruteforce_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) DEFAULT NULL,
  `action` varchar(32) DEFAULT NULL,
  `when` int(11) DEFAULT NULL,
  `data` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS=1;
