<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
$baseURL = $this->baseUrl();
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/scripts/jquery.min.js');
?>
<script type="text/javascript">
    if (typeof (window.jQuery) != 'undefined') {
        jQuery.noConflict();

<?php if ($this->removePadding): ?>
            jQuery("#global_wrapper").css('padding-top', '0px');
<?php endif; ?>
        setTimeout(function () {
            if (jQuery(".layout_middle").children().length == 1) {
                jQuery("#global_footer").css('margin-top', '165px');
            }
            
            if('<?php echo $this->headerAlreadyPlaced;?>') {
            if (jQuery(".layout_top") && jQuery('.layout_top').find('.layout_middle').children().length == 1) {
                
                if('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('captivate.floating.header', 1);?>' == 1) {
                    jQuery('.layout_top').next().css('margin-top', '635px');
                } else {
                    jQuery('.layout_top').next().css('margin-top', '578px');  
                }
            }
            //$$('.layout_captivate_images').setStyle('margin-top', '-16px');
        } else {
              if (jQuery(".layout_top") && jQuery('.layout_top').find('.layout_middle').children().length == 1)
                jQuery('.layout_top').next().css('margin-top', '720px');
    }
        }, 100);
    }
    var widgetName = 'layout_captivate_images';
</script> 

<?php
if ((!empty($this->captivateSignupLoginLink) || !empty($this->captivateSignupLoginButton)) && !empty($this->isSitemenuExist) && !$this->viewer->getIdentity()):
    echo $this->partial(
            '_addLoginSignupPopupContent.tpl', 'sitemenu', array(
        'isUserLoginPage' => 0,
        'isUserSignupPage' => 0,
        'isPost' => $this->isPost,
        'sitemenuEnableLoginLightbox' => 1, //$this->show_login,
        'sitemenuEnableSignupLightbox' => $this->show_signup_popup
    ));

    Zend_Registry::set('sitemenu_mini_menu_widget', 1);


endif;
?>

<?php if (!empty($this->isSitemenuExist) && (!empty($this->captivateSignupLoginLink) || !empty($this->captivateSignupLoginButton))): ?>

    <?php
    $this->headLink()
            ->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitemenu/externals/styles/style_sitemenu.css');
    ?>
    <?php

// @TODO add bundle
//    $this->headScript()
//            ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitemenu/externals/scripts/core.js');
    ?>
<?php endif; ?>

<?php if ($this->captivateHowItWorks): ?>
    <div style="display: none;" id="how_it_works">
        <?php
        if ($this->captivateLendingBlockValue):
            echo '<div id="show_help_content" style="width:1200px;margin:0 auto;display:table;">' . $this->captivateLendingBlockValue . '</div>';
        else:
           $captivateLendingBlockValue = '<div id="show_help_content" style="width:1200px;margin:0 auto;display:table;"><div>
<span style="font-size:48px;color:#292929;float:left;width:100%;text-align:center;margin:80px 0 0 0;position:absolute;top:0;left:0;right:0;clear:both;">How It Works !</span>
<div style="float: left; margin: 10px 0; opacity: 1; padding: 125px 0; text-align: center; width: 33.3%;"><a href="' . $baseURL . '/videos"><span style=" background-position: center bottom; background-repeat: no-repeat;  height: 175px; margin: 0 auto; width: 175px; background-image: url(' . $baseURL . '/application/modules/Captivate/externals/images/post-video.png); display: block;">&nbsp;</span></a> <a href="' . $baseURL . '/videos"> <span style="color: #fff;text-shadow: 0 0 3px rgba(0, 0, 0, 0.4); float: left; font-family: Ubuntu, sans-serif; font-size: 22px; margin-top: 40px; text-align: center; width: 100%;">Post & Watch Videos</span> <span style="color: #fff;text-shadow: 0 0 3px rgba(0, 0, 0, 0.4); float: left; font-family: Open Sans, sans-serif; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Post the videos, watch the videos and share the videos.</span> </a></div>
<div style="float: left; margin: 10px 0; opacity: 1; padding: 125px 0; text-align: center; width: 33.3%;"><a href="' . $baseURL . '/channels"><span style=" background-position: center 50%; background-repeat: no-repeat;  height: 175px; margin: 0 auto; width: 175px; background-image: url(' . $baseURL . '/application/modules/Captivate/externals/images/create-channel.png); display: block;">&nbsp;</span></a> <a href="' . $baseURL . '/channels"> <span style="color: #fff;text-shadow: 0 0 3px rgba(0, 0, 0, 0.4); float: left; font-family: Ubuntu, sans-serif; font-size: 22px; margin-top: 40px; text-align: center; width: 100%;">Create & Explore Channels</span> <span style="color: #fff;text-shadow: 0 0 3px rgba(0, 0, 0, 0.4); float: left; font-family: Open Sans, sans-serif; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Create your channel and explore the channels.</span></a></div>
<div style="float: left; margin: 10px 0; opacity: 1; padding: 125px 0; text-align: center; width: 33.3%;"><a href="' . $baseURL . '/videos/playlists/browse"><span style=" background-position: center 50%; background-repeat: no-repeat;  height: 175px; margin: 0 auto; width: 175px; background-image: url(' . $baseURL . '/application/modules/Captivate/externals/images/create-playlist.png); display: block;">&nbsp;</span></a> <a href="' . $baseURL . '/videos/playlists/browse"> <span style="color: #fff;text-shadow: 0 0 3px rgba(0, 0, 0, 0.4); float: left; font-family: Ubuntu, sans-serif; font-size: 22px; margin-top: 40px; text-align: center; width: 100%;">Create and Share Playlists</span> <span style="color: #fff;text-shadow: 0 0 3px rgba(0, 0, 0, 0.4); float: left; font-family: Open Sans, sans-serif; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Create playlists for your loved videos and share the playlists.</span></a></div>
<a style="text-indent: 100px; height: 20px; width: 20px; position: absolute; top: 12px; background-image: url(' . $baseURL . '/application/modules/Captivate/externals/images/close-icon.png);" href="#">.</a></div></div>';
        echo $captivateLendingBlockValue;
            ?>
        <?php
        endif;
        ?>
    </div>
<?php endif; ?>

<div class="captivate_images_image_content">
    <div class="captivate_images_page_container">
        <div class="captivate_images_top_head">
            <div class="captivate_images_top_head_left">
                <?php if (!empty($this->showLogo)): ?>
                    <div class="layout_core_menu_logo">
                        <?php
                        $title = $this->coreSettings->getSetting('core_general_site_title', $this->translate('_SITE_TITLE'));
                        $logo = $this->logo;
                        $route = $this->viewer()->getIdentity() ? array('route' => 'user_general', 'action' => 'home') : array('route' => 'default');
                        echo ($logo) ? $this->htmlLink($route, $this->htmlImage($logo, array('alt' => $title))) : $this->htmlLink($route, $title);
                        ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($this->captivateBrowseMenus)): ?>
                    <?php
                    echo $this->content()->renderWidget("captivate.browse-menu-main", array('max' => $this->max));
                    ?>
                <?php endif; ?>
            </div>
            <div class="captivate_images_top_head_right">
                <?php if (!empty($this->captivateSignupLoginLink) && !$this->viewer->getIdentity()): ?>
                    <span class="sign_up_login_btn">
                        <?php if (!empty($this->isSitemenuExist) && !empty($this->show_signup_popup)): ?>
                            <a href="<?php echo $this->url(array(), "user_signup", true) ?>" onClick="advancedMenuUserLoginOrSignUp('signup', '', '');
                                            return false;"><?php echo $this->translate("Sign Up"); ?></a>
                           <?php else: ?>
                            <a href="<?php echo $this->url(array(), "user_signup", true) ?>"><?php echo $this->translate("Sign Up"); ?></a>
                        <?php endif; ?>
                        <?php if (!empty($this->isSitemenuExist)): ?>
                            <a href="<?php echo $this->url(array(), "user_login", true) ?>" onClick="advancedMenuUserLoginOrSignUp('login', '', '');
                                            return false;"><?php echo $this->translate("Sign In"); ?></a>
                           <?php else: ?>
                            <a href="<?php echo $this->url(array(), "user_login", true) ?>"><?php echo $this->translate("Sign In"); ?></a>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>

                <?php if ($this->captivateFirstImprotantLink): ?>
                    <span class="captivate_images_create_account_btn">
                        <a href="<?php echo $this->captivateFirstUrl; ?>"><?php echo $this->translate($this->captivateFirstTitle); ?></a>
                    </span>
                <?php endif; ?>
            </div>     
        </div>
        <div class="captivate_images_middle_caption">
            <h3><?php echo $this->translate($this->captivateHtmlTitle); ?></h3>
            <p><?php echo $this->translate($this->captivateHtmlDescription); ?></p>
            <?php if ($this->captivateHowItWorks): ?>
                <a href="javascript:void(0);" onclick="showHowItWorks();">
                    <?php echo $this->captivateLendingBlockTitleValue ? $this->captivateLendingBlockTitleValue : $this->translate('Get Started'); ?>
                </a>
            <?php endif; ?>
        </div>
        <?php if (!empty($this->captivateSignupLoginButton) && !$this->viewer->getIdentity()): ?>
            <div class="spec_btnsblock">
                <?php if (!empty($this->isSitemenuExist)): ?>
                    <a href="<?php echo $this->url(array(), "user_login", true) ?>" onClick="advancedMenuUserLoginOrSignUp('login', '', '');
                                    return false;"><?php echo $this->translate("Sign In"); ?></a>
                   <?php else: ?>
                    <a href="<?php echo $this->url(array(), "user_login", true) ?>"><?php echo $this->translate("Sign In"); ?></a>
                <?php endif; ?>
                <?php if (!empty($this->isSitemenuExist) && !empty($this->show_signup_popup)): ?>
                    <a href="<?php echo $this->url(array(), "user_signup", true) ?>" onClick="advancedMenuUserLoginOrSignUp('signup', '', '');
                                    return false;"><?php echo $this->translate("Sign Up"); ?></a>
                   <?php else: ?>
                    <a href="<?php echo $this->url(array(), "user_signup", true) ?>"><?php echo $this->translate("Sign Up"); ?></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if ($this->captivateSearchBox): ?>
            <div>
                <?php
                echo $this->content()->renderWidget("captivate.landing-search", array('showLocationSearch' => $this->showLocationSearch, 'showLocationBasedContent' => $this->showLocationBasedContent, 'captivateSearchBox' => $this->captivateSearchBox));
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include APPLICATION_PATH . '/application/modules/Captivate/views/scripts/_imageContent.tpl';
?> 

<script type="text/javascript">
    jQuery(function () {
        jQuery('#show_help_content').find('a').last().on('click', function (e) {
            e.preventDefault();
            jQuery('#how_it_works').css('display', 'none');
        });
        showHowItWorks = function () {
            jQuery("#slide-images").slideDown("slow", function () {
            });
            jQuery("#how_it_works").slideToggle("slow", function () {
            });
        };
    });
    <?php if (Engine_Api::_()->seaocore()->getCurrentActivateTheme()): ?>   
        
        
        
        setTimeout(function () {
            
            
        if (($$('.layout_captivate_navigation div').getChildren().length < 3) && ($$('.layout_captivate_images').length > 0)) {
            if (document.getElementsByTagName("BODY")[0]) {
                if($$('.layout_sitemenu_menu_main').length < 1)
                document.getElementsByTagName("BODY")[0].addClass('captivate_transparent_header');
            }

            window.addEvent('scroll', function () {
                if ($$(".layout_page_header").length > 0)
                {
                    var scrollTop = document.body.scrollTop ? document.body.scrollTop : document.documentElement.scrollTop; 


                    if (scrollTop > 50) {
                        $$(".layout_page_header").addClass("captivate_fix_header");
                    } else {
                        $$(".layout_page_header").removeClass("captivate_fix_header");
                    }
                }
            });
        }
    }, 100);
    
     <?php endif;?>
</script>
<?php if (Engine_Api::_()->seaocore()->getCurrentActivateTheme()): ?>    
    <style type="text/css">
        .layout_main {
            width:1200px;
            margin: 0 auto;
        }
    </style>
<?php endif; ?>
