<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitereaction_Api_Siteapi_Core extends Core_Api_Abstract {

    public function getLikesReactionIcons($popularity) {
        $tempReactions = array();
        foreach ($popularity as $reaction) {
            $icons = $this->getIcons($reaction['reaction'], 1, $reaction['reaction_count']);
            $tempReactions[$icons['reactionicon_id']] = $icons;
        }
        return $tempReactions;
    }

    public function getAllReactionIcons() {
        $reactionIconsTable = Engine_Api::_()->getDbTable('reactionicons', 'sitereaction');
        // get all reaction icons
        $reactionIcons = $reactionIconsTable->getReactions(array('orderby' => 'order'));
        $reactionIconsData = array();
        foreach ($reactionIcons as $reactionIcon) {
            $iconImages = $this->getIcons($reactionIcon['type']);
            $reactionIconsData[$reactionIcon['type']] = array(
                'caption' => Engine_Api::_()->getApi('Core', 'siteapi')->translate($reactionIcon->title),
                'order' => $reactionIcon->order,
                'reactionicon_id' => $reactionIcon->reactionicon_id,
                'reaction' => $reactionIcon->type,
                'icon' => $iconImages,
            );
        }
        return $reactionIconsData;
    }

    public function getIcons($type=0, $caption=0, $count=0) {
        $reactionIconsTable = Engine_Api::_()->getDbTable('reactionicons', 'sitereaction');
        // get all reaction icons 
        $select = $reactionIconsTable->select()
                ->where('type = ?', $type)
                ->limit(1);

        $reaction = $reactionIconsTable->fetchRow($select);
         $tempImageArray = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($reaction, false, 'reaction');
        $icon['reaction_image_icon'] = $tempImageArray['reaction_image_icon'];

        if (isset($caption) && !empty($caption)) {
            $icon['caption'] = Engine_Api::_()->getApi('Core', 'siteapi')->translate($reaction['title']);
            $icon['reactionicon_id'] = $reaction['reactionicon_id'];
            if (isset($count) && !empty($count))
                $icon['reaction_count'] = $count;
        }

        return $icon;
    }

    public function hasStoreCollections() {
        $count = 0;
        $params['limit'] = 50;

        $table = Engine_Api::_()->getDbtable('collections', 'sitereaction');
        $paginator = $table->getStoreCollection($params);
        $count = $paginator->getTotalItemCount();
        if (!empty($count) && $count > 0) {
            return 1;
        } else {
            return 0;
        }
    }

}
