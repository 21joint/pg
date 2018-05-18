<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'sitefaq', 
    'version' => '4.9.4',
    'path' => 'application/modules/Sitefaq',
    'title' => 'FAQs, Knowledgebase, Tutorials & Help Center Plugin',
    'description' => 'FAQs, Knowledgebase, Tutorials & Help Center Plugin',
'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
    'callback' => array(
      'path' => 'application/modules/Sitefaq/settings/install.php',
      'class' => 'Sitefaq_Installer',
    ),
    'actions' => 
    array (
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
    ),
    'directories' => 
    array (
      0 => 'application/modules/Sitefaq',
    ),
    'files' => 
    array (
      0 => 'application/languages/en/sitefaq.csv',
    )
	),
    'sitemobile_compatible' =>true,
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onUserDeleteBefore',
      'resource' => 'Sitefaq_Plugin_Core',
    ),
		array(
				'event' => 'onRenderLayoutDefault',
				'resource' => 'Sitefaq_Plugin_Core'
		),
      array(
            'event' => 'onRenderLayoutMobileSMDefault',
            'resource' => 'Sitefaq_Plugin_Sitemobile',
        ),
  ),
	//Items ---------------------------------------------------------------------
	'items' => array(
			'sitefaq_faq',
			'sitefaq_question',
			'sitefaq_category',
      'sitefaq_option'
	),
	// Route--------------------------------------------------------------------
    'routes' => array(
        'sitefaq_general' => array(
            'route' => 'faqs/:action/*',
            'defaults' => array(
                'module' => 'sitefaq',
                'controller' => 'index',
                'action' => 'home',
            ),
            'reqs' => array(
                'action' => '(home|browse|create|manage|question|print|tagscloud|mobi-home|mobi-browse|upload-photo)',
            ),
        ),
				'sitefaq_specific' => array(
					'route' => 'faq/:action/:faq_id/*',
					'defaults' => array(
							'module' => 'sitefaq',
							'controller' => 'index',
						'action' => 'view',
					),
					'reqs' => array(
						'action' => '(edit|publish|delete|print-view)',
						'faq_id' => '\d+',
					)
				),
				'sitefaq_category' => array(
						'route' => 'faq/category/:action/*',
						'defaults' => array(
								'module' => 'sitefaq',
								'controller' => 'index',
								'action' => 'sub-category',
						),
						'reqs' => array(
								'action' => '(sub-category|subsub-category)',
						),
				),
        'sitefaq_view' => array(
            'route' => 'faq/view/:faq_id/:category_id/:subcategory_id/:subsubcategory_id/:slug/*',
            'defaults' => array(
                'module' => 'sitefaq',
                'controller' => 'index',
                'action' => 'view',
								'category_id' => 0,
								'subcategory_id' => 0,
								'subsubcategory_id' => 0,
                'slug' => '',
            ),
            'reqs' => array(
                'faq_id' => '\d+'
            )
        ),
        'sitefaq_general_category' => array(
            'route' => 'faqs/browse/:category/:categoryname/*',
            'defaults' => array(
                'module' => 'sitefaq',
                'controller' => 'index',
                'action' => 'browse',
            ),
            'reqs' => array(
                'category' => '\d+'           
            ),
        ),
        'sitefaq_general_subcategory' => array(
            'route' => 'faqs/browse/:category/:categoryname/:subcategory/:subcategoryname/*',
            'defaults' => array(
                'module' => 'sitefaq',
                'controller' => 'index',
                'action' => 'browse',
            ),
            'reqs' => array(
                'category' => '\d+',
                'subcategory' => '\d+'
           
            ),
         ),
        'sitefaq_general_subsubcategory' => array(
            'route' => 'faqs/browse/:category/:categoryname/:subcategory/:subcategoryname/:subsubcategory/:subsubcategoryname/*',
            'defaults' => array(
                'module' => 'sitefaq',
                'controller' => 'index',
                'action' => 'browse',
            ),
            'reqs' => array(
                'category' => '\d+',
                'subcategory' => '\d+',
								'subsubcategory' => '\d+'
            ),
         ),
			)
);
