<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminCustomizationController.php 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteluminous_AdminCustomizationController extends Core_Controller_Action_Admin {

  private function _getCustomCSS($values) {
    $returnTheme = null;
    if(!empty($values) && isset($values['theme_customization'])) {
      switch($values['theme_customization']) {
        case 0: // DEFAULT THEME
          $returnTheme .= 'theme_color: #3FC8F4; button_border_color:#3da7ca; landingpage_signinbtn:#ff5f3f; landingpage_signupbtn: rgba(255,95,63,.5);';
          break;
        
        case 1: // LIGHTORANGE COLOR BASED THEME
          $returnTheme .= 'theme_color: #E89476; button_border_color:#D9704A; landingpage_signinbtn:#3FC8F4; landingpage_signupbtn: rgba(63, 200, 244, .5);';
          break;
        
        case 2: // LIGHTPINK COLOR BASED THEME
          $returnTheme .= 'theme_color: #BF6F94; button_border_color:#A6567B; landingpage_signinbtn:#3FC8F4; landingpage_signupbtn: rgba(63, 200, 244, .5);';
          break;
          
        case 4: // LIGHTPURPLE COLOR BASED THEME
        $returnTheme .= 'theme_color: #9397E2; button_border_color:#7A7EC9; landingpage_signinbtn:#BF548F; landingpage_signupbtn: rgba(191, 84, 143, .5);';
        break;
        
        case 5: // LIGHTYELLO COLOR BASED THEME
        $returnTheme .= 'theme_color: #E1D998; button_border_color:#CCC483; landingpage_signinbtn:#3FC8F4; landingpage_signupbtn: rgba(63, 200, 244, .5);';
        break;
        
        case 6: // LEAFGREEN COLOR BASED THEME
        $returnTheme .= 'theme_color: #5EC797; button_border_color:#4BB484; landingpage_signinbtn:#FF5F3F; landingpage_signupbtn: rgba(255, 95, 63, .5);';
        break;
        
        case 7: // FADEBLUE  COLOR BASED THEME
        $returnTheme .= 'theme_color: #446EA6; button_border_color:#335D95; landingpage_signinbtn:#FF5F3F; landingpage_signupbtn: rgba(255, 95, 63, .5);';
        break;
        
        case 8: // SEAGREEN COLOR BASED THEME
        $returnTheme .= 'theme_color: #54BFBF; button_border_color:#3EA9A9; landingpage_signinbtn:#3FC8F4; landingpage_signupbtn: rgba(63, 200, 244, .5);';
        break;
        
        case 9: // PURPLE COLOR BASED THEME
        $returnTheme .= 'theme_color: #67568C; button_border_color:#58477D; landingpage_signinbtn:#FF5F3F; landingpage_signupbtn: rgba(255, 95, 63, .5);';
        break;
        
        case 10: // GREEN COLOR BASED THEME
        $returnTheme .= 'theme_color: #038C7E; button_border_color:#067668; landingpage_signinbtn:#FF5F3F; landingpage_signupbtn: rgba(255, 95, 63, .5);';
        break;
        
        case 11: // RED COLOR BASED THEME
        $returnTheme .= 'theme_color: #BD4D4A; button_border_color:#A83835; landingpage_signinbtn:#005DA4; landingpage_signupbtn: rgba(0, 93, 164, .5);';
        break;
        
        case 12: // DARKBLUE COLOR BASED THEME
        $returnTheme .= 'theme_color: #005DA4; button_border_color:#02478E; landingpage_signinbtn:#BF548F; landingpage_signupbtn: rgba(191, 84, 143, .5);';
        break;
        
        case 13: // MAGENTA COLOR BASED THEME
        $returnTheme .= 'theme_color: #A64E5E; button_border_color:#822A3A; landingpage_signinbtn:#038C7E; landingpage_signupbtn: rgba(3, 140, 126, .5);';
        break;
        
        case 14: // YELLOW COLOR BASED THEME
        $returnTheme .= 'theme_color: #E8BA52; button_border_color:#D0A23A; landingpage_signinbtn:#5EC797; landingpage_signupbtn: rgba(94, 199, 151, .5);';
        break;
        
        case 15: // DARKPINK COLOR BASED THEME
        $returnTheme .= 'theme_color: #DC2850; button_border_color:#B8183B; landingpage_signinbtn:#3FC8F4; landingpage_signupbtn: rgba(63, 200, 244, .5);';
        break;
        
        case 3: // CUSTOM COLOR BASED THEME
          $returnTheme .= 'theme_color: ' . $values['siteluminous_theme_color'] . '; button_border_color:' . $values['siteluminous_theme_button_border_color'] . '; landingpage_signinbtn: ' . $values['siteluminous_landingpage_signinbtn'] . '; landingpage_signupbtn: ' . $values['siteluminous_landingpage_signupbtn'] . ';';
          break;
      }
    }
    
    return $returnTheme;
  }
  
  public function indexAction() {
      $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
              ->getNavigation('siteluminous_admin_main', array(), 'siteluminous_admin_theme_customization');

      $this->view->form = $form = new Siteluminous_Form_Admin_Customization();

      if (!$this->getRequest()->isPost())
        return;        

      if (!$form->isValid($this->getRequest()->getPost()))
        return;

      $values = $form->getValues();
      
      include_once APPLICATION_PATH . '/application/modules/Siteluminous/controllers/license/license2.php';
  }
}
