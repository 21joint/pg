<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: IndexController.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_IndexController extends Core_Controller_Action_Standard {

  public function likesAction() {
    // Collect params
    $subject_id = $this->_getParam('subject_id');
    $subject_type = $this->_getParam('subject_type');
    $this->view->reaction = $reaction = $this->_getParam('reaction');
    $this->view->isAjax = $this->_getParam('is_ajax', false);
    if ($subject_type == 'activity_action') {
      $subject = Engine_Api::_()->getDbTable('actions', 'advancedactivity')->getActionById($subject_id);
    } else {
      $subject = Engine_Api::_()->getItem($subject_type, $subject_id);
    }
    $this->view->subject = $subject;
    $likesSelect = $subject->likes()->getLikeSelect();
    if ($reaction) {
      $likesSelect->where('reaction = (?)', $reaction);
    }

    $paginator = Zend_Paginator::factory($likesSelect);
    $paginator->setItemCountPerPage(1000);
    $paginator->count();
    $pages = $paginator->getPageRange();
    $this->view->paginator = $paginator->setCurrentPageNumber($pages);
    $reactionIconsTable = Engine_Api::_()->getDbTable('reactionicons', 'sitereaction');
    // get all reaction icons
    $reactionIcons = $reactionIconsTable->getReactions(array('orderby' => 'order'));
    $reactionIconsData = array();
    foreach ($reactionIcons as $reactionIcon) {
      $reactionIconsData[$reactionIcon->type] = array(
        'caption' => $reactionIcon->title,
        'type' => $reactionIcon->type,
        'icon' => $reactionIcon->getPhotoUrl(),
      );
    }
    $this->view->reactionIcons = $reactionIconsData;
  }

  public function loadStickerAction()
  {
    //this actions is used for load sticker via ajax
  }
}
