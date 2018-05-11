<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content.php 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$widgetSettings = array();
if (Engine_Api::_()->hasModuleBootstrap('sitecitycontent') && Engine_Api::_()->hasModuleBootstrap('siteadvsearch')) {
    $widgetSettings = array(
        'elements' => array(
            array(
                'Radio',
                'showLocationBasedContent',
                array(
                    'label' => 'Show results based on the location, saved in user’s browser cookie.',
                    'multiOptions' => array(
                        1 => 'Yes',
                        0 => 'No'
                    ),
                    'value' => 0
                )
            ),
            array(
                'Radio',
                'showLocationSearch',
                array(
                    'label' => 'Do you want to enable location based searching?',
                    'multiOptions' => array(
                        1 => 'Yes',
                        0 => 'No'
                    ),
                    'value' => 0
                )
    )));
}
return array(
    array(
        'title' => $view->translate('Responsive Luminous Theme - Landing Page Images'),
        'description' => $view->translate('Displays the multiple images uploaded by you for the Landing Page.'),
        'category' => 'SEAO - Responsive Luminous Theme',
        'type' => 'widget',
        'name' => 'siteluminous.homepage-images',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter width for the Landing Page Images (If left blank images will have 100% width)',
                        'value' => ''
                    )
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height for Landing Page Images',
                        'value' => 583
                    )
                ),
                array(
                    'Text',
                    'speed',
                    array(
                        'label' => 'Enter the duration in milliseconds (ms) after which images in the landing page images rotator should rotate.',
                        'value' => 5000
                    )
                ),
                array(
                    'Radio',
                    'order',
                    array(
                        'label' => 'How do you want the images in this rotator to rotate?',
                        'value' => 5000,
                        'multiOptions' => array(
                            2 => 'Random Order',
                            1 => 'Descending Order',
                            0 => 'Ascending Order'
                        ),
                        'value' => 2,
                    )
                ),
                array(
                    'Radio',
                    'show_login',
                    array(
                        'label' => "Do you want 'Login' button on this widget?",
                        'multiOptions' => array(
                            1 => 'Yes, show ‘Login’ button',
                            0 => 'No, do not show ‘Login’ button'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Radio',
                    'show_signup',
                    array(
                        'label' => "Do you want 'Signup' button on this widget?",
                        'multiOptions' => array(
                            1 => 'Yes, show ‘Signup’ button',
                            0 => 'No, do not show ‘Signup’ button'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Radio',
                    'show_login_popup',
                    array(
                        'label' => "Do you want to show Login popup when Login button is clicked on this widget?",
                        'multiOptions' => array(
                            1 => 'Yes, show Login popup',
                            0 => 'No, do not show Login popup'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Radio',
                    'show_signup_popup',
                    array(
                        'label' => "Do you want Signup popup when signup button is clicked on this widget?",
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
        'title' => $view->translate('Responsive Luminous Theme - Landing Page HTML Block'),
        'description' => $view->translate('You can place this widget on landing page and can set its layout and text from the tinymce editor available in the ‘Global Settings’ section of this plugin.'),
        'category' => 'SEAO - Responsive Luminous Theme',
        'type' => 'widget',
        'name' => 'siteluminous.homepage-blocks',
    ),
    array(
        'title' => $view->translate('Responsive Luminous Theme - Footer Text'),
        'description' => $view->translate('You can place this widget in the footer and can set text accordingly from the ‘Language Manager’ under ‘Layout’ section available in the admin panel of your site. For more detail, please read FAQ section from Admin Panel => Responsive Luminous Theme => FAQs'),
        'category' => 'SEAO - Responsive Luminous Theme',
        'type' => 'widget',
        'name' => 'siteluminous.homepage-footertext',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'show_signup_popup_footer',
                    array(
                        'label' => "Do you want Signup popup when create account button is clicked on this widget?",
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
        'title' => 'Responsive Luminous Theme - Footer Menu',
        'description' => 'Shows the site-wide footer menu. You can edit its contents in your menu editor.',
        'category' => 'SEAO - Responsive Luminous Theme',
        'type' => 'widget',
        'name' => 'siteluminous.menu-footer',
        'requirements' => array(
            'header-footer',
        ),
    ),
    array(
        'title' => 'Responsive Luminous Theme - Landing Search',
        'description' => 'Displays the Advanced Search Box on the landing page. [Dependent on Advanced Search Plugin, if you are not having this plugin global search box will be displayed.]',
        'category' => 'SEAO - Responsive Luminous Theme',
        'type' => 'widget',
        'name' => 'siteluminous.landing-search',
        'adminForm' => $widgetSettings
    ),
    array(
        'title' => 'Responsive Luminous Theme - Landing Page CSS',
        'description' => 'Responsive Luminous Theme - Landing Page CSS',
        'category' => 'SEAO - Responsive Luminous Theme',
        'type' => 'widget',
        'name' => 'siteluminous.landing-page-css'
    )
);
?>