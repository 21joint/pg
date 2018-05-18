<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
  $this->headLink()
     ->prependStylesheet($this->layout()->staticBaseUrl.'application/modules/Sitepage/externals/styles/sitepage_featured_carousel.css');
  $this->headScript()
		->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/scripts/sitepageslideitmoo-1.1_full_source.js');
?>

<a id="group_profile_members_anchor" style="position:absolute;"></a>
<script language="javascript" type="text/javascript">
  var module = 'Sitepagereview';
</script>
<script language="javascript" type="text/javascript">
  var slideshowreview;
  window.addEvents ({
    'domready': function() {
      slideshowreview = new SocialengineSlideItMoo({
        fwdbck_click:1,
        slide_element_limit:1,
        startindex:-1,
        in_one_row:<?php echo  $this->inOneRow_review?>,
        no_of_row:<?php echo  $this->noOfRow_review?>,
        curnt_limit:<?php echo $this->totalItemShowreview;?>,
        category_id:<?php echo $this->category_id;?>,
        total:<?php echo $this->totalCount_review; ?>,
        limit:<?php echo $this->totalItemShowreview*2;?>,
        module : 'Sitepagereview',
        call_count:1,
        foward:'Sitepagereview_SlideItMoo_forward',
        bck:'Sitepagereview_SlideItMoo_back',
        overallContainer: 'Sitepagereview_SlideItMoo_outer',
        elementScrolled: 'Sitepagereview_SlideItMoo_inner',
        thumbsContainer: 'Sitepagereview_SlideItMoo_items',
        slideVertical: <?php echo $this->vertical?>,
        itemsVisible:1,
        elemsSlide:1,
        duration:<?php echo  $this->interval;?>,
        itemsSelector: '.Sitepagereview_SlideItMoo_element',
        itemWidth:<?php echo 146 * $this->inOneRow_review?>,
        itemHeight:<?php echo 146 * $this->noOfRow_review?>,
        showControls:1,
        startIndex:1,
        navs:{ /* starting this version, you'll need to put your back/forward navigators in your HTML */
				fwd:'.Sitepagereview_SlideItMoo_forward', /* forward button CSS selector */
				bk:'.Sitepagereview_SlideItMoo_back' /* back button CSS selector */
				},
        transition: Fx.Transitions.linear, /* transition */
        onChange: function(index) { slideshowreview.options.call_count = 1;
        }
      });

      $('Sitepagereview_SlideItMoo_back').addEvent('click', function () {slideshowreview.sendajax(-1,slideshowreview,'Sitepagereview',"<?php echo $this->url(array('module' => 'sitepagereview','controller' => 'index','action'=>'featured-reviews-carousel'),'default',true); ?>");
        slideshowreview.options.call_count = 1;

      });

      $('Sitepagereview_SlideItMoo_forward').addEvent('click', function () { slideshowreview.sendajax(1,slideshowreview,'Sitepagereview',"<?php echo $this->url(array('module' => 'sitepagereview','controller' => 'index','action'=>'featured-reviews-carousel'),'default',true); ?>");
        slideshowreview.options.call_count = 1;
      });
     
      if((slideshowreview.options.total -slideshowreview.options.curnt_limit)<=0){
        // hidding forward button
       document.getElementById('Sitepagereview_SlideItMoo_forward').style.display= 'none';
       document.getElementById('Sitepagereview_SlideItMoo_back_disable').style.display= 'none';
      }
    }
  });
</script>
<?php
$reviewSettings=  array();
$reviewSettings['class'] = 'thumb';

?>
<ul class="Sitepagecontent_featured_slider">
  <li>
		<?php
    $module = 'Sitepagereview';
    $extra_width=0;
    $extra_height=0;    
        if (empty($this->vertical)):
        $typeClass='horizontal';
         if ($this->totalCount_review > $this->totalItemShowreview):
          $extra_width = 60;
          endif;
          $prev='back';
          $next='forward';
        else:
        	$typeClass='vertical';
        if ($this->totalCount_review > $this->totalItemShowreview):
          $extra_height=50;
          endif;
          $prev='up';
          $next='down';
        endif;
     ?>
    <div id="Sitepagereview_SlideItMoo_outer" class="Sitepagecontent_SlideItMoo_outer Sitepagecontent_SlideItMoo_outer_<?php echo $typeClass;?>" style="height:<?php echo 146*$this->heightRow+$extra_height;?>px; width:<?php echo (146*$this->inOneRow_review)+$extra_width;?>px;">
      <div class="Sitepagecontent_SlideItMoo_back" id="Sitepagereview_SlideItMoo_back" style="display:none;">
				<?php echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Sitepage/externals/images/photo/slider-$prev.png", '', array('align'=>'', 'onMouseOver'=>'this.src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepage/externals/images/photo/slider-'.$prev.'-active.png";','onMouseOut'=>'this.src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepage/externals/images/photo/slider-'.$prev.'.png";', 'border'=>'0')) ?>
      </div>
      <div class="Sitepagecontent_SlideItMoo_back" id="Sitepagereview_SlideItMoo_back_loding" style="display:none;">
        <?php echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Seaocore/externals/images/core/loading.gif", '', array('align'=>'', 'border'=>'0','class'=>'Sitepagecontent_SlideItMoo_loding'));  ?>
      </div>      
       <div class="Sitepagecontent_SlideItMoo_back_disable" id="Sitepagereview_SlideItMoo_back_disable" style="display:block;cursor:default;">
				<?php echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Sitepage/externals/images/photo/slider-$prev-disable.png", '', array('align'=>'', 'border'=>'0'));  ?>
      </div>
      <div id="Sitepagereview_SlideItMoo_inner" class="Sitepagecontent_SlideItMoo_inner">
        <div id="Sitepagereview_SlideItMoo_items" class="Sitepagecontent_SlideItMoo_items" style="height:<?php echo 146*$this->heightRow;?>px;">
          <div class="Sitepagecontent_SlideItMoo_element Sitepagereview_SlideItMoo_element" style="width:<?php echo 146*$this->inOneRow_review;?>px;">
              <div class="Sitepagecontent_SlideItMoo_contentList">
               <?php  $i=0; ?>
                  <?php $photo_review = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.photo', 1);?>
                  <?php foreach ($this->featuredReviews as $review):?>
                       <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $review->page_id);?>
                       <?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
												$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagereview.profile-sitepagereviews', $review->page_id, $layout);?>
                        <div class="featured_thumb_content">
													<a class="thumb_img" href="<?php echo Engine_Api::_()->sitepage()->getHref($sitepage_object->page_id, $sitepage_object->owner_id, $sitepage_object->getSlug()); ?>">
                           
														<?php if(!empty($photo_review)):?>
                              <span>
																<?php $user = Engine_Api::_()->getItem('user', $review->owner_id);
																echo $this->itemPhoto($user, 'thumb.profile');
																?>
                              </span>
														<?php else:?>
                              <span>
															<?php echo $this->itemPhoto($sitepage_object, 'thumb.normaml', $sitepage_object->getTitle()) ?></span>
														<?php endif;?>
													</a>
                          <span class="show_content_des">
                            <?php
								              $owner = $review->getOwner();
								              echo $this->htmlLink($review->getHref(array('tab' => $tab_id)), $this->string()->chunk($this->string()->truncate($review->getTitle(), 45), 10),array('title' => $review->getTitle()));
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
                   <?php  $i++; ?>
                  <?php endforeach; ?>
                <?php for($i; $i<($this->heightRow *$this->inOneRow_review);$i++):?>
                <div class="featured_thumb_content"></div>
                <?php endfor; ?>
              </div>
           </div>
        </div>
      </div>
      <?php $module = 'Sitepagereview';?>
      <div class="Sitepagecontent_SlideItMoo_forward" id ="Sitepagereview_SlideItMoo_forward">
      	<?php echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Sitepage/externals/images/photo/slider-$next.png", '', array('align'=>'', 'onMouseOver'=>'this.src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepage/externals/images/photo/slider-'.$next.'-active.png";','onMouseOut'=>'this.src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepage/externals/images/photo/slider-'.$next.'.png";', 'border'=>'0')) ?>
      </div>
      <div class="Sitepagecontent_SlideItMoo_forward" id="Sitepagereview_SlideItMoo_forward_loding"  style="display: none;">
        <?php echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Seaocore/externals/images/core/loading.gif", '', array('align'=>'', 'border'=>'0','class'=>'Sitepagecontent_SlideItMoo_loding'));  ?>
      </div>
      <div class="Sitepagecontent_SlideItMoo_forward_disable" id="Sitepagereview_SlideItMoo_forward_disable" style="display:none;cursor:default;">
      	<?php  echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Sitepage/externals/images/photo/slider-$next-disable.png", '', array('align'=>'', 'border'=>'0'));  ?>
      </div>
    </div>
    <div class="clear"></div>
  </li>
</ul>
