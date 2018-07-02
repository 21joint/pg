<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$routes = array();

$routes['pgservicelayer'] = array(
    'pgservicelayer_apireviews' => array(
        'route' => 'api/:version/review/:action/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'reviews',
          'action' => 'index',
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
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
    'pgservicelayer_apicomment' => array(
        'route' => 'api/:version/comment/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'comments',
          'action' => 'index',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
    'pgservicelayer_apitopic' => array(
        'route' => 'api/:version/topic/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'topic',
          'action' => 'index',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
);

return $routes;