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

<?php if($this->owner_id): ?>
    <div class='seaocore_gutter_photo'>
      <?php echo $this->htmlLink($this->owner->getHref(), $this->itemPhoto($this->owner)) ?> 
      <?php echo $this->htmlLink($this->owner->getHref(), $this->owner->getTitle(), array('class' => 'seaocore_gutter_title')) ?>
    </div>	
<?php elseif($this->actionName == 'view'): ?>
  	<div class='seaocore_gutter_photo'>
    	<?php echo $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/User/externals/images/nophoto_user_thumb_profile.png', '', array('class' => 'thumb_profile item_nophoto')) ?>
    	<span class="seaocore_gutter_title"><?php echo $this->feedback->anonymous_name;?></span> <br /> 
    	<small><?php echo $this->feedback->anonymous_email;?></small>
    </div>
<?php endif; ?>
