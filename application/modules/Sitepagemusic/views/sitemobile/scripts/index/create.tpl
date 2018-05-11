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
<script type="text/javascript">

  sm4.core.runonce.add(function() { 
    if (DetectAllWindowsMobile() || DetectAllIos()) {
      $.mobile.activePage.find('#form-upload-music').css('display', 'none');
      $.mobile.activePage.find('#show_supported_message').css('display', 'block');
    } else {
      $.mobile.activePage.find('#form-upload-music').css('display', 'block');
      $.mobile.activePage.find('#show_supported_message').css('display', 'none');
    } 
  });

</script>
<?php 
$breadcrumb = array(
    array("href"=>$this->sitepage->getHref(),"title"=>$this->sitepage->getTitle(),"icon"=>"arrow-r"),
    array("href"=>$this->sitepage->getHref(array('tab' => $this->tab_selected_id)),"title"=>"Music","icon"=>"arrow-d"));

echo $this->breadcrumb($breadcrumb);
?>
<div class="layout_middle">
	<div class='global_form'>
	  <?php echo $this->form->render($this) ?>
	</div>
</div>

<script type="text/javascript">
var playlist_id = <?php echo $this->playlist_id ?>;
function updateTextFields() {
  if ($('#playlist_id').attr('selectedIndex') > 0) {
    $('#title-wrapper').hide();
    $('#description-wrapper').hide();
    $('#search-wrapper').hide();
  } else {
    $('#title-wrapper').show();
    $('#description-wrapper').show();
    $('#search-wrapper').show();
  }
}
// populate field if playlist_id is specified
if (playlist_id > 0) {
  $('.playlist_id option').each(function(el, index) {
    if (el.val() == playlist_id)
      $('#playlist_id').attr('selectedIndex', index);
  });
  updateTextFields();
}
</script>

<div style="display:none" id="show_supported_message" class='tip'>

  <span><?php echo $this->translate("Sorry, due to copyright laws and Apple restrictions, music cannot be uploaded from your device. You can create a playlist from your Desktop."); ?><span>

</div>