<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http:// www.socialengineaddons.com/license/
 * @version    $Id: view.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

// this is done to make these links more uniform with other viewscripts
$playlist = $this->playlist;
$songs    = $playlist->getSongs();
$can_edit = $this->can_edit;
?>
<?php 
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';

	$this->headLink()
  ->appendStylesheet($this->layout()->staticBaseUrl
    . 'application/modules/Sitepagemusic/externals/styles/style_sitepagemusic.css');
// ?>

<?php if (!$this->popout): ?>
	<div class="sitepage_viewpages_head">
		<?php echo $this->htmlLink($this->sitepage->getHref(), $this->itemPhoto($this->sitepage, 'thumb.icon', '', array('align' => 'left'))) ?>
		<h2>
		  <?php echo $this->sitepage->__toString() ?>
		  <?php echo $this->translate('&raquo; ');?>
		  <?php echo $this->htmlLink($this->sitepage->getHref(array('tab'=>$this->tab_selected_id)), $this->translate('Music')) ?>
		  <?php echo $this->translate('&raquo; ');?>
		 	<?php echo $playlist->getTitle() ?>
		</h2>
	</div> 
<?php else:?>
	<h3><?php echo $playlist->getTitle() ?>	</h3>
<?php endif; ?>

<div class="layout_middle">
	<?php if ($this->popout): ?>
	<?php $this->headTitle($playlist->getTitle(), Zend_View_Helper_Placeholder_Container_Abstract::SET) ?>
	  <div class="sitepagemusic_playlist_popout_wrapper">
	    <div class="sitepagemusic_playlist_info_date">
	    	<?php echo $this->translate('Created by '), $this->htmlLink($playlist->getOwner(), $playlist->getOwner()->getTitle()) ?>
	    </div>
	    <?php echo $this->partial('_Player.tpl', array('playlist'=> $this->playlist, 'popout'=>true)) ?>
	  </div>
	<?php return; endif; ?>
	<div class="sitepagemusic_playlist" id="music_playlist_item_<?php echo $playlist->getIdentity() ?>">
	  <div class="sitepagemusic_playlist_info">
	    <div class="sitepagemusic_playlist_info_title">
	    	<h3><?php echo $playlist->getTitle() ?></h3>
	    </div>
	    <div class="sitepagemusic_playlist_info_date">
	      <?php echo $this->translate('Created by ') ?>
	      <?php echo $this->htmlLink($playlist->getOwner(), $playlist->getOwner()->getTitle()) ?>
	    </div>
	     <div class="sitepagemusic_playlist_info_des">
	      <?php echo $playlist->description ?>
        
	       <!--FACEBOOK LIKE BUTTON START HERE-->
         <?php  $fbmodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('facebookse');
        if (!empty ($fbmodule)) :
          $enable_facebookse = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('facebookse'); 
          if (!empty ($enable_facebookse) && !empty($fbmodule->version)) :
            $fbversion = $fbmodule->version; 
            if (!empty($fbversion) && ($fbversion >= '4.1.5')) { ?>
               <div class="sitepagemusic_fb_like">
                  <script type="text/javascript">
                    var fblike_moduletype = 'sitepagemusic_playlist';
		                var fblike_moduletype_id = '<?php echo $playlist->playlist_id ?>';
                  </script>
                  <?php echo '<br />' . Engine_Api::_()->facebookse()->isValidFbLike() . '<br />'; ?>
                </div>
            
            <?php } ?>
          <?php endif; ?>
     <?php endif; ?> 
	    </div>
	
	 		<?php echo $this->partial('_Player.tpl', array('playlist'=>$playlist, 'page_id' => $this->sitepage->page_id, 'can_edit' => $can_edit)) ?>
	 		<div class="sitepagemusic_playlist_info_date">
	    <?php echo $this->translate('Created %s ', $this->timestamp($playlist->creation_date)) ?>
	    -
      <?php echo $this->translate(array('%s play', '%s plays', $playlist->play_count), $this->locale()->toNumber($playlist->play_count)) ?>
      -
      <?php echo $this->translate(array('%s view', '%s views', $playlist->view_count), $this->locale()->toNumber($playlist->view_count)) ?>
      -
			<?php echo $this->translate(array('%s like', '%s likes', $playlist->like_count ), $this->locale()->toNumber($playlist->like_count )) ?>	 
			</div>
	    <div class="sitepagemusic_playlist_options" id="sitepagemusic-item-<?php echo $playlist->getIdentity() ?>">
				<?php if($this->can_create):?>
					<a href='<?php echo $this->url(array('page_id' => $playlist->page_id, 'tab' => $this->tab_selected_id), 'sitepagemusic_create', true) ?>' class='buttonlink icon_sitepagemusic_new'><?php echo $this->translate('Upload Music');?></a>
					|
				<?php endif; ?>
	  		 
				<?php if($playlist->owner_id == $this->viewer_id || $can_edit == 1): ?>				
		  	  <?php  echo $this->htmlLink($playlist->getHref(array('route' => 'sitepagemusic_playlist_specific', 'action' => 'edit', 'page_id' => $this->sitepage->page_id)),
		        $this->translate('Edit Playlist'),
		        array('class'=>'buttonlink icon_sitepagemusic_edit'
		      )) ?>
		       |
		    <?php
		      echo $this->htmlLink($playlist->getHref(array('route' => 'sitepagemusic_playlist_specific', 'action' => 'delete', 'page_id' => $this->sitepage->page_id)),
		        $this->translate('Delete Playlist'),
		        array('class'=>'buttonlink smoothbox icon_sitepagemusic_delete'
		      )) ?>	  
					|
			  <?php endif;?>
        <?php if($this->allowView ): ?>
					<?php echo $this->htmlLink(array('route' => 'default','module'=> 'sitepagemusic', 'controller'=>'index','action' => 'add-music-of-day', 'playlist_id' => $playlist->playlist_id, 'format' => 'smoothbox'), $this->translate('Make Music of the Day'), array(
					'class' => 'buttonlink smoothbox icon_sitepagemusic_music'
				)) ?>
					&nbsp; | &nbsp;
		    <?php endif;?>
			  <?php if($playlist->owner_id == $this->viewer_id || $can_edit == 1): ?>	
			    <?php  echo $this->htmlLink($playlist->getHref(array('route' => 'sitepagemusic_playlist_specific', 'action' => 'set-profile')),
			        $this->translate($playlist->profile ? 'Disable from Page Profile' : 'Play on Page Profile'),
			        array(
			          'class' => 'buttonlink sitepagemusic_set_profile_playlist ' . ( $playlist->profile ? 'icon_sitepagemusic_disableonprofile' : 'icon_sitepagemusic_playonprofile' )
			        )
			      )
		      ?> 
					
	      <?php endif;?> 
				<?php if( !empty($this->viewer_id) ): ?>
        |
				<!--  Start: Suggest to Friend link show work -->
				<?php if( !empty($this->musicSuggLink) && !empty($playlist->search) ): ?>		
					<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'suggestion', 'controller' => 'index', 'action' => 'popups', 'sugg_id' => $playlist->playlist_id, 'sugg_type' => 'page_music'), $this->translate('Suggest to Friends'), array(
						'class'=>'smoothbox buttonlink icon_page_friend_suggestion')) ?>
					|
				<?php endif; ?>					
				<!--  End: Suggest to Friend link show work -->
	      <?php echo $this->htmlLink(array(
	        'module'=>'activity',
	        'controller'=>'index',
	        'action'=>'share',
	        'route'=>'default',
	        'type'=>'sitepagemusic_playlist',
	        'id' => $this->playlist->getIdentity(),
	        'format' => 'smoothbox'
	      ), $this->translate("Share"), array('class' => 'smoothbox buttonlink icon_sitepages_share')); ?>
	      |
	      <?php echo $this->htmlLink(array(
	        'module'=>'core',
	        'controller'=>'report',
	        'action'=>'create',
	        'route'=>'default',
	        'subject'=>$this->playlist->getGuid(),
	        'format' => 'smoothbox'
	      ), $this->translate("Report"), array('class' => 'smoothbox buttonlink icon_sitepages_report')); ?>	
				<?php endif;?> 
	  	</div> 
		  <?php 
        include_once APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/_listComment.tpl';
    ?>
	  </div>
	</div>
</div>