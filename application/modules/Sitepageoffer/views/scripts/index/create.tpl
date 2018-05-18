<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: create.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php 
  include APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/Adintegration.tpl';
?>

<?php 
$this->headScript()
    ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitepageoffer/externals/scripts/ajaxupload.js');
$this->headLink()
        ->appendStylesheet($this->layout()->staticBaseUrl
			. 'application/modules/Sitepageoffer/externals/styles/style_sitepageoffer.css');
?>

<?php $this->offer_page = 0;?>
<?php if(empty($this->offer_page)):?>
	<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/payment_navigation_views.tpl'; ?>
	<div class="sitepage_viewpages_head">
		<?php echo $this->htmlLink($this->sitepage->getHref(), $this->itemPhoto($this->sitepage, 'thumb.icon', '', array('align' => 'left'))) ?>
    <?php if(!empty($this->can_edit) && empty($this->offer_page)):?>
      <div class="fright">
				<a href='<?php echo $this->url(array('page_id' => $this->sitepage->page_id), 'sitepage_edit', true) ?>' class='buttonlink icon_sitepages_dashboard'><?php echo $this->translate('Dashboard');?></a>
      </div>
	  <?php endif;?>
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

  function post () {
	document.getElementById('submit_form').submit();
  }

  function imageupload () {

    $('imageName').value='';
		$('imageenable').value=0;
		$m('photo').innerHTML='';
		$m('photo_id_filepath').value='';
		
		if($('validation_image')){
			document.getElementById("image-element").removeChild($('validation_image'));
		}
		form = $m('submit_form');

		var  url_action= '<?php echo $this->url(array('module' => 'sitepageoffer', 'controller' => 'index', 'action' => 'upload'), 'default', true) ?>';

		ajaxUpload(form,
		url_action,'photo',
		'<center><img src=\"<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepageoffer/externals/images/loader.gif\" border=\'0\' />','');
		
		$m("loading_image").style.display="block";
		$m("loading_image").innerHTML='<img src=\"<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepageoffer/externals/images/loader.gif\" border=\'0\' /> ' + '<?php echo $this->string()->escapeJavascript($this->translate("Uploading image...")) ?>';
		
		$m('photo').style.visibility="Hidden";
  }

  function showdetail() {

		var validationFlage=0;
		if ($('title').value == '')
		{
			if(!$('validation_offer_description')){
				var div_title_name = document.getElementById("title-element");
				var myElement = new Element("p");
				myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate("* Please enter a title.")) ?>';
				myElement.addClass("error");
				myElement.id = "validation_offer_title";
				div_title_name.appendChild(myElement);
				validationFlage=1;
			}
		}

		var str = $('coupon_code').value;
    if(str != '') {
			if (!str.match(/^[a-zA-Z0-9-_ ]+$/g))
			{
				if(!$('validation_offer_description')){
					var div_title_name = document.getElementById("coupon_code-element");
					var myElement = new Element("p");
					myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate("* Please enter valid coupon code.")) ?>';
					myElement.addClass("error");
					myElement.id = "validation_offer_code";
					div_title_name.appendChild(myElement);
					validationFlage=1;
				}
			}
    }

		if ($('description').value == '')
		{
			if(!$('validation_offer_description')){
				var div_description_name = document.getElementById("description-element");
				var myElement = new Element("p");
				myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate("* Please enter description.")) ?>';
				myElement.addClass("error");
				myElement.id = "validation_offer_description";
				div_description_name.appendChild(myElement);
				validationFlage=1;
			}
		}

		if ($('claim_count').value == '')
		{
			if(!$('validation_offer_claim')){
				var div_claim_name = document.getElementById("claim_count-element");
				var myElement = new Element("p");
				myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate("* Please enter a claims count.")) ?>';
				myElement.addClass("error");
				myElement.id = "validation_offer_claim";
				div_claim_name.appendChild(myElement);
				validationFlage=1;
			}
		}

		if(validationFlage == 1){
			return false;
		}
		
		var title = $('title').value;
		var description = $('description').value;
		var claims = $('claim_count').value;
		
		var url = en4.core.baseUrl + 'sitepageoffer/index/preview';
		Smoothbox.open(url);
  }

  en4.core.runonce.add(function(){

    // check end date and make it the same date if it's too
    cal_end_time.calendars[0].start = new Date( $('end_time-date').value );
    // redraw calendar
    cal_end_time.navigate(cal_end_time.calendars[0], 'm', 1);
    cal_end_time.navigate(cal_end_time.calendars[0], 'm', -1);

  });

  window.addEvent('domready', function() {
   
    if($('end_settings-1').checked) {
      document.getElementById("end_time-wrapper").style.display = "block";
    }
   
  });
  // -->
</script>


<script type="text/javascript">

  var endsettingss = 0;
  
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