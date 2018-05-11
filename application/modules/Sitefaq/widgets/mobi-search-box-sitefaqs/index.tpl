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

<div class="sitefaq_mobi_search">
	<form id='filter_form_search_box' method='get' action='<?php echo $this->url(array('action' => 'browse'), 'sitefaq_general', true) ?>'>
		<?php if($this->heading): ?>
			<div class="sitefaq_mobi_search_head">
				<?php echo $this->translate("Hi %s, what do you need help with?", $this->display_name);?>
			</div>
		<?php endif; ?>
		<input id="text" class="text" maxlength='100' name="search" type='text' placeholder="<?php echo $this->translate("Enter a keyword or question");?>" />
		<button value="submit"><?php echo $this->translate("Search") ?></button>
	</form>
</div>