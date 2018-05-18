<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminManageController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_AdminManageController extends Core_Controller_Action_Admin {

  //ACTION FOR MANAGING THE OFFERS
  public function indexAction() {

    //CREATE NAVIGATION TABS
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sitepageoffer_admin_main', array(), 'sitepageoffer_admin_main_manage');

    //FORM GENERATION
    $this->view->formFilter = $formFilter = new Sitepageoffer_Form_Admin_Manage_Filter();

    //FETCH OFFER DATAS
    $tableUser = Engine_Api::_()->getItemTable('user')->info('name');
    $tableSitepage = Engine_Api::_()->getItemTable('sitepage_page')->info('name');
    $table = Engine_Api::_()->getDbtable('offers', 'sitepageoffer');
    $rName = $table->info('name');
    $select = $table->select()
                    ->setIntegrityCheck(false)
                    ->from($rName)
                    ->joinLeft($tableUser, "$rName.owner_id = $tableUser.user_id", 'username')
                    ->joinLeft($tableSitepage, "$rName.page_id = $tableSitepage.page_id", 'title AS sitepage_title');
    $values = array();
    if ($formFilter->isValid($this->_getAllParams())) {
      $values = $formFilter->getValues();
    }

    foreach ($values as $key => $value) {
      if (null === $value) {
        unset($values[$key]);
      }
    }
    if (isset($_POST['search'])) {
      if (!empty($_POST['owner'])) {
        $this->view->owner = $_POST['owner'];
        $select->where($tableUser . '.username  LIKE ?', '%' . $_POST['owner'] . '%');
      }
      if (!empty($_POST['title'])) {
        $this->view->title = $_POST['title'];
        $select->where($rName . '.title  LIKE ?', '%' . $_POST['title'] . '%');
      }
      if (!empty($_POST['sitepage_title'])) {
        $this->view->sitepage_title = $_POST['sitepage_title'];
        $select->where($tableSitepage . '.title  LIKE ?', '%' . $_POST['sitepage_title'] . '%');
      }
      if (!empty($_POST['hotoffer'])) {
        $this->view->hotoffer = $_POST['hotoffer'];
        $_POST['hotoffer']--;
        $select->where($rName . '.hotoffer = ? ', $_POST['hotoffer']);
      }
    }
    $values = array_merge(array(
                'order' => 'offer_id',
                'order_direction' => 'DESC',
                    ), $values);

    $this->view->assign($values);
    $select->order((!empty($values['order']) ? $values['order'] : 'offer_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));
    include APPLICATION_PATH . '/application/modules/Sitepageoffer/controllers/license/license2.php';
    
    $this->view->paginator->setItemCountPerPage(50);
    $this->view->paginator->setCurrentPageNumber($this->_getParam('page',1));
  }

  //ACTION FOR MULTI DELETE OFFERS
  public function multiDeleteAction() {

    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          //DELETE OFFERS FROM DATABASE AND SCRIBD
          $offer_id = (int) $value;
					Engine_Api::_()->sitepageoffer()->deleteContent($offer_id);
        }
      }
    }
    return $this->_helper->redirector->gotoRoute(array('action' => 'index'));
  }

}
?>