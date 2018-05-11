<?php

/**
* SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: selectMenu.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteseo_Form_Admin_Sitemap_SelectMenu extends Engine_Form {

	public function init() {

        $this->setTitle('Add / Remove Menu for Sitemap');
        $description = 'Here you can add / remove menu you want to display into your sitemap file. ';
        $this->setDescription($description);
        $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));

        $menuTable = Engine_Api::_()->getDbtable('menus', 'core');
        $menuApi = Engine_Api::_()->getApi('menus', 'core');
        $select = $menuTable->select();
        $menus = $menuTable->fetchAll($select);
        $menuArray = array();
        $mainMenus = array();
        foreach ($menus as $menu) {
            if(strpos($menu->name, 'dashboard') !== false)
                continue;
            $menuArray[$menu->id] = $menu->title;
            if(strpos($menu->name, '_main') !== false)
                $mainMenus[] = $menu->id; 
        }


        $settings = Engine_Api::_()->getApi('settings','core');
        $selectedMenu = $settings->getSetting('siteseo.sitemap.selectedmenu', $mainMenus);
        $selectedMenu = is_array($selectedMenu) ? $selectedMenu : json_decode($selectedMenu);
        $this->addElement('MultiCheckbox', 'selectedmenu', array(
            'label' => 'Select Menus',
            'description' => 'Select the menus whose Menu items you want to show in sitemap.',
            'multiOptions' => $menuArray,
            'value' => $selectedMenu,
        ));

        $this->addElement('Button', 'submit', array(
            'label' => 'Save Selection',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array(
                'ViewHelper',
                ),
        ));
        
        // Element: cancel
        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'onclick' => "javascript:parent.Smoothbox.close();",
            'href' => "javascript:void(0);",
            'decorators' => array(
                'ViewHelper',
                ),
        ));

        // DisplayGroup: buttons
        $this->addDisplayGroup(array(
            'submit',
            'cancel',
            ), 'buttons', array(
            'decorators' => array(
                'FormElements',
                'DivDivDivWrapper'
                ),
        ));
        $button_group = $this->getDisplayGroup('buttons');
        $button_group->setOrder('999');
    }
}