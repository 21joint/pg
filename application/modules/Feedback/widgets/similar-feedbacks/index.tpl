<?php 
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Document
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2010-08-11 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<div class="seaocore_gutter_blocks generic_layout_container">
  <ul class="seaocore_sidebar_list">
    <?php $count = 1;?>
    <?php foreach( $this->feedbacks as $feedback ): ?>
      <?php if($count > $this->itemCount):?>
        <li> 
          <?php echo $this->htmlLink($this->url(array('user_id' => $feedback->owner_id), 'feedback_view'), $this->translate('More &raquo;'), array('class'=>'more_link')) ?> 
        </li>
        <?php break;?>
      <?php endif;?>
      <li>
      	<?php $user = Engine_Api::_()->getItem('user', $feedback->owner_id); ?>
        <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon')) ?>    
        <div class='seaocore_sidebar_list_info'>
          <div class='seaocore_sidebar_list_title'> 
            <?php echo $this->htmlLink($feedback->getHref(), Engine_Api::_()->seaocore()->seaocoreTruncateText($feedback->feedback_title, $this->truncationLimit),  array('target'=>'_parent', 'title' => $feedback->getTitle())) ?> 
          </div>
          <div class='seaocore_sidebar_list_details'> 
            <?php echo $this->translate(array('%s vote', '%s votes', $feedback->total_votes), $this->locale()->toNumber($feedback->total_votes)) ?> | 
            <?php echo $this->translate(array('%s comment', '%s comments', $feedback->comment_count), $this->locale()->toNumber($feedback->comment_count))?> | 
            <?php echo $this->translate(array('%s view', '%s views', $feedback->views), $this->locale()->toNumber($feedback->views))?> 
          </div>
        </div>
      </li>
      <?php $count++ ; ?>
    <?php endforeach; ?>
  </ul>
</div>