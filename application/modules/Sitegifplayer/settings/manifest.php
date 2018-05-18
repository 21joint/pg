<?php
return array(
  'package' =>
  array(
    'type' => 'module',
    'name' => 'sitegifplayer',
    'version' => '4.9.2p1',
    'path' => 'application/modules/Sitegifplayer',
    'title' => 'GIF Player',
    'description' => 'GIF Player',
    'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
    'callback' => array(
      'path' => 'application/modules/Sitegifplayer/settings/install.php',
      'class' => 'Sitegifplayer_Installer',
    ),
    'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '4.9.0',
      ),
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
      0 => 'application/modules/Sitegifplayer',
    ),
    'files' => array(
      0 => 'gif.php'
    ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onStorageFileCreateAfter',
      'resource' => 'Sitegifplayer_Plugin_Core',
    ),
    array(
      'event' => 'onStorageFileUpdateAfter',
      'resource' => 'Sitegifplayer_Plugin_Core',
    ),
  ),
);
?>