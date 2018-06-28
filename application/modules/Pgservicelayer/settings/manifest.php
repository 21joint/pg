<?php 

$apiVersion = "v1";
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
    
  'routes' => array(
    'pgservicelayer_apireviews' => array(
        'route' => $apiVersion.'/reviews/:action/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'reviews',
          'action' => 'index',
          'format' => 'json'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
    'pgservicelayer_sitereview_categories' => array(
        'route' => $apiVersion.'/categorization/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'reviews',
          'action' => 'categories',
          'format' => 'json'
        ),
        'reqs' => array(
          'action' => 'categories',
        )
    ),
    'pgservicelayer_apiphotos' => array(
        'route' => $apiVersion.'/photo/:action/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'photo',
          'action' => 'index',
          'format' => 'json'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
      
    'pgservicelayer_apiranking' => array(
        'route' => $apiVersion.'/ranking/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'user',
          'action' => 'ranking',
          'format' => 'json'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
    'pgservicelayer_apimembers' => array(
        'route' => $apiVersion.'/members/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'user',
          'action' => 'index',
          'format' => 'json'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
    
  ),
); ?>