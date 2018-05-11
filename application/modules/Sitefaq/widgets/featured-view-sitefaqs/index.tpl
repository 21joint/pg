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

<?php if($this->sitefaq->featured):?>
	<div class="sitefaq_featured_label"  style="background: <?php echo $this->featured_color; ?>;">
		<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitefaq/externals/images/star-img.png', '') ?>
		<?php echo $this->translate('FEATURED')?>
		<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitefaq/externals/images/star-img.png', '') ?>	
	</div>
<?php endif;?>