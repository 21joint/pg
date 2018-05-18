<?php
return array(
  'package' =>
  array(
    'type' => 'module',
    'name' => 'advancedpagecache',
    'version' => '4.9.3',
    'path' => 'application/modules/Advancedpagecache',
    'title' => 'Page Cache Plugin - Speed up your Website',
    'description' => 'Page Cache Plugin - Speed up your Website',
    'author' => '<a href="http:\\\\www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
    'callback' =>
    array(
      'path' => 'application/modules/Advancedpagecache/settings/install.php',
      'class' => 'Advancedpagecache_Installer',
    ),
    'actions' =>
    array(
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
    ),
    'directories' =>
    array(
      0 => 'application/modules/Advancedpagecache',
    ),
    'files' =>
    array(
      0 => 'application/languages/en/advancedpagecache.csv',
    ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onItemDeleteAfter',
      'resource' => 'Advancedpagecache_Plugin_Cache',
    ),
    array(
      'event' => 'onItemCreateAfter',
      'resource' => 'Advancedpagecache_Plugin_Cache',
    ),
    array(
      'event' => 'onItemUpdateAfter',
      'resource' => 'Advancedpagecache_Plugin_Cache',
    ),
    array(
      'event' => 'onUserLoginAfter',
      'resource' => 'Advancedpagecache_Plugin_Cache',
    ),
    array(
      'event' => 'onUserLogoutAfter',
      'resource' => 'Advancedpagecache_Plugin_Cache',
    ),
    array(
      'event' => 'getAdminNotifications',
      'resource' => 'Advancedpagecache_Plugin_Core',
    ),
  ),
);
?>