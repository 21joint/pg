<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: preview.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php 
	$this->headLink()
	  ->appendStylesheet($this->layout()->staticBaseUrl
	    . 'application/modules/Sitepageoffer/externals/styles/style_sitepageoffer.css');
?>

<div class="sitepage_offer_priview_popup">      
	<div class="sitepage_offer_priview_popup_head">
		<b><?php echo $this->translate('Preview') ?></b>
	</div>
	<div class="sitepage_offer_block">
		<div class="sitepage_offer_photo">
			<img src='<?php echo $this->image_path;?>' alt='' />
		</div>
		<div class="sitepage_offer_details">
			<div id='title' class="sitepage_offer_title"></div>
      <div id='date' class="sitepage_offer_stats"></div>
      <div id='coupon_code' class="sitepage_offer_stats"></div>
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)): ?>
        <div class="sitepage_offer_date seaocore_txt_light">
          <span>
            <a href='javascript:void(0);'>
              <img src="<?php echo $this->layout()->staticBaseUrl?>application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />	
              <?php echo $this->translate('Get Offer');?>
            </a>
          </span>	
          <span><b>&middot;</b></span><span id="claim"></span>
        </div>		
      <?php endif; ?>
		</div>		
	</div>
	<button onclick="window.parent.post()" type="submit_button"><?php echo $this->translate("Post");?></button>&nbsp;&nbsp;or&nbsp;
	<a href="#" data-rel="back" data-role="button">
          <?php echo $this->translate('Edit') ?>
        </a>
</div>	

<script type="text/javascript">
  
  var mydate = new Date(window.parent.$('#calendar_output_span_end_time-date').html);

  var day = mydate.getDate();

	var month = ["January", "February", "March", "April", "May", "June",
	"July", "August", "September", "October", "November", "December"][mydate.getMonth()];
	var str = day + ' ' + month + ' ' + mydate.getFullYear();
  var url = window.parent.$('url').value;
  var myTruncatedUrl = url.substring(0,30);
  var end_date = window.parent.$('end_settings-0').checked;
  var final_url = '<a href = "' + url + '" target="_blank" title= "' + url + '" >' +  myTruncatedUrl + '</a>';

  if(window.parent.$('#url').value) {
		if (end_date) {
			$('#date').html = '<?php echo $this->string()->escapeJavascript($this->translate('End date: Never Expires'));?>' + ' ' + '|' + ' ' + 'URL:' + ' ' + final_url;
		}
		else{
			$('#date').html = '<?php echo $this->string()->escapeJavascript($this->translate('End date:'));?>' + ' ' + str + ' ' + '|' + ' ' + 'URL:' + ' ' +final_url;
		}
  }
  else {
		if(end_date) {
			$('#date').html = '<?php echo $this->string()->escapeJavascript($this->translate('End date: Never Expires'));?>';
		}
		else {
			$('#date').html = '<?php echo $this->string()->escapeJavascript($this->translate('End date:'));?>' + ' ' + str;
		}
  }
  
  $('#title').html = window.parent.$('#title').value;
  <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)): ?>
    if(window.parent.$('#claim_count').value == 0) {
      $('#claim').html = '<?php echo $this->string()->escapeJavascript($this->translate('Unlimited claims'));?>';
    }
    else {
      if(window.parent.$('#claim_count').value > 1) {
        $('#claim').html = window.parent.$('#claim_count').value + ' ' + '<?php echo $this->string()->escapeJavascript($this->translate('#Claims left'));?>';
      }
      else {   
        $('#claim').html = window.parent.$('#claim_count').value + ' ' + '<?php echo $this->string()->escapeJavascript($this->translate('#Claims left'));?>';
      }
    }
  <?php endif; ?>

  var coupon_code = window.parent.$('#coupon_code').value;
  if(coupon_code != '') {
    $('#coupon_code').html = '<?php echo $this->string()->escapeJavascript($this->translate('Coupon Code:'));?>'+ ' ' + window.parent.$('#coupon_code').value;
  }

</script>