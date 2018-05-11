/**
 * EXTFOX
 *
 * @category   Application_Extensions
 * @package    Ggcommunity
 * @author     EXTFOX
 */


-- --------------------------------------------------------


--
-- Table structure for table `engine4_ggcommunity_questions`
--

DROP TABLE IF EXISTS `engine4_ggcommunity_questions`;
CREATE TABLE `engine4_ggcommunity_questions` (
  `question_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `photo_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `topic` text COLLATE utf8_unicode_ci NOT NULL,
  `open` tinyint(1) NOT NULL DEFAULT '1',
  `search` tinyint(1) NOT NULL DEFAULT '1',
  `date_closed` datetime DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `sponsored` tinyint(1) NOT NULL DEFAULT '0',
  `view_count` int(11) NOT NULL DEFAULT '0',
  `comment_count` int(11) NOT NULL DEFAULT '0',
  `answer_count` int(11) NOT NULL DEFAULT '0',
  `accepted_answer` tinyint(1) NOT NULL DEFAULT '0',
  `up_vote_count` int(11) NOT NULL DEFAULT '0',
  `down_vote_count` int(11) NOT NULL DEFAULT '0',
  `approved_date` datetime NOT NULL,
  `draft` tinyint(1) NOT NULL DEFAULT '0',
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_ggcommunity_answers`
--

DROP TABLE IF EXISTS `engine4_ggcommunity_answers`;
CREATE TABLE `engine4_ggcommunity_answers` (
  `answer_id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) UNSIGNED NOT NULL,
  `parent_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) UNSIGNED NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `up_vote_count` int(11) NOT NULL DEFAULT '0',
  `down_vote_count` int(11) NOT NULL DEFAULT '0',
  `comment_count` int(11) NOT NULL DEFAULT '0',
  `accepted` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `creation_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`answer_id`),
  KEY `user_id` (`user_id`),
  KEY `parent_type` (`parent_type`),
  KEY `parent_id` (`parent_id`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_ggcommunity_comments`
--

DROP TABLE IF EXISTS `engine4_ggcommunity_comments`;
CREATE TABLE `engine4_ggcommunity_comments` (
  `comment_id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) UNSIGNED NOT NULL,
  `parent_type` enum('ggcommunity_question','ggcommunity_answer','ggcommunity_comment','unknown') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'unknown',
  `parent_id` int(11) UNSIGNED NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_ggcommunity_votes`
--

DROP TABLE IF EXISTS `engine4_ggcommunity_votes`;
CREATE TABLE `engine4_ggcommunity_votes` (
  `vote_id` int(11) unsigned NOT NULL auto_increment,
  `vote_type` tinyint(1) NOT NULL,
  `parent_type` enum('ggcommunity_question','ggcommunity_answer','ggcommunity_comment','unknown') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'unknown',
  `parent_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`vote_id`),
  KEY `user_id` (`user_id`),
  KEY `parent_id` (`parent_id`),
  KEY `parent_type` (`parent_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_ggcommunity_topicmaps`
--

DROP TABLE IF EXISTS `engine4_ggcommunity_topicmaps`;
CREATE TABLE `engine4_ggcommunity_topicmaps` (
  `topicmap_id` int(11) unsigned NOT NULL auto_increment,
  `parent_type` text COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`topicmap_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
--
-- Dumping data for table `engine4_core_menuitems`
--

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES

('core_main_ggcommunity', 'ggcommunity', 'Guidance Guide Community', '', '{"route":"listing_struggles",
"icon":"fa-question-circle"}', 'core_main', '', 25),

('ggcommunity_admin_main_manage', 'ggcommunity', 'GGCOMMUNITY_QUESTIONS', NULL, '{"route":"admin_default","module":"ggcommunity","controller":"manage"}', 'ggcommunity_admin_main', NULL, '3'),
('ggcommunity_admin_main_answer', 'ggcommunity', 'GGCOMMUNITY_ANSWERS', NULL, '{"route":"admin_default","module":"ggcommunity","controller":"manage","action":"answer"}', 'ggcommunity_admin_main', NULL, '4'),
('ggcommunity_admin_main_comment', 'ggcommunity', 'GGCOMMUNITY_COMMENTS', NULL, '{"route":"admin_default","module":"ggcommunity","controller":"manage","action":"comment"}', 'ggcommunity_admin_main', NULL, '5'),
('core_admin_main_plugins_ggcommunity', 'ggcommunity', 'Guidance Guide Community ', NULL, '{"route":"admin_default","module":"ggcommunity","controller":"manage"}', 'core_admin_main_plugins', NULL,'999'),
('ggcommunity_admin_main_settings', 'ggcommunity', 'Global Settings', NULL, '{"route":"admin_default","module":"ggcommunity","controller":"settings"}', 'ggcommunity_admin_main', NULL, '1'),
('ggcommunity_admin_main_level', 'ggcommunity', 'Member Level Settings', NULL, '{"route":"admin_default","module":"ggcommunity","controller":"level"}', 'ggcommunity_admin_main', NULL,'2');

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_settings`
--

INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES

('ggcommunity.answer.page', '10'),
('ggcommunity.question.page', '10'),
('ggcommunity.automatically.close', '80');

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_modules`
--

INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('ggcommunity', 'Guidance Guide Community', '', '4.9.3', 1, 'extra') ;


-- --------------------------------------------------------

--
-- Dumping data for table `engine4_authorization_permissions`
--

-- Insert Permission for SUPER ADMIN,ADMIN and MODERATOR 
-- answer_question, approve_question, best_answer, comment_answer, comment_question, create_question, edit_close_date, edit_question, featured_question, sponsored_question, view_question, vote_answer, vote_question
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'answer_question' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('moderator','admin')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'approve_question' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('moderator','admin')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'best_answer' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('moderator','admin')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'comment_answer' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('moderator','admin')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'comment_question' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('moderator','admin')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'create_question' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('moderator','admin')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'edit_close_date' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('moderator','admin')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'edit_question' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('moderator','admin')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'fetured_question' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('moderator','admin')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'sponsored_question' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('moderator','admin')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'view_question' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('moderator','admin')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'vote_answer' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('moderator','admin')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'vote_question' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('moderator','admin')
;

-- USER and PUBLIC
-- answer_question, approve_question, best_answer, comment_answer, comment_question, create_question, edit_close_date, edit_question, featured_question, sponsored_question, view_question, vote_answer, vote_question
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'answer_question' as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('user','public')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'approve_question' as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('user','public')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'best_answer' as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('user','public')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'comment_answer' as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('user','public')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'comment_question' as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('user','public')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'create_question' as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('user','public')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'edit_close_date' as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('user','public')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'edit_question' as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('user','public')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'fetured_question' as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('user','public')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'sponsored_question' as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('user','public')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'view_question' as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('user','public')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'vote_answer' as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('user','public')
;
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'ggcommunity' as `type`,
    'vote_question' as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type`  IN('user','public')
;
