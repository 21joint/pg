<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: WidgetSettings.php 6590 2010-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

//GET DB
$db = Zend_Db_Table_Abstract::getDefaultAdapter();
    
$db->query("INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('feedback_admin_main_form_search', 'feedback', 'Search Form Settings', '', '{\"route\":\"admin_default\",\"module\":\"feedback\",\"controller\":\"settings\",\"action\":\"form-search\"}', 'feedback_admin_main', '', 1, 0, 799)");

