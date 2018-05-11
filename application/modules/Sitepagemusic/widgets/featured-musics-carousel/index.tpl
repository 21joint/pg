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

<?php
  $this->headLink()
     ->prependStylesheet($this->layout()->staticBaseUrl.'application/modules/Sitepage/externals/styles/sitepage_featured_carousel.css');
  $this->headScript()
		->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/scripts/sitepageslideitmoo-1.1_full_source.js');
?>

<a id="group_profile_members_anchor" style="position:absolute;"></a>
<script language="javascript" type="text/javascript">

  var module = 'Sitepagemusic';
</script>
<script language="javascript" type="text/javascript">
  var slideshowmusic;
  window.addEvents ({
    'domready': function() {
      slideshowmusic = new SocialengineSlideItMoo({
        fwdbck_click:1,
        slide_element_limit:1,
        startindex:-1,
        in_one_row:<?php echo  $this->inOneRow_music?>,
        no_of_row:<?php echo  $this->noOfRow_music?>,
        curnt_limit:<?php echo $this->totalItemShowmusic;?>,
        category_id:<?php echo $this->category_id;?>,
        total:<?php echo $this->totalCount_music; ?>,
        limit:<?php echo $this->totalItemShowmusic*2;?>,
        module : 'Sitepagemusic',
        call_count:1,
        foward:'Sitepagemusic_SlideItMoo_forward',
        bck:'Sitepagemusic_SlideItMoo_back',
        overallContainer: 'Sitepagemusic_SlideItMoo_outer',
        elementScrolled: 'Sitepagemusic_SlideItMoo_inner',
        thumbsContainer: 'Sitepagemusic_SlideItMoo_items',
        slideVertical: <?php echo $this->vertical?>,
        itemsVisible:1,
        elemsSlide:1,
        duration:<?php echo  $this->interval;?>,
        itemsSelector: '.Sitepagemusic_SlideItMoo_element',
        itemWidth:<?php echo 146 * $this->inOneRow_music?>,
        itemHeight:<?php echo 146 * $this->noOfRow_music?>,
        showControls:1,
        startIndex:1,
        navs:{ /* starting this version, you'll need to put your back/forward navigators in your HTML */
				fwd:'.Sitepagemusic_SlideItMoo_forward', /* forward button CSS selector */
				bk:'.Sitepagemusic_SlideItMoo_back' /* back button CSS selector */
				},
        transition: Fx.Transitions.linear, /* transition */
        onChange: function(index) { slideshowmusic.options.call_count = 1;
        }
      });

      $('Sitepagemusic_SlideItMoo_back').addEvent('click', function () {slideshowmusic.sendajax(-1,slideshowmusic,'Sitepagemusic',"<?php echo $this->url(array('module' => 'sitepagemusic','controller' => 'index','action'=>'featured-musics-carousel'),'default',true); ?>");
        slideshowmusic.options.call_count = 1;

      });

      $('Sitepagemusic_SlideItMoo_forward').addEvent('click', function () { slideshowmusic.sendajax(1,slideshowmusic,'Sitepagemusic',"<?php echo $this->url(array('module' => 'sitepagemusic','controller' => 'index','action'=>'featured-musics-carousel'),'default',true); ?>");
        slideshowmusic.options.call_count = 1;
      });
     
      if((slideshowmusic.options.total -slideshowmusic.options.curnt_limit)<=0){
        // hidding forward button
       document.getElementById('Sitepagemusic_SlideItMoo_forward').style.display= 'none';
       document.getElementById('Sitepagemusic_SlideItMoo_back_disable').style.display= 'none';
      }
    }
  });
</script>
<?php
$musicSettings=  array();
$musicSettings['class'] = 'thumb';

?>
<ul class="Sitepagecontent_featured_slider">
  <li>
		<?php
    $module = 'Sitepagemusic';
    $extra_width=0;
    $extra_height=0;    
        if (empty($this->vertical)):
        $typeClass='horizontal';
         if ($this->totalCount_music > $this->totalItemShowmusic):
          $extra_width = 60;
          endif;
          $prev='back';
          $next='forward';
        else:
        	$typeClass='vertical';
        if ($this->totalCount_music > $this->totalItemShowmusic):
          $extra_height=50;
          endif;
          $prev='up';
          $next='down';
        endif;
     ?>
    <div id="Sitepagemusic_SlideItMoo_outer" class="Sitepagecontent_SlideItMoo_outer Sitepagecontent_SlideItMoo_outer_<?php echo $typeClass;?>" style="height:<?php echo 146*$this->heightRow+$extra_height;?>px; width:<?php echo (146*$this->inOneRow_music)+$extra_width;?>px;">
      <div class="Sitepagecontent_SlideItMoo_back" id="Sitepagemusic_SlideItMoo_back" style="display:none;">
				<?php echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Sitepage/externals/images/photo/slider-$prev.png", '', array('align'=>'', 'onMouseOver'=>'this.src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepage/externals/images/photo/slider-'.$prev.'-active.png";','onMouseOut'=>'this.src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepage/externals/images/photo/slider-'.$prev.'.png";', 'border'=>'0')) ?>
      </div>
      <div class="Sitepagecontent_SlideItMoo_back" id="Sitepagemusic_SlideItMoo_back_loding" style="display:none;">
        <?php echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Seaocore/externals/images/core/loading.gif", '', array('align'=>'', 'border'=>'0','class'=>'Sitepagecontent_SlideItMoo_loding'));  ?>
      </div>      
       <div class="Sitepagecontent_SlideItMoo_back_disable" id="Sitepagemusic_SlideItMoo_back_disable" style="display:block;cursor:default;">
				<?php echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Sitepage/externals/images/photo/slider-$prev-disable.png", '', array('align'=>'', 'border'=>'0'));  ?>
      </div>
      <div id="Sitepagemusic_SlideItMoo_inner" class="Sitepagecontent_SlideItMoo_inner">
        <div id="Sitepagemusic_SlideItMoo_items" class="Sitepagecontent_SlideItMoo_items" style="height:<?php echo 146*$this->heightRow;?>px;">
          <div class="Sitepagecontent_SlideItMoo_element Sitepagemusic_SlideItMoo_element" style="width:<?php echo 146*$this->inOneRow_music;?>px;">
              <div class="Sitepagecontent_SlideItMoo_contentList">
               <?php  $i=0; ?>
                  <?php foreach ($this->featuredMusics as $music):?>
                       <?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
												$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagemusic.profile-sitepagemusic', $music->page_id, $layout);?>
                        <div class="featured_thumb_content">
													<?php if($music->photo_id != 0):?>
														<a class="thumb_img" href="<?php echo $music->getHref(array( 'page_id' => $music->page_id, 'playlist_id' => $music->playlist_id,'slug' => $music->getSlug(), 'tab' => $tab_id)); ?>">
															<span><?php echo $this->itemPhoto($music, null, $music->getTitle()) ?></span>
														</a>
													<?php else:?>
														<a class="thumb_img" href="<?php echo $music->getHref(array( 'page_id' => $music->page_id, 'playlist_id' => $music->playlist_id,'slug' => $music->getSlug(), 'tab' => $tab_id)); ?>">
														<span><?php echo $this->itemPhoto($music, null, $music->getTitle(), array()) ?></span>
														</a>
													<?php endif;?>
                          <span class="show_content_des">
                            <?php
								              $owner = $music->getOwner();
								              echo  $this->htmlLink($music->getHref(array('tab' => $tab_id)), $this->string()->chunk($this->string()->truncate($music->getTitle(), 45), 10),array('title' => $music->getTitle()));
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
                   <?php  $i++; ?>
                  <?php endforeach; ?>
                <?php for($i; $i<($this->heightRow *$this->inOneRow_music);$i++):?>
                <div class="featured_thumb_content"></div>
                <?php endfor; ?>
              </div>
           </div>
        </div>
      </div>
      <?php $module = 'Sitepagemusic';?>
      <div class="Sitepagecontent_SlideItMoo_forward" id ="Sitepagemusic_SlideItMoo_forward">
      	<?php echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Sitepage/externals/images/photo/slider-$next.png", '', array('align'=>'', 'onMouseOver'=>'this.src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepage/externals/images/photo/slider-'.$next.'-active.png";','onMouseOut'=>'this.src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepage/externals/images/photo/slider-'.$next.'.png";', 'border'=>'0')) ?>
      </div>
      <div class="Sitepagecontent_SlideItMoo_forward" id="Sitepagemusic_SlideItMoo_forward_loding"  style="display: none;">
        <?php echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Seaocore/externals/images/core/loading.gif", '', array('align'=>'', 'border'=>'0','class'=>'Sitepagecontent_SlideItMoo_loding'));  ?>
      </div>
      <div class="Sitepagecontent_SlideItMoo_forward_disable" id="Sitepagemusic_SlideItMoo_forward_disable" style="display:none;cursor:default;">
      	<?php  echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Sitepage/externals/images/photo/slider-$next-disable.png", '', array('align'=>'', 'border'=>'0'));  ?>
      </div>
    </div>
    <div class="clear"></div>
  </li>
</ul>
