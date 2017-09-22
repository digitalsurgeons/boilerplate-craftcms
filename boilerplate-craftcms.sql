-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Sep 22, 2017 at 12:27 PM
-- Server version: 5.6.25
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boilerplate-craftcms`
--

-- --------------------------------------------------------

--
-- Table structure for table `craft_assetfiles`
--

DROP TABLE IF EXISTS `craft_assetfiles`;
CREATE TABLE `craft_assetfiles` (
  `id` int(11) NOT NULL,
  `sourceId` int(11) DEFAULT NULL,
  `folderId` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `kind` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unknown',
  `width` int(11) unsigned DEFAULT NULL,
  `height` int(11) unsigned DEFAULT NULL,
  `size` bigint(20) unsigned DEFAULT NULL,
  `dateModified` datetime DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_assetfiles`
--

INSERT INTO `craft_assetfiles` (`id`, `sourceId`, `folderId`, `filename`, `kind`, `width`, `height`, `size`, `dateModified`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(7, 1, 1, 'space.jpg', 'image', 2000, 1250, 696632, '2017-09-22 16:17:28', '2017-09-22 16:17:28', '2017-09-22 16:17:28', '7fa14c5e-ff69-40b7-a77e-dadc952bc66d');

-- --------------------------------------------------------

--
-- Table structure for table `craft_assetfolders`
--

DROP TABLE IF EXISTS `craft_assetfolders`;
CREATE TABLE `craft_assetfolders` (
  `id` int(11) NOT NULL,
  `parentId` int(11) DEFAULT NULL,
  `sourceId` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_assetfolders`
--

INSERT INTO `craft_assetfolders` (`id`, `parentId`, `sourceId`, `name`, `path`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, NULL, 1, 'Mastheads', '', '2017-09-22 16:14:18', '2017-09-22 16:14:18', '11683e94-147a-4722-b5a4-2ba4a2d0550d'),
(2, NULL, 2, 'Videos', '', '2017-09-22 16:14:43', '2017-09-22 16:14:43', '05933a73-ab53-4b4f-8c6c-00a771a11599');

-- --------------------------------------------------------

--
-- Table structure for table `craft_assetindexdata`
--

DROP TABLE IF EXISTS `craft_assetindexdata`;
CREATE TABLE `craft_assetindexdata` (
  `id` int(11) NOT NULL,
  `sessionId` varchar(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sourceId` int(10) NOT NULL,
  `offset` int(10) NOT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recordId` int(10) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_assetsources`
--

DROP TABLE IF EXISTS `craft_assetsources`;
CREATE TABLE `craft_assetsources` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `settings` text COLLATE utf8_unicode_ci,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `fieldLayoutId` int(10) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_assetsources`
--

INSERT INTO `craft_assetsources` (`id`, `name`, `handle`, `type`, `settings`, `sortOrder`, `fieldLayoutId`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 'Mastheads', 'mastheads', 'Local', '{"path":"{basePath}uploads\\/mastheads\\/","publicURLs":"1","url":"{baseUrl}uploads\\/mastheads\\/"}', 1, 16, '2017-09-22 16:14:18', '2017-09-22 16:15:20', '42be5c2d-0078-43d7-993d-07d9fab5588f'),
(2, 'Videos', 'videos', 'Local', '{"path":"{basePath}uploads\\/videos\\/","publicURLs":"1","url":"{baseUrl}uploads\\/videos\\/"}', 2, 15, '2017-09-22 16:14:43', '2017-09-22 16:15:09', '6c4c6434-8af0-4988-bfed-daf9c5e56dbf');

-- --------------------------------------------------------

--
-- Table structure for table `craft_assettransformindex`
--

DROP TABLE IF EXISTS `craft_assettransformindex`;
CREATE TABLE `craft_assettransformindex` (
  `id` int(11) NOT NULL,
  `fileId` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `format` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sourceId` int(11) DEFAULT NULL,
  `fileExists` tinyint(1) DEFAULT NULL,
  `inProgress` tinyint(1) DEFAULT NULL,
  `dateIndexed` datetime DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_assettransforms`
--

DROP TABLE IF EXISTS `craft_assettransforms`;
CREATE TABLE `craft_assettransforms` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mode` enum('stretch','fit','crop') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'crop',
  `position` enum('top-left','top-center','top-right','center-left','center-center','center-right','bottom-left','bottom-center','bottom-right') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'center-center',
  `height` int(10) DEFAULT NULL,
  `width` int(10) DEFAULT NULL,
  `format` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quality` int(10) DEFAULT NULL,
  `dimensionChangeTime` datetime DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_categories`
--

DROP TABLE IF EXISTS `craft_categories`;
CREATE TABLE `craft_categories` (
  `id` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_categorygroups`
--

DROP TABLE IF EXISTS `craft_categorygroups`;
CREATE TABLE `craft_categorygroups` (
  `id` int(11) NOT NULL,
  `structureId` int(11) NOT NULL,
  `fieldLayoutId` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hasUrls` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `template` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_categorygroups_i18n`
--

DROP TABLE IF EXISTS `craft_categorygroups_i18n`;
CREATE TABLE `craft_categorygroups_i18n` (
  `id` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `urlFormat` text COLLATE utf8_unicode_ci,
  `nestedUrlFormat` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_content`
--

DROP TABLE IF EXISTS `craft_content`;
CREATE TABLE `craft_content` (
  `id` int(11) NOT NULL,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_body` text COLLATE utf8_unicode_ci,
  `field_metaData` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_content`
--

INSERT INTO `craft_content` (`id`, `elementId`, `locale`, `title`, `field_body`, `field_metaData`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 1, 'en_us', NULL, NULL, NULL, '2017-09-22 15:46:50', '2017-09-22 15:46:50', 'bb8e4b3c-ad9a-48e2-9e48-23af34b527e3'),
(4, 4, 'en_us', 'Home', NULL, '{"id":null,"enabled":1,"archived":0,"locale":"en_us","localeEnabled":1,"slug":null,"uri":null,"dateCreated":null,"dateUpdated":null,"root":null,"lft":null,"rgt":null,"level":null,"searchScore":null,"elementId":0,"metaType":"template","metaPath":"","seoMainEntityCategory":"CreativeWork","seoMainEntityOfPage":"WebPage","seoTitle":"Home","seoDescription":"","seoKeywords":"","seoImageTransform":"","seoFacebookImageTransform":"","seoTwitterImageTransform":"","twitterCardType":"","openGraphType":"","robots":"","seoImageId":"","seoTwitterImageId":"","seoFacebookImageId":"","canonicalUrlOverride":"","seoTitleUnparsed":"Home","seoDescriptionUnparsed":"","seoKeywordsUnparsed":"","seoTitleSource":"field","seoTitleSourceField":"title","seoDescriptionSource":"custom","seoDescriptionSourceField":"title","seoKeywordsSource":"custom","seoKeywordsSourceField":"title","seoImageIdSource":"custom","seoImageIdSourceField":"","seoTwitterImageIdSource":"custom","seoTwitterImageIdSourceField":"","seoFacebookImageIdSource":"custom","seoFacebookImageIdSourceField":"","seoCommerceVariants":null,"__model__":"Craft\\\\Seomatic_MetaFieldModel"}', '2017-09-22 16:05:49', '2017-09-22 16:17:37', 'ab5b3e1a-2a59-4daa-87e5-980fa6701bd3'),
(5, 7, 'en_us', 'Space', NULL, NULL, '2017-09-22 16:17:28', '2017-09-22 16:17:28', 'f0193785-cd8a-450e-9da9-3192a454a93f');

-- --------------------------------------------------------

--
-- Table structure for table `craft_deprecationerrors`
--

DROP TABLE IF EXISTS `craft_deprecationerrors`;
CREATE TABLE `craft_deprecationerrors` (
  `id` int(11) NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fingerprint` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastOccurrence` datetime NOT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `line` smallint(6) unsigned NOT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `templateLine` smallint(6) unsigned DEFAULT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `traces` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_elementindexsettings`
--

DROP TABLE IF EXISTS `craft_elementindexsettings`;
CREATE TABLE `craft_elementindexsettings` (
  `id` int(11) NOT NULL,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `settings` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_elements`
--

DROP TABLE IF EXISTS `craft_elements`;
CREATE TABLE `craft_elements` (
  `id` int(11) NOT NULL,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `archived` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_elements`
--

INSERT INTO `craft_elements` (`id`, `type`, `enabled`, `archived`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 'User', 1, 0, '2017-09-22 15:46:50', '2017-09-22 15:46:50', '6f1cba51-beb4-4b32-be69-29b8fafcac40'),
(4, 'Entry', 1, 0, '2017-09-22 16:05:49', '2017-09-22 16:17:37', '24a4cf2e-d997-4ee4-bb72-86733c9da918'),
(5, 'MatrixBlock', 1, 0, '2017-09-22 16:05:50', '2017-09-22 16:17:37', '02fe395f-ece0-47b0-b912-8686d256e2fe'),
(6, 'SuperTable_Block', 1, 0, '2017-09-22 16:05:50', '2017-09-22 16:17:37', 'b750981b-ff95-4712-bf7f-cb703bec0039'),
(7, 'Asset', 1, 0, '2017-09-22 16:17:28', '2017-09-22 16:17:28', '7b4f9b09-0438-4652-b0bf-e550b7060c0f');

-- --------------------------------------------------------

--
-- Table structure for table `craft_elements_i18n`
--

DROP TABLE IF EXISTS `craft_elements_i18n`;
CREATE TABLE `craft_elements_i18n` (
  `id` int(11) NOT NULL,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_elements_i18n`
--

INSERT INTO `craft_elements_i18n` (`id`, `elementId`, `locale`, `slug`, `uri`, `enabled`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 1, 'en_us', '', NULL, 1, '2017-09-22 15:46:50', '2017-09-22 15:46:50', '6ca8b9d9-4c45-43a1-8e7a-1bb04c0dfdb7'),
(4, 4, 'en_us', '__home__', '__home__', 1, '2017-09-22 16:05:50', '2017-09-22 16:17:37', '1e033b46-b474-4558-b546-4115233deb1b'),
(5, 5, 'en_us', '', NULL, 1, '2017-09-22 16:05:50', '2017-09-22 16:17:37', '85b0b944-e92e-40dd-b464-e20fcc6ad1fc'),
(6, 6, 'en_us', '', NULL, 1, '2017-09-22 16:05:50', '2017-09-22 16:17:37', '9d81ee79-d8b3-4c1d-99e6-6466231030d0'),
(7, 7, 'en_us', 'space', NULL, 1, '2017-09-22 16:17:28', '2017-09-22 16:17:28', 'd52fc2de-66b6-46fb-9421-1cbee32c0698');

-- --------------------------------------------------------

--
-- Table structure for table `craft_emailmessages`
--

DROP TABLE IF EXISTS `craft_emailmessages`;
CREATE TABLE `craft_emailmessages` (
  `id` int(11) NOT NULL,
  `key` char(150) COLLATE utf8_unicode_ci NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_entries`
--

DROP TABLE IF EXISTS `craft_entries`;
CREATE TABLE `craft_entries` (
  `id` int(11) NOT NULL,
  `sectionId` int(11) NOT NULL,
  `typeId` int(11) DEFAULT NULL,
  `authorId` int(11) DEFAULT NULL,
  `postDate` datetime DEFAULT NULL,
  `expiryDate` datetime DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_entries`
--

INSERT INTO `craft_entries` (`id`, `sectionId`, `typeId`, `authorId`, `postDate`, `expiryDate`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(4, 3, 3, 1, '2017-09-22 16:05:00', NULL, '2017-09-22 16:05:50', '2017-09-22 16:17:37', 'c42b9a05-b82c-4d5f-beea-77a27d2b01f4');

-- --------------------------------------------------------

--
-- Table structure for table `craft_entrydrafts`
--

DROP TABLE IF EXISTS `craft_entrydrafts`;
CREATE TABLE `craft_entrydrafts` (
  `id` int(11) NOT NULL,
  `entryId` int(11) NOT NULL,
  `sectionId` int(11) NOT NULL,
  `creatorId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notes` tinytext COLLATE utf8_unicode_ci,
  `data` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_entrytypes`
--

DROP TABLE IF EXISTS `craft_entrytypes`;
CREATE TABLE `craft_entrytypes` (
  `id` int(11) NOT NULL,
  `sectionId` int(11) NOT NULL,
  `fieldLayoutId` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hasTitleField` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `titleLabel` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Title',
  `titleFormat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_entrytypes`
--

INSERT INTO `craft_entrytypes` (`id`, `sectionId`, `fieldLayoutId`, `name`, `handle`, `hasTitleField`, `titleLabel`, `titleFormat`, `sortOrder`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(3, 3, 11, 'Pages', 'page', 1, 'Title', NULL, 1, '2017-09-22 15:56:17', '2017-09-22 16:03:20', '641d292f-5dc9-4fbe-9edb-d969725c2f61');

-- --------------------------------------------------------

--
-- Table structure for table `craft_entryversions`
--

DROP TABLE IF EXISTS `craft_entryversions`;
CREATE TABLE `craft_entryversions` (
  `id` int(11) NOT NULL,
  `entryId` int(11) NOT NULL,
  `sectionId` int(11) NOT NULL,
  `creatorId` int(11) DEFAULT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `num` smallint(6) unsigned NOT NULL,
  `notes` tinytext COLLATE utf8_unicode_ci,
  `data` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_entryversions`
--

INSERT INTO `craft_entryversions` (`id`, `entryId`, `sectionId`, `creatorId`, `locale`, `num`, `notes`, `data`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(4, 4, 3, 1, 'en_us', 1, '', '{"typeId":null,"authorId":"1","title":"Home","slug":"home","postDate":1506096349,"expiryDate":null,"enabled":1,"parentId":null,"fields":{"3":{"new1":{"type":"masthead","enabled":"1","fields":{"image":{"new1":{"type":"1"}},"video":"","heading":"Heading","subheading":"Subheading"}}},"13":{"seoMainEntityCategory":"CreativeWork","seoMainEntityOfPage":"WebPage","seoTitleSource":"field","seoTitleSourceField":"title","seoTitleUnparsed":"","seoDescriptionSource":"custom","seoDescriptionSourceField":"title","seoDescriptionUnparsed":"","seoKeywordsSource":"custom","seoKeywordsSourceField":"title","seoKeywordsUnparsed":"","seoImageIdSource":"custom","seoImageTransform":"","canonicalUrlOverride":"","twitterCardType":"","seoTwitterImageIdSource":"custom","seoTwitterImageTransform":"","openGraphType":"","seoFacebookImageIdSource":"custom","seoFacebookImageTransform":"","robots":""}}}', '2017-09-22 16:05:50', '2017-09-22 16:05:50', '041c85e1-2a96-4e84-ab88-ce170591d2dc'),
(5, 4, 3, 1, 'en_us', 2, '', '{"typeId":"3","authorId":"1","title":"Home","slug":"home","postDate":1506096300,"expiryDate":null,"enabled":1,"parentId":null,"fields":{"3":{"5":{"type":"masthead","enabled":"1","fields":{"image":{"6":{"type":"1"}},"video":"","heading":"Heading","subheading":"Subheading"}}},"13":{"seoMainEntityCategory":"CreativeWork","seoMainEntityOfPage":"WebPage","seoTitleSource":"field","seoTitleSourceField":"title","seoTitleUnparsed":"Home","seoDescriptionSource":"custom","seoDescriptionSourceField":"title","seoDescriptionUnparsed":"","seoKeywordsSource":"custom","seoKeywordsSourceField":"title","seoKeywordsUnparsed":"","seoImageIdSource":"custom","seoImageTransform":"","canonicalUrlOverride":"","twitterCardType":"","seoTwitterImageIdSource":"custom","seoTwitterImageTransform":"","openGraphType":"","seoFacebookImageIdSource":"custom","seoFacebookImageTransform":"","robots":""}}}', '2017-09-22 16:06:05', '2017-09-22 16:06:05', 'babe2dc2-99e2-4b46-985d-0e5ec293efea'),
(6, 4, 3, 1, 'en_us', 3, '', '{"typeId":"3","authorId":"1","title":"Home","slug":"__home__","postDate":1506096300,"expiryDate":null,"enabled":1,"parentId":null,"fields":{"3":{"5":{"type":"masthead","enabled":"1","fields":{"image":{"6":{"type":"1"}},"video":"","heading":"Heading","subheading":"Subheading"}}},"13":{"seoMainEntityCategory":"CreativeWork","seoMainEntityOfPage":"WebPage","seoTitleSource":"field","seoTitleSourceField":"title","seoTitleUnparsed":"Home","seoDescriptionSource":"custom","seoDescriptionSourceField":"title","seoDescriptionUnparsed":"","seoKeywordsSource":"custom","seoKeywordsSourceField":"title","seoKeywordsUnparsed":"","seoImageIdSource":"custom","seoImageTransform":"","canonicalUrlOverride":"","twitterCardType":"","seoTwitterImageIdSource":"custom","seoTwitterImageTransform":"","openGraphType":"","seoFacebookImageIdSource":"custom","seoFacebookImageTransform":"","robots":""}}}', '2017-09-22 16:07:28', '2017-09-22 16:07:28', '3b36fe82-16b1-416a-8303-e9469febbf9a'),
(7, 4, 3, 1, 'en_us', 4, '', '{"typeId":"3","authorId":"1","title":"Home","slug":"__home__","postDate":1506096300,"expiryDate":null,"enabled":1,"parentId":null,"fields":{"3":{"5":{"type":"masthead","enabled":"1","fields":{"image":{"6":{"type":"1","fields":{"mobile":["7"],"desktop":["7"]}}},"video":"","heading":"Heading","subheading":"Subheading"}}},"13":{"seoMainEntityCategory":"CreativeWork","seoMainEntityOfPage":"WebPage","seoTitleSource":"field","seoTitleSourceField":"title","seoTitleUnparsed":"Home","seoDescriptionSource":"custom","seoDescriptionSourceField":"title","seoDescriptionUnparsed":"","seoKeywordsSource":"custom","seoKeywordsSourceField":"title","seoKeywordsUnparsed":"","seoImageIdSource":"custom","seoImageId":"","seoImageTransform":"","canonicalUrlOverride":"","twitterCardType":"","seoTwitterImageIdSource":"custom","seoTwitterImageId":"","seoTwitterImageTransform":"","openGraphType":"","seoFacebookImageIdSource":"custom","seoFacebookImageId":"","seoFacebookImageTransform":"","robots":""}}}', '2017-09-22 16:17:37', '2017-09-22 16:17:37', '173fb8e6-62e0-41e8-8e22-ad824cc1c0ec');

-- --------------------------------------------------------

--
-- Table structure for table `craft_fieldgroups`
--

DROP TABLE IF EXISTS `craft_fieldgroups`;
CREATE TABLE `craft_fieldgroups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_fieldgroups`
--

INSERT INTO `craft_fieldgroups` (`id`, `name`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 'Default', '2017-09-22 15:46:54', '2017-09-22 15:46:54', '806efc71-1220-4b2e-bbac-ea16943b2a27');

-- --------------------------------------------------------

--
-- Table structure for table `craft_fieldlayoutfields`
--

DROP TABLE IF EXISTS `craft_fieldlayoutfields`;
CREATE TABLE `craft_fieldlayoutfields` (
  `id` int(11) NOT NULL,
  `layoutId` int(11) NOT NULL,
  `tabId` int(11) NOT NULL,
  `fieldId` int(11) NOT NULL,
  `required` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_fieldlayoutfields`
--

INSERT INTO `craft_fieldlayoutfields` (`id`, `layoutId`, `tabId`, `fieldId`, `required`, `sortOrder`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(14, 11, 7, 3, 0, 1, '2017-09-22 16:03:20', '2017-09-22 16:03:20', '5510aa23-e41a-45a1-94d6-817db9dbf88e'),
(15, 11, 8, 13, 0, 1, '2017-09-22 16:03:20', '2017-09-22 16:03:20', '6ffd9a54-fe39-4d79-b0a9-ad8dda000290'),
(16, 17, 9, 5, 0, 1, '2017-09-22 16:17:02', '2017-09-22 16:17:02', 'f8932f07-aeb3-4094-b3f3-46f3deaa586b'),
(17, 17, 9, 6, 0, 2, '2017-09-22 16:17:02', '2017-09-22 16:17:02', 'fa0a1885-ab53-4368-9f17-bb0694491b7e'),
(18, 18, 10, 8, 0, 1, '2017-09-22 16:17:02', '2017-09-22 16:17:02', '6267f775-82d2-4364-a3d3-ff622af1696b'),
(19, 18, 10, 9, 0, 2, '2017-09-22 16:17:02', '2017-09-22 16:17:02', '4da5a76e-c170-46fb-b3f4-058322c0360a'),
(20, 18, 10, 10, 0, 3, '2017-09-22 16:17:02', '2017-09-22 16:17:02', '954c6303-276d-433c-a513-a47e2435f535'),
(21, 19, 11, 4, 0, 1, '2017-09-22 16:17:02', '2017-09-22 16:17:02', '6786ad72-59c8-44d8-bf99-8c761e01d005'),
(22, 19, 11, 7, 0, 2, '2017-09-22 16:17:02', '2017-09-22 16:17:02', '341fbc03-69d9-4441-a353-a599c9a75687'),
(23, 19, 11, 11, 0, 3, '2017-09-22 16:17:02', '2017-09-22 16:17:02', '8b117b36-2832-4c64-b7fa-ed988c8e4271'),
(24, 19, 11, 12, 0, 4, '2017-09-22 16:17:02', '2017-09-22 16:17:02', '20f646da-d40d-439d-b8ee-2f589591eb5b');

-- --------------------------------------------------------

--
-- Table structure for table `craft_fieldlayouts`
--

DROP TABLE IF EXISTS `craft_fieldlayouts`;
CREATE TABLE `craft_fieldlayouts` (
  `id` int(11) NOT NULL,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_fieldlayouts`
--

INSERT INTO `craft_fieldlayouts` (`id`, `type`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 'Tag', '2017-09-22 15:46:54', '2017-09-22 15:46:54', '1a9e06bd-6020-4c53-a920-4ab2db7ed032'),
(11, 'Entry', '2017-09-22 16:03:20', '2017-09-22 16:03:20', 'fa13c3ff-e4f7-453d-a2be-850b2e200734'),
(15, 'Asset', '2017-09-22 16:15:09', '2017-09-22 16:15:09', '80782df8-d0d6-45d4-9682-b1a0c2a30c0b'),
(16, 'Asset', '2017-09-22 16:15:20', '2017-09-22 16:15:20', '9858f32d-121e-4cff-a2c0-6457c5c99da5'),
(17, 'SuperTable_Block', '2017-09-22 16:17:02', '2017-09-22 16:17:02', '6882faa9-b94c-4d66-b9eb-84107b95122f'),
(18, 'SuperTable_Block', '2017-09-22 16:17:02', '2017-09-22 16:17:02', '9032eafc-b4c5-4d98-9e11-3715288a3d61'),
(19, 'MatrixBlock', '2017-09-22 16:17:02', '2017-09-22 16:17:02', '5abc0735-7424-4ac7-b21d-649cb8609d2a');

-- --------------------------------------------------------

--
-- Table structure for table `craft_fieldlayouttabs`
--

DROP TABLE IF EXISTS `craft_fieldlayouttabs`;
CREATE TABLE `craft_fieldlayouttabs` (
  `id` int(11) NOT NULL,
  `layoutId` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_fieldlayouttabs`
--

INSERT INTO `craft_fieldlayouttabs` (`id`, `layoutId`, `name`, `sortOrder`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(7, 11, 'Page Builder', 1, '2017-09-22 16:03:20', '2017-09-22 16:03:20', '8456b525-f44c-468c-8e7e-04f09ab866f7'),
(8, 11, 'Meta Data', 2, '2017-09-22 16:03:20', '2017-09-22 16:03:20', 'ef52e720-4c50-4110-96d7-393644c04ad3'),
(9, 17, 'Content', 1, '2017-09-22 16:17:02', '2017-09-22 16:17:02', 'f0997020-6c42-4c36-9346-8c4a96ae9351'),
(10, 18, 'Content', 1, '2017-09-22 16:17:02', '2017-09-22 16:17:02', 'dd3132ae-79e4-4fac-8942-d7d9b910fd05'),
(11, 19, 'Content', 1, '2017-09-22 16:17:02', '2017-09-22 16:17:02', 'd01598fa-41b0-4160-ab1a-38cad7bf60dc');

-- --------------------------------------------------------

--
-- Table structure for table `craft_fields`
--

DROP TABLE IF EXISTS `craft_fields`;
CREATE TABLE `craft_fields` (
  `id` int(11) NOT NULL,
  `groupId` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(58) COLLATE utf8_unicode_ci NOT NULL,
  `context` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'global',
  `instructions` text COLLATE utf8_unicode_ci,
  `translatable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `settings` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_fields`
--

INSERT INTO `craft_fields` (`id`, `groupId`, `name`, `handle`, `context`, `instructions`, `translatable`, `type`, `settings`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 1, 'Body', 'body', 'global', NULL, 1, 'RichText', '{"configFile":"Standard.json","columnType":"text"}', '2017-09-22 15:46:54', '2017-09-22 15:46:54', 'b4732646-9526-48db-b9eb-e5e101192641'),
(2, 1, 'Tags', 'tags', 'global', NULL, 0, 'Tags', '{"source":"taggroup:1"}', '2017-09-22 15:46:54', '2017-09-22 15:46:54', 'd7231262-1078-4764-b320-8eb245a87b43'),
(3, 1, 'Components', 'components', 'global', '', 0, 'Matrix', '{"maxBlocks":null}', '2017-09-22 15:59:34', '2017-09-22 16:17:02', 'c4e29ac7-4d38-4103-bbe9-820095fa0d8d'),
(4, NULL, 'Image', 'image', 'matrixBlockType:1', '', 0, 'SuperTable', '{"columns":{"5":{"width":""},"6":{"width":""}},"fieldLayout":"row","staticField":"1","selectionLabel":"Add a row","maxRows":null,"minRows":null}', '2017-09-22 15:59:35', '2017-09-22 16:17:02', '441f8949-1bf9-4f79-857e-a18f0449f4af'),
(5, NULL, 'Mobile', 'mobile', 'superTableBlockType:1', '', 0, 'Assets', '{"useSingleFolder":"1","sources":"*","defaultUploadLocationSource":"1","defaultUploadLocationSubpath":"","singleUploadLocationSource":"1","singleUploadLocationSubpath":"","restrictFiles":"1","allowedKinds":["image"],"limit":"1","viewMode":"list","selectionLabel":""}', '2017-09-22 15:59:35', '2017-09-22 16:17:02', '649c27dc-1ade-4a26-b6ad-f0ec8959571b'),
(6, NULL, 'Desktop', 'desktop', 'superTableBlockType:1', '', 0, 'Assets', '{"useSingleFolder":"1","sources":"*","defaultUploadLocationSource":"1","defaultUploadLocationSubpath":"","singleUploadLocationSource":"1","singleUploadLocationSubpath":"","restrictFiles":"1","allowedKinds":["image"],"limit":"1","viewMode":"list","selectionLabel":""}', '2017-09-22 15:59:35', '2017-09-22 16:17:02', '870e35db-f971-49a1-95c7-0d92c4b3c053'),
(7, NULL, 'Video', 'video', 'matrixBlockType:1', '', 0, 'SuperTable', '{"columns":{"8":{"width":""},"9":{"width":""},"10":{"width":""}},"fieldLayout":"row","staticField":null,"selectionLabel":"Add a row","maxRows":null,"minRows":null}', '2017-09-22 15:59:35', '2017-09-22 16:17:02', '55e1598a-5d3f-4b5c-afd9-9c064c83f8ea'),
(8, NULL, 'webm', 'webm', 'superTableBlockType:2', '', 0, 'Assets', '{"useSingleFolder":"1","sources":"*","defaultUploadLocationSource":"1","defaultUploadLocationSubpath":"","singleUploadLocationSource":"2","singleUploadLocationSubpath":"","restrictFiles":"1","allowedKinds":["video"],"limit":"1","viewMode":"list","selectionLabel":""}', '2017-09-22 15:59:35', '2017-09-22 16:17:02', '978d3f7a-8dbb-4e0c-b8d6-6507d249a964'),
(9, NULL, 'mp4', 'mp4', 'superTableBlockType:2', '', 0, 'Assets', '{"useSingleFolder":"1","sources":"*","defaultUploadLocationSource":"1","defaultUploadLocationSubpath":"","singleUploadLocationSource":"2","singleUploadLocationSubpath":"","restrictFiles":"1","allowedKinds":["video"],"limit":"1","viewMode":"list","selectionLabel":""}', '2017-09-22 15:59:35', '2017-09-22 16:17:02', '711dcb9a-4947-4925-827d-ab52b7509b5a'),
(10, NULL, 'ogg', 'ogg', 'superTableBlockType:2', '', 0, 'Assets', '{"useSingleFolder":"1","sources":"*","defaultUploadLocationSource":"1","defaultUploadLocationSubpath":"","singleUploadLocationSource":"2","singleUploadLocationSubpath":"","restrictFiles":"1","allowedKinds":["video"],"limit":"1","viewMode":"list","selectionLabel":""}', '2017-09-22 15:59:35', '2017-09-22 16:17:02', '7b1b91e5-075d-47e5-9c37-bffca05eafbd'),
(11, NULL, 'Heading', 'heading', 'matrixBlockType:1', '', 0, 'PlainText', '{"placeholder":"","maxLength":"","multiline":"","initialRows":"4"}', '2017-09-22 15:59:35', '2017-09-22 16:17:02', 'ed63842c-bf51-482c-b690-94b3fd88ab30'),
(12, NULL, 'Subheading', 'subheading', 'matrixBlockType:1', '', 0, 'PlainText', '{"placeholder":"","maxLength":"","multiline":"","initialRows":"4"}', '2017-09-22 15:59:36', '2017-09-22 16:17:02', '7fdcfa69-da8c-438b-a078-40f52d5bb883'),
(13, 1, 'Meta Data', 'metaData', 'global', '', 0, 'Seomatic_Meta', '{"seoMainEntityCategory":"CreativeWork","seoMainEntityOfPage":"WebPage","seoTitleSource":"field","seoTitleSourceField":"title","seoTitle":"","seoTitleSourceChangeable":"1","seoDescriptionSource":"custom","seoDescriptionSourceField":"title","seoDescription":"","seoDescriptionSourceChangeable":"1","seoKeywordsSource":"custom","seoKeywordsSourceField":"title","seoKeywords":"","seoKeywordsSourceChangeable":"1","seoImageIdSource":"custom","seoImageIdSourceChangeable":"1","seoImageTransform":"","twitterCardType":"","twitterCardTypeChangeable":"1","seoTwitterImageIdSource":"custom","seoTwitterImageIdSourceChangeable":"1","seoTwitterImageTransform":"","openGraphType":"","openGraphTypeChangeable":"1","seoFacebookImageIdSource":"custom","seoFacebookImageIdSourceChangeable":"1","seoFacebookImageTransform":"","robots":"","robotsChangeable":"1"}', '2017-09-22 16:02:53', '2017-09-22 16:02:53', '81d338af-d81a-4328-b2c5-00bad17ce3c8');

-- --------------------------------------------------------

--
-- Table structure for table `craft_freeform_crm_fields`
--

DROP TABLE IF EXISTS `craft_freeform_crm_fields`;
CREATE TABLE `craft_freeform_crm_fields` (
  `id` int(11) NOT NULL,
  `integrationId` int(11) NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('string','numeric','boolean','array') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'string',
  `required` int(1) unsigned NOT NULL DEFAULT '0',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_freeform_export_settings`
--

DROP TABLE IF EXISTS `craft_freeform_export_settings`;
CREATE TABLE `craft_freeform_export_settings` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `setting` text COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_freeform_fields`
--

DROP TABLE IF EXISTS `craft_freeform_fields`;
CREATE TABLE `craft_freeform_fields` (
  `id` int(11) NOT NULL,
  `notificationId` int(11) DEFAULT NULL,
  `assetSourceId` int(11) DEFAULT NULL,
  `type` enum('text','textarea','email','hidden','select','checkbox','checkbox_group','radio_group','file','dynamic_recipients','datetime','number','phone','website','rating','regex','confirmation') COLLATE utf8_unicode_ci DEFAULT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `required` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `placeholder` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instructions` text COLLATE utf8_unicode_ci,
  `values` text COLLATE utf8_unicode_ci,
  `options` text COLLATE utf8_unicode_ci,
  `checked` tinyint(1) unsigned DEFAULT '0',
  `rows` int(10) DEFAULT NULL,
  `fileKinds` text COLLATE utf8_unicode_ci,
  `maxFileSizeKB` int(10) DEFAULT NULL,
  `additionalProperties` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_freeform_fields`
--

INSERT INTO `craft_freeform_fields` (`id`, `notificationId`, `assetSourceId`, `type`, `handle`, `label`, `required`, `value`, `placeholder`, `instructions`, `values`, `options`, `checked`, `rows`, `fileKinds`, `maxFileSizeKB`, `additionalProperties`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, NULL, NULL, 'text', 'firstName', 'First Name', 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2017-09-22 15:48:04', '2017-09-22 15:48:04', '3ebdfdea-7728-4d08-889d-13cc65751b95'),
(2, NULL, NULL, 'text', 'lastName', 'Last Name', 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2017-09-22 15:48:04', '2017-09-22 15:48:04', '65a7cc00-a3f6-4ec4-8f84-641ca0cdd2bd'),
(3, NULL, NULL, 'email', 'email', 'Email', 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2017-09-22 15:48:04', '2017-09-22 15:48:04', 'c55fc519-b44e-43c3-82a8-33846d57b076'),
(4, NULL, NULL, 'text', 'website', 'Website', 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2017-09-22 15:48:05', '2017-09-22 15:48:05', 'b085db14-b7ae-4f3f-aa18-fc48b2382e3d'),
(5, NULL, NULL, 'text', 'cellPhone', 'Cell Phone', 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2017-09-22 15:48:05', '2017-09-22 15:48:05', '9cc0c8a4-8ddb-4894-9f5d-8f8a2977ab6a'),
(6, NULL, NULL, 'text', 'homePhone', 'Home Phone', 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2017-09-22 15:48:05', '2017-09-22 15:48:05', '45b35838-b074-463c-810b-32f5e271baac'),
(7, NULL, NULL, 'text', 'companyName', 'Company Name', 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2017-09-22 15:48:05', '2017-09-22 15:48:05', '7cb24bd4-c145-48e2-a164-526d2b54d6af'),
(8, NULL, NULL, 'textarea', 'address', 'Address', 0, NULL, NULL, NULL, NULL, NULL, 0, 2, NULL, NULL, NULL, '2017-09-22 15:48:05', '2017-09-22 15:48:05', '98eab1e4-c833-4cfb-962e-1e009b53d5f0'),
(9, NULL, NULL, 'text', 'city', 'City', 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2017-09-22 15:48:05', '2017-09-22 15:48:05', '0a32a7bc-99cf-4f7d-8d4f-7c161ffa79a6'),
(10, NULL, NULL, 'select', 'state', 'State', 0, NULL, NULL, NULL, NULL, '[{"value":"","label":"Select a State"},{"value":"AL","label":"Alabama"},{"value":"AK","label":"Alaska"},{"value":"AZ","label":"Arizona"},{"value":"AR","label":"Arkansas"},{"value":"CA","label":"California"},{"value":"CO","label":"Colorado"},{"value":"CT","label":"Connecticut"},{"value":"DE","label":"Delaware"},{"value":"DC","label":"District of Columbia"},{"value":"FL","label":"Florida"},{"value":"GA","label":"Georgia"},{"value":"HI","label":"Hawaii"},{"value":"ID","label":"Idaho"},{"value":"IL","label":"Illinois"},{"value":"IN","label":"Indiana"},{"value":"IA","label":"Iowa"},{"value":"KS","label":"Kansas"},{"value":"KY","label":"Kentucky"},{"value":"LA","label":"Louisiana"},{"value":"ME","label":"Maine"},{"value":"MD","label":"Maryland"},{"value":"MA","label":"Massachusetts"},{"value":"MI","label":"Michigan"},{"value":"MN","label":"Minnesota"},{"value":"MS","label":"Mississippi"},{"value":"MO","label":"Missouri"},{"value":"MT","label":"Montana"},{"value":"NE","label":"Nebraska"},{"value":"NV","label":"Nevada"},{"value":"NH","label":"New Hampshire"},{"value":"NJ","label":"New Jersey"},{"value":"NM","label":"New Mexico"},{"value":"NY","label":"New York"},{"value":"NC","label":"North Carolina"},{"value":"ND","label":"North Dakota"},{"value":"OH","label":"Ohio"},{"value":"OK","label":"Oklahoma"},{"value":"OR","label":"Oregon"},{"value":"PA","label":"Pennsylvania"},{"value":"RI","label":"Rhode Island"},{"value":"SC","label":"South Carolina"},{"value":"SD","label":"South Dakota"},{"value":"TN","label":"Tennessee"},{"value":"TX","label":"Texas"},{"value":"UT","label":"Utah"},{"value":"VT","label":"Vermont"},{"value":"VA","label":"Virginia"},{"value":"WA","label":"Washington"},{"value":"WV","label":"West Virginia"},{"value":"WI","label":"Wisconsin"},{"value":"WY","label":"Wyoming"}]', 0, NULL, NULL, NULL, NULL, '2017-09-22 15:48:05', '2017-09-22 15:48:05', '75f3ae1a-18a8-49f2-9259-74a61518e25b'),
(11, NULL, NULL, 'text', 'zipCode', 'Zip Code', 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2017-09-22 15:48:05', '2017-09-22 15:48:05', 'cb2e703f-544f-48a7-99b9-78b499ab3769'),
(12, NULL, NULL, 'textarea', 'message', 'Message', 0, NULL, NULL, NULL, NULL, NULL, 0, 5, NULL, NULL, NULL, '2017-09-22 15:48:06', '2017-09-22 15:48:06', 'ee4960c1-ba8c-47a7-8cd9-5027401370e1');

-- --------------------------------------------------------

--
-- Table structure for table `craft_freeform_forms`
--

DROP TABLE IF EXISTS `craft_freeform_forms`;
CREATE TABLE `craft_freeform_forms` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `spamBlockCount` int(10) DEFAULT '0',
  `submissionTitleFormat` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `layoutJson` text COLLATE utf8_unicode_ci NOT NULL,
  `returnUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `defaultStatus` int(10) NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_freeform_integrations`
--

DROP TABLE IF EXISTS `craft_freeform_integrations`;
CREATE TABLE `craft_freeform_integrations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('mailing_list','crm') COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `accessToken` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `settings` text COLLATE utf8_unicode_ci,
  `forceUpdate` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lastUpdate` datetime DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_freeform_mailing_lists`
--

DROP TABLE IF EXISTS `craft_freeform_mailing_lists`;
CREATE TABLE `craft_freeform_mailing_lists` (
  `id` int(11) NOT NULL,
  `integrationId` int(11) NOT NULL,
  `resourceId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `memberCount` int(11) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_freeform_mailing_list_fields`
--

DROP TABLE IF EXISTS `craft_freeform_mailing_list_fields`;
CREATE TABLE `craft_freeform_mailing_list_fields` (
  `id` int(11) NOT NULL,
  `mailingListId` int(11) NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('string','numeric','boolean','array') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'string',
  `required` int(1) unsigned NOT NULL DEFAULT '0',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_freeform_notifications`
--

DROP TABLE IF EXISTS `craft_freeform_notifications`;
CREATE TABLE `craft_freeform_notifications` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `fromName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fromEmail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `replyToEmail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `includeAttachments` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bodyHtml` text COLLATE utf8_unicode_ci,
  `bodyText` text COLLATE utf8_unicode_ci,
  `sortOrder` int(10) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_freeform_statuses`
--

DROP TABLE IF EXISTS `craft_freeform_statuses`;
CREATE TABLE `craft_freeform_statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` enum('green','blue','yellow','orange','red','pink','purple','turquoise','light','grey','black') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'grey',
  `isDefault` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sortOrder` int(10) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_freeform_statuses`
--

INSERT INTO `craft_freeform_statuses` (`id`, `name`, `handle`, `color`, `isDefault`, `sortOrder`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 'Pending', 'pending', 'light', 0, 1, '2017-09-22 15:48:06', '2017-09-22 15:48:06', '46fa7742-39e8-471d-8524-5590b5233a93'),
(2, 'Open', 'open', 'green', 1, 2, '2017-09-22 15:48:06', '2017-09-22 15:48:06', '9df14b38-999c-4778-97a8-62f62d6c37c0'),
(3, 'Closed', 'closed', 'grey', 0, 3, '2017-09-22 15:48:06', '2017-09-22 15:48:06', '8ba1c56e-8f90-41fc-b55d-454505a9c31e');

-- --------------------------------------------------------

--
-- Table structure for table `craft_freeform_submissions`
--

DROP TABLE IF EXISTS `craft_freeform_submissions`;
CREATE TABLE `craft_freeform_submissions` (
  `id` int(11) NOT NULL,
  `statusId` int(11) DEFAULT NULL,
  `formId` int(11) NOT NULL,
  `incrementalId` int(10) DEFAULT '0',
  `field_1` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_3` text COLLATE utf8_unicode_ci,
  `field_4` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_5` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_6` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_7` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_8` text COLLATE utf8_unicode_ci,
  `field_9` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_10` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_11` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_12` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_freeform_unfinalized_files`
--

DROP TABLE IF EXISTS `craft_freeform_unfinalized_files`;
CREATE TABLE `craft_freeform_unfinalized_files` (
  `id` int(11) NOT NULL,
  `assetId` int(11) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_globalsets`
--

DROP TABLE IF EXISTS `craft_globalsets`;
CREATE TABLE `craft_globalsets` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fieldLayoutId` int(10) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_info`
--

DROP TABLE IF EXISTS `craft_info`;
CREATE TABLE `craft_info` (
  `id` int(11) NOT NULL,
  `version` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `schemaVersion` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `edition` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `siteName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `siteUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timezone` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `on` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `maintenance` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_info`
--

INSERT INTO `craft_info` (`id`, `version`, `schemaVersion`, `edition`, `siteName`, `siteUrl`, `timezone`, `on`, `maintenance`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, '2.6.2990', '2.6.10', 0, 'Boilerplate Craftcms', 'http://boilerplate-craftcms.ads.dsdev', 'UTC', 1, 0, '2017-09-22 15:46:47', '2017-09-22 16:03:48', '812a45b3-50df-44a8-8b91-15f6f9399ce8');

-- --------------------------------------------------------

--
-- Table structure for table `craft_jobscore`
--

DROP TABLE IF EXISTS `craft_jobscore`;
CREATE TABLE `craft_jobscore` (
  `id` int(11) NOT NULL,
  `companyName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `jobId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `applyUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `detailUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `jobDateUpdated` datetime DEFAULT NULL,
  `jobDateOpened` datetime DEFAULT NULL,
  `jobDateCreate` datetime DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_locales`
--

DROP TABLE IF EXISTS `craft_locales`;
CREATE TABLE `craft_locales` (
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_locales`
--

INSERT INTO `craft_locales` (`locale`, `sortOrder`, `dateCreated`, `dateUpdated`, `uid`) VALUES
('en_us', 1, '2017-09-22 15:46:47', '2017-09-22 15:46:47', 'a5d6485a-32be-4330-92ee-27296feca126');

-- --------------------------------------------------------

--
-- Table structure for table `craft_matrixblocks`
--

DROP TABLE IF EXISTS `craft_matrixblocks`;
CREATE TABLE `craft_matrixblocks` (
  `id` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `fieldId` int(11) NOT NULL,
  `typeId` int(11) DEFAULT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `ownerLocale` char(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_matrixblocks`
--

INSERT INTO `craft_matrixblocks` (`id`, `ownerId`, `fieldId`, `typeId`, `sortOrder`, `ownerLocale`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(5, 4, 3, 1, 1, NULL, '2017-09-22 16:05:50', '2017-09-22 16:17:37', '9e4d3234-f6a1-47bc-b952-faee6d34d43f');

-- --------------------------------------------------------

--
-- Table structure for table `craft_matrixblocktypes`
--

DROP TABLE IF EXISTS `craft_matrixblocktypes`;
CREATE TABLE `craft_matrixblocktypes` (
  `id` int(11) NOT NULL,
  `fieldId` int(11) NOT NULL,
  `fieldLayoutId` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_matrixblocktypes`
--

INSERT INTO `craft_matrixblocktypes` (`id`, `fieldId`, `fieldLayoutId`, `name`, `handle`, `sortOrder`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 3, 19, 'Masthead', 'masthead', 1, '2017-09-22 15:59:35', '2017-09-22 16:17:02', 'e7660c57-2fb3-4405-9f69-145fd4385c4c');

-- --------------------------------------------------------

--
-- Table structure for table `craft_matrixcontent_components`
--

DROP TABLE IF EXISTS `craft_matrixcontent_components`;
CREATE TABLE `craft_matrixcontent_components` (
  `id` int(11) NOT NULL,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `field_masthead_heading` text COLLATE utf8_unicode_ci,
  `field_masthead_subheading` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_matrixcontent_components`
--

INSERT INTO `craft_matrixcontent_components` (`id`, `elementId`, `locale`, `field_masthead_heading`, `field_masthead_subheading`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 5, 'en_us', 'Heading', 'Subheading', '2017-09-22 16:05:50', '2017-09-22 16:17:37', '54880cd5-cf72-49ca-928f-65b28f8c83e2');

-- --------------------------------------------------------

--
-- Table structure for table `craft_migrations`
--

DROP TABLE IF EXISTS `craft_migrations`;
CREATE TABLE `craft_migrations` (
  `id` int(11) NOT NULL,
  `pluginId` int(11) DEFAULT NULL,
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `applyTime` datetime NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_migrations`
--

INSERT INTO `craft_migrations` (`id`, `pluginId`, `version`, `applyTime`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, NULL, 'm000000_000000_base', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', 'bda2fc18-b994-4b8f-aa53-e90d8df547f6'),
(2, NULL, 'm140730_000001_add_filename_and_format_to_transformindex', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', 'acc2a296-6fa6-4e36-8de7-ebf7fa7fc8ce'),
(3, NULL, 'm140815_000001_add_format_to_transforms', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '415593c4-7149-4bde-bca9-53eddb016036'),
(4, NULL, 'm140822_000001_allow_more_than_128_items_per_field', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '03b81991-b368-434d-ae90-3d91a1921182'),
(5, NULL, 'm140829_000001_single_title_formats', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '5683bc61-3ad5-4cea-b8f9-1083dc4025be'),
(6, NULL, 'm140831_000001_extended_cache_keys', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '3675a970-3b82-4e46-864d-ad08050632fc'),
(7, NULL, 'm140922_000001_delete_orphaned_matrix_blocks', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', 'c70f8fe4-69ab-4f1b-a53e-61476ecbfb96'),
(8, NULL, 'm141008_000001_elements_index_tune', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '03d93ef5-a387-4b77-941b-acb44c0bd898'),
(9, NULL, 'm141009_000001_assets_source_handle', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2524c11f-e453-4d45-b98c-c2390c674fae'),
(10, NULL, 'm141024_000001_field_layout_tabs', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '49cee31b-cfca-4a74-9910-581a4a2646db'),
(11, NULL, 'm141030_000000_plugin_schema_versions', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '780fc385-88c7-4f44-bd4e-672d63be8c30'),
(12, NULL, 'm141030_000001_drop_structure_move_permission', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', 'd404366d-97f3-453f-9f75-cc9cafd94fb1'),
(13, NULL, 'm141103_000001_tag_titles', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '229600f2-9584-4422-8b50-f2f97378a684'),
(14, NULL, 'm141109_000001_user_status_shuffle', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', 'bce19d08-c952-4f90-9c23-6e06cbd1dd89'),
(15, NULL, 'm141126_000001_user_week_start_day', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '76abb627-ec6e-4e75-866f-2e167ccc07df'),
(16, NULL, 'm150210_000001_adjust_user_photo_size', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '234f3e6c-9adc-4645-87cb-accbef809bad'),
(17, NULL, 'm150724_000001_adjust_quality_settings', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '67d1c47e-d967-43ce-887a-ae6fafcf0273'),
(18, NULL, 'm150827_000000_element_index_settings', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '6bb8ab4a-112c-4fde-821e-77a9638516f6'),
(19, NULL, 'm150918_000001_add_colspan_to_widgets', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '13ac6a53-726e-4ab7-a679-1a128edc2916'),
(20, NULL, 'm151007_000000_clear_asset_caches', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '6ff79894-52e4-4d5e-a046-8b6e808ab563'),
(21, NULL, 'm151109_000000_text_url_formats', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '9b4a0562-2e7f-4d25-ab31-62d746108ef0'),
(22, NULL, 'm151110_000000_move_logo', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '13395a1c-fb11-4fcb-9cc3-99d793fe30c5'),
(23, NULL, 'm151117_000000_adjust_image_widthheight', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '51745103-b2a4-40e5-8e06-2dcd28ced9d1'),
(24, NULL, 'm151127_000000_clear_license_key_status', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '1fefdb5e-5612-405d-b453-7af1c35f6cd7'),
(25, NULL, 'm151127_000000_plugin_license_keys', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '735c6b86-1440-4f53-a1be-2e9ed4f9cbae'),
(26, NULL, 'm151130_000000_update_pt_widget_feeds', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', 'b299ab01-e9e6-4c86-9562-8b53134c2b64'),
(27, NULL, 'm160114_000000_asset_sources_public_url_default_true', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', 'eb186558-7ae2-469f-8c19-e520605a1b76'),
(28, NULL, 'm160223_000000_sortorder_to_smallint', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '71609e7d-5025-41fd-8691-cc41da21e1a2'),
(29, NULL, 'm160229_000000_set_default_entry_statuses', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '6be91e31-13c0-4e33-92c4-2a8284504095'),
(30, NULL, 'm160304_000000_client_permissions', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', 'fd1b8267-69b3-403f-a709-b74fdb3724d3'),
(31, NULL, 'm160322_000000_asset_filesize', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '6f2ed5f5-7ce7-4fdc-a92a-b76a70bd1a77'),
(32, NULL, 'm160503_000000_orphaned_fieldlayouts', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '8c55c807-8dc0-47af-9f1b-20e2ec3882f7'),
(33, NULL, 'm160510_000000_tasksettings', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', 'd963d9fc-f079-46e3-b190-67787d0f1ea3'),
(34, NULL, 'm160829_000000_pending_user_content_cleanup', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '1bae1203-b4ce-4090-a3c1-51923702ac3f'),
(35, NULL, 'm160830_000000_asset_index_uri_increase', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', 'c13ed17f-e9ba-4c58-b351-95076a142fbb'),
(36, NULL, 'm160919_000000_usergroup_handle_title_unique', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '5f1e77ce-70d8-4624-8809-d2e4b70f31ce'),
(37, NULL, 'm161108_000000_new_version_format', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '3c85c886-79e4-40bd-a056-84dd4cede920'),
(38, NULL, 'm161109_000000_index_shuffle', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '5699fd81-5527-454b-8444-81c57df4c44c'),
(39, NULL, 'm170612_000000_route_index_shuffle', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2017-09-22 15:46:47', '2a3d25a3-09ef-47b2-8bef-6ae3dcc1e9bd'),
(40, 4, 'm161121_101534_freeform_AddCheckedAttributeToFieldModel', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '2017-09-22 15:48:03', 'ca020a8a-9c18-42b6-8f0d-249d30df6398'),
(41, 4, 'm161129_134744_freeform_ChangingFieldDatabaseTypes', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '23fd8126-8682-49d5-b2bc-4aad024611a1'),
(42, 4, 'm170127_083920_freeform_MakeFieldCheckedNullable', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '2017-09-22 15:48:03', 'db5b4fec-42d9-4383-9f96-1a4f11695023'),
(43, 4, 'm170127_095031_freeform_AddArrayTypeToIntegrationFields', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '59e68211-bfb9-4f69-9511-4fdc11012299'),
(44, 4, 'm170207_111312_freeform_AddColorToForms', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '77e2376d-e662-48e7-bb17-9548d5982c1f'),
(45, 4, 'm170609_120423_freeform_AddNewFieldtypesToFields', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '2017-09-22 15:48:03', 'dcc2958e-5c5f-4a44-a524-37c0447fb6a1'),
(46, 4, 'm170609_142437_freeform_AddNewPropertiesToFields', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '1a8cfc54-c52a-44fd-b745-c0b32ced05b7'),
(47, 4, 'm170627_113056_freeform_AddExportSettings', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '5c6ed897-a8af-429a-be5f-f9ce3e6b3645'),
(48, 4, 'm170629_062621_freeform_AddExportProfiles', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '2b92a757-f325-43b3-a7ef-75e674127b42'),
(49, 4, 'm170705_121240_freeform_AddStatusesToExportProfiles', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '201eb567-829a-415c-a64b-119798f5c29b'),
(50, 4, 'm170817_062447_freeform_AddIncrementalSubmissionId', '2017-09-22 15:48:03', '2017-09-22 15:48:03', '2017-09-22 15:48:03', 'e11b0200-3d12-4f66-a9a3-8c3a92540174'),
(51, 6, 'm160208_010101_FruitLinkIt_UpdateExistingLinkItFields', '2017-09-22 15:48:27', '2017-09-22 15:48:27', '2017-09-22 15:48:27', '1d232682-a4b9-4848-836d-16bd9970711d'),
(53, 9, 'm160922_150924_navee_AddRegexField', '2017-09-22 15:49:28', '2017-09-22 15:49:28', '2017-09-22 15:49:28', '2252b55f-8297-4db8-ba4d-b6915c137888'),
(54, 11, 'm130715_191457_oauth_addUserMappingField', '2017-09-22 15:50:46', '2017-09-22 15:50:46', '2017-09-22 15:50:46', '45814f9e-ed48-470e-b69c-8d9e2641b041'),
(55, 11, 'm130907_140340_oauth_renameOauth_providersProviderClassToClass', '2017-09-22 15:50:46', '2017-09-22 15:50:46', '2017-09-22 15:50:46', '9f3cf6e1-04cd-453e-9170-8dfb950ed69e'),
(56, 11, 'm130912_153247_oauth_createTokenIndexes', '2017-09-22 15:50:46', '2017-09-22 15:50:46', '2017-09-22 15:50:46', 'bfbecea0-8b57-4e47-973c-030ab6df13b4'),
(57, 11, 'm140417_000003_changeTokenUniqueIndexes', '2017-09-22 15:50:46', '2017-09-22 15:50:46', '2017-09-22 15:50:46', '6d8d3e1e-432e-4bd2-b40b-cc1ef311bb58'),
(58, 11, 'm140623_130304_oauth_new_tokens_table', '2017-09-22 15:50:46', '2017-09-22 15:50:46', '2017-09-22 15:50:46', '2c24f77d-50fd-446c-96b3-527e6c8725d9'),
(59, 11, 'm150112_220705_oauth_add_token_columns', '2017-09-22 15:50:46', '2017-09-22 15:50:46', '2017-09-22 15:50:46', '8ad246f9-3305-48cb-9ae5-8ecb5791bbcf'),
(60, 11, 'm150311_000001_oauth_remove_old_tokens', '2017-09-22 15:50:46', '2017-09-22 15:50:46', '2017-09-22 15:50:46', '24d77320-b310-4b89-817d-00d8644fccd4'),
(61, 11, 'm161025_000001_oauth_change_tokens_column_types', '2017-09-22 15:50:46', '2017-09-22 15:50:46', '2017-09-22 15:50:46', 'ab744c00-1ef5-4212-a52b-3e4ec4a798f7'),
(62, 12, 'm160307_180819_picPuller_dataBaseCreation', '2017-09-22 15:50:52', '2017-09-22 15:50:52', '2017-09-22 15:50:52', '01e088b8-3030-449e-8c8f-24f66b64738e'),
(63, 13, 'm140403_171200_reroute_addMethodColumn', '2017-09-22 15:50:57', '2017-09-22 15:50:57', '2017-09-22 15:50:57', 'cf77dbba-4227-4e7f-b07e-1e00adefb9b9'),
(64, 13, 'm140403_172200_reroute_autofillMethod', '2017-09-22 15:50:57', '2017-09-22 15:50:57', '2017-09-22 15:50:57', 'b1c1397b-d681-42e6-b466-be521ae229e0'),
(65, 14, 'm151225_000000_seomatic_addHumansField', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', 'c2d308c6-3f6f-4a13-a515-949b91c2d234'),
(66, 14, 'm151226_000000_seomatic_addTwitterFacebookFields', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '46ceffe6-f48f-4cd6-a8d0-55a2ac3ac9a6'),
(67, 14, 'm160101_000000_seomatic_addRobotsFields', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '170f7d9f-6478-4696-b916-d089e92d7f76'),
(68, 14, 'm160111_000000_seomatic_addTitleFields', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', 'df01ffd1-f711-4c8d-8d5e-f6e317b58435'),
(69, 14, 'm160122_000000_seomatic_addTypeFields', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '4a5e1b04-f03c-41c7-a512-578c71b7c6a7'),
(70, 14, 'm160123_000000_seomatic_addOpeningHours', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '068aa856-f224-485e-90e4-c4814f1a0421'),
(71, 14, 'm160202_000000_seomatic_addSocialHandles', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', 'd03033d8-dfe9-499b-8d02-26d58ead50dd'),
(72, 14, 'm160204_000000_seomatic_addGoogleAnalytics', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '9d267462-724e-4135-a0a4-b21cd52d609d'),
(73, 14, 'm160205_000000_seomatic_addResturantMenu', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '64f28b3b-5d01-45c3-8df2-3af9e2488c42'),
(74, 14, 'm160206_000000_seomatic_addGoogleAnalyticsPlugins', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '267709a6-3b0d-4dd0-a6d6-503da96aaa1c'),
(75, 14, 'm160206_000000_seomatic_addGoogleAnalyticsSendPageView', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '53ac6b7e-d3bd-4ad1-8490-7e72345815d0'),
(76, 14, 'm160209_000000_seomatic_alterDescriptionsColumns', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '073a813a-bcc9-4b43-9153-b516d56c21e3'),
(77, 14, 'm160209_000001_seomatic_addRobotsTxt', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', 'f951d068-dd75-4f90-a95e-7f5b39f577ee'),
(78, 14, 'm160227_000000_seomatic_addFacebookAppId', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '6a1b8b14-488e-40b2-9505-98de77e743cc'),
(79, 14, 'm160416_000000_seomatic_addContactPoints', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', 'f2d7553c-bca7-42de-b6a5-e5f1d92d8a7e'),
(80, 14, 'm160509_000000_seomatic_addSiteLinksBing', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2e0cc96d-5716-44ba-970f-f6a8cb261516'),
(81, 14, 'm160707_000000_seomatic_addGoogleTagManager', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '753ace44-1b8f-46d3-9db0-5877b3b5c7d2'),
(82, 14, 'm160715_000000_seomatic_addSeoImageTransforms', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '00aaabe2-69c2-4bd2-97e1-d011de0cc841'),
(83, 14, 'm160723_000000_seomatic_addSeoMainEntityOfPage', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '583b6bb4-71f5-416a-86f7-3069e178a9c1'),
(84, 14, 'm160724_000000_seomatic_addSeoMainEntityCategory', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '4658e8e6-791f-4c92-b555-757608c2c62c'),
(85, 14, 'm160811_000000_seomatic_addVimeo', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', 'ae7f725c-4021-4450-8565-6c1cf0eb1d3b'),
(86, 14, 'm160904_000000_seomatic_addTwitterFacebookImages', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '7302a82e-87e3-4399-813e-5c07a470c8a6'),
(87, 14, 'm161220_000000_seomatic_addPriceRange', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', 'a1b118b9-0b29-41c5-b872-b334839c90f4'),
(88, 14, 'm170212_000000_seomatic_addGoogleAnalyticsAnonymizeIp', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '0345dadc-95bf-4bc5-894f-0c5621dbebef'),
(89, 14, 'm170212_000000_seomatic_addWikipedia', '2017-09-22 15:51:02', '2017-09-22 15:51:02', '2017-09-22 15:51:02', 'b0182430-06d9-4634-9520-540cdd0735da'),
(90, 15, 'm150901_144609_superTable_fixForContentTables', '2017-09-22 15:51:26', '2017-09-22 15:51:26', '2017-09-22 15:51:26', '31b289dc-a968-4b4d-aac3-44451ad2931c');

-- --------------------------------------------------------

--
-- Table structure for table `craft_navee_navigations`
--

DROP TABLE IF EXISTS `craft_navee_navigations`;
CREATE TABLE `craft_navee_navigations` (
  `id` int(11) NOT NULL,
  `creatorId` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `maxLevels` int(10) DEFAULT NULL,
  `fieldLayoutId` int(10) DEFAULT NULL,
  `structureId` int(10) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_navee_nodes`
--

DROP TABLE IF EXISTS `craft_navee_nodes`;
CREATE TABLE `craft_navee_nodes` (
  `id` int(11) NOT NULL,
  `navigationId` int(11) NOT NULL,
  `linkType` enum('entryId','assetId','categoryId','customUri','none') COLLATE utf8_unicode_ci DEFAULT NULL,
  `entryId` int(10) DEFAULT NULL,
  `assetId` int(10) DEFAULT NULL,
  `categoryId` int(10) DEFAULT NULL,
  `customUri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idAttr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `titleAttr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accessKey` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `target` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `includeInNavigation` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `passive` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `userGroups` text COLLATE utf8_unicode_ci,
  `regex` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_oauth_providers`
--

DROP TABLE IF EXISTS `craft_oauth_providers`;
CREATE TABLE `craft_oauth_providers` (
  `id` int(11) NOT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `clientId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `clientSecret` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_oauth_tokens`
--

DROP TABLE IF EXISTS `craft_oauth_tokens`;
CREATE TABLE `craft_oauth_tokens` (
  `id` int(11) NOT NULL,
  `providerHandle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pluginHandle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `accessToken` text COLLATE utf8_unicode_ci,
  `secret` text COLLATE utf8_unicode_ci,
  `endOfLife` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `refreshToken` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_picpuller_authorizations`
--

DROP TABLE IF EXISTS `craft_picpuller_authorizations`;
CREATE TABLE `craft_picpuller_authorizations` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `instagram_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `oauth` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_plugins`
--

DROP TABLE IF EXISTS `craft_plugins`;
CREATE TABLE `craft_plugins` (
  `id` int(11) NOT NULL,
  `class` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `schemaVersion` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `licenseKey` char(24) COLLATE utf8_unicode_ci DEFAULT NULL,
  `licenseKeyStatus` enum('valid','invalid','mismatched','unknown') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unknown',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `settings` text COLLATE utf8_unicode_ci,
  `installDate` datetime NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_plugins`
--

INSERT INTO `craft_plugins` (`id`, `class`, `version`, `schemaVersion`, `licenseKey`, `licenseKeyStatus`, `enabled`, `settings`, `installDate`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 'TimeAgoInWords', '1.1.3', NULL, NULL, 'unknown', 1, NULL, '2017-09-22 15:47:29', '2017-09-22 15:47:29', '2017-09-22 16:05:06', 'ffb53b08-4bc4-487b-a899-da46587f47cb'),
(2, 'CacheBuster', '1.2.2', NULL, NULL, 'unknown', 1, NULL, '2017-09-22 15:47:35', '2017-09-22 15:47:35', '2017-09-22 16:05:06', 'b4ea8587-0d4e-4543-a01b-cb6a40648419'),
(3, 'ElementApi', '1.6.0', '1.0.0', NULL, 'unknown', 1, NULL, '2017-09-22 15:47:40', '2017-09-22 15:47:40', '2017-09-22 16:05:06', '5e5a1fdf-0620-4aa1-8f9d-f5d308bade86'),
(4, 'Freeform', '1.6.1', '1.0.10', NULL, 'unknown', 1, NULL, '2017-09-22 15:48:03', '2017-09-22 15:48:03', '2017-09-22 16:05:06', 'caa1ba35-3e0c-4298-a596-d1f90500633d'),
(5, 'Hue', '1.1.1', '0.0.0.0', NULL, 'unknown', 1, NULL, '2017-09-22 15:48:10', '2017-09-22 15:48:10', '2017-09-22 16:05:06', 'efb5da2d-d8ea-412f-87c8-1f9c29c8ad05'),
(6, 'FruitLinkIt', '2.3.4', '2.3.0', NULL, 'unknown', 1, NULL, '2017-09-22 15:48:27', '2017-09-22 15:48:27', '2017-09-22 16:05:06', '60ac2dad-d778-4655-b50b-fe73e0c7ddee'),
(7, 'JobScore', '0.0.1', '0.0.1', NULL, 'unknown', 1, NULL, '2017-09-22 15:48:31', '2017-09-22 15:48:31', '2017-09-22 16:05:06', '88db8936-17aa-4e20-ba8e-d38dd9610dc7'),
(9, 'Navee', '1.3.0', NULL, NULL, 'unknown', 1, NULL, '2017-09-22 15:49:28', '2017-09-22 15:49:28', '2017-09-22 16:05:06', 'd3e21d9b-bb38-4f55-9593-77b1fe9cb762'),
(10, 'NoCache', '1.0.3', '0.1.0', NULL, 'unknown', 1, NULL, '2017-09-22 15:50:40', '2017-09-22 15:50:40', '2017-09-22 16:05:06', '2b094824-8295-40bf-b141-17b331d85d66'),
(11, 'Oauth', '2.0.3', '1.0.1', NULL, 'unknown', 1, NULL, '2017-09-22 15:50:46', '2017-09-22 15:50:46', '2017-09-22 16:05:06', 'bbd256df-abb2-47c9-bbbd-c200ce1e1104'),
(12, 'PicPuller', '2.3.2', '2.1.0', NULL, 'unknown', 1, NULL, '2017-09-22 15:50:52', '2017-09-22 15:50:52', '2017-09-22 16:05:06', 'a8608886-6086-46cf-9f25-6510ff01b6a7'),
(13, 'Reroute', '1.0.4', NULL, NULL, 'unknown', 1, NULL, '2017-09-22 15:50:57', '2017-09-22 15:50:57', '2017-09-22 16:05:06', '3dd2dc30-c22f-4512-9de2-88e365e1ea62'),
(14, 'Seomatic', '1.1.51', '1.1.25', NULL, 'unknown', 1, NULL, '2017-09-22 15:51:01', '2017-09-22 15:51:01', '2017-09-22 16:05:06', 'c20310b1-6f0f-4d20-9f4d-dcdb650c904c'),
(15, 'SuperTable', '1.0.5', '1.0.0', NULL, 'unknown', 1, NULL, '2017-09-22 15:51:26', '2017-09-22 15:51:26', '2017-09-22 16:05:06', 'e55e33a4-e533-4b22-89db-23ebd123abcc'),
(16, 'SvgIcons', '0.0.6', '0.0.1', NULL, 'unknown', 1, NULL, '2017-09-22 15:51:32', '2017-09-22 15:51:32', '2017-09-22 16:05:06', '6d6fd637-c640-45f8-98da-1490e85834f8'),
(17, 'TinyImage', '1.1.0', NULL, NULL, 'unknown', 1, NULL, '2017-09-22 15:53:11', '2017-09-22 15:53:11', '2017-09-22 16:05:06', '4500f8d4-a8f6-4a8a-8729-0321dcfa5e5c'),
(18, 'Twitter', '1.1.3', '1.0.0', NULL, 'unknown', 1, NULL, '2017-09-22 15:53:30', '2017-09-22 15:53:30', '2017-09-22 16:05:06', 'ce7178af-d3b5-4ae1-b12e-d47965df96f6'),
(19, 'VzAddress', '1.5.0', '1.0.0', NULL, 'unknown', 1, NULL, '2017-09-22 15:53:35', '2017-09-22 15:53:35', '2017-09-22 16:05:06', '4b4f4660-2f00-4a36-b999-e2c19cdaf347');

-- --------------------------------------------------------

--
-- Table structure for table `craft_rackspaceaccess`
--

DROP TABLE IF EXISTS `craft_rackspaceaccess`;
CREATE TABLE `craft_rackspaceaccess` (
  `id` int(11) NOT NULL,
  `connectionKey` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `storageUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cdnUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_relations`
--

DROP TABLE IF EXISTS `craft_relations`;
CREATE TABLE `craft_relations` (
  `id` int(11) NOT NULL,
  `fieldId` int(11) NOT NULL,
  `sourceId` int(11) NOT NULL,
  `sourceLocale` char(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `targetId` int(11) NOT NULL,
  `sortOrder` smallint(6) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_relations`
--

INSERT INTO `craft_relations` (`id`, `fieldId`, `sourceId`, `sourceLocale`, `targetId`, `sortOrder`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 5, 6, NULL, 7, 1, '2017-09-22 16:17:37', '2017-09-22 16:17:37', '4f96fe70-1f1f-43f8-af2a-6b7c52bbf04c'),
(2, 6, 6, NULL, 7, 1, '2017-09-22 16:17:37', '2017-09-22 16:17:37', '3aab9286-af35-49b8-88c7-aae3d0268ccb');

-- --------------------------------------------------------

--
-- Table structure for table `craft_reroute`
--

DROP TABLE IF EXISTS `craft_reroute`;
CREATE TABLE `craft_reroute` (
  `id` int(11) NOT NULL,
  `oldUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `newUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `method` int(10) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_routes`
--

DROP TABLE IF EXISTS `craft_routes`;
CREATE TABLE `craft_routes` (
  `id` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `urlParts` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `urlPattern` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `template` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_searchindex`
--

DROP TABLE IF EXISTS `craft_searchindex`;
CREATE TABLE `craft_searchindex` (
  `elementId` int(11) NOT NULL,
  `attribute` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `fieldId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `keywords` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_searchindex`
--

INSERT INTO `craft_searchindex` (`elementId`, `attribute`, `fieldId`, `locale`, `keywords`) VALUES
(1, 'username', 0, 'en_us', ' adam soffer '),
(1, 'firstname', 0, 'en_us', ''),
(1, 'lastname', 0, 'en_us', ''),
(1, 'fullname', 0, 'en_us', ''),
(1, 'email', 0, 'en_us', ' adam soffer digitalsurgeons com '),
(1, 'slug', 0, 'en_us', ''),
(6, 'slug', 0, 'en_us', ''),
(7, 'filename', 0, 'en_us', ' space jpg '),
(7, 'extension', 0, 'en_us', ' jpg '),
(7, 'kind', 0, 'en_us', ' image '),
(7, 'slug', 0, 'en_us', ' space '),
(7, 'title', 0, 'en_us', ' space '),
(6, 'field', 6, 'en_us', ' space '),
(4, 'slug', 0, 'en_us', ' __home__ '),
(4, 'title', 0, 'en_us', ' home '),
(5, 'field', 4, 'en_us', ' space space '),
(5, 'field', 7, 'en_us', ''),
(5, 'field', 11, 'en_us', ' heading '),
(5, 'field', 12, 'en_us', ' subheading '),
(5, 'slug', 0, 'en_us', ''),
(6, 'field', 5, 'en_us', ' space '),
(4, 'field', 13, 'en_us', ' 1 en_us 1 0 template creativework webpage home home field title custom title custom title custom custom custom '),
(4, 'field', 3, 'en_us', ' heading space space subheading ');

-- --------------------------------------------------------

--
-- Table structure for table `craft_sections`
--

DROP TABLE IF EXISTS `craft_sections`;
CREATE TABLE `craft_sections` (
  `id` int(11) NOT NULL,
  `structureId` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('single','channel','structure') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'channel',
  `hasUrls` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `template` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enableVersioning` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_sections`
--

INSERT INTO `craft_sections` (`id`, `structureId`, `name`, `handle`, `type`, `hasUrls`, `template`, `enableVersioning`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(3, NULL, 'Pages', 'page', 'channel', 1, 'page', 1, '2017-09-22 15:56:17', '2017-09-22 15:56:17', 'f98732f6-4448-4b1e-a437-777deca04b1e');

-- --------------------------------------------------------

--
-- Table structure for table `craft_sections_i18n`
--

DROP TABLE IF EXISTS `craft_sections_i18n`;
CREATE TABLE `craft_sections_i18n` (
  `id` int(11) NOT NULL,
  `sectionId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `enabledByDefault` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `urlFormat` text COLLATE utf8_unicode_ci,
  `nestedUrlFormat` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_sections_i18n`
--

INSERT INTO `craft_sections_i18n` (`id`, `sectionId`, `locale`, `enabledByDefault`, `urlFormat`, `nestedUrlFormat`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(3, 3, 'en_us', 1, '{slug}', NULL, '2017-09-22 15:56:17', '2017-09-22 15:56:17', 'b06def7c-0219-451c-b663-db50b94b5959');

-- --------------------------------------------------------

--
-- Table structure for table `craft_seomatic_meta`
--

DROP TABLE IF EXISTS `craft_seomatic_meta`;
CREATE TABLE `craft_seomatic_meta` (
  `id` int(11) NOT NULL,
  `seoImageId` int(11) DEFAULT NULL,
  `seoTwitterImageId` int(11) DEFAULT NULL,
  `seoFacebookImageId` int(11) DEFAULT NULL,
  `locale` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'en_us',
  `elementId` int(10) DEFAULT '0',
  `metaType` enum('default','template') COLLATE utf8_unicode_ci DEFAULT 'template',
  `metaPath` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `seoMainEntityCategory` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `seoMainEntityOfPage` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `seoTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `seoDescription` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `seoKeywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `seoImageTransform` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `seoFacebookImageTransform` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `seoTwitterImageTransform` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `twitterCardType` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'summary',
  `openGraphType` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'website',
  `robots` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_seomatic_settings`
--

DROP TABLE IF EXISTS `craft_seomatic_settings`;
CREATE TABLE `craft_seomatic_settings` (
  `id` int(11) NOT NULL,
  `siteSeoImageId` int(11) DEFAULT NULL,
  `siteSeoTwitterImageId` int(11) DEFAULT NULL,
  `siteSeoFacebookImageId` int(11) DEFAULT NULL,
  `genericOwnerImageId` int(11) DEFAULT NULL,
  `genericCreatorImageId` int(11) DEFAULT NULL,
  `locale` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `siteSeoName` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `siteSeoTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `siteSeoTitleSeparator` varchar(10) COLLATE utf8_unicode_ci DEFAULT '|',
  `siteSeoTitlePlacement` enum('before','after','none') COLLATE utf8_unicode_ci DEFAULT 'after',
  `siteSeoDescription` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `siteSeoKeywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `siteSeoImageTransform` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `siteSeoFacebookImageTransform` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `siteSeoTwitterImageTransform` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `siteTwitterCardType` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `siteOpenGraphType` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `siteRobots` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `siteRobotsTxt` text COLLATE utf8_unicode_ci,
  `siteLinksSearchTargets` text COLLATE utf8_unicode_ci,
  `siteLinksQueryInput` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `googleSiteVerification` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `bingSiteVerification` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `googleAnalyticsUID` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `googleTagManagerID` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `googleAnalyticsSendPageview` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `googleAnalyticsAdvertising` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `googleAnalyticsEcommerce` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `googleAnalyticsEEcommerce` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `googleAnalyticsLinkAttribution` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `googleAnalyticsLinker` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `googleAnalyticsAnonymizeIp` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `siteOwnerType` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `siteOwnerSubType` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `siteOwnerSpecificType` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `genericOwnerName` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericOwnerAlternateName` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericOwnerDescription` varchar(1024) COLLATE utf8_unicode_ci DEFAULT '',
  `genericOwnerUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericOwnerTelephone` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericOwnerEmail` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericOwnerStreetAddress` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericOwnerAddressLocality` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericOwnerAddressRegion` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericOwnerPostalCode` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericOwnerAddressCountry` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericOwnerGeoLatitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericOwnerGeoLongitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `organizationOwnerDuns` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `organizationOwnerFounder` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `organizationOwnerFoundingDate` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `organizationOwnerFoundingLocation` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `organizationOwnerContactPoints` text COLLATE utf8_unicode_ci,
  `localBusinessPriceRange` varchar(10) COLLATE utf8_unicode_ci DEFAULT '$$$',
  `localBusinessOwnerOpeningHours` text COLLATE utf8_unicode_ci,
  `corporationOwnerTickerSymbol` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `restaurantOwnerServesCuisine` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `restaurantOwnerMenuUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `restaurantOwnerReservationsUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `personOwnerGender` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `personOwnerBirthPlace` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `twitterHandle` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `facebookHandle` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `facebookProfileId` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `facebookAppId` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `linkedInHandle` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `googlePlusHandle` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `youtubeHandle` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `youtubeChannelHandle` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `instagramHandle` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `pinterestHandle` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `githubHandle` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `vimeoHandle` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `wikipediaUrl` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `siteCreatorType` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `siteCreatorSubType` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `siteCreatorSpecificType` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericCreatorName` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericCreatorAlternateName` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericCreatorDescription` varchar(1024) COLLATE utf8_unicode_ci DEFAULT '',
  `genericCreatorUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericCreatorTelephone` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericCreatorEmail` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericCreatorStreetAddress` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericCreatorAddressLocality` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericCreatorAddressRegion` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericCreatorPostalCode` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericCreatorAddressCountry` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericCreatorGeoLatitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericCreatorGeoLongitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `organizationCreatorDuns` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `organizationCreatorFounder` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `organizationCreatorFoundingDate` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `organizationCreatorFoundingLocation` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `organizationCreatorContactPoints` text COLLATE utf8_unicode_ci,
  `localBusinessCreatorOpeningHours` text COLLATE utf8_unicode_ci,
  `corporationCreatorTickerSymbol` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `restaurantCreatorServesCuisine` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `restaurantCreatorMenuUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `restaurantCreatorReservationsUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `personCreatorGender` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `personCreatorBirthPlace` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `genericCreatorHumansTxt` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_seomatic_settings`
--

INSERT INTO `craft_seomatic_settings` (`id`, `siteSeoImageId`, `siteSeoTwitterImageId`, `siteSeoFacebookImageId`, `genericOwnerImageId`, `genericCreatorImageId`, `locale`, `siteSeoName`, `siteSeoTitle`, `siteSeoTitleSeparator`, `siteSeoTitlePlacement`, `siteSeoDescription`, `siteSeoKeywords`, `siteSeoImageTransform`, `siteSeoFacebookImageTransform`, `siteSeoTwitterImageTransform`, `siteTwitterCardType`, `siteOpenGraphType`, `siteRobots`, `siteRobotsTxt`, `siteLinksSearchTargets`, `siteLinksQueryInput`, `googleSiteVerification`, `bingSiteVerification`, `googleAnalyticsUID`, `googleTagManagerID`, `googleAnalyticsSendPageview`, `googleAnalyticsAdvertising`, `googleAnalyticsEcommerce`, `googleAnalyticsEEcommerce`, `googleAnalyticsLinkAttribution`, `googleAnalyticsLinker`, `googleAnalyticsAnonymizeIp`, `siteOwnerType`, `siteOwnerSubType`, `siteOwnerSpecificType`, `genericOwnerName`, `genericOwnerAlternateName`, `genericOwnerDescription`, `genericOwnerUrl`, `genericOwnerTelephone`, `genericOwnerEmail`, `genericOwnerStreetAddress`, `genericOwnerAddressLocality`, `genericOwnerAddressRegion`, `genericOwnerPostalCode`, `genericOwnerAddressCountry`, `genericOwnerGeoLatitude`, `genericOwnerGeoLongitude`, `organizationOwnerDuns`, `organizationOwnerFounder`, `organizationOwnerFoundingDate`, `organizationOwnerFoundingLocation`, `organizationOwnerContactPoints`, `localBusinessPriceRange`, `localBusinessOwnerOpeningHours`, `corporationOwnerTickerSymbol`, `restaurantOwnerServesCuisine`, `restaurantOwnerMenuUrl`, `restaurantOwnerReservationsUrl`, `personOwnerGender`, `personOwnerBirthPlace`, `twitterHandle`, `facebookHandle`, `facebookProfileId`, `facebookAppId`, `linkedInHandle`, `googlePlusHandle`, `youtubeHandle`, `youtubeChannelHandle`, `instagramHandle`, `pinterestHandle`, `githubHandle`, `vimeoHandle`, `wikipediaUrl`, `siteCreatorType`, `siteCreatorSubType`, `siteCreatorSpecificType`, `genericCreatorName`, `genericCreatorAlternateName`, `genericCreatorDescription`, `genericCreatorUrl`, `genericCreatorTelephone`, `genericCreatorEmail`, `genericCreatorStreetAddress`, `genericCreatorAddressLocality`, `genericCreatorAddressRegion`, `genericCreatorPostalCode`, `genericCreatorAddressCountry`, `genericCreatorGeoLatitude`, `genericCreatorGeoLongitude`, `organizationCreatorDuns`, `organizationCreatorFounder`, `organizationCreatorFoundingDate`, `organizationCreatorFoundingLocation`, `organizationCreatorContactPoints`, `localBusinessCreatorOpeningHours`, `corporationCreatorTickerSymbol`, `restaurantCreatorServesCuisine`, `restaurantCreatorMenuUrl`, `restaurantCreatorReservationsUrl`, `personCreatorGender`, `personCreatorBirthPlace`, `genericCreatorHumansTxt`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, 'en_us', 'Boilerplate Craftcms', 'This is the default global title of the site pages.', '|', 'after', 'This is the default global natural language description of the content on the site pages.', 'default,global,comma-separated,keywords', '', '', '', 'summary', 'website', '', '# robots.txt for {{ siteUrl }}\n# For a multi-environment setup, see: https://nystudio107.com/blog/prevent-google-from-indexing-staging-sites\n\nSitemap: {{ siteUrl |trim(''/'') }}/sitemap.xml\n\n{% if craft.config.devMode %}\n# devMode - disallow all\n\nUser-agent: *\nDisallow: /\n{% else %}\n# Live - Don''t allow web crawlers to index Craft\n\nUser-agent: *\nDisallow: /craft/\n{% endif %}', '', '', '', '', '', '', 1, 0, 0, 0, 0, 0, 0, 'Organization', 'Corporation', '', 'Boilerplate Craftcms', '', '', 'http://boilerplate-craftcms.ads.dsdev/', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '$$$', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Organization', 'Corporation', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '/* TEAM */\n\n{% if seomaticCreator.name is defined and seomaticCreator.name %}\nCreator: {{ seomaticCreator.name }}\n{% endif %}\n{% if seomaticCreator.url is defined and seomaticCreator.url %}\nURL: {{ seomaticCreator.url }}\n{% endif %}\n{% if seomaticCreator.description is defined and seomaticCreator.description %}\nDescription: {{ seomaticCreator.description }}\n{% endif %}\n\n/* THANKS */\n\nPixel & Tonic - https://pixelandtonic.com\n\n/* SITE */\n\nStandards: HTML5, CSS3\nComponents: Craft CMS, Yii, PHP, Javascript, SEOmatic', '2017-09-22 15:56:54', '2017-09-22 15:56:54', '8084006f-2a7a-4472-981f-d7b94997ad66');

-- --------------------------------------------------------

--
-- Table structure for table `craft_sessions`
--

DROP TABLE IF EXISTS `craft_sessions`;
CREATE TABLE `craft_sessions` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `token` char(100) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_sessions`
--

INSERT INTO `craft_sessions` (`id`, `userId`, `token`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 1, 'ddadbabffbac9f1fdb452e57b90f2f247134dcf1czozMjoiQVo1VH4xMGVnZjZtR1BOaFA1YjdHZU5oaVJlZkwyVWQiOw==', '2017-09-22 15:46:54', '2017-09-22 15:46:54', '463294e2-f571-4e6b-b1e8-9aee4e5aa6b9');

-- --------------------------------------------------------

--
-- Table structure for table `craft_shunnedmessages`
--

DROP TABLE IF EXISTS `craft_shunnedmessages`;
CREATE TABLE `craft_shunnedmessages` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expiryDate` datetime DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_structureelements`
--

DROP TABLE IF EXISTS `craft_structureelements`;
CREATE TABLE `craft_structureelements` (
  `id` int(11) NOT NULL,
  `structureId` int(11) NOT NULL,
  `elementId` int(11) DEFAULT NULL,
  `root` int(11) unsigned DEFAULT NULL,
  `lft` int(11) unsigned NOT NULL,
  `rgt` int(11) unsigned NOT NULL,
  `level` smallint(6) unsigned NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_structures`
--

DROP TABLE IF EXISTS `craft_structures`;
CREATE TABLE `craft_structures` (
  `id` int(11) NOT NULL,
  `maxLevels` smallint(6) unsigned DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_supertableblocks`
--

DROP TABLE IF EXISTS `craft_supertableblocks`;
CREATE TABLE `craft_supertableblocks` (
  `id` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `fieldId` int(11) NOT NULL,
  `typeId` int(11) DEFAULT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `ownerLocale` char(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_supertableblocks`
--

INSERT INTO `craft_supertableblocks` (`id`, `ownerId`, `fieldId`, `typeId`, `sortOrder`, `ownerLocale`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(6, 5, 4, 1, 1, NULL, '2017-09-22 16:05:50', '2017-09-22 16:17:37', '826b0bc7-66b1-478a-b41e-245d89f0ebff');

-- --------------------------------------------------------

--
-- Table structure for table `craft_supertableblocktypes`
--

DROP TABLE IF EXISTS `craft_supertableblocktypes`;
CREATE TABLE `craft_supertableblocktypes` (
  `id` int(11) NOT NULL,
  `fieldId` int(11) NOT NULL,
  `fieldLayoutId` int(11) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_supertableblocktypes`
--

INSERT INTO `craft_supertableblocktypes` (`id`, `fieldId`, `fieldLayoutId`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 4, 17, '2017-09-22 15:59:35', '2017-09-22 16:17:02', '0788152c-e7fe-44ef-a4da-02a680f8dd0c'),
(2, 7, 18, '2017-09-22 15:59:35', '2017-09-22 16:17:02', '36b9379d-036c-4252-a950-3ba7724fc2f3');

-- --------------------------------------------------------

--
-- Table structure for table `craft_supertablecontent_1_image`
--

DROP TABLE IF EXISTS `craft_supertablecontent_1_image`;
CREATE TABLE `craft_supertablecontent_1_image` (
  `id` int(11) NOT NULL,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_supertablecontent_1_image`
--

INSERT INTO `craft_supertablecontent_1_image` (`id`, `elementId`, `locale`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 6, 'en_us', '2017-09-22 16:05:50', '2017-09-22 16:17:37', '462477b8-6547-4869-9344-89e04fc7fb75');

-- --------------------------------------------------------

--
-- Table structure for table `craft_supertablecontent_1_video`
--

DROP TABLE IF EXISTS `craft_supertablecontent_1_video`;
CREATE TABLE `craft_supertablecontent_1_video` (
  `id` int(11) NOT NULL,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_systemsettings`
--

DROP TABLE IF EXISTS `craft_systemsettings`;
CREATE TABLE `craft_systemsettings` (
  `id` int(11) NOT NULL,
  `category` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `settings` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_systemsettings`
--

INSERT INTO `craft_systemsettings` (`id`, `category`, `settings`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 'email', '{"protocol":"php","emailAddress":"adam.soffer@digitalsurgeons.com","senderName":"Boilerplate Craftcms"}', '2017-09-22 15:46:54', '2017-09-22 15:46:54', '076d2c06-03cf-47bf-a794-9f52250a7373');

-- --------------------------------------------------------

--
-- Table structure for table `craft_taggroups`
--

DROP TABLE IF EXISTS `craft_taggroups`;
CREATE TABLE `craft_taggroups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fieldLayoutId` int(10) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_taggroups`
--

INSERT INTO `craft_taggroups` (`id`, `name`, `handle`, `fieldLayoutId`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 'Default', 'default', 1, '2017-09-22 15:46:54', '2017-09-22 15:46:54', 'd8151e1e-c0ce-438f-beb7-4a9ed7638ba1');

-- --------------------------------------------------------

--
-- Table structure for table `craft_tags`
--

DROP TABLE IF EXISTS `craft_tags`;
CREATE TABLE `craft_tags` (
  `id` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_tasks`
--

DROP TABLE IF EXISTS `craft_tasks`;
CREATE TABLE `craft_tasks` (
  `id` int(11) NOT NULL,
  `root` int(11) unsigned DEFAULT NULL,
  `lft` int(11) unsigned NOT NULL,
  `rgt` int(11) unsigned NOT NULL,
  `level` smallint(6) unsigned NOT NULL,
  `currentStep` int(11) unsigned DEFAULT NULL,
  `totalSteps` int(11) unsigned DEFAULT NULL,
  `status` enum('pending','error','running') COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `settings` mediumtext COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_templatecachecriteria`
--

DROP TABLE IF EXISTS `craft_templatecachecriteria`;
CREATE TABLE `craft_templatecachecriteria` (
  `id` int(11) NOT NULL,
  `cacheId` int(11) NOT NULL,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `criteria` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_templatecacheelements`
--

DROP TABLE IF EXISTS `craft_templatecacheelements`;
CREATE TABLE `craft_templatecacheelements` (
  `cacheId` int(11) NOT NULL,
  `elementId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_templatecaches`
--

DROP TABLE IF EXISTS `craft_templatecaches`;
CREATE TABLE `craft_templatecaches` (
  `id` int(11) NOT NULL,
  `cacheKey` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expiryDate` datetime NOT NULL,
  `body` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_tinyimage_assets_ignored`
--

DROP TABLE IF EXISTS `craft_tinyimage_assets_ignored`;
CREATE TABLE `craft_tinyimage_assets_ignored` (
  `id` int(11) NOT NULL,
  `assetId` int(10) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_tokens`
--

DROP TABLE IF EXISTS `craft_tokens`;
CREATE TABLE `craft_tokens` (
  `id` int(11) NOT NULL,
  `token` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `route` text COLLATE utf8_unicode_ci,
  `usageLimit` tinyint(3) unsigned DEFAULT NULL,
  `usageCount` tinyint(3) unsigned DEFAULT NULL,
  `expiryDate` datetime NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_usergroups`
--

DROP TABLE IF EXISTS `craft_usergroups`;
CREATE TABLE `craft_usergroups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_usergroups_users`
--

DROP TABLE IF EXISTS `craft_usergroups_users`;
CREATE TABLE `craft_usergroups_users` (
  `id` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_userpermissions`
--

DROP TABLE IF EXISTS `craft_userpermissions`;
CREATE TABLE `craft_userpermissions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_userpermissions_usergroups`
--

DROP TABLE IF EXISTS `craft_userpermissions_usergroups`;
CREATE TABLE `craft_userpermissions_usergroups` (
  `id` int(11) NOT NULL,
  `permissionId` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_userpermissions_users`
--

DROP TABLE IF EXISTS `craft_userpermissions_users`;
CREATE TABLE `craft_userpermissions_users` (
  `id` int(11) NOT NULL,
  `permissionId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `craft_users`
--

DROP TABLE IF EXISTS `craft_users`;
CREATE TABLE `craft_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `preferredLocale` char(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `weekStartDay` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `client` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `suspended` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pending` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `archived` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lastLoginDate` datetime DEFAULT NULL,
  `lastLoginAttemptIPAddress` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invalidLoginWindowStart` datetime DEFAULT NULL,
  `invalidLoginCount` tinyint(4) unsigned DEFAULT NULL,
  `lastInvalidLoginDate` datetime DEFAULT NULL,
  `lockoutDate` datetime DEFAULT NULL,
  `verificationCode` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verificationCodeIssuedDate` datetime DEFAULT NULL,
  `unverifiedEmail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passwordResetRequired` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lastPasswordChangeDate` datetime DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_users`
--

INSERT INTO `craft_users` (`id`, `username`, `photo`, `firstName`, `lastName`, `email`, `password`, `preferredLocale`, `weekStartDay`, `admin`, `client`, `locked`, `suspended`, `pending`, `archived`, `lastLoginDate`, `lastLoginAttemptIPAddress`, `invalidLoginWindowStart`, `invalidLoginCount`, `lastInvalidLoginDate`, `lockoutDate`, `verificationCode`, `verificationCodeIssuedDate`, `unverifiedEmail`, `passwordResetRequired`, `lastPasswordChangeDate`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 'adam.soffer', NULL, NULL, NULL, 'adam.soffer@digitalsurgeons.com', '$2y$13$mmUFHYBmHVU361rMC3Yicuh9bn3GML8JIOfkDFM9W8fZRguM4x/eG', NULL, 0, 1, 0, 0, 0, 0, 0, '2017-09-22 15:46:54', '::1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2017-09-22 15:46:50', '2017-09-22 15:46:50', '2017-09-22 15:46:54', '6c76b85c-b38b-4bca-8d34-f4322973561c');

-- --------------------------------------------------------

--
-- Table structure for table `craft_widgets`
--

DROP TABLE IF EXISTS `craft_widgets`;
CREATE TABLE `craft_widgets` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `colspan` tinyint(4) unsigned DEFAULT NULL,
  `settings` text COLLATE utf8_unicode_ci,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `craft_widgets`
--

INSERT INTO `craft_widgets` (`id`, `userId`, `type`, `sortOrder`, `colspan`, `settings`, `enabled`, `dateCreated`, `dateUpdated`, `uid`) VALUES
(1, 1, 'RecentEntries', 1, NULL, NULL, 1, '2017-09-22 15:46:59', '2017-09-22 15:46:59', '9f1f6095-38be-4535-9830-25a0e55e70ef'),
(2, 1, 'GetHelp', 2, NULL, NULL, 1, '2017-09-22 15:46:59', '2017-09-22 15:46:59', 'e8875b22-8306-4ccb-8f18-ebccd4fdd690'),
(3, 1, 'Updates', 3, NULL, NULL, 1, '2017-09-22 15:46:59', '2017-09-22 15:46:59', 'a9544369-edd3-4abe-a15e-336f905100b0'),
(4, 1, 'Feed', 4, NULL, '{"url":"https:\\/\\/craftcms.com\\/news.rss","title":"Craft News"}', 1, '2017-09-22 15:46:59', '2017-09-22 15:46:59', 'b6f1816b-fa8e-4d31-a210-2a57c15da99d');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `craft_assetfiles`
--
ALTER TABLE `craft_assetfiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_assetfiles_filename_folderId_unq_idx` (`filename`,`folderId`),
  ADD KEY `craft_assetfiles_sourceId_fk` (`sourceId`),
  ADD KEY `craft_assetfiles_folderId_fk` (`folderId`);

--
-- Indexes for table `craft_assetfolders`
--
ALTER TABLE `craft_assetfolders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_assetfolders_name_parentId_sourceId_unq_idx` (`name`,`parentId`,`sourceId`),
  ADD KEY `craft_assetfolders_parentId_fk` (`parentId`),
  ADD KEY `craft_assetfolders_sourceId_fk` (`sourceId`);

--
-- Indexes for table `craft_assetindexdata`
--
ALTER TABLE `craft_assetindexdata`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_assetindexdata_sessionId_sourceId_offset_unq_idx` (`sessionId`,`sourceId`,`offset`),
  ADD KEY `craft_assetindexdata_sourceId_fk` (`sourceId`);

--
-- Indexes for table `craft_assetsources`
--
ALTER TABLE `craft_assetsources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_assetsources_name_unq_idx` (`name`),
  ADD UNIQUE KEY `craft_assetsources_handle_unq_idx` (`handle`),
  ADD KEY `craft_assetsources_fieldLayoutId_fk` (`fieldLayoutId`);

--
-- Indexes for table `craft_assettransformindex`
--
ALTER TABLE `craft_assettransformindex`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_assettransformindex_sourceId_fileId_location_idx` (`sourceId`,`fileId`,`location`);

--
-- Indexes for table `craft_assettransforms`
--
ALTER TABLE `craft_assettransforms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_assettransforms_name_unq_idx` (`name`),
  ADD UNIQUE KEY `craft_assettransforms_handle_unq_idx` (`handle`);

--
-- Indexes for table `craft_categories`
--
ALTER TABLE `craft_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_categories_groupId_fk` (`groupId`);

--
-- Indexes for table `craft_categorygroups`
--
ALTER TABLE `craft_categorygroups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_categorygroups_name_unq_idx` (`name`),
  ADD UNIQUE KEY `craft_categorygroups_handle_unq_idx` (`handle`),
  ADD KEY `craft_categorygroups_structureId_fk` (`structureId`),
  ADD KEY `craft_categorygroups_fieldLayoutId_fk` (`fieldLayoutId`);

--
-- Indexes for table `craft_categorygroups_i18n`
--
ALTER TABLE `craft_categorygroups_i18n`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_categorygroups_i18n_groupId_locale_unq_idx` (`groupId`,`locale`),
  ADD KEY `craft_categorygroups_i18n_locale_fk` (`locale`);

--
-- Indexes for table `craft_content`
--
ALTER TABLE `craft_content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_content_elementId_locale_unq_idx` (`elementId`,`locale`),
  ADD KEY `craft_content_title_idx` (`title`),
  ADD KEY `craft_content_locale_fk` (`locale`);

--
-- Indexes for table `craft_deprecationerrors`
--
ALTER TABLE `craft_deprecationerrors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_deprecationerrors_key_fingerprint_unq_idx` (`key`,`fingerprint`);

--
-- Indexes for table `craft_elementindexsettings`
--
ALTER TABLE `craft_elementindexsettings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_elementindexsettings_type_unq_idx` (`type`);

--
-- Indexes for table `craft_elements`
--
ALTER TABLE `craft_elements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_elements_type_idx` (`type`),
  ADD KEY `craft_elements_enabled_idx` (`enabled`),
  ADD KEY `craft_elements_archived_dateCreated_idx` (`archived`,`dateCreated`);

--
-- Indexes for table `craft_elements_i18n`
--
ALTER TABLE `craft_elements_i18n`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_elements_i18n_elementId_locale_unq_idx` (`elementId`,`locale`),
  ADD UNIQUE KEY `craft_elements_i18n_uri_locale_unq_idx` (`uri`,`locale`),
  ADD KEY `craft_elements_i18n_slug_locale_idx` (`slug`,`locale`),
  ADD KEY `craft_elements_i18n_enabled_idx` (`enabled`),
  ADD KEY `craft_elements_i18n_locale_fk` (`locale`);

--
-- Indexes for table `craft_emailmessages`
--
ALTER TABLE `craft_emailmessages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_emailmessages_key_locale_unq_idx` (`key`,`locale`),
  ADD KEY `craft_emailmessages_locale_fk` (`locale`);

--
-- Indexes for table `craft_entries`
--
ALTER TABLE `craft_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_entries_sectionId_idx` (`sectionId`),
  ADD KEY `craft_entries_typeId_idx` (`typeId`),
  ADD KEY `craft_entries_postDate_idx` (`postDate`),
  ADD KEY `craft_entries_expiryDate_idx` (`expiryDate`),
  ADD KEY `craft_entries_authorId_fk` (`authorId`);

--
-- Indexes for table `craft_entrydrafts`
--
ALTER TABLE `craft_entrydrafts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_entrydrafts_entryId_locale_idx` (`entryId`,`locale`),
  ADD KEY `craft_entrydrafts_sectionId_fk` (`sectionId`),
  ADD KEY `craft_entrydrafts_creatorId_fk` (`creatorId`),
  ADD KEY `craft_entrydrafts_locale_fk` (`locale`);

--
-- Indexes for table `craft_entrytypes`
--
ALTER TABLE `craft_entrytypes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_entrytypes_name_sectionId_unq_idx` (`name`,`sectionId`),
  ADD UNIQUE KEY `craft_entrytypes_handle_sectionId_unq_idx` (`handle`,`sectionId`),
  ADD KEY `craft_entrytypes_sectionId_fk` (`sectionId`),
  ADD KEY `craft_entrytypes_fieldLayoutId_fk` (`fieldLayoutId`);

--
-- Indexes for table `craft_entryversions`
--
ALTER TABLE `craft_entryversions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_entryversions_entryId_locale_idx` (`entryId`,`locale`),
  ADD KEY `craft_entryversions_sectionId_fk` (`sectionId`),
  ADD KEY `craft_entryversions_creatorId_fk` (`creatorId`),
  ADD KEY `craft_entryversions_locale_fk` (`locale`);

--
-- Indexes for table `craft_fieldgroups`
--
ALTER TABLE `craft_fieldgroups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_fieldgroups_name_unq_idx` (`name`);

--
-- Indexes for table `craft_fieldlayoutfields`
--
ALTER TABLE `craft_fieldlayoutfields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_fieldlayoutfields_layoutId_fieldId_unq_idx` (`layoutId`,`fieldId`),
  ADD KEY `craft_fieldlayoutfields_sortOrder_idx` (`sortOrder`),
  ADD KEY `craft_fieldlayoutfields_tabId_fk` (`tabId`),
  ADD KEY `craft_fieldlayoutfields_fieldId_fk` (`fieldId`);

--
-- Indexes for table `craft_fieldlayouts`
--
ALTER TABLE `craft_fieldlayouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_fieldlayouts_type_idx` (`type`);

--
-- Indexes for table `craft_fieldlayouttabs`
--
ALTER TABLE `craft_fieldlayouttabs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_fieldlayouttabs_sortOrder_idx` (`sortOrder`),
  ADD KEY `craft_fieldlayouttabs_layoutId_fk` (`layoutId`);

--
-- Indexes for table `craft_fields`
--
ALTER TABLE `craft_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_fields_handle_context_unq_idx` (`handle`,`context`),
  ADD KEY `craft_fields_context_idx` (`context`),
  ADD KEY `craft_fields_groupId_fk` (`groupId`);

--
-- Indexes for table `craft_freeform_crm_fields`
--
ALTER TABLE `craft_freeform_crm_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_freeform_crm_fields_integrationId_handle_unq_idx` (`integrationId`,`handle`);

--
-- Indexes for table `craft_freeform_export_settings`
--
ALTER TABLE `craft_freeform_export_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_freeform_export_settings_userId_fk` (`userId`);

--
-- Indexes for table `craft_freeform_fields`
--
ALTER TABLE `craft_freeform_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_freeform_fields_handle_unq_idx` (`handle`),
  ADD KEY `craft_freeform_fields_notificationId_fk` (`notificationId`),
  ADD KEY `craft_freeform_fields_assetSourceId_fk` (`assetSourceId`);

--
-- Indexes for table `craft_freeform_forms`
--
ALTER TABLE `craft_freeform_forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_freeform_forms_handle_unq_idx` (`handle`);

--
-- Indexes for table `craft_freeform_integrations`
--
ALTER TABLE `craft_freeform_integrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_freeform_integrations_class_handle_unq_idx` (`class`,`handle`),
  ADD UNIQUE KEY `craft_freeform_integrations_handle_unq_idx` (`handle`);

--
-- Indexes for table `craft_freeform_mailing_lists`
--
ALTER TABLE `craft_freeform_mailing_lists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_freeform_mailing_lists_integrationId_resourceId_unq_idx` (`integrationId`,`resourceId`);

--
-- Indexes for table `craft_freeform_mailing_list_fields`
--
ALTER TABLE `craft_freeform_mailing_list_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_freeform_mailing_list_fields_mailingListId_handle_unq_idx` (`mailingListId`,`handle`);

--
-- Indexes for table `craft_freeform_notifications`
--
ALTER TABLE `craft_freeform_notifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_freeform_notifications_handle_unq_idx` (`handle`);

--
-- Indexes for table `craft_freeform_statuses`
--
ALTER TABLE `craft_freeform_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_freeform_statuses_name_unq_idx` (`name`),
  ADD UNIQUE KEY `craft_freeform_statuses_handle_unq_idx` (`handle`);

--
-- Indexes for table `craft_freeform_submissions`
--
ALTER TABLE `craft_freeform_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_freeform_submissions_incrementalId_idx` (`incrementalId`),
  ADD KEY `craft_freeform_submissions_statusId_fk` (`statusId`),
  ADD KEY `craft_freeform_submissions_formId_fk` (`formId`);

--
-- Indexes for table `craft_freeform_unfinalized_files`
--
ALTER TABLE `craft_freeform_unfinalized_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_freeform_unfinalized_files_assetId_fk` (`assetId`);

--
-- Indexes for table `craft_globalsets`
--
ALTER TABLE `craft_globalsets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_globalsets_name_unq_idx` (`name`),
  ADD UNIQUE KEY `craft_globalsets_handle_unq_idx` (`handle`),
  ADD KEY `craft_globalsets_fieldLayoutId_fk` (`fieldLayoutId`);

--
-- Indexes for table `craft_info`
--
ALTER TABLE `craft_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `craft_jobscore`
--
ALTER TABLE `craft_jobscore`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_jobscore_title_unq_idx` (`title`);

--
-- Indexes for table `craft_locales`
--
ALTER TABLE `craft_locales`
  ADD PRIMARY KEY (`locale`),
  ADD KEY `craft_locales_sortOrder_idx` (`sortOrder`);

--
-- Indexes for table `craft_matrixblocks`
--
ALTER TABLE `craft_matrixblocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_matrixblocks_ownerId_idx` (`ownerId`),
  ADD KEY `craft_matrixblocks_fieldId_idx` (`fieldId`),
  ADD KEY `craft_matrixblocks_typeId_idx` (`typeId`),
  ADD KEY `craft_matrixblocks_sortOrder_idx` (`sortOrder`),
  ADD KEY `craft_matrixblocks_ownerLocale_fk` (`ownerLocale`);

--
-- Indexes for table `craft_matrixblocktypes`
--
ALTER TABLE `craft_matrixblocktypes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_matrixblocktypes_name_fieldId_unq_idx` (`name`,`fieldId`),
  ADD UNIQUE KEY `craft_matrixblocktypes_handle_fieldId_unq_idx` (`handle`,`fieldId`),
  ADD KEY `craft_matrixblocktypes_fieldId_fk` (`fieldId`),
  ADD KEY `craft_matrixblocktypes_fieldLayoutId_fk` (`fieldLayoutId`);

--
-- Indexes for table `craft_matrixcontent_components`
--
ALTER TABLE `craft_matrixcontent_components`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_matrixcontent_components_elementId_locale_unq_idx` (`elementId`,`locale`),
  ADD KEY `craft_matrixcontent_components_locale_fk` (`locale`);

--
-- Indexes for table `craft_migrations`
--
ALTER TABLE `craft_migrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_migrations_version_unq_idx` (`version`),
  ADD KEY `craft_migrations_pluginId_fk` (`pluginId`);

--
-- Indexes for table `craft_navee_navigations`
--
ALTER TABLE `craft_navee_navigations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_navee_navigations_name_unq_idx` (`name`),
  ADD UNIQUE KEY `craft_navee_navigations_handle_unq_idx` (`handle`),
  ADD KEY `craft_navee_navigations_fieldLayoutId_fk` (`fieldLayoutId`),
  ADD KEY `craft_navee_navigations_creatorId_fk` (`creatorId`);

--
-- Indexes for table `craft_navee_nodes`
--
ALTER TABLE `craft_navee_nodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_navee_nodes_navigationId_fk` (`navigationId`);

--
-- Indexes for table `craft_oauth_providers`
--
ALTER TABLE `craft_oauth_providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_oauth_providers_class_unq_idx` (`class`);

--
-- Indexes for table `craft_oauth_tokens`
--
ALTER TABLE `craft_oauth_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `craft_picpuller_authorizations`
--
ALTER TABLE `craft_picpuller_authorizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `craft_plugins`
--
ALTER TABLE `craft_plugins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `craft_rackspaceaccess`
--
ALTER TABLE `craft_rackspaceaccess`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_rackspaceaccess_connectionKey_unq_idx` (`connectionKey`);

--
-- Indexes for table `craft_relations`
--
ALTER TABLE `craft_relations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_relations_fieldId_sourceId_sourceLocale_targetId_unq_idx` (`fieldId`,`sourceId`,`sourceLocale`,`targetId`),
  ADD KEY `craft_relations_sourceId_fk` (`sourceId`),
  ADD KEY `craft_relations_sourceLocale_fk` (`sourceLocale`),
  ADD KEY `craft_relations_targetId_fk` (`targetId`);

--
-- Indexes for table `craft_reroute`
--
ALTER TABLE `craft_reroute`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `craft_routes`
--
ALTER TABLE `craft_routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_routes_locale_idx` (`locale`),
  ADD KEY `craft_routes_urlPattern_idx` (`urlPattern`);

--
-- Indexes for table `craft_searchindex`
--
ALTER TABLE `craft_searchindex`
  ADD PRIMARY KEY (`elementId`,`attribute`,`fieldId`,`locale`),
  ADD FULLTEXT KEY `craft_searchindex_keywords_idx` (`keywords`);

--
-- Indexes for table `craft_sections`
--
ALTER TABLE `craft_sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_sections_name_unq_idx` (`name`),
  ADD UNIQUE KEY `craft_sections_handle_unq_idx` (`handle`),
  ADD KEY `craft_sections_structureId_fk` (`structureId`);

--
-- Indexes for table `craft_sections_i18n`
--
ALTER TABLE `craft_sections_i18n`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_sections_i18n_sectionId_locale_unq_idx` (`sectionId`,`locale`),
  ADD KEY `craft_sections_i18n_locale_fk` (`locale`);

--
-- Indexes for table `craft_seomatic_meta`
--
ALTER TABLE `craft_seomatic_meta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_seomatic_meta_seoImageId_fk` (`seoImageId`),
  ADD KEY `craft_seomatic_meta_seoTwitterImageId_fk` (`seoTwitterImageId`),
  ADD KEY `craft_seomatic_meta_seoFacebookImageId_fk` (`seoFacebookImageId`);

--
-- Indexes for table `craft_seomatic_settings`
--
ALTER TABLE `craft_seomatic_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_seomatic_settings_siteSeoImageId_fk` (`siteSeoImageId`),
  ADD KEY `craft_seomatic_settings_siteSeoTwitterImageId_fk` (`siteSeoTwitterImageId`),
  ADD KEY `craft_seomatic_settings_siteSeoFacebookImageId_fk` (`siteSeoFacebookImageId`),
  ADD KEY `craft_seomatic_settings_genericOwnerImageId_fk` (`genericOwnerImageId`),
  ADD KEY `craft_seomatic_settings_genericCreatorImageId_fk` (`genericCreatorImageId`);

--
-- Indexes for table `craft_sessions`
--
ALTER TABLE `craft_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_sessions_uid_idx` (`uid`),
  ADD KEY `craft_sessions_token_idx` (`token`),
  ADD KEY `craft_sessions_dateUpdated_idx` (`dateUpdated`),
  ADD KEY `craft_sessions_userId_fk` (`userId`);

--
-- Indexes for table `craft_shunnedmessages`
--
ALTER TABLE `craft_shunnedmessages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_shunnedmessages_userId_message_unq_idx` (`userId`,`message`);

--
-- Indexes for table `craft_structureelements`
--
ALTER TABLE `craft_structureelements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_structureelements_structureId_elementId_unq_idx` (`structureId`,`elementId`),
  ADD KEY `craft_structureelements_root_idx` (`root`),
  ADD KEY `craft_structureelements_lft_idx` (`lft`),
  ADD KEY `craft_structureelements_rgt_idx` (`rgt`),
  ADD KEY `craft_structureelements_level_idx` (`level`),
  ADD KEY `craft_structureelements_elementId_fk` (`elementId`);

--
-- Indexes for table `craft_structures`
--
ALTER TABLE `craft_structures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `craft_supertableblocks`
--
ALTER TABLE `craft_supertableblocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_supertableblocks_ownerId_idx` (`ownerId`),
  ADD KEY `craft_supertableblocks_fieldId_idx` (`fieldId`),
  ADD KEY `craft_supertableblocks_typeId_idx` (`typeId`),
  ADD KEY `craft_supertableblocks_sortOrder_idx` (`sortOrder`),
  ADD KEY `craft_supertableblocks_ownerLocale_fk` (`ownerLocale`);

--
-- Indexes for table `craft_supertableblocktypes`
--
ALTER TABLE `craft_supertableblocktypes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_supertableblocktypes_fieldId_fk` (`fieldId`),
  ADD KEY `craft_supertableblocktypes_fieldLayoutId_fk` (`fieldLayoutId`);

--
-- Indexes for table `craft_supertablecontent_1_image`
--
ALTER TABLE `craft_supertablecontent_1_image`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_supertablecontent_1_image_elementId_locale_unq_idx` (`elementId`,`locale`),
  ADD KEY `craft_supertablecontent_1_image_locale_fk` (`locale`);

--
-- Indexes for table `craft_supertablecontent_1_video`
--
ALTER TABLE `craft_supertablecontent_1_video`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_supertablecontent_1_video_elementId_locale_unq_idx` (`elementId`,`locale`),
  ADD KEY `craft_supertablecontent_1_video_locale_fk` (`locale`);

--
-- Indexes for table `craft_systemsettings`
--
ALTER TABLE `craft_systemsettings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_systemsettings_category_unq_idx` (`category`);

--
-- Indexes for table `craft_taggroups`
--
ALTER TABLE `craft_taggroups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_taggroups_name_unq_idx` (`name`),
  ADD UNIQUE KEY `craft_taggroups_handle_unq_idx` (`handle`),
  ADD KEY `craft_taggroups_fieldLayoutId_fk` (`fieldLayoutId`);

--
-- Indexes for table `craft_tags`
--
ALTER TABLE `craft_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_tags_groupId_fk` (`groupId`);

--
-- Indexes for table `craft_tasks`
--
ALTER TABLE `craft_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_tasks_root_idx` (`root`),
  ADD KEY `craft_tasks_lft_idx` (`lft`),
  ADD KEY `craft_tasks_rgt_idx` (`rgt`),
  ADD KEY `craft_tasks_level_idx` (`level`);

--
-- Indexes for table `craft_templatecachecriteria`
--
ALTER TABLE `craft_templatecachecriteria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_templatecachecriteria_cacheId_fk` (`cacheId`),
  ADD KEY `craft_templatecachecriteria_type_idx` (`type`);

--
-- Indexes for table `craft_templatecacheelements`
--
ALTER TABLE `craft_templatecacheelements`
  ADD KEY `craft_templatecacheelements_cacheId_fk` (`cacheId`),
  ADD KEY `craft_templatecacheelements_elementId_fk` (`elementId`);

--
-- Indexes for table `craft_templatecaches`
--
ALTER TABLE `craft_templatecaches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_templatecaches_expiryDate_cacheKey_locale_path_idx` (`expiryDate`,`cacheKey`,`locale`,`path`),
  ADD KEY `craft_templatecaches_locale_fk` (`locale`);

--
-- Indexes for table `craft_tinyimage_assets_ignored`
--
ALTER TABLE `craft_tinyimage_assets_ignored`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `craft_tokens`
--
ALTER TABLE `craft_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_tokens_token_unq_idx` (`token`),
  ADD KEY `craft_tokens_expiryDate_idx` (`expiryDate`);

--
-- Indexes for table `craft_usergroups`
--
ALTER TABLE `craft_usergroups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_usergroups_name_unq_idx` (`name`),
  ADD UNIQUE KEY `craft_usergroups_handle_unq_idx` (`handle`);

--
-- Indexes for table `craft_usergroups_users`
--
ALTER TABLE `craft_usergroups_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_usergroups_users_groupId_userId_unq_idx` (`groupId`,`userId`),
  ADD KEY `craft_usergroups_users_userId_fk` (`userId`);

--
-- Indexes for table `craft_userpermissions`
--
ALTER TABLE `craft_userpermissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_userpermissions_name_unq_idx` (`name`);

--
-- Indexes for table `craft_userpermissions_usergroups`
--
ALTER TABLE `craft_userpermissions_usergroups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_userpermissions_usergroups_permissionId_groupId_unq_idx` (`permissionId`,`groupId`),
  ADD KEY `craft_userpermissions_usergroups_groupId_fk` (`groupId`);

--
-- Indexes for table `craft_userpermissions_users`
--
ALTER TABLE `craft_userpermissions_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_userpermissions_users_permissionId_userId_unq_idx` (`permissionId`,`userId`),
  ADD KEY `craft_userpermissions_users_userId_fk` (`userId`);

--
-- Indexes for table `craft_users`
--
ALTER TABLE `craft_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `craft_users_username_unq_idx` (`username`),
  ADD UNIQUE KEY `craft_users_email_unq_idx` (`email`),
  ADD KEY `craft_users_verificationCode_idx` (`verificationCode`),
  ADD KEY `craft_users_uid_idx` (`uid`),
  ADD KEY `craft_users_preferredLocale_fk` (`preferredLocale`);

--
-- Indexes for table `craft_widgets`
--
ALTER TABLE `craft_widgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_widgets_userId_fk` (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `craft_assetfolders`
--
ALTER TABLE `craft_assetfolders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `craft_assetindexdata`
--
ALTER TABLE `craft_assetindexdata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_assetsources`
--
ALTER TABLE `craft_assetsources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `craft_assettransformindex`
--
ALTER TABLE `craft_assettransformindex`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_assettransforms`
--
ALTER TABLE `craft_assettransforms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_categorygroups`
--
ALTER TABLE `craft_categorygroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_categorygroups_i18n`
--
ALTER TABLE `craft_categorygroups_i18n`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_content`
--
ALTER TABLE `craft_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `craft_deprecationerrors`
--
ALTER TABLE `craft_deprecationerrors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_elementindexsettings`
--
ALTER TABLE `craft_elementindexsettings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_elements`
--
ALTER TABLE `craft_elements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `craft_elements_i18n`
--
ALTER TABLE `craft_elements_i18n`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `craft_emailmessages`
--
ALTER TABLE `craft_emailmessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_entrydrafts`
--
ALTER TABLE `craft_entrydrafts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_entrytypes`
--
ALTER TABLE `craft_entrytypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `craft_entryversions`
--
ALTER TABLE `craft_entryversions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `craft_fieldgroups`
--
ALTER TABLE `craft_fieldgroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `craft_fieldlayoutfields`
--
ALTER TABLE `craft_fieldlayoutfields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `craft_fieldlayouts`
--
ALTER TABLE `craft_fieldlayouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `craft_fieldlayouttabs`
--
ALTER TABLE `craft_fieldlayouttabs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `craft_fields`
--
ALTER TABLE `craft_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `craft_freeform_crm_fields`
--
ALTER TABLE `craft_freeform_crm_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_freeform_export_settings`
--
ALTER TABLE `craft_freeform_export_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_freeform_fields`
--
ALTER TABLE `craft_freeform_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `craft_freeform_forms`
--
ALTER TABLE `craft_freeform_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_freeform_integrations`
--
ALTER TABLE `craft_freeform_integrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_freeform_mailing_lists`
--
ALTER TABLE `craft_freeform_mailing_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_freeform_mailing_list_fields`
--
ALTER TABLE `craft_freeform_mailing_list_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_freeform_notifications`
--
ALTER TABLE `craft_freeform_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_freeform_statuses`
--
ALTER TABLE `craft_freeform_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `craft_freeform_unfinalized_files`
--
ALTER TABLE `craft_freeform_unfinalized_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_info`
--
ALTER TABLE `craft_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `craft_jobscore`
--
ALTER TABLE `craft_jobscore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_matrixblocktypes`
--
ALTER TABLE `craft_matrixblocktypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `craft_matrixcontent_components`
--
ALTER TABLE `craft_matrixcontent_components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `craft_migrations`
--
ALTER TABLE `craft_migrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT for table `craft_navee_navigations`
--
ALTER TABLE `craft_navee_navigations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_oauth_providers`
--
ALTER TABLE `craft_oauth_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_oauth_tokens`
--
ALTER TABLE `craft_oauth_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_picpuller_authorizations`
--
ALTER TABLE `craft_picpuller_authorizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_plugins`
--
ALTER TABLE `craft_plugins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `craft_rackspaceaccess`
--
ALTER TABLE `craft_rackspaceaccess`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_relations`
--
ALTER TABLE `craft_relations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `craft_reroute`
--
ALTER TABLE `craft_reroute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_routes`
--
ALTER TABLE `craft_routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_sections`
--
ALTER TABLE `craft_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `craft_sections_i18n`
--
ALTER TABLE `craft_sections_i18n`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `craft_seomatic_settings`
--
ALTER TABLE `craft_seomatic_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `craft_sessions`
--
ALTER TABLE `craft_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `craft_shunnedmessages`
--
ALTER TABLE `craft_shunnedmessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_structureelements`
--
ALTER TABLE `craft_structureelements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_structures`
--
ALTER TABLE `craft_structures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_supertableblocktypes`
--
ALTER TABLE `craft_supertableblocktypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `craft_supertablecontent_1_image`
--
ALTER TABLE `craft_supertablecontent_1_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `craft_supertablecontent_1_video`
--
ALTER TABLE `craft_supertablecontent_1_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_systemsettings`
--
ALTER TABLE `craft_systemsettings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `craft_taggroups`
--
ALTER TABLE `craft_taggroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `craft_tasks`
--
ALTER TABLE `craft_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_templatecachecriteria`
--
ALTER TABLE `craft_templatecachecriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_templatecaches`
--
ALTER TABLE `craft_templatecaches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_tinyimage_assets_ignored`
--
ALTER TABLE `craft_tinyimage_assets_ignored`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_tokens`
--
ALTER TABLE `craft_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_usergroups`
--
ALTER TABLE `craft_usergroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_usergroups_users`
--
ALTER TABLE `craft_usergroups_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_userpermissions`
--
ALTER TABLE `craft_userpermissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_userpermissions_usergroups`
--
ALTER TABLE `craft_userpermissions_usergroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_userpermissions_users`
--
ALTER TABLE `craft_userpermissions_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `craft_widgets`
--
ALTER TABLE `craft_widgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `craft_assetfiles`
--
ALTER TABLE `craft_assetfiles`
  ADD CONSTRAINT `craft_assetfiles_folderId_fk` FOREIGN KEY (`folderId`) REFERENCES `craft_assetfolders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_assetfiles_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_assetfiles_sourceId_fk` FOREIGN KEY (`sourceId`) REFERENCES `craft_assetsources` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_assetfolders`
--
ALTER TABLE `craft_assetfolders`
  ADD CONSTRAINT `craft_assetfolders_parentId_fk` FOREIGN KEY (`parentId`) REFERENCES `craft_assetfolders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_assetfolders_sourceId_fk` FOREIGN KEY (`sourceId`) REFERENCES `craft_assetsources` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_assetindexdata`
--
ALTER TABLE `craft_assetindexdata`
  ADD CONSTRAINT `craft_assetindexdata_sourceId_fk` FOREIGN KEY (`sourceId`) REFERENCES `craft_assetsources` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_assetsources`
--
ALTER TABLE `craft_assetsources`
  ADD CONSTRAINT `craft_assetsources_fieldLayoutId_fk` FOREIGN KEY (`fieldLayoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `craft_categories`
--
ALTER TABLE `craft_categories`
  ADD CONSTRAINT `craft_categories_groupId_fk` FOREIGN KEY (`groupId`) REFERENCES `craft_categorygroups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_categories_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_categorygroups`
--
ALTER TABLE `craft_categorygroups`
  ADD CONSTRAINT `craft_categorygroups_fieldLayoutId_fk` FOREIGN KEY (`fieldLayoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `craft_categorygroups_structureId_fk` FOREIGN KEY (`structureId`) REFERENCES `craft_structures` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_categorygroups_i18n`
--
ALTER TABLE `craft_categorygroups_i18n`
  ADD CONSTRAINT `craft_categorygroups_i18n_groupId_fk` FOREIGN KEY (`groupId`) REFERENCES `craft_categorygroups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_categorygroups_i18n_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `craft_content`
--
ALTER TABLE `craft_content`
  ADD CONSTRAINT `craft_content_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_content_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `craft_elements_i18n`
--
ALTER TABLE `craft_elements_i18n`
  ADD CONSTRAINT `craft_elements_i18n_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_elements_i18n_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `craft_emailmessages`
--
ALTER TABLE `craft_emailmessages`
  ADD CONSTRAINT `craft_emailmessages_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `craft_entries`
--
ALTER TABLE `craft_entries`
  ADD CONSTRAINT `craft_entries_authorId_fk` FOREIGN KEY (`authorId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_entries_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_entries_sectionId_fk` FOREIGN KEY (`sectionId`) REFERENCES `craft_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_entries_typeId_fk` FOREIGN KEY (`typeId`) REFERENCES `craft_entrytypes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_entrydrafts`
--
ALTER TABLE `craft_entrydrafts`
  ADD CONSTRAINT `craft_entrydrafts_creatorId_fk` FOREIGN KEY (`creatorId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_entrydrafts_entryId_fk` FOREIGN KEY (`entryId`) REFERENCES `craft_entries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_entrydrafts_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `craft_entrydrafts_sectionId_fk` FOREIGN KEY (`sectionId`) REFERENCES `craft_sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_entrytypes`
--
ALTER TABLE `craft_entrytypes`
  ADD CONSTRAINT `craft_entrytypes_fieldLayoutId_fk` FOREIGN KEY (`fieldLayoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `craft_entrytypes_sectionId_fk` FOREIGN KEY (`sectionId`) REFERENCES `craft_sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_entryversions`
--
ALTER TABLE `craft_entryversions`
  ADD CONSTRAINT `craft_entryversions_creatorId_fk` FOREIGN KEY (`creatorId`) REFERENCES `craft_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `craft_entryversions_entryId_fk` FOREIGN KEY (`entryId`) REFERENCES `craft_entries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_entryversions_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `craft_entryversions_sectionId_fk` FOREIGN KEY (`sectionId`) REFERENCES `craft_sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_fieldlayoutfields`
--
ALTER TABLE `craft_fieldlayoutfields`
  ADD CONSTRAINT `craft_fieldlayoutfields_fieldId_fk` FOREIGN KEY (`fieldId`) REFERENCES `craft_fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_fieldlayoutfields_layoutId_fk` FOREIGN KEY (`layoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_fieldlayoutfields_tabId_fk` FOREIGN KEY (`tabId`) REFERENCES `craft_fieldlayouttabs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_fieldlayouttabs`
--
ALTER TABLE `craft_fieldlayouttabs`
  ADD CONSTRAINT `craft_fieldlayouttabs_layoutId_fk` FOREIGN KEY (`layoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_fields`
--
ALTER TABLE `craft_fields`
  ADD CONSTRAINT `craft_fields_groupId_fk` FOREIGN KEY (`groupId`) REFERENCES `craft_fieldgroups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_freeform_crm_fields`
--
ALTER TABLE `craft_freeform_crm_fields`
  ADD CONSTRAINT `craft_freeform_crm_fields_integrationId_fk` FOREIGN KEY (`integrationId`) REFERENCES `craft_freeform_integrations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_freeform_export_settings`
--
ALTER TABLE `craft_freeform_export_settings`
  ADD CONSTRAINT `craft_freeform_export_settings_userId_fk` FOREIGN KEY (`userId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_freeform_fields`
--
ALTER TABLE `craft_freeform_fields`
  ADD CONSTRAINT `craft_freeform_fields_assetSourceId_fk` FOREIGN KEY (`assetSourceId`) REFERENCES `craft_assetsources` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `craft_freeform_fields_notificationId_fk` FOREIGN KEY (`notificationId`) REFERENCES `craft_freeform_notifications` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `craft_freeform_mailing_lists`
--
ALTER TABLE `craft_freeform_mailing_lists`
  ADD CONSTRAINT `craft_freeform_mailing_lists_integrationId_fk` FOREIGN KEY (`integrationId`) REFERENCES `craft_freeform_integrations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_freeform_mailing_list_fields`
--
ALTER TABLE `craft_freeform_mailing_list_fields`
  ADD CONSTRAINT `craft_freeform_mailing_list_fields_mailingListId_fk` FOREIGN KEY (`mailingListId`) REFERENCES `craft_freeform_mailing_lists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_freeform_submissions`
--
ALTER TABLE `craft_freeform_submissions`
  ADD CONSTRAINT `craft_freeform_submissions_formId_fk` FOREIGN KEY (`formId`) REFERENCES `craft_freeform_forms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_freeform_submissions_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_freeform_submissions_statusId_fk` FOREIGN KEY (`statusId`) REFERENCES `craft_freeform_statuses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `craft_freeform_unfinalized_files`
--
ALTER TABLE `craft_freeform_unfinalized_files`
  ADD CONSTRAINT `craft_freeform_unfinalized_files_assetId_fk` FOREIGN KEY (`assetId`) REFERENCES `craft_assetfiles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_globalsets`
--
ALTER TABLE `craft_globalsets`
  ADD CONSTRAINT `craft_globalsets_fieldLayoutId_fk` FOREIGN KEY (`fieldLayoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `craft_globalsets_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_matrixblocks`
--
ALTER TABLE `craft_matrixblocks`
  ADD CONSTRAINT `craft_matrixblocks_fieldId_fk` FOREIGN KEY (`fieldId`) REFERENCES `craft_fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_matrixblocks_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_matrixblocks_ownerId_fk` FOREIGN KEY (`ownerId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_matrixblocks_ownerLocale_fk` FOREIGN KEY (`ownerLocale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `craft_matrixblocks_typeId_fk` FOREIGN KEY (`typeId`) REFERENCES `craft_matrixblocktypes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_matrixblocktypes`
--
ALTER TABLE `craft_matrixblocktypes`
  ADD CONSTRAINT `craft_matrixblocktypes_fieldId_fk` FOREIGN KEY (`fieldId`) REFERENCES `craft_fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_matrixblocktypes_fieldLayoutId_fk` FOREIGN KEY (`fieldLayoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `craft_matrixcontent_components`
--
ALTER TABLE `craft_matrixcontent_components`
  ADD CONSTRAINT `craft_matrixcontent_components_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_matrixcontent_components_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `craft_migrations`
--
ALTER TABLE `craft_migrations`
  ADD CONSTRAINT `craft_migrations_pluginId_fk` FOREIGN KEY (`pluginId`) REFERENCES `craft_plugins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_navee_navigations`
--
ALTER TABLE `craft_navee_navigations`
  ADD CONSTRAINT `craft_navee_navigations_creatorId_fk` FOREIGN KEY (`creatorId`) REFERENCES `craft_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `craft_navee_navigations_fieldLayoutId_fk` FOREIGN KEY (`fieldLayoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `craft_navee_nodes`
--
ALTER TABLE `craft_navee_nodes`
  ADD CONSTRAINT `craft_navee_nodes_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_navee_nodes_navigationId_fk` FOREIGN KEY (`navigationId`) REFERENCES `craft_navee_navigations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_relations`
--
ALTER TABLE `craft_relations`
  ADD CONSTRAINT `craft_relations_fieldId_fk` FOREIGN KEY (`fieldId`) REFERENCES `craft_fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_relations_sourceId_fk` FOREIGN KEY (`sourceId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_relations_sourceLocale_fk` FOREIGN KEY (`sourceLocale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `craft_relations_targetId_fk` FOREIGN KEY (`targetId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_routes`
--
ALTER TABLE `craft_routes`
  ADD CONSTRAINT `craft_routes_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `craft_sections`
--
ALTER TABLE `craft_sections`
  ADD CONSTRAINT `craft_sections_structureId_fk` FOREIGN KEY (`structureId`) REFERENCES `craft_structures` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `craft_sections_i18n`
--
ALTER TABLE `craft_sections_i18n`
  ADD CONSTRAINT `craft_sections_i18n_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `craft_sections_i18n_sectionId_fk` FOREIGN KEY (`sectionId`) REFERENCES `craft_sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_seomatic_meta`
--
ALTER TABLE `craft_seomatic_meta`
  ADD CONSTRAINT `craft_seomatic_meta_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_seomatic_meta_seoFacebookImageId_fk` FOREIGN KEY (`seoFacebookImageId`) REFERENCES `craft_assetfiles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `craft_seomatic_meta_seoImageId_fk` FOREIGN KEY (`seoImageId`) REFERENCES `craft_assetfiles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `craft_seomatic_meta_seoTwitterImageId_fk` FOREIGN KEY (`seoTwitterImageId`) REFERENCES `craft_assetfiles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `craft_seomatic_settings`
--
ALTER TABLE `craft_seomatic_settings`
  ADD CONSTRAINT `craft_seomatic_settings_genericCreatorImageId_fk` FOREIGN KEY (`genericCreatorImageId`) REFERENCES `craft_assetfiles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `craft_seomatic_settings_genericOwnerImageId_fk` FOREIGN KEY (`genericOwnerImageId`) REFERENCES `craft_assetfiles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `craft_seomatic_settings_siteSeoFacebookImageId_fk` FOREIGN KEY (`siteSeoFacebookImageId`) REFERENCES `craft_assetfiles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `craft_seomatic_settings_siteSeoImageId_fk` FOREIGN KEY (`siteSeoImageId`) REFERENCES `craft_assetfiles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `craft_seomatic_settings_siteSeoTwitterImageId_fk` FOREIGN KEY (`siteSeoTwitterImageId`) REFERENCES `craft_assetfiles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `craft_sessions`
--
ALTER TABLE `craft_sessions`
  ADD CONSTRAINT `craft_sessions_userId_fk` FOREIGN KEY (`userId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_shunnedmessages`
--
ALTER TABLE `craft_shunnedmessages`
  ADD CONSTRAINT `craft_shunnedmessages_userId_fk` FOREIGN KEY (`userId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_structureelements`
--
ALTER TABLE `craft_structureelements`
  ADD CONSTRAINT `craft_structureelements_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_structureelements_structureId_fk` FOREIGN KEY (`structureId`) REFERENCES `craft_structures` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_supertableblocks`
--
ALTER TABLE `craft_supertableblocks`
  ADD CONSTRAINT `craft_supertableblocks_fieldId_fk` FOREIGN KEY (`fieldId`) REFERENCES `craft_fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_supertableblocks_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_supertableblocks_ownerId_fk` FOREIGN KEY (`ownerId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_supertableblocks_ownerLocale_fk` FOREIGN KEY (`ownerLocale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `craft_supertableblocks_typeId_fk` FOREIGN KEY (`typeId`) REFERENCES `craft_supertableblocktypes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_supertableblocktypes`
--
ALTER TABLE `craft_supertableblocktypes`
  ADD CONSTRAINT `craft_supertableblocktypes_fieldId_fk` FOREIGN KEY (`fieldId`) REFERENCES `craft_fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_supertableblocktypes_fieldLayoutId_fk` FOREIGN KEY (`fieldLayoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `craft_supertablecontent_1_image`
--
ALTER TABLE `craft_supertablecontent_1_image`
  ADD CONSTRAINT `craft_supertablecontent_1_image_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_supertablecontent_1_image_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `craft_supertablecontent_1_video`
--
ALTER TABLE `craft_supertablecontent_1_video`
  ADD CONSTRAINT `craft_supertablecontent_1_video_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_supertablecontent_1_video_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `craft_taggroups`
--
ALTER TABLE `craft_taggroups`
  ADD CONSTRAINT `craft_taggroups_fieldLayoutId_fk` FOREIGN KEY (`fieldLayoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `craft_tags`
--
ALTER TABLE `craft_tags`
  ADD CONSTRAINT `craft_tags_groupId_fk` FOREIGN KEY (`groupId`) REFERENCES `craft_taggroups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_tags_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_templatecachecriteria`
--
ALTER TABLE `craft_templatecachecriteria`
  ADD CONSTRAINT `craft_templatecachecriteria_cacheId_fk` FOREIGN KEY (`cacheId`) REFERENCES `craft_templatecaches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_templatecacheelements`
--
ALTER TABLE `craft_templatecacheelements`
  ADD CONSTRAINT `craft_templatecacheelements_cacheId_fk` FOREIGN KEY (`cacheId`) REFERENCES `craft_templatecaches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_templatecacheelements_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_templatecaches`
--
ALTER TABLE `craft_templatecaches`
  ADD CONSTRAINT `craft_templatecaches_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `craft_usergroups_users`
--
ALTER TABLE `craft_usergroups_users`
  ADD CONSTRAINT `craft_usergroups_users_groupId_fk` FOREIGN KEY (`groupId`) REFERENCES `craft_usergroups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_usergroups_users_userId_fk` FOREIGN KEY (`userId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_userpermissions_usergroups`
--
ALTER TABLE `craft_userpermissions_usergroups`
  ADD CONSTRAINT `craft_userpermissions_usergroups_groupId_fk` FOREIGN KEY (`groupId`) REFERENCES `craft_usergroups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_userpermissions_usergroups_permissionId_fk` FOREIGN KEY (`permissionId`) REFERENCES `craft_userpermissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_userpermissions_users`
--
ALTER TABLE `craft_userpermissions_users`
  ADD CONSTRAINT `craft_userpermissions_users_permissionId_fk` FOREIGN KEY (`permissionId`) REFERENCES `craft_userpermissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_userpermissions_users_userId_fk` FOREIGN KEY (`userId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `craft_users`
--
ALTER TABLE `craft_users`
  ADD CONSTRAINT `craft_users_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `craft_users_preferredLocale_fk` FOREIGN KEY (`preferredLocale`) REFERENCES `craft_locales` (`locale`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `craft_widgets`
--
ALTER TABLE `craft_widgets`
  ADD CONSTRAINT `craft_widgets_userId_fk` FOREIGN KEY (`userId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
