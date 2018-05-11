<?php return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'sitemetatag',
    'version' => '4.9.4',
    'path' => 'application/modules/Sitemetatag',
    'title' => 'Social Meta Tags Plugin',
    'description' => 'Social Meta Tags Plugin – Open Graph for Facebook, Google+, Pinterest and Twitter Cards',
    'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
    'callback' => 
    array (
      'path' => 'application/modules/Sitemetatag/settings/install.php',
      'class' => 'Sitemetatag_Installer',
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
      0 => 'application/modules/Sitemetatag',
    ),
    'files' => array(
      'application/languages/en/sitemetatag.csv',
    ),
  ),
  // hooks
  'hooks' => array(
      array(
        'event' => 'onRenderLayoutDefault',
        'resource' => 'Sitemetatag_Plugin_Core',
        ),
      array(
        'event' => 'onRenderLayoutDefaultSimple',
        'resource' => 'Sitemetatag_Plugin_Core',
        ),
      ),

); ?>