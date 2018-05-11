<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Widget_AssignedBadgesSliderController extends Engine_Content_Widget_Abstract {
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
    
    $table = Engine_Api::_()->getDbtable('badges', 'sdparentalguide');
    $tableName = $table->info("name");

    $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
    $assignedTableName = $assignedTable->info("name");

    $selectAssigned = $table->select()->setIntegrityCheck(false)->from($tableName)
            ->joinLeft($assignedTableName,"$assignedTableName.badge_id = $tableName.badge_id",array("$assignedTableName.active as assigned_active"))
            ->where("$assignedTableName.active = ?",1)
            ->where("$tableName.active = ?",1)
            ->where("$assignedTableName.user_id = ?",$subject->getIdentity());

    $this->view->assignedBadges = $assignedBadges = $table->fetchAll($selectAssigned); 
    if(count($assignedBadges) <= 0){
        return $this->setNoRender();
    }
    $this->getElement()->setAttrib('class', 'layout_sitealbum_photo_strips');
  }
}