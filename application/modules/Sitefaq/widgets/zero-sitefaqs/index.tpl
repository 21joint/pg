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

<?php if($this->total_results <= 0): ?>
	<div class="tip">
		<span>
			<?php echo $this->translate('No FAQs has been created yet.');  ?>
			<?php if ($this->can_create):  ?>
				<?php echo $this->translate('Be the first to %1$screate%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sitefaq_general").'">', '</a>'); ?>
			<?php endif; ?>
		</span>
	</div>
<?php endif; ?>