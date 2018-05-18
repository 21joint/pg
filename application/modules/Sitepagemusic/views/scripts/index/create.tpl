<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Music
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: create.tpl 7244 2010-09-01 01:49:53Z john $
 * @author     Steve
 */
?>
<?php 
  if(file_exists(APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/Adintegration.tpl'))
    include APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/Adintegration.tpl';
?>
<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/payment_navigation_views.tpl'; ?>

<div class="sitepage_viewpages_head">
  <?php echo $this->htmlLink($this->sitepage->getHref(), $this->itemPhoto($this->sitepage, 'thumb.icon', '', array('align' => 'left'))) ?>
  <?php if(!empty($this->can_edit)):?>
		<div class="fright">
			<a href='<?php echo $this->url(array('page_id' => $this->sitepage->page_id), 'sitepage_edit', true) ?>' class='buttonlink icon_sitepages_dashboard'><?php echo $this->translate('Dashboard');?></a>
		</div>
	<?php endif;?>
  <h2>	
    <?php echo $this->sitepage->__toString(); ?>	
    <?php echo $this->translate('&raquo; '); ?>
    <?php echo $this->htmlLink($this->sitepage->getHref(array('tab'=>$this->tab_selected_id)), $this->translate('Music')) ?>
  </h2>
</div>
<?php if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.admusiccreate', 3) && $page_communityad_integration && Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepage)): ?>
  <div class="layout_right" id="communityad_musiccreate">

	<?php
				echo $this->content()->renderWidget("communityad.ads", array( "itemCount"=>Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.admusiccreate', 3),"loaded_by_ajax"=>0,'widgetId'=>"page_musiccreate")); 			 
			?>
  </div>
<?php endif; ?>
<div class="layout_middle">
	<div class='global_form'>
	  <?php echo $this->form->render($this) ?>
	</div>
</div>

<script type="text/javascript">
var playlist_id = <?php echo $this->playlist_id ?>;
function updateTextFields() {
  if ($('playlist_id').selectedIndex > 0) {
    $('title-wrapper').hide();
    $('description-wrapper').hide();
    $('search-wrapper').hide();
  } else {
    $('title-wrapper').show();
    $('description-wrapper').show();
    $('search-wrapper').show();
  }
}
// populate field if playlist_id is specified
if (playlist_id > 0) {
  $$('#playlist_id option').each(function(el, index) {
    if (el.value == playlist_id)
      $('playlist_id').selectedIndex = index;
  });
  updateTextFields();
}
</script>