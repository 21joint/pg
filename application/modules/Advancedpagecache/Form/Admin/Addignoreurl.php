<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Addignoreurl.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedpagecache_Form_Admin_Addignoreurl extends Engine_Form {

    public function init() {
        $this
                ->setTitle('Exclude URLs');

        $this->addElement('Textarea', 'ignoreUrl', array(
            'description' => 'If you want to exclude some URLs from caching then you can add them here.',
        ));
        // Element: submit
        $this->addElement('Dummy', 'ad_header2', array(
            'label' => '',
            'description' =>'Multiple URLs can be added here separated by ‘,’ e.g: "/blogs/manage,/albums/manage,/mobi".'
        ));
        $this->addElement('Button', 'submit', array(
            'label' => 'Save',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'href' => '',
            'onClick' => 'javascript:parent.Smoothbox.close();',
            'decorators' => array(
                'ViewHelper'
            )
        ));
        $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
        $button_group = $this->getDisplayGroup('buttons');
    }

}

?>
