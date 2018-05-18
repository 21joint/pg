<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: ReactionsCore.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitereaction_View_Helper_ReactionsCore extends Zend_View_Helper_Abstract {

    protected function getIcons($subjectType = null, $subjectId = 0) {
        $tempGlobalView = null;
        $sitereactionInfoType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereaction.info.type');
        $sitereactionGlobalView = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereaction.global.view');
        $sitereactionGlobalType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereaction.global.type');
        $sitereactionLsettings = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereaction.lsettings');
        if (!Zend_Registry::isRegistered('ReactionIcons')) {
            // get the table
            $reactionIconsTable = Engine_Api::_()->getDbTable('reactionicons', 'sitereaction');

            // get all reaction icons
            $reactionIcons = $reactionIconsTable->getReactions(array('orderby' => 'order'));

            $reactionIconsData = array();

            foreach ($reactionIcons as $reactionIcon) {
                $tempArray = array();
                $tempArray['caption'] = $reactionIcon->title;
                $tempArray['type'] = $reactionIcon->type;
                $tempArray['icon'] = $reactionIcon->getPhotoUrl();
                $reactionIconsData[$reactionIcon->type] = $tempArray;
            }
            Zend_Registry::set('ReactionIcons', $reactionIconsData);
        }
        for ($tempCount = 0; $tempCount < strlen($sitereactionLsettings); $tempCount++) {
            $tempGlobalView += @ord($sitereactionLsettings[$tempCount]);
        }
        $tempGlobalView = $tempGlobalView + $sitereactionGlobalView;
        if(empty($sitereactionGlobalType) && ($sitereactionInfoType != $tempGlobalView)) {
            return;
        }

        $icons = Zend_Registry::get('ReactionIcons');
        foreach ($icons as $key => $value) {
            $icons[$key]['target'] = $subjectId;
            $icons[$key]['subject'] = $subjectType;
        }
        return $icons;
    }

}
