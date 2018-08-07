<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_AdminPermissionController extends Core_Controller_Action_Admin {
  //ACTION FOR LEVEL SETTINGS
  public function indexAction() {

    //GET NAVIGATION
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_permission');
    
    $this->view->navigation2 = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_permission', array(), 'sdparentalguide_admin_permission_reviews');

    $this->view->tab_type = 'levelType';

    $this->view->listingTypeCount = $listingTypeCount = Engine_Api::_()->getDbTable('listingtypes', 'sitereview')->getListingTypeCount();

    //GET LEVEL ID
    if (null != ($id = $this->_getParam('id'))) {
      $level = Engine_Api::_()->getItem('authorization_level', $id);
    } else {
      $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
    }

    if (!$level instanceof Authorization_Model_Level) {
      throw new Engine_Exception('missing level');
    }

    $id = $level->level_id;

    //GET LISTING TYPE ID
    $this->view->listingtype_id = $listingtype_id = $this->_getParam('listingtype_id', 0);

    //MAKE FORM
    $this->view->form = $form = new Sdparentalguide_Form_Admin_Permission_Review(array(
                'public' => ( in_array($level->type, array('public')) ),
                'moderator' => ( in_array($level->type, array('admin', 'moderator')) ),
            ));
    $form->level_id->setValue($id);

    //POPULATE DATA
    $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
    $prefieldValues = $permissionsTable->getAllowed('sitereview_listing', $id, array_keys($form->getValues()));
    $prefieldValues['max_listtype_'.$listingtype_id] = Engine_Api::_()->authorization()->getPermission($id, 'sitereview_listing', 'max_listtype_'.$listingtype_id);
    $prefieldValues['listingtype_id'] = '0';
    $form->populate($prefieldValues);

    if ($listingTypeCount > 1) {
//      $form->listingtype_id->setValue($listingtype_id);
    } else {
      $wishlistArray = array();
      $wishlistArray['wishlist'] = $permissionsTable->getAllowed('sitereview_wishlist', $id, 'view');
      $wishlistArray['auth_wishlist'] = $permissionsTable->getAllowed('sitereview_wishlist', $id, 'auth_view');
      $form->populate($wishlistArray);
    }

    //CHECK POST
    if (!$this->getRequest()->isPost()) {
      return;
    }

    //CHECK VALIDITY
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    //PROCESS
    $values = $form->getValues();

    if ($listingTypeCount == 1) {
      $values['view'] = $values['view_listtype_1'];
      $values['comment'] = $values['comment_listtype_1'];

      $wishlistSettings = array();
      $otherSettings = array();
      foreach ($values as $key => $value) {
        if ($key == 'wishlist') {
          $wishlistSettings['view'] = $value;
        } elseif ($key == 'auth_wishlist') {
          $wishlistSettings['auth_view'] = $value;
        } else {
          $otherSettings[$key] = $value;
        }
      }
    }

    $db = $permissionsTable->getAdapter();
    $db->beginTransaction();
    try {

      //SET PERMISSION
      if ($listingTypeCount == 1) {
        include_once APPLICATION_PATH . '/application/modules/Sitereview/controllers/license/license2.php';
      } else {

        $permissionsTable->setAllowed('sitereview_listing', $id, $values);

        //IF ALL LISTINGTYPE HAS NO FOR VIEW AND COMMENT THEN WE WILL SET NO ELSE SET YES
        $listingTypes = Engine_Api::_()->getDbTable('listingtypes', 'sitereview')->getListingTypesArray(0, 0);
        $fixed_values = array();
        $levelFlag = true;
        foreach ($listingTypes as $listingtype_id => $plural_title) {
          $fixed_values['view'] = $view = $permissionsTable->getAllowed('sitereview_listing', $id, 'view_listtype_' . $listingtype_id);
          if (!empty($view)) {
            break;
          }
        }

        foreach ($listingTypes as $listingtype_id => $plural_title) {
          $fixed_values['comment'] = $comment = $permissionsTable->getAllowed('sitereview_listing', $id, 'comment_listtype_' . $listingtype_id);
          if (!empty($comment)) {
            break;
          }
        }

        include_once APPLICATION_PATH . '/application/modules/Sitereview/controllers/license/license2.php';
      }


      //COMMIT
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }
  
  public function customAction() {

    //GET NAVIGATION
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_permission');
    
    $this->view->navigation2 = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_permission', array(), 'sdparentalguide_admin_permission_custom');

    $this->view->tab_type = 'levelType';

    $this->view->listingTypeCount = $listingTypeCount = Engine_Api::_()->getDbTable('listingtypes', 'sitereview')->getListingTypeCount();

    //GET LEVEL ID
    if (null != ($id = $this->_getParam('id'))) {
      $level = Engine_Api::_()->getItem('authorization_level', $id);
    } else {
      $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
    }

    if (!$level instanceof Authorization_Model_Level) {
      throw new Engine_Exception('missing level');
    }

    $id = $level->level_id;

    //GET LISTING TYPE ID
    $this->view->listingtype_id = $listingtype_id = $this->_getParam('listingtype_id', 0);

    //MAKE FORM
    $this->view->form = $form = new Sdparentalguide_Form_Admin_Permission_Custom(array(
                'public' => ( in_array($level->type, array('public')) ),
                'moderator' => ( in_array($level->type, array('admin', 'moderator')) ),
            ));
    $form->level_id->setValue($id);

    //POPULATE DATA
    $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
    $prefieldValues = $permissionsTable->getAllowed('sdparentalguide_custom', $id, array_keys($form->getValues()));
    $form->populate($prefieldValues);

    //CHECK POST
    if (!$this->getRequest()->isPost()) {
      return;
    }

    //CHECK VALIDITY
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    //PROCESS
    $values = $form->getValues();

    $db = $permissionsTable->getAdapter();
    $db->beginTransaction();
    try {

      //SET PERMISSION
      $permissionsTable->setAllowed('sdparentalguide_custom', $id, $values);
      $form->addNotice('Your changes have been saved.');

      //COMMIT
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }
}
