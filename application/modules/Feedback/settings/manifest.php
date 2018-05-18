<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
return array(
    // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'module',
    'name' => 'feedback',
    'version' => '4.9.2',
    'path' => 'application/modules/Feedback',
    'repository' => 'null',
		'title' => 'Feedback',
		'description' => 'Collect and act upon valuable feedback and ideas from your community.',
'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
		'date' => 'Friday, 09 Jul 2010 18:33:08 +0000',
		'copyright' => 'Copyright 2009-2010 BigStep Technologies Pvt. Ltd.',
    'actions' => array(
       'install',
       'upgrade',
       'refresh',
       'enable',
       'disable',
     ),
    'callback' => array(
      'path' => 'application/modules/Feedback/settings/install.php',
      'class' => 'Feedback_Installer',
        'priority' => 1740,
    ),
    'directories' => array(
      'application/modules/Feedback',
    ),
    'files' => array(
      'application/languages/en/feedback.csv',
    ),
  ),
 'sitemobile_compatible' =>true,
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onUserDeleteBefore',
      'resource' => 'Feedback_Plugin_Core',
    ),
    array(
      'event' => 'onRenderLayoutDefault',
      'resource' => 'Feedback_Plugin_Core',
    ),
    array(
      'event' => 'onRenderLayoutDefaultSimple',
      'resource' => 'Feedback_Plugin_Core',
    ),       
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'feedback',
    'feedback_album',
    'feedback_image',
    'feedback_vote',
    'feedback_blockuser',
    'feedback_blockip',
  	'feedback_category',
  	'feedback_severity',
  	'feedback_status'
  ),
  // Routes --------------------------------------------------------------------
  
  'routes' => array(
    'feedback_extended' => array(
      'route' => 'feedbacks/:controller/:action/*',
      'defaults' => array(
        'module' => 'feedback',
        'controller' => 'index',
        'action' => 'index',
      ),
      'reqs' => array(
        'controller' => '\D+',
        'action' => '\D+',
      )
    ),
    
     'feedback_create' => array(
      'route' => 'feedbacks/create',
      'defaults' => array(
        'module' => 'feedback',
        'controller' => 'index',
        'action' => 'create'
      )
    ),

		'feedback_newcreate' => array(
      'route' => 'feedbacks/newcreate',
      'defaults' => array(
        'module' => 'feedback',
        'controller' => 'index',
        'action' => 'newcreate'
      ),
    ),
    
    'feedback_delete' => array(
      'route' => 'feedbacks/delete/:feedback_id',
      'defaults' => array(
        'module' => 'feedback',
        'controller' => 'index',
        'action' => 'delete'
      ),
      'reqs' => array(
        'feedback_id' => '\d+'
      )
    ),
   
    'feedback_featured' => array(
      'route' => 'feedbacks/featured/:feedback_id',
      'defaults' => array(
        'module' => 'feedback',
        'controller' => 'index',
        'action' => 'featured'
      ),
      'reqs' => array(
        'feedback_id' => '\d+'
      )
    ),
    
     'feedback_browse' => array(
      'route' => 'feedback/:page/:sort/*',
       'defaults' => array(
         'module' => 'feedback',
         'controller' => 'index',
         'action' => 'browse',
        'page' => 1,
         'sort' => 'recent',
       ),
      'reqs' => array(
        'page' => '\d+',
      )
     ),
 
    'feedback_manage' => array(
      'route' => 'feedbacks/manage',
      'defaults' => array(
        'module' => 'feedback',
        'controller' => 'index',
        'action' => 'manage'
      )
    ),
    
    'feedback_edit' => array(
      'route' => 'feedbacks/edit/:feedback_id',
      'defaults' => array(
        'module' => 'feedback',
        'controller' => 'index',
        'action' => 'edit'
      )
    ),
    
    'feedback_view' => array(
      'route' => 'feedbacks/:user_id/*',
      'defaults' => array(
        'module' => 'feedback',
        'controller' => 'index',
        'action' => 'list',
      ),
      'reqs' => array(
        'user_id' => '\d+'
      )
    ),
    
    'feedback_removeimage' => array(
      'route' => 'feedbacks/image/remove/:feedback_id/:image_id/*',
      'defaults' => array(
        'module' => 'feedback',
        'controller' => 'image',
        'action' => 'remove',
      ),
    ),
    
    'feedback_detail_view' => array(
      'route' => 'feedbacks/:user_id/:feedback_id/:slug/*',
      'defaults' => array(
        'module' => 'feedback',
        'controller' => 'index',
        'action' => 'view',
        'slug' => '',
      ),
      'reqs' => array(
        'user_id' => '\d+',
        'feedback_id' => '\d+'
      )
    ),
    
    'feedback_success' => array(
      'route' => 'feedbacks/success/:feedback_id',
      'defaults' => array(
        'module' => 'feedback',
        'controller' => 'index',
        'action' => 'success'
      )
    ),

		 'feedback_newsuccess' => array(
      'route' => 'feedbacks/newsuccess/:feedback_id',
      'defaults' => array(
        'module' => 'feedback',
        'controller' => 'index',
        'action' => 'newsuccess'
      )
    ),
    
     'feedback_image_specific' => array(
      'route' => 'feedbacks/image/view/:owner_id/:album_id/:image_id/*',
      'defaults' => array(
        'module' => 'feedback',
        'controller' => 'image',
        'action' => 'view'
      ),
      'reqs' => array(
        'action' => '(view)',
      ),
    ),
  )
   
); 
