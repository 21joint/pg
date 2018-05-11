<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$isActive = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.isActivate', 0);
if ( empty($isActive) ) {
  return;
}

$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
return array(
    array(
        'title' => $view->translate('Page Profile Offers'),
        'description' => $view->translate('This widget forms the Offers tab on the Page Profile and displays the offers of the Page. It should be placed in the Tabbed Blocks area of the Page Profile.'),
        'category' => $view->translate('Page Profile'),
        'type' => 'widget',
        'name' => 'sitepageoffer.sitemobile-profile-sitepageoffers',
        'defaultParams' => array(
            'title' => $view->translate('Offers'),
            'titleCount' => true,
        ),
    ),
    array(
        'title' => $view->translate('Page Offers'),
        'description' => $view->translate('Displays the list of Offers from Pages created on your community. This widget should be placed in the widgetized Page Offers page. Results from the Search Page Offers form are also shown here.'),
        'category' => $view->translate('Pages'),
        'type' => 'widget',
        'name' => 'sitepageoffer.sitepage-offer',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => $view->translate('Count'),
                        'description' => $view->translate('(number of offers to show)'),
                        'value' => 10,
												'validators' => array(
													array('Int', true),
													array('GreaterThan', true, array(0)),
												),
                    ),
                ),
            ),
        ),
    ),
		array(
				'title' => $view->translate('Page Offer View'),
				'description' => $view->translate("This widget should be placed on the Page Offer View Page."),
				'category' => $view->translate('Pages'),
				'type' => 'widget',
				'name' => 'sitepageoffer.offer-content',
				'defaultParams' => array(
						'title' => '',
						'titleCount' => true,
				),
		),
	)

?>