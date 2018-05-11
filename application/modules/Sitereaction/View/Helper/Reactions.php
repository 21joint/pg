<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Reactions.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitereaction_View_Helper_Reactions extends Sitereaction_View_Helper_ReactionsCore
{

  public function reactions($action, $params = array(), $isMobileMode = false)
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $caption = 'Like';
  
    $params['icons'] = $this->getIcons($action->getType(), $action->getIdentity());
    $like = $action->likes()->getLike($viewer);
    
     
    
    $reaction = array();
    $likeReactionCaption = $caption = $params['icons']['like']['caption'];
    if ($like && isset($params['icons'][$like->reaction])) {
      $reaction = $params['icons'][$like->reaction];
      $caption = $reaction['caption'];
    }
    //$likeClass = !empty($params['likeClass']) ? $params['likeClass'] : '';
    $data = array(
        'action' => $action,
        'toolbar' => $params,
        'caption' => $caption,
        'likeClass' => '',
        'like' => $like,
        'reaction' => $reaction,
        'likeReactionCaption' => $likeReactionCaption,
        'isMobileMode' => $isMobileMode
    );
    $filePreFix = $isMobileMode ? '/sitemobile' : '';
    return $this->view->partial(
        'application/modules/Sitereaction/views'.$filePreFix.'/scripts/_reactions.tpl',
        null,
        $data
    );
  }

}
