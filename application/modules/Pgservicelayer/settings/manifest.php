<?php 

return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'pgservicelayer',
    'version' => '4.10.3',
    'path' => 'application/modules/Pgservicelayer',
    'title' => 'PG Service Layer',
    'description' => 'PG Service Layer',
    'author' => 'Stars Developer',
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
    'directories' => 
    array (
      0 => 'application/modules/Pgservicelayer',
    ),
    'files' => 
    array (
      0 => 'application/languages/en/pgservicelayer.csv',
    ),
  ),
    
  'routes' => array(),
); ?>