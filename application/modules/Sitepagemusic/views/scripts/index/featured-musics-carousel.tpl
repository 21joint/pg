<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: featured-musics-carousel.tpl 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php if ($this->direction == 1) { ?>
 <?php  $j=0; $offset=$this->offset; ?>

    <?php foreach ($this->featuredMusics as $music): ?>
      <?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
			$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagemusic.profile-sitepagemusics', $music->page_id, $layout); ?>
      <?php if($j% $this->itemsVisible ==0):?>
        <div class="Sitepagecontent_SlideItMoo_element Sitepagemusic_SlideItMoo_element" style="width:<?php echo 146 * $this->inOneRow; ?>px;">
        <div class="Sitepagecontent_SlideItMoo_contentList">
      <?php endif;?>
      <div class="featured_thumb_content">
			<?php if($music->photo_id != 0):?>
				<a class="thumb_img" href="<?php echo $music->getHref(array( 'page_id' => $music->page_id, 'playlist_id' => $music->playlist_id,'slug' => $music->getSlug(), 'tab' => $tab_id)); ?>">
			<span><?php echo $this->itemPhoto($music, null, $music->getTitle(), array()) ?></span>
				</a>
			<?php else:?>
				<a class="thumb_img" href="<?php echo $music->getHref(array( 'page_id' => $music->page_id, 'playlist_id' => $music->playlist_id,'slug' => $music->getSlug(), 'tab' => $tab_id)); ?>">
				<span><?php echo $this->itemPhoto($music, null, $music->getTitle(), array()) ?></span>
				</a>
			<?php endif;?>
			<span class="show_content_des">
				<?php
				$owner = $music->getOwner();
				//$parent = $music->getParent();
				echo $this->htmlLink($music->getHref(), $this->string()->truncate($music->getTitle(),25),array('title'=>$music->getTitle()));
				?>
        <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $music->page_id);?>
				<?php
				$truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.title.truncation', 18);
				$tmpBody = strip_tags($sitepage_object->title);
				$page_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
				?>
				<?php echo $this->translate("in ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($music->page_id, $music->owner_id, $music->getSlug()),  $page_title,array('title' => $sitepage_object->title)) ?>  
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.postedby', 1)):?>
					<?php echo $this->translate('by ').
								$this->htmlLink($owner->getHref(), $this->string()->truncate($owner->getTitle(),25),array('title'=>$owner->getTitle()));?>
        <?php endif;?>
			</span>
      </div>
        <?php $j++; $offset++;?>
       <?php if(($j% $this->itemsVisible) ==0):?>
           </div>
        </div>    
       <?php endif;?>     
    <?php endforeach; ?>
    <?php if($j <($this->totalItemsInSlide)):?>
       <?php for ($j;$j<($this->totalItemsInSlide); $j++ ): ?>
      <div class="featured_thumb_content">
      </div>
       <?php endfor; ?>
         </div>
      </div>
    <?php endif;?>
     
<?php } else {?>
<?php $count=$this->itemsVisible;
$j=0;  $offset=$this->offset+$count;?>
  <?php for ($i =$count; $i < $this->totalItemsInSlide; $i++):?> 
      <?php if ($j % $this->itemsVisible == 0): ?>
      <div class="Sitepagecontent_SlideItMoo_element Sitepagemusic_SlideItMoo_element" style="width:<?php echo 146 * $this->inOneRow; ?>px;">
        <div class="Sitepagecontent_SlideItMoo_contentList">
      <?php endif; ?>
          <?php if ($i < $this->count): ?>
            <div class="featured_thumb_content">
							<?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
							$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagemusic.profile-sitepagemusics', $this->featuredMusics[$i]->page_id, $layout); ?>
							<?php if($this->featuredMusics[$i]->photo_id != 0):?>
								<a class="thumb_img" href="<?php echo $this->featuredMusics[$i]->getHref(array( 'page_id' => $this->featuredMusics[$i]->page_id, 'playlist_id' => $this->featuredMusics[$i]->playlist_id,'slug' => $this->featuredMusics[$i]->getSlug(), 'tab' => $tab_id)); ?>">
									<span><?php echo $this->itemPhoto($this->featuredMusics[$i], null, $this->featuredMusics[$i]->getTitle(), array()) ?></span>
								</a>
							<?php else:?>
								<a class="thumb_img" href="<?php echo $this->featuredMusics[$i]->getHref(array( 'page_id' => $this->featuredMusics[$i]->page_id, 'playlist_id' => $this->featuredMusics[$i]->playlist_id,'slug' => $this->featuredMusics[$i]->getSlug(), 'tab' => $tab_id)); ?>">
									<span><?php echo $this->itemPhoto($this->featuredMusics[$i], null, $this->featuredMusics[$i]->getTitle(), array()) ?></span>
								</a>
							<?php endif;?>
							<span class="show_content_des">
            		<?php
                $owner = $this->featuredMusics[$i]->getOwner();
               // $parent = $this->featuredMusics[$i]->getParent();
                echo $this->htmlLink($this->featuredMusics[$i]->getHref(), $this->string()->truncate($this->featuredMusics[$i]->getTitle(),25),array('title'=>$this->featuredMusics[$i]->getTitle()));
                ?>
								<?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $this->featuredMusics[$i]->page_id);?>
								<?php
								$truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.title.truncation', 18);
								$tmpBody = strip_tags($sitepage_object->title);
								$page_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
								?>
								<?php echo $this->translate("in ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($this->featuredMusics[$i]->page_id, $this->featuredMusics[$i]->owner_id, $this->featuredMusics[$i]->getSlug()),  $page_title,array('title' => $sitepage_object->title)) ?>
                <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.postedby', 1)):?>
									<?php echo $this->translate('by ').
											$this->htmlLink($owner->getHref(), $this->string()->truncate($owner->getTitle(),25),array('title'=>$owner->getTitle()));?>
                <?php endif;?>
            	</span>
             </div>
          <?php else: ?>
             <div class="featured_thumb_content">
             </div>
          <?php endif; ?>
      <?php $j++; $offset++;?>
      <?php if (($j % $this->itemsVisible) == 0): ?>
          </div>
        </div>
      <?php endif; ?>     
     
  <?php endfor;?>
 <?php $j=0; $offset=$this->offset; ?>
 <?php for ($i = 0; $i < $count; $i++): ?>
   <?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
			$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagemusic.profile-sitepagemusics', $this->featuredMusics[$i]->page_id, $layout); ?>
   <?php if ($j % $this->itemsVisible == 0): ?>
      <div class="Sitepagecontent_SlideItMoo_element Sitepagemusic_SlideItMoo_element" style="width:<?php echo 146 * $this->inOneRow; ?>px;">
        <div class="Sitepagecontent_SlideItMoo_contentList">
      <?php endif; ?>        
            <div class="featured_thumb_content">
	            <?php if($this->featuredMusics[$i]->photo_id != 0):?>
								<a class="thumb_img" href="<?php echo $this->featuredMusics[$i]->getHref(array( 'page_id' => $this->featuredMusics[$i]->page_id, 'playlist_id' => $this->featuredMusics[$i]->playlist_id,'slug' => $this->featuredMusics[$i]->getSlug(), 'tab' => $tab_id)); ?>">
								<span><?php echo $this->itemPhoto($this->featuredMusics[$i], null, $this->featuredMusics[$i]->getTitle(), array()) ?></span>
								</a>
							<?php else:?>
								<a class="thumb_img" href="<?php echo $this->featuredMusics[$i]->getHref(array( 'page_id' => $this->featuredMusics[$i]->page_id, 'playlist_id' => $this->featuredMusics[$i]->playlist_id,'slug' => $this->featuredMusics[$i]->getSlug(), 'tab' => $tab_id)); ?>">
								<span><?php echo $this->itemPhoto($this->featuredMusics[$i], null, $this->featuredMusics[$i]->getTitle(), array()) ?></span>
								</a>
							<?php endif;?>
							<span class="show_content_des">
            		<?php
                $owner = $this->featuredMusics[$i]->getOwner();
               // $parent = $this->featuredMusics[$i]->getParent();
                echo $this->htmlLink($this->featuredMusics[$i]->getHref(), $this->string()->truncate($this->featuredMusics[$i]->getTitle(),25),array('title'=>$this->featuredMusics[$i]->getTitle()));
                ?>
								<?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $this->featuredMusics[$i]->page_id);?>
								<?php
								$truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.title.truncation', 18);
								$tmpBody = strip_tags($sitepage_object->title);
								$page_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
								?>
								<?php echo $this->translate("in ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($this->featuredMusics[$i]->page_id, $this->featuredMusics[$i]->owner_id, $this->featuredMusics[$i]->getSlug()),  $page_title,array('title' => $sitepage_object->title)) ?>
                <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.postedby', 1)):?>
									<?php echo $this->translate('by ').
											$this->htmlLink($owner->getHref(), $this->string()->truncate($owner->getTitle(),25),array('title'=>$owner->getTitle()));?>
                <?php endif;?>
            	</span>
	          </div>
         <?php $j++; $offset++; ?>
        <?php if ($j % $this->itemsVisible == 0): ?>
          </div>
        </div>
      <?php endif; ?>
  <?php endfor; ?>
 <?php } ?>
