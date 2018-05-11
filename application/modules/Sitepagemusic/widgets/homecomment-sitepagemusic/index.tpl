<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php 
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
?>
<ul class="sitepage_sidebar_list">
  <?php foreach ($this->paginator as $sitepagemusic): ?>
    <li>             
      <?php $this->sitepage_subject = Engine_Api::_()->getItem('sitepage_page', $sitepagemusic->page_id);?>
			<?php
			  $truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.truncation.limit', 13); 
			  $tmpBody = strip_tags($sitepagemusic->title);
			  $item_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
			?>	  
      <?php echo $this->htmlLink($sitepagemusic->getHref(), $this->itemPhoto($sitepagemusic, 'thumb.icon'), array('class' => 'thumb', 'title' => $sitepagemusic->getTitle())) ?>
      <div class='sitepage_sidebar_list_info'>
        <div class='sitepage_sidebar_list_title'>
          <?php
	          $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
	          $tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagemusic.profile-sitepagemusic', $sitepagemusic->page_id, $layout);
	          echo $this->htmlLink($sitepagemusic->getHref(), $item_title, array('title' => $sitepagemusic->title));
          ?>
        </div>
        <div class='sitepage_sidebar_list_details'>
          <?php
	          $truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.title.truncation', 18);
	          $tmpBody = strip_tags($sitepagemusic->page_title);
	          $page_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
          ?>
          <?php echo $this->translate("in ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($sitepagemusic->page_id, $sitepagemusic->owner_id, $sitepagemusic->getSlug()), $page_title, array('title' => $sitepagemusic->page_title)) ?> 
        </div>    
        <div class="sitepage_sidebar_list_details"> 
	        <?php echo $this->translate(array('%s comment', '%s comments', $sitepagemusic->comment_count), $this->locale()->toNumber($sitepagemusic->comment_count)) ?>,
					<?php echo $this->translate(array('%s view', '%s views', $sitepagemusic->view_count), $this->locale()->toNumber($sitepagemusic->view_count)) ?>      
				</div>   
      </div>
    </li>
  <?php endforeach; ?>
  <li class="sitepage_sidebar_list_seeall">
		<a href='<?php echo $this->url(array('commentedmusic'=> 1), 'sitepagemusic_browse', true) ?>'><?php echo $this->translate('See All');?> &raquo;</a>
	</li>
</ul>