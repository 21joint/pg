<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php 
  if(file_exists(APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/Adintegration.tpl'))
    include APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/Adintegration.tpl';
?>

<?php if (!empty($this->show_content)) : ?>
	<script type="text/javascript">	
	  function deletemusic(thisobj) 
		{
			var Obj_Url = thisobj.href;
			Smoothbox.open(Obj_Url);
		}
		
	  var sitepageMusicSearchText = '<?php echo $this->search ?>';
	  var pageMusicPage = <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber()) ?>;
	  en4.core.runonce.add(function() 
	  { 	
	    var url = en4.core.baseUrl + 'widget/index/mod/sitepagemusic/name/profile-sitepagemusic';
	    $('sitepage_music_search_input_text').addEvent('keypress', function(e) {
	      if( e.key != 'enter' ) return;
	      if($('sitepage_music_search_input_checkbox') && $('sitepage_music_search_input_checkbox').checked == true) {
					var checkbox_value = 1;
				}
				else {
					var checkbox_value = 0;
				}
				if($('sitepagemusic_search') != null) {
					$('sitepagemusic_search').innerHTML = '<center><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepagemusic/externals/images/spinner_temp.gif" /></center>'; 
				}
	      en4.core.request.send(new Request.HTML({
	      'url' : url,
	      'data' : {
	        'format' : 'html',
	        'subject' : en4.core.subject.guid,
	        'search' : $('sitepage_music_search_input_text').value,
					'selectbox' : $('sitepage_music_search_input_selectbox').value,
					'checkbox' : checkbox_value,
					'isajax' : '1',
					'tab' : '<?php echo $this->content_id ?>'
	      }
	      }), {
	       'element' : $('id_' + <?php echo $this->content_id ?>)
	      });
	    });
	  });
	  
	  function showsearchmusiccontent () 
	  {			 
			var url = en4.core.baseUrl + 'widget/index/mod/sitepagemusic/name/profile-sitepagemusic';
	    $('sitepage_music_search_input_text').addEvent('keypress', function(e) {
	      if( e.key != 'enter' ) return;
	      if($('sitepage_music_search_input_checkbox') && $('sitepage_music_search_input_checkbox').checked == true) {
					var checkbox_value = 1;
				}
				else {
					var checkbox_value = 0;
				}
				if($('sitepagemusic_search') != null) {
					$('sitepagemusic_search').innerHTML = '<center><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepagemusic/externals/images/spinner_temp.gif" /></center>'; 
				}
				
	      en4.core.request.send(new Request.HTML({
	      'url' : url,
	      'data' : {
	        'format' : 'html',
	        'subject' : en4.core.subject.guid,
	        'search' : $('sitepage_music_search_input_text').value,
					'selectbox' : $('sitepage_music_search_input_selectbox').value,
					'checkbox' : checkbox_value,
					'isajax' : '1',
					'tab' : '<?php echo $this->content_id ?>'
	      }
	      }), {
	       'element' : $('id_' + <?php echo $this->content_id ?>)
	      });
	    });
			  
			}
	
		function Ordermusicelect()
	  {
			var sitepageMusicSearchSelectbox = '<?php echo $this->selectbox ?>';
			var pageMusicPage = <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber()) ?>;
			var url = en4.core.baseUrl + 'widget/index/mod/sitepagemusic/name/profile-sitepagemusic';
		 	if($('sitepage_music_search_input_checkbox') && $('sitepage_music_search_input_checkbox').checked == true) {
		    var checkbox_value = 1;
		  }
			else {
				var checkbox_value = 0;
			}
			if($('sitepagemusic_search') != null) {
				$('sitepagemusic_search').innerHTML = '<center><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepagemusic/externals/images/spinner_temp.gif" /></center>'; 
			} 
			en4.core.request.send(new Request.HTML({
				'url' : url,
	      'data' : {
					'format' : 'html',
					'subject' : en4.core.subject.guid,
					 'search' : $('sitepage_music_search_input_text').value,
					 'selectbox' : $('sitepage_music_search_input_selectbox').value,
					 'checkbox' : checkbox_value,
					 'isajax' : '1', 
					 'tab' : '<?php echo $this->content_id ?>'
	       }
	    }), {
				'element' : $('id_' + <?php echo $this->content_id ?>)
			});
		}
	
		function Mypagemusic() 
		{		
			var sitepageMusicSearchCheckbox = '<?php echo $this->checkbox ?>';
			var pageMusicPage = <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber()) ?>;
			var url = en4.core.baseUrl + 'widget/index/mod/sitepagemusic/name/profile-sitepagemusic';
			
		 	if($('sitepage_music_search_input_checkbox') && $('sitepage_music_search_input_checkbox').checked == true) {
		    var checkbox_value = 1;
		  }
			else {
				var checkbox_value = 0;
			}
			if($('sitepagemusic_search') != null) {
				$('sitepagemusic_search').innerHTML = '<center><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepagemusic/externals/images/spinner_temp.gif" /></center>'; 
			}
			en4.core.request.send(new Request.HTML({
			'url' : url,
			'data' : {
				'format' : 'html',
				'subject' : en4.core.subject.guid,
				'search' : $('sitepage_music_search_input_text').value,
				'selectbox' : $('sitepage_music_search_input_selectbox').value,
				'checkbox' : checkbox_value,
				'isajax' : '1',
				'tab' : '<?php echo $this->content_id ?>'
			} 
		 }), {
			'element' : $('id_' + <?php echo $this->content_id ?>)
		 });
		}
	
	  var paginatePageMusics = function(page) 
	  {
	    var url = en4.core.baseUrl + 'widget/index/mod/sitepagemusic/name/profile-sitepagemusic';
		 	if($('sitepage_music_search_input_checkbox') && $('sitepage_music_search_input_checkbox').checked == true) {
		    var checkbox_value = 1;
		  }
			else {
				var checkbox_value = 0;
			}
	
	    en4.core.request.send(new Request.HTML({
	      'url' : url,
	      'data' : {
	        'format' : 'html',
	        'subject' : en4.core.subject.guid,
	        'search' : sitepageMusicSearchText,
					'selectbox' : $('sitepage_music_search_input_selectbox').value,
					'checkbox' : checkbox_value,
	        'page' : page,
	        'isajax' : '1',
	        'tab' : '<?php echo $this->content_id ?>'
	      }
	    }), {
	      'element' : $('id_' + <?php echo $this->content_id ?>)
	    });
	  }
	</script>
<?php endif;?>

<?php if (empty($this->isajax)) : ?>
	<div id="id_<?php echo $this->content_id; ?>">
<?php endif;?>


<?php if (!empty($this->show_content)) : ?>
	<?php if($this->showtoptitle == 1):?>
		<div class="layout_simple_head" id="layout_music">			
      <?php echo $this->translate($this->sitepageSubject->getTitle());?><?php echo $this->translate("'s Music");?>
		</div>
	<?php endif; ?>
	<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.admusicwidget', 3)  && $page_communityad_integration && Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepageSubject)):?>
	<div class="layout_right" id="communityad_music">

		<?php
			echo $this->content()->renderWidget("communityad.ads", array( "itemCount"=>Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.admusicwidget', 3),"loaded_by_ajax"=>1,'widgetId'=>"page_music")); 			 
		?>
	</div>
	<div class="layout_middle">
	<?php endif;?>
	<?php if($this->can_create):?>
		<div class="seaocore_add">	
			<a href='<?php echo $this->url(array('page_id' => $this->sitepageSubject->page_id, 'tab' => $this->identity_temp), 'sitepagemusic_create', true) ?>' class='buttonlink icon_sitepagemusic_new'><?php echo $this->translate('Upload Music');?></a>
		</div>
	<?php  endif; ?>
	<?php if( $this->paginator->count() <= 0 && (empty($this->search) && empty($this->checkbox) && empty($this->selectbox))): ?>
		<div class="sitepage_list_filters" style="display:none;">
	<?php else: ?>
		<div class="sitepage_list_filters">
	<?php endif; ?>
	<?php if(!empty($this->viewer_id)):?>
		<div class="sitepage_list_filter_first">
			<?php if($this->checkbox != 1): ?>
				<input id="sitepage_music_search_input_checkbox" type="checkbox" value="1" onclick="Mypagemusic();" /><?php echo $this->translate("Show my music");?>
			<?php else: ?>
				<input id="sitepage_music_search_input_checkbox" type="checkbox" value="2"  checked = "checked" onclick="Mypagemusic();" /><?php echo $this->translate("Show my music");?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
		<div class="sitepage_list_filter_field">
			<?php echo $this->translate("Search:");?>
			<input id="sitepage_music_search_input_text" type="text" value="<?php echo $this->search; ?>" />
	  </div>
		<div class="sitepage_list_filter_field">
			<?php echo $this->translate('Browse by:');?>	
			<select name="default_visibility" id="sitepage_music_search_input_selectbox" onchange = "Ordermusicelect()">
				<?php if($this->selectbox == 'creation_date'): ?>
					<option value="creation_date" selected='selected'><?php echo $this->translate("Most Recent"); ?></option>
				<?php else:?>
					<option value="creation_date"><?php echo $this->translate("Most Recent"); ?></option>
				<?php endif;?>
				<?php if($this->selectbox == 'comment_count'): ?>
					<option value="comment_count" selected='selected'><?php echo $this->translate("Most Commented"); ?></option>
				<?php else:?>
					<option value="comment_count"><?php echo $this->translate("Most Commented"); ?></option>
				<?php endif;?>		
				<?php if($this->selectbox == 'view_count'): ?>
					<option value="view_count" selected='selected'><?php echo $this->translate("Most Viewed"); ?></option>
				<?php else:?>
					<option value="view_count"><?php echo $this->translate("Most Viewed"); ?></option>
				<?php endif;?>		
			  <?php if($this->selectbox == 'play_count'): ?>
					<option value="play_count" selected='selected'><?php echo $this->translate("Most Popular"); ?></option>
				<?php else:?>
					<option value="play_count"><?php echo $this->translate("Most Popular"); ?></option>
				<?php endif;?>
			  <?php if($this->selectbox == 'like_count'): ?>
					<option value="like_count" selected='selected'><?php echo $this->translate("Most Liked"); ?></option>
				<?php else:?>
					<option value="like_count"><?php echo $this->translate("Most Liked"); ?></option>
				<?php endif;?>	
        <?php if($this->selectbox == 'featured'): ?>
					<option value="featured" selected='selected'><?php echo $this->translate("Featured"); ?></option>
				<?php else:?>
					<option value="featured"><?php echo $this->translate("Featured"); ?></option>
				<?php endif;?>			
			</select>
		</div>
	</div>
	<div id= 'sitepagemusic_search'>
	<?php if( count($this->paginator) > 0 ):  ?>
	<ul class="sitepage_profile_list" >
  <?php foreach ($this->paginator as $playlist): ?>
  <?php if($playlist->owner_id != $this->viewer_id): ?>
				<li id="sitepagemusic-item-<?php echo $playlist->playlist_id ?>">
			  <?php else: ?>
				<li id="sitepagemusic-item-<?php echo $playlist->playlist_id ?>" class="sitepage_profile_list_owner">
				<?php endif; ?>
	<div class="sitepage_profile_list_options">
	<?php if($playlist->owner_id == $this->viewer_id || $this->can_edit == 1):
      echo $this->htmlLink($playlist->getHref(array('route' => 'sitepagemusic_playlist_specific', 'action' => 'edit', 'page_id' => $this->sitepageSubject->page_id)),
        $this->translate('Edit Playlist'),
        array('class'=>'buttonlink icon_sitepagemusic_edit'
      )) ?>
    <?php 
      echo $this->htmlLink($playlist->getHref(array('route' => 'sitepagemusic_playlist_specific', 'action' => 'delete', 'page_id' => $this->sitepageSubject->page_id)),
        $this->translate('Delete Playlist'),
        array('class'=>'buttonlink smoothbox icon_sitepagemusic_delete','onclick' => 'deletemusic(this);return false'
      )) ?>     
     <?php endif; ?>
     <?php if($playlist->owner_id == $this->viewer_id || $this->can_edit == 1):?>
		 <?php      echo $this->htmlLink($playlist->getHref(array('route' => 'sitepagemusic_playlist_specific', 'action' => 'set-profile')),
		        $this->translate($playlist->profile ? 'Disable from Page Profile' : 'Play on Page Profile'),
		        array(
		          'class' => 'buttonlink sitepagemusic_set_profile_playlist ' . ( $playlist->profile ? 'icon_sitepagemusic_disableonprofile' : 'icon_sitepagemusic_playonprofile' )
		        )
		      ) ?>
	    <?php endif;  ?>  
      <?php if(($this->allowView) && $this->canMakeFeatured):?>
			<?php if($playlist->featured == 1) echo $this->htmlLink(array('route' => 'sitepagemusic_featured', 'playlist_id' => $playlist->playlist_id,'tab'=>$this->identity_temp), $this->translate('Make Un-featured'), array(
				'onclick' => 'owner(this);return false', ' class' => 'buttonlink seaocore_icon_unfeatured')) ?>
			<?php if($playlist->featured == 0) echo $this->htmlLink(array('route' => 'sitepagemusic_featured', 'playlist_id' => $playlist->playlist_id,'tab'=>$this->identity_temp), $this->translate('Make Featured'), array(
				'onclick' => 'owner(this);return false',' class' => 'buttonlink seaocore_icon_featured')) ?>
		<?php endif;?>
     </div>
	  <div class="sitepage_profile_list_info">
	    <div class="sitepage_profile_list_title">
      <span>
				<?php if($playlist->featured == 1): ?>
					<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/featured.gif', '', array('class' => 'icon', 'title' => $this->translate('Featured'))) ?>
				<?php endif;?>
		  </span>
	    <?php echo $this->htmlLink($playlist->getHref(), $playlist->getTitle()) ?>
	    </div>
			<div class="sitepage_profile_list_info_date">
		    <?php echo $this->translate('Created %s by ', $this->timestamp($playlist->creation_date)) ?>
		   
		    <?php echo $this->htmlLink($playlist->getOwner(), $playlist->getOwner()->getTitle()) ?>
		    -
		    <?php echo $this->translate(array('%s comment', '%s comments', $playlist->comment_count), $this->locale()->toNumber($playlist->comment_count)) ?>
		    
		    -
		    <?php echo $this->translate(array('%s like', '%s likes', $playlist->like_count), $this->locale()->toNumber($playlist->like_count))?>

	  	</div>
	  	<div class="sitepage_profile_list_info_des">
	  	<?php echo $playlist->description ?>
	  	<?php echo $this->partial('application/modules/Sitepagemusic/views/scripts/_Player.tpl', array('playlist' => $playlist, 'hideLinks' => true, 'page_id' => $this->sitepageSubject->page_id, 'can_edit' => $this->can_edit)) ?>  	
	  	</div>
	  	<div class="sitepagemusic_playlist_info_date">
	  	<?php echo $this->translate(array('%s play', '%s plays', $playlist->play_count), $this->locale()->toNumber($playlist->play_count)) ?>
	  	
      -
      <?php echo $this->translate(array('%s view', '%s views', $playlist->view_count), $this->locale()->toNumber($playlist->view_count)) ?>
		 </div>
	  </div>
	  </li>
  <?php endforeach; ?>
</ul>
<?php if( $this->paginator->count() > 1 ): ?>
    <div>
      <?php if( $this->paginator->getCurrentPageNumber() > 1 ): ?>
        <div id="user_group_members_previous" class="paginator_previous">
          <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Previous'), array(
            'onclick' => 'paginatePageMusics(pageMusicPage - 1)',
            'class' => 'buttonlink icon_previous'
          )); ?>
        </div>
      <?php endif; ?>
      <?php if( $this->paginator->getCurrentPageNumber() < $this->paginator->count() ): ?>
        <div id="user_group_members_next" class="paginator_next">
          <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Next') , array(
            'onclick' => 'paginatePageMusics(pageMusicPage + 1)',
            'class' => 'buttonlink_right icon_next'
          )); ?>
        </div>
      <?php endif; ?>
    </div>
<?php endif; ?>
<?php elseif($this->paginator->count() <= 0 && ($this->search != '' || $this->checkbox == 1 || $this->selectbox == 'view_count' ||  $this->selectbox == 'comment_count' ||  $this->selectbox == 'like_count' ||  $this->selectbox == 'creation_date')):?>	
		<div class="tip" id='sitepagemusic_search'>
			<span>
				<?php echo $this->translate('No music were found matching your search criteria.');?>
			</span>
		</div>
	<?php else: ?>	
		<div class="tip" id='sitepagemusic_search'>
		<span>
			<?php echo $this->translate('No music has been posted in this Page yet.'); ?>
			<?php if ($this->can_create):  ?>
				<?php echo $this->translate('Be the first to %1$spost%2$s one!', '<a href="'.$this->url(array('page_id' => $this->sitepageSubject->page_id, 'tab' => $this->identity_temp), 'sitepagemusic_create').'">', '</a>'); ?>
			<?php endif; ?>
		</span>
		</div>	
<?php endif; ?>
</div>
<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.admusicwidget', 3) && $page_communityad_integration && Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepageSubject)):?>
		</div>
	<?php endif; ?>
<?php endif;?>
<?php if (empty($this->isajax)) : ?>
	</div>
<?php endif;?>

<script type="text/javascript">
  var music_ads_display = '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.admusicwidget', 3);?>';
  var adwithoutpackage = '<?php echo Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepageSubject) ?>';
	var is_ajax_divhide = '<?php echo $this->isajax;?>';
	var execute_Request_Music = '<?php echo $this->show_content;?>';		
	var MusictabId = '<?php echo $this->module_tabid;?>';
  var show_widgets = '<?php echo $this->widgets ?>';
	var MusicTabIdCurrent = '<?php echo $this->identity_temp; ?>';
	var page_communityad_integration = '<?php echo $page_communityad_integration; ?>';
	if (MusicTabIdCurrent == MusictabId) {
	  if(page_showtitle != 0) {		 	 	
 	 	  if($('profile_status') && show_widgets == 1) {
	 	    $('profile_status').innerHTML = "<h2><?php echo $this->string()->escapeJavascript($this->sitepageSubject->getTitle())?><?php echo $this->translate(' &raquo; ');?><?php echo $this->translate('Music');?></h2>";	
 	 	  }
 	 	  if($('layout_music')) {
			 $('layout_music').style.display = 'block';
		  }
    } 
     
    hideWidgetsForModule('sitepagemusic');
    prev_tab_id = '<?php echo $this->content_id; ?>';
    prev_tab_class = 'layout_sitepagemusic_profile_sitepagemusic';   
    execute_Request_Music = true;
    hideLeftContainer (music_ads_display, page_communityad_integration, adwithoutpackage);
  }	 
  else if (is_ajax_divhide != 1) {  	
  	if($('global_content').getElement('.layout_sitepagemusic_profile_sitepagemusic')) {
			$('global_content').getElement('.layout_sitepagemusic_profile_sitepagemusic').style.display = 'none';
	  }	
	}
	
	$$('.tab_<?php echo $this->identity_temp; ?>').addEvent('click', function() {
		$('global_content').getElement('.layout_sitepagemusic_profile_sitepagemusic').style.display = 'block';
	 	if(page_showtitle != 0) {
	 		if($('profile_status') && show_widgets == 1) {
	 	    $('profile_status').innerHTML = "<h2><?php echo $this->string()->escapeJavascript($this->sitepageSubject->getTitle())?><?php echo $this->translate(' &raquo; ');?><?php echo $this->translate('Music');?></h2>";	
	 		}
	  } 	  
    hideWidgetsForModule('sitepagemusic');
    $('id_' + <?php echo $this->content_id ?>).style.display = "block";
    if ($('id_' + prev_tab_id) != null && prev_tab_id != 0 && prev_tab_id != '<?php echo $this->content_id; ?>') {
      $$('.'+ prev_tab_class).hide();
    }
		if (prev_tab_id != '<?php echo $this->content_id; ?>') {
			execute_Request_Music = false;
			prev_tab_id = '<?php echo $this->content_id; ?>';
			prev_tab_class = 'layout_sitepagemusic_profile_sitepagemusic';       		
		}
		if(execute_Request_Music == false) {		
			ShowContent('<?php echo $this->content_id; ?>', execute_Request_Music, '<?php echo $this->identity_temp?>', 'music', 'sitepagemusic', 'profile-sitepagemusic', page_showtitle, 'null', music_ads_display, page_communityad_integration, adwithoutpackage);
			execute_Request_Music = true;    		
		} 
		if('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1);?>' && music_ads_display == 0)
{setLeftLayoutForPage();}			  			    
	}); 
</script>
