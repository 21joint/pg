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
      ->where( $uName.'.user_id = ?', $subject->getIdentity() )
      ->where( $bName.'.type = ?', 1 )
      ->where( $uName.'.profile_display = ?', 1 )
      ->where( $bName.'.active = ?', 1 )
      ->where( $bName.'.profile_display = ?', 1 )
      ->order( $uName.'.gg_dt_created DESC' )
    ;

    $this->view->specialBadges = $specialBadges = Zend_Paginator::factory($selectSpecialBadges);
    $specialBadges->setItemCountPerPage($this->_getParam('itemCountPerPage', 4));
    $specialBadges->setCurrentPageNumber($this->_getParam('page', 1));

  }

  
}