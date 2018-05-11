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

$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/styles/image_rotate.css');

$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/scripts/image_rotate.js');
?>

<?php if(!empty($this->sitemenuEnable) && empty($this->sitemenu_mini_menu_widget)):
    echo $this->partial(
            '_addLoginSignupPopupContent.tpl', 'sitemenu', array(
            'isUserLoginPage' => $this->isUserLoginPage,
            'isUserSignupPage' => $this->isUserSignupPage,
            'isPost' => $this->isPost,
            'sitemenuEnableLoginLightbox' => $this->show_login_popup, //$this->show_login,
            'sitemenuEnableSignupLightbox' => $this->show_signup_popup //$this->show_signup
    ));
    
    Zend_Registry::set('sitemenu_mini_menu_widget', 1);
endif; ?>

<script type="text/javascript">
  window.addEvent('domready', function() {
    durationOfRotateImage = <?php echo!empty($this->defaultDuration) ? $this->defaultDuration : 500; ?>;
    image_rotate();
  });
</script>

<style type="text/css">
  #slide-images{
    width: <?php echo!empty($this->slideWidth) ? $this->slideWidth . 'px;' : '100%'; ?>;
    height: <?php echo $this->slideHeight . 'px;'; ?>;
  }
  .slideblok_image img{
    height: <?php echo $this->slideHeight . 'px;'; ?>;
  }
</style>

<div id="slide-images"  class="slideblock">
  <div class="toptext">
      <h1><?php echo $this->translate("_SITELUMINOUS_HOME_IMAGE_TITLE_"); ?></h1>
      <article><?php echo $this->translate("_SITELUMINOUS_HOME_IMAGE_SUBTITLE_"); ?></article>
      <div class="btnsblock">
        <?php if(!empty($this->show_login)): ?>
          <?php if(!empty($this->isSitemenuExist) && !empty($this->show_login_popup)): ?>
            <a href="<?php echo $this->url(array(), "user_login", true) ?>" onClick="advancedMenuUserLoginOrSignUp('login', '', '');return false;"><?php echo $this->translate("SIGN IN"); ?></a>
          <?php else: ?>
            <a href="<?php echo $this->url(array(), "user_login", true) ?>"><?php echo $this->translate("SIGN IN"); ?></a>
          <?php endif; ?>
        <?php endif; ?>
        <?php if(!empty($this->show_signup)): ?>
            <?php if(!empty($this->isSitemenuExist) && !empty($this->show_signup_popup)): ?>
              <a href="<?php echo $this->url(array(), "user_signup", true) ?>" onClick="advancedMenuUserLoginOrSignUp('signup', '', '');return false;"><?php echo $this->translate("SIGN UP"); ?></a>
            <?php else: ?>
              <a href="<?php echo $this->url(array(), "user_signup", true) ?>"><?php echo $this->translate("SIGN UP"); ?></a>
            <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
 <?php
  foreach ($this->getImages as $imagePath):
    if(!is_array($imagePath)):      
      $iconSrc = "application/modules/Siteluminous/externals/images/" . $imagePath;
    else:
      $iconSrc = Engine_Api::_()->siteluminous()->displayPhoto($imagePath['file_id'], 'thumb.icon');
    endif;
    if (!empty($iconSrc)):
      ?>
      <div class="slideblok_image">
      	<img src="<?php echo $iconSrc; ?>" />
      </div>
      <?php
    endif;
  endforeach;
  ?>
  <div class="slideoverlay"></div>
</div>