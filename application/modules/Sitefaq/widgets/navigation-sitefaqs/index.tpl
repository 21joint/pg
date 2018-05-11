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
<div class="headline">
	<h2>
	  <?php echo $this->translate('FAQs');  ?>
	</h2>
	<div class='tabs'>
	  <?php echo $this->navigation($this->navigation)->render() ?>
	</div>
</div>