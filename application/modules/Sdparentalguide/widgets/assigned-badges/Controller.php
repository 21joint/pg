<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Widget_AssignedBadgesController extends Engine_Content_Widget_Abstract {
  protected $_childCount = null;
  public function indexAction() {

    // Don't render this if not authorized
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!Engine_Api::_()->core()->hasSubject()) {
      return $this->setNoRender();
    }

    // Get subject and check auth
    $subject = Engine_Api::_()->core()->getSubject();
    if (!$subject->authorization()->isAllowed($viewer, 'view')) {
      return $this->setNoRender();
    }
    
    $this->view->photoHeight = $param['photoHeight'] = $this->_getParam('photoHeight', 200);
    $this->view->photoWidth = $param['photoWidth'] = $this->_getParam('photoWidth', 200);
    $this->view->normalPhotoWidth = Engine_Api::_()->getApi('settings', 'core')->getSetting('normal.photo.width', 375);
    $this->view->normalLargePhotoWidth = Engine_Api::_()->getApi('settings', 'core')->getSetting('normallarge.photo.width', 720);
    
    $table = Engine_Api::_()->getDbtable('badges', 'sdparentalguide');
    $tableName = $table->info("name");

    $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
    $assignedTableName = $assignedTable->info("name");

    $selectAssigned = $table->select()->setIntegrityCheck(false)->from($tableName)
            ->joinLeft($assignedTableName,"$assignedTableName.badge_id = $tableName.badge_id",array("$assignedTableName.active as assigned_active"))
            ->where("$assignedTableName.active = ?",1)
            ->where("$tableName.active = ?",1)
            ->where("$assignedTableName.profile_display = ?",1)
            ->where("$tableName.profile_display = ?",1)
            ->where("$assignedTableName.user_id = ?",$subject->getIdentity());

    $this->view->assignedBadges = $assignedBadges = $table->fetchAll($selectAssigned); 
    $this->_childCount = count($assignedBadges);
    if($this->_childCount <= 0){
        return $this->setNoRender();
    }
    
    $this->getElement()->setAttrib('class', 'layout_sitealbum_photo_strips');
  }
  
  public function getChildCount(){
      return $this->_childCount;
  }

}