<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Showignoreurl.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedpagecache_Form_Admin_Showignoreurl extends Engine_Form {

    protected $_defaultOptions = array();

    public function setOptionArray($sourceArray = array()) {

        if (!empty($sourceArray)) {
            $this->_defaultOptions = $sourceArray;
            $this->addElements();
        }
    }

    public function addElements($array = array()) {
        $this->setAttrib('id', 'multiignore_form');
        $this->setAttrib('onSubmit', 'event.preventDefault(); multiModify()');
        $this->setTitle('Excluded URLs');
        $this->addElement('MultiCheckbox', 'delete_urls', array(
            'multiOptions' => !empty($this->_defaultOptions) ? $this->_defaultOptions : array(),
        ));

        // Element: submit
        $this->addElement('Button', 'btnSubmit', array(
            'label' => 'Delete Selected',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array('ViewHelper')
        ));
    }

}

?>
