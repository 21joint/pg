<?php return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'ggcommunity',
    'version' => '4.9.6p1',
    'path' => 'application/modules/Ggcommunity',
    'title' => 'Guidance Guide Community',
    'description' => '',
    'author' => 'EXTFOX',
    'callback' => 
    array (
      'class' => 'Engine_Package_Installer_Module',
    ),
    'actions' => 
    array (
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
    ),
    'callback' => array(
      'path' => 'application/modules/Ggcommunity/settings/install.php',
      'class' => 'Ggcommunity_Installer',
    ),
    'directories' => array(
      'application/modules/Ggcommunity',
    ),
    'files' => array(
      'application/languages/en/ggcommunity.csv',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'ggcommunity',
    'ggcommunity_question',
    'ggcommunity_answer',
    'ggcommunity_comment',
    'ggcommunity_vote'
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onItemDeleteBefore',
      'resource' => 'Ggcommunity_Plugin_Core',
    ),
    array(
      'event' => 'onUserDeleteBefore',
      'resource' => 'Ggcommunity_Plugin_Core',
    ), 
    array(
      'event' => 'onAnswerCreateBefore',
      'resource' => 'Ggcommunity_Plugin_Core',
    ),
    array(
      'event' => 'onCommentCreateBefore',
      'resource' => 'Ggcommunity_Plugin_Core',
    ),
    array(
      'event' => 'onItemEditBefore',
      'resource' => 'Ggcommunity_Plugin_Core',
    ), 
    array (
      'event' => 'onRenderLayoutDefault',
      'resource' => 'Ggcommunity_Plugin_Core',
    )
  ),

  // Routes --------------------------------------------------------------------
  'routes' => array(
    // Specific Question - Options
    'question_options' => array(
      'route' => 'ggcommunity/:action/:question_id/*',
      'defaults' => array(
        'module' => 'ggcommunity',
        'controller' => 'question-profile',
        'action' => ''
      ),
      'reqs' => array(
        'action' => '(edit|delete)',
        'question_id' => '\d+',
      )
    ),

    // Question profile
    'question_profile' => array(
      'route' => 'ggcommunity/question/:question_id/*',
      'defaults' => array(
        'module' => 'ggcommunity',
        'controller' => 'question-profile',
        'action' => 'view',
      ),
      'reqs' => array(
        'question_id' => '\d+',
      )
    ),

    // List Struggles
    'listing_struggles' => array(
      'route' => 'ggcommunity/home',
      'defaults' => array(
        'module' => 'ggcommunity',
        'controller' => 'question-index',
        'action' => 'list',
      ),
    ),

    // Browse Struggles
    'browse_struggles' => array(
      'route' => 'ggcommunity/browse',
      'defaults' => array(
        'module' => 'ggcommunity',
        'controller' => 'question-index',
        'action' => 'browse',
      )
    ),

    // Manage Struggles
    'ggcommunity_manage' => array(
      'route' => 'ggcommunity/question/manage',
      'defaults' => array(
        'module' => 'ggcommunity',
        'controller' => 'question-index',
        'action' => 'manage',
      ),
      'reqs' => array(
       
      )
    ),

    // Leaderboard 
    'ggcommunity_leaderboard' => array(
      'route' => 'ggcommunity/leaderboard',
      'defaults' => array(
        'module' => 'ggcommunity',
        'controller' => 'question-index',
        'action' => 'leaderboard',
      ),
      'reqs' => array(
       
      )
    ),
   
   
  )

); ?>