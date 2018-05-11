<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<div class='seaocore_gutter_photo'>
	<?php echo $this->htmlLink($this->owner->getHref(), $this->itemPhoto($this->owner)) ?>
  <?php echo $this->htmlLink($this->owner->getHref(), $this->owner->getTitle(), array('class' => 'seaocore_gutter_title')) ?>
</div>