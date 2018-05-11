<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

if (!empty($this->isSiteadvsearchEnabled)):
  echo $this->content()->renderWidget('siteadvsearch.search-box', array('showLocationSearch' => $this->showLocationSearch, 'showLocationBasedContent' => $this->showLocationBasedContent));
else: ?>
  <form id="global_search_form" action="<?php echo $this->url(array('controller' => 'search'), 'default', true) ?>" method="get">
    <input style="width:500px;" type="text" class="text suggested" name="query" id="_titleAjax" size="20" maxlength="130" alt='<?php echo $this->translate('Search') ?>'>   
    <button type="button" onclick="this.form.submit();"></button> 
  </form>
<?php endif; ?>