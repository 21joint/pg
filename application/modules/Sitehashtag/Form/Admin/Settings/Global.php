<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Global.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitehashtag_Form_Admin_Settings_Global extends Engine_Form {

   
    
    public function init() {
      
        $view = Zend_Registry::get('Zend_View');
        
        $this
                ->setTitle('General Settings')
                ->setDescription('These settings affect all members in your community.');

        $coreSettings = Engine_Api::_()->getApi('settings', 'core');
        
    
        
        $this->addElement('Radio', 'sitehashtag_modules', array(
            'label' => 'Use Hashtag with Content Modules',
            'description' => "Do you want to use hashtag feature with all the Content Modules of your site?",
            'multiOptions' => array(
                2 =>  'Yes',
                1 => "Yes, but only with specific modules. (You can configure your content modules from \'Manage Modules\' section of this plugin.)",
                0 => 'No',
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.modules', 2),
        ));
        
        $description = "Do you want to display content modules' hashtags in activity feed separately? " . '<a href="' . $view->layout()->staticBaseUrl . 'application/modules/Sitehashtag/externals/images/4_3.png" title="View Screenshot" class="buttonlink sitemenu_icon_view mleft5" target="_blank"></a>';
        // Show hashtag
        $this->addElement('Radio', 'sitehashtag_showHashtags', array(
            'label' => 'Display Hashtags',
            'description' => $description,
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
          'escape' => false,
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.showHashtags', 1),
        ));
        
        $this->sitehashtag_showHashtags->getDecorator('description')->setOptions(array('placement' => 'PREPEND','escape' => false));
        // Add submit button
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
        ));
    }

}
