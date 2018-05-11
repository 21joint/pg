<?php
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: partialWidget.tpl 6590 2010-12-31 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php 
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
?>
<li>      
    <?php echo $this->htmlLink($this->sitepagemusic->getHref(), $this->itemPhoto($this->sitepagemusic, 'thumb.icon'), array('class' => 'thumb', 'title' => $this->sitepagemusic->getTitle())) ?>
	<?php
	  $truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.truncation.limit', 13); 
	  $tmpBody = strip_tags($this->sitepagemusic->getTitle());
	  $item_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
	?>		      
	<div class="sitepage_sidebar_list_info">
  <div class="sitepage_sidebar_list_title" title="<?php echo $this->sitepagemusic->getTitle();?>">        
    <?php echo $this->htmlLink($this->sitepagemusic->getHref(), $item_title) ?>
  </div>
  <div class="sitepage_sidebar_list_details">                  