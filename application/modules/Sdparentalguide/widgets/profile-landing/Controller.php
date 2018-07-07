<?php
/**
 * EXTFOX
 *
 * @package    EXTFOX 
 */
class Sdparentalguide_Widget_ProfileLandingController extends Engine_Content_Widget_Abstract
{

  public function indexAction() {

    // Don't render this if not authorized
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    if (!Engine_Api::_()->core()->hasSubject()) {
      return $this->setNoRender();
    }

    // Get subject and check auth
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();
    if (!$subject->authorization()->isAllowed($viewer, 'view')) {
      return $this->setNoRender();
    }


    // following start function
    $showFriend = true;

    // Don't render this if friendships are disabled
    if( !Engine_Api::_()->getApi('settings', 'core')->user_friends_eligible ) {
      return $this->setNoRender();
      $showFriend = false;
    }

    // Multiple friend mode
    $select = $subject->membership()->getMembersOfSelect();
    $this->view->friends = $friends = $friendsPaginator = Zend_Paginator::factory($select);

    // Set item count per page and current page number
    $friendsPaginator->setItemCountPerPage($this->_getParam('itemCountPerPage', 5));
    $friendsPaginator->setCurrentPageNumber($this->_getParam('page', 1));

    // Get stuff
    $ids = array();
    foreach( $friends as $friend ) {
      $ids[] = $friend->resource_id;
    }
    $this->view->friendIds = $ids;

    // Get the items
    $friendUsers = array();
    foreach( Engine_Api::_()->getItemTable('user')->find($ids) as $friendUser ) {
      $friendUsers[$friendUser->getIdentity()] = $friendUser;
    }
    $this->view->friendUsers = $friendUsers;

    if( $viewer->isSelf($subject) ) {
      // Get lists
      $listTable = Engine_Api::_()->getItemTable('user_list');
      $this->view->lists = $lists = $listTable->fetchAll($listTable->select()->where('owner_id = ?', $viewer->getIdentity()));

      $listIds = array();
      foreach( $lists as $list ) {
        $listIds[] = $list->list_id;
      }

      // Build lists by user
      $listItems = array();
      $listsByUser = array();
      if( !empty($listIds) ) {
        $listItemTable = Engine_Api::_()->getItemTable('user_list_item');
        $listItemSelect = $listItemTable->select()
          ->where('list_id IN(?)', $listIds)
          ->where('child_id IN(?)', $ids);
        $listItems = $listItemTable->fetchAll($listItemSelect);
        foreach( $listItems as $listItem ) {
          //$list = $lists->getRowMatching('list_id', $listItem->list_id);
          //$listsByUser[$listItem->child_id][] = $list;
          $listsByUser[$listItem->child_id][] = $listItem->list_id;
        }
      }
      $this->view->listItems = $listItems;
      $this->view->listsByUser = $listsByUser;
    }
    
    // Do not render if nothing to show
    if( $friendsPaginator->getTotalItemCount() <= 0 ) {
      $showFriend = false;
    }


    $this->view->showFriends = $showfirend;
    // following end function

    $this->view->profileSettings = $tab = Zend_Controller_Front::getInstance()->getRequest()->getParam('type', null);


    $table = Engine_Api::_()->getDbTable('badges', 'sdparentalguide');
    $bName = $table->info('name');

    $uTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
    $uName = $uTable->info('name');

    // select special badges
    $selectSpecialBadges = $table->select()
      ->setIntegrityCheck(false)
      ->from($table)
      ->joinLeft( $uName, "$bName.badge_id = $uName.badge_id" )
      ->where( $uName.'.owner_id = ?', $subject->getIdentity() )
      ->where( $uName.'.profile_display = ?', 1 )
      ->where( $bName.'.type = ?', 1 )
      ->where( $bName.'.active = ?', 1 )
      ->where( $bName.'.profile_display = ?', 1 )
      ->order( $uName.'.gg_dt_created DESC' )
    ;
    
    $this->view->specialBadges = $specialBadges = Zend_Paginator::factory($selectSpecialBadges);

    $specialBadges->setItemCountPerPage(4);
    $specialBadges->setCurrentPageNumber(1);






  }

  
}