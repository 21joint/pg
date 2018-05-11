<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: edit.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php 
$breadcrumb = array(
    array("href"=>$this->sitepage->getHref(),"title"=>$this->sitepage->getTitle(),"icon"=>"arrow-r"),
    array("href"=>$this->sitepage->getHref(array('tab' => $this->tab_selected_id)),"title"=>"Offers","icon"=>"arrow-d")
   );

echo $this->breadcrumb($breadcrumb);
?>
<?php
$this->headLink()
        ->appendStylesheet($this->layout()->staticBaseUrl
                . 'application/modules/Sitepageoffer/externals/styles/style_sitepageoffer.css');
?>

<div class="clr">
  <?php echo $this->form->render($this) ?>
</div>

<script type="text/javascript">

	sm4.core.runonce.add(function() { 
    if (DetectAllWindowsMobile()) {
      $.mobile.activePage.find('#dummy-wrapper').css('display', 'block');
      $.mobile.activePage.find('#photo-wrapper').css('display', 'none');
    } else {
      $.mobile.activePage.find('#photo-wrapper').css('display', 'block');
      $.mobile.activePage.find('#dummy-wrapper').css('display', 'none');
    } 
  });

  var endsettingss = '<?php echo $this->sitepageoffer->end_settings;?>';
  
  function updateTextFields(value) {
		if (value == 0)
    {
      if($("#end_time-wrapper"))
      $("#end_time-wrapper").css("display","none");
    } else if (value == 1)
    { if($("#end_time-wrapper"))
      $("#end_time-wrapper").css("display","block");
    }
  }

  sm4.core.runonce.add(updateTextFields(endsettingss));

</script>