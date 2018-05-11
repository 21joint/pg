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
$this->headLink()
        ->appendStylesheet($this->layout()->staticBaseUrl
                . 'application/modules/Sitepageoffer/externals/styles/style_sitepageoffer.css');
?>
<?php if(empty($this->offer_page)):?>
	<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/payment_navigation_views.tpl'; ?>
	<div class="sitepage_viewpages_head">
		 <?php echo $this->htmlLink($this->sitepage->getHref(), $this->itemPhoto($this->sitepage, 'thumb.icon', '', array('align' => 'left'))) ?>
		<h2>	
			<?php echo $this->sitepage->__toString() ?>	
			<?php echo $this->translate('&raquo; '); ?>
      <?php echo $this->htmlLink($this->sitepage->getHref(array('tab'=>$this->tab_selected_id)), $this->translate('Offers')) ?>
		</h2>
	</div>
<?php endif;?>

<div class="clr">
  <?php echo $this->form->render($this) ?>
</div>

<?php
	$this->headScript()
					->appendFile($this->layout()->staticBaseUrl . 'externals/calendar/calendar.compat.js');
	$this->headLink()
        ->appendStylesheet($this->layout()->staticBaseUrl . 'externals/calendar/styles.css');
?>

<script type="text/javascript">
  //<!--

  en4.core.runonce.add(function(){

    // check end date and make it the same date if it's too
    cal_end_time.calendars[0].start = new Date( $('end_time-date').value );
    // redraw calendar
    cal_end_time.navigate(cal_end_time.calendars[0], 'm', 1);
    cal_end_time.navigate(cal_end_time.calendars[0], 'm', -1);

  });

  // -->
</script>


<script type="text/javascript">

  var myCalStart = false;
  var myCalEnd = false;

  var endsettingss = '<?php echo $this->sitepageoffer->end_settings;?>';
  
  function updateTextFields(value) {
		if (value == 0)
    {
      if($("end_time-wrapper"))
      $("end_time-wrapper").style.display = "none";
    } else if (value == 1)
    { if($("end_time-wrapper"))
      $("end_time-wrapper").style.display = "block";
    }
  }

  en4.core.runonce.add(updateTextFields(endsettingss));

</script>