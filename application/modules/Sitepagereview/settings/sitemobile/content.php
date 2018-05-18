<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$isActive = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.isActivate', 0);
if(empty($isActive)){ return; }

$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
return array(
    array(
        'title' => $view->translate('Page Profile Reviews'),
        'description' => $view->translate('This widget forms the Reviews tab on the Page Profile and displays the reviews of the Page. It should be placed in the Tabbed Blocks area of the Page Profile.'),
        'category' => $view->translate('Page Profile'),
        'type' => 'widget',
        'name' => 'sitepagereview.sitemobile-profile-sitepagereviews',
        'defaultParams' => array(
            'title' => $view->translate('Reviews'),
        ),
    ),
    array(
			'title' => $view->translate('Page Review View'),
			'description' => $view->translate("This widget should be placed on the Page Review View Page."),
      'category' => $view->translate('Pages'),
			'type' => 'widget',
			'name' => 'sitepagereview.review-content',
			'defaultParams' => array(
					'title' => '',
					'titleCount' => true,
			),
	  ),
    array(
        'title' => $view->translate('Page Reviews'),
        'description' => $view->translate('Displays the list of Reviews from Pages created on your community. This widget should be placed in the widgetized Page Reviews page. Results from the Search Page Reviews form are also shown here.'),
        'category' => $view->translate('Pages'),
        'type' => 'widget',
        'name' => 'sitepagereview.sitepage-review',
        'defaultParams' => array(
            'title' => $view->translate('Reviews'),
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => $view->translate('Count'),
                        'description' => $view->translate('(number of reviews to show)'),
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
)
?>