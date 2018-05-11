<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Addpartialurl.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedpagecache_Form_Admin_Addpartialurl extends Engine_Form {

    public function init() {

        $this
                ->setTitle('Configure Cache Settings');

        $this->addElement('Textarea', 'addUrl', array(
            'description' => 'If you want to configure cache settings for some of the URLs then you can add them here.',
        ));
        $this->addElement('Dummy', 'ad_header2', array(
            'label' => '',
            'description' =>'Multiple URLs can be added here separated by ‘,’ e.g: "/blogs/manage,/albums/manage,/mobi".'
        ));
        
        // Element: submit
        $optionArray = array('member_level' => 'Member level Caching', 'loggedin' => 'Logged In / Non-Logged In User', 'all' => 'Common For All');
        $this->addElement('Radio', 'cache_basedon', array(
            'description' => 'Please select an option on the basis of which you want caching to be done.',
            'multiOptions' => $optionArray,
            'value' => 'all',
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
