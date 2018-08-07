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
    'pgservicelayer_apilike' => array(
        'route' => 'api/:version/like/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'likes',
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
    'pgservicelayer_apiquestion' => array(
        'route' => 'api/:version/question/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'question',
          'action' => 'index',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
    'pgservicelayer_apianswer' => array(
        'route' => 'api/:version/answer/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'answer',
          'action' => 'index',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
    'pgservicelayer_apifollow' => array(
        'route' => 'api/:version/follow/:action/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'membership',
          'action' => 'index',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '(confirm|reject|index)',
        )
    ),
    'pgservicelayer_apisearch' => array(
        'route' => 'api/:version/search/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'search',
          'action' => 'index',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
    'pgservicelayer_apireaction' => array(
        'route' => 'api/:version/reaction/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'reaction',
          'action' => 'index',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
    'pgservicelayer_apirating' => array(
        'route' => 'api/:version/rating/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'rating',
          'action' => 'index',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
    'pgservicelayer_apiviews' => array(
        'route' => 'api/:version/action/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'action',
          'action' => 'index',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
    'pgservicelayer_apibadge' => array(
        'route' => 'api/:version/badge/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'badge',
          'action' => 'index',
          'version' => 'v1'
        ),
    ),
    'pgservicelayer_apimemberbadge' => array(
        'route' => 'api/:version/memberbadge/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'badge',
          'action' => 'memberbadge',
          'version' => 'v1'
        ),
    ),
    'pgservicelayer_apilogin' => array(
        'route' => 'api/:version/login/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'auth',
          'action' => 'login',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
    'pgservicelayer_apicontribution' => array(
        'route' => 'api/:version/contribution/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'contribution',
          'action' => 'index',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
    'pgservicelayer_apipermission' => array(
        'route' => 'api/:version/permission/*',
        'defaults' => array(
          'module' => 'pgservicelayer',
          'controller' => 'permission',
          'action' => 'index',
          'version' => 'v1'
        ),
        'reqs' => array(
          'action' => '\D+',
        )
    ),
);

return $routes;