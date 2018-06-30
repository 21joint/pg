<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$routes = array();

$routes['pgservicelayer'] = array(
    'pgservicelayer_apireviews' => array(
        'route' => 'api/:version/reviews/:action/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'reviews',
          'action' => 'index',
          'format' => 'json',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
    'pgservicelayer_sitereview_categories' => array(
        'route' => 'api/:version/categorization/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'reviews',
          'action' => 'categories',
          'format' => 'json',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => 'categories',
        )
    ),
    'pgservicelayer_apiphotos' => array(
        'route' => 'api/:version/photo/:action/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'photo',
          'action' => 'index',
          'format' => 'json',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
      
    'pgservicelayer_apiranking' => array(
        'route' => 'api/:version/ranking/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'user',
          'action' => 'ranking',
          'format' => 'json',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
    'pgservicelayer_apimembers' => array(
        'route' => 'api/:version/member/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'user',
          'action' => 'index',
          'format' => 'json',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),    
);

return $routes;