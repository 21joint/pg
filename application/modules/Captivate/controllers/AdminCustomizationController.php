<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminCustomizationController.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Captivate_AdminCustomizationController extends Core_Controller_Action_Admin {

    private function _getCustomCSS($values) {
        $returnTheme = null;
        if (!empty($values) && isset($values['captivate_theme_customization'])) {
            switch ($values['captivate_theme_customization']) {
                case 0: // DEFAULT THEME
                    $returnTheme .= 'theme_color: #44bbff; button_border_color:#17aafe; landingpage_signinbtn:#44bbff; landingpage_signupbtn: rgba(255,95,63,.5); theme_background_color:#f1f1f1;navigation_background_color: #e1e1e1;';
                    break;

                case 1: // PINK COLOR BASED THEME
                    $returnTheme .= 'theme_color: #ff5a5f; button_border_color:#ff2f34; landingpage_signinbtn:#ff5a5f; landingpage_signupbtn: rgba(63, 200, 244, .5); theme_background_color:#f1f1f1;navigation_background_color: #e1e1e1;';
                    break;

                case 2: // GREEN COLOR BASED THEME
                    $returnTheme .= 'theme_color: #038c7e; button_border_color:#007165; landingpage_signinbtn:#038c7e; landingpage_signupbtn: rgba(63, 200, 244, .5); theme_background_color:#f1f1f1;navigation_background_color: #e1e1e1;';
                    break;

                case 4: // DARK SKY BLUE COLOR BASED THEME
                    $returnTheme .= 'theme_color: #3eaacd; button_border_color:#047da5; landingpage_signinbtn:#3eaacd; landingpage_signupbtn: rgba(191, 84, 143, .5); theme_background_color:#f1f1f1;navigation_background_color: #e1e1e1;';
                    break;

                case 5: // VOILET COLOR BASED THEME
                    $returnTheme .= 'theme_color: #9351a6; button_border_color:#7c3490; landingpage_signinbtn:#9351a6; landingpage_signupbtn: rgba(63, 200, 244, .5); theme_background_color:#f1f1f1;navigation_background_color: #e1e1e1;';
                    break;

                case 6: // ORANGE COLOR BASED THEME
                    $returnTheme .= 'theme_color: #bc5300; button_border_color:#a24a05; landingpage_signinbtn:#bc5300; landingpage_signupbtn: rgba(255, 95, 63, .5); theme_background_color:#f1f1f1;navigation_background_color: #e1e1e1;';
                    break;

                case 7: // SUNFLOWER COLOR BASED THEME
                    $returnTheme .= 'theme_color: #f1c40f; button_border_color:#c29d0b; landingpage_signinbtn:#f1c40f; landingpage_signupbtn: rgba(255, 95, 63, .5); theme_background_color:#f1f1f1;navigation_background_color: #e1e1e1;';
                    break;

                case 8: // YELLOW COLOR BASED THEME
                    $returnTheme .= 'theme_color: #b0bf0a; button_border_color:#909d00; landingpage_signinbtn:#b0bf0a; landingpage_signupbtn: rgba(63, 200, 244, .5); theme_background_color:#f1f1f1;navigation_background_color: #e1e1e1;';
                    break;

                case 9: // EMERALD COLOR BASED THEME
                    $returnTheme .= 'theme_color: #2ecc71; button_border_color:#20a257; landingpage_signinbtn:#2ecc71; landingpage_signupbtn: rgba(255, 95, 63, .5); theme_background_color:#f1f1f1;navigation_background_color: #e1e1e1;';
                    break;

                case 10: // DARK PINK COLOR BASED THEME
                    $returnTheme .= 'theme_color: #e63c61; button_border_color:#d90f3a; landingpage_signinbtn:#e63c61; landingpage_signupbtn: rgba(255, 95, 63, .5); theme_background_color:#f1f1f1;navigation_background_color: #e1e1e1;';
                    break;

                case 3: // CUSTOM COLOR BASED THEME
                    $returnTheme .= 'theme_color: ' . $values['captivate_theme_color'] . '; button_border_color:' . $values['captivate_theme_button_border_color'] . '; landingpage_signinbtn: ' . $values['captivate_landingpage_signinbtn'] . '; landingpage_signupbtn: ' . $values['captivate_landingpage_signupbtn'] . '; theme_background_color: ' . $values['captivate_theme_background_color'] . '; theme_containers_background_color: ' . $values['captivate_theme_containers_background_color'] . '; navigation_background_color: ' . $values['captivate_navigation_background_color'] . ';';
                    break;
            }
        }
        return $returnTheme;
    }

    public function indexAction() {
        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('captivate_admin_main', array(), 'captivate_admin_theme_customization');

        $this->view->form = $form = new Captivate_Form_Admin_Customization();

        if (!$this->getRequest()->isPost())
            return;

        if (!$form->isValid($this->getRequest()->getPost()))
            return;

        $values = $form->getValues();

        include_once APPLICATION_PATH . '/application/modules/Captivate/controllers/license/license2.php';
    }

}
