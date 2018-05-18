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
<?php if($this->paginator->getTotalItemCount()):?>
  <form id='filter_form_page' class='global_form_box' method='get' action='<?php echo $this->url(array(), 'sitepagemusic_browse', true) ?>' style='display: none;'>
    <input type="hidden" id="page" name="page"  value=""/>
  </form>
<ul class="seaocore_browse_list">
  <?php foreach ($this->paginator as $playlist): ?>
	<li>
    <?php $this->sitepageSubject = Engine_Api::_()->getItem('sitepage_page', $playlist->page_id);?>
	  <div class="seaocore_browse_list_info">
	    <div class="seaocore_browse_list_info_title">
				<span>
					<?php if (($playlist->price>0)): ?>
						<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/sponsored.png', '', array('class' => 'icon', 'title' => $this->translate('Sponsored'))) ?>
					<?php endif; ?>
          <?php if ($playlist->featured == 1): ?>
						<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/featured.png', '', array('class' => 'icon', 'title' => $this->translate('Featured'))) ?>
					<?php endif; ?>
				</span>
	    	<h3><?php echo $this->htmlLink($playlist->getHref(), $playlist->getTitle(),array('title'=> $playlist->getTitle())) ?></h3>
	    </div>
      <div class="seaocore_browse_list_info_date">
				<?php echo $this->translate("in ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($playlist->page_id, $playlist->owner_id, $playlist->getSlug()),  $playlist->page_title,array('title'=>$playlist->page_title)) ?>
			</div>
			<div class="seaocore_browse_list_info_date">
		    <?php echo $this->translate('Created %s by ', $this->timestamp($playlist->creation_date)) ?>
		   
		    <?php echo $this->htmlLink($playlist->getOwner(), $playlist->getOwner()->getTitle(),array('title'=> $playlist->getOwner()->getTitle())) ?>,
		    <?php echo $this->translate(array('%s comment', '%s comments', $playlist->comment_count), $this->locale()->toNumber($playlist->comment_count)) ?>,
		    <?php echo $this->translate(array('%s like', '%s likes', $playlist->like_count), $this->locale()->toNumber($playlist->like_count)) ?>

	  	</div>
	  	<div class="seaocore_browse_list_info_blurb">
	  	<?php echo $this->viewMore($playlist->description); ?><br />
	  	<?php echo $this->partial('application/modules/Sitepagemusic/views/scripts/_Player.tpl', array('playlist' => $playlist, 'hideLinks' => true, 'page_id' => $this->sitepageSubject->page_id, 'can_edit' => $this->can_edit)) ?>  	
	  	</div>
	  	<div class="seaocore_browse_list_info_date">
	  	<?php echo $this->translate(array('%s play', '%s plays', $playlist->play_count), $this->locale()->toNumber($playlist->play_count)) ?>,
      <?php echo $this->translate(array('%s view', '%s views', $playlist->view_count), $this->locale()->toNumber($playlist->view_count)) ?>
		 </div>
	  </div>
	  </li>
  <?php endforeach; ?>
</ul>
<?php echo $this->paginationControl($this->paginator, null, array("pagination/pagination.tpl", "sitepagemusic"), array("orderby" => $this->orderby)); ?>
	</div>
<?php else: ?>
	<div class="tip">
		<span>
			<?php echo $this->translate('There are no search results to display.');?>
		</span>
	</div>
<?php endif;?>


<script type="text/javascript">
  var pageAction = function(page){
     var form;
     if($('filter_form')) {
       form=document.getElementById('filter_form');
      }else if($('filter_form_page')){
				form=$('filter_form_page');
			}
    form.elements['page'].value = page;
    
		form.submit();
  } 
</script>