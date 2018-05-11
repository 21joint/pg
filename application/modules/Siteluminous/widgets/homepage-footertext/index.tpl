<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h3>
    <?php echo $this->translate("_LUMINOUS_FOOTER_TITLE"); ?>
  </h3>

  <p class="desc-text">
     <?php echo $this->translate("_LUMINOUS_FOOTER_DESCRIPTION"); ?>
  </p>
  
  <div class="signupblock">
  <?php if(!empty($this->isSitemenuExist) && !empty($this->show_signup_popup_footer)): ?>
      <a href="javascript:void(0)" onClick="advancedMenuUserLoginOrSignUp('signup', '', '')"><?php echo $this->translate("Create Your Account"); ?></a>
    <?php else: ?>
      <a href="<?php echo $this->url(array(), "user_signup", true) ?>"><?php echo $this->translate("_LUMINOUS_FOOTER_BUTTON_TEXT"); ?></a>
    <?php endif; ?>
 </div>
 
<?php if(!empty($this->sitemenuEnable) && empty($this->sitemenu_mini_menu_widget)):
    echo $this->partial(
            '_addLoginSignupPopupContent.tpl', 'sitemenu', array(
            'isUserLoginPage' => $this->isUserLoginPage,
            'isUserSignupPage' => $this->isUserSignupPage,
            'isPost' => $this->isPost,
            'sitemenuEnableLoginLightbox' => 0,
            'sitemenuEnableSignupLightbox' => $this->show_signup_popup_footer
    ));

    Zend_Registry::set('sitemenu_mini_menu_widget', 1);
endif; ?>