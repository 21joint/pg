<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>

<?php 
  $this->headScript()
    ->appendFile($this->layout()->staticBaseUrl . 'externals/mdetect/mdetect' . ( APPLICATION_ENV != 'development' ? '.min' : '' ) . '.js');
?>
<?php if ($this->pageIdentity !== 'user-auth-login') : ?>
  <div id='user_auth_popup' style="display:none;">
    <div class="close_icon_container" onclick="parent.Smoothbox.close();">
      <i class="fa fa-times" aria-hidden="true" ></i>
    </div>
      <?php $ifSiteLogin = Engine_Api::_()->hasModuleBootstrap('sitelogin'); 
            $authLogin = $ifSiteLogin ? 'sitelogin' : 'user';
    ?>
    <?php echo $this->action('login','auth', $authLogin, array(
      'disableContent' => true,
      'return_url' => '64-' . base64_encode($this->url())
    )); ?>
  </div>
<?php endif; ?>
<?php if (!in_array($this->pageIdentity, array('user-signup-index','sitequicksignup-signup-index'))) : ?>
<div id='user_signup_popup' class="user_signup_popup" style="display:none">
<div id="TB_overlay" class="user_signup_popup" style="position: fixed; top: 0; left: 0; opacity: 0.6; min-height: 100%; width: 100%;"></div>
  <div id="TB_window" class="signup_login_popup_wrapper" style="opacity: 1; visibility: visible; width: 480px; left: 0; right: 0; margin-left: auto; margin-right: auto; top: 20px;" class="signup_login_popup_wrapper"><div id="TB_title"><div id="TB_ajaxWindowTitle"></div><div id="TB_closeAjaxWindow"><a id="TB_title" href="javascript:void(0);" title="close">close</a></div></div><div id="TB_ajaxContent" width="480" height="320" style="width: 480px; height: 20px;"><div>
    <div class="close_icon_container" onclick="$('user_signup_popup').addClass('dnone'); $('user_signup_popup').removeClass('dblock')">
      <i class="fa fa-times" aria-hidden="true"></i>
    </div>                 
    <?php $ifSitequicksignup = $this->settings('sitequicksignup.allow.quick.signup',1); 
        $signupModule = $ifSitequicksignup ? 'sitequicksignup' : 'user';
      ?>
     <?php echo $this->action('index','signup', $signupModule, array('disableContent' => true)); ?>    
  </div></div></div>
  </div>
   
<?php endif; ?>

<script type='text/javascript'>
  if( !DetectMobileQuick() && !DetectIpad() ) {
    en4.core.runonce.add(function() {
      var setPopupContent = function (event, contentId) {
        event.stop();
        en4.core.reCaptcha.render();
        if(contentId == 'user_signup_popup'){
          $(contentId).addClass('dblock');
          $(contentId).removeClass('dnone');
          return;
        }
        Smoothbox.open($(contentId).get('html'));
        $('TB_window').addClass('signup_login_popup_wrapper');
      };

      window.addEvent('click', function (event) {
        if(event.target.hasClass('user_signup_popup')) {
          $('user_signup_popup').removeClass('dblock');
          $('user_signup_popup').addClass('dnone');
        }  
      });
      <?php if (!in_array($this->pageIdentity, array('user-signup-index','sitequicksignup-signup-index'))) : ?>
        $$('.user_signup_link').addEvent('click', function(event) {
          if($('socialsignup_popup_div')) $('socialsignup_popup_div').addClass('socialsignup_popup_div');
          if($('sociallogin_signup_popup')) $('sociallogin_signup_popup').addClass('sociallogin_signup_popup');
          setPopupContent(event, 'user_signup_popup');
        });
      <?php endif; ?>
      <?php if ($this->pageIdentity !== 'user-auth-login') : ?>
        $$('.user_auth_link').addEvent('click', function(event) {
          if($('sociallogin_popup_div')) $('sociallogin_popup_div').addClass('sociallogin_popup_div');
          setPopupContent(event, 'user_auth_popup');
        });
      <?php endif; ?>
    });
  }
</script>
