<?php
/**
 * EXTFOX
 *
 */
class Sdparentalguide_Widget_AjaxBadgesController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $feedType = $this->_getParam('type', null);

    // Don't render this if not authorized
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    if( !Engine_Api::_()->core()->hasSubject()  || $viewer->getIdentity() < 1 ) {
      return $this->setNoRender();
    }

    // Get subject and check auth
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject('user');
    if( !$subject->authorization()->isAllowed($viewer, 'edit') ) {
      return $this->setNoRender();
    }

    //build ajax
    $this->view->isajax = $is_ajax = $this->_getParam('isajax', '');

    if(!$this->view->isajax) {
        $this->view->params = $params = $this->_getAllParams();
        if ($this->_getParam('loaded_by_ajax', true)) {
          $this->view->loaded_by_ajax = true;
          ;
          if ($this->_getParam('is_ajax_load', false)) {
            $this->view->is_ajax_load = true;
            $this->view->loaded_by_ajax = false;
            if (!$this->_getParam('onloadAdd', false))
              $this->getElement()->removeDecorator('Title');
              $this->getElement()->removeDecorator('Container');
          } else {
            return;
          }
        }

        $table = Engine_Api::_()->getDbTable('badges', 'sdparentalguide');
        $bName = $table->info('name');

        $uTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
        $uName = $uTable->info('name');

        $topicTable = Engine_Api::_()->getDbTable('topics', 'sdparentalguide');
        $tName = $topicTable->info('name');

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
        $specialBadges->setItemCountPerPage($this->_getParam('itemCountPerPage', 4));
        $specialBadges->setCurrentPageNumber($this->_getParam('page', 1));

        // select special badges
        $selectContributorBadges = $table->select()
          ->setIntegrityCheck(false)
          ->from($table)
          ->joinLeft( $tName, "$bName.topic_id = $tName.topic_id")
          ->where( $bName . '.type = ?', 2)
          ->group($tName . '.name')
          ->order( $tName . '.name ASC' )
        ;

        $this->view->contributorBadges = $contributorBadges = Zend_Paginator::factory($selectContributorBadges);
        $contributorBadges->setItemCountPerPage($this->_getParam('itemCountPerPage', 50));
        $contributorBadges->setCurrentPageNumber($this->_getParam('page', 1));
      
        // render content
        $this->view->showContent = true;  
  
    }  else {

        $this->view->showContent = true;
    }

  }

}