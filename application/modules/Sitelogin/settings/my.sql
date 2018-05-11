/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitelogin
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: my.sql 6590 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_modules`
--
INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  
('sitelogin', 'Social Login and Sign-up Plugin', 'Social Login and Sign-up Plugin', '4.9.4p5', 1, 'extra');


CREATE TABLE IF NOT EXISTS `engine4_sitelogin_instagram` ( `user_id` INT NOT NULL PRIMARY KEY, `instagram_id` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `access_token` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `code` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `expires` DATETIME NOT NULL ) ENGINE = InnoDB;
CREATE TABLE IF NOT EXISTS `engine4_sitelogin_yahoo` ( `user_id` INT NOT NULL PRIMARY KEY, `yahoo_id` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `access_token` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `code` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `expires` DATETIME NOT NULL ) ENGINE = InnoDB;
CREATE TABLE IF NOT EXISTS `engine4_sitelogin_pinterest` ( `user_id` INT NOT NULL PRIMARY KEY, `pinterest_id` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `access_token` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `code` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `expires` DATETIME NOT NULL ) ENGINE = InnoDB;
CREATE TABLE IF NOT EXISTS `engine4_sitelogin_outlook` ( `user_id` INT NOT NULL PRIMARY KEY, `outlook_id` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `access_token` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `code` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `expires` DATETIME NOT NULL ) ENGINE = InnoDB;
CREATE TABLE IF NOT EXISTS `engine4_sitelogin_flickr` ( `user_id` INT NOT NULL PRIMARY KEY, `flickr_id` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `access_token` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `code` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `expires` DATETIME NOT NULL ) ENGINE = InnoDB;
CREATE TABLE IF NOT EXISTS `engine4_sitelogin_vk` ( `user_id` INT NOT NULL PRIMARY KEY, `vk_id` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `access_token` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `code` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL , `expires` DATETIME NOT NULL ) ENGINE = InnoDB;

