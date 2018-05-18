<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<ul class="sitepage_sidebar_list">
  <?php foreach( $this->paginator as $user ): ?>
    <li>
      <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon'), array('class' => 'popularmembers_thumb', 'title' => $user->getTitle()), array('title' => $user->getTitle())) ?>
      <div class='sitepage_sidebar_list_info'>
        <div class='sitepage_sidebar_list_title'>
          <?php echo $this->htmlLink($user->getHref(), $user->getTitle(), array('title' =>  $user->getTitle())) ?>
        </div>
        <div class='sitepage_sidebar_list_details'>
          <?php echo $this->translate(array('%s review', '%s reviews', $user->review_count),$this->locale()->toNumber($user->review_count)) ?>
        </div>
      </div>
    </li>
  <?php endforeach; ?>
</ul>
