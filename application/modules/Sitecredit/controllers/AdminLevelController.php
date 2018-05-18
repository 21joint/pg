<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminLevelController.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_AdminLevelController extends Core_Controller_Action_Admin {

    public function indexAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_level');
        $this->view->form = $form = new Sitecredit_Form_Admin_Level();
        $AffiliateLinkPermission = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.allow.affiliate.link', 1);
        if (empty($AffiliateLinkPermission)) {
            $form->removeElement("link_credit");
        }
        // Get level id
        if (null !== ($id = $this->_getParam('id'))) {
            $level = Engine_Api::_()->getItem('authorization_level', $id);
        } else {
            $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
        }
        if (!$level instanceof Authorization_Model_Level) {
            throw new Engine_Exception('missing level');
        }

        $id = $level->level_id;
        $form->level_id->setValue($id);
        if (($level->type == 'public')) {
            $form->removeElement('buy');
            $form->removeElement('send');
            $form->removeElement('max_perday');
            $form->removeElement('link_credit');
            $form->removeElement('validity');
            $form->removeElement('submit');
            $form->addNotice('No settings are available for this member level.');
        }
        // Populate values
        $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
        $form->populate($permissionsTable->getAllowed('sitecredit_credit', $id, array_keys($form->getValues())));

        // Check post
        if (!$this->getRequest()->isPost()) {
            return;
        }

        // Check validitiy
        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        // Process

        $values = $form->getValues();
        $db = $permissionsTable->getAdapter();
        $db->beginTransaction();

        try {
            include_once APPLICATION_PATH . '/application/modules/Sitecredit/controllers/license/license2.php';
            // Commit
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        $form->addNotice('Your changes have been saved.');
    }

}
