<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminAbstract.php 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitequicksignup_Controller_AdminAbstract extends Core_Controller_Action_Admin {

    protected $_fieldType;
    protected $_requireProfileType = false;
    protected $_moduleName;

    public function init() {


        // Parse module name from class
        if (!$this->_moduleName) {
            $this->_moduleName = substr(get_class($this), 0, strpos(get_class($this), '_'));
        }

        // Try to set item type to module name (usually an item type)
        if (!$this->_fieldType) {
            $this->_fieldType = Engine_Api::deflect($this->_moduleName);
        }

        if (!$this->_fieldType || !$this->_moduleName || !Engine_APi::_()->hasItemType($this->_fieldType)) {
            throw new Fields_Model_Exception('Invalid fieldType or modulePath');
        }

        $this->view->fieldType = $this->_fieldType;

        // Hack up the view paths
        $this->view->addHelperPath(dirname(dirname(__FILE__)) . '/views/helpers', 'Fields_View_Helper');
        $this->view->addScriptPath(dirname(dirname(__FILE__)) . '/views/scripts');

        $this->view->addHelperPath(dirname(dirname(dirname(__FILE__))) . DS . $this->_moduleName . '/views/helpers', $this->_moduleName . '_View_Helper');
        $this->view->addScriptPath(dirname(dirname(dirname(__FILE__))) . DS . $this->_moduleName . '/views/scripts');
    }

    public function indexAction() {

        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitequicksignup_admin_main', array(), 'sitequicksignup_admin_main_signupform');

        // Set data
        $mapData = Engine_Api::_()->getApi('core', 'fields')->getFieldsMaps($this->_fieldType);
        $metaData = Engine_Api::_()->getApi('core', 'fields')->getFieldsMeta($this->_fieldType);
        $optionsData = Engine_Api::_()->getApi('core', 'fields')->getFieldsOptions($this->_fieldType);

        // Get top level fields
        $topLevelMaps = $mapData->getRowsMatching(array('field_id' => 0, 'option_id' => 0));
        $topLevelFields = array();
        foreach ($topLevelMaps as $map) {
            $field = $map->getChild();
            $topLevelFields[$field->field_id] = $field;
        }
        $this->view->topLevelMaps = $topLevelMaps;
        $this->view->topLevelFields = $topLevelFields;

        // Do we require profile type?
        // No
        if (!$this->_requireProfileType) {
            $this->topLevelOptionId = '0';
            $this->topLevelFieldId = '0';
        }
        // Yes
        else {

            // Get top level field
            // Only allow one top level field
            if (count($topLevelFields) > 1) {
                throw new Engine_Exception('Only one top level field is currently allowed');
            }
            $topLevelField = array_shift($topLevelFields);
            // Only allow the "profile_type" field to be a top level field (for now)
            if ($topLevelField->type !== 'profile_type') {
                throw new Engine_Exception('Only profile_type can be a top level field');
            }
            $this->view->topLevelField = $topLevelField;
            $this->view->topLevelFieldId = $topLevelField->field_id;

            // Get top level options
            $topLevelOptions = array();
            foreach ($optionsData->getRowsMatching('field_id', $topLevelField->field_id) as $option) {
                $topLevelOptions[$option->option_id] = $option->label;
            }
            $this->view->topLevelOptions = $topLevelOptions;

            // Get selected option
            $option_id = $this->_getParam('option_id');
            if (empty($option_id) || empty($topLevelOptions[$option_id])) {
                $option_id = current(array_keys($topLevelOptions));
            }
            $topLevelOption = $optionsData->getRowMatching('option_id', $option_id);
            if (!$topLevelOption) {
                throw new Engine_Exception('Missing option');
            }
            $this->view->topLevelOption = $topLevelOption;
            $this->view->topLevelOptionId = $topLevelOption->option_id;

            // Get second level fields
            $secondLevelMaps = array();
            $secondLevelFields = array();
            if (!empty($option_id)) {
                $secondLevelMaps = $mapData->getRowsMatching('option_id', $option_id);
                if (!empty($secondLevelMaps)) {
                    foreach ($secondLevelMaps as $map) {
                        $secondLevelFields[$map->child_id] = $map->getChild();
                    }
                }
            }
            $this->view->secondLevelMaps = $secondLevelMaps;
            $this->view->secondLevelFields = $secondLevelFields;
        }
    }

    // Other

    public function orderAction() {
        if (!$this->getRequest()->isPost()) {
            return;
        }

        // Get params
        $fieldOrder = (array) $this->_getParam('fieldOrder');
        $optionOrder = (array) $this->_getParam('optionOrder');

        // Sort
        ksort($fieldOrder, SORT_NUMERIC);
        ksort($optionOrder, SORT_NUMERIC);

        // Get data
        $mapData = Engine_Api::_()->fields()->getFieldsMaps($this->_fieldType);
        $metaData = Engine_Api::_()->fields()->getFieldsMeta($this->_fieldType);
        $optionData = Engine_Api::_()->fields()->getFieldsOptions($this->_fieldType);

        // Parse fields (maps)
        $i = 0;
        foreach ($fieldOrder as $index => $ids) {
            $map = $mapData->getRowMatching(array(
                'field_id' => $ids['parent_id'],
                'option_id' => $ids['option_id'],
                'child_id' => $ids['child_id'],
            ));
            $map->order = ++$i;
            $map->save();
        }

        // Parse options
        $i = 0;
        foreach ($optionOrder as $index => $ids) {
            $option = $optionData->getRowMatching('option_id', $ids['suboption_id']);
            $option->order = ++$i;
            $option->save();
        }

        // Flush cache
        $mapData->getTable()->flushCache();
        $metaData->getTable()->flushCache();
        $optionData->getTable()->flushCache();

        $this->view->status = true;
    }

    public function headingEnableAction() {
        $option_id = $this->_getParam('option_id');
        $field_id = $this->_getParam('field_id');
        $field = Engine_Api::_()->fields()->getField($this->_getParam('field_id'), $this->_fieldType);
        $field->show = 1;
        $field->save();

        if ($option_id && $field_id) {
            $this->_redirect('admin/sitequicksignup/signupform?option_id=' . $option_id . '&field_id=' . $field_id);
        } else {
            $this->_redirect('admin/sitequicksignup/signupform?field_id=' . $field_id);
        }
    }

    public function headingDisableAction() {
        $option_id = $this->_getParam('option_id');
        $field_id = $this->_getParam('field_id');
        $field = Engine_Api::_()->fields()->getField($this->_getParam('field_id'), $this->_fieldType);
        $field->show = 0;
        $field->save();

        if ($option_id && $field_id) {
            $this->_redirect('admin/sitequicksignup/signupform?option_id=' . $option_id . '&field_id=' . $field_id);
        } else {
            $this->_redirect('admin/sitequicksignup/signupform?field_id=' . $field_id);
        }
    }

}
