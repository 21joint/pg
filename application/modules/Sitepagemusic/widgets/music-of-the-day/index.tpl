<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
						$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagemusic.profile-sitepagemusic', $this->musicOfDay->page_id, $layout);?>
<ul class="generic_list_widget generic_list_widget_large_photo">
	<li>
		<div class="photo photo generic_list_widget_day">
			<?php echo $this->htmlLink($this->musicOfDay->getHref(),$this->itemPhoto($this->musicOfDay, null, $this->musicOfDay->getTitle()), array('class' => 'thumb', 'title' => $this->musicOfDay->getTitle())) ?>
		</div>
		<div class="info clr">
			<div class="title">
			  <?php echo $this->htmlLink($this->musicOfDay->getHref(array('tab' => $tab_id)), Engine_Api::_()->sitepagemusic()->truncation($this->musicOfDay->getTitle()), array('title' => $this->musicOfDay->getTitle())); ?>
			</div>
	    <div class="owner">
				<?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $this->musicOfDay->page_id);?>
				<?php
				$truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.title.truncation', 18);
				$tmpBody = strip_tags($sitepage_object->title);
				$page_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
				?>	
			<?php echo $this->translate("in ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($this->musicOfDay->page_id, $this->musicOfDay->owner_id, $this->musicOfDay->getSlug()),  $page_title,array('title' => $sitepage_object->title)) ?>      
			</div>	
		</div>
	</li>
</ul>		