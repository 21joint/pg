<?php return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'sdparentalguide',
    'version' => '4.10.3p5',
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
      'application/libraries/Zend/Db/Table/Row/Abstract.php',
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
            'event' => 'onItemUpdateBefore',
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
//        array(
//            'event' => 'onCreditCreateAfter',
//            'resource' => 'Sdparentalguide_Plugin_Core',
//        ),
  ),
  'items' => array(
      'sdparentalguide_badge',
      'sdparentalguide_assigned_badge',
      'sdparentalguide_topic',
      'sdparentalguide_task',
      'sdparentalguide_preference',
      'sdparentalguide_search_term',
      'sdparentalguide_search_terms_alias',
      'sdparentalguide_listing_rating',
      'sdparentalguide_search_analytic',
      'sdparentalguide_guide',
      'sdparentalguide_guide_item',
  ),
  'routes' => array(
    //Extended Route
    'sdparentalguide_extended' => array(
        'route' => 'gg/:controller/:action/*',
        'defaults' => array(
          'module' => 'sdparentalguide',
          'controller' => 'ajax',
          'action' => 'index',
        ),
        'reqs' => array(
          'controller' => '\D+',
          'action' => '\D+',
        )
    ),
    //Routes Sprint 7
    //PG assign badges  
    'sdparentalguide_badges_assign' => array(
        'route' => 'badges/assign/*',
        'defaults' => array(
          'module' => 'sdparentalguide',
          'controller' => 'index',
          'action' => 'assignbadges',
        ),
    ),
    // PG assign badger to user  
    'sdparentalguide_badger_assignuser' => array(
        'route' => 'badges/assignbadges/user/*',
        'defaults' => array(
          'module' => 'sdparentalguide',
          'controller' => 'index',
          'action' => 'assign-user',
        ),
    ),
    //PG reviews listings or grade
    'sdparentalguide_reviews_grade' => array(
        'route' => 'reviews/grade/*',
        'defaults' => array(
          'module' => 'sdparentalguide',
          'controller' => 'index',
          'action' => 'listings',
        ),
    ),
    //PG Core Search
    'sdparentalguide_core_search' => array(
        'route' => 'se-search/*',
        'defaults' => array(
          'module' => 'core',
          'controller' => 'search',
          'action' => 'index',
        ),
        'reqs' => array(
          'action' => '(index)',
        ),
    ),  
    //PG Home 
    'sdparentalguide_user_home' => array(
        'route' => 'home/*',
        'defaults' => array(
          'module' => 'user',
          'controller' => 'index',
          'action' => 'home',
        ),
    ),
    //PG Default Profile 
    'sdparentalguide_default_profile' => array(
        'route' => 'profile/*',
        'defaults' => array(
          'module' => 'user',
          'controller' => 'profile',
          'action' => 'index',
        ),
    ), 
    //General Route
    'sdparentalguide_general' => array(
        'route' => 'gg/:action/*',
        'defaults' => array(
          'module' => 'sdparentalguide',
          'controller' => 'index',
          'action' => 'index',
        ),
        'reqs' => array(
          'action' => '(index|listings|suggest-username|suggest-displayname|suggest-category|get-subcategories|get-categories|categories|assign-quick|assign-bulk|assign-status|suggest-topic)',
        ),
    ),
    //PG Reviews
    'sdparentalguide_reviews' => array(
        'route' => 'reviews/:action/*',
        'defaults' => array(
          'module' => 'sdparentalguide',
          'controller' => 'reviews',
          'action' => 'home',
        ),
        'reqs' => array(
          'action' => '(home|create|view)',
        ),
    ),
    //PG Guides
    'sdparentalguide_guides' => array(
        'route' => 'guides/:action/*',
        'defaults' => array(
          'module' => 'sdparentalguide',
          'controller' => 'guides',
          'action' => 'home',
        ),
        'reqs' => array(
          'action' => '(home|create|view)',
        ),
    ),
    //PG Community
    'sdparentalguide_community' => array(
        'route' => 'community/:action/*',
        'defaults' => array(
          'module' => 'sdparentalguide',
          'controller' => 'community',
          'action' => 'home',
        ),
        'reqs' => array(
          'action' => '(home|leaderboard)',
        ),
    ),
    //PG Search
    'sdparentalguide_search' => array(
        'route' => 'search/*',
        'defaults' => array(
          'module' => 'sdparentalguide',
          'controller' => 'search',
          'action' => 'index',
        ),
        'reqs' => array(
          'action' => '(index)',
        ),
    ),
    //PG Core Search
    'sdparentalguide_core_search' => array(
        'route' => 'se-search/*',
        'defaults' => array(
          'module' => 'core',
          'controller' => 'search',
          'action' => 'index',
        ),
        'reqs' => array(
          'action' => '(index)',
        ),
    ),
    //PG Topics
    'sdparentalguide_topics' => array(
        'route' => 'topics/:action/*',
        'defaults' => array(
          'module' => 'sdparentalguide',
          'controller' => 'topics',
          'action' => 'home',
        ),
        'reqs' => array(
          'action' => '(home)',
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