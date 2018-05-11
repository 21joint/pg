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
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
?>
<?php if(empty($this->is_ajax)): ?>
<div class="layout_core_container_tabs">
<div class="tabs_alt tabs_parent">
  <ul id="main_tabs">
    <?php foreach ($this->tabs as $tab): ?>
    <?php $class = $tab->name == $this->activTab->name ? 'active' : '' ?>
      <li class = '<?php echo $class ?>'  id = '<?php echo 'sitepagemusic_' . $tab->name.'_tab' ?>'>
        <a href='javascript:void(0);'  onclick="tabSwitchSitepagemusic('<?php echo$tab->name; ?>');"><?php echo $this->translate($tab->getTitle()) ?></a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
<div id="hideResponse_div" style="display: none;"></div>
<div id="sitepagelbum_musics_tabs">   
   <?php endif; ?>
   <?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
      <?php if($this->is_ajax !=2): ?>
     <ul class="thumbs sitepage_content_thumbs" id ="sitepagemusic_list_tab_music_content">
       <?php endif; ?>
      <?php foreach( $this->paginator as $music ): ?>
        <?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
						$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagemusic.profile-sitepagemusic', $music->page_id, $layout);?>
        <li style="margin-left:<?php echo $this->marginPhoto ?>px;margin-right:<?php echo $this->marginPhoto ?>px;">
					<?php if($music->photo_id != 0):?>
						<a class="thumbs_photo" href="<?php echo $music->getHref(array( 'page_id' => $music->page_id, 'playlist_id' => $music->playlist_id,'slug' => $music->getSlug(), 'tab' => $tab_id)); ?>">
								<span style="background-image: url(<?php echo $music->getPhotoUrl('thumb.normal'); ?>);"></span>
						</a>
          <?php else:?>
						<a class="thumbs_photo" href="<?php echo $music->getHref(array( 'page_id' => $music->page_id, 'playlist_id' => $music->playlist_id,'slug' => $music->getSlug(), 'tab' => $tab_id)); ?>">
								<span><?php echo $this->itemPhoto($music, null, $music->getTitle(), array()) ?></span>
						</a>
          <?php endif;?>
          <p class="thumbs_info">
						<span class="thumbs_title">
							<?php echo $this->htmlLink($music->getHref(array('tab' => $tab_id)), $this->string()->chunk($this->string()->truncate($music->getTitle(), 45), 10),array('title' => $music->getTitle())); ?>
						</span>
						<?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $music->page_id);?>
						<?php
							$truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.title.truncation', 18);
							$tmpBody = strip_tags($sitepage_object->title);
							$page_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
						?>
						<?php echo $this->translate("in ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($music->page_id, $music->owner_id, $music->getSlug()),  $page_title,array('title' => $sitepage_object->title)) ?>      
            <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.postedby', 1)):?> 
							<?php echo $this->translate('by ').$this->htmlLink($music->getOwner()->getHref(), $music->getOwner()->getTitle(), array('title'=>$music->getOwner()->getTitle(),'class' => 'thumbs_author')) ?>
            <?php endif;?>
	          <?php if( $this->activTab->name == 'viewed_pagemusics' ): ?> <br />
	            <?php echo $this->translate(array('%s view', '%s views', $music->view_count), $this->locale()->toNumber($music->view_count)) ?>
	          <?php elseif( $this->activTab->name == 'commented_pagemusics' ): ?> <br />
	            <?php echo $this->translate(array('%s comment', '%s comments', $music->comment_count), $this->locale()->toNumber($music->comment_count)) ?>
	          <?php elseif( $this->activTab->name == 'liked_pagemusics' ): ?> <br />
	            <?php echo $this->translate(array('%s like', '%s likes', $music->like_count), $this->locale()->toNumber($music->like_count)) ?>
	          <?php endif; ?>
          </p>
        </li>
      <?php endforeach;?>
       <?php if($this->is_ajax !=2): ?>  
    </ul>  
      <?php endif; ?>
  <?php else: ?>
    <div class="tip">
      <span>
        <?php echo $this->translate('No musics have been created yet.');?>
      </span>
    </div>
  <?php endif; ?>   
<?php if(empty($this->is_ajax)): ?>    
</div>
<?php if (!empty($this->showViewMore)): ?>
<div class="seaocore_view_more" id="sitepagemusic_musics_tabs_view_more" onclick="viewMoreTabMusic()">
  <?php
  echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array(
      'id' => 'feed_viewmore_link',
      'class' => 'buttonlink icon_viewmore'
  ))
  ?>
</div>
<div class="seaocore_loading" id="sitepagemusic_musics_tabs_loding_image" style="display: none;">
  <img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif' alt="" />
  <?php echo $this->translate("Loading ...") ?>
</div>
<?php endif; ?>
</div>
<?php endif; ?>

<?php if(empty($this->is_ajax)): ?>
<script type="text/javascript">
  
  var tabSwitchSitepagemusic = function (tabName) {
 <?php foreach ($this->tabs as $tab): ?>
  if($('<?php echo 'sitepagemusic_'.$tab->name.'_tab' ?>'))
        $('<?php echo 'sitepagemusic_' .$tab->name.'_tab' ?>').erase('class');
  <?php  endforeach; ?>

 if($('sitepagemusic_'+tabName+'_tab'))
        $('sitepagemusic_'+tabName+'_tab').set('class', 'active');
   if($('sitepagelbum_musics_tabs')) {
      $('sitepagelbum_musics_tabs').innerHTML = '<center><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepage/externals/images/loader.gif" class="sitepage_tabs_loader_img" /></center>';
    }   
    if($('sitepagemusic_musics_tabs_view_more'))
    $('sitepagemusic_musics_tabs_view_more').style.display =  'none';
    var request = new Request.HTML({
     method : 'post',
      'url' : en4.core.baseUrl + 'widget/index/mod/sitepagemusic/name/list-musics-tabs-view',
      'data' : {
        format : 'html',
        isajax : 1,
        category_id : '<?php echo $this->category_id?>',
        tabName: tabName,
        margin_photo : '<?php echo $this->marginPhoto ?>'
      },
      onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {
            $('sitepagelbum_musics_tabs').innerHTML = responseHTML;
            <?php if(!empty ($this->showViewMore)): ?>
              hideViewMoreLinkSitepageMusicMusic();
             <?php endif; ?> 
      }
    });

    request.send();
  }
</script>
<?php endif; ?>
<?php if(!empty ($this->showViewMore)): ?>
<script type="text/javascript">
    en4.core.runonce.add(function() {
    hideViewMoreLinkSitepageMusicMusic();  
    });
    function getNextPageSitepageMusicMusic(){
      return <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>
    }
    function hideViewMoreLinkSitepageMusicMusic(){
      if($('sitepagemusic_musics_tabs_view_more'))
        $('sitepagemusic_musics_tabs_view_more').style.display = '<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() || $this->count == 0 ? 'none' : '' ) ?>';
    }
        
    function viewMoreTabMusic()
  {
    $('sitepagemusic_musics_tabs_view_more').style.display ='none';
    $('sitepagemusic_musics_tabs_loding_image').style.display ='';
    en4.core.request.send(new Request.HTML({
      method : 'post',
      'url' : en4.core.baseUrl + 'widget/index/mod/sitepagemusic/name/list-musics-tabs-view',
      'data' : {
        format : 'html', 
        isajax : 2,
        category_id : '<?php echo $this->category_id?>',
        tabName : '<?php echo $this->activTab->name ?>',
        margin_photo : '<?php echo $this->marginPhoto ?>',
        page: getNextPageSitepageMusicMusic()
      },
      onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {    
        $('hideResponse_div').innerHTML=responseHTML;      
        var photocontainer = $('hideResponse_div').getElement('.layout_sitepagemusic_list_musics_tabs_view').innerHTML;
        $('sitepagemusic_list_tab_music_content').innerHTML = $('sitepagemusic_list_tab_music_content').innerHTML + photocontainer;
        $('sitepagemusic_musics_tabs_loding_image').style.display ='none';
        $('hideResponse_div').innerHTML="";        
      }
    }));

    return false;

  }  
</script>
<?php endif; ?>
