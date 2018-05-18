<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Fieldorder.php 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitequicksignup_Model_DbTable_Fieldorder extends Engine_Db_Table {

    public function getFieldsOptions($name) {
        return $this->fetchRow(array('name = ?' => $name));
    }

    public function getModuleOptions() {

        $fields = $this->select()
                ->from($this->info('name'), array('name', 'display', 'order'))
                ->query()
                ->fetchAll();

        $signupFormSettings = array();
        foreach ($fields as $field) {
            $signupFormSettings[$field['name']]['display'] = $field['display'];
            $signupFormSettings[$field['name']]['order'] = $field['order'];
        }

        return $signupFormSettings;
    }

}
