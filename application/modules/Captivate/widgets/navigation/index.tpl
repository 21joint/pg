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
<?php $floating_header = Engine_Api::_()->getApi('settings', 'core')->getSetting('captivate.floating.header', 1); ?>
<div id="captivate_navigation_content">
    <div class="headline" style="display: none;"></div>

</div>

<script type="text/javascript">
    var floating_header_for_widget = '<?php echo $floating_header; ?>'
    if ($$('.layout_captivate_navigation div').getChildren().length > 2) {
        setTimeout(function () {

            if ($('global_header') && $('global_header').getElement('.layout_sitemenu_menu_main')) {
                if ($('global_wrapper')) {
                    $('global_wrapper').setStyle('padding-top', '166px');
                }
            } else {
                if ($('global_wrapper')) {
                    if (floating_header_for_widget == 0) {
                        $('global_wrapper').setStyle('padding-top', '70px');
                    } else {
                       navigationHeight = $$('.layout_captivate_navigation') ? (parseInt($$('.layout_captivate_navigation').getHeight()[0])):0;
                       $('global_wrapper').setStyles({'padding-top':parseInt($$('.layout_page_header').getHeight()[0])+navigationHeight+'px'});
                    }
                }
            }


        }, 1);
    }

    if (<?php echo $this->padding_top; ?>) {
        if ($$('.layout_captivate_navigation div').getChildren().length == 2) {
            setTimeout(function () {
                if ($('global_wrapper')) {
                   // $('global_wrapper').setStyle('padding-top', '55px');
                }
            }, 1);
        }
    } else {
        setTimeout(function () {
            if ($('global_header') && $('global_header').getElement('.layout_sitemenu_menu_main')) {
                if ($('global_wrapper')) {
                    if (floating_header_for_widget == 0) {
                        $('global_wrapper').setStyle('padding-top', '70px');
                    } else {
                         navigationHeight = $$('.layout_captivate_navigation') ? (parseInt($$('.layout_captivate_navigation').getHeight()[0])):0;
                          $('global_wrapper').setStyles({'padding-top':parseInt($$('.layout_page_header').getHeight()[0])+navigationHeight+'px'});
                    }
                }
            }
        }, 1);
    }
    var setNavigation = function () {
        if ($('global_wrapper').getElementById('global_content') && $('global_wrapper').getElementById('global_content').getElement('.headline')) {
            if ($('global_header') && $('global_header').getElement('.layout_sitemenu_menu_main')) {
                if ($('global_wrapper')) {
                    $('global_wrapper').setStyle('padding-top', '166px');
                }
            } else {
                if ($('global_wrapper')) {
                    if (floating_header_for_widget == 0) {
                        $('global_wrapper').setStyle('padding-top', '70px');
                    } else {
                        navigationHeight = $$('.layout_captivate_navigation') ? (parseInt($$('.layout_captivate_navigation').getHeight()[0])):0;
                        if($('global_wrapper').getElementById('global_content').hasClass('global_content_fullwidth')) { 
                            $('global_wrapper').setStyles({'padding-top':parseInt($$('.layout_page_header').getHeight()[0])+'px'});
                        } else {
                            if(parseInt($$('.layout_page_header').getHeight()[0]) > 100) {
                                $('global_wrapper').setStyles({'padding-top':parseInt($$('.layout_page_header').getHeight()[0])+'px'});
                            } else {
                                $('global_wrapper').setStyles({'padding-top':parseInt($$('.layout_page_header').getHeight()[0])+navigationHeight+'px'});
                            } 
                        } 
                    }
                }
            }
            $('captivate_navigation_content').getElement('.headline').setStyle('display', 'block');
            $('captivate_navigation_content').getElement('.headline').innerHTML = $('global_wrapper').getElementById('global_content').getElement('.headline').innerHTML;
            $('global_wrapper').getElementById('global_content').getElement('.headline').hide();
            if ($('global_wrapper').getElementById('global_content').getElement('.layout_top') && $('global_wrapper').getElementById('global_content').getElement('.layout_top').getElement('.layout_middle') && $('global_wrapper').getElementById('global_content').getElement('.layout_top').getElement('.layout_middle').getChildren() && $('global_wrapper').getElementById('global_content').getElement('.layout_top').getElement('.layout_middle').getChildren()[0]) {
                $('global_wrapper').getElementById('global_content').getElement('.layout_top').getElement('.layout_middle').getChildren()[0].hide();
            }
            if ($('global_wrapper').getElementById('global_content').getElement('.layout_sitemember_navigation_sitemember'))
                $('global_wrapper').getElementById('global_content').getElement('.layout_sitemember_navigation_sitemember').hide();

            if ($('global_wrapper').getElementById('global_content').getElement('.layout_sitecrowdfunding_navigation'))
                $('global_wrapper').getElementById('global_content').getElement('.layout_sitecrowdfunding_navigation').hide();
        } else {
            $('global_wrapper').setStyles({'padding-top':(parseInt($$('.layout_page_header').getHeight()[0]) -4)+'px'});
        }
    }; 

    setTimeout(setNavigation, 50);
    en4.core.runonce.add(setNavigation);
    setTimeout(setNavigation, 400);
    setTimeout(setNavigation, 1000);
</script>
