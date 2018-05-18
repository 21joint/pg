<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$contentTypes = Engine_Api::_()->seaocore()->getEnabledModuleNames();
$contentTypeArray = array();
if (!empty($contentTypes)) {
    foreach ($contentTypes as $contentType) {
        if ($contentType['name'] == 'sitevideo') {
            $contentTypeArray['sitevideo_video'] = 'Sponsored Video Categories';
            $contentTypeArray['sitevideo_channel'] = 'Sponsored Channel Categories';
        } else {
            $contentTypeArray[$contentType['name']] = $contentType['title'] . ' Sponsored Categories';
        }
    }
}
$contentTypeElement = array();
if (!empty($contentTypeArray)) {
    $contentTypeElement = array(
        'Select',
        'contentModuleSponsoredCategories',
        array(
            'label' => 'Select',
            'multiOptions' => $contentTypeArray,
        ),
        'value' => '',
    );
}

$onloadScript = " <script>
 window.addEvent('domready', function () {
      $('title-wrapper').style.display = 'none';
});


</script>";
return array(
    array(
        'title' => 'Responsive Captivate Theme - Footer Text',
        'description' => 'You can place this widget in the footer and can set text accordingly from the ‘Language Manager’ under ‘Layout’ section available in the admin panel of your site. For more detail, please read FAQ section from Admin Panel => Responsive Captivate Theme => FAQs',
        'category' => 'SEAO - Responsive Captivate Theme',
        'type' => 'widget',
        'name' => 'captivate.homepage-footertext',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'show_signup_popup_footer',
                    array(
                        'label' => "Do you want to open sign up in pop-up when create account button is being clicked in this widget?",
                        'multiOptions' => array(
                            1 => 'Yes, show Signup popup',
                            0 => 'No, do not show Signup popup'
                        ),
                        'value' => 1,
                    )
                ),
            )
        )
    ),
    array(
        'title' => 'Responsive Captivate Theme - Footer Menu',
        'description' => 'Shows the site-wide footer menu. You can edit its contents in your menu editor.',
        'category' => 'SEAO - Responsive Captivate Theme',
        'type' => 'widget',
        'name' => 'captivate.menu-footer',
        'requirements' => array(
            'header-footer',
        ),
        'adminForm' => array(
            'elements' => array(
            )
        ),
    ),
    array(
        'title' => 'Responsive Captivate Theme - Landing Search',
        'description' => 'Displays the Advanced Search Box on the landing page. [Dependent on Advanced Search Plugin, if you are not having this plugin global search box will be displayed.]',
        'category' => 'SEAO - Responsive Captivate Theme',
        'type' => 'widget',
        'name' => 'captivate.landing-search',
        'adminForm' => 'Captivate_Form_Admin_Widget_Search',
    ),
    array(
        'title' => 'Responsive Captivate Theme - Banner Images',
        'description' => 'Displays the Banner Images uploaded by you. This widget can be placed on any widgetized page.',
        'category' => 'SEAO - Responsive Captivate Theme',
        'type' => 'widget',
        'name' => 'captivate.banner-images',
        'adminForm' => 'Captivate_Form_Admin_Widget_BannerContent',
        'autoEdit' => 'true'
    ),
    array(
        'title' => 'Responsive Captivate Theme - Navigation Tabs',
        'description' => "Displays the site wide navigation menus of your website. This widget should be placed in header.",
        'category' => 'SEAO - Responsive Captivate Theme',
        'type' => 'widget',
        'name' => 'captivate.navigation',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
        'adminForm' => array(
        ),
    ),
    array(
        'title' => 'Responsive Captivate Theme - Main Menu',
        'description' => 'Shows the site-wide main menu. You can edit its contents in your menu editor.',
        'category' => 'SEAO - Responsive Captivate Theme',
        'type' => 'widget',
        'name' => 'captivate.browse-menu-main',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'max',
                    array(
                        'description' => "How many main menus do you want to show on your website?",
                        'value' => 20
                    )
                )))
    ),
    array(
        'title' => 'Responsive Captivate Theme - Landing Page Images',
        'description' => 'Displays multiple images uploaded by you on the Landing Page.',
        'category' => 'SEAO - Responsive Captivate Theme',
        'type' => 'widget',
        'name' => 'captivate.images',
        'adminForm' => 'Captivate_Form_Admin_Widget_Content',
        'autoEdit' => 'true'
    ),
    array(
        'title' => 'Responsive Captivate Theme - Search Box',
        'description' => 'Displays the Advanced Search box in the header. [Dependent on Advanced Search Plugin, if your are not having this plugin global search box will be displayed.]',
        'category' => 'SEAO - Responsive Captivate Theme',
        'type' => 'widget',
        'name' => 'captivate.search-box',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'captivate_search_width',
                    array(
                        'label' => 'Enter width for searchbox.',
                        'value' => 240,
                    )
                ),
                array(
                    'Text',
                    'captivate_search_box_width_for_nonloggedin',
                    array(
                        'label' => 'Enter width for searchbox non logged-in user.',
                        'value' => 275,
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'Responsive Captivate Theme - Sponsored Categories With Image',
        'description' => 'This widget displays the sponsored categories with image.',
        'category' => 'SEAO - Responsive Captivate Theme',
        'type' => 'widget',
        'name' => 'seaocore.sponsored-categories-with-image',
        'adminForm' => array(
            'elements' => array(
                $contentTypeElement,
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter width for each block',
                        'value' => 275,
                    )
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter height for each block.',
                        'value' => 275,
                    )
                ),
            ))
    ),
    array(
        'title' => 'Responsive Captivate Theme - HTML Block',
        'description' => 'This widget shows the HTML title & description. [If you want to edit the HTML title and description then, please <a target="_blank" href="admin/captivate/html-block">click here</a>.',
        'decorators' => array('ViewHelper', array('Description', array('placement' => 'PREPEND', 'escape' => false))),
        'category' => 'SEAO - Responsive Captivate Theme',
        'type' => 'widget',
        'name' => 'captivate.html-block',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
            ),
        ),
    ),
        )
?>