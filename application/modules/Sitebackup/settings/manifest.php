<?php
return array(
  'package' =>
  array(
    'type' => 'module',
    'name' => 'sitebackup',
    'version' => '4.9.4',
    'path' => 'application/modules/Sitebackup',
    'title' => 'Website Backup and Restore',
    'description' => 'Website Backup and Restore',
    'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
    'callback' =>
    array(
      'path' => 'application/modules/Sitebackup/settings/install.php',
      'class' => 'Sitebackup_Installer',
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
      0 => 'application/modules/Sitebackup',
    ),
    'files' =>
    array(
      0 => 'application/languages/en/sitebackup.csv',
    ),
  // Items ---------------------------------------------------------------------
  ),
  'items' => array(
    'sitebackup_backup',
    'sitebackup_destinations',
    'sitebackup_backuplog'
  ),
);
?>