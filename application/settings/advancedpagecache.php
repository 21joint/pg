<?php
defined('_ENGINE') or die('Access Denied');
return array (
  'browser_lifetime' => 600,
  'ignoreUrl' => 
  array (
    0 => '/activity/notifications',
    1 => '/members/home',
  ),
  'utilization_space' => '1',
  'default_backend' => 'File',
  'frontend' => 
  array (
    'lifetime' => '1200',
    'cache_id_prefix' => 'advancedpagecache_page_',
    'default_options' => 
    array (
      'cache_with_get_variables' => true,
      'cache_with_cookie_variables' => true,
      'cache_with_session_variables' => true,
      'tags' => 
      array (
        0 => 'browse_cache',
      ),
      'cache' => true,
    ),
    'memorize_headers' => 
    array (
      0 => 'location',
    ),
  ),
  'backend' => 
  array (
    'Memcached' => 
    array (
      'servers' => 
      array (
        0 => 
        array (
          'host' => '127.0.0.1',
          'port' => 11211,
        ),
        1 => 
        array (
          'host' => '127.0.0.1',
          'port' => 6379,
        ),
      ),
      'compression' => false,
    ),
  ),
  'disable_browse' => '0',
  'default_file_path' => '/home/parentalguidance/public_html/temporary/sitecache',
); ?>