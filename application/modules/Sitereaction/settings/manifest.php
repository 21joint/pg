<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: mainfest.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

return array(
  'package' =>
  array(
    'type' => 'module',
    'name' => 'sitereaction',
    'version' => '4.9.4',
    'path' => 'application/modules/Sitereaction',
    'title' => 'Reactions & Stickers Plugin',
    'description' => 'Reactions & Stickers Plugin',
    'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
    'callback' =>
    array(
      'path' => 'application/modules/Sitereaction/settings/install.php',
      'class' => 'Sitereaction_Installer',
        'priority' => 1600,
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
      0 => 'application/modules/Sitereaction',
    ),
    'files' =>
    array(
      0 => 'application/languages/en/sitereaction.csv',
    ),
  ),
  // Mobile / Tablet Plugin Compatible
  'sitemobile_compatible' => true,
// Hooks ---------------------------------------------------------------------
    'hooks' => array(
        array(
            'event' => 'onRenderLayoutDefault',
            'resource' => 'Sitereaction_Plugin_Core',
        ),
        array(
            'event' => 'onRenderLayoutDefaultSimple',
            'resource' => 'Sitereaction_Plugin_Core',
        ),
        array(
            'event' => 'onRenderLayoutMobileDefault',
            'resource' => 'Sitereaction_Plugin_Core',
        ),
        array(
            'event' => 'onActivityLikeDeleteBefore',
            'resource' => 'Sitereaction_Plugin_Core',
        ),
        array(
            'event' => 'onCoreLikeDeleteBefore',
            'resource' => 'Sitereaction_Plugin_Core',
        ),
    ),
    // Items ---------------------------------------------------------------------
  'items' => array(
    'sitereaction_reactionicon',
    'sitereaction_collection',
    'sitereaction_sticker',
    'sitereaction_stickersearch'
  ),
  //Composer--------------------------------------------------------------------------------
  'composer'=>array(
    'sticker' => array(
      'script' => array('_composeSticker.tpl', 'sitereaction'),
      'plugin' => 'Sitereaction_Plugin_Composer_Sticker',
    ),
  )
);
?>
