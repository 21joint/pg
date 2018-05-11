<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2015-05-15 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
if ($this->captivateSearchBox == 3) {
    echo $this->content()->renderWidget('sitevideo.searchbox-video-sitevideo', array('locationDetection' => 0, 'formElements' => array('textElement', 'categoryElement', 'locationElement', 'locationmilesSearch'), 'categoriesLevel' => array('category'), 'showAllCategories' => 0, 'textWidth' => 125, 'locationWidth' => 125, 'locationmilesWidth' => 125, 'categoryWidth' => 125));
}
if ($this->captivateSearchBox == 2) {
    echo $this->content()->renderWidget('siteevent.searchbox-siteevent', array('locationDetection' => 0, 'formElements' => array('textElement', 'categoryElement', 'locationElement', 'locationmilesSearch'), 'categoriesLevel' => array('category'), 'showAllCategories' => 0, 'textWidth' => 125, 'locationWidth' => 125, 'locationmilesWidth' => 125, 'categoryWidth' => 125));
} else if ($this->captivateSearchBox == 1) {
    if ($this->isSiteadvsearchEnabled) {
        echo $this->content()->renderWidget('siteadvsearch.search-box', array('showLocationSearch' => $this->showLocationSearch, 'showLocationBasedContent' => $this->showLocationBasedContent));
    } else {
        ?>
        <form id="global_search_form" action="<?php echo $this->url(array('controller' => 'search'), 'default', true) ?>" method="get">
            <input style="width:500px;" type="text" class="text suggested" name="query" id="_titleAjax" size="20" maxlength="130" alt='<?php echo $this->translate('Search') ?>'>   
            <button type="button" onclick="this.form.submit();"></button> 
        </form>
        <?php
    }
}