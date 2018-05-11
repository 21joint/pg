<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: NestedCommentReactions.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitereaction_View_Helper_NestedCommentReactions extends Sitereaction_View_Helper_ReactionsCore {

    public function nestedCommentReactions($subject, $params = array(), $isMobileMode = false) {
        $viewer = Engine_Api::_()->user()->getViewer();
        $like = $subject->likes()->getLike($viewer);
        $tempGlobalView = null;
        $sitereactionInfoType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereaction.info.type');
        $sitereactionGlobalView = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereaction.global.view');
        $sitereactionGlobalType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereaction.global.type');
        $sitereactionLsettings = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereaction.lsettings');
        $params['icons'] = $this->getIcons($subject->getType(), $subject->getIdentity());
        
        $caption = 'Like';
        for ($tempCount = 0; $tempCount < strlen($sitereactionLsettings); $tempCount++) {
            $tempGlobalView += @ord($sitereactionLsettings[$tempCount]);
        }
        $tempGlobalView = $tempGlobalView + $sitereactionGlobalView;
        if(empty($sitereactionGlobalType) && ($sitereactionInfoType != $tempGlobalView))
            return;

        $reaction = array();
        $likeReactionCaption = $caption = $params['icons']['like']['caption'];
        if ($like && isset($params['icons'][$like->reaction])) {
            $reaction = $params['icons'][$like->reaction];
            $caption = $reaction['caption'];
        }
        $data = array(
            'subject' => $subject,
            'toolbar' => $params,
            'caption' => $caption,
            'like' => $like,
            'likeReactionCaption' => $likeReactionCaption,
            'reaction' => $reaction
        );
        $filePreFix = $isMobileMode ? '/sitemobile' : '';
        return $this->view->partial(
            'application/modules/Sitereaction/views'.$filePreFix.'/scripts/_nestedCommentReactions.tpl',
            null,
            $data
        );
    }

}
