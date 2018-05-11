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

<div class="quicklinks seaocore_gutter_blocks">
  <ul>
    <?php if(($this->countUserPublicFeedbacks > 0) && $this->feedback->owner_id): ?>
      <li> 
        <?php echo $this->htmlLink($this->url(array('user_id' => $this->feedback->owner_id), 'feedback_view'), $this->translate('View All Feedback'), array('class' => 'buttonlink icon_feedback')) ?> 
      </li>
    <?php endif; ?>
    <?php if (($this->feedback->owner_id == $this->viewer_id || $this->user_level == 1) && !empty($this->feedback->owner_id)):?>
      <li> 
        <?php echo $this->htmlLink(array('route' => 'feedback_edit', 'feedback_id' => $this->feedback->feedback_id), $this->translate('Edit Feedback'), array('class' => 'buttonlink icon_feedback_edit')) ?>
      </li>
      <?php if($this->allow_upload == 1 && !empty($this->feedback->owner_id)): ?>
        <li> 
          <?php echo $this->htmlLink(array(
            'route' => 'feedback_extended',
            'controller' => 'image',
            'action' => 'upload',
            'owner_id' => $this->feedback->owner_id,
            'subject' => $this->feedback->getGuid(),
          ), $this->translate('Add Pictures'), array(
            'class' => 'buttonlink icon_feedback_image_new'
          )) ?> 
        </li>
      <?php endif; ?>
    <?php endif; ?>
    <?php if ($this->feedback->owner_id == $this->viewer_id || $this->user_level == 1):?>
      <li> 
        <?php echo $this->htmlLink(array('route' => 'feedback_delete', 'feedback_id' => $this->feedback->feedback_id), $this->translate('Delete Feedback'), array(
          'class'=>'buttonlink  icon_feedback_delete'
        )) ?> 
      </li>
    <?php endif; ?>  
    <?php if(!empty($this->viewer_id)): ?>
      <li> 
        <?php echo $this->htmlLink(array('route' => 'feedback_manage', 'action' => 'manage'), $this->translate(' My Feedbacks'), array('class'=>'buttonlink  icon_feedback', 'target' => '_parent')) ?>
      </li> 
    <?php endif; ?>
   </ul>
</div> 
