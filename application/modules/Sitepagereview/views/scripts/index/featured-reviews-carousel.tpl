<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: featured-reviews-carousel.tpl 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $photo_review = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.photo', 1);?>
<?php if ($this->direction == 1) { ?>
 <?php  $j=0; $offset=$this->offset; ?>

    <?php foreach ($this->featuredReviews as $review): ?>
      <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $review->page_id);?>
      <?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
			$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagereview.profile-sitepagereviews', $review->page_id, $layout); ?>
      <?php if($j% $this->itemsVisible ==0):?>
        <div class="Sitepagecontent_SlideItMoo_element Sitepagereview_SlideItMoo_element" style="width:<?php echo 146 * $this->inOneRow; ?>px;">
        <div class="Sitepagecontent_SlideItMoo_contentList">
      <?php endif;?>
      <div class="featured_thumb_content">
				<a class="thumb_img" href="<?php echo Engine_Api::_()->sitepage()->getHref($sitepage_object->page_id, $sitepage_object->owner_id, $sitepage_object->getSlug()); ?>">
					<?php if(!empty($photo_review)):?>
						<span>
							<?php $user = Engine_Api::_()->getItem('user', $review->owner_id);
								echo $this->itemPhoto($user, 'thumb.profile');
							?>
						</span>
				  <?php else:?>
						<span><?php echo $this->itemPhoto($sitepage_object, 'thumb.normaml', $sitepage_object->getTitle()) ?></span>
				  <?php endif;?>
				</a>
			<span class="show_content_des">
				<?php
				$owner = $review->getOwner();
				//$parent = $review->getParent();
				echo 
							$this->htmlLink($review->getHref(), $this->string()->truncate($review->getTitle(),25),array('title' => $review->getTitle()));
				?>
				<?php
				$truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.title.truncation', 18);
				$tmpBody = strip_tags($sitepage_object->title);
				$page_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
				?>
				<?php echo $this->translate("on ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($review->page_id, $review->owner_id, $review->getSlug()),  $page_title,array('title' => $sitepage_object->title,'class' => 'bold')) ?> 
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.postedby', 1)):?>  
					<?php echo $this->translate('by ').
								$this->htmlLink($owner->getHref(), $this->string()->truncate($owner->getTitle(),25),array('title' => $owner->getTitle()));?>
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
      <div class="Sitepagecontent_SlideItMoo_element Sitepagereview_SlideItMoo_element" style="width:<?php echo 146 * $this->inOneRow; ?>px;">
        <div class="Sitepagecontent_SlideItMoo_contentList">
      <?php endif; ?>
          <?php if ($i < $this->count): ?>
            <div class="featured_thumb_content">
              <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $this->featuredReviews[$i]->page_id);?>
              <?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
							$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagereview.profile-sitepagereviews', $this->featuredReviews[$i]->page_id, $layout); ?>
							<a class="thumb_img" href="<?php echo Engine_Api::_()->sitepage()->getHref($sitepage_object->page_id, $sitepage_object->owner_id, $sitepage_object->getSlug()); ?>">
								<?php if(!empty($photo_review)):?>
									<span>
										<?php $user = Engine_Api::_()->getItem('user', $this->featuredReviews[$i]->owner_id);
										echo $this->itemPhoto($user, 'thumb.profile');
										?>
									</span>
								<?php else:?>
									<span><?php echo $this->itemPhoto($sitepage_object, 'thumb.normaml', $sitepage_object->getTitle()) ?></span>
								<?php endif;?>
							</a>
							<span class="show_content_des">
            		<?php
                $owner = $this->featuredReviews[$i]->getOwner();
               // $parent = $this->featuredReviews[$i]->getParent();
                echo 
                     $this->htmlLink($this->featuredReviews[$i]->getHref(), $this->string()->truncate($this->featuredReviews[$i]->getTitle(),25),array('title' => $this->featuredReviews[$i]->getTitle()));
                ?>
								<?php
								$truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.title.truncation', 18);
								$tmpBody = strip_tags($sitepage_object->title);
								$page_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
								?>
								<?php echo $this->translate("on ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($this->featuredReviews[$i]->page_id, $this->featuredReviews[$i]->owner_id, $this->featuredReviews[$i]->getSlug()),  $page_title,array('title' => $sitepage_object->title,'class' => 'bold')) ?>
                <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.postedby', 1)):?> 
									<?php echo $this->translate('by ').
											$this->htmlLink($owner->getHref(), $this->string()->truncate($owner->getTitle(),25),array('title' => $owner->getTitle()));?>
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
			$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagereview.profile-sitepagereviews', $this->featuredReviews[$i]->page_id, $layout); ?>
   <?php if ($j % $this->itemsVisible == 0): ?>
      <div class="Sitepagecontent_SlideItMoo_element Sitepagereview_SlideItMoo_element" style="width:<?php echo 146 * $this->inOneRow; ?>px;">
        <div class="Sitepagecontent_SlideItMoo_contentList">
      <?php endif; ?>        
            <div class="featured_thumb_content">
              <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $this->featuredReviews[$i]->page_id);?>
	            <a class="thumb_img" href="<?php echo Engine_Api::_()->sitepage()->getHref($sitepage_object->page_id, $sitepage_object->owner_id, $sitepage_object->getSlug()); ?>">
					      <?php if(!empty($photo_review)):?>
									<span>
										<?php $user = Engine_Api::_()->getItem('user', $this->featuredReviews[$i]->owner_id);
										echo $this->itemPhoto($user, 'thumb.profile');
										?>
									</span>
								<?php else:?>
									<span><?php echo $this->itemPhoto($sitepage_object, 'thumb.normaml', $sitepage_object->getTitle()) ?></span>
								<?php endif;?>
				      </a>
							<span class="show_content_des">
            		<?php
                $owner = $this->featuredReviews[$i]->getOwner();
                //$parent = $this->featuredReviews[$i]->getParent();
                echo 
                     $this->htmlLink($this->featuredReviews[$i]->getHref(), $this->string()->truncate($this->featuredReviews[$i]->getTitle(),25),array('title' => $this->featuredReviews[$i]->getTitle()));
                ?>
								<?php
								$truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.title.truncation', 18);
								$tmpBody = strip_tags($sitepage_object->title);
								$page_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
								?>
								<?php echo $this->translate("on ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($this->featuredReviews[$i]->page_id, $this->featuredReviews[$i]->owner_id, $this->featuredReviews[$i]->getSlug()),  $page_title,array('title' => $sitepage_object->title,'class' => 'bold')) ?>
                <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.postedby', 1)):?> 
									<?php echo $this->translate('by ').
											$this->htmlLink($owner->getHref(), $this->string()->truncate($owner->getTitle(),25),array('title' => $owner->getTitle()));?>
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
