<?php return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'sdparentalguide',
    'version' => '4.10.2',
    'path' => 'application/modules/Sdparentalguide',
    'title' => 'SD - Guidance Guide Settings',
    'description' => 'Guidance Guide Settings Custom Module',
    'author' => 'Stars Developer',
    'callback' => 
    array (
      'class' => 'Engine_Package_Installer_Module',
    ),
//    'callback' => array(
//            'path' => 'application/modules/Sdparentalguide/settings/install.php',
//            'class' => 'Sdparentalguide_Installer',
//            'priority' => 2000,
//    ),
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
      0 => 'application/modules/Sdparentalguide',
      1 => 'externals/masonry',
    ),
    'files' => array(
      'application/languages/en/sdparentalguide.csv',
    ),
  ),
  'hooks' => array(
        array(
            'event' => 'onItemCreateBefore',
            'resource' => 'Sdparentalguide_Plugin_Core',
        ),
        array(
            'event' => 'onItemCreateAfter',
            'resource' => 'Sdparentalguide_Plugin_Core',
        ),
        array(
            'event' => 'onItemDeleteBefore',
            'resource' => 'Sdparentalguide_Plugin_Core',
        ),
        array(
            'event' => 'onItemUpdateAfter',
            'resource' => 'Sdparentalguide_Plugin_Core',
        ),
        array(
            'event' => 'onRenderLayoutAdmin',
            'resource' => 'Sdparentalguide_Plugin_Core',
        ),
        array(
            'event' => 'onRenderLayoutAdminSimple',
            'resource' => 'Sdparentalguide_Plugin_Core',
        ),
        array(
            'event' => 'onRenderLayoutDefault',
            'resource' => 'Sdparentalguide_Plugin_Core',
        ),
        array(
            'event' => 'onActivityActionCreateAfter',
            'resource' => 'Sdparentalguide_Plugin_Core',
            'priority' => '1000'
        ),
  ),
  'items' => array(
      'sdparentalguide_badge',
      'sdparentalguide_assigned_badge',
      'sdparentalguide_topic',
      'sdparentalguide_preference',
      'sdparentalguide_listing_topic',
      'sdparentalguide_search_term',
      'sdparentalguide_search_terms_alias',
      'sdparentalguide_listing_rating',
      'sdparentalguide_search_analytic',
  ),
  'routes' => array(
    'sdparentalguide_general' => array(
        'route' => 'gg/:action/*',
        'defaults' => array(
          'module' => 'sdparentalguide',
          'controller' => 'index',
          'action' => 'index',
        ),
        'reqs' => array(
          'action' => '(index|listings|suggest-username|suggest-displayname|suggest-category|get-subcategories|get-categories|categories|assignbadges|assign-user|assign-quick|assign-bulk|assign-status|suggest-topic)',
        ),
    ),
    'sdparentalguide_preferences' => array(
        'route' => 'preferences/:action/*',
        'defaults' => array(
          'module' => 'sdparentalguide',
          'controller' => 'preferences',
          'action' => 'index',
        ),
        'reqs' => array(
          'action' => '(edit|index)',
        ),
    ),
    'sdparentalguide_family' => array(
        'route' => 'family/members/:action/*',
        'defaults' => array(
          'module' => 'sdparentalguide',
          'controller' => 'family',
          'action' => 'index',
        ),
        'reqs' => array(
          'action' => '(edit|index|add|delete)',
        ),
    ),
  )
); ?>