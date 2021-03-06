<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<form id="global_search_form" action="<?php echo $this->url(array('controller' => 'search'), 'default', true) ?>" method="get">
    <input <?php if ($this->viewer()->getIdentity()): ?> style="width:<?php echo $this->searchbox_width; ?>px;" <?php else: ?> style="width:<?php echo $this->captivate_search_box_width_for_nonloggedin; ?>px;" <?php endif; ?> type="text" class="text suggested" name="query" id="_titleAjax" size="20" alt='<?php echo $this->translate('Search') ?>'>   
    <button type="button" onclick="this.form.submit();"></button> 
</form>