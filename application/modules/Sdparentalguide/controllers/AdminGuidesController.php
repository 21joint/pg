<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_AdminGuidesController extends Core_Controller_Action_Admin
{
  public function indexAction(){
      $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_listings');
      
      $this->view->formFilter = $formFilter = new Sdparentalguide_Form_Admin_Manage_FilterListings();
      $page = $this->_getParam('page', 1);
      $values = $this->getRequest()->getPost();
      if( $formFilter->isValid($this->_getAllParams()) ) {
//          $values = $formFilter->getValues();
      }
      
      $table = Engine_Api::_()->getDbtable('listings', 'sitereview');
      $tableName = $listingTableName = $table->info("name");    
      $usersTable = Engine_Api::_()->getDbtable('users', 'user');
      $usersTableName = $usersTable->info("name");
      $select = $table->select()->setIntegrityCheck(false)->from($tableName)
              ->joinLeft($usersTableName,"$usersTableName.user_id = $tableName.owner_id",array());
      
      
      $values = array_merge(array(
        'order' => 'listing_id',
        'order_direction' => 'DESC',
            ), $values);

      $select->order((!empty($values['order']) ? $values['order'] : 'listing_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));
          
      $valuesCopy = array_filter($values);
      
       // Make paginator
      $this->view->paginator = $paginator = Zend_Paginator::factory($select);
      $this->view->paginator = $paginator->setCurrentPageNumber( $page );
      $paginator->setItemCountPerPage(15);
      $this->view->formValues = $valuesCopy;
  }
}