<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminModuleController.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_AdminModuleController extends Core_Controller_Action_Admin {

    protected $_modulestable;

    public function init() {
        //get table
        $this->_modulestable = Engine_Api::_()->getDbtable('modules', 'sitecredit');
    }

    public function indexAction() {
        // fetch enabled modules
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_module');

        // Get list of all modules from sitecredit module table which are enabled in core module table.
        $this->view->modulesList = $modules = $this->_modulestable->getManageModulesList();

        if (!$this->getRequest()->isPost()) {
            return;
        }
        include_once APPLICATION_PATH . '/application/modules/Sitecredit/controllers/license/license2.php';
    }

    public function enableModuleAction() {
        //enable disbale modules 
        if (!$this->_helper->requireUser()->isValid())
            return;
        $enable_module = $this->_getParam('enable_module');
        $moduleName = $this->_getParam('name');
        $integrated = $this->_getParam('integrated');
        $flag = $this->_getParam('flag');

        $moduleTable = Engine_Api::_()->getDbtable('modules', 'sitecredit');
        if ($this->_getParam('enable_module')) {
            $moduleTable->update(array('integrated' => 1), array('name = ?' => $moduleName, 'flag = ?' => $flag));
        } else {
            $moduleTable->update(array('integrated' => 0), array('name = ?' => $moduleName, 'flag = ?' => $flag));
        }

        return $this->_helper->redirector->gotoRoute(array('module' => 'sitecredit', 'controller' => 'module', 'action' => 'index'), 'admin_default', true);
    }

    //SHOWING THE SOME GUIDELINE.
    public function paypalGuidelinesAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_module');
    }

    public function moduleListAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_modulelist');

        $modulelistTable = Engine_Api::_()->getDbtable('modulelists', 'sitecredit');
        $getEnabledModuleNames = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
        $menuItems = $modulelistTable->fetchAll();
        $menuItems = $menuItems->toArray();

        foreach ($menuItems as $key => $value) {
            $moduleName[] = $value['name'];
        }
        $needToDeleteResult = array_diff($moduleName, $getEnabledModuleNames);
        if (!empty($needToDeleteResult)) {
            foreach ($needToDeleteResult as $value) {
                $modulelistTable->delete(array('name = ?' => $value));
            }
        }
        $coreModuletable = Engine_Api::_()->getDbtable('modules', 'core');
        $coreModuletableName = $coreModuletable->info('name');

        $table = Engine_Api::_()->getDbtable('actionTypes', 'activity');
        $activityTableName = $table->info('name');
        $select = $table->select()->setIntegrityCheck(false);
        $select->from($activityTableName, array("DISTINCT(module)"))->join($coreModuletableName, $coreModuletableName . '.name = ' . $activityTableName . '.module', array("$coreModuletableName.title"));

        $activitymodule = $table->fetchAll($select);
        foreach ($activitymodule as $value) {
            $activitymoduleArray[] = $value['module'];
            $moduletitleArray[$value['module']] = $value['title'];
        }

        $Result = array_diff($getEnabledModuleNames, $moduleName);
        $needToenterResult = array_intersect($activitymoduleArray, $Result);
        if (!empty($needToenterResult)) {
            foreach ($needToenterResult as $value) {
                $modulelistTable->insert(array(
                    'name' => $value,
                    'label' => $moduletitleArray[$value],
                ));
            }
        }

        $this->view->menuItems = Engine_Api::_()->sitecredit()->getModuleEditorArray();
    }

    public function orderAction() {
        ///need to change this
        if (!$this->getRequest()->isPost()) {
            return;
        }
        $orderArray = $this->getRequest()->getPost();
        foreach ($orderArray as $key => $value) {
            $name = explode('_', $key);
            $name = $name[3];
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            try {
                Engine_Api::_()->getDbtable('modulelists', 'sitecredit')->update(
                        array('order_id' => $value), array('name = ?' => $name)
                );
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
        }
        return;
    }

    public function editAction() {

        $itemType = $this->_getParam('addType');
        $id = $this->_getParam('id');
        $modules = Engine_Api::_()->sitecredit()->getModuleEditorArray();
        $modules = $modules->toArray();
        $parentModule = array();
        $count = 0;
        foreach ($modules as $module) {
            if ($module['parent_id'] == 0)
                $parentModule[$module['modulelist_id']] = $module['label'];
            if ($module['modulelist_id'] == $id)
                $moduleArray = $module;
            if ($module['parent_id'] == $id)
                ++$count;
        }
        $this->view->form = $form = new Sitecredit_Form_Admin_Edit();

        if ($count > 0) {
            $form->removeElement('is_submenu');
            $form->removeElement('parent_id');
        } else {
            if ($itemType == 'child') {
                $form->removeElement('label');
            }
            $form->parent_id->setOptions(array('multiOptions' => $parentModule));
        }
        $form->populate($moduleArray);
        // Check post/form
        if (!$this->getRequest()->isPost()) {
            return;
        }
        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }
        $module = Engine_Api::_()->getItem('modulelist', $id);
        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();

        try {
            $values = $form->getValues();

            if ($values['is_submenu'] == 0) {
                $values['parent_id'] = 0;
            }

            $module->setFromArray($values);
            $module->save();
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 10,
            'parentRefresh' => 10,
            'messages' => array('')
        ));
    }

    public function disableAction() {
        $id = $this->_getParam('id');
        $enabled = $this->_getParam('enabled');

        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try {
            $module = Engine_Api::_()->getItem('modulelist', $id);
            if ($enabled) {
                $module->enabled = 0;
            } else {
                $module->enabled = 1;
            }
            $module->save();
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        return $this->_helper->redirector->gotoRoute(array('module' => 'sitecredit', 'controller' => 'module', 'action' => 'module-list'), 'admin_default', true);
    }

}
