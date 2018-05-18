<div class='sitemenu_lightbox' id='user_form_default_sea_lightbox' style="display: none;">
  <div class='sitemenu_lightbox_options'>
    <a  class='close seao_smoothbox_lightbox_close' title='<?php echo $this->translate('Close') ?>' ></a>
  </div>
  <div id='user_form_div_sea_lightbox'>
    <div id='user_form_div_seaocore' onclick="advancedMenuStopLightBoxClickEvent();">

        
      <!--TABS FOR LOGIN AND SIGNUP FORM-->
      <?php //if(!Engine_Api::_()->hasModuleBootstrap('sitehomepagevideo') || !Engine_Api::_()->hasModuleBootstrap('spectacular')):?>
        <?php if ((empty($this->isUserLoginPage) || (empty($this->isUserSignupPage))) && !$this->isPost) :?>
          <div class="headlinesitemenu mbot10">
            <div class=''>
              <ul class="navigation">
                <?php if (!empty($this->sitemenuEnableLoginLightbox)): ?>
                  <li id="user_login_form_tab" class="fleft" style="display: none;">
                    <?php echo $this->translate('Sign In') ?>
                  </li>
                <?php endif; ?>
                <?php if (!empty($this->sitemenuEnableSignupLightbox)): ?>
                  <li id="user_signup_form_tab" class="fleft" style="display: none;">
                    <?php echo $this->translate('Create Account') ?>
                  </li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
        <?php endif; ?>
      <?php //endif; ?>
      
      
      <!--LOGIN AND SIGNUP PAGE CONTENT-->
      <?php if (!empty($this->sitemenuEnableLoginLightbox)): ?>
        <div id="user_login_form" style="display:none">
          <?php if (Engine_Api::_()->hasModuleBootstrap('sitelogin')) :?>
           <?php echo $this->action('login','auth', 'sitelogin', array(
            'disableContent' => true,
            'return_url' => '64-' . base64_encode($this->url())
            )); ?>
           <?php else: ?>
            <?php echo $this->content()->renderWidget('sitemenu.login-or-signup', array('hasInPopup' => 1)); ?>
           <?php endif; ?>  
          <?php if (!empty($this->sitemenuEnableSignupLightbox)): ?>
          <?php if (Engine_Api::_()->hasModuleBootstrap('sitelogin')) :
                Zend_Registry::set('siteloginSignupPopUp', 1); 
            endif;  ?>
            <p class="sitemenu_switch_option">Don’t have an account? <a href="javascript:void(0)" onclick="advancedMenuUserLoginOrSignUp('signup', '<?php echo $this->isUserLoginPage ?>', '<?php echo $this->isUserSignupPage ?>');">Sign Up</a>
            </p>
           <!-- <div class="fright sitemenu_signup_instead_btn">
              <button type="button" onclick="advancedMenuUserLoginOrSignUp('signup', '<?php echo $this->isUserLoginPage ?>', '<?php echo $this->isUserSignupPage ?>');" name="submit"><?php echo $this->translate('Create Account') ?></button>
            </div>-->
          
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <?php if (empty($this->isUserSignupPage) && !$this->isPost && !empty($this->sitemenuEnableSignupLightbox)) : ?>
        <div id="user_signup_form" style="display:none">
            <?php $ifSitequicksignup = Engine_Api::_()->hasModuleBootstrap('sitequicksignup') && $this->settings('sitequicksignup.allow.quick.signup',1); 
                  $signupModule = $ifSitequicksignup ? 'sitequicksignup' : 'sitemenu';
                ?>
          <?php echo $this->action("index", "signup", $signupModule, array('disableContent' => true)) ?>
          <?php if (!empty($this->sitemenuEnableLoginLightbox)): ?>
          <?php if (Engine_Api::_()->hasModuleBootstrap('sitelogin')) :
                Zend_Registry::set('siteloginSignupPopUp', 1); 
            endif;  ?>
            <p class="sitemenu_switch_option">Already a member? <a href="javascript:void(0)" onclick="advancedMenuUserLoginOrSignUp('login', '<?php echo $this->isUserLoginPage ?>', '<?php echo $this->isUserSignupPage ?>');">Sign In</a>
            </p>
           <!-- <div class="fright sitemenu_login_instead_btn">
              <button type="button" onclick="advancedMenuUserLoginOrSignUp('login', '<?php echo $this->isUserLoginPage ?>', '<?php echo $this->isUserSignupPage ?>');" name="submit"><?php echo $this->translate('Already a member?') ?></button>
            </div>-->
          
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>       
  </div>
</div>