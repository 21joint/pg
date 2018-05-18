<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

return array(
  array(
    'title' => 'Reactions and Stickers',
    'description' => 'Adds reaction and stickers to the plugin',
    'category' => 'Sitereaction',
    'type' => 'plugin',
    'name' => 'reactions_and_stickers',
    'isPaginated' => true,
    'defaultParams' => array(
      'title' => 'reactions',
      'titleCount' => true,
    ),
    'requirements' => array(
      'subject' => 'user',
    ),
  ),
  )
?>
